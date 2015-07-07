<?php require 'stuSideBar.php';?>

<div class="span9">
    <h2>被删除学生列表</h2>
<form id="myForm" method="post" action="" onkeydown="if(event.keyCode==13){return false;}"> 
<input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" />全选　　批量：</input>
<a href="./index.php?r=admin/"><img src="<?php echo IMG_URL; ?>reborn.png">恢复</a>
<a href="#" ><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>选择</th>
            <th>学号</th>
            <th>用户名</th>
            <th>原班级</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($stuLst as $model):?>
                <tr>
                     <td style="width: 75px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>
                    <td style="width: 75px"><?php echo $model['userID'];?></td>
                    <td><?php echo $model['userName'];?></td>
                    <td><?php if($model['classID']=="0")
                                    echo "无";
                                else echo $model['classID'];
                        ?></td>
                    <td> 
                        <a href="./index.php?r=admin/"><img src="<?php echo IMG_URL; ?>reborn.png">恢复</a>
                        <a href="#" ><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                    </td>
                </tr>            
                <?php endforeach;?> 
            </tbody>
</table>
<!-- 学生列表结束 -->
<!-- 右侧内容展示结束-->
</div>

<script>
function check_all(obj,cName) 
{    
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
$(document).ready(function(){
    $("#lli-recycleStu").attr("class","active");
});
</script>