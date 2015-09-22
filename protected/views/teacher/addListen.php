<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=teacher/searchListen" method="post" >
                    <li>
                                <select name="type" style="width: 185px">
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >课程号</option>
                                        <option value="title" >题目名</option>
                                        <option value="createPerson" >创建人</option>
                                        <option value="content">内容</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn_serch"></button>
                                <a href="./index.php?r=teacher/addListen" class="btn_add"></a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
                        <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
                        <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
                        <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>

<div class="span9">        
<h3>添加听打练习题</h3>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/AddListen" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
           <legend>填写题目</legend>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title;?></textarea>
            </div>
        </div>
        <div class="control-group">
               <label class="control-label" for="input02">文件</label>
               <div class="controls">
               <input type="file" name="file" id="input02">      
               </div>
           </div>
        <div class="control-group">
            <label class="control-label" for="input03">内容</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;" id="input03"><?php echo $content;?></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddListen&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">取消</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert('添加听打练习成功！');
    else if(result === '0')
        alert('添加听打练习失败！');
    else if(result != 'no')
    {      
        alert(result);      
    }
        
});
$("#myForm").submit(function(){

    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        alert('题目内容不能为空');
        return false;
    }
    var uploadFile = $("#input02")[0].value;
    if(uploadFile === "")
    {
        alert('上传文件不能为空');
    return false;
    }
    
    var A = $("#input03")[0].value;
        if(A === ""){
        alert('内容不能为空');
        return false;
    }
    
    


});
</script>
  
    


