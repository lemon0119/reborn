<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<!--直播begin-->
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>getMediaElement.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>index.css" rel="stylesheet" type="text/css" />
<!--点播 begin-->
<link href="<?php echo CSS_URL; ?>webrtc_style.css" rel="stylesheet" type="text/css"/>
<style>
    .media-container{display: block;margin-bottom: 10px;margin-right: 10px;}//直播框
    /*#aaa{border: 5px solid red;}
    #bbb{border: 5px solid red;}*/
</style>
<!--点播end-->

<!--
<link rel="stylesheet" href="//cdn.webrtc-experiment.com/style.css">
-->

<link rel="stylesheet" href="<?php echo CSS_URL; ?>cdn-style.css">

<style>
    .left{
        border: 1px solid #d4d4d4;
        -webkit-border-radius: 4px;
           -moz-border-radius: 4px;
                border-radius: 4px;
        margin-left:30px;
        width:61%;
        height:600px;
        float:left;
    }
    .right{
        border: 1px solid #d4d4d4;
        -webkit-border-radius: 4px;
           -moz-border-radius: 4px;
                border-radius: 4px;
        margin-right: 0px;
        width:31%;
        height:600px;
        float:right;
    }

    audio,
    video {
        -moz-transition: all 1s ease;
        -ms-transition: all 1s ease;
        -o-transition: all 1s ease;
        -webkit-transition: all 1s ease;
        transition: all 1s ease;
        vertical-align: top;
        width: 100%;
        margin-left:auto; 
        margin-right:auto;
        //margin-top:10px;
        border:1px;
    }

    .bulletin {
        width: 80%;
        margin-left:auto; 
        margin-right:auto;
        //margin-top:10px;
        border:1px;        
    }   

    .chatroom {
        width: 80%;
        height: 300px;
        margin-left:auto; 
        margin-right:auto;
        //margin-top:10px;        
        overflow:auto;
        font-size:15px;
    }
    
    .sendfoot {
        width: 80%;
        margin-left:auto; 
        margin-right:auto;
        //margin-top:10px;        
        overflow:auto;        
    }

    input {
        border: 1px solid #d9d9d9;
        border-radius: 1px;
        font-size: 2em;
        margin: .2em;
        width: 20%;
    }
    select {
        border: 1px solid #d9d9d9;
        border-radius: 1px;
        height: 50px;
        margin-left: 1em;
        margin-right: -12px;
        padding: 1.1em;
        vertical-align: 6px;
    }
    .setup {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
        font-size: 102%;
        height: 47px;
        margin-left: -9px;
        margin-top: 8px;
        position: absolute;
    }
    p {
        padding: 1em;
    }
    #chat-output div,
    #file-progress div {
        border: 1px solid black;
        border-bottom: 0;
        padding: .1em .4em;
    }
    #chat-output,
    #file-progress {
        margin: 0 0 0 .4em;
        max-height: 12em;
        overflow: auto;
    }
    .data-box input {
        border: 1px solid black;
        font-family: inherit;
        font-size: 1em;
        margin: .1em .3em;
        outline: none;
        padding: .1em .2em;
        width: 97%;
    }
</style>


<!--
    sunpy: move these CDNs resources to local server

<script src="//cdn.webrtc-experiment.com/getMediaElement.min.js"></script>
<script src="//cdn.webrtc-experiment.com/RTCMultiConnection.js"></script>
<script src='//cdn.webrtc-experiment.com/firebase.js'></script>

-->


<script src="<?php echo JS_URL; ?>cdn-conference.js"></script>
<script src="<?php echo JS_URL; ?>cdn-getMediaElement.min.js"></script>
<script src='<?php echo JS_URL; ?>cdn-firebase.js'></script>
<script src="<?php echo JS_URL; ?>cdn-RTCMultiConnection.js"></script>


<?php
$username = Yii::app()->user->name;
echo "<script>var current_username=\"$username\";</script>";

$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
?>


<!--自定义css begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
<!--自定义css end-->

                <div class="left">
                    <span style="display:none">
                        <a href="/Pluginfree-Screen-Sharing/" target="_blank" title="Open this link for private screen sharing!">
                            <code style="display:none"><strong id="unique-token">#123456789</strong></code>
                        </a>
                    </span>

                    <!-- sunpy: if teachers -->
                    <?php if ($role == 'teacher') { ?>

                        <div style="display:inline;padding-right:60px;">
                            <button id="share-screen" style="font-size:20px;height:40px">屏幕</button>
                        </div>

                        <div style="display:inline;padding-right:60px;">
                            <!-- sunpy: video start -->
                            <button id="setup-new-broadcast" style="font-size:20px;height:40px">视频</button>                        
                            <button id="teacher-close-camera" style="font-size:20px;height:40px" disabled="true">关闭视频</button>
                        </div>

                        <div style="display:inline;">
                            <!-- sunpy: broadcast local video -->                                 
                            <button id="teacher-dianbo" style="font-size:20px;height:40px">点播</button>
                            <input type="file" placeholder="选择点播文件" id="teacher-choose-file" style="font-size:20px;width:30%"></input>                           
                            <!-- <button id="teacher-stop-dianbo">停止点播</button> -->                        
                        </div>

                        <!-- local/remote videos container -->
                        <div style="padding-top:2px;font-size:15px;margin-left:auto;margin-right:auto;height:10px;width:100%;border:none;">
                            <table style="border-style:dashed;" height=8 border="1">
                                <tr height="8">
                                    <td align="center" height=8 id="sw-show-screen">屏幕共享</td>
                                    <td align="center" height=8 id="sw-show-dianbo">点播文件</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div id="videos-container" style="width: 100%; display:none">
                            
                        </div>
                        <div id="dianbo-videos-container" style="margin-top:18px;display:none">  

                        </div>                                      
                                                   
                        <!-- sunpy: if students -->    
                    <?php } else { ?>
                        <!-- sunpy: video start -->
                        <button id="student-close-camera" style="display:none">关闭视频</button>
                        <button id="student-open-camera" style="display:none">打开视频</button>
                        <!-- sunpy: video end-->

                        <!-- list of all available broadcasting rooms -->
                        <table style="width: 100%;" id="rooms-list"></table>

                        <!-- local/remote videos container -->
                        <div style="display:block;padding-top:2px;font-size:15px;margin-left:auto;margin-right:auto;height:10px;width:100%;border:none;">
                            <table style="border-style:dashed;" height=8 border="1">
                                <tr height="8">
                                    <td align="center" height=8 id="sw-show-screen">屏幕共享</td>
                                    <td align="center" height=8 id="sw-show-dianbo">点播文件</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div id="videos-container" style="height: 100%; margin-top:0px; width: 100%; display:none">

                        </div>
                        
                        <div id="dianbo-videos-container" style="margin-top:18px;display:none">  

                        </div>                                                
                    <?php } ?>    
                </div>

                <div class="right">
                    <div>
                        <div style="margin-left:auto;margin-right:auto;width:80%;border:none;">
                            <table style="border-style:dashed;" border="0">
                                <tr>
                                    <td align="center" id="sw-teacher-camera">教师视频</td>
                                    <td align="center" id="sw-bulletin">通知公告</td>
                                </tr>
                            </table>
                        </div>

                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                            <textarea id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                            <?php if ($role == 'student') { ?>
                                <button id="postnotice" name="发布公告"/>
                                <?php } ?>
                        </div>

                        <div id="chatroom" class="chatroom">

                        </div>
                        <div class="sendfoot">
                            <input type='text' id='messageInput' style="width:65%;margin-top:0px;margin-bottom:0px">
                            <button id="send-msg" style="padding-top:4px;padding-bottom:4px;height:30px;width:25%;font-size:10px">发送</button>
                        </div>
                    </div>
                </div>

                <!-- sunpy: chatroom -->
                <script>
                    // ------------------------------------------------------ publish bulletin
                    $("#postnotice").hide();
                    $(document).keypress(function(event) {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            $("#postnotice").click();
                        }
                    });

                    $(function() {
                        var current_date = new Date();
                        var current_time = current_date.toLocaleTimeString();

                        $("#postnotice").click(function() {
                            var text = $("#bulletin-textarea").val()

                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putBulletin",
                                data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'}
                            })
                        })
                    })

                    // ------------------------------------------------------ poll latest bulletin
                    /*第一次读取最新通知*/
                    setTimeout(function() {
                        pollBulletin();
                    }, 200);
                    /*30轮询读取函数*/
                    setInterval(function() {
                        pollBulletin();
                    }, 10000);

                    function pollBulletin() {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "index.php?r=api/GetLatestBulletin",
                            success: function(data) {
                                console.log(data[0].id);
                                if (role === 'student') {
                                    $("#bulletin-textarea").val(data[0].content);
                                } else {
                                    if ($("#bulletin-textarea").val() === "") {
                                        $("#bulletin-textarea").val(data[0].content);
                                    }
                                }
                            }
                        });
                    }

                    // ------------------------------------------------------ poll chat
                    setInterval(function() {
                        pollChatRoom();
                    }, 1000);

                    function pollChatRoom() {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "index.php?r=api/getlatestchat",
                            success: function(data) {
                                $("#chatroom").empty();
                                var html = "";
                                var obj = eval(data);
                                $.each(obj, function(entryIndex, entry) {
                                    html += entry['username'] + "：" + entry['chat'] + "<br>";
                                });
                                $("#chatroom").append(html);
                                //$("#chatroom").scrollTop($("#chatroom").height);
                            }
                        });
                    }

                    // ------------------------------------------------------ send chat
                    $(function() {
                        $("#send-msg").click(function() {
                            var messageField = $('#messageInput');
                            var msg = messageField.val();
                            messageField.val('');

                            var current_date = new Date();
                            var current_time = current_date.toLocaleTimeString();

                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putChat",
                                data: {
                                    username: '"' + current_username + '"',
                                    chat: '"' + msg + '"',
                                    time: '"' + current_time + '"'
                                }
                            })
                        })
                    })
                </script>

                <!-- sunpy: switch camera and bulletin -->
                <script>
                    $("#sw-teacher-camera").hover(function() {
                        $("#teacher-camera").show();
                        $("#bulletin").hide();
                    });

                    $("#sw-bulletin").hover(function() {
                        $("#teacher-camera").hide();
                        $("#bulletin").show();
                    });
                </script>
                
                <!-- sunpy: switch dianbo and screen -->
                <script>
                    $("#sw-show-dianbo").click(function() {
                        $("#sw-show-dianbo").css("background-color", "gray");
                        $("#sw-show-screen").css("filter", "Alpha(opacity=50)");
                        $("#videos-container").hide();
                        $("#dianbo-videos-container").show();
                    });

                    $("#sw-show-screen").click(function() {
                        $("#sw-show-screen").css("background-color", "gray");
                        $("#sw-show-dianbo").css("filter", "Alpha(opacity=50)");
                        $("#videos-container").show();
                        $("#dianbo-videos-container").hide();
                    });
                </script>                

                <!-- sunpy: share screen -->
                <script>

                    var isWebRTCExperimentsDomain = document.domain.indexOf('webrtc-experiment.com') != -1;

                    var config = {
                        openSocket: function(config) {
                            var channel = config.channel || 'screen-capturing-' + location.href.replace(/\/|:|#|%|\.|\[|\]/g, '');
                            var socket = new Firebase('https://sulu.firebaseIO.com/' + channel);
                            socket.channel = channel;
                            socket.on("child_added", function(data) {
                                console.log("sunpy: on child_added event " + data.val());
                                config.onmessage && config.onmessage(data.val());
                            });
                            socket.send = function(data) {
                                console.log("sunpy: push data " + data);
                                this.push(data);
                            };
                            config.onopen && setTimeout(config.onopen, 1);
                            socket.onDisconnect().remove();
                            return socket;
                        },
                        onRemoteStream: function(media) {
                            $("#videos-container").show();
                            $("#dianbo-videos-container").hide();
                            console.log('sunpy: onRemoteStream');
                            var video = media.video;
                            video.setAttribute('controls', true);
                            //videosContainer.removeChild();
                            videosContainer.insertBefore(video, videosContainer.firstChild);
                            video.play();
                        },
                        onRoomFound: function(room) {
                            console.log('sunpy: found share screen room')

                            if (location.hash.replace('#', '').length) {
                                console.log("sunpy: enter if and join and return");
                                conferenceUI.joinRoom({
                                    roomToken: room.roomToken,
                                    joinUser: room.broadcaster
                                });
                                return;
                            }

                            var alreadyExist = document.getElementById(room.broadcaster);
                            if (alreadyExist)
                                return;

                            /*
                             if (typeof roomsList === 'undefined')
                             roomsList = document.body;
                             */


                            (function() {
                                var button = this;
                                button.disabled = true;
                                conferenceUI.joinRoom({
                                    roomToken: room.roomToken,
                                    joinUser: room.broadcaster
                                });
                                console.log("sunpy: anoymous join room");
                            })();
                        },
                        onNewParticipant: function(numberOfParticipants) {
                            /*
                             document.title = numberOfParticipants + ' users are viewing your screen!';
                             var element = document.getElementById('number-of-participants');
                             if (element) {
                             element.innerHTML = numberOfParticipants + ' users are viewing your screen!';
                             }
                             */
                        },
                        oniceconnectionstatechange: function(state) {
                            if (state == 'failed') {
                                alert('Failed to bypass Firewall rules. It seems that target user did not receive your screen. Please ask him reload the page and try again.');
                            }

                            if (state == 'connected') {
                                alert('A user successfully received your screen.');
                            }
                        }
                    };

                    function captureUserMedia(callback, extensionAvailable) {
                        console.log('sunpy: captureUserMedia');
                        console.log('captureUserMedia chromeMediaSource', DetectRTC.screen.chromeMediaSource);

                        var screen_constraints = {
                            mandatory: {
                                chromeMediaSource: DetectRTC.screen.chromeMediaSource,
                                maxWidth: screen.width > 1920 ? screen.width : 1920,
                                maxHeight: screen.height > 1080 ? screen.height : 1080
                            },
                            optional: [{// non-official Google-only optional constraints
                                    googTemporalLayeredScreencast: true
                                }, {
                                    googLeakyBucket: true
                                }]
                        };

                        if (isChrome && isWebRTCExperimentsDomain && typeof extensionAvailable == 'undefined' && DetectRTC.screen.chromeMediaSource != 'desktop') {
                            DetectRTC.screen.isChromeExtensionAvailable(function(available) {
                                captureUserMedia(callback, available);
                            });
                            return;
                        }

                        if (isChrome && isWebRTCExperimentsDomain && DetectRTC.screen.chromeMediaSource == 'desktop' && !DetectRTC.screen.sourceId) {
                            DetectRTC.screen.getSourceId(function(error) {
                                if (error && error == 'PermissionDeniedError') {
                                    alert('PermissionDeniedError: User denied to share content of his screen.');
                                }

                                captureUserMedia(callback);
                            });
                            return;
                        }

                        // for non-www.webrtc-experiment.com domains
                        if (isChrome && !isWebRTCExperimentsDomain && !DetectRTC.screen.sourceId) {
                            window.addEventListener('message', function(event) {
                                if (event.data && event.data.chromeMediaSourceId) {
                                    var sourceId = event.data.chromeMediaSourceId;

                                    DetectRTC.screen.sourceId = sourceId;
                                    DetectRTC.screen.chromeMediaSource = 'desktop';

                                    if (sourceId == 'PermissionDeniedError') {
                                        return alert('User denied to share content of his screen.');
                                    }

                                    captureUserMedia(callback, true);
                                }

                                if (event.data && event.data.chromeExtensionStatus) {
                                    warn('Screen capturing extension status is:', event.data.chromeExtensionStatus);
                                    DetectRTC.screen.chromeMediaSource = 'screen';
                                    captureUserMedia(callback, true);
                                }
                            });
                            screenFrame.postMessage();
                            return;
                        }

                        if (isChrome && DetectRTC.screen.chromeMediaSource == 'desktop') {
                            screen_constraints.mandatory.chromeMediaSourceId = DetectRTC.screen.sourceId;
                        }

                        var constraints = {
                            audio: true,
                            video: screen_constraints
                        };

                        if (!!navigator.mozGetUserMedia) {
                            console.warn(Firefox_Screen_Capturing_Warning);
                            constraints.video = {
                                mozMediaSource: 'window',
                                mediaSource: 'window',
                                maxWidth: 1920,
                                maxHeight: 1080,
                                minAspectRatio: 1.77
                            };
                        }

                        console.log(JSON.stringify(constraints, null, '\t'));

                        var video = document.createElement('video');
                        video.setAttribute('autoplay', true);
                        video.setAttribute('controls', true);
                        videosContainer.insertBefore(video, videosContainer.firstChild);

                        getUserMedia({
                            video: video,
                            constraints: constraints,
                            onsuccess: function(stream) {
                                config.attachStream = stream;
                                callback && callback();
                            },
                            onerror: function() {
                                if (isChrome && location.protocol === 'http:') {
                                    alert('Please test this WebRTC experiment on HTTPS.');
                                } else if (isChrome) {
                                    alert('Screen capturing is either denied or not supported. Please install chrome extension for screen capturing or run chrome with command-line flag: --enable-usermedia-screen-capturing');
                                }
                                else if (!!navigator.mozGetUserMedia) {
                                    alert(Firefox_Screen_Capturing_Warning);
                                }
                            }
                        });
                    }

                    /* on page load: get public rooms */
                    var conferenceUI = conference(config);

                    /* UI specific */
                    var videosContainer = document.getElementById("videos-container") || document.body;
                    var roomsList = document.getElementById('rooms-list');

                    document.getElementById('share-screen').onclick = function() {
                        conferenceUI.leaveRoom();

                        $("#videos-container").empty();

                        //var myRoomName = 'sunpeiyuanshixiaohua' + Math.random();
                        var myRoomName = 'sunpeiyuanshixiaohua';
                        captureUserMedia(function() {
                            conferenceUI.createRoom({
                                roomName: myRoomName
                            });
                        });
                        $("#videos-container").show();
                        $("#dianbo-videos-container").hide();
                        //this.disabled = true;
                    };

                    function rotateVideo(video) {
                        video.style[navigator.mozGetUserMedia ? 'transform' : '-webkit-transform'] = 'rotate(0deg)';
                        setTimeout(function() {
                            video.style[navigator.mozGetUserMedia ? 'transform' : '-webkit-transform'] = 'rotate(360deg)';
                        }, 1000);
                    }

                    (function() {
                        var uniqueToken = document.getElementById('unique-token');
                        uniqueToken.parentNode.parentNode.href = '#CKLQCDPU-PCIK9';
                    })();

                    var Firefox_Screen_Capturing_Warning = 'Make sure that you are using Firefox Nightly and you enabled: media.getusermedia.screensharing.enabled flag from about:config page. You also need to add your domain in "media.getusermedia.screensharing.allowed_domains" flag.';

                </script>

                <!-- sunpy: share screen -->
                <script>
                    var screenFrame, loadedScreenFrame;

                    function loadScreenFrame(skip) {
                        if (loadedScreenFrame)
                            return;
                        if (!skip)
                            return loadScreenFrame(true);

                        loadedScreenFrame = true;

                        var iframe = document.createElement('iframe');
                        iframe.onload = function() {
                            iframe.isLoaded = true;
                            console.log('Screen Capturing frame is loaded.');

                            document.getElementById('share-screen').disabled = false;
                            document.getElementById('room-name').disabled = false;
                        };
                        iframe.src = 'https://www.webrtc-experiment.com/getSourceId/';
                        iframe.style.display = 'none';
                        (document.body || document.documentElement).appendChild(iframe);

                        screenFrame = {
                            postMessage: function() {
                                if (!iframe.isLoaded) {
                                    setTimeout(screenFrame.postMessage, 100);
                                    return;
                                }
                                console.log('Asking iframe for sourceId.');
                                iframe.contentWindow.postMessage({
                                    captureSourceId: true
                                }, '*');
                            }
                        };
                    }
                    ;

                    if (!isWebRTCExperimentsDomain) {
                        loadScreenFrame();
                    }
                    else {
                        document.getElementById('share-screen').disabled = false;
                        document.getElementById('room-name').disabled = false;
                    }
                </script>

                <!-- sunpy: share screen -->
                <script>
                    // todo: need to check exact chrome browser because opera also uses chromium framework
                    var isChrome = !!navigator.webkitGetUserMedia;

                    // DetectRTC.js - https://github.com/muaz-khan/WebRTC-Experiment/tree/master/DetectRTC
                    // Below code is taken from RTCMultiConnection-v1.8.js (http://www.rtcmulticonnection.org/changes-log/#v1.8)
                    var DetectRTC = {};

                    (function() {

                        var screenCallback;

                        DetectRTC.screen = {
                            chromeMediaSource: 'screen',
                            getSourceId: function(callback) {
                                if (!callback)
                                    throw '"callback" parameter is mandatory.';
                                screenCallback = callback;
                                window.postMessage('get-sourceId', '*');
                            },
                            isChromeExtensionAvailable: function(callback) {
                                if (!callback)
                                    return;

                                if (DetectRTC.screen.chromeMediaSource == 'desktop')
                                    return callback(true);

                                // ask extension if it is available
                                window.postMessage('are-you-there', '*');

                                setTimeout(function() {
                                    if (DetectRTC.screen.chromeMediaSource == 'screen') {
                                        callback(false);
                                    }
                                    else
                                        callback(true);
                                }, 2000);
                            },
                            onMessageCallback: function(data) {
                                if (!(typeof data == 'string' || !!data.sourceId))
                                    return;

                                console.log('chrome message', data);

                                // "cancel" button is clicked
                                if (data == 'PermissionDeniedError') {
                                    DetectRTC.screen.chromeMediaSource = 'PermissionDeniedError';
                                    if (screenCallback)
                                        return screenCallback('PermissionDeniedError');
                                    else
                                        throw new Error('PermissionDeniedError');
                                }

                                // extension notified his presence
                                if (data == 'rtcmulticonnection-extension-loaded') {
                                    if (document.getElementById('install-button')) {
                                        document.getElementById('install-button').parentNode.innerHTML = '<strong>Great!</strong> <a href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk" target="_blank">Google chrome extension</a> is installed.';
                                    }
                                    DetectRTC.screen.chromeMediaSource = 'desktop';
                                }

                                // extension shared temp sourceId
                                if (data.sourceId) {
                                    DetectRTC.screen.sourceId = data.sourceId;
                                    if (screenCallback)
                                        screenCallback(DetectRTC.screen.sourceId);
                                }
                            },
                            getChromeExtensionStatus: function(callback) {
                                if (!!navigator.mozGetUserMedia)
                                    return callback('not-chrome');

                                var extensionid = 'ajhifddimkapgcifgcodmmfdlknahffk';

                                var image = document.createElement('img');
                                image.src = 'chrome-extension://' + extensionid + '/icon.png';
                                image.onload = function() {
                                    DetectRTC.screen.chromeMediaSource = 'screen';
                                    window.postMessage('are-you-there', '*');
                                    setTimeout(function() {
                                        if (!DetectRTC.screen.notInstalled) {
                                            callback('installed-enabled');
                                        }
                                    }, 2000);
                                };
                                image.onerror = function() {
                                    DetectRTC.screen.notInstalled = true;
                                    callback('not-installed');
                                };
                            }
                        };

                        // check if desktop-capture extension installed.
                        if (window.postMessage && isChrome) {
                            DetectRTC.screen.isChromeExtensionAvailable();
                        }
                    })();

                    DetectRTC.screen.getChromeExtensionStatus(function(status) {
                        if (status == 'installed-enabled') {
                            if (document.getElementById('install-button')) {
                                document.getElementById('install-button').parentNode.innerHTML = '<strong>Great!</strong> <a href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk" target="_blank">Google chrome extension</a> is installed.';
                            }
                            DetectRTC.screen.chromeMediaSource = 'desktop';
                        }
                    });

                    window.addEventListener('message', function(event) {
                        if (event.origin != window.location.origin) {
                            return;
                        }

                        DetectRTC.screen.onMessageCallback(event.data);
                    });

                    console.log('current chromeMediaSource', DetectRTC.screen.chromeMediaSource);
                </script>

                <!-- sunpy: video start script-->
                <script>
                    var connection = new RTCMultiConnection();
                    var teacherCamera = document.getElementById("teacher-camera");

                    connection.session = {
                        audio: true,
                        video: true,
                        oneway: true
                    };

                    connection.privileges = {
                        canStopRemoteStream: true, // user can't stop remote streams
                        canMuteRemoteStream: false  // user can't mute remote streams
                    };

                    connection.onstream = function(e) {
                        console.log("sunpy: video on steam");
                        e.mediaElement.width = 600;
                        //e.mediaElement.css("margin-left:auto;margin-right:auto;");
                        teacherCamera.insertBefore(e.mediaElement, teacherCamera.firstChild);
                        //$("#student-close-camera").css("display", "block");
                        scaleVideos();
                    };

                    $("#student-close-camera").click(function() {
                        console.log("sunpy: student hides camera");
                        this.disable = true;
                        $("#teacher-camera").css("display", "none");
                        $("#student-open-camera").css("display", "block");
                    })

                    $("#student-open-camera").click(function() {
                        console.log("sunpy: student displays camera");
                        this.disable = true;
                        $("#teacher-camera").css("display", "block");
                        $("#student-close-camera").css("display", "block");
                    })

                    connection.onstreamended = function(e) {
                        e.mediaElement.style.opacity = 0;
                        rotateVideo(e.mediaElement);
                        setTimeout(function() {
                            if (e.mediaElement.parentNode) {
                                e.mediaElement.parentNode.removeChild(e.mediaElement);
                            }
                            scaleVideos();
                        }, 1000);
                    };

                    // sunpy ------------------------------------ join start
                    var sessions = {};
                    connection.onNewSession = function(session) {
                        console.log("sunpy: video session.sessionid: ", session.sessionid);

                        if (sessions[session.sessionid]) {
                            console.log("sunpy: video onNewSession returned")
                            return;
                        }
                        sessions[session.sessionid] = session;

                        (function() {
                            console.log("sunpy: video session.sessionid: ", session.sessionid);
                            //var sessionid = this.getAttribute('data-sessionid');
                            session = sessions[session.sessionid];

                            if (!session)
                                throw 'No such session exists.';

                            connection.join(session);
                        })();
                    };
                    // sunpy ------------------------------------ join end

                    var videosContainer = document.getElementById('videos-container');
                    var roomsList = document.getElementById('rooms-list');

                    // setup-new-broadcast: 视频 点击事件
                    if (document.getElementById('setup-new-broadcast')) {
                        document.getElementById('setup-new-broadcast').onclick = function() {
                            console.log("sunpy: open session teacher");
                            this.disabled = true;
                            $("#teacher-close-camera").attr("disabled", false);
                            connection.open('teacher');
                            console.log("sunpy: connection.open teacher");
                        };
                    }

                    $("#teacher-close-camera").click(function() {
                        console.log("sunpy: teacher close camera");
                        connection.close();
                        this.disable = true;
                        $("#setup-new-broadcast").removeAttr("disabled");
                    });

                    // setup signaling to search existing sessions
                    connection.connect();

                    (function() {
                        var uniqueToken = document.getElementById('unique-token');
                        if (uniqueToken)
                            if (location.hash.length > 2)
                                uniqueToken.parentNode.parentNode.parentNode.innerHTML = '<h2 style="text-align:center;"><a href="' + location.href + '" target="_blank">Share this link</a></h2>';
                            else
                                uniqueToken.innerHTML = uniqueToken.parentNode.parentNode.href = '#' + (Math.random() * new Date().getTime()).toString(36).toUpperCase().replace(/\./g, '-');
                    })();

                    function scaleVideos() {
                        var videos = document.querySelectorAll('video'),
                                length = videos.length,
                                video;

                        var minus = 130;
                        var windowHeight = 700;
                        var windowWidth = 600;
                        var windowAspectRatio = windowWidth / windowHeight;
                        var videoAspectRatio = 4 / 3;
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
                <!-- sunpy video end script-->

                <!-- sunpy: dianbo start script-->
                <script>
                    var absl_path;
                    
                    $("#teacher-dianbo").click(function() {   
                        console.log("sunpy: role = " + role);
                        //$("#videos-container").empty();

                        var server_root_path = "https://192.168.101.235/dianbo_video/";
                        var filepath = $("#teacher-choose-file").val();
                        var absl_path = server_root_path + filepath;
                        var video_element;
                        var video_time_duration;

                        console.log("sunpy: choose file " + server_root_path + filepath);

                        var html = "";
                        html += '<video id="video1" width="100%" controls>';
                        html += '<source src="' + absl_path + '">';
                        html += '</video>';
                        //html += '<button id="play">播放</button>';
                        //html += '<button id="pause">暂停</button>';
                        $("#dianbo-videos-container").empty();
                        $("#dianbo-videos-container").append(html);
                        $("#dianbo-videos-container").show();
                        $("#videos-container").hide();

                        video_element = document.getElementById("video1");
                        video_element.onloadedmetadata = function() {
                            video_time_duration = video_element.duration;
                            console.log("sunpy: video duration " + video_time_duration);
                        };

                        WebSocketConnect(absl_path);
                    });
                    
                    // sunpy: student side connect to websocket server
                    if (document.getElementById("teacher-dianbo") === null) {
                        var myVideo = document.getElementById("video1");
                        var connection_state = 0;
                        var is_first_connection = 1;
                        var is_first_set_path = 1;

                        if (connection_state !== 1) { 
                            var ws = new WebSocket("wss://192.168.101.235:8443", 'echo-protocol');                            
                            
                            ws.onopen = function() {
                                console.log("connect to websocket server");
                            };

                            ws.addEventListener("message", function(e) 
                            {                                
                                var msg = e.data;
                                var local_my_video = document.getElementById("video1");
                                
                                if (msg === "Play" && local_my_video !== null && local_my_video.paused) {
                                    local_my_video = document.getElementById("video1");
                                    local_my_video.play();
                                } else if (msg === "Pause" && local_my_video !== null) {
                                    local_my_video = document.getElementById("video1");
                                    local_my_video.pause();
                                } else if (msg.startWith('Path')) {
                                    var video_path = msg.substr(5);
                                    if (is_first_set_path === 1) {  
                                       is_first_set_path = 0;
                                       var html = "";
                                       html += '<video id="video1" width="100%" controls>';
                                       html += '<source src="' + video_path + '">';
                                       html += '</video>';
                                       $("#dianbo-videos-container").empty();
                                       $("#dianbo-videos-container").append(html);
                                       $("#dianbo-videos-container").show();
                                       $("#videos-container").hide();
                                       
                                       local_my_video = document.getElementById("video1");    
                                    }                                    
                                } /*
                                    else if (msg.startWith('psync')) {
                                    local_my_video = document.getElementById("video1");                                                                             
                                    var video_current_time = parseFloat(msg.substr(6));
                                    console.log("sunpy: recv websocket broadcast msg " + video_current_time);
                                    if (is_first_connection === 1) {
                                        is_first_connection = 0;
                                        local_my_video.play();
                                        local_my_video.currentTime = video_current_time;
                                        console.log("sunpy: student side set video time " + video_current_time);
                                    }
                                    local_my_video.currentTime = video_current_time;
                                } else if (msg.startWith('sync')) {
                                    local_my_video = document.getElementById("video1");                                                                             
                                    var video_current_time = parseFloat(msg.substr(5));
                                    console.log("sunpy: recv websocket broadcast msg " + video_current_time);
                                    local_my_video.currentTime = video_current_time;
                                    console.log("sunpy: student side set video time " + video_current_time);
                                }   */                       
                            });

                            ws.onclose = function(event) {
                                alert("与点播服务器的连接断开...");
                            };
                        }                        
                    }

                    function WebSocketConnect(absl_path)
                    {
                        console.log("sunpy [WebSocketConnect]");

                        var myVideo = document.getElementById("video1");
                        var connection_state = 0;
                        var is_first_connection = 1;
                        var is_first_set_path = 1;

                        if (connection_state !== 1) { //cheching is there is a live connection so we do not spam the server.
                            //if there is not live connection we create one
                            var ws = new WebSocket("wss://192.168.101.235:8443", 'echo-protocol');// initializing the connection through the websocket api
                            ws.onopen = function() //creating the connection
                            {
                                connection_state = 1;
                                /*
                                if (document.getElementById("video1") !== null) {
                                    $("#teacher-stop-dianbo").click(function() {
                                        ws.close();
                                    });
                                } */                               
                                
                                myVideo.addEventListener("play", function() {
                                    console.log("sunpy: btn play");
                                    var message_sent = "Play";
                                    ws.send(message_sent); 
                                    message_sent = "Path " + absl_path;
                                    ws.send(message_sent);                                    
                                });
                                
                                myVideo.addEventListener("pause", function() {
                                    console.log("sunpy: btn pause");
                                    var message_sent = "Pause";
                                    ws.send(message_sent);                                    
                                });                                

                                // sunpy: teacher side broadcasts sync msg
                                //        progress + path
                                setInterval(function() {
                                    broadcast_video_time(absl_path);
                                }, 1000);

                                function broadcast_video_time(absl_path)
                                {
                                    var syn_msg;
                                    var video_current_time = myVideo.currentTime;                                    
                                    
                                    if (myVideo.paused) {
                                        syn_msg = "sync " + video_current_time;                                        
                                    } else {
                                        syn_msg = "psync " + video_current_time;
                                    }                                    
                                    
                                    var syn_path_msg = "Path " + absl_path;
                                    ws.send(syn_path_msg);
                                    ws.send(syn_msg);
                                }
                            };

                            ws.addEventListener("message", function(e) 
                            {                                
                                var msg = e.data;
                                
                                if (msg === "Play" && myVideo.paused) {
                                    myVideo.play();
                                } else if (msg === "Pause") {
                                    myVideo.pause();
                                }                               
                            });

                            ws.onclose = function(event) {
                                alert("与点播服务器的连接断开...");
                            };
                        }
                    }
                    ;
                </script>

                <script language="javascript">
                    String.prototype.trim = function() {
                        return this.replace(/(^\s*)|(\s*$)/g, "");
                    }

                    String.prototype.ltrim = function() {
                        return this.replace(/(^\s*)/g, "");
                    }

                    String.prototype.rtrim = function() {
                        return this.replace(/(\s*$)/g, "");
                    }

                    String.prototype.startWith = function(str) {
                        if (str == null || str == "" || this.length == 0 || str.length > this.length)
                            return false;
                        if (this.substr(0, str.length) == str)
                            return true;
                        else
                            return false;
                        return true;
                    }
                </script>
<!-- END PAGE -->

<script>
    var $$ = jQuery;
    $$(function() {
        $i = 1;
        $$('#parent').click(function(e) {
            if (e.target == $('#bu_zhibo')[0]) {
                $$('#controll2').css('display', 'none');
                $$('#controll1').css('display', 'block');
                function teacher_auto() {
                    if (!!$$('#setup-new-room')) {
                        $$('#setup-new-room').on('click', function() {
                            $$('#zhibo').css('display', 'none');
                        });
                        $$('#setup-new-room').click();
                    }
                }
                if ($i == 1) {
                    teacher_auto();
                    $i++;
                }
            } else if (e.target == $('#bu_dianbo')[0]) {
                $$('#controll1').css('display', 'none');
                $$('#controll2').css('display', 'block');
            }
        });
    });
</script>
