<?php require 'stuSideBar.php';?>
<!-- 老师列表-->
<div class="span9">
<?php if($teaLst->count()!=0){?>
    <h2>查询结果</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>学号</th>
            <th>用户名</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($teaLst as $model):?>
                <tr>
                    <td style="width: 75px"><?php echo $model['userID'];?></td>
                    <td><?php echo $model['userName'];?></td>
                    <td>  
                        <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>&&flag=search"><img src="<?php echo IMG_URL; ?>detail.png">资料</a>
                        <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>&&flag=search"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                        <a href="#" onclick="dele(<?php $userID=$model['userID'];
                                                         echo "'$userID'"; ?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                    </td>
                </tr>            
                <?php endforeach;?> 
            </tbody>
</table>
<!-- 老师列表结束 -->
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
    function dele(stuID){
        if(confirm("这将会移动该学生至回收站，您确定这样吗？")){
            window.location.href = "./index.php?r=admin/deleteStuSearch&&id="+stuID+"&&flag=search";
        }
    }  
</script>

