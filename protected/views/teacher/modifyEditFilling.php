    <?php  $optArr = explode("$$",$answer);
        $len = count($optArr); ?>

    <script type="text/javascript">
    var count = <?php echo $len; ?>+1;
    function addIn()
    {
        if(count<6){
            count++;
            $("#input"+count).show();
        }else
        {
            window.wxc.xcConfirm("最多添加五个选项", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    function deleteIn()
    {
        if(count>2){
            $("#input"+count).hide();
            $("#input"+count).val("");
            count--;
        }else
        {
            window.wxc.xcConfirm("必须有一个答案", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    </script>  
    
<div class="span9">        

<?php if(!isset($action)) {?>
        <h3>编辑填空题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看填空题</h3>
    <?php }?>
<form id="myForm" class="form-horizontal" method="post" action="./index.php?r=teacher/editFillInfo&&exerciseID=<?php echo $exerciseID;?>"> 
        <fieldset>
    <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
         <div class="control-group">
             <label class="control-label" for="input1">题目</label>
             <div class="controls">
                 <textarea name="requirements" style="width:450px; height:50px;" id="input1"><?php echo $requirements; ?></textarea>
             </div>
         </div>
         <div class="control-group">
             <label class="control-label" for="input2">答案</label>
             <div class="controls">
                 <input name="in1" type="text" class="input-xlarge" id="input2" value="<?php echo $optArr[0];?>" />
                 <?php if(!isset($action)) {?>
                    <a class="btn_insert_admin" onclick="addIn()"></a> 
                    <a class="btn_subtract_admin" onclick="deleteIn()"></a>
                 <?php }?>
             </div>
         </div>
         <div class="control-group">
             <div class="controls">
                 <input name="in2" style="<?php if ($len<2) echo "display:none";?>" type="text" class="input-xlarge" id="input3" value="<?php if ($len>1) echo $optArr[1];?>" />
             </div>
         </div>
         <div class="control-group">
             <div class="controls">
                 <input name="in3" style="<?php if ($len<3) echo "display:none";?>" type="text" class="input-xlarge" id="input4" value="<?php if ($len>2) echo $optArr[2];?>" />
             </div>
         </div>
         <div class="control-group">
             <div class="controls">
                 <input name="in4" style="<?php if ($len<4) echo "display:none";?>" type="text" class="input-xlarge" id="input5" value="<?php if ($len>3) echo $optArr[3];?>" />
             </div>
         </div>
         <div class="control-group">
             <div class="controls">
                 <input name="in5" style="<?php if ($len<5) echo "display:none";?>" type="text" class="input-xlarge" id="input6" value="<?php if ($len>4) echo $optArr[4];?>" />
             </div>
         </div>
         <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
             <?php }?>
             <a href="./index.php?r=teacher/returnFromAddFill" class="btn btn-primary">返回</a>
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
    var requirements = $("#input1")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    var x,y;
    for(x=2;x<=count;x++){
    var an = $("#input"+x)[0].value;
    if(an === ""){
        y=x-1;
        window.wxc.xcConfirm("空"+y+"内容不能为空", window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    }
});
</script>




