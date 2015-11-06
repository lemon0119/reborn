 <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>

<script src="<?php echo JS_URL; ?>socketio.js"></script>

<?php
echo "<script>var current_username=\"$userName\";</script>";
$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
?>


<!--自定义css begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
<!--自定义css end-->
<div class="left">
    <div class="vp1" style="width: 100%;">
        <br/>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;学号:</td>
                    <td>&nbsp;&nbsp;<?php echo $userID;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;学生姓名:</td>
                    <td>&nbsp;&nbsp;<?php echo $userName;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;班级:</td>
                    <td>&nbsp;&nbsp;<?php $sqlClass = TbClass::model()->find("classID = $class");
                    echo $sqlClass['className'];
                    ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;当前课程:</td>
                    <td>&nbsp;&nbsp;<?php foreach ($lessons as $less) {
                               if($less['lessonID'] === $currentLesn){
                                   echo $less['lessonName'];
                                };
                           }?>
                    </td>           
                </tr>
       
    </div>
    <!-- local/remote videos container --> 
    <div id="ppt-container" align="center" style="width: 100% ; height:100%;  margin-top:0px;display:none;overflow-x: hidden">
        <div id ="full-screen" style="position: relative; left: 275px; top: 20px;display:none;">
            <img src="<?php echo IMG_URL; ?>ppt-full-screen.png" onmouseover="fun3();" onclick="fun4()" style="opacity:0.3"/> 
        </div>
        <div id="ppt-asd">
        <img id="ppt-img"  onmouseover="fun1();" onmouseout="fun2();" src=""  style="height: 100%;"/>  
        </div>
    </div>

    <div id="dianbo-videos-container" style="display:none;">  </div>
</div>


<div class="right"style="background-color: #3b3b3b;border: 0px" >
    <div align="center" id="sw-teacher-camera"><a href="#" ><h4 style="color: white">教 师 视 频</h4></a></div>
    <div id="teacher-camera" style="border:0px solid #ccc; margin-left:auto;margin-right:auto;width:100%; height:280px; clear:both;">
        <iframe src="./index.php?r=webrtc/null" name="iframe_b" style="background-color:#5e5e5e;width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no" allowfullscreen></iframe>
    </div>
        <div align="center" id="sw-bulletin"><a href="#"><h4 style="color: white">通 知 公 告</h4></a></div>
        <div id="bulletin" class="bulletin" style="display:none;border: 0px;width: 100%;margin-left: -1.1px">
            <textarea disabled id="bulletin-textarea" style=" background-color:#5e5e5e;color:red;margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
        </div>
        <div align="center" id="sw-chat" ><a href="#"><h4 style="color: white">课 堂 问 答</h4></a></div>
        <div id="chat-box" style="border: 0px">
            <div id="chatroom" class="chatroom" style="background-color:#5e5e5e;border: 0px;width: 100%"></div>
            <div class="sendfoot" style="width: 100%;height: 100%;border: 0px;margin-left: -1.5px">
                <input type='text' id='messageInput' style="border: 0px;width:283px;height:26px; margin-top:0px;margin-bottom:0px;margin-right: 0px;color:gray" oninput="this.style.color='black'">
                <a id="send-msg"></a>
            </div>
        </div>
</div>
<script>
    //显示全屏图像
    var onImg = false;
    function fun1(){
        $('#ppt-asd').attr('style','margin-top: -35px');
        $("#full-screen").show();
        onImg = false;
        //full_screen =document.mozFullScreen;
    }
    function fun2(){
        if(onImg!=true)
        {   
            $("#full-screen").hide();
            $('#ppt-asd').attr('style','');
            onImg = false;
        }
    }
    function fun3(){
        onImg = true;
        $("#full-screen").show();
        $('#ppt-asd').attr('style','margin-top: -35px');
    }
    function fun4(){
        var docelem         = document.getElementById('ppt-asd');
        if (docelem.requestFullscreen) {
            docelem.requestFullscreen();
        }else if (docelem.webkitRequestFullscreen) {
            docelem.webkitRequestFullscreen();
        } else if(docelem.mozRequestFullScreen) {
            docelem.mozRequestFullScreen();
        } else if(docelem.msRequestFullscreen) {
            docelem.msRequestFullscreen();
        } 
    }
</script>

<script>
    //chat and bulletin
$(document).ready(function(){
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();
    //每5秒，发送一次时间
    setInterval(function() {    
        checkOnLine();
    }, 8000);
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
    //綁定enter
    document.onkeydown=function(event){
         e = event ? event :(window.event ? window.event : null);
         if(e.keyCode==13){
           var messageField = $('#messageInput');
           var msg = messageField.val();
           messageField.val('');

           var current_date = new Date();
            var current_time = current_date.toLocaleTimeString();
          
             $.ajax({
                  type: "POST",
                  url: "index.php?r=api/putChat&&classID=<?php echo $classID;?>",
                data: {
                  username: '"' + current_username + '"',
                   chat: '"' + msg + '"',
                  time: '"' + current_time + '"',
                 
                }
                });
           }
        }
    $("#send-msg").click(function() {
        var messageField = $('#messageInput');
        var msg = messageField.val();
        messageField.val('');

        var current_date = new Date();
        var current_time = current_date.toLocaleTimeString();
      

        $.ajax({
            type: "POST",
            url: "index.php?r=api/putChat&&classID=<?php echo $classID;?>",
            data: {
                username: '"' + current_username + '"',
                chat: '"' + msg + '"',
                time: '"' + current_time + '"',
              
            }
        });
    });
});

function pollChatRoom() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?r=api/getlatestchat&&classID=<?php echo $classID;?>",
        success: function(data) {
            $("#chatroom").empty();
            var html = "";
            var obj = eval(data);
            $.each(obj, function(entryIndex, entry) {
               if(entry['identity']=='teacher')
                     html += "<font color=\"red\">"+entry['username']+ "：" + entry['chat'] + "</font><br>";
                else
                     html += entry['username']+ "：" + entry['chat'] + "<br>";
            });
            $("#chatroom").append(html);
            //$("#chatroom").scrollTop($("#chatroom").height);
        }
    });
}

function checkOnLine(){
        $.ajax({
             type: "GET",
             dataType: "json",
             url: "index.php?r=api/updateStuOnLine&&classID=<?php echo $classID;?>&&userid=<?php echo Yii::app()->session['userid_now']?>",
             data: {},
             success: function(){ console.log("set time");},
                error: function(xhr, type, exception){
                    console.log(xhr, "Failed");
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    
                }
         });
        return false;
　　　}
function pollBulletin() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?r=api/GetLatestBulletin&&classID=<?php echo $classID;?>",
        success: function(data) {
            if (role === 'student') {
                $("#bulletin-textarea").val(data[0].content);
            } else {
                if ($("#bulletin-textarea").val() === "") {
                    $("#bulletin-textarea").val(data[0].content);
                }
            }
        },
        error: function(xhr, type, exception){
            console.log('get Bulletin erroe', type);
            console.log(xhr.responseText, "Failed");
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
    var ws = null;
    var last_path = -1;
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
            $("#ppt-container").hide();
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
                iframe_b.location.href ='./index.php?r=webrtc/stuCam&&classID=<?php echo $classID;?>';
                onCam   =  1;
            }
        } else if(msg.indexOf('<?php echo $classID;?>playppt') >= 0){
            var ppt_src = msg.substr(msg.indexOf('playppt')+7);
            if(last_path==-1)
            {
                $("#dianbo-videos-container").hide();
                $("#ppt-container").show();
            }
            if(ppt_src!=last_path)
            {
                $("#ppt-img").attr("src", ppt_src);
                last_path = ppt_src;
            }
        } else if(msg.indexOf('<?php echo $classID;?>closeppt') >= 0){
            last_path = -1;
            $("#ppt-container").hide();
        } 
    }
    
    function addMessageHandleForWS(){
        ws.onopen = function() {
            console.log("connect to websocket server");
        };
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