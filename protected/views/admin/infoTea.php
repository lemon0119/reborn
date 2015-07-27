<?php
    if(Yii::app()->session['lastUrl']=="classLst"||Yii::app()->session['lastUrl']=="infoClass"||Yii::app()->session['lastUrl']=="searchClass")
        require 'classLstSideBar.php';
    else
        require 'teaSideBar.php';?>
<div class="span9">
    <h3>老师信息</h3>
    <div class="hero-unit">
    <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td width="30%">工号:</td>
                    <td><?php echo $id;?></td>
                </tr>
                <tr>
                    <td>姓名:</td>
                    <td><?php echo $name;?></td>
                </tr>
            </tbody>
    </table>
        <?php if(Yii::app()->session['lastUrl']=="classLst"){?>
        <a href="./index.php?r=admin/classLst&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        <?php }else if(Yii::app()->session['lastUrl']=="searchClass"){?>
        <a href="./index.php?r=admin/searchClass&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        <?php }else if(Yii::app()->session['lastUrl']=="infoClass"){?>
        <a href="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>" class="btn">返回</a>
        <?php }else if(isset($flag)){?>
        <a href="./index.php?r=admin/searchTea&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        <?php }else{?>
        <a href="./index.php?r=admin/teaLst&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        <?php }?>
    </div>
</div>
