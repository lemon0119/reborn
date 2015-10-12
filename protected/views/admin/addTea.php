<?php require 'teaSideBar.php';?>
<div class="span9">
    <h2>添 加 老 师</h2>
    <form action="./index.php?r=admin/addTea" class="form-horizontal" method="post" id="form-addTea">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                    <label class="control-label" for="input01"><span class="must-fill" >*</span>工号</label>
                    <div class="controls">
                            <input name="userID" type="text" class="input-xlarge" id="input01" value="" />
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label" for="input02"><span class="must-fill" >*</span>姓名</label>
                    <div class="controls">
                            <input name="userName" type="text" class="input-xlarge" id="input02" value="" />
                    </div>
            </div>
           <div class="control-group">
                <label class="control-label" for="input06"><span class="must-fill" >*</span>性别</label>
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
                <label class="control-label" for="input03">所属部门</label>
                <div class="controls">
                    <input name="department" type="text" class="input-xlarge" id="input03" value="" />
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
<!--            <div class="control-group">
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
            </div>								-->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/exlAddTea" class="btn">批量添加</a> <a href="./index.php?r=admin/teaLst" class="btn">返回</a>
            </div>
        </fieldset>
    </form>
</div>
<script>     
$(document).ready(function(){
    $("#li-addTea").attr("class","active");
});
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加老师成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加老师失败！', window.wxc.xcConfirm.typeEnum.error);
        
});
function getUserID(){
    var result = new Array();
    <?php foreach ($userAll as $key => $value) {
        $userID = $value['userID'];
        echo "result[$key] = '$userID';";
    }?>
   return result;
}
$("#form-addTea").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        window.wxc.xcConfirm('老师工号不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    if(getUserID().indexOf(userID) >= 0){
        window.wxc.xcConfirm('老师工号已存在！', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var userName = $("#input02")[0].value;
    if(userName === ""){
        window.wxc.xcConfirm('老师姓名不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    
//    var pass1 = $("#input04")[0].value;
//    if(pass1 === ""){
//        alert('密码不能为空');
//        return false;
//    }
//    var pass2 = $("#input05")[0].value;
//    if(pass2 === ""){
//        alert('确认密码不能为空');
//        return false;
//    }
//    if(pass1 !== pass2){
//        alert('密码两次输入不相同！');
//        return false;
//    }
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
            window.wxc.xcConfirm("请选择老师性别！", window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
    }
    
    var phone_number = $("#input09")[0].value;
            if (phone_number.length !== 11 && phone_number !== ""){
     window.wxc.xcConfirm('请输入正确的联系电话！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
    }
});
</script>