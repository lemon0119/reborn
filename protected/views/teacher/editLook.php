<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i><span style="position: relative;top: 6px">搜索</span></li>
        <form action="./index.php?r=teacher/searchLook" method="post">
            <li>
                <select name="type" >
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="courseID" >科目号</option>
                    <option value="createPerson" >创建人</option>
                    <option value="title">题目名</option>
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
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>-->
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i><span style="position: relative;top: 6px">打字练习</span></li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i><span style="position: relative;top: 6px"> 键打练习</span></a></li>
            <li class="active"><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> <span style="position: relative;top: 6px">看打练习</span></a></li>
            <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i><span style="position: relative;top: 6px"> 听打练习</span></a></li>
        </ul>
    </div>
</div>

    
<div class="span9">        
<?php if(!isset($action)) {?>
<legend><h3>编辑看打练习题</h3><span style="color: red;font-size: 15px">(内容不可超出3000字，超出的部分将被屏蔽)</span></legend>
<?php } else if($action == 'look') {?>
<legend><h3>查看看打练习题</h3><span style="color: red;font-size: 15px">(内容不可超出3000字，超出的部分将被屏蔽)</span></legend>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editLookInfo&&exerciseID=<?php echo $exerciseID;?>" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>><?php echo $title; ?></textarea>
            </div><br>
            <div class="controls">
                <input type="checkbox" name="checkbox" value="" <?php if(strpos($title,"-不提示略码")){ ?> checked="checked" <?php } ?> style="position: relative;bottom:4px"/> 不提示略码
            </div>
        </div>
            <div class="control-group">
                <label class="control-label" for="input04">修改</label>
                <div class="controls">
                    <input type="file" name="modifyfiles" id="myfiles">
                </div>
            </div>
        <div class="control-group">
            <label class="control-label" for="input02">看打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;"<?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?> ><?php echo $content; ?></textarea>
                <br>字数：<span id="wordCount">0</span> 字
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddLook&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn btn-primary">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>
    var v=<?php echo Tool::clength($content);?>;
    $("#wordCount").text(v);
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === "" || requirements.lenght > 26){
        window.wxc.xcConfirm('题目内容不能为空且不超过20个字', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
});
</script>



