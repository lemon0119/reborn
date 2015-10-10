<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchListen" method="post">
            <li>
                <select name="type" >
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="courseID" >课程号</option>
                    <option value="createPerson" >创建人</option>
                    <option value="title">题目名</option>
                    <option value="content">内容</option>
                </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addListen" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li class="active"><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

    
<div class="span9">        
<?php if(!isset($action)) {?>
<h3>编辑听打练习题</h3>
<?php } else if($action == 'look') {?>
<h3>查看听打练习题</h3>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=admin/editListenInfo&&oldfilename=<?php echo $filename;?>&&exerciseID=<?php echo $exerciseID;?>&&filepath=<?php echo $filepath;?>" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
        <?php if(!isset($action)) {?>
            <legend>填写题目</legend>
        <?php } else if($action == 'look') {?>
            <legend>查看题目</legend>
        <?php }?>
        <?php $listenpath = "resources/".$filepath.$filename;?>
            
       <div class="control-group">
            <label class="control-label" for="input01">题目</label>
       <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title; ?></textarea>
            </div>
        </div>
            
        <div class="control-group">
            <label class="control-label" ></label>
          <audio  src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
        </div>
            
        <?php if(!isset($action)) {?>
            <div class="control-group">
                <label class="control-label" for="input02">修改</label>
                <div class="controls">
                <input type="file" name="modifyfile" id="input02">
                <div id="upload" style="display:inline;" hidden="true">
                <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
                     正在上传，请稍等...
                </div>
                </div>
            </div>                   
        <?php } else if($action == 'look') {?>
        <?php }?>   

            
        <div class="control-group">
            <label class="control-label" for="input03">内容</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;" id="input03"><?php echo $content; ?></textarea>
            </div>
        </div> 
            
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=admin/returnFromAddListen&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        </div>
            
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    $("#upload").hide();
    <?php 
        if(isset($result)){
            echo '$("#upload").hide();';
            echo "alert('$result');";
        }
    ?>
});



$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input03")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    $("#upload").show();
});
</script>
