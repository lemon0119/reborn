<?php require 'stuSideBar.php'; ?>
<div class="span9">
    <h2>添 加 学 生</h2>
    <form action="./index.php?r=admin/addStu" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01"><span class="must-fill" >*</span>学号</label>
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
                    <input name="age" type="text" class="input-xlarge" id="input07" value="" onblur="chkAge()" /><span id="usertips_age" style="margin-left: 5px;"></span>（1-99）
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">班级名称</label>
                <div class="controls">
                    <select name="classID" style="width: 285px;color: #000" id="input03" onchange="chkClass()">
                        <option value="0" >以后再选</option>
                        <?php 
                            $classes = TbClass::model()->findall();
                            foreach ($classes as $value) {
                                if($value['className']!="速录一班"){
                        ?>
                            <option value="<?php echo $value['classID']; ?>" ><?php echo $value['className']; ?></option>
                                <?php }  
                            }
                        ?>
                    </select>
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
            </div>						-->
            <div style="width: 80%;text-align: center">
                <button onclick="window.location.href = './index.php?r=admin/stuLst'" type="button" class="btn btn-primary">返 回</button> <button onclick="window.location.href = './index.php?r=admin/exlAddStu'" type="button" class="btn btn-primary">批量添加</button><button  type="submit" class="btn btn-primary">添加</button>
                
            </div>
        </fieldset>
    </form>
</div>
<script>
    $(document).ready(function(){
        //document.getElementById("sub").focus();
    $("#li-addStu").attr("class","active");
            });
            $(document).ready(function(){
    var result = <?php echo "'$result'"; ?>;
            if (result === '1')
        window.wxc.xcConfirm('添加学生成功！', window.wxc.xcConfirm.typeEnum.success);
            else if (result === '0')
        window.wxc.xcConfirm('添加学生失败！', window.wxc.xcConfirm.typeEnum.error);
            });
            function getUserID(){
            var result = new Array();
<?php
foreach ($userAll as $key => $value) {
    $userID = $value['userID'];
    
    echo "result[$key] = '$userID';";
}
?>
            return result.toString();
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
    var userID = $("#input01")[0].value.toLocaleUpperCase();
            if (userID === ""){
    window.wxc.xcConfirm('学生学号不能为空', window.wxc.xcConfirm.typeEnum.info);
            return false;
    }
    if (getUserID().indexOf(userID) >= 0){
    window.wxc.xcConfirm('学生学号已存在！', window.wxc.xcConfirm.typeEnum.info);
            return false;
    }
    var userName = $("#input02")[0].value;
            if (userName === ""){
    window.wxc.xcConfirm('学生姓名不能为空', window.wxc.xcConfirm.typeEnum.info);
            return false;
    }
     
//    var pass1 = $("#input04")[0].value;
//            if (pass1 === ""){
//    alert('密码不能为空');
//            return false;
//    }
//    var pass2 = $("#input05")[0].value;
//            if (pass2 === ""){
//    alert('确认密码不能为空');
//            return false;
//    }
//    if (pass1 !== pass2){
//    alert('密码两次输入不相同！');
//            return false;
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
            window.wxc.xcConfirm("请选择学生性别！", window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
    }
    
        var phone_number = $("#input09")[0].value;
        if (phone_number !=="" && phone_number.length!==11 ) {
            window.wxc.xcConfirm('请按提示正确填写资料！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
    
    var mail_address = $("#input10")[0].value;
        var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;  
        if(mail_address==""){
            return true;
        }else if (!pattern.test(mail_address)) {  
            window.wxc.xcConfirm('请按提示正确填写资料', window.wxc.xcConfirm.typeEnum.info);
            return false;
        }

    });
    
    function chkClass(){
        var usernameVal = document.getElementById("input03").value;
        $.ajax({
                    type: "POST",
                    url: "index.php?r=admin/getNum&&classID="+usernameVal,
                    data: {
                        classID: '"' + usernameVal + '"',
                    },
                    success: function (data) {
                        if(data=="error"){
                            window.wxc.xcConfirm('此班级已满!', window.wxc.xcConfirm.typeEnum.info);
                            document.getElementById("input03").value="0";
                        }
                    },
                });
    }
    
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
        
    
    //学号受限
    function chkIt(){
    var usernameVal = document.getElementById("input01").value;  
  
    usertipsSpan = document.getElementById("usertips");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!usernameVal.match( /^(?![0-9]+$)(?![a-zA-Z]+$)[A-Za-z0-9]+$/)) {  
        usertipsSpan.innerHTML="必须由字母和数字组成";  
        document.getElementById("input01").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
      
    if(usernameVal.length > 20){ //一个汉字算一个字符  
        usertipsSpan.innerHTML="大于20个字符！";  
        document.getElementById("input01").value="";
    }  
}
//姓名受限
 function chkName(){
    var usernameVal = document.getElementById("input02").value;  
  
    usertipsSpan = document.getElementById("usertips_name");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!usernameVal.match( /^[A-Za-z_\u4e00-\u9fa5]+$/)) {  
        usertipsSpan.innerHTML="必须由汉字或英文组成";  
        document.getElementById("input02").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
      
    if(usernameVal.length > 20){ //一个汉字算一个字符  
        usertipsSpan.innerHTML="大于20个字符！";  
        document.getElementById("input02").value="";
    }  
}
//年龄受限
 function chkAge(){
    var usernameVal = document.getElementById("input07").value;  
  
    usertipsSpan = document.getElementById("usertips_age");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!usernameVal.match( /^[0-9]+$/)) {  
        usertipsSpan.innerHTML="必须由数字组成的正整数";  
        document.getElementById("input07").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
      
    if(usernameVal > 100){ //一个汉字算一个字符  
        usertipsSpan.innerHTML="太老了吧亲";  
        document.getElementById("input07").value="";
    }  
}

</script>