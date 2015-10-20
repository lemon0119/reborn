<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_URL; ?>socketio.js"></script>
<script>
　　　
   </script>
  
<!--直播begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
    <?php
   
    echo "<script>var current_username=\"$userName\";</script>";
    $role = Yii::app()->session['role_now'];
    echo "<script>var role='$role';</script>";
    $role               = Yii::app()->session['role_now'];
    $userid             = Yii::app()->session['userid_now'];
    $videoFilePath      = $role."/".$userid."/".$classID."/".$on."/video/"; 
    $vdir               = "./resources/".$videoFilePath;                 
    $pptFilePath        = $role."/".$userid."/".$classID."/".$on."/ppt/"; 
    $pdir               = "./resources/".$pptFilePath;
    
    $courseID           = TbClass::model()->findCourseIDByClassID($classID);
    $adminPptFilePath   = "admin/001/$courseID/$on/ppt/"; 
    $adminPdir          = "./resources/admin/001/$courseID/$on/ppt/";
    $adminVideoFilePath = "admin/001/$courseID/$on/video/"; 
    $adminVdir          = "./resources/admin/001/$courseID/$on/video/";
    ?>

    <div class="left">
            <div style="display:inline;">
                <button id="teacher-dianbo" class="btn btn-primary">点播视频</button>
                <select id="teacher-choose-file" style="width:150px">
                    <?php
                    	$mydir = dir($adminVdir); 
                        while($file = $mydir->read())
                        { 
                                if((!is_dir("$$adminVdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {
                    ?>
                    <option value ="<?php echo $adminVideoFilePath.iconv("gb2312","UTF-8",$file);?>"><?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?></option>   
                    <?php     
                                } 
                        } 
                        $mydir->close(); 
                    ?>
                    <?php
                    	$mydir = dir($vdir); 
                        while($file = $mydir->read())
                        { 
                                if((!is_dir("$vdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {
                    ?>
                    <option value ="<?php echo $videoFilePath.iconv("gb2312","UTF-8",$file);?>"><?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?></option>   
                    <?php     
                                } 
                        } 
                        $mydir->close(); 
                    ?>
                </select>
                <button id="close-dianbo" class="btn" disabled="disabled">关闭点播</button>
                <button id="share-Cam" class="btn btn-primary" style="margin-left: 200px">直播视频</button>
                <button id="close-Cam" class="btn" disabled="disabled">关闭直播</button>
            </div>
        
            <div style="display:block;"></div>
            <div style="display:inline;">
                <button id="play-ppt" class="btn btn-primary">放映PPT</button>
                <select id="choose-ppt" style="width:150px">
                    <?php
                    	$mydir = dir($adminPdir); 
                        while($file = $mydir->read())
                        { 
                                if((is_dir("$adminPdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {
                    ?>
                    <option value ="<?php echo iconv("gb2312","UTF-8",$file);?>+-+<?php   
                                                                    $dir = "$adminPdir/$file"; 
                                                                    $num = sizeof(scandir($dir)); 
                                                                    $num = ($num>2)?($num-2):0; 
                                                                    echo $num;?>+-+admin"><?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file.".ppt"));?></option>   
                    <?php     
                                } 
                        } 
                        $mydir->close(); 
                    ?>
                    <?php
                    	$mydir = dir($pdir); 
                        while($file = $mydir->read())
                        { 
                                if((is_dir("$pdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {
                    ?>
                    <option value ="<?php echo iconv("gb2312","UTF-8",$file);?>+-+<?php   
                                                                    $dir = "$pdir/$file"; 
                                                                    $num = sizeof(scandir($dir)); 
                                                                    $num = ($num>2)?($num-2):0; 
                                                                    echo $num;?>+-+tea"><?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file.".ppt"));?></option>   
                    <?php     
                                } 
                        } 
                        $mydir->close(); 
                    ?>
                </select>
                <button id="close-ppt" class="btn" disabled="disabled">停止放映</button>
            </div>
        
            <div id="scroll-page" style="display:inline;">
                <button id="page-up" class="btn btn-primary">上页</button>
                <input id="yeshu" style="width:50px;" value="1">
                /<input id="all-yeshu" style="width:50px;" readOnly="true">
                <button id="page-go" class="btn btn-primary">跳转</button>
                <button id="page-down" class="btn btn-primary">下页</button>
                <button id="full-screen-button" class="btn btn-primary">全屏</button>
            </div>

            <div id="videos-container" style="height: 100%; width: 100%; margin-top:0px;display:none">
                <iframe src="" name="iframe_a" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
            </div>
            <div id="dianbo-videos-container" style="display:none">  
            </div>
            <div id="ppt-container" align="center" style="width: 100% ; height: 100%;  margin-top:0px;display:none">
                <img id="ppt-img" src="" style="height: 100%;"/>
            </div>
    </div>

    <div class="right">
        <div>
            <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>  
            <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:220px; clear:both;">
                <iframe src="./index.php?r=webrtc/teaCam&&classID=<?php echo $classID;?>" name="iframe_b" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no" allowfullscreen></iframe>
            </div>
            <div align="center" id="sw-bulletin"><a href="#"><h4>通 知 公 告</h4></a></div>
            <div id="bulletin" class="bulletin" style="display:none">

            <textarea id="bulletin-textarea" style="color:red;margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"oninput="this.style.color='red'"></textarea>
            <button id="postnotice" name="发布公告" class="btn btn-primary" style="margin-left: 100px; margin-top: 5px;">发布公告</button>

            </div>
            <div align="center" id="sw-chat"><a href="#"><h4>课 堂 问 答</h4></a></div>
            <div id="chat-box">
                <div id="chatroom" class="chatroom"></div>
            <div class="sendfoot">
                <input type='text' id='messageInput' style="width:55%;margin-top:0px;margin-bottom:0px;color:gray" oninput="this.style.color='black'">
                <a id="send-msg" ></a>
            </div>
            </div>
        </div>
    </div>
<script>
//全屏
    $('#full-screen-button').on('click', function(){
    var docelem         = document.getElementById('ppt-container');
    if (docelem.requestFullscreen) {
        docelem.requestFullscreen();
    }else if (docelem.webkitRequestFullscreen) {
        docelem.webkitRequestFullscreen();
    } else if(docelem.mozRequestFullScreen) {
        docelem.mozRequestFullScreen();
    } else if(docelem.msRequestFullscreen) {
        docelem.msRequestFullscreen();
    } 
    window.wxc.xcConfirm("按方向键左右进行跳转，按Esc退出！", window.wxc.xcConfirm.typeEnum.info);
    });
    
    function keyDown(e) {   
  　　  var keycode = e.which;   　　 　　   
//        var realkey = String.fromCharCode(e.which);   　　 　　    
//        alert("按键码: " + keycode + " 字符: " + realkey);
        if(cur_ppt!=-1)
        {
            if(keycode == 37)
            {
                pageUp();
            }else if(keycode == 39)
            {
                pageDown();
            }
        }
    } 　　   
    document.onkeydown      = keyDown;
</script>

<script>
    //chat and bulletin
$(document).ready(function(){
    /*
   $("div.container div.navbar div.navbar-inner div.container div.nav-collapse ul.nav li.dropdown ul.dropdown-menu li").find("a").click(function() {
            var url=$(this).attr("href");
            if(url.indexOf("index.php")>0){
                $.ajax({
                    type: "POST",
                    url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID;?>",
                    data: {},
                    success: function(){ 
                        window.location.href = url;
                        //window.wxc.xcConfirm('更新成功！', window.wxc.xcConfirm.typeEnum.success);
                        },
                    error: function(xhr, type, exception){
                        window.wxc.xcConfirm('出错了呀...', window.wxc.xcConfirm.typeEnum.error);
                        console.log(xhr.responseText, "Failed");
                    }
                });
                return false;
            }
    });*/
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();

    $("#postnotice").click(function() {
        var text = $("#bulletin-textarea").val();
        $.ajax({
            type: "POST",
            url: "index.php?r=api/putBulletin&&classID=<?php echo $classID;?>",
            data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'},
            success: function(){ window.wxc.xcConfirm('公告发布成功！', window.wxc.xcConfirm.typeEnum.success);},
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr.responseText, "Failed");
            }
        });
    });
    //每5秒，发送一次时间
    setInterval(function() {    //setInterval才是轮询，setTimeout是一定秒数后，执行一次的！！
        checkLeave();
    }, 5000);
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
                time: '"' + current_time + '"'
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
function checkLeave(){
        $.ajax({
             type: "POST",
             url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID;?>",
             data: {},
             success: function(){ console.log("set time");},
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });
        return false;
　　　}
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

function pollBulletin() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?r=api/GetLatestBulletin&&classID=<?php echo $classID;?>",
        success: function(data) {
            console.log(data[0]);
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
    function js_method(){
        alert("ty");
    }
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
        if($("#choose-ppt")[0].selectedIndex == -1 )
        {    return;    }
        window.scrollTo(0,130);
        document.getElementById("teacher-dianbo").disabled = true;
        $("#teacher-dianbo").attr("class","btn");
        document.getElementById("close-ppt").disabled = false;
        $("#close-ppt").attr("class","btn btn-primary");
        $("#ppt-container").show();
        $("#scroll-page").show();
        cur_ppt = 1;
        var file_info = $("#choose-ppt option:selected").val().split("+-+");
        var source    = file_info[2];
        var server_root_path;
        if(source === "tea")
        {
            server_root_path = "<?php echo SITE_URL.'resources/'.$pptFilePath;?>";
        }else {
            server_root_path = "<?php echo SITE_URL.'resources/'.$adminPptFilePath;?>";
        }
        var dirname = file_info[0];
        ppt_dir = server_root_path + dirname;
        ppt_pages = file_info[1];
        $("#all-yeshu").val(ppt_pages);
        goCurPage();
        if(timer_ppt!==null)
            clearInterval(timer_ppt);
        timer_ppt = setInterval(function() {
            var syn_msg;                                 
            syn_msg = "<?php echo $classID;?>playppt"+$("#ppt-img")[0].src;                                        
            ws.send(syn_msg);
        }, 4000);
    });
    $("#page-up").click(function(){
        pageUp();
    });
    $("#page-go").click(function(){
        var input_page =$("#yeshu").val();
        input_page =input_page - 1 + 1;
        if((input_page>=1)&&(input_page<=ppt_pages))
        {
            cur_ppt=input_page;
            goCurPage();
        }else{
            window.wxc.xcConfirm("请输入合适范围的页数！", window.wxc.xcConfirm.typeEnum.info);
        }
    });
    $("#page-down").click(function(){
        pageDown();
    });
    $("#close-ppt").click(function(){
        cur_ppt     = -1;
        ppt_pages   = -1;
        if(timer_ppt!==null)
            clearInterval(timer_ppt);
        var msg = "<?php echo $classID;?>closeppt";   
        ws.send(msg);
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
        if($("#teacher-choose-file")[0].selectedIndex == -1 )
        {    return;    }
        window.scrollTo(0,130);
        document.getElementById("play-ppt").disabled = true;
        $("#play-ppt").attr("class","btn");
        document.getElementById("close-dianbo").disabled = false;
        $("#close-dianbo").attr("class","btn btn-primary");
        var server_root_path = "<?php echo SITE_URL.'resources/'?>";
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

var play_fun        =   null;
var pause_fun       =   null;
var timer           =   null;
var timer_ppt       =   null;
var timer_cam       =   null;
var ws              = null;
var cur_ppt         = -1;
var ppt_pages       = -1;
var ppt_dir         = null;

function goCurPage(){
    $("#yeshu").val(cur_ppt);
    $("#ppt-img").attr("src", ppt_dir+"/幻灯片"+cur_ppt+".JPG");
    var msg = "<?php echo $classID;?>playppt"+$("#ppt-img")[0].src;   
    ws.send(msg);
}
function pageUp(){
    if(cur_ppt<=1){
        cur_ppt=1;
        window.wxc.xcConfirm("已到第一页！", window.wxc.xcConfirm.typeEnum.info);
    }else{
        cur_ppt = cur_ppt -1;
    }
    goCurPage();
}

function pageDown(){
    if(cur_ppt>=ppt_pages){
        cur_ppt=ppt_pages;
        window.wxc.xcConfirm("已到最后页！", window.wxc.xcConfirm.typeEnum.info);
    }else{
        cur_ppt = cur_ppt +1;
    }
    goCurPage();
}


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