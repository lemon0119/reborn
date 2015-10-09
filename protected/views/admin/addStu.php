<?php require 'stuSideBar.php'; ?>
<div class="span9">
    <h2>添 加 学 生</h2>
    <form action="./index.php?r=admin/addStu" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01"><span class="must-fill" >*</span>学号</label>
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
                <label class="control-label" for="input03">班级名称</label>
                <div class="controls">
                    <select name="classID" style="width: 285px">
                        <option value="0" >以后再选</option>
                        <?php 
                            $classes = TbClass::model()->findall();
                            foreach ($classes as $value) {                               
                        ?>
                            <option value="<?php echo $value['classID']; ?>" ><?php echo $value['className']; ?></option>
                        <?php   
                            }
                        ?>
                    </select>
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
            </div>						-->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/exlAddStu" class="btn">批量添加</a> <a href="./index.php?r=admin/stuLst" class="btn">返回</a>

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
    window.wxc.xcConfirm('学生学号不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
    }
    if (getUserID().indexOf(userID) >= 0){
    window.wxc.xcConfirm('学生学号已存在！', window.wxc.xcConfirm.typeEnum.warning);
            return false;
    }
    var userName = $("#input02")[0].value;
            if (userName === ""){
    window.wxc.xcConfirm('学生姓名不能为空', window.wxc.xcConfirm.typeEnum.warning);
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
            window.wxc.xcConfirm("请选择学生性别！", window.wxc.xcConfirm.typeEnum.warning);
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