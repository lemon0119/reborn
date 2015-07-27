<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>

<script src="<?php echo JS_URL; ?>RTCPeerConnection.js"></script>        <!-- required -->
<script src="<?php echo JS_URL; ?>RTCMultiConnection.js"></script>       <!-- required -->
<script src="<?php echo JS_URL; ?>globals.js"></script>                  <!-- required -->
<script src="<?php echo JS_URL; ?>MultiPeersHandler.js"></script>        <!-- required -->
<script src="<?php echo JS_URL; ?>OnIceCandidateHandler.js"></script>    <!-- required -->
<script src="<?php echo JS_URL; ?>IceServersHandler.js"></script>        <!-- required -->



<script src="<?php echo JS_URL; ?>Plugin.EveryWhere.js"></script>        <!-- optional -->
<script src="<?php echo JS_URL; ?>getUserMedia.js"></script>             <!-- optional -->
<script src="<?php echo JS_URL; ?>BandwidthHandler.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>DetectRTC.js"></script>                <!-- optional -->
<script src="<?php echo JS_URL; ?>FileBufferReader.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>TextSenderReceiver.js"></script>       <!-- optional -->
<script src="<?php echo JS_URL; ?>MediaStreamRecorder.js"></script>      <!-- optional -->
<script src="<?php echo JS_URL; ?>StreamsHandler.js"></script>           <!-- optional -->
<script src="<?php echo JS_URL; ?>RecordingHandler.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>Screen-Capturing.js"></script>         <!-- optional -->

<script src="<?php echo JS_URL; ?>socket.io.js"></script>
<!--直播begin-->
<!--点播 begin-->
<!--点播end-->

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
                        <!-- sunpy: video start -->
                        <button id="student-close-camera" style="display:none">关闭视频</button>
                        <button id="student-open-camera" style="display:none">打开视频</button>
                        <!-- sunpy: video end-->

                        <!-- list of all available broadcasting rooms -->
                        <table style="width: 100%;" id="rooms-list"></table>

                        <!-- local/remote videos container -->
                        <table class="student-video-head-table">
                            <tr>
                                <td class="student-video-head-td" id="share-button"><a href="#" id="sw-show-screen">屏幕共享</a></td>
                                <td class="student-video-head-td" id="video-button"><a href="#" id="sw-show-dianbo">点播文件</a></td>
                            </tr>
                        </table>
                        
                        <div id="videos-container" style="height: 100%; width: 100%; margin-top:5px; display:none"></div>
                        
                        <div id="dianbo-videos-container" style="margin-top:5px;display:none">  </div>
                </div>

                <div class="right">
                    <div>
                        <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>
                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div align="center" id="sw-bulletin"><a href="#"><h4>通 知 公 告</h4></a></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                                <textarea disabled id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                        </div>
                        <div align="center" id="sw-chat"><a href="#"><h4>课 堂 问 答</h4></a></div>
                        <div id="chat-box">
                        <div id="chatroom" class="chatroom"></div>
                        <div class="sendfoot">
                            <input type='text' id='messageInput' style="width:55%;margin-top:0px;margin-bottom:0px">
                            <button id="send-msg" style="padding-top:4px;padding-bottom:4px;height:30px;width:25%;font-size:10px">发送</button>
                        </div>
                        </div>
                    </div>
                </div>

<script>
// ......................................................
// ......................screen-sharing..................
// .......................UI Code........................
// ......................................................
$(document).ready(function(){
    var share_conn = new RTCMultiConnection('screen-sharing-id-1');
    var share_state = false;
    var video_state = false;
    share_conn.session = {
        screen: true,
        oneway: true
    };
    
    share_conn.sdpConstraints.mandatory = {
        OfferToReceiveAudio: false,
        OfferToReceiveVideo: false
    };
    share_conn.autoCloseEntireSession = true;
    share_conn.onstream = function(event) {
        var container = document.getElementById("videos-container");
        share_state = true;
        container.insertBefore(event.mediaElement, container.firstChild);
        //$("#videos-container").show();
        $("#sw-show-screen").click();
    };
    var join_interval = setInterval(function () {
        if (!share_state) {
            share_conn.join("class");
        } else {
            clearInterval(join_interval);
            console.log('stop join share!');
        }
    }, 500);
    
    var video_conn = new RTCMultiConnection('video-sharing-id-1');
    video_conn.session = {
        audio: true,
        video: true,
        data : true,
        oneway: true
    };
    video_conn.sdpConstraints.mandatory = {
        OfferToReceiveAudio: true,
        OfferToReceiveVideo: true
    };
    video_conn.onstream = function(event) {
        video_state = true;
        var teacherCamera = document.getElementById("teacher-camera");
        teacherCamera.insertBefore(event.mediaElement, teacherCamera.firstChild);
    };
    video_conn.autoCloseEntireSession = true;
    var join_video_interval = setInterval(function () {
        if (!video_state) {
            video_conn.join("video-classid");
        } else {
            clearInterval(join_video_interval);
            console.log('stop join video!');
        }
    }, 500);
});
</script>

<script>
    //chat and bulletin
$(document).ready(function(){
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();
    // ------------------------------------------------------ poll latest bulletin
    /*第一次读取最新通知*/
    setTimeout(function() {
        pollBulletin();
    }, 200);
    /*30轮询读取函数*/
    setInterval(function() {
        pollBulletin();
    }, 10000);
    // ------------------------------------------------------ poll chat
    setInterval(function() {
        pollChatRoom();
    }, 1000);

    // ------------------------------------------------------ send chat
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
        });
    });
});

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

</script>

<script>
    //sunpy: switch camera and bulletin
$(document).ready(function(){
    $("#sw-teacher-camera").click(function() {
        $("#teacher-camera").toggle(200);
    });
    $("#sw-chat").click(function() {
        $("#chat-box").toggle(200);
    });
    $("#sw-bulletin").click(function() {
        $("#bulletin").toggle(200);
    });

    //switch dianbo and screen
    $("#sw-show-dianbo").click(function() {
        $("#video-button").css("background-color", "#d9d9d9");
        $("#share-button").css("background-color", "white");
        $("#videos-container").hide();
        $("#dianbo-videos-container").show();
    });

    $("#sw-show-screen").click(function() {
        $("#share-button").css("background-color", "#d9d9d9");
        $("#video-button").css("background-color", "white");
        $("#sw-show-dianbo").css("filter", "Alpha(opacity=50)");
        $("#videos-container").show();
        $("#dianbo-videos-container").hide();
    });
});
</script>

<script>
    //点播
$(document).ready(function(){
    var myVideo = document.getElementById("video1");
    var connection_state = 0;
    var is_first_connection = 1;
    var is_first_set_path = 1;

    if (connection_state !== 1) { 
        var ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');                            

        ws.onopen = function() {
            console.log("connect to websocket server");
        };

        ws.addEventListener("message", function(e){
            var msg = e.data;
            var local_my_video = document.getElementById("video1");

            if (msg === "Play" && local_my_video !== null && local_my_video.paused) {
                local_my_video = document.getElementById("video1");
                local_my_video.play();
            } else if (msg === "Pause" && local_my_video !== null) {
                local_my_video = document.getElementById("video1");
                local_my_video.pause();
            } else if (msg.indexOf('Path') >= 0) {
                var video_path = msg.substr(5);
                if (is_first_set_path === 1) {  
                    is_first_set_path = 0;
                    var video = document.getElementById('video1');
                    if(video===null){
                        var html = "";
                        html += '<video id="video1" width="100%" controls>';
                        html += '<source src="' + video_path + '">';
                        html += '</video>';
                        //html += '<button id="play">播放</button>';
                        //html += '<button id="pause">暂停</button>';
                        $("#dianbo-videos-container").empty();
                        $("#dianbo-videos-container").append(html);
                    } else {
                        video.setAttribute("src", video_path); 
                    }
                    $("#dianbo-videos-container").show();
                    $("#videos-container").hide();
                }                                    
            }                
        });
    }
});
                            
function WebSocketConnect(absl_path){
    console.log("sunpy [WebSocketConnect]");
    var myVideo = document.getElementById("video1");
    var connection_state = 0;
    var is_first_connection = 1;
    var is_first_set_path = 1;

    if (connection_state !== 1) { //cheching is there is a live connection so we do not spam the server.
        //if there is not live connection we create one
        var ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');// initializing the connection through the websocket api
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
</script>