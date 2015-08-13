<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>

<script src="<?php echo JS_URL; ?>socketio.js"></script>

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
            <!-- list of all available broadcasting rooms -->
            <table style="width: 100%;" id="rooms-list"></table>

            <!-- local/remote videos container -->
            <table class="student-video-head-table">
                <tr>
                    <td class="student-video-head-td" id="share-button"><a href="./index.php?r=webrtc/stuScreen" target="iframe_a">屏幕共享</a></td>
                    <td class="student-video-head-td" id="video-button"><a href="./index.php?r=webrtc/stuCam" target="iframe_b">点播文件</a></td>
                </tr>
            </table>

            <div id="videos-container" style="height: 1000px; width: 100%; margin-top:0px;display:none">
                <iframe src="" name="iframe_a" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
            </div>

            <div id="dianbo-videos-container" style="margin-top:5px;display:none">  </div>
    </div>

    <div class="right">
        <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>
        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;">
            <iframe src="" name="iframe_b" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
        </div>
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
        ws.close();
        $("#dianbo-videos-container").hide();
    });
});
</script>

<script>
    //点播
    var ws = null;
    var last_path = -1;
    var onScreen  = -1;
    var onCam     = -1;
    
    function clearVideo(){
        last_path = -1
        var video = document.getElementById('video1');
        if(video != null){
            video.pause();
            video.parentNode.removeChild(video);
        }
        $("#dianbo-videos-container").empty();
        $("#dianbo-videos-container").hide();
    }

    function changVideo(video_path){
        if (last_path !== video_path) {
            last_path = video_path;
            var video = document.getElementById('video1');
            if(video===null){
                var html = "";
                html += '<video id="video1" width="100%" controls>';
                html += '<source src="' + video_path + '">';
                html += '</video>';
                $("#dianbo-videos-container").empty();
                $("#dianbo-videos-container").append(html);
            } else {
                video.setAttribute("src", video_path); 
            }
            $("#videos-container").show();
            $("#dianbo-videos-container").show();
            var local_my_video = document.getElementById("video1");
            local_my_video.play();
        }
    }

    function DoMessage(e){
        var msg = e.data;
        console.log(msg);
        var local_my_video = document.getElementById("video1");
        if (msg === "<?php echo $classID;?>Play" && local_my_video !== null && local_my_video.paused) {
            //message Play
            local_my_video = document.getElementById("video1");
            local_my_video.play();
        } else if (msg === "<?php echo $classID;?>Pause" && local_my_video !== null) {
            //message Pause
            local_my_video = document.getElementById("video1");
            local_my_video.pause();
        } else if (msg.indexOf('<?php echo $classID;?>Path') >= 0) {
            //message Path
            var video_path = msg.substr(msg.indexOf('Path')+5);               
            changVideo(video_path);
        }else if (msg.indexOf('<?php echo $classID;?>playSync') >= 0) {
            //message playSync                                                                       
            var video_current_time = parseFloat(msg.substr(msg.indexOf('-----')+5));
            var video_path = msg.substr((msg.indexOf('playSync')+9),msg.indexOf('-----')-(msg.indexOf('playSync')+9));
            //console.log("sunpy: recv websocket broadcast msg " + video_current_time);
            changVideo(video_path);
            local_my_video = document.getElementById("video1");
            local_my_video.play();
            if(Math.abs(local_my_video.currentTime-video_current_time) > 5)
                local_my_video.currentTime = video_current_time;
            //console.log("sunpy: student side set video time " + video_current_time);
        } else if (msg.indexOf('<?php echo $classID;?>pauseSync') >= 0) {
            //message pauseSync
            var video_current_time = parseFloat(msg.substr(msg.indexOf('-----')+5));
            var video_path         = msg.substr((msg.indexOf('pauseSync')+10),msg.indexOf('-----')-(msg.indexOf('pauseSync')+10));
            //console.log("sunpy: recv websocket broadcast msg " + video_current_time);
            changVideo(video_path);
            local_my_video = document.getElementById("video1");
            local_my_video.pause();
            if(Math.abs(local_my_video.currentTime-video_current_time) > 5)
                local_my_video.currentTime = video_current_time;
            //console.log("sunpy: student side set video time " + video_current_time);    
        } else if(msg.indexOf('<?php echo $classID;?>closeVideo') >= 0){
            clearVideo();
        } else if(msg.indexOf('<?php echo $classID;?>closeCam') >= 0){
            iframe_b.location.href ='./index.php?r=webrtc/null';
            onCam = -1;
        } else if(msg.indexOf('<?php echo $classID;?>onCam') >= 0){
            if(onCam==-1){
                iframe_b.location.href ='./index.php?r=webrtc/stuCam';
                onCam   =  1;
            }
        } else if(msg.indexOf('<?php echo $classID;?>closeScreen') >= 0){
            iframe_a.location.href ='./index.php?r=webrtc/null';
            onScreen = -1;
        } else if(msg.indexOf('<?php echo $classID;?>onScreen') >= 0){
            if(onScreen==-1){
                $("#videos-container").show();
                iframe_a.location.href ='./index.php?r=webrtc/stuScreen';
                onScreen = 1;
            }
        }
    }
    
    function addMessageHandleForWS(){
        ws.onopen = function() {
            console.log("connect to websocket server");
        };
//        ws.onclose = function() {
//            clearVideo();
//            ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');
//            addMessageHandleForWS();
//            console.log("close to connect to websocket server");
//        };
        ws.addEventListener("message",DoMessage);
    }
    
$(document).ready(function(){
    var connection_state = 0;
    
    if (connection_state !== 1) { 
        ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');
        addMessageHandleForWS();
    }
});
</script>