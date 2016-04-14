<div class="span3">
    <div class="well-bottomnoradius" >
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-knowlage"></i>课时列表</li>
        </ul>
    </div>
    <div class="well-topnoradius" style="padding: 8px 0;height:580px;overflow:auto;top: -20px;border-top-left-radius:0px; ">
        <ul class="nav nav-list">
            <?php foreach ($allLesson as $Lesson): ?>
                    <li style="pointer-events: none;" ><a <?php if(Yii::app()->session['insert_lesson']==$Lesson['lessonName']){
echo 'style="color:#f46500"';Yii::app()->session['insert_lesson']="";}else{echo 'style="color: #aaa9a9"';}?>><i class="icon-list"></i><?php echo $Lesson['lessonName']; ?></a></li>
            <?php endforeach; ?>   
        </ul>
    </div>
</div>
<div class="span9">
    <h3>新建一课</h3>
    <form id="myForm" method="post" action="./index.php?r=admin/addLesson&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>" onkeydown="if(event.keyCode==13){return false;}"> 
        <fieldset>
            <legend>填写信息</legend>
            <div style="text-align: center"  class="control-group">
                <label style="position: relative;right:125px" class="control-label" for="input01">课名</label>
                <div class="controls">
                        <input name="lessonName" type="text" class="input-xlarge" id="input01"/>
                </div>
            </div>
            <div  style="text-align: center;">
                <a  href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>" class="btn btn-primary">返回</a>
                <button  type="submit" class="btn btn-primary">添加</button>
            </div>
        </fieldset>
    </form>   
</div>

<script>
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加课时成功！', window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
                    window.location.href="./index.php?r=admin/addLesson&&courseID=<?php echo $courseID; ?>&&courseName=<?php echo $courseName; ?>&&createPerson=<?php echo $createPerson; ?>";
                }
            });
    else if(result === '0')
    window.wxc.xcConfirm('添加课时失败！', window.wxc.xcConfirm.typeEnum.error);
});    
$("#myForm").submit(function(){
    var userID = $("#input01")[0].value;
    if(userID === ""){
        window.wxc.xcConfirm('课时不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
});
</script>

