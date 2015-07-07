<?php require 'stuSideBar.php';?>
<!-- 学生列表-->
<div class="span9">
<?php if($stuLst->count()!=0){?>
    <h2>查询结果</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>学号</th>
            <th>用户名</th>
            <th>班级</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($stuLst as $model):?>
                <tr>
                    <td style="width: 75px"><?php echo $model['userID'];?></td>
                    <td><?php echo $model['userName'];?></td>
                    <td><?php if($model['classID']=="0")
                                    echo "无";
                                else echo $model['classID'];
                        ?></td>
                    <td>  
                        <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>detail.png">资料</a>
                        <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                        <a href="./index.php?r=admin/stuLst&&flag=delete&&id=<?php echo $model['userID'];?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
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

