<?php require 'teaSideBar.php';?>
<div class="span9">
    <h2>添 加 老 师</h2>
    <form action="./index.php?r=admin/addTea" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                    <label class="control-label" for="input01">工号</label>
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
                <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/teaLst" class="btn">取消</a>
            </div>
        </fieldset>
    </form>
</div>
<script>     
$(document).ready(function(){
    $("#li-addStu").attr("class","active");
});
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert('添加老师成功！');
    else if(result === '0')
        alert('添加老师失败！');
        
});
function getUserID(){
    var result = new Array();
    <?php foreach ($userAll as $key => $value) {
        $userID = $value['userID'];
        echo "result[$key] = '$userID';";
    }?>
   return result;
}
$("#form-addStu").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        alert('老师工号不能为空');
        return false;
    }
    if(getUserID().indexOf(userID) >= 0){
        alert('老师工号已存在！');
        return false;
    }
    var userName = $("#input02")[0].value;
    if(userName === ""){
        alert('老师姓名不能为空');
        return false;
    }
    
    var pass1 = $("#input04")[0].value;
    if(pass1 === ""){
        alert('密码不能为空');
        return false;
    }
    var pass2 = $("#input05")[0].value;
    if(pass2 === ""){
        alert('确认密码不能为空');
        return false;
    }
    if(pass1 !== pass2){
        alert('密码两次输入不相同！');
        return false;
    }
});
</script>