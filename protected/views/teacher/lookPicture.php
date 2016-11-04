<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }   
?>
<?php if($new!=1){?>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">当前科目</li>
        <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
        <li class="divider"></li>
        <li class="nav-header">其余科目</li>
         </ul>
        <div class="well-bottomnoradius" style="padding: 8px 0;height:496px;overflow:auto;top: 0px;border-top-left-radius:0px; ">
        <ul class="nav nav-list">
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
    <a href="./index.php?r=teacher/pictureLst&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary">返回</a>
</div>
<?php }?>
<div class="span9" style=" height: 574px">
    <div id="dianbo-videos-container" style="width: 100%;height: 100%">
        <img src = "<?php echo  $file;?>" style="width: 100% !important; height:100% !important; " />    
    </div>
</div>
<script>
    
$(document).ready(function(){
    $("#li-<?php echo $on;?>").attr("class","active");      
}); 
</script>

