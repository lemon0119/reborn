<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=admin/searchCourse" method="post">
                <li>
                    <select name="type" >
                        <option value="courseID" selected="selected">编号</option>
                        <option value="courseName">科目名</option>
                        <option value="createPerson">创建人</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addCourse" class="btn_add"></a>
                </li>
            </form>
            <li class="divider"></li>
            <li class="active" ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left"></i> 科目列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">

<?php
//得到老师ID对应的名称
foreach ($teacher as $model):
$teacherID=$model['userID'];
$teachers["$teacherID"]=$model['userName'];
endforeach;
?>
<h2>科目列表</h2>
<!-- 科目列表-->
   <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">选择</th>
            <th class="font-center">编号</th>
            <th class="font-center">科目名</th>
            <th class="font-center">创建人</th>
            <th class="font-center">课时数</th>
            <th class="font-center">创建时间</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <form id="deleForm" method="post" action="./index.php?r=admin/deleteCourse">
                <?php   foreach($courseLst as $k=>$model):?>
                <tr>
                     <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['courseID']; ?>" /> </td>
                    <td class="font-center" style="width: 75px"><?php echo $model['courseID'];?></td>
                    <td class="font-center"><?php echo $model['courseName'];?></td>
                    <td class="font-center"><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo $teachers[$model['createPerson']];
                        ?></td>
                    <td class="font-center"><?php echo $courseNumber[$k];?></td>
                    <td class="font-center"><?php echo $model['createTime'];?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $model['courseID'];?>&&courseName=<?php echo $model['courseName'];?>&&createPerson=<?php if($model['createPerson']=="0")                                                                                                                                                                  ?>"><img title="编辑课程" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#"  onclick="deleteCourse(<?php echo $model['courseID'];?>,'<?php echo $model['courseName'];?>')" ><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm("删除成功！", window.wxc.xcConfirm.typeEnum.success,{
       onOk:function(){
		 window.location.href = "./index.php?r=admin/courseLst";
						}
    });
    else if(result==='0'){
    window.wxc.xcConfirm("已有班级进行需要删除的科目，无法删除！", window.wxc.xcConfirm.typeEnum.error,{
       onOk:function(){
		 window.location.href = "./index.php?r=admin/courseLst";
						}
    });
    }
});

    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    
    function deleteCourse(id,name){
        var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href="./index.php?r=admin/deleteCourse&&courseID="+id+"&&page=<?php echo Yii::app()->session ['lastPage'];?>";
						}
					};
					window.wxc.xcConfirm("确定要删除科目："+name+"?这样做将无法恢复！", "custom", option);
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
    $(document).ready(function () {
        $("#li-stuLst").attr("class", "active");
    });
</script>
