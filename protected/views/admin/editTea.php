<?php require 'teaSideBar.php';?>
<div class="span9">
    <h2>编 辑 老 师</h2>
    <form action="./index.php?r=admin/editTeaInfo&&id=<?php echo $userID;
                                                             if(isset($flag))
                                                                echo "&&flag=search";
                            ?>" class="form-horizontal" method="post" id="form-addTea">
        <fieldset>
            <legend>老师信息</legend>
            <div class="control-group">
                    <label class="control-label" for="input01">工号</label>
                    <div class="controls">
                        <input name="userID" type="text" class="input-xlarge" id="input01" value="<?php echo $userID?>" readonly="readonly"/>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label" for="input02">姓名</label>
                    <div class="controls">
                            <input name="userName" type="text" class="input-xlarge" id="input02" value="<?php echo $userName?>" onblur="chkName()"/><span id="usertips_name" style="margin-left: 5px;"></span>
                    </div>
            </div>
             <div class="control-group">
                <label class="control-label" for="input04">性别</label>
                <div class="controls">
                    男
                    <input name="sex" type="radio"<?php if ($sex == "男") {
              echo 'checked="checked"';
          } ?>  class="input-xlarge"  value="男" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    女
                    <input name="sex" type="radio" <?php if ($sex == "女") {
              echo 'checked="checked"';
          } ?> class="input-xlarge"  value="女" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input05">年龄</label>
                <div class="controls">
                    <input name="age" type="text" class="input-xlarge" id="input05" value="<?php echo $age ?>" onblur="chkAge()"/><span id="usertips_age" style="margin-left: 5px;"></span>（1-99）
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">院系</label>
                <div class="controls">
                    <input name="department" type="text" class="input-xlarge" id="input03" value="<?php echo $department ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input08">院校</label>
                <div class="controls">
                    <input name="school" type="text" class="input-xlarge" id="input08" value="<?php echo $school ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input06">联系电话</label>
                <div class="controls">
                    <input name="phone_number" type="text" class="input-xlarge" id="input06" value="<?php echo $phone_number ?>" onblur="chkTel()"/><span id="usertips_tel" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input07">联系邮箱</label>
                <div class="controls">
                    <input name="mail_address" type="text" class="input-xlarge" id="input07" value="<?php echo $mail_address ?>" onblur="chkMail()"/><span id="usertips_mail" style="margin-left: 5px;"></span>
                </div>
            </div>
           
            <div style="text-align: center">
                <button type="submit"style="top:-2px;left: -45px" class="btn btn-primary">保存</button>　
                <a  style="right: 50px" class="btn btn-primary" onclick="resetPass()">重置密码</a>
                <?php if(isset($flag)){?>
                <a  style="right: 35px" href="./index.php?r=admin/searchTea&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn btn-primary">返回</a>
                <?php }else{?>
                <a style="right: 35px" href="./index.php?r=admin/teaLst&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn btn-primary">返回</a>
                <?php }?>　　
            </div>
        </fieldset>
    </form>
</div>
<script>
<?php 
    if(isset($result)){
        echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.success);";
        unset($result);
    }
?>
function resetPass(){
    var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href = "./index.php?r=admin/resetTeaPass&&id=<?php echo $userID;
                                                                        if(isset($flag))
                                                                            echo "&&flag=search";
                                                                    ?>";
						}
					}
					window.wxc.xcConfirm("这将会重置这名老师的密码为：000，您确定这样吗？", "custom", option);
}
function getUserID(){
    var result = new Array();
    <?php
        $i=0;
        foreach ($userAll as $key => $value) {
            $stuID = $value['userID'];
            if($userID==$value['userID'])
                $i=1;
            else {
               $j=$key-$i;
                echo "result[$j] = '$stuID';";  
            }
            }
    ?>
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
    var phone_number = $("#input06")[0].value;
        if (phone_number !=="" && phone_number.length!==11 ) {
            window.wxc.xcConfirm('请按提示正确填写资料！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        
        var mail_address = $("#input07")[0].value;
        var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
        if (mail_address == "") {
            return true;
        } else if (!pattern.test(mail_address)) {
            window.wxc.xcConfirm('请按提示正确填写资料！', window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
       
});

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
        var usernameVal = document.getElementById("input05").value;

        usertipsSpan = document.getElementById("usertips_age");
        usertipsSpan.style.color = "red";
        usertipsSpan.style.marginLeft = "25px";
        if (!usernameVal.match(/^[0-9]+$/)) {
            usertipsSpan.innerHTML = "必须由数字组成的正整数";
            document.getElementById("input05").value = "";
            return false;
        } else {
            usertipsSpan.innerHTML = '';
        }

        if (usernameVal > 100) { //一个汉字算一个字符  
            usertipsSpan.innerHTML = "太老了吧亲";
            document.getElementById("input05").value = "";
        }
    } 
    
        function chkTel(){
//        var phone_number = $("#input09")[0].value;
//            if (phone_number.length !== 11 && phone_number !== ""){
//        window.wxc.xcConfirm('请输入正确的联系电话！', window.wxc.xcConfirm.typeEnum.info);
//            return false;
//        }
        
    var phone_number = $("#input06")[0].value;
  
    usertipsSpan = document.getElementById("usertips_tel");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (phone_number.length !== 11 && phone_number !== "") {  
        usertipsSpan.innerHTML="请输入正确的联系电话！";  
//        document.getElementById("input06").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
    }
    
        function chkMail(){
//        var phone_number = $("#input09")[0].value;
//            if (phone_number.length !== 11 && phone_number !== ""){
//        window.wxc.xcConfirm('请输入正确的联系电话！', window.wxc.xcConfirm.typeEnum.info);
//            return false;
//        }
        
    var mail_address = $("#input07")[0].value;
    var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
    usertipsSpan = document.getElementById("usertips_mail");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!pattern.test(mail_address)) {  
        usertipsSpan.innerHTML="请输入正确的邮箱地址";  
//        document.getElementById("input07").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML=''; 
    }  
    }
</script>