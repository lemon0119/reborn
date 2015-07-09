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
                <li id="li-<?php echo $progress;?>"><a href="./index.php?r=admin/stuLst"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
                <li class="divider"></li>
                <li class="nav-header">其余课程</li>
                <?php foreach($lessonsName as $key => $value):
                    if($key!=$progress){
                    ?>
                    <li id="li-<?php echo $key;?>"><a href="./index.php?r=admin/stuLst"><i class="icon-list-alt"></i> <?php echo $value;?></a></li>
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
                <a href="./index.php?r=webrtc/index" class="btn btn-primary btn-large">虚拟课堂</a> <a class="btn btn-large">课堂作业</a>
        </p>
    </div>
    <h1>
       本课资源
    </h1>
    <div class="well summary">
        <ul>
                <li>
                        <a href="#"><span class="count">3</span> 视频</a>
                </li>
                <li>
                        <a href="#"><span class="count">27</span> ppt</a>
                </li>
                <li class="last">
                        <a href="#"><span class="count">5</span> 习题</a>
                </li>
        </ul>
    </div>
<div>
<script>
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>

