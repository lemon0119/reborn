<?php require 'classLstSideBar.php';?>
<!-- 学生列表-->
<div class="span9">
    <h2>学生列表</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr >
            <th class="font-center">学号</th>
            <th class="font-center">用户名</th>
            <th class="font-center">班级</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody >     
                <form id="deleForm" method="post" action="./index.php?r=admin/deleteStuDontHaveClass">
                <?php foreach($stuLst as $model):?>
                <tr >
                    <td class="font-center"  style=" width: 75px"><?php echo $model['userID'];?></td>
                    <td class="font-center"><?php echo $model['userName'];?></td>
                    <td class="font-center"><?php if($model['classID']=="0")
                                    echo "无";
                                else echo $model['classID'];
                        ?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                        <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                    </td>
                </tr>            
                <?php endforeach;?> 
                </form>
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
</div>
<script>

    $(document).ready(function(){
        $("#stuLst").attr("class","active");
    });

</script>
