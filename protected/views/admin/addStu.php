<?php require 'stuSideBar.php'; ?>
<div class="span9">
    <h2>添 加 学 生</h2>
    <form action="./index.php?r=admin/addStu" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">学号</label>
                <div class="controls">
                    <input name="userID" type="text" class="input-xlarge" id="input01" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input02">姓名</label>
                <div class="controls">
                    <input name="userName" type="text" class="input-xlarge" id="input02" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input06">性别</label>
                <div class="controls">
                    男
                    <input name="sex" type="radio" class="input-xlarge" id="input06" value="男" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    女
                    <input name="sex" type="radio"  class="input-xlarge" id="input06" value="女" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input07">年龄</label>
                <div class="controls">
                    <input name="age" type="text" class="input-xlarge" id="input07" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">班级</label>
                <div class="controls">
                    <input name="className" type="text" class="input-xlarge" id="input03" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input09">联系电话</label>
                <div class="controls">
                    <input name="phone_number" type="text" class="input-xlarge" id="input09" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input10">联系邮箱</label>
                <div class="controls">
                    <input name="mail_address" type="text" class="input-xlarge" id="input10" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input04">密码</label>
                <div class="controls">
                    <input name="password1" type="password" class="input-xlarge" id="input04" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input05">确认密码</label>
                <div class="controls">
                    <input name="password2" type="password" class="input-xlarge" id="input05" value="" />
                </div>
            </div>						
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/exlAddStu" class="btn">批量添加</a> <a href="./index.php?r=admin/stuLst" class="btn">取消</a>

            </div>
        </fieldset>
    </form>
</div>
<script>
    $(document).ready(function(){
    $("#li-addStu").attr("class", "active");
            });
            $(document).ready(function(){
    var result = <?php echo "'$result'"; ?>;
            if (result === '1')
            alert('添加学生成功！');
            else if (result === '0')
            alert('添加学生失败！');
            });
            function getUserID(){
            var result = new Array();
<?php
foreach ($userAll as $key => $value) {
    $userID = $value['userID'];
    echo "result[$key] = '$userID';";
}
?>
            return result;
                    }
    function getclassID(){
    var result = new Array();
<?php
foreach ($classAll as $key => $value) {
    $classID = $value['classID'];
    $className = $value['className'];
    echo "result[$key] = '$classID';";
}
?>
    return result;
            }
    function getclassName(){
    var result = new Array();
<?php
foreach ($classAll as $key => $value) {
    $className = $value['className'];
    echo "result[$key] = '$className';";
}
?>
    return result;
            }
    $("#form-addStu").submit(function(){
    var userID = $("#input01")[0].value;
            if (userID === ""){
    alert('学生学号不能为空');
            return false;
    }
    if (getUserID().indexOf(userID) >= 0){
    alert('学生学号已存在！');
            return false;
    }
    var userName = $("#input02")[0].value;
            if (userName === ""){
    alert('学生姓名不能为空');
            return false;
    }
    var className = $("#input03")[0].value;
            if (className === ""){
    alert('学生班级不能为空');
            return false;
    }
    var classAll = getclassName();
            if (classAll.indexOf(className) < 0){
    alert('学生班级不存在！');
            return false;
    }
    var pass1 = $("#input04")[0].value;
            if (pass1 === ""){
    alert('密码不能为空');
            return false;
    }
    var pass2 = $("#input05")[0].value;
            if (pass2 === ""){
    alert('确认密码不能为空');
            return false;
    }
    if (pass1 !== pass2){
    alert('密码两次输入不相同！');
            return false;
    }
    var sex = document.getElementsByName("sex");
    for(var i=0;i<sex.length;i++){
        if(sex[i].checked){
            if(sex[i].value ==="男"){
                break;
            }else if(sex[i].value ==="女"){
                break;
            }
        }
        if(i===1){
            alert("请选择学生性别！");
            return false;
        }
    }
    
    
    var phone_number = $("#input09")[0].value;
            if (phone_number.length !== 11 && phone_number !== ""){
    alert('请输入正确的联系电话！');
            return false;
    }

    });
</script>