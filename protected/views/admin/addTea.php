<?php require 'teaSideBar.php'; ?>
<div class="span9">
    <h2>添 加 老 师</h2>
    <form action="./index.php?r=admin/addTea" class="form-horizontal" method="post" id="form-addTea">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01"><span class="must-fill" >*</span>工号</label>
                <div class="controls">
                    <input name="userID" type="text" class="input-xlarge" id="input01" value="" onblur="chkIt()"/><span id="usertips" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input02"><span class="must-fill" >*</span>姓名</label>
                <div class="controls">
                    <input name="userName" type="text" class="input-xlarge" id="input02" value="" onblur="chkName()" /><span id="usertips_name" style="margin-left: 5px;"></span>
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
                    <input name="age" type="text" class="input-xlarge" id="input07" value="" onblur="chkAge()" /><span id="usertips_age" style="margin-left: 5px;"></span>&nbsp; &nbsp;(1-99)
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">部门</label>
                <div class="controls">
                    <input name="department" type="text" class="input-xlarge" id="input03" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input11">院校</label>
                <div class="controls">
                    <input name="school" type="text" class="input-xlarge" id="input11" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input09">联系电话</label>
                <div class="controls">
                    <input name="phone_number" type="text" class="input-xlarge" id="input09" value="" onblur="chkTel()"/><span id="usertips_tel" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input10">联系邮箱</label>
                <div class="controls">
                    <input name="mail_address" type="text" class="input-xlarge" id="input10" value="" onblur="chkMail()"/><span id="usertips_mail" style="margin-left: 5px;"></span>
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
            <div style="width: 80%;text-align: center">
                <button onclick="window.location.href = './index.php?r=admin/teaLst'" type="button" class="btn btn-primary">返 回</button> <button onclick="window.location.href = './index.php?r=admin/exlAddTea'" type="button" class="btn btn-primary">批量添加</button><button  type="submit" class="btn btn-primary">添加</button>
            </div>
        </fieldset>
    </form>
</div>
<script>
    $(document).ready(function () {
        $("#li-addTea").attr("class", "active");
       // document.getElementById("sub").focus();
        var result = <?php echo "'$result'"; ?>;
        if (result === '1')
            window.wxc.xcConfirm('添加老师成功！', window.wxc.xcConfirm.typeEnum.success);
        else if (result === '0')
            window.wxc.xcConfirm('添加老师失败！', window.wxc.xcConfirm.typeEnum.error);

    });
    function getUserID() {
        var result = new Array();
<?php
foreach ($userAll as $key => $value) {
    $userID = $value['userID'];
    echo "result[$key] = '$userID';";
}
?>
        return result;
    }
    $("#form-addTea").submit(function () {
        var userID = $("#input01")[0].value;
        if (userID === "") {
            window.wxc.xcConfirm('老师工号不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        if (getUserID().indexOf(userID) >= 0) {
            window.wxc.xcConfirm('老师工号已存在！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var userName = $("#input02")[0].value;
        if (userName === "") {
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
        for (var i = 0; i < sex.length; i++) {
            if (sex[i].checked) {
                if (sex[i].value === "男") {
                    break;
                } else if (sex[i].value === "女") {
                    break;
                }
            }
            if (i === 1) {
                window.wxc.xcConfirm("请选择老师性别！", window.wxc.xcConfirm.typeEnum.warning);
                return false;
            }
        }

        var phone_number = $("#input09")[0].value;
        if (phone_number.length !== 11 && phone_number !== "") {
            window.wxc.xcConfirm('请按提示正确填写资料！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }

        var mail_address = $("#input10")[0].value;
        var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
        if (mail_address == "") {
            return true;
        } else if (!pattern.test(mail_address)) {
            window.wxc.xcConfirm('请按提示正确填写资料！', window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
    });

    function chkMail(){
//        var phone_number = $("#input09")[0].value;
//            if (phone_number.length !== 11 && phone_number !== ""){
//        window.wxc.xcConfirm('请输入正确的联系电话！', window.wxc.xcConfirm.typeEnum.info);
//            return false;
//        }
        
    var mail_address = $("#input10")[0].value;
    var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
    usertipsSpan = document.getElementById("usertips_mail");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!pattern.test(mail_address)) {  
        usertipsSpan.innerHTML="请输入正确的邮箱地址";  
//        document.getElementById("input10").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML=''; 
    }  
    }
        

    function chkTel(){
//        var phone_number = $("#input09")[0].value;
//            if (phone_number.length !== 11 && phone_number !== ""){
//        window.wxc.xcConfirm('请输入正确的联系电话！', window.wxc.xcConfirm.typeEnum.info);
//            return false;
//        }
        
    var phone_number = $("#input09")[0].value;
  
    usertipsSpan = document.getElementById("usertips_tel");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (phone_number.length !== 11 && phone_number !== "") {  
        usertipsSpan.innerHTML="请输入正确的联系电话！";  
//        document.getElementById("input09").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
    }
    
    //工号受限
    function chkIt() {
        var usernameVal = document.getElementById("input01").value;

        usertipsSpan = document.getElementById("usertips");
        usertipsSpan.style.color = "red";
        usertipsSpan.style.marginLeft = "25px";
        if (!usernameVal.match(/^[A-Za-z]+[A-Za-z0-9]+$/)) {
            usertipsSpan.innerHTML = "必须由英文或者数字组成，首字符必须是英文";
            document.getElementById("input01").value = "";
            return false;
        } else {
            usertipsSpan.innerHTML = '';
        }

        if (usernameVal.length > 20) { //一个汉字算一个字符  
            usertipsSpan.innerHTML = "大于20个字符！";
            document.getElementById("input01").value = "";
        }
    }
    //姓名受限
    function chkName() {
        var usernameVal = document.getElementById("input02").value;

        usertipsSpan = document.getElementById("usertips_name");
        usertipsSpan.style.color = "red";
        usertipsSpan.style.marginLeft = "25px";
        if (!usernameVal.match(/^[A-Za-z_\u4e00-\u9fa5]+$/)) {
            usertipsSpan.innerHTML = "必须由汉字或英文组成";
            document.getElementById("input02").value = "";
            return false;
        } else {
            usertipsSpan.innerHTML = '';
        }

        if (usernameVal.length > 20) { //一个汉字算一个字符  
            usertipsSpan.innerHTML = "大于20个字符！";
            document.getElementById("input02").value = "";
        }
    }
//年龄受限
    function chkAge() {
        var usernameVal = document.getElementById("input07").value;

        usertipsSpan = document.getElementById("usertips_age");
        usertipsSpan.style.color = "red";
        usertipsSpan.style.marginLeft = "25px";
        if (!usernameVal.match(/^[0-9]+$/)) {
            usertipsSpan.innerHTML = "必须由数字组成的正整数";
            document.getElementById("input07").value = "";
            return false;
        } else {
            usertipsSpan.innerHTML = '';
        }

        if (usernameVal > 100) { //一个汉字算一个字符  
            usertipsSpan.innerHTML = "太老了吧亲";
            document.getElementById("input07").value = "";
        }
    }
</script>