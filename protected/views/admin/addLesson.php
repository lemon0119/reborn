<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>"><i class="icon-align-left"></i> 课业列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h3>新建一课</h3>
    <form id="myForm" method="post" action="./index.php?r=admin/addLesson&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>" onkeydown="if(event.keyCode==13){return false;}"> 
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">课名</label>
                <div class="controls">
                        <input name="lessonName" type="text" class="input-xlarge" id="input01"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> 
                <a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>" class="btn">返回</a>
            </div>
        </fieldset>
    </form>   
</div>

<script>
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert('添加课程成功！');
    else if(result === '0')
        alert('添加课程失败！');  
});    
$("#myForm").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        alert('课程名不能为空');
        return false;
    }
});
</script>

