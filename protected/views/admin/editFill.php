<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">查询</li>
        <form action="./index.php?r=admin/searchFill" method="post">
            <li>
                <select name="type" style="width: 185px">
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="courseID" >课程号</option>
                    <option value="createPerson" >创建人</option>
                </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">查询</button>
                    <a href="./index.php?r=admin/addFill" class="btn">添加</a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header">基础知识</li>
            <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li class="active"><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header">打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
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
<?php if(!isset($action)) {?>
<h3>修改填空题</h3>
<?php } else if($action == 'look') {?>
<h3>查看填空题</h3>
<?php }?>
<form id="myForm" class="form-horizontal" method="post" action="./index.php?r=admin/editFillInfo&&exerciseID=<?php echo $exerciseID;?>"> 
        <fieldset>
        <?php if(!isset($action)) {?>
            <legend>填写题目</legend>
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
             <a href="./index.php?r=admin/returnFromAddFill&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">取消</a>
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


