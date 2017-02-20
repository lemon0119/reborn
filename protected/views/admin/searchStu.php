<?php require 'stuSideBar.php';?>
<!-- 学生列表-->
<div class="span9">
<?php if($stuLst->count()!=0){?>
    <h2>查询结果</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">学号</th>
            <th class="font-center">姓名</th>
            <th class="font-center">班级</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($stuLst as $model):?>
                <tr>
                    <td class="font-center" style="width: 125px"><?php echo $model['userID'];?></td>
                    <td class="font-center"><?php echo $model['userName'];?></td>
                    <td class="font-center"><?php if($model['classID']=="0"){
                                    echo "无";
                    }
                                else{$classID = $model['classID'];
                                    $sqlClass = TbClass::model()->find("classID = $classID");
                                echo $sqlClass['className'];}
                        ?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>&&flag=search"><img title="资料" src="<?php echo IMG_URL; ?>detail.png"></a>
                        <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>&&flag=search"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                    </td>
                </tr>            
                <?php endforeach;?> 
            </tbody>
</table>
<!-- 学生列表结束 -->
<!-- 显示翻页标签 -->
<div align=center>
<?php   
    $this->widget('CLinkPager',array('pages'=>$pages));
?>
</div>
<!-- 翻页标签结束 -->
<!-- 右侧内容展示结束-->
<?php } else {?>
    <h2>查询结果为空！</h2>
<?php }?>
</div>
<script>
</script>

