<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">查询</li>
        <form action="./index.php?r=admin/searchChoice" method="post">
            <li>
                    <select name="type" style="width: 185px">
                            <option value="exerciseID" selected="selected">编号</option>
                            <option value="courseID" >课程号</option>
                    </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">查询</button>
                    <a href="./index.php?r=admin/addChoice" class="btn">添加</a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header">基础知识</li>
            <li class="active"><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header">打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<div class="span9">
    <h2>添加看打练习</h2>
    <form id="myForm" class="form-horizontal" method="post" action="./index.php?r=admin/choiceLst&&action=add"> 
        <fieldset>
        <legend>填写题目</legend>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="requirements" style="width:600px; height:50px;"></textarea>
            </div>
        </div>
        <div class="control-group">
            <input type="radio"  value="A" name="answer" for="input01">A</input>
            <div class="controls">
                <input name="A" type="text" class="input-xlarge" id="input02" value="" />
            </div>
        </div>
    <br/>
    <input type="radio" value="B"  name="answer">B</input>&nbsp&nbsp
    <input type="text" name="B" style="width: 120px">
    <br/>

    <input type="radio" value="C"  name="answer">C</input>&nbsp&nbsp
    <input type="text" name="C" style="width: 120px">
    <br/>
    
    <input type="radio" value="D"  name="answer">D</input>&nbsp&nbsp
    <input type="text" name="D" style="width: 120px">
    <br/>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=admin/returnFromAddChoice" class="btn">取消</a>
    </div>
    </fieldset>
</form>   
<?php
if(isset($shao))
{
     echo $shao;
}
?>

</div>
</div>
</div>

<?php
//显示操作结果
if(isset($result))
{
   if(!empty($result))
   {
       echo "<script>var result = '$result';</script>";
   }
}
?>
<script>
if(result != null){
    alert(result);
    result = null;
}
</script>



