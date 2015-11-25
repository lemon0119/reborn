<?php 
    $lessonsName=Array();
    $lessons=Lesson::model()->findall("classID='$classID'");
    if($lessons!=null){
        foreach ($lessons as $key => $value) {
            $lessonsName[$value['number']]=$value['lessonName'];
            $courseID=$value['courseID'];
        }
     }
     $courseName=  Course::model()->find('courseID=?',array($courseID))['courseName'];
?>

<div class="span3">
    <div class="well-bottomnoradius" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-knowlage"></i>当前科目</li>
                <li style="margin-left:20px;"><?php echo $courseName;?></li>
                <li class="nav-header"><i class="icon-knowlage"></i>当前课时</li>
                <?php  if($lessonsName!=null){?>
                <li id="li-<?php echo $progress;?>">
                    <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>">
                        <i class="icon-list-alt"></i> 
                        <?php echo $lessonsName[$progress]; ?>
                    </a>
                </li>
                <?php }?>
                <li class="divider"></li>
                <li class="nav-header"><i class="icon-knowlage"></i>其余课时</li>
                </ul>
    </div>
    <div class="well-bottomnoradius" style="padding: 8px 0;height:230px;overflow:auto;top: -20px;border-top-left-radius:0px; ">
                <ul class="nav nav-list">
                
                <?php foreach($lessonsName as $key => $value):
                    if($key!=$progress){
                    ?>
                    <li  id="li-<?php echo $key; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $key; ?>"><i class="icon-list-alt"></i> <?php echo $value; ?></a></li>
                    <?php
                }
            endforeach;
            ?>
                    
        </ul>
    </div>
    
    <div class="well-topnoradius" style="padding: 8px 0;top: -40px;">
        
                <ul class="nav nav-list">
                <li class="nav-header"></li>
                <li class="nav-header"><i class="icon-knowlage"></i>学生列表</li>
                <div class="scroll" style="padding: 8px 0;height:100px;overflow:auto;margin-left: 20px;">
                <?php foreach($stu as $student){
                    ?>
                    <li><i class="icon-headphones"></i><?php echo $student['userName']?></li>
                <?php
                }              
            ?>
                </div>
        </ul>
    </div>
</div>
<div class="span9">
    <?php 
        if($lessonsName!=null) {  ?>
    
    <h1>
       <?php  
         if($lessonsName!=null)
            echo $lessonsName[$progress];?>
    </h1>
    <div class="hero-unit table-bordered" style="height:200px; width: 770px">
        <p class="font-startcourse">
            点击虚拟课堂开始本课.
        </p>
        <div style="height:200px; width: 780px">
            <?php if ($on == $progress) { ?>
            <a href="#" onclick="getBackTime()"class="startcourse-virtualclass">虚拟课堂</a> 
            <?php } else { ?>
                <a href="./index.php?r=teacher/changeProgress&&classID=<?php echo $classID; ?>&&on=<?php echo $on; ?>" class="startcourse-virtualclass">开始本课</a> 
               <?php } ?>
            <a href="./index.php?r=teacher/assignWork&&classID=<?php echo $classID; ?>&&lessonID=<?php $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
               echo $less['lessonID'];
               ?>" class="startcourse-startclass">课堂作业</a>
        </div>
        </div>
        <h1>
            本课资源
        </h1>
        <?php
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        $vdir = "resources/" . $videoFilePath;
        if (!is_dir($vdir)) {//true表示可以创建多级目录
            mkdir($vdir, 0777, true);
        }
        $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        $pdir = "resources/" . $pptFilePath;
        if (!is_dir($pdir)) {//true表示可以创建多级目录
            mkdir($pdir, 0777, true);
        }       
        $txtFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
        $txtdir = "resources/" . $txtFilePath;
        if (!is_dir($txtdir)) {//true表示可以创建多级目录
            mkdir($txtdir, 0777, true);
        }
        
        $picFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
        $picdir = "resources/" . $picFilePath;
        if (!is_dir($picdir)) {//true表示可以创建多级目录
            mkdir($picdir, 0777, true);
        }
        
        $voFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
        $vodir = "resources/" . $voFilePath;
        if (!is_dir($vodir)) {//true表示可以创建多级目录
            mkdir($vodir, 0777, true);
        }

        $courseID = TbClass::model()->findCourseIDByClassID($classID);
        $adminPdir = "resources/admin/001/$courseID/$on/ppt/";
        $adminVdir = "resources/admin/001/$courseID/$on/video/";
        $adminPICdir = "resources/admin/001/$courseID/$on/picture/";
        $adminTXTdir = "resources/admin/001/$courseID/$on/txt/";
        $adminVOdir = "resources/admin/001/$courseID/$on/voice/";
        if (!is_dir($adminPdir)) {//true表示可以创建多级目录
            mkdir($adminPdir, 0777, true);
        }
        if (!is_dir($adminVdir)) {//true表示可以创建多级目录
            mkdir($adminVdir, 0777, true);
        }
        if (!is_dir($adminPICdir)) {//true表示可以创建多级目录
            mkdir($adminPICdir, 0777, true);
        }
        if (!is_dir($adminTXTdir)) {//true表示可以创建多级目录
            mkdir($adminTXTdir, 0777, true);
        }
        if (!is_dir($adminVOdir)) {//true表示可以创建多级目录
            mkdir($adminVOdir, 0777, true);
        }
        
        $publicPdir = "resources/public/ppt/";
        $publicVdir = "resources/public/video/";
        $publicPicdir = "resources/public/picture/";
        $publicTxtdir = "resources/public/txt/";
        $publicVodir = "resources/public/voice/";
                if (!is_dir($publicPdir)) {//true表示可以创建多级目录
            mkdir($publicPdir, 0777, true);
        }
                if (!is_dir($publicVdir)) {//true表示可以创建多级目录
            mkdir($publicVdir, 0777, true);
        }
                if (!is_dir($publicPicdir)) {//true表示可以创建多级目录
            mkdir($publicPicdir, 0777, true);
        }        if (!is_dir($publicTxtdir)) {//true表示可以创建多级目录
            mkdir($publicTxtdir, 0777, true);
        }
                if (!is_dir($publicVodir)) {//true表示可以创建多级目录
            mkdir($publicVodir, 0777, true);
        }
        
        
        ?>
        <div class="table-bordered summary">
            <ul>
                <li>
                    <a  id="ppt" href="./index.php?r=teacher/videoLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>"><span class="count"><?php
        $num1 = sizeof(scandir($vdir));
        $num1 = ($num1 > 2) ? ($num1 - 2) : 0;
        $num2 = sizeof(scandir($adminVdir));
        $num2 = ($num2 > 2) ? ($num2 - 2) : 0;
        $num2 = sizeof(scandir($adminVdir));
        $num2 = ($num2 > 2) ? ($num2 - 2) : 0;
        $num3 = sizeof(scandir($publicVdir));
        $num3 = ($num3 > 2) ? ($num3 - 2) : 0;
        echo $num1 + $num2 + $num3;
        ?></span > <font style="color:#000">视频</font></a>
                </li>
                <li >
                    <a href="./index.php?r=teacher/pptLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>" id="ppt">
                        <span class="count"><?php
                            $num    = 0;                                                    
                            $mydir = dir($pdir); 
                            while($file = $mydir->read())
                            { 
                                if((!is_dir("$pdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $num = $num + 1;
                                } 
                            } 
                            $mydir->close();
                            $mydir = dir($adminPdir); 
                            while($file = $mydir->read())
                            { 
                                if((!is_dir("$adminPdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $num = $num + 1;
                                } 
                            } 
                            $mydir->close(); 
                            $mydir = dir($publicPdir); 
                            while($file = $mydir->read())
                            { 
                                if((!is_dir("$publicPdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $num = $num + 1;
                                } 
                            } 
                            $mydir->close(); 
                            echo    $num;
                            ?></span> <font style="color:#000">PPT</font></a></li>
                <li><a  id="ppt" href="./index.php?r=teacher/txtLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>"><span class="count"><?php
                                   $txtnum    = 0;                                                    
                            $mytxtdir = dir($txtdir); 
                            while($file = $mytxtdir->read())
                            { 
                                if((!is_dir("$txtdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $txtnum = $txtnum + 1;
                                } 
                            } 
                            $mytxtdir->close();
                            $mytxtdir = dir($adminTXTdir); 
                            while($file = $mytxtdir->read())
                            { 
                                if((!is_dir("$adminTXTdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $txtnum = $txtnum + 1;
                                } 
                            } 
                            $mytxtdir->close(); 
                            $mytxtdir = dir($publicTxtdir); 
                            while($file = $mytxtdir->read())
                            { 
                                if((!is_dir("$publicTxtdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $txtnum = $txtnum + 1;
                                } 
                            } 
                            $mytxtdir->close(); 
                            echo    $txtnum;
        ?></span ><font style="color:#000">文本</font></a></li>
                
                
                
                
                <li>  <a  id="ppt" href="./index.php?r=teacher/voiceLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>"><span class="count"><?php
                         $vonum    = 0;                                                    
                            $myvodir = dir($vodir); 
                            while($file = $myvodir->read())
                            { 
                                if((!is_dir("$vodir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $vonum = $vonum + 1;
                                } 
                            } 
                            $myvodir->close();
                            $myvodir = dir($adminVOdir); 
                            while($file = $myvodir->read())
                            { 
                                if((!is_dir("$adminVOdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $vonum = $vonum + 1;
                                } 
                            } 
                            $myvodir->close(); 
                            $myvodir = dir($publicVodir); 
                            while($file = $myvodir->read())
                            { 
                                if((!is_dir("$publicVodir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $vonum = $vonum + 1;
                                } 
                            } 
                            $myvodir->close(); 
                            echo    $vonum;
        ?></span > <font style="color:#000">音频</font></a></li>              
                <li> <a class="last"   id="ppt" href="./index.php?r=teacher/pictureLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>"><span class="count"><?php
                                 $picnum    = 0;                                                    
                            $mypicdir = dir($picdir); 
                            while($file = $mypicdir->read())
                            { 
                                if((!is_dir("$picdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $picnum = $picnum + 1;
                                } 
                            } 
                            $mypicdir->close();
                            $mypicdir = dir($adminPICdir); 
                            while($file = $mypicdir->read())
                            { 
                                if((!is_dir("$adminPICdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $picnum = $picnum + 1;
                                } 
                            } 
                            $mypicdir->close(); 
                            $mypicdir = dir($publicPicdir); 
                            while($file = $mypicdir->read())
                            { 
                                if((!is_dir("$publicPicdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $picnum = $picnum + 1;
                                } 
                            } 
                            $mypicdir->close(); 
                            echo    $picnum;
        ?></span><font style="color:#000">图片</font></a></li>       
                
                
            </ul>
        </div>
    <h1>自由练习</h1>
    <div class="table-bordered summary">
     <ul>
                <li>
                    <a  id="ppt" href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>"><span class="count">2
                        </span > <font style="color:#000">键打练习</font></a>
                </li>
                <li>
                    <a  id="ppt" href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>"><span class="count">2
                        </span > <font style="color:#000">看打练习</font></a>
                </li>
                <li>
                    <a  id="ppt" href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>"><span class="count">2
                        </span > <font style="color:#000">听打练习</font></a>
                </li>
     </ul>
        </div>
    </div>
    <?php }?>
</div>
<script>
    $(document).ready(function(){
        //每5秒，刷新一次
        //setTimeout(function() {
        //    getBackTime();
        //}, 1000);
        $("#li-<?php echo $on;?>").attr("class","active");
    });
    function beginVirClass(){     //点击开始虚拟课堂时触发
        getBackTime();
        
        if($("#txt").val()=='1'){
            alert("此班级正在上课！");
        }else{
            window.location.href="./index.php?r=teacher/virtualClass&&classID=<?php echo $classID; ?>&&on=<?php echo $on; ?>";
        }
    }
    function getBackTime() {
    $.ajax({
        type: "GET",
        dataType: "json",
        //url: "index.php?r=api/GetBackTime&&classID=<?php echo $classID;?>",
        url: "index.php?r=api/GetClassState&&classID=<?php echo $classID;?>",
        success: function(data) {
            console.log("qq",data);
            var now=<?php echo time()?>;    //这个时间是页面进入的时候，生成的。
            //虽然点击的时候，才会执行这个js代码，但是，php是加载的时候就已经生成了
            //也就是说，等到用户点击，这个时间now的值，是加载页面的时间。
            if(!data){
                window.location.href="./index.php?r=teacher/virtualClass&&classID=<?php echo $classID; ?>&&on=<?php echo $on; ?>";//$("#txt").val('0');
            } else{
                alert("此班级正在上课！");//$("#txt").val('1');
            }
        },
        error: function(xhr, type, exception){
            console.log('get backtime erroe', type);
            console.log(xhr, "Failed");
        }
    });
}
</script>

