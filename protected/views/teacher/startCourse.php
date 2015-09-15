<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }
?>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">当前课程</li>
                <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
                <li class="divider"></li>
                <li class="nav-header">其余课程</li>
                <?php foreach($lessonsName as $key => $value):
                    if($key!=$progress){
                    ?>
                    <li id="li-<?php echo $key;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $key;?>"><i class="icon-list-alt"></i> <?php echo $value;?></a></li>
                    <?php
                    } 
                    endforeach;?>
                </ul>
        </div>
</div>
<div class="span9">
    <h1>
       <?php  echo $lessonsName[$progress];?>
    </h1>
    <div class="hero-unit">
        <p>
                点击虚拟课堂开始本课.
        </p>
        <p>
            <?php if($on == $progress){?>
            <a href="./index.php?r=teacher/virtualClass&&classID=<?php echo $classID;?>&&on=<?php echo $on;?>" class="btn btn-primary btn-large">虚拟课堂</a> 
            <?php }else {?>
            <a href="./index.php?r=teacher/changeProgress&&classID=<?php echo $classID;?>&&on=<?php echo $on;?>" class="btn btn-primary btn-large">开始本课</a> 
            <?php } ?>
            <a href="./index.php?r=teacher/assignWork&&classID=<?php echo $classID;?>&&lessonID=<?php $less    = Lesson::model()->find('classID=? and number=?', array($classID, $on));
                                                                                                        echo $less['lessonID'];?>" class="btn btn-large">课堂作业</a>
        </p>
    </div>
    <h1>
       本课资源
    </h1>
    <?php   $typename = Yii::app()->session['role_now'];
             $userid = Yii::app()->session['userid_now'];
             $videoFilePath =$typename."/".$userid."/".$classID."/".$on."/video/"; 
             $vdir = "resources/".$videoFilePath;            
             if(!is_dir($vdir))
             {//true表示可以创建多级目录
                mkdir($vdir,0777,true);
             }             
             $pptFilePath =$typename."/".$userid."/".$classID."/".$on."/ppt/"; 
             $pdir = "resources/".$pptFilePath;            
             if(!is_dir($pdir))
             {//true表示可以创建多级目录
                mkdir($pdir,0777,true);
             }
    ?>
    <div class="well summary">
        <ul>
                <li>
                        <a href="./index.php?r=teacher/videoLst&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><span class="count"><?php  $num = sizeof(scandir($vdir)); 
                                                                $num = ($num>2)?($num-2):0;
                                                                echo $num;?></span> 视频</a>
                </li>
                <li class="last">
                        <a href="./index.php?r=teacher/pptLst&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>">
                            <span class="count"><?php  $num = sizeof(scandir($pdir)); 
                                                        $num = ($num>2)?($num-2)/2:0;
                                                        echo $num;?></span> ppt</a>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>

