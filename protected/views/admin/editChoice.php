<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchChoice" method="post">
            <li>
                    <select name="type" >
                            <option value="exerciseID" selected="selected">编号</option>
                            <option value="courseID" >科目号</option>
                            <option value="createPerson">创建人</option>
                            <option value="requirements">内容</option>
                    </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addChoice" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li class="active"><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键打练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<?php  $optArr = explode("$$",$options);
        $mark = 'A'; ?>
<div class="span9">
    <?php if(!isset($action)) {?>
        <h3>编辑选择题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看选择题</h3>
    <?php }?>
        
        
    <form class="form-horizontal" method="post" action="./index.php?r=admin/editChoiceInfo&&exerciseID=<?php echo $exerciseID;?>" id="myForm"> 
        <fieldset>
    <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="requirements" style="width:450px; height:50px;" id="input01"><?php echo $requirements;?></textarea>
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
    <div  style="text-align: center;">
        <?php if(!isset($action)) {?>
            <button type="submit" style="left: 135px;top: 49px;" class="btn_save_admin"></button> 
        <?php }?>
            <a style="left: 150px;top: 50px;" href="./index.php?r=admin/returnFromAddChoice&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn_ret_admin"></a>
    </div>
    </fieldset>
</form>   
</div>

<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('选项A内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var B = $("#input03")[0].value;
    if(B === ""){
        window.wxc.xcConfirm('选项B内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var C = $("#input04")[0].value;
    if(C === ""){
        window.wxc.xcConfirm('选项C内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var D = $("#input05")[0].value;
    if(D === ""){
        window.wxc.xcConfirm('选项D内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var answer = $('input:radio[name="answer"]:checked').val();
    if(answer == null){
        window.wxc.xcConfirm('请选择一个答案选项', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }

});
</script>