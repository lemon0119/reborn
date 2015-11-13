<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchQuestion" method="post">
            <li>
                <select name="type" >
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="courseID" >科目号</option>
                    <option value="createPerson" >创建人</option>
                    <option value="requirements">内容</option>
                </select>
            </li>
            <li>
                <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                <button type="submit" class="btn_serch"></button>
                <a href="./index.php?r=admin/addQuestion" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li class="active"><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<div class="span9">          
    <?php if(!isset($action)) {?>
        <h3>修改简答题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看简答题</h3>
    <?php }?>
    <form id="myForm" class="form-horizontal" method="post" action="./index.php?r=admin/editQuestionInfo&&exerciseID=<?php echo $exerciseID;?>">
        <fieldset>
    <?php if(!isset($action)) {?>
        <legend>修改题目</legend>
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
                <textarea name="answer" style="width:450px; height:200px;" id="input02"><?php echo $answer; ?></textarea>
            </div>
        </div>
        <div  style="text-align: center;">
            <?php if(!isset($action)) {?> 
                <button type="submit" style="left: 135px;top: 49px;" class="btn_save_admin"></button>
            <?php }?>
            <a style="left: 150px;top: 50px;" href="./index.php?r=admin/returnFromAddQuestion&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn_ret_admin"></a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.success);";?>
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
});
</script>

