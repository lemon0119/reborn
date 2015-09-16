<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>"><i class="icon-align-left"></i> 课业列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h2>视频列表</h2>
    <div id ="video-table"></div>
    <form class="form-horizontal" method="post" action="./index.php?r=admin/addVideo&&vdir=<?php echo $vdir;?>" id="myForm" enctype="multipart/form-data"> 
    <div class="control-group">
       <label class="control-label" for="input02">上传</label>
       <div class="controls">
       <input type="file" name="file" id="input02"> 
       <div id="upload" style="display:inline;" hidden="true">
       <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
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
});

    $("#video-table").load("./index.php?r=admin/videoTable&&vdir=<?php echo $vdir;?>");

    var options = {  
        success: function(info){
            alert(info);
            $("#video-table").load("./index.php?r=admin/videoTable&&vdir=<?php echo $vdir;?>");
            $("#upload").hide();
        },
        error: function(xhr, type, exception){
            console.log('upload erroe', type);
            console.log(xhr.responseText, "Failed");
            alert("上传失败！");
            $("#upload").hide();
        }
    };

$("#myForm").submit(function(){
    $("#upload").show();
    $(this).ajaxSubmit(options);   
        // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
    return false;   
});
</script>
