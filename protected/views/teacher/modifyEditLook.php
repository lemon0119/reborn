
<div class="span9">     
<?php if(!isset($action)) {?>
        <h3>编辑看打练习题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看看打练习题</h3>
    <?php }?>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editLookInfo&&exerciseID=<?php echo $exerciseID;?>" id="myForm"> 
        <fieldset>
           <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">内容</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;"><?php echo $content; ?></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddLook&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.confirm);";?>
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
});
</script>



