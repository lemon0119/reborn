 <div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:5px;left:"></i>搜索</li>
        <form action="./index.php?r=teacher/searchLook" method="post">
            <li>
                <select name="type" >
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="title">题目名</option>
                    <option value="createPerson" >创建人</option>
                    <option value="content">内容</option>
                </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn_4big">搜 索</button>
                    <button onclick="window.location.href = './index.php?r=teacher/AddLook'" type="button" class="btn_4big">添 加</button>
            </li>
        </form>
<!--            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font" style="position:relative;bottom:5px;left:"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width" style="position:relative;bottom:5px;left:"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left" style="position:relative;bottom:5px;left:"></i> 简答</a></li>-->
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:5px;left:"></i>打字练习</li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
            <li class="active"><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

    
<div class="span9">        
<?php if(!isset($action)) {?>
<h3>编辑看打练习题</h3>
<?php } else if($action == 'look') {?>
<h3>查看看打练习题</h3>
<?php }?>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/AddLook" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
        <?php if(!isset($action)) {?>
            <legend>填写题目<span style="color: red;font-size: 15px">(内容不可超出3000字，超出的部分将被屏蔽)</span></legend>
        <?php } else if($action == 'look') {?>
            <legend>查看题目<span style="color: red;font-size: 15px">(内容不可超出3000字，超出的部分将被屏蔽)</span></legend>
        <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"></textarea>
            </div><br>
            <div class="controls">
                <input type="checkbox" name="checkbox" value="" style="position: relative;bottom:4px"/> 不提示略码
            </div>
        </div>
          <div class="control-group">
                <label class="control-label" for="input04">上传答案</label>
                <div class="controls">
                    <input type="file" name="myfiles" id="myfiles" onchange="getImgURL(this)"  >
                    <!--<input class="btn btn-primary"  type="button" onclick ="uplodes()" value="上传">-->
                </div>
            </div>  
        <div class="control-group" id="answers">
            <label class="control-label" for="input02">看打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;"  id="input02"></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddLook&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn btn-primary">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script> 
 function getImgURL(node) {
     document.getElementById("answers").style.display = "none";
 }    
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加看打练习成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加看打练习失败！', window.wxc.xcConfirm.typeEnum.error);
    else if (result != 'no')
    {
            window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.info);
    }
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === "" || requirements.length > 20){
        window.wxc.xcConfirm('题目内容不能为空且不超过20个字', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input02")[0].value;
    var files =  document.getElementById("myfiles").value;
    if(A == "" && files === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
});
</script>


