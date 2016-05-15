<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
        <script src="<?php echo JS_URL; ?>socket.io.min.js"></script>
        <script src="<?php echo JS_URL; ?>rmc3.min.js"></script>
    </head>
    <body style="margin:0;">
        <video id='video-preview' style="display:none" controls loop></video>
    </body>
    <script>
        window.parent.document.getElementById('share-Cam').onclick = function() {
            this.disabled = true;
            $("#share-Cam",window.parent.document).attr("class","btn");
            window.parent.document.getElementById('close-Cam').disabled = false;
            $('#close-Cam',window.parent.document).attr("class","btn btn-primary");
            startLiveVideo();
        };
        
        // skipping xirsys servers
        window.getExternalIceServers = false;
        // recording is disabled because it is resulting for browser-crash
        var enableRecordings = false;

        var connection = new RTCMultiConnection(null, {
            useDefaultDevices: true // if we don't need to force selection of specific devices
        });

        // its mandatory in v3
        connection.enableScalableBroadcast = true;

        // each relaying-user should serve only 1 users
        connection.maxRelayLimitPerUser = 1;

        // we don't need to keep room-opened
        // scalable-broadcast.js will handle stuff itself.
        connection.autoCloseEntireSession = true;

        // by default, socket.io server is assumed to be deployed on your own URL
        connection.socketURL = 'https://<?php echo HOST_IP; ?>:9001';
        // comment-out below line if you do not have your own socket.io server
        // connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';
        connection.socketMessageEvent = 'scalable-media-broadcast-demo';

        // document.getElementById('broadcast-id').value = connection.userid;
        // user need to connect server, so that others can reach him.
        connection.connectSocket(function(socket) {
            socket.on('logs', function(log) {
                console.log(log.replace(/</g, '----').replace(/>/g, '___').replace(/----/g, '(<span style="color:red;">').replace(/___/g, '</span>)'));
            });
            // this event is emitted when a broadcast is already created.
            socket.on('join-broadcaster', function(hintsToJoinBroadcast) {
                console.log('join-broadcaster', hintsToJoinBroadcast);
                connection.session = hintsToJoinBroadcast.typeOfStreams;
                connection.sdpConstraints.mandatory = {
                    OfferToReceiveVideo: !!connection.session.video,
                    OfferToReceiveAudio: !!connection.session.audio
                };
                connection.join(hintsToJoinBroadcast.userid);
            });

            socket.on('rejoin-broadcast', function(broadcastId) {
                console.log('rejoin-broadcast', broadcastId);
                connection.attachStreams = [];
                socket.emit('check-broadcast-presence', broadcastId, function(isBroadcastExists) {
                    if(!isBroadcastExists) {
                        // the first person (i.e. real-broadcaster) MUST set his user-id
                        connection.userid = broadcastId;
                    }
                    socket.emit('join-broadcast', {
                        broadcastId: broadcastId,
                        userid: connection.userid,
                        typeOfStreams: connection.session
                    });
                });
            });

            socket.on('broadcast-stopped', function(broadcastId) {
                // alert('Broadcast has been stopped.');
                // location.reload();
                console.error('broadcast-stopped', broadcastId);
                console.log('This broadcast has been stopped.');
            });

            // this event is emitted when a broadcast is absent.
            socket.on('start-broadcasting', function(typeOfStreams) {
                console.log('start-broadcasting', typeOfStreams);
                // host i.e. sender should always use this!
                connection.sdpConstraints.mandatory = {
                    OfferToReceiveVideo: false,
                    OfferToReceiveAudio: false
                };
                connection.session = typeOfStreams;
                // "open" method here will capture media-stream
                // we can skip this function always; it is totally optional here.
                // we can use "connection.getUserMediaHandler" instead
                connection.open(connection.userid);
            });
        });

        var videoPreview = document.getElementById('video-preview');

        connection.onstream = function(event) {
            if(connection.isInitiator && event.type !== 'local') {
                return;
            }
            if(event.mediaElement) {
                event.mediaElement.pause();
                delete event.mediaElement;
            }
            
            //tell student the live video is started. 
            PostVideoMsg();
            
            connection.isUpperUserLeft = false;
            videoPreview.src = URL.createObjectURL(event.stream);
            videoPreview.play();
            videoPreview.userid = event.userid;
            if(event.type === 'local') {
                videoPreview.muted = true;
            }
            
            $('#video-preview').show();
            //调整大小
            scaleVideos();
            if (connection.isInitiator == false && event.type === 'remote') {
                // he is merely relaying the media
                connection.dontCaptureUserMedia = true;
                connection.attachStreams = [event.stream];
                connection.sdpConstraints.mandatory = {
                    OfferToReceiveAudio: false,
                    OfferToReceiveVideo: false
                };
                var socket = connection.getSocket();
                socket.emit('can-relay-broadcast');
                if(connection.DetectRTC.browser.name === 'Chrome') {
                    connection.getAllParticipants().forEach(function(p) {
                        if(p + '' != event.userid + '') {
                            var peer = connection.peers[p].peer;
                            peer.getLocalStreams().forEach(function(localStream) {
                                peer.removeStream(localStream);
                            });
                            peer.addStream(event.stream);
                            connection.dontAttachStream = true;
                            connection.renegotiate(p);
                            connection.dontAttachStream = false;
                        }
                    });
                }
                if(connection.DetectRTC.browser.name === 'Firefox') {
                    // Firefox is NOT supporting removeStream method
                    // that's why using alternative hack.
                    // NOTE: Firefox seems unable to replace-tracks of the remote-media-stream
                    // need to ask all deeper nodes to rejoin
                    connection.getAllParticipants().forEach(function(p) {
                        if(p + '' != event.userid + '') {
                            connection.replaceTrack(event.stream, p);
                        }
                    });
                }
                // Firefox seems UN_ABLE to record remote MediaStream
                // WebAudio solution merely records audio
                // so recording is skipped for Firefox.
                if(connection.DetectRTC.browser.name === 'Chrome') {
                    repeatedlyRecordStream(event.stream);
                }
            }
        };

        // ask node.js server to look for a broadcast
        // if broadcast is available, simply join it. i.e. "join-broadcaster" event should be emitted.
        // if broadcast is absent, simply create it. i.e. "start-broadcasting" event should be fired.
        function startLiveVideo() {
            var broadcastId = 'class<?php echo $classID;?>Cam';
            if (broadcastId.replace(/^\s+|\s+$/g, '').length <= 0) {
                return;
            }
            connection.session = {
                audio: true,
                video: true,
                oneway: true
            };
            var socket = connection.getSocket();
            socket.emit('check-broadcast-presence', broadcastId, function(isBroadcastExists) {
                if(!isBroadcastExists) {
                    // the first person (i.e. real-broadcaster) MUST set his user-id
                    connection.userid = broadcastId;
                }
                console.log('check-broadcast-presence', broadcastId, isBroadcastExists);
                socket.emit('join-broadcast', {
                    broadcastId: broadcastId,
                    userid: connection.userid,
                    typeOfStreams: connection.session
                });
            });
        };

        connection.onstreamended = function() {};

        connection.onleave = function(event) {
            if(event.userid !== videoPreview.userid) return;
            var socket = connection.getSocket();
            socket.emit('can-not-relay-broadcast');
            connection.isUpperUserLeft = true;
            if(allRecordedBlobs.length) {
                // playing lats recorded blob
                var lastBlob = allRecordedBlobs[allRecordedBlobs.length - 1];
                videoPreview.src = URL.createObjectURL(lastBlob);
                videoPreview.play();
                allRecordedBlobs = [];
            }
            else if(connection.currentRecorder) {
                var recorder = connection.currentRecorder;
                connection.currentRecorder = null;
                recorder.stopRecording(function() {
                    if(!connection.isUpperUserLeft) return;

                    videoPreview.src = URL.createObjectURL(recorder.blob);
                    videoPreview.play();
                });
            }
            if(connection.currentRecorder) {
                connection.currentRecorder.stopRecording();
                connection.currentRecorder = null;
            }
        };

        var allRecordedBlobs = [];

        function repeatedlyRecordStream(stream) {
            if(!enableRecordings) {
                return;
            }
            connection.currentRecorder = RecordRTC(stream, {
                type: 'video'
            });
            connection.currentRecorder.startRecording();
            setTimeout(function() {
                if(connection.isUpperUserLeft || !connection.currentRecorder) {
                    return;
                }
                connection.currentRecorder.stopRecording(function() {
                    allRecordedBlobs.push(connection.currentRecorder.blob);
                    if(connection.isUpperUserLeft) {
                        return;
                    }
                    connection.currentRecorder = null;
                    repeatedlyRecordStream(stream);
                });
            }, 30 * 1000); // 30-seconds
        };
        
        function PostVideoMsg(){
            var msg =   "<?php echo $classID;?>onCam";                               
            parent.ws.send(msg);
            parent.timer_cam = setInterval(function() {                           
                parent.ws.send(msg);
            }, 4000);
        };
        function scaleVideos() {
            var videos = document.querySelectorAll('video'),
                length = videos.length,
                video;
            var minus = 60;
            var windowHeight = 440;
            var windowWidth = 440;
            var windowAspectRatio = windowWidth / windowHeight;
            var videoAspectRatio = 16 / 9;
            var blockAspectRatio;
            var tempVideoWidth = 0;
            var maxVideoWidth = 0;

            for (var i = length; i > 0; i--) {
                blockAspectRatio = i * videoAspectRatio / Math.ceil(length / i);
                if (blockAspectRatio <= windowAspectRatio) {
                    tempVideoWidth = videoAspectRatio * windowHeight / Math.ceil(length / i);
                } else {
                    tempVideoWidth = windowWidth / i;
                }
                if (tempVideoWidth > maxVideoWidth)
                    maxVideoWidth = tempVideoWidth;
            }
            for (var i = 0; i < length; i++) {
                video = videos[i];
                if (video)
                    video.width = maxVideoWidth - minus;
            }
        }

        window.onresize = scaleVideos;
    </script>
</html>
