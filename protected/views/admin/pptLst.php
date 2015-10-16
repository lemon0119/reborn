<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>"><i class="icon-align-left"></i> 课业列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h2 style="display:inline-block;">PPT列表</h2>
    <span>(支持PPT格式)</span>
    <div id ="ppt-table"></div>
    <form class="form-horizontal" method="post" action="./index.php?r=admin/addPpt&&pdir=<?php echo $pdir;?>" id="myForm" enctype="multipart/form-data"> 
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
    
    <?php if(isset($result)){?>
    var result = <?php echo "'$result'";?>;
    if(result !== '0')
    window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.confirm);
    <?php }?>
});

    $("#ppt-table").load("./index.php?r=admin/pptTable&&pdir=<?php echo $pdir;?>");

    var options = {  
        success: function(info){
            window.wxc.xcConfirm(info, window.wxc.xcConfirm.typeEnum.confirm);
            $("#ppt-table").load("./index.php?r=admin/pptTable&&pdir=<?php echo $pdir;?>");
            $("#upload").hide();
        },
        error: function(xhr, type, exception){
            console.log('upload erroe', type);
            console.log(xhr.responseText, "Failed");
            window.wxc.xcConfirm("上传失败！", window.wxc.xcConfirm.typeEnum.error);
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
</script>
