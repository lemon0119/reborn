<?php require 'stuSideBar.php';?>

<div class="span9">
    <h2>被删除学生列表</h2>
<form id="myForm" method="post" action="" onkeydown="if(event.keyCode==13){return false;}"> 
    <input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
<a href="./index.php?r=admin/revokeStu" id="submit"><img title="撤销" src="<?php echo IMG_URL; ?>reborn.png"></a>
<a href="./index.php?r=admin/confirmPass" id="submit"><img title="彻底删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">选择</th>
            <th class="font-center">学号</th>
            <th class="font-center">用户名</th>
            <th class="font-center">原班级</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($stuLst as $model):?>
                <tr>
                    <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>
                    <td class="font-center" style="width: 75px"><?php echo $model['userID'];?></td>
                    <td class="font-center"><?php echo $model['userName'];?></td>
                    <td class="font-center"><?php if($model['classID']=="0") echo "无"; else echo $model['classID']; ?></td>
                    <td class="font-center" style="width: 100px">
                        <a href="./index.php?r=admin/revokeStu&&userID=<?php echo $model['userID'];?>" id="submit"><img title="撤销" src="<?php echo IMG_URL; ?>reborn.png"></a>
                        <a href="./index.php?r=admin/confirmPass&&userID=<?php echo $model['userID'];?>" id="submit"><img title="彻底删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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