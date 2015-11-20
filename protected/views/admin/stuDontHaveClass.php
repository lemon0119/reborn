<?php require 'classLstSideBar.php';?>
<!-- 学生列表-->
<div class="span9">
    <h2>学生列表</h2>
      <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr >
            <th class="font-center">选择</th>
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
                    <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['userID']; ?>" /> </td>
                    <td class="font-center"  style=" width: 75px"><?php echo $model['userID'];?></td>
                    <td class="font-center"><?php echo $model['userName'];?></td>
                    <td class="font-center"><?php if($model['classID']=="0")
                                    echo "无";
                                else echo $model['classID'];
                        ?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                        <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
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
            function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    
    function dele(stuID){      
                var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href="./index.php?r=admin/deleteStuDontHaveClass&&id="+stuID+"&&page=<?php echo Yii::app()->session['lastPage'];?>";
						}
					};
					window.wxc.xcConfirm("这将会移动该学生至回收站，您确定这样吗？", "custom", option);
    } 
    $(document).ready(function(){
        $("#stuLst").attr("class","active");
    });
    
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
           window.wxc.xcConfirm('未选中任何题目', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							$('#deleForm').submit();
						}
					};
					window.wxc.xcConfirm("确定删除选中的科目吗？", "custom", option);
        }   
    }
</script>
