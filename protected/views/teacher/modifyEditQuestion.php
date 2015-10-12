
 <div class="span9">          
        <?php if(!isset($action)) {?>
        <h3>编辑简答题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看简答题</h3>
    <?php }?>
    <form id="myForm" class="form-horizontal" method="post" action="./index.php?r=teacher/editQuestionInfo&&exerciseID=<?php echo $exerciseID;?>">
        <fieldset>
    <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="requirements" style="width:450px; height:50px;" id="input01"><?php echo $requirements; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">答案</label>
            <div class="controls">               
                <textarea name="answer" style="width:450px; height:200px;"><?php echo $answer; ?></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddQuestion" class="btn">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.confirm);";?>
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



