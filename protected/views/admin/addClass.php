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
                    <label class="control-label" for="input02">选择课程</label>
                    <div class="controls">
                        <select name="courseID" style="width: 185px" id="input02">
                            <option value="" selected="selected">请选择课程</option>
                            <?php 
                                foreach ($courses as $key => $value) {?>
                            <option value="<?php echo $value['courseID']; ?>"><?php echo $value['courseName']; ?></option>     
                            <?php  }?>
                        </select>
                    </div>
            </div>
            <div class="form-actions">
            <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/classLst" class="btn">取消</a>
            </div>
        </fieldset>
    </form>   
</div>

<script>
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert('添加班级成功！');
    else if(result === '0')
        alert('添加班级失败！');  
});    
$("#myForm").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        alert('班级名称不能为空');
        return false;
    }
   
    var pass2 = $("#input02")[0].value;
    if(pass2 === ""){
        alert('请选择合适的课程');
        return false;
    }
});
</script>
