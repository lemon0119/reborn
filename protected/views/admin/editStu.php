<?php
if (Yii::app()->session['lastUrl'] == "stuDontHaveClass")
    require 'classLstSideBar.php';
else
    require 'stuSideBar.php';
?>
<div class="span9">
    <h2>编 辑 学 生</h2>
    <form action="./index.php?r=admin/editStuInfo&&id=<?php
    echo $userID;
    if (isset($flag))
        echo "&&flag=search";
    ?>" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>学生信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">学号</label>
                <div class="controls">
                    <input name="userID" type="text" class="input-xlarge" id="input01" value="<?php echo $userID ?>" readonly="true"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input02">姓名</label>
                <div class="controls">
                    <input name="userName" type="text" class="input-xlarge" id="input02" value="" onblur="chkName()" /><span id="usertips_name" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input04">性别</label>
                <div class="controls">
                    男
                    <input name="sex" type="radio"<?php
                    if ($sex == "男") {
                        echo 'checked="checked"';
                    }
                    ?>  class="input-xlarge"  value="男" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    女
                    <input name="sex" type="radio" <?php
                    if ($sex == "女") {
                        echo 'checked="checked"';
                    }
                    ?> class="input-xlarge"  value="女" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input05">年龄</label>
                <div class="controls">
                    <input name="age" type="text" class="input-xlarge" id="input05" value="" onblur="chkAge()"/><span id="usertips_age" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">班级</label>
                <div class="controls">
                    <select name="classID" style="width: 285px;border:1px solid #000;color:#000;">
                        <option value="0" >以后再选</option>
                        <?php
                        $classes = TbClass::model()->findall();
                        foreach ($classes as $value) {
                            if ($value['classID'] == $classID) {
                                ?>
                                <option value="<?php echo $value['classID']; ?>" selected="selected"><?php echo $value['className']; ?></option>
    <?php } else { ?>
                                <option value="<?php echo $value['classID']; ?>" ><?php echo $value['className']; ?></option>
    <?php }
} ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input06">联系电话</label>
                <div class="controls">
                    <input name="phone_number" type="text" class="input-xlarge" id="input06" value="" onblur="chkTel()"/><span id="usertips_tel" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input07">联系邮箱</label>
                <div class="controls">
                    <input name="mail_address" type="text" class="input-xlarge" id="input07" value="" onblur="chkMail()"/><span id="usertips_mail" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div style="text-align: center">
                <button type="submit"style="top:-2px;left: -45px" class="btn btn-primary">保存</button>
                <a  style="right: 40px" class="btn btn-primary" onclick="resetPass()">重置密码</a>
                <?php if (Yii::app()->session['lastUrl'] == "stuDontHaveClass") { ?>
                    <a style="right: 30px" href="./index.php?r=admin/stuDontHaveClass&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn btn-primary">返回</a>
<?php } else if (isset($flag)) { ?>
                    <a style="right: 30px" href="./index.php?r=admin/searchStu&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn btn-primary">返回</a>
<?php } else { ?>
                    <a style="right: 30px" href="./index.php?r=admin/stuLst&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn btn-primary">返回</a>
<?php } ?>　　
                
                
            </div>
        </fieldset>
    </form>
</div>
<script>
    $(document).ready(function () {
        $("#stuLst").attr("class", "active");
    });
<?php
if (isset($result)) {
    echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.success);";
    unset($result);
}
?>
    function resetPass() {
        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                window.location.href = "./index.php?r=admin/resetPass&&id=<?php
echo $userID;
if (isset($flag))
    echo "&&flag=search";
?>";
            }
        }
        window.wxc.xcConfirm("这将会重置这名学生的密码为：000，您确定这样吗？", "custom", option);
    }
    function getUserID() {
        var result = new Array();
<?php
$i = 0;
foreach ($userAll as $key => $value) {
    $stuID = $value['userID'];
    if ($userID == $value['userID'])
        $i = 1;
    else {
        $j = $key - $i;
        echo "result[$j] = '$stuID';";
    }
}
?>
        return result;
    }
    function getclassID() {
        var result = new Array();
<?php
foreach ($classAll as $key => $value) {
    $classID = $value['className'];
    echo "result[$key] = '$classID';";
}
?>
        return result;
    }
    $("#form-addStu").submit(function () {
        var userID = $("#input01")[0].value;
        if (userID === "") {
            window.wxc.xcConfirm('学生学号不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        if (getUserID().indexOf(userID) >= 0) {
            window.wxc.xcConfirm('学生学号已存在！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var userName = $("#input02")[0].value;
        if (userName === "") {
            window.wxc.xcConfirm('学生姓名不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var phone_number = $("#input06")[0].value;
        if (phone_number !== "" && phone_number.length !== 11) {
            window.wxc.xcConfirm('联系电话格式有误', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }

    });
    
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

 function chkAge(){
    var usernameVal = document.getElementById("input05").value;  
  
    usertipsSpan = document.getElementById("usertips_age");  
    usertipsSpan.style.color = "red";  
    usertipsSpan.style.marginLeft="25px";
    if (!usernameVal.match( /^[0-9]+$/)) {  
        usertipsSpan.innerHTML="必须由数字组成的正整数";  
        document.getElementById("input05").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML='';  
    }  
      
    if(usernameVal.length > 2){ //一个汉字算一个字符  
        usertipsSpan.innerHTML="太老了吧亲";  
        document.getElementById("input05").value="";
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
        document.getElementById("input06").value="";
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
        document.getElementById("input07").value="";
        return false;  
    } else {  
        usertipsSpan.innerHTML=''; 
    }  
    }
</script>