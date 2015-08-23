<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_URL; ?>socketio.js"></script>

<!--直播begin-->
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />

    <?php
    $username = Yii::app()->user->name;
    echo "<script>var current_username=\"$username\";</script>";

    $role = Yii::app()->session['role_now'];
    echo "<script>var role='$role';</script>";
    ?>

    <div class="left">                 

            <div style="display:inline;padding-right:60px;">
                <!-- sunpy: video start -->
                <button id="share-Cam" class="btn btn-primary">直播视频</button>
                <button id="close-Cam" class="btn" disabled="disabled">关闭直播</button>
            </div>

            <div style="display:inline;">
                <!-- sunpy: broadcast local video -->    
                <button id="teacher-dianbo" class="btn btn-primary">点播视频</button>
                <select id="teacher-choose-file" >
                    <option value ="1.mp4">速录演示视频</option>
                    <option value="CB6601435D33002ECE7BAD33F79126D6.flv">MV-不见长安</option>
                    <option value ="h0134j6z7lp.mp4">MV-时间都去哪了</option>
                    <option value="l0015jn2cz8.mp4">MV-泡沫</option>
                </select>　　
                <button id="close-dianbo" class="btn" disabled="disabled">关闭点播</button>
            </div>
        
            <div style="display:inline;">
                <!-- sunpy: broadcast local video -->    
                <button id="play-ppt" class="btn btn-primary">放映PPT</button>
                <select id="choose-ppt" >
                    <option value ="test+-+<?php  $dir = 'D:/wamp/www/reborn/resources/'.'test'; 
                                                   $num = sizeof(scandir($dir)); 
                                                   $num = ($num>2)?($num-2):0; 
                                                   echo $num;?>">test</option>
                    <option value ="test2+-+<?php  $dir = 'D:/wamp/www/reborn/resources/'.'test2'; 
                                                   $num = sizeof(scandir($dir)); 
                                                   $num = ($num>2)?($num-2):0; 
                                                   echo $num;?>">test2</option>
                </select>　　
                <button id="close-ppt" class="btn" disabled="disabled">停止放映</button>
            </div>
        
            <div id="scroll-page" style="display:inline;">
                <button id="page-up" class="btn btn-primary">上页</button>
                <input id="yeshu" style="width:50px;" value="1">
                /<input id="all-yeshu" style="width:50px;" readOnly="true">
                <button id="page-go" class="btn btn-primary">跳转</button>
                <button id="page-down" class="btn btn-primary">下页</button>
            </div>

            <div id="videos-container" style="height: 1000px; width: 100%; margin-top:0px;display:none">
                <iframe src="" name="iframe_a" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
            </div>
            <div id="dianbo-videos-container" style="margin-top:18px;display:none">  
            </div>
            <div id="ppt-container" align="center" style="height: 1000px; width: 100% ; margin-top:0px;display:none">
                <img id="ppt-img" src="" style="width: 100%;"/>
            </div>
    </div>

    <div class="right">
        <div>
            <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>  
            <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;">
                <iframe src="./index.php?r=webrtc/teaCam&&classID=<?php echo $classID;?>" name="iframe_b" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
            </div>
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
            //console.log(data[0].id);
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
$("#scroll-page").hide();
$(document).ready(function(){
    //打开连接
    openConnect();
      
    $("#play-ppt").click(function(){
        document.getElementById("teacher-dianbo").disabled = true;
        $("#teacher-dianbo").attr("class","btn");
        document.getElementById("close-ppt").disabled = false;
        $("#close-ppt").attr("class","btn btn-primary");
        $("#ppt-container").show();
        $("#scroll-page").show();
        var server_root_path = "<?php echo SITE_URL.'resources/'?>";
        var file_info = $("#choose-ppt option:selected").val().split("+-+");
        var filename = file_info[0];
        var absl_path = server_root_path + filename;
        $("#all-yeshu").val(file_info[1]);
        $("#ppt-img").attr("src", absl_path+"/幻灯片1.JPG");
    });
    $("#close-ppt").click(function(){
        this.disabled = true;
        $("#close-ppt").attr("class","btn");
        document.getElementById("play-ppt").disabled = false;
        document.getElementById("teacher-dianbo").disabled = false;
        $("#teacher-dianbo").attr("class","btn btn-primary");
        $("#ppt-container").hide();
        $("#scroll-page").hide();
    });
      
    $("#share-Cam").click(function() {
    });
      
    $("#close-Cam").click(function() {
        var msg = "<?php echo $classID;?>closeCam";
        ws.send(msg);
        if(timer_cam!=null)
            clearInterval(timer_cam);
        this.disabled = true;
        $("#close-Cam").attr("class","btn");
        document.getElementById("share-Cam").disabled = false;
        $("#share-Cam").attr("class","btn btn-primary");
        iframe_b.location.href ='./index.php?r=webrtc/null'; 
        iframe_b.location.href ='./index.php?r=webrtc/teaCam&&classID=<?php echo $classID;?>';
      });
    
    $("#teacher-dianbo").click(function() {
        document.getElementById("play-ppt").disabled = true;
        $("#play-ppt").attr("class","btn");
        document.getElementById("close-dianbo").disabled = false;
        $("#close-dianbo").attr("class","btn btn-primary");
        var server_root_path = "<?php echo SITE_URL.'resources/'?>video/";
        var filepath = $("#teacher-choose-file option:selected").val();
        var absl_path = server_root_path + filepath;
        var video_element;
        var video_time_duration;

        console.log("Choose file " + server_root_path + filepath);

        var video = document.getElementById('video1');
        if(video===null){
            var html = "";
            html += '<video id="video1" width="100%" controls>';
            html += '<source src="' + absl_path + '">';
            html += '</video>';
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
    
      $("#close-dianbo").click(function() {
        document.getElementById("play-ppt").disabled = false;
        $("#play-ppt").attr("class","btn btn-primary");
        clearVideo();
        this.disabled = true;
        $("#close-dianbo").attr("class","btn");
      });
});

var play_fun    =   null;
var pause_fun   =   null;
var timer       =   null;
var timer_screen      =   null;
var timer_cam         =   null;
var ws = null;
function openConnect(){
    if(ws !== null)
        return ;
    ws = new WebSocket("wss://<?php echo HOST_IP;?>:8443", 'echo-protocol');// initializing the connection through the websocket api
    ws.onclose = function(event) {
          console.log("与点播服务器的连接断开...");
    };
}

function closeConnect(){
    if(ws === null)
        return;
    var message_sent = "<?php echo $classID;?>close";
    ws.send(message_sent);
    ws.close();
    ws = null;
}

function clearVideo(){
    var myVideo = document.getElementById("video1");
    if(play_fun != null)
    {
        myVideo.removeEventListener("play",play_fun);
        myVideo.removeEventListener("pause",pause_fun);
    }
    //   teacher side broadcasts sync msg
    //   progress + path
    if(timer!=null)
        clearInterval(timer);
    var message_sent = "<?php echo $classID;?>closeVideo";
    ws.send(message_sent);
    $("#dianbo-videos-container").empty();
    $("#dianbo-videos-container").hide();
};

function WebSocketConnect(absl_path){
    console.log("sunpy [WebSocketConnect]");
    var myVideo = document.getElementById("video1");
    if(play_fun != null)
    {
        myVideo.removeEventListener("play",play_fun);
        myVideo.removeEventListener("pause",pause_fun);
    }
    //   teacher side broadcasts sync msg
    //   progress + path
    if(timer!=null)
        clearInterval(timer);
    timer = setInterval(function() {
        var syn_msg;
        var video_current_time = myVideo.currentTime;                                    

        if (myVideo.paused) {
            syn_msg = "<?php echo $classID;?>pauseSync " +absl_path+"-----" +video_current_time;                                        
        } else {
            syn_msg = "<?php echo $classID;?>playSync " +absl_path+"-----"+ video_current_time;
        }
        ws.send(syn_msg);
    }, 4000);
    
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