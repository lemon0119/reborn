<?php require 'teaSideBar.php';?>

<div class="span9">
    <h2>被删除老师列表</h2>
<form id="myForm" method="post" action="" onkeydown="if(event.keyCode==13){return false;}"> 
    <input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a onclick="ReCheck()"  href="#" id="submit"><img title="撤销" src="<?php echo IMG_URL; ?>reborn.png"></a>
<a onclick="deleCheck()" href="#"  id="submit"><img title="彻底删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">选择</th>
            <th class="font-center">工号</th>
            <th class="font-center">姓名</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($teaLst as $model):?>
                <tr>
                    <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>
                    <td class="font-center" style="width: 75px"><?php echo $model['userID'];?></td>
                    <td class="font-center"><?php echo $model['userName'];?></td>
                    <td class="font-center" style="width: 100px">
                        <a href="./index.php?r=admin/revokeTea&&userID=<?php echo $model['userID'];?>" id="submit"><img title="撤销" src="<?php echo IMG_URL; ?>reborn.png"></a>
                      
                        <a href="./index.php?r=admin/confirmTeaPass&&userID=<?php echo $model['userID'];?>" id="submit"><img title="彻底删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                    
                    </td>
                </tr>
                <?php endforeach;?> 
            </tbody>
</table>
    <div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
</form>
<!-- 学生列表结束 -->
<!-- 右侧内容展示结束-->
</div>

<script>
    $(document).ready(function(){
        //侧边菜单选中变色
    $("#li-recycleStu").attr("class","active");
       <?php if(isset($_POST['checkbox'])){ ?>
           window.location.href="./index.php?r=admin/recycleTea";
      <?php }?> 
    });
function check_all(obj,cName)
{    
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
}

function deleCheck() {
    var checkboxs = document.getElementsByName('checkbox[]');
    var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何学生', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
                                                    $('#myForm').attr('action',"./index.php?r=admin/confirmTeaPass");
                                                    $('#myForm').submit();
                                                    return false;
						}
					};
					window.wxc.xcConfirm("这将会彻底删除该学生信息，您确定这样吗？", "custom", option);
        }
       
    }
    
function ReCheck() {
    var checkboxs = document.getElementsByName('checkbox[]');
    var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何学生', window.wxc.xcConfirm.typeEnum.info);
        }else{
                                                    $('#myForm').attr('action',"./index.php?r=admin/revokeTea");
                                                    $('#myForm').submit();
                                                    return false;
        }
       
    }

//$(document).ready(function(){
//    //提交表单
//    $("a#submit").click(function(){
//        var url = $(this).attr("href");
//        $('#myForm').attr('action',url);
//        $('#myForm').submit();
//        return false;
//    });
//});
</script>