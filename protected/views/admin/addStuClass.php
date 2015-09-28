<?php require 'classLstSideBar.php';?>
<div class="span9">

<h3>向<?php echo $classID;?>班添加学生</h3>
<form id="myForm" method="post" action="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>&&action=addStu" onkeydown="if(event.keyCode==13){return false;}"> 
<input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" />全选/全不选</p> 
<table class="table table-bordered table-striped">
<thead>
    <tr>
        <th>选择</th>
        <th>学号</th>
        <th>用户名</th>
        <th>班级</th>
    </tr>
</thead>
        <tbody>        
            <?php foreach($posts as $model):?>
            <tr>
                <td style="width: 75px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>                     
                <td style="width: 75px"><?php echo $model['userID'];?></td>
                <td><?php echo $model['userName'];?></td>
                <td>无</td>
            </tr>            
            <?php endforeach;?> 
        </tbody>
</table>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">提交</button> <a href="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>" class="btn">返回</a>
    </div>
</form>   

<!-- js控制全选/取消全选  -->    
<script type="text/javascript"> 
function check_all(obj,cName) 
{    
var checkboxs = document.getElementsByName(cName); 
for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
</script> 

</div>

