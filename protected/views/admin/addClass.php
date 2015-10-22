<?php require 'classLstSideBar.php';?>
<div class="span9">
<?php $courses=Course::model()->findall(); ?>
    <h2>新建班级</h2>
    <form id="myForm" method="post" class="form-horizontal" action="./index.php?r=admin/addClass&&action=add" onkeydown="if(event.keyCode==13){return false;}">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                    <label class="control-label" for="input01">班级名</label>
                    <div class="controls">
                            <input name="className" type="text" class="input-xlarge" id="input01"/>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label" for="input02">选择科目</label>
                    <div class="controls">
                        <select name="courseID"  id="input02">
                            <option value="" selected="selected">请选择科目</option>
                            <?php 
                                foreach ($courses as $key => $value) {?>
                            <option value="<?php echo $value['courseID']; ?>"><?php echo $value['courseName']; ?></option>     
                            <?php  }?>
                        </select>
                    </div>
            </div>
            <div class="form-actions">
            <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/classLst" class="btn">返回</a>
            </div>
        </fieldset>
    </form>   
</div>

<script>
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加班级成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加班级失败！', window.wxc.xcConfirm.typeEnum.error);
    else if(result === '2')
    window.wxc.xcConfirm('存在同名班级!', window.wxc.xcConfirm.typeEnum.error);    
});    
$("#myForm").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        window.wxc.xcConfirm('班级名称不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
   
    var pass2 = $("#input02")[0].value;
    if(pass2 === ""){
        window.wxc.xcConfirm('请选择合适的科目', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
});
</script>
