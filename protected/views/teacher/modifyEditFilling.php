<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=choice"><i class="icon-font"></i> 选择</a></li>
                        <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=filling"><i class="icon-text-width"></i> 填空</a></li>
                        <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=question"><i class="icon-align-left"></i> 简答</a></li>
                        <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=key"><i class="icon-th"></i> 键位练习</a></li>
                        <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=look"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=listen"><i class="icon-headphones"></i> 听打练习</a></li>                           
        </ul>
        
    </div>  
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table>
                    <tr><td><?php echo $currentClass['className']?></td></tr>
                    <tr><td><?php echo $currentLesson['lessonName']?></td></tr>
                    <tr><td><?php echo $suite['suiteName']?></td></tr>
                </table>                
            </center>                   
        </ul>
    </div>  
</div>

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
            alert("最多添加五个选项");
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
            alert("必须有一个答案");
        }
    }
    </script>  
    
<div class="span9">        

<h3>修改填空题</h3>
<form id="myForm" class="form-horizontal" method="post" action="./index.php?r=teacher/editFillInfo&&exerciseID=<?php echo $exerciseID;?>"> 
        <fieldset>
            <legend>填写题目</legend>
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
                    <a class="btn btn-primary" onclick="addIn()"><i class="icon-plus icon-white"></i></a> 
                    <a class="btn btn-primary" onclick="deleteIn()"><i class="icon-minus icon-white"></i></a>
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
             <a href="./index.php?r=teacher/returnFromAddFill" class="btn">取消</a>
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
        alert('题目内容不能为空');
        return false;
    }
    var x,y;
    for(x=2;x<=count;x++){
    var an = $("#input"+x)[0].value;
    if(an === ""){
        y=x-1;
        alert("空"+y+"内容不能为空");
        return false;
    }
    }
});
</script>




