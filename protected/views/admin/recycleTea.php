<?php require 'teaSideBar.php';?>

<div class="span9">
    <h2>被删除老师列表</h2>
<form id="myForm" method="post" action="" onkeydown="if(event.keyCode==13){return false;}"> 
    <input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
<a href="./index.php?r=admin/revokeTea" id="submit"><img src="<?php echo IMG_URL; ?>reborn.png"></a>
    <a href="./index.php?r=admin/confirmTeaPass" id="submit"><img src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>选择</th>
            <th>学号</th>
            <th>用户名</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($teaLst as $model):?>
                <tr>
                    <td style="width: 75px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>
                    <td style="width: 75px"><?php echo $model['userID'];?></td>
                    <td><?php echo $model['userName'];?></td>
                    <td>
                        <a href="./index.php?r=admin/revokeTea&&userID=<?php echo $model['userID'];?>" id="submit"><img src="<?php echo IMG_URL; ?>reborn.png"></a>
                      
                        <a href="./index.php?r=admin/confirmTeaPass&&userID=<?php echo $model['userID'];?>" id="submit"><img src="<?php echo IMG_URL; ?>delete.png"></a>
                    
                    </td>
                </tr>
                <?php endforeach;?> 
            </tbody>
</table>
</form>
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
    //侧边菜单选中变色
    $("#li-recycleStu").attr("class","active");
    //提交表单
    $("a#submit").click(function(){
        var url = $(this).attr("href");
        $('#myForm').attr('action',url);
        $('#myForm').submit();
        return false;
    });
});
</script>