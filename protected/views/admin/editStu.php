<?php
    if(Yii::app()->session['lastUrl']=="stuDontHaveClass")
        require 'classLstSideBar.php';
    else
        require 'stuSideBar.php';?>
<div class="span9">
    <h2>编 辑 学 生</h2>
    <form action="./index.php?r=admin/editStuInfo&&id=<?php echo $userID;
                                                             if(isset($flag))
                                                                echo "&&flag=search";
                            ?>" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>学生信息</legend>
            <div class="control-group">
                    <label class="control-label" for="input01">学号</label>
                    <div class="controls">
                        <input name="userID" type="text" class="input-xlarge" id="input01" value="<?php echo $userID?>" />
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label" for="input02">姓名</label>
                    <div class="controls">
                            <input name="userName" type="text" class="input-xlarge" id="input02" value="<?php echo $userName?>" />
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label" for="input03">班级</label>
                    <div class="controls">
                            <input name="classID" type="text" class="input-xlarge" id="input03" value="<?php echo $classID?>" />
                    </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">提交</button>
                <?php if(Yii::app()->session['lastUrl']=="stuDontHaveClass"){?>
                    <a href="./index.php?r=admin/stuDontHaveClass" class="btn">返回</a>
                <?php }else if(isset($flag)){?>
                    <a href="./index.php?r=admin/searchStu" class="btn">返回</a>
                <?php }else{?>
                    <a href="./index.php?r=admin/stuLst" class="btn">返回</a>
                <?php }?>　　
                <a  class="btn btn-primary" onclick="resetPass()">重置密码</a>
            </div>
        </fieldset>
    </form>
</div>
<script>
$(document).ready(function(){
    $("#stuLst").attr("class","active");
});
<?php 
    if(isset($result)){
        echo "alert('$result');";
        unset($result);
    }
?>
function resetPass(){
    if(confirm("这将会重置这名学生的密码为：000，您确定这样吗？")){
        window.location.href = "./index.php?r=admin/resetPass&&id=<?php echo $userID;
                                                                        if(isset($flag))
                                                                            echo "&&flag=search";
                                                                    ?>";
    } else 
        return false;
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
function getclassID(){
    var result = new Array();
<?php foreach ($classAll as $key => $value) {
    $classID = $value['classID'];
    echo "result[$key] = '$classID';";
}?>
   return result;
}
$("#form-addStu").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        alert('学生学号不能为空');
        return false;
    }
    if(getUserID().indexOf(userID) >= 0){
        alert('学生学号已存在！');
        return false;
    }
    var userName = $("#input02")[0].value;
    if(userName === ""){
        alert('学生姓名不能为空');
        return false;
    }
    var classID = $("#input03")[0].value;
    if(classID === ""){
        alert('学生班级不能为空');
        return false;
    }
    var classAll = getclassID();
    if(classAll.indexOf(classID) < 0){
        alert('学生班级不存在！');
        return false;
    }
});
</script>