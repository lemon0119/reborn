<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']] = $value['lessonName'];
    }
    
    $username    = Yii::app()->user->name;
    $role        = Yii::app()->session['role_now'];
    $userid      = Yii::app()->session['userid_now'];             
    $pptFilePath = $role."/".$userid."/".$classID."/".$on."/ppt/"; 
    $pdir        = "./resources/".$pptFilePath;
    
?>
<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">当前科目</li>
        <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
        <li class="divider"></li>
        <li class="nav-header">其余科目</li>
        <?php foreach($lessonsName as $key => $value):
            if($key!=$progress){
            ?>
            <li id="li-<?php echo $key;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $key;?>"><i class="icon-list-alt"></i> <?php echo $value;?></a></li>
            <?php
            } 
            endforeach;?>
        </ul>
    </div>
    <?php if(isset($_GET['url'])){ ?>
         <a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary">返回</a>
    <?php }else{ ?>
        <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary">返回</a>
    <?php }?>
</div>
<div class="span9" style="position: relative; left: 20px">
    <h2 style="display:inline-block;">PPT列表</h2>
    <span>(支持PPT格式,最大100M)</span>
    <div id ="ppt-table"></div>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/addPpt&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" id="myForm" enctype="multipart/form-data"> 
    <div class="control-group">
       <label class="control-label" for="input02">上传</label>
       <div class="controls">
       <input type="file" name="file" id="input02"> 
       <div id="upload" style="display:inline;" hidden="true">
       (ppt最大100M)<img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
            正在上传，请稍等...
       </div>
       <button type="submit" class="btn btn-primary">上传</button>
       </div>
    </div>
    </form>
</div>
<script>
    $(document).ready(function(){
    $("#upload").hide();
    /*
    <?php if(isset($result)){?>
    var result = <?php echo "'$result'";?>;
    if(result !== '0')
    window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.confirm);
    <?php }?>
        */
});

    $("#ppt-table").load("./index.php?r=teacher/pptTable&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>");

    var options = {  
        success: function(info){
            window.wxc.xcConfirm(info, window.wxc.xcConfirm.typeEnum.info);
            $("#ppt-table").load("./index.php?r=teacher/pptTable&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>");
            $("#upload").hide();
        },
        error: function(xhr, type, exception){
            console.log('upload erroe', type);
            console.log(xhr.responseText, "Failed");
            window.wxc.xcConfirm("上传失败！", window.wxc.xcConfirm.typeEnum.info);
            $("#upload").hide();
        }
        //type:'post',
        //dataType:'json',
        //resetForm:false,
       // timeout:10000
    };

$("#myForm").submit(function(){
    $("#upload").show();
    $(this).ajaxSubmit(options);   
        // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
    return false;   
});
    
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>
