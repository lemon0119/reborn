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
<div class="left" style="min-height: 793px">
    <div class="vp1" style="width: 100%;">
        <br/>
                <tr>
                    <td style="font-weight: bolder"><span style="font-weight: bolder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;学号:</span></td>
                    <td><span style="color: #f46500">&nbsp;&nbsp;<?php echo $userID;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bolder"><span style="font-weight: bolder">&nbsp;&nbsp;学生姓名:</span></td>
                    <td><span style="color: #f46500">&nbsp;&nbsp;<?php echo $userName;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bolder"><span style="font-weight: bolder">&nbsp;&nbsp;班级:</span></td>
                    <td><span style="color: #f46500">&nbsp;&nbsp;<?php $sqlClass = TbClass::model()->find("classID = $class");
                    echo $sqlClass['className'];
                    ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bolder"><span style="font-weight: bolder">&nbsp;&nbsp;当前课程:</span></td>
                    <td><span style="color: #f46500">&nbsp;&nbsp;<?php foreach ($lessons as $less) {
                               if($less['lessonID'] === $currentLesn){
                                   echo $less['lessonName'];
                                }
                           }?>
                    </span></td>           
                </tr>
    </div>
    
    <!-- local/remote videos container --> 
    <div id="ppt-container" align="center" style="width: 100% ; height:560px;;  margin-top:0px;display:none;overflow-x: hidden">
        <div id ="full-screen" style="position: relative; left: 200px; top: 40px;display:none;">
            <img src="<?php echo IMG_URL; ?>ppt-full-screen.png" onmouseover="fun3();" onclick="fun4()" style="opacity:0.3"/> 
        </div>
        <div id="ppt-asd">
        <img id="ppt-img"  onmouseover="fun1();" onmouseout="fun2();" src=""  style="height: 100%;"/>  
        </div>
    </div>
     <div id="classExercise-container" align="center" style="width: 100% ; height:800px;  margin-top:0px;display:none;overflow-x: hidden">
         <div><button id="exercise_again" onclick="reExercise()" style="margin-left: 10px;margin-right: 10px" class="fr btn" >再来一遍</button><button id="exercise_next" onclick="nextExercise()" <?php if(count($exerciseIsOpenNow)<2){echo "disabled='disabled'";}?> style="margin-left: 10px;margin-right: 10px" class="fr btn btn-primary">下一题</button><button id="exercise_last" onclick="lastExercise()" disabled="disabled" style="margin-left: 10px;margin-right: 10px" class="fr btn btn-primary" >上一题</button></div>
         <div style="height: 730px;">
            <iframe id="iframe_classExercise" name="iframe_classExercise" style="border: 0px;height: 100%;width: 95%;"></iframe>
        </div>
    </div>

    <div id="dianbo-videos-container" style="height:560px;display:none;">  </div>
    <div id="bulletin_activex">
    <object style="position: absolute;top:713px;" id="typeOCX" type="application/x-itst-activex" 
        clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
        width ='744' height='180'
        event_OnChange="onChange"
        event_OnStenoPress="onStenoPressKey">
</object>
        </div>
</div>

<div class="right"style="max-height: 1200px;background-color: #3b3b3b;border: 0px" >
    <div align="center" id="sw-teacher-camera"><a href="#" ><h4 style="color: white">教 师 视 频</h4></a></div>
    <div id="teacher-camera" style="border:0px solid #ccc; margin-left:auto;margin-right:auto;width:100%; height:280px; clear:both;">
        <iframe src="./index.php?r=webrtc/null" name="iframe_b" style="background-color:#5e5e5e;width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no" allowfullscreen></iframe>
    </div>
    <div align="center" ><h4 ><a href="#" id="sw-bulletin" style="position: relative;left:26px;color: white">虚 拟 键 盘</a><button class="fr btn" id="sw-openAnalysis">统计</button></h4></div>
        <div id="bulletin" class="bulletin" style="border: 0px;width: 100%;margin-left: -1.1px">
             <?php require  Yii::app()->basePath."\\views\\student\\keyboard_virtual_class.php";?>
        </div>
    <div class="fl" align="center" id="sw-chat" ><h4 ><a style="position: relative;left:145px;color: white"  href="#">课 堂 问 答</a></h4></div>
        <div id="chat-box" style="display:none;border: 0px">
            <div id="chatroom" class="chatroom" style="background-color:#5e5e5e;border: 0px;width: 100%"></div>
            <div class="sendfoot" style="width: 100%;height: 100%;border: 0px;margin-left: -1.5px">
                <input onfocus="setPress()"onblur="delPress()" type='text' id='messageInput' style="border: 0px;width:283px;height:26px; margin-top:0px;margin-bottom:0px;margin-right: 0px;color:gray" oninput="this.style.color='black'">
                <a id="send-msg"></a>
            </div>
           
        </div>
         <div align="center" >
       
    </div>
</div>
<div  class="analysisTool" id="analysis" style="display: none;left:1190px;bottom: 473px">
        <table style="margin: 0px auto;">
            <tr>
                <td><span class="fl"  style="color: #fff;font-weight: bolder">平均速度：</span><span style="color: greenyellow" id="getAverageSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span><span class="fr" style="color: #fff"> 字/分</span> </td></tr>
             <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时速度：</span><span style="color: greenyellow" id="getMomentSpeed">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>
                 <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高速度：</span><span style="color: greenyellow" id="getHighstSpeed">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>
            <tr>
                <td><span class="fl"  style="color: #fff;font-weight: bolder">平均击键：</span><span style="color: greenyellow" id="getAverageKeyType">0</span ><span class="fr" style="color: #fff"> 次/分</span></td></tr>
             <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时击键：</span><span style="color:greenyellow" id="getMomentKeyType">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高击键：</span><span style="color: greenyellow" id="getHighstCountKey">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">击键间隔：</span><span style="color: greenyellow" id="getIntervalTime">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高间隔：</span><span style="color: greenyellow" id="getHighIntervarlTime">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">总击键数：</span><span style="color: greenyellow" id="getcountAllKey">0</span ><span class="fr" style="color: #fff"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
            <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">回改字数：</span><span style="color: greenyellow" id="getBackDelete">0</span ><span class="fr" style="color: #fff"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
        </table>
    </div>
<script src="<?php echo JS_URL;?>exerJS/AnalysisTool.js"></script>
<script>
    //显示全屏图像
    var exerciseIsOpenNow = new Array();
    var isfinish = new Array();
    var nowOn = 0;
<?php foreach ($exerciseIsOpenNow as $key=>$value) { ?>
        exerciseIsOpenNow[<?php echo $key?>] = <?php echo $value['exerciseID']?>;
        isfinish[<?php echo $key?>] = 0;
  <?php  } ?>
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
    
    function delPress(){
         document.onkeydown=function(event){
         e = event ? event :(window.event ? window.event : null);
         e.returnValue=false;
         }
    }
    
    function setPress(){
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
                },
                success: function(result) {           
                                        if(result == "1")
                                        {
            				var txt=  "你被禁言了！";
					window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.confirm);
                                        } 
                 },   
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
            },
            success: function(result) {           
                                        if(result == "1")
                                        {
            				var txt=  "你被禁言了！";
					window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.confirm);
                                        } 
        },               
        });
    });
        
        }
        //开始课堂练习
        
</script>

<script>
    var isClassExercise = 0;
    
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
    // ------------------------------------------------------ start classExercise
    var timer = setInterval(function() {
        if(isClassExercise===0){
            startClassExercise();
        }else{
            clearInterval(timer);
        }
    }, 2000);
});

function startClassExercise(){
    $.ajax({
        type:"GET",
        url:"index.php?r=student/startClassExercise&&classID=<?php echo $classID;?>&&lessonID=<?php echo $currentLesn;?>",
        success:function(data){
            if(data===""){
            }else{
                isClassExercise=1;
                window.wxc.xcConfirm("有新练习发布，点击开始！", window.wxc.xcConfirm.typeEnum.info,{
                    onOk:function(){
                       var exerciseID = exerciseIsOpenNow[nowOn] ;
                                $("#sw-bulletin").unbind("click");
                        $("#classExercise-container").toggle(200);
                        if(data==="look"){
                            $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Look&exerciseID="+exerciseID);
                        }else if(data==="listen"){
                             $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Listen&exerciseID="+exerciseID);
                        }else if(data==='speed'||data==='correct'||data==='free'){
                             $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Key&exerciseID="+exerciseID);
                        }
                         if(!$("#bulletin").is(":hidden")){ 
                         $("#bulletin").toggle(200);
                         $("#bulletin_activex").toggle(200);
                        }
                        $("#sw-openAnalysis").attr("disabled","true");
                       $("#analysis").hide();
                    }
                });
                
            }
        }
    });
}

function passClassExercise(){
    var exerciseID = exerciseIsOpenNow[nowOn] ;
    $.ajax({
        type:"GET",
        url:"index.php?r=student/passClassExercise&&exerciseID="+exerciseID,
        success:function(data){
            if(data===""){
            }else{
                if(isfinish[nowOn]===0){
                     if(data==="look"){
                            $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Look&exerciseID="+exerciseID);
                        }else if(data==="listen"){
                             $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Listen&exerciseID="+exerciseID);
                        }else if(data==='speed'||data==='correct'||data==='free'){
                             $("#iframe_classExercise").attr("src","index.php?r=student/iframe4Key&exerciseID="+exerciseID);
                        }
                }else{
                    $("#iframe_classExercise").attr("src","index.php?r=student/iframe4finish&exerciseID="+exerciseID);
                }
             }
        }
    });
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
                     html += "<font color=\"#00FF00\">"+entry['username']+ "</font>" + "<font color=\"#fff\">" +"：" + entry['chat'] + "</font><br>";
                else
                     html += "<font color=\"#f46500\">"+entry['username']+ "</font>"+ "：" + entry['chat'] + "<br>";
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
             data:{},
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
                if(data[0]!== undefined){
                    $("#bulletin-textarea").val(data[0].content);
                }
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
        $("#analysis").hide();
    });
    $("#sw-bulletin").click(function() {
        if(!$("#bulletin").is(":hidden")){ 
            $("#sw-openAnalysis").attr("disabled","true");
        }
        if($("#bulletin").is(":hidden")){
            $("#sw-openAnalysis").removeAttr("disabled");
        }
        $("#bulletin").toggle(200);
        $("#bulletin_activex").toggle(200);
        $("#analysis").hide();
        document.getElementById("typeOCX").SetTextSize(8);
        document.getElementById("typeOCX").HideToolBar();
        document.getElementById("typeOCX").HideSecondToolBar();
    });
    $("#sw-openAnalysis").click(function() {
        $("#analysis").toggle(0);
        $("#chat-box").hide();
    });
});

</script>

<script>
    //点播
    var ws = null;
    var last_path = -1;
    var onCam     = -1;
    
    function clearVideo(){
        last_path = -1;
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
                html += '<video id="video1"  width="100%" controls>';
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

function closeClassExercise(){
    isClassExercise = 0;
    $("#classExercise-container").toggle(200);
    $("#iframe_classExercise").attr("src","");
     $("#sw-bulletin").click(function() {
        if(!$("#bulletin").is(":hidden")){ 
            $("#sw-openAnalysis").attr("disabled","true");
        }
        if($("#bulletin").is(":hidden")){
            $("#sw-openAnalysis").removeAttr("disabled");
        }
        $("#bulletin").toggle(200);
        $("#bulletin_activex").toggle(200);
        $("#analysis").hide();
        document.getElementById("typeOCX").SetTextSize(8);
        document.getElementById("typeOCX").HideToolBar();
        document.getElementById("typeOCX").HideSecondToolBar();
    });    
     if($("#bulletin").is(":hidden")){
            $("#sw-openAnalysis").removeAttr("disabled");
        }
                $("#analysis").hide();
      var timer = setInterval(function() {
        if(isClassExercise===0){
            startClassExercise();
        }else{
            clearInterval(timer);
        }
    }, 2000);
}


function nextExercise(){
    $("#exercise_last").removeAttr("disabled");
    if(nowOn === exerciseIsOpenNow.length-2){
        $("#exercise_next").attr("disabled","disabled");
      }
    if(nowOn<exerciseIsOpenNow.length){
      nowOn++;
      
      passClassExercise();
    }
}

function lastExercise(){
        $("#exercise_next").removeAttr("disabled");
        if(nowOn ===1){
          $("#exercise_last").attr("disabled","disabled");  
        }
        
    if(nowOn>0){
      nowOn--;  
      passClassExercise();
    }
}

function reExercise(){
    isfinish[nowOn] = 0;
    passClassExercise();
}

function alertStartKeyExercise(){
    window.wxc.xcConfirm("即将开始！您将有3秒准备时间！", window.wxc.xcConfirm.typeEnum.warning,{
            onOk:function(){
                iframe_classExercise.window.start();
            }
        });
}

function finish(){
    isfinish[nowOn] = 1;
    if(nowOn<exerciseIsOpenNow.length){
        window.wxc.xcConfirm("点击确定将进入下一个练习", window.wxc.xcConfirm.typeEnum.info,{
                    onOk:function(){
                        nextExercise();
                    }
                });
    }
}
</script>
