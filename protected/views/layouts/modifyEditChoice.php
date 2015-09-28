<?php  $optArr = explode("$$",$options);
        $mark = 'A'; ?>

<div class="span9">
<?php if(!isset($action)) {?>
        <h3>编辑选择题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看选择题</h3>
    <?php }?>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editChoiceInfo&&exerciseID=<?php echo $exerciseID;?>" id="myForm"> 
        <fieldset>
    <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="requirements" style="width:600px; height:50px;" id="input01"><?php echo $requirements;?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">A</label>
            <div class="controls">
                <input type="radio"  value="A" name="answer" <?php if($mark==$answer)
                                                                    echo "checked='checked'"; $mark++; ?>></input>
                <input name="A" type="text" class="input-xlarge" id="input02" value="<?php echo $optArr[0]; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input03">B</label>
            <div class="controls">
                <input type="radio"  value="B" name="answer" <?php if($mark==$answer)
                                                                    echo "checked='checked'"; $mark++; ?>></input>
                <input name="B" type="text" class="input-xlarge" id="input03" value="<?php echo $optArr[1]; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input04">C</label>
            <div class="controls">
                <input type="radio"  value="C" name="answer" <?php if($mark==$answer)
                                                                    echo "checked='checked'"; $mark++; ?>></input>
                <input name="C" type="text" class="input-xlarge" id="input04" value="<?php echo $optArr[2]; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input05">D</label>
            <div class="controls">
                <input type="radio"  value="D" name="answer" <?php if($mark==$answer)
                                                                    echo "checked='checked'";?>></input>
                <input name="D" type="text" class="input-xlarge" id="input05" value="<?php echo $optArr[3]; ?>"/>
            </div>
        </div>
    <div class="form-actions">
        <?php if(!isset($action)) {?>
            <button type="submit" class="btn btn-primary">修改</button> 
        <?php }?>
        <a href="./index.php?r=teacher/returnFromAddChoice" class="btn">返回</a>
    </div>
    </fieldset>
</form>   
</div>

<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "alert('$result');";?>
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        alert('题目内容不能为空');
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        alert('选项A内容不能为空');
        return false;
    }
    var B = $("#input03")[0].value;
    if(B === ""){
        alert('选项B内容不能为空');
        return false;
    }
    var C = $("#input04")[0].value;
    if(C === ""){
        alert('选项C内容不能为空');
        return false;
    }
    var D = $("#input05")[0].value;
    if(D === ""){
        alert('选项D内容不能为空');
        return false;
    }
    var answer = $('input:radio[name="answer"]:checked').val();
    if(answer == null){
        alert('请选择一个答案选项');
        return false;
    }

});
</script>



