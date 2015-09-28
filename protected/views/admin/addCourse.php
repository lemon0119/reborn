<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=admin/searchCourse" method="post">
                <li>
                    <select name="type" >
                        <option value="courseID" selected="selected">编号</option>
                        <option value="courseName">课程名</option>
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
            <li ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left"></i> 课程列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h3>新建课程</h3>
    <form id="myForm" method="post" action="./index.php?r=admin/addCourse" onkeydown="if(event.keyCode==13){return false;}"> 
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">课程名</label>
                <div class="controls">
                        <input name="courseName" type="text" class="input-xlarge" id="input01"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> 
                <a href="./index.php?r=admin/<?php echo Yii::app()->session['lastUrl'];?>&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
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

