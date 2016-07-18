<?php require 'teaSideBar.php';?>
<!-- 老师列表-->
<div class="span9">
    <h2>老师列表</h2>
    <input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
<a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">选择</th>
            <th class="font-center">工号</th>
            <th class="font-center">姓名</th>
            <th class="font-center">院系</th>
            <th class="font-center">院校</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody> 
                <form id="deleForm" method="post" action="./index.php?r=admin/deleteTea"> 
                <?php foreach($teaLst as $model):?>
                <tr>
                    <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['userID'];?>" /> </td>
                    <td class="font-center" style="width: 75px"><?php echo $model['userID'];?></td>
                    <td class="font-center" style="width: 75px"><?php echo $model['userName'];?></td>
                    <td class="font-center" style="width: 150px"><?php echo $model['department'];?></td>
                    <td class="font-center" style="width: 150px"><?php echo $model['school'];?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoTea&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                        <a href="./index.php?r=admin/editTea&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php $userID=$model['userID'];
                                                    echo "'$userID'"; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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
       <?php if(isset($_POST['checkbox'])){ ?>
           window.location.href="./index.php?r=admin/teaLst";
      <?php }?> 
    });
    function check_all(obj,cName)
{    
    var checkboxs =document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
    }  
    function dele(stuID){
        var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							window.location.href = "./index.php?r=admin/deleteTea&&id="+stuID+"&&page=<?php echo Yii::app()->session['lastPage'];?>";
						}
					};
					window.wxc.xcConfirm("这将会移动该老师至回收站，您确定这样吗？", "custom", option);
    } 
     
    function deleCheck(){
        var checkboxs = document.getElementsByName('checkbox[]');
        var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何老师', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							$('#deleForm').submit();
						}
					};
					window.wxc.xcConfirm("这将会移动选中老师至回收站，您确定这样吗？", "custom", option);
        }
       
    }
    $(document).ready(function(){
        $("#li-stuLst").attr("class","active");
    });
</script>
