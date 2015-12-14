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
$role = Yii::app()->session['role_now'];
$userid = Yii::app()->session['userid_now'];
$videoFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
$vdir = "./resources/" . $videoFilePath;
$pptFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
$pdir = "./resources/" . $pptFilePath;

$courseID = TbClass::model()->findCourseIDByClassID($classID);
$adminPptFilePath = "admin/001/$courseID/$on/ppt/";
$adminPdir = "./resources/admin/001/$courseID/$on/ppt/";
$adminPublicPdir = "./resources/public/ppt/";
$adminVideoFilePath = "admin/001/$courseID/$on/video/";
$adminPublicVdir = "./resources/public/video/";
$adminVdir = "./resources/admin/001/$courseID/$on/video/";
?>

<div class="left">
    <div id="title_movie" class="title_select" style="border-bottom-left-radius: 5px;border-top-left-radius: 5px;" >
        <div   align="center"  id="sw-movie"><h4 style="line-height: 40px;">视 频 </h4></div>

    </div>
    <div id="title_picture" class="title_select" >
        <div   align="center" id="sw-picture"><h4 style="line-height: 40px;">图 片 </h4></div>
        <div id="show-picture"  class="online" style="display: none;border: 0px;width:100px;">
            <div id="dd1" disabled="disabled" style="overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:300px; padding:0;">
                <text id="dd11"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
                <text id="dd21"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
            </div>
        </div>
    </div>
    <div id="title_text" class="title_select" >
        <div   align="center" id="sw-text"><h4 style="line-height: 40px;">文 本 </h4></div>
        <div id="show-text"  class="online" style="display: none;border: 0px;width:100px;">
            <div id="dd1" disabled="disabled" style="overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:300px; padding:0;">
                <text id="dd11"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
                <text id="dd21"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
            </div>
        </div>
    </div>
    <div id="title_ppt" class="title_select" >
        <div   align="center" id="sw-ppt"><h4 style="line-height: 40px;">PPT </h4></div>
    </div>
    <div id="title_video" class="title_select" >
        <div   align="center" id="sw-video"><h4 style="line-height: 40px;">音 频 </h4></div>
        <div id="show-video"  class="online" style="display: none;border: 0px;width:100px;">
            <div id="dd1" disabled="disabled" style="overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:300px; padding:0;">
                <text id="dd11"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
                <text id="dd21"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
            </div>
        </div>
    </div>
    <div id="title_classExercise" class="title_select" >
        <div  align="center" id="sw-classExercise"><h4 >课 堂<br/>作 业 </h4></div>
        <div id="show-classExercise"  class="online" style="display: none;border: 0px;width:100px;">
            <div id="dd1" disabled="disabled" style="overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:300px; padding:0;">
                <text id="dd11"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
                <text id="dd21"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
            </div>
        </div>
    </div>
    
    <div id="title_bull" class="title_select" style="width: 185px;border-bottom-right-radius: 5px;border-top-right-radius: 5px;" >
        <div   align="center" id="sw-bull"><h4>本 班：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $totle ?>人<br/>在 线 学 生: <font style="color: greenyellow"><?php echo $count ?></font> 人</h4></div>

    </div>
     <button id="share-Cam" class="btn btn-primary" >直播视频</button>
    <button id="close-Cam" class="btn" disabled="disabled">关闭直播</button>
    <button onclick="checkforbid()" class="btn">查看禁言</button>
    
    <div id="showOnline"  class="online" style="display: none;border: 0px;width:100px;">
        <div id="dd" disabled="disabled" style="overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:300px; padding:0;">
            <text id="dd1"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
            <text id="dd2"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
        </div>
    </div>
   
    <div id="show-movie"  class="online" style="display: none;border: 0px;width:100px;">
        <div style="display:inline;">
            <button id="teacher-dianbo" class="btn btn-primary">点播视频</button>
            <select id="teacher-choose-file" style="width:150px;margin-top: 10px;">
                <?php
                $mydir = dir($adminVdir);
                while ($file = $mydir->read()) {
                    if ((!is_dir("$$adminVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                        ?>
                        <option value ="<?php echo $adminVideoFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                        <?php
                    }
                }
                $mydir->close();
                ?>
                <?php
                $mydir = dir($vdir);
                while ($file = $mydir->read()) {
                    if ((!is_dir("$vdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                        ?>
                        <option value ="<?php echo $videoFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                        <?php
                    }
                }
                $mydir->close();
                ?>
            </select>
            <button id="teacher-dianbo-public" class="btn btn-primary">点播视频</button>
            <select id="teacher-choose-file-public" style="width:150px;margin-top: 10px;">
                <?php
                $mydir = dir($adminPublicVdir);
                while ($file = $mydir->read()) {
                    if ((!is_dir("$adminPublicVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                        ?>
                        <option value ="<?php echo $adminPublicVdir . iconv("gb2312", "UTF-8", $file); ?>"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                        <?php
                    }
                }
                $mydir->close();
                ?>
            </select>

        </div>
    </div>
    <div id="show-ppt"  class="online" style="position: relative;left: 70px;display: none;border: 0px;width:100px;">
            <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >备 课<br/>资 源 </h4></div>
        <div style="display:inline;width:150px;">
            <div  style="width:150px;position:relative;right: 200px ">
                <br/>
            <select id="choose-ppt" style="width:150px;margin-top: 10px;">
<?php
$mydir = dir($adminPdir);
while ($file = $mydir->read()) {
    if ((is_dir("$adminPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
        ?>
                        <option value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                        $dir = "$adminPdir/$file";
                        $num = sizeof(scandir($dir));
                        $num = ($num > 2) ? ($num - 2) : 0;
                        echo $num;
                        ?>+-+admin"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                        <?php
                    }
                }
                $mydir->close();
                ?>
                        <?php
                        $mydir = dir($pdir);
                        while ($file = $mydir->read()) {
                            if ((is_dir("$pdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                                ?>
                        <option value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                                $dir = "$pdir/$file";
                                $num = sizeof(scandir($dir));
                                $num = ($num > 2) ? ($num - 2) : 0;
                                echo $num;
                                ?>+-+tea"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                                <?php
                            }
                        }
                        $mydir->close();
                        ?>
            </select>
                 <button id="play-ppt" style="width: 150px;" class="btn btn-primary">放映PPT</button>
            </div>
            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 70px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 150px;left: 100px ">
            <select id="choose-ppt-public" style="width:150px;margin-top: 10px;">
<?php
$mydir = dir($adminPublicPdir);
while ($file = $mydir->read()) {echo $adminPublicPdir."/".$file;
    if ((is_dir("$adminPublicPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
        ?>
                        <option value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                        $dir = "$adminPublicPdir/$file";
                        $num = sizeof(scandir($dir));
                        $num = ($num > 2) ? ($num - 2) : 0;
                        echo $num;
                        ?>+-+admin"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                        <?php
                    }
                }
                $mydir->close();
                ?>
            </select>
                <button id="play-ppt-public" style="width: 150px;" class="btn btn-primary">放映PPT</button>
            </div>
        </div>
    </div> 
<div id="scroll-video" style="display:inline;">
 <button id="close-dianbo" class="btn" disabled="disabled">关闭点播</button> 
</div>
    <div id="scroll-page" style="display:inline;">
        <button id="page-up" class="btn btn-primary">上页</button>
        <input id="yeshu" style="width:50px;" value="1">
        <input id="all-yeshu" style="width:50px;" readOnly="true">
        <button id="page-go" class="btn btn-primary">跳转</button>
        <button id="page-down" class="btn btn-primary">下页</button>
        <button id="full-screen-button" class="btn btn-primary">全屏</button>
        <button id="close-ppt" class="btn" disabled="disabled">停止放映</button>
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
<div class="right"style="background-color: #3b3b3b;border: 0px" >

    <div align="center" id="sw-teacher-camera"><a href="#"><h4 style="color: white">教 师 视 频</h4></a></div>  
    <div id="teacher-camera" style="border:0px solid #ccc; margin-left:auto;margin-right:auto;width:100%; height:280px; clear:both;">
        <iframe src="./index.php?r=webrtc/teaCam&&classID=<?php echo $classID; ?>" name="iframe_b" style="background-color:#5e5e5e; width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no" allowfullscreen></iframe>
    </div>
    <!--            <div align="center" id="sw-bulletin"><a href="#"><h4 style="color: white">通 知 公 告</h4></a></div>
                <div id="bulletin" class="bulletin" style="display:none;border: 0px;width: 100%;margin-left: -1.1px">
    
                <textarea id="bulletin-textarea" style="background-color:#5e5e5e;color:yellow;margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"oninput="this.style.color='red'"></textarea>
                <a id="postnoticeTea"></a>
                
    
                </div>-->
    <div align="center" id="sw-chat"><a href="#"><h4 style="color: white">课 堂 问 答</h4></a> </div>            
    <div id="chat-box" style="border: 0px">   
        <div id="chatroom" class="chatroom" style="background-color:#5e5e5e;border: 0px;width: 100%">
        </div>

        <div class="sendfoot" style="width: 100%;height: 100%;border: 0px;margin-left: -1.5px">
            <input onfocus="setPress()"onblur="delPress()" type='text' id='messageInput' style="border: 0px;width:283px;height:26px; margin-top:0px;margin-bottom:0px;margin-right: 0px;color:gray" oninput="this.style.color='black'">
            <a  id="send-msg"></a>

        </div>
    </div>

</div>
<script>
//全屏
    $('#full-screen-button').on('click', function () {
        window.wxc.xcConfirm("按方向键左右进行跳转，按Esc退出！", window.wxc.xcConfirm.typeEnum.warning, {
            onOk: function () {
                var docelem = document.getElementById('ppt-container');
                if (docelem.requestFullscreen) {
                    docelem.requestFullscreen();
                } else if (docelem.webkitRequestFullscreen) {
                    docelem.webkitRequestFullscreen();
                } else if (docelem.mozRequestFullScreen) {
                    docelem.mozRequestFullScreen();
                } else if (docelem.msRequestFullscreen) {
                    docelem.msRequestFullscreen();
                }
            }
        });
    });

    function keyDown(e) {
        var keycode = e.which;
     
//     var realkey = String.fromCharCode(e.which);   　　 　　    
                console.log("按键码: " + " 字符: ");
        if (cur_ppt != -1)
        {
            if (keycode == 37)
            {
                pageUp();
            } else if (keycode == 39)
            {
                pageDown();
            }
        }
    }
 
            document.onkeydown = keyDown;

    function delPress() {
        document.onkeydown = function (event) {
            e = event ? event : (window.event ? window.event : null);
            e.returnValue = false;
        }
    }
    function setPress() {
        //綁定enter
        document.onkeydown = function (event) {
            e = event ? event : (window.event ? window.event : null);
            if (e.keyCode == 13) {
                var messageField = $('#messageInput');
                var msg = messageField.val();
                messageField.val('');

                var current_date = new Date();
                var current_time = current_date.toLocaleTimeString();
                $.ajax({
                    type: "POST",
                    url: "index.php?r=api/putChat&&classID=<?php echo $classID; ?>",
                    data: {
                        username: '"' + current_username + '"',
                        chat: '"' + msg + '"',
                        time: '"' + current_time + '"'
                    }
                });
            }
        }
    }
</script>

<script>
    //chat and bulletin   
    $(document).ready(function () {
        /*
         $("div.container div.navbar div.navbar-inner div.container div.nav-collapse ul.nav li.dropdown ul.dropdown-menu li").find("a").click(function() {
         var url=$(this).attr("href");
         if(url.indexOf("index.php")>0){
         $.ajax({
         type: "POST",
         url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID; ?>",
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
        $("#postnoticeTea").click(function () {
            var text = $("#bulletin-textarea").val();
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putBulletin&&classID=<?php echo $classID; ?>",
                data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'},
                success: function () {
                    window.wxc.xcConfirm('公告发布成功！', window.wxc.xcConfirm.typeEnum.success);
                },
                error: function (xhr, type, exception) {
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr.responseText, "Failed");
                }
            });
        });

        $("#send-msg").click(function () {
            var messageField = $('#messageInput');
            var msg = messageField.val();
            messageField.val('');
            var current_date = new Date();
            var current_time = current_date.toLocaleTimeString();
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putChat&&classID=<?php echo $classID; ?>",
                data: {
                    username: '"' + current_username + '"',
                    chat: '"' + msg + '"',
                    time: '"' + current_time + '"', }
            });
        });


        //每5秒，发送一次时间
        setInterval(function () {    //setInterval才是轮询，setTimeout是一定秒数后，执行一次的！！
            checkLeave();
        }, 5000);

        // ------------------------------------------------------ on line
        setInterval(function () {
            getBackTime();
            //freshOnline();
        }, 4000);
        // ------------------------------------------------------ poll latest bulletin
        /*第一次读取最新通知*/
        setTimeout(function () {
            pollBulletin();
        }, 200);
        /*30轮询读取函数*/
        setInterval(function () {
            pollBulletin();
        }, 10000);
        // ------------------------------------------------------ poll chat
        setInterval(function () {
            pollChatRoom();
        }, 1000);

        // ------------------------------------------------------ send chat

    });

    function checkLeave() {
        $.ajax({
            type: "POST",
            url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID; ?>",
            data: {},
            success: function () {
                console.log("set time");
            },
            error: function (xhr, type, exception) {
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
        });
        return false;
        }
    function getBackTime() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/GetStuOnLine&&classID=<?php echo $classID; ?>",
            success: function (data) {
                console.log("qq", data);
                var now =<?php echo time() ?>;    //这个时间是页面进入的时候，生成的。
                //虽然点击的时候，才会执行这个js代码，但是，php是加载的时候就已经生成了
                //也就是说，等到用户点击，这个时间now的值，是加载页面的时间。
                //var user = new Array(0,1,2,3,4);
                $("#ff").val(data[2]);
                var content = data[0].join("<br/>&nbsp;&nbsp;&nbsp;&nbsp;");
                var content2 = data[1].join("<br/>&nbsp;&nbsp;&nbsp;&nbsp;");
                $("#dd1").html("&nbsp;&nbsp;&nbsp;&nbsp;" + content);
                $("#dd2").html("<br/>&nbsp;&nbsp;&nbsp;&nbsp;" + content2);
            },
            error: function (xhr, type, exception) {
                console.log('get backtime erroe', type);
                console.log(xhr, "Failed");
            }
        });
    }
    function pollChatRoom() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/getlatestchat&&classID=<?php echo $classID; ?>",
            success: function (data) {
                $("#chatroom").empty();
                var html = "";
                var obj = eval(data);
                $.each(obj, function (entryIndex, entry) {
                    if (entry['identity'] == 'teacher')
                        html += "<font color=\"#00FF00\">" + entry['username'] + "</font>：" + "<font color=\"#fff\">" + entry['chat'] + "</font><br>";
                    else
                    {
                        html += "<a onclick=shitup('" + entry['userid'] + "') href=\"#\">" + entry['username'] + "</a>" + "：" + entry['chat'] + "<br>";
                    }
                });
                $("#chatroom").append(html);
                //$("#chatroom").scrollTop($("#chatroom").height);
            }
        });
    }

    function shitup(userid) {
        var txt = "确定要禁言吗";
        var option = {
            title: "禁言",
            btn: parseInt("0011", 2),
            onOk: function () {
                $.ajax({
                    type: "GET",
                    url: "index.php?r=teacher/shitup&&userid=" + userid,
                    success: function () {
                        var txt = "成功！";
                        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.success);
                    },
                    error: function () {
                        var txt = "失败！";
                        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.success);
                    }
                });
            }
        };
        window.wxc.xcConfirm(txt, "custom", option);
    }

    function pollBulletin() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/GetLatestBulletin&&classID=<?php echo $classID; ?>",
            success: function (data) {
                console.log(data[0]);
                if (role === 'student') {
                    $("#bulletin-textarea").val(data[0].content);
                } else {
                    if ($("#bulletin-textarea").val() === "") {
                        $("#bulletin-textarea").val(data[0].content);
                    }
                }
            },
            error: function (xhr, type, exception) {
                console.log('get Bulletin erroe', type);
                console.log(xhr.responseText, "Failed");
            }
        });
    }
</script>

<script>
    function js_method() {
        alert("ty");
    }
    //sunpy: switch camera and bulletin
    $(document).ready(function () {
        var flag = "";
        $("#sw-teacher-camera").click(function () {
            $("#teacher-camera").toggle(200);
        });
        $("#sw-chat").click(function () {
            $("#chat-box").toggle(200);
        });
        $("#sw-movie").click(function () {
            if(flag ==="movie"){
                flag = "";
                $("#title_movie").css({"color":"#fff"});
            }else{
                flag = "movie";
                $("#title_movie").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-movie").toggle(200);
        });
        $("#sw-picture").click(function () {
             if(flag ==="picture"){
                flag = "";
                $("#title_picture").css({"color":"#fff"});
            }else{
                flag = "picture";
                $("#title_picture").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-picture").toggle(200);
        });
        $("#sw-text").click(function () {
             if(flag ==="text"){
                flag = "";
                $("#title_text").css({"color":"#fff"});
            }else{
                flag = "text";
                $("#title_text").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-text").toggle(200);
        });
        $("#sw-ppt").click(function () {
            if(flag ==="ppt"){
                flag = "";
                $("#title_ppt").css({"color":"#fff"});
            }else{
                flag = "ppt";
                $("#title_ppt").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-ppt").toggle(200);
        });
        $("#sw-video").click(function () {
            if(flag ==="video"){
                flag = "";
                $("#title_video").css({"color":"#fff"});
            }else{
                flag = "video";
                $("#title_video").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-video").toggle(200);
        });
        $("#sw-classExercise").click(function () {
            if(flag ==="classExercise"){
                flag = "";
                $("#title_classExercise").css({"color":"#fff"});
            }else{
                flag = "classExercise";
                $("#title_classExercise").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-classExercise").toggle(200);
        });
//    $("#sw-bulletin").click(function() {
//        $("#bulletin").toggle(200);
//    });
        $("#sw-bull").click(function () {
            if(flag ==="showOnline"){
                flag = "";
                $("#title_bull").css({"color":"#fff"});
            }else{
                flag = "showOnline";
                $("#title_bull").css({"color":"#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#showOnline").toggle(200);
        });
        getBackTime();
    });
</script>

<script>
    $("#scroll-page").hide();
    $("#scroll-video").hide();
    $(document).ready(function () {
        //打开连接
        openConnect();

        $("#play-ppt").click(function () {
            closeAllTitle();
            if ($("#choose-ppt")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("teacher-dianbo").disabled = true;
            $("#teacher-dianbo").attr("class", "btn");
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").show();
            $("#scroll-page").show();
            cur_ppt = 1;
            var file_info = $("#choose-ppt option:selected").val().split("+-+");
            var source = file_info[2];
            var server_root_path;
            if (source === "tea")
            {
                server_root_path = "<?php echo SITE_URL . 'resources/' . $pptFilePath; ?>";
            } else {
                server_root_path = "<?php echo SITE_URL . 'resources/' . $adminPptFilePath; ?>";
            }
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
        });
        $("#play-ppt-public").click(function () {
            closeAllTitle();
            if ($("#choose-ppt-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("teacher-dianbo").disabled = true;
            $("#teacher-dianbo").attr("class", "btn");
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").show();
            $("#scroll-page").show();
            cur_ppt = 1;
            var file_info = $("#choose-ppt-public option:selected").val().split("+-+");
            var source = file_info[2];
            var server_root_path;
            if (source === "tea")
            {
                server_root_path = "<?php echo $adminPublicPdir; ?>";
            } else {
                server_root_path = "<?php echo $adminPublicPdir; ?>";
            }
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
        });
        $("#page-up").click(function () {
            pageUp();
        });
        $("#page-go").click(function () {
            var input_page = $("#yeshu").val();
            input_page = input_page - 1 + 1;
            if ((input_page >= 1) && (input_page <= ppt_pages))
            {
                cur_ppt = input_page;
                goCurPage();
            } else {
                window.wxc.xcConfirm("请输入合适范围的页数！", window.wxc.xcConfirm.typeEnum.info);
            }
        });
        $("#page-down").click(function () {
            pageDown();
        });
        $("#close-ppt").click(function () {
            cur_ppt = -1;
            ppt_pages = -1;
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            var msg = "<?php echo $classID; ?>closeppt";
            ws.send(msg);
            this.disabled = true;
            $("#close-ppt").attr("class", "btn");
            document.getElementById("play-ppt").disabled = false;
            document.getElementById("teacher-dianbo").disabled = false;
            $("#teacher-dianbo").attr("class", "btn btn-primary");
            $("#ppt-container").hide();
            $("#scroll-page").hide();
        });

        $("#share-Cam").click(function () {
        });

        $("#close-Cam").click(function () {
            var msg = "<?php echo $classID; ?>closeCam";
            ws.send(msg);
            if (timer_cam != null)
                clearInterval(timer_cam);
            this.disabled = true;
            $("#close-Cam").attr("class", "btn");
            document.getElementById("share-Cam").disabled = false;
            $("#share-Cam").attr("class", "btn btn-primary");
            iframe_b.location.href = './index.php?r=webrtc/null';
            iframe_b.location.href = './index.php?r=webrtc/teaCam&&classID=<?php echo $classID; ?>';
        });

        $("#teacher-dianbo").click(function () {
            closeAllTitle();
            if ($("#teacher-choose-file")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("play-ppt").disabled = true;
            $("#play-ppt").attr("class", "btn");
            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php echo SITE_URL . 'resources/' ?>";
            console.log(server_root_path);
            var filepath = $("#teacher-choose-file option:selected").val();
            var absl_path = server_root_path + filepath;
            var video_element;
            var video_time_duration;

            console.log("Choose file " + server_root_path + filepath);

            var video = document.getElementById('video1');
            if (video === null) {
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
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
        });
        
        $("#teacher-dianbo-public").click(function () {
            closeAllTitle();
            if ($("#teacher-choose-file-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("play-ppt").disabled = true;
            $("#play-ppt").attr("class", "btn");
            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php echo SITE_URL; ?>";
            var filepath = $("#teacher-choose-file-public option:selected").val();
            var absl_path = server_root_path + filepath;
            var video_element;
            var video_time_duration;

            console.log("Choose file " + server_root_path + filepath);

            var video = document.getElementById('video1');
            if (video === null) {
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
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
        });

        $("#close-dianbo").click(function () {
            $("#scroll-video").hide();
            document.getElementById("play-ppt").disabled = false;
            $("#play-ppt").attr("class", "btn btn-primary");
            clearVideo();
            this.disabled = true;
            $("#close-dianbo").attr("class", "btn");
        });
    });

    var play_fun = null;
    var pause_fun = null;
    var timer = null;
    var timer_ppt = null;
    var timer_cam = null;
    var ws = null;
    var cur_ppt = -1;
    var ppt_pages = -1;
    var ppt_dir = null;

    function goCurPage() {
        $("#yeshu").val(cur_ppt);
        $("#ppt-img").attr("src", ppt_dir + "/幻灯片" + cur_ppt + ".JPG");
        var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
        ws.send(msg);
    }
    function pageUp() {
        if (cur_ppt <= 1) {
            cur_ppt = 1;
            window.wxc.xcConfirm("已到第一页！", window.wxc.xcConfirm.typeEnum.info);
        } else {
            cur_ppt = cur_ppt - 1;
        }
        goCurPage();
    }

    function pageDown() {
        if (cur_ppt >= ppt_pages) {
            cur_ppt = ppt_pages;
            window.wxc.xcConfirm("已到最后页！", window.wxc.xcConfirm.typeEnum.info);
        } else {
            cur_ppt = cur_ppt + 1;
        }
        goCurPage();
    }


    function openConnect() {
        if (ws !== null)
            return;
        ws = new WebSocket("wss://<?php echo HOST_IP; ?>:8443", 'echo-protocol');// initializing the connection through the websocket api
        ws.onclose = function (event) {
            console.log("与点播服务器的连接断开...");
        };
    }

    function closeConnect() {
        if (ws === null)
            return;
        var message_sent = "<?php echo $classID; ?>close";
        ws.send(message_sent);
        ws.close();
        ws = null;
    }

    function clearVideo() {
        var myVideo = document.getElementById("video1");
        if (play_fun != null)
        {
            myVideo.removeEventListener("play", play_fun);
            myVideo.removeEventListener("pause", pause_fun);
        }
        //   teacher side broadcasts sync msg
        //   progress + path
        if (timer != null)
            clearInterval(timer);
        var message_sent = "<?php echo $classID; ?>closeVideo";
        ws.send(message_sent);
        $("#dianbo-videos-container").empty();
        $("#dianbo-videos-container").hide();
    }
    ;

    function checkforbid() {

        window.open("./index.php?r=teacher/checkforbid&&classID=<?php echo $classID; ?>", 'newwindow', 'height=450,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }

    function WebSocketConnect(absl_path) {
        console.log("sunpy [WebSocketConnect]");
        var myVideo = document.getElementById("video1");
        if (play_fun != null)
        {
            myVideo.removeEventListener("play", play_fun);
            myVideo.removeEventListener("pause", pause_fun);
        }
        //   teacher side broadcasts sync msg
        //   progress + path
        if (timer != null)
            clearInterval(timer);
        timer = setInterval(function () {
            var syn_msg;
            var video_current_time = myVideo.currentTime;

            if (myVideo.paused) {
                syn_msg = "<?php echo $classID; ?>pauseSync " + absl_path + "-----" + video_current_time;
            } else {
                syn_msg = "<?php echo $classID; ?>playSync " + absl_path + "-----" + video_current_time;
            }
            ws.send(syn_msg);
        }, 4000);

        play_fun = function () {
            console.log("sunpy: btn play");
            var message_sent = "<?php echo $classID; ?>Play";
            ws.send(message_sent);
            message_sent = "<?php echo $classID; ?>Path " + absl_path;
            ws.send(message_sent);
        };
        pause_fun = function () {
            console.log("sunpy: btn pause");
            var message_sent = "<?php echo $classID; ?>Pause";
            ws.send(message_sent);
        }
        myVideo.addEventListener("play", play_fun);
        myVideo.addEventListener("pause", pause_fun);

    }
    
    function closeAllTitle(){
            $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
    }
    function closeTitleWithoutFlag(flag){
        switch(flag){
        case "movie":
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "picture":
            $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "text":
          $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "ppt":
           $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "video":
           $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "classExercise":
           $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#showOnline").hide();
            $("#title_bull").css({"color":"#fff"});
            break;
        case "showOnline":
           $("#show-movie").hide();
            $("#title_movie").css({"color":"#fff"});
            $("#show-picture").hide();
            $("#title_picture").css({"color":"#fff"});
            $("#show-text").hide();
            $("#title_text").css({"color":"#fff"});
            $("#show-ppt").hide();
            $("#title_ppt").css({"color":"#fff"});
            $("#show-video").hide();
            $("#title_video").css({"color":"#fff"});
            $("#show-classExercise").hide();
            $("#title_classExercise").css({"color":"#fff"});
            break;
        }
    }
</script>