<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo Yii::app()->session['courseID'];?>&&courseName=<?php echo Yii::app()->session['courseName'];?>&&createPerson=<?php echo Yii::app()->session['createPerson'];?>"><i class="icon-align-left"></i> 课时列表</a></li>
        </ul>
    </div>
    <a href="./index.php?r=admin/videoLst&&vdir=<?php echo $vdir;?>&&courseID=<?php echo Yii::app()->session['courseID'];?>&&courseName=<?php echo Yii::app()->session['courseName'];?>&&createPerson=<?php echo Yii::app()->session['createPerson'];?>" class="btn btn-primary">返回</a>
</div>
<div class="span9">
    <div id="dianbo-videos-container">
        <video id="video1" width="100%" controls>
            <source src="<?php echo  $file;?>">
        </video>
    </div>
</div>

