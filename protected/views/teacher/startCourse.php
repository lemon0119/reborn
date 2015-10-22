<?php 
    $lessonsName=Array();
    $lessons=Lesson::model()->findall("classID='$classID'");
    if($lessons!=null)
        foreach ($lessons as $key => $value) {
            $lessonsName[$value['number']]=$value['lessonName'];
        }
?>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">当前课程</li>
                <?php  if($lessonsName!=null){?>
                <li id="li-<?php echo $progress;?>">
                    <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>">
                        <i class="icon-list-alt"></i> 
                        <?php echo $lessonsName[$progress]; ?>
                    </a>
                </li>
                <?php }?>
                <li class="divider"></li>
                <li class="nav-header">其余课程</li>
                <?php foreach($lessonsName as $key => $value):
                    if($key!=$progress){
                    ?>
                    <li id="li-<?php echo $key; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $key; ?>"><i class="icon-list-alt"></i> <?php echo $value; ?></a></li>
                    <?php
                }
            endforeach;
            ?>
        </ul>
    </div>
    
    <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"></li>
                <li class="nav-header">学生列表</li>
                <div class="scroll" style="padding: 8px 0;height:100px;overflow:auto;margin-left: 20px;">
                <?php foreach($stu as $student){
                    ?>
                    <li><?php echo $student['userName']?></li>
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

        $courseID = TbClass::model()->findCourseIDByClassID($classID);
        $adminPdir = "resources/admin/001/$courseID/$on/ppt/";
        $adminVdir = "resources/admin/001/$courseID/$on/video/";
        if (!is_dir($adminPdir)) {//true表示可以创建多级目录
            mkdir($adminPdir, 0777, true);
        }
        if (!is_dir($adminVdir)) {//true表示可以创建多级目录
            mkdir($adminVdir, 0777, true);
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
        echo $num1 + $num2;
        ?></span > <font style="color:#000">视频</font></a>
                </li>
                <li class="last">
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
                                if((!is_dir("$pdir/$file")) AND ($file!=".") AND ($file!="..")) 
                                {   
                                    $num = $num + 1;
                                } 
                            } 
                            $mydir->close(); 
                            echo    $num;
                            ?></span> <font style="color:#000">PPT</font></a>
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
            alert("此班级正在上课！")
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

