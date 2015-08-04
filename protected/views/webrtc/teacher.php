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
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>getMediaElement.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>index.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />

<!--点播 begin-->
<link href="<?php echo CSS_URL; ?>webrtc_style.css" rel="stylesheet" type="text/css"/>
<!--点播end-->



<?php
$username = Yii::app()->user->name;
echo "<script>var current_username=\"$username\";</script>";

$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
?>
                <div class="left">
                    <span style="display:none">
                        <a href="/Pluginfree-Screen-Sharing/" target="_blank" title="Open this link for private screen sharing!">
                            <code style="display:none"><strong id="unique-token">#123456789</strong></code>
                        </a>
                    </span>
                    
                        <div style="display:inline;padding-right:60px;">
                            <button id="share-screen" class="btn-large btn-primary">屏幕共享</button>
                        </div>

                        <div style="display:inline;padding-right:60px;">
                            <!-- sunpy: video start -->
                            <button id="setup-new-broadcast" class="btn-large btn-primary">直播视频</button>
                        </div>

                        <div style="display:inline;">
                            <!-- sunpy: broadcast local video -->    
                            <button id="teacher-dianbo" style="font-size:20px;height:40px">点播</button>
                            <select id="teacher-choose-file">
                                <option value ="1.mp4">速录演示视频</option>
                                <option value="CB6601435D33002ECE7BAD33F79126D6.flv">MV-不见长安</option>
                                <option value ="h0134j6z7lp.mp4">MV-时间都去哪了</option>
                                <option value="l0015jn2cz8.mp4">MV-泡沫</option>
                            </select>
                            <!--<input type="file" placeholder="选择点播文件" id="teacher-choose-file" style="font-size:20px;width:30%"></input>-->
                            <!-- <button id="teacher-stop-dianbo">停止点播</button> -->
                        </div>
                        
                        <div id="videos-container" style="height: 100%; width: 100%; margin-top:0px; display:none">
                        </div>
                        <div id="dianbo-videos-container" style="margin-top:18px;display:none">  
                        </div>
                </div>

                <div class="right">
                    <div>
                        <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>
                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div align="center" id="sw-bulletin"><a href="#"><h4>通 知 公 告</h4></a></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                            
                        <textarea id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                        <button id="postnotice" name="发布公告" class="btn btn-primary" style="margin-left: 100px; margin-top: 5px;">发布公告</button>

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
    var share_conn;
    $('#share-screen').click(function(){
        var cls = $(this).attr('class');
        if(cls.indexOf('btn-primary') > 0){//按钮是共享
            share_conn = new RTCMultiConnection('screen-sharing-id-1');
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
                $("#videos-container").show();
                container.insertBefore(event.mediaElement, container.firstChild);
            };
            share_conn.open("class");
            $(this).attr('class','btn btn-large');
            $(this).html('关闭共享');
        } else {
            //按钮是关闭
            share_conn.close();
            share_conn = null;
            $("#videos-container").hide();
            $("#videos-container").empty();
            $(this).attr('class','btn-large btn-primary');
            $(this).html('共享屏幕');
        }
    });
    
    $('#setup-new-broadcast').click(function(){
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
            var teacherCamera = document.getElementById("teacher-camera");
            teacherCamera.insertBefore(event.mediaElement, teacherCamera.firstChild);
        };
        video_conn.autoCloseEntireSession = true;
        video_conn.open('video-classid');
    });
});
</script>

<script>
    //chat and bulletin
$(document).ready(function(){
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();

    $("#postnotice").click(function() {
        var text = $("#bulletin-textarea").val();
        $.ajax({
            type: "POST",
            url: "index.php?r=api/putBulletin",
            data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'},
            success: function(){alert('公告发布成功！');},
            error: function(){alert('出错了...');}
        });
    });
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
});
</script>

<script>
    //点播
$(document).ready(function(){
    $("#teacher-dianbo").click(function() {   
        console.log("sunpy: role = " + role);
        //$("#videos-container").empty();

        var server_root_path = "<?php echo SITE_URL.'resources/'?>video/";
        var filepath = $("#teacher-choose-file option:selected").val();
        var absl_path = server_root_path + filepath;
        var video_element;
        var video_time_duration;

        console.log("sunpy: choose file " + server_root_path + filepath);

        var video = document.getElementById('video1');
        if(video===null){
            var html = "";
            html += '<video id="video1" width="100%" controls>';
            html += '<source src="' + absl_path + '">';
            html += '</video>';
            //html += '<button id="play">播放</button>';
            //html += '<button id="pause">暂停</button>';
            $("#dianbo-videos-container").empty();
            $("#dianbo-videos-container").append(html);
        } else {
            video.setAttribute("src", absl_path); 
        }
        $("#dianbo-videos-container").show();
        $("#videos-container").hide();
        video_element = document.getElementById("video1");
        video_element.onloadedmetadata = function() {
            video_time_duration = video_element.duration;
            console.log("sunpy: video duration " + video_time_duration);
        };
        WebSocketConnect(absl_path);
    });
});

var play_fun = null;
var pause_fun = null;
var ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');// initializing the connection through the websocket api
ws.onclose = function(event) {
      console.log("与点播服务器的连接断开...");
  }
  
function WebSocketConnect(absl_path){
    console.log("sunpy [WebSocketConnect]");
    var myVideo = document.getElementById("video1");
    if(play_fun != null)
    {
        myVideo.removeEventListener("play",play_fun);
        myVideo.removeEventListener("pause",pause_fun);
    }
    play_fun = function() {
        console.log("sunpy: btn play");
        var message_sent = "<?php echo $classID;?>Play";
        ws.send(message_sent); 
        message_sent = "<?php echo $classID;?>Path " + absl_path;
        ws.send(message_sent);                                    
    };
    pause_fun = function() {
        console.log("sunpy: btn pause");
        var message_sent = "<?php echo $classID;?>Pause";
        ws.send(message_sent);                                    
    }
    myVideo.addEventListener("play",play_fun);
    myVideo.addEventListener("pause",pause_fun);

}
</script>