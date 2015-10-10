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
                    <input name="userName" type="text" class="input-xlarge" id="input02" value="<?php echo $userName ?>" />
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
                    <input name="age" type="text" class="input-xlarge" id="input05" value="<?php echo $age ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input03">班级</label>
                <div class="controls">
                    <select name="classID" style="width: 285px">
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
                    <input name="phone_number" type="text" class="input-xlarge" id="input06" value="<?php echo $phone_number ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input07">联系邮箱</label>
                <div class="controls">
                    <input name="mail_address" type="text" class="input-xlarge" id="input07" value="<?php echo $mail_address ?>" />
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">提交</button>
                <?php if (Yii::app()->session['lastUrl'] == "stuDontHaveClass") { ?>
                    <a href="./index.php?r=admin/stuDontHaveClass&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn">返回</a>
<?php } else if (isset($flag)) { ?>
                    <a href="./index.php?r=admin/searchStu&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn">返回</a>
<?php } else { ?>
                    <a href="./index.php?r=admin/stuLst&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn">返回</a>
<?php } ?>　　
                <a  class="btn btn-primary" onclick="resetPass()">重置密码</a>
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
    echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.confirm);";
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
</script>