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
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/addPpt&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" id="myForm" enctype="multipart/form-data"> 
    <div class="control-group">
       <label class="control-label" for="input02">上传</label>
       <div class="controls">
<!--       <input type="file" name="file" id="input02"> -->
       <div id="upload" style="display:inline;" hidden="true">
       <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
            正在上传，请稍等...
       </div>
       </div>
       <button type="submit" class="btn btn-primary">上传</button>
    </div>
    </form>
</div>
<script>
    $(document).ready(function(){
    $("#upload").hide();
    <?php 
        if(isset($result)){
            echo '$("#upload").hide();';
            echo "alert('$result');";
        }
    ?>
});
$("#myForm").submit(function(){
    $("#upload").show();
});
    
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>
