<?php
    if(Yii::app()->session['lastUrl']=="classLst")
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
        <a href="./index.php?r=admin/classLst" class="btn">返回</a>
        <?php }else if(isset($flag)){?>
        <a href="./index.php?r=admin/searchTea" class="btn">返回</a>
        <?php }else{?>
        <a href="./index.php?r=admin/teaLst" class="btn">返回</a>
        <?php }?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#classLst").attr("class","active");
    });
</script>
