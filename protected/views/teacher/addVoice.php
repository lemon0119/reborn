<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }
    
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $voFilePath =$role."/".$userid."/".$classID."/".$on."/voice/"; 
    $vodir = "./resources/".$voFilePath;
    
?>
<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
<script src="<?php //echo JS_URL; ?>jquery.min.js" ></script>
<script src="<?php echo JS_URL; ?>bootstrap.min.js" ></script>
<script src="<?php echo JS_URL; ?>site.js" ></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
<link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js" ></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js"></script>

<div class="span9" style="position: relative; left: 20px;margin-left: 300px;">
    <h2 style="display:inline-block;">音频列表</h2>
    <div id ="voice-table"></div>
    <form class="form-horizontal" id="myForm"  method="post" action="./index.php?r=teacher/addVoiceNew&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&isnew=1" enctype="multipart/form-data"> 
     <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" /> 
        <div class="control-group">
       <label class="control-label" for="input02">上传</label>
       <div class="controls">
       <input type="file" name="file" id="input02"> 
       <div id="upload" style="display:inline;" hidden="true">
       <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
            <div id="number">0%</div>
       </div>
       <button type="submit" class="btn btn-primary">上传</button>
        <span style="position: relative;left: 10px">
       <input type="checkbox" name="checkbox"  value="" />
       是否上传为公共资源
       </span>
       </div>
    </div>
    </form>
</div>
<script>
    $(document).ready(function(){
        <?php if(isset($result)){ if($result=='删除成功！'){ ?>
            window.wxc.xcConfirm("<?php echo $result; ?>", window.wxc.xcConfirm.typeEnum.success);
        <?php  } }?>
        <?php if(isset($result)){ if($result=='上传成功!'){ ?>
            window.wxc.xcConfirm("<?php echo $result; ?>", window.wxc.xcConfirm.typeEnum.success);
        <?php  } }?>
        <?php if(isset($result)){ if($result=='请选择文件！'){ ?>
            window.wxc.xcConfirm("<?php echo $result; ?>", window.wxc.xcConfirm.typeEnum.info);
        <?php  } }?>
        <?php if(isset($result)){ if($result=='请上传正确类型的文件！'){ ?>
            window.wxc.xcConfirm("<?php echo $result; ?>", window.wxc.xcConfirm.typeEnum.info);
        <?php  } }?>
        <?php if(isset($result)){ if($result=='该文件已存在，如需重复使用请改名重新上传！'){ ?>
            window.wxc.xcConfirm("<?php echo $result; ?>", window.wxc.xcConfirm.typeEnum.info);
        <?php  } }?>
    $("#upload").hide();
});

    $("#voice-table").load("./index.php?r=teacher/voiceTable&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&isnew=1");

    var options = {  
        success: function(info){
            window.wxc.xcConfirm(info, window.wxc.xcConfirm.typeEnum.info);
            $("#voice-table").load("./index.php?r=teacher/voiceTable&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>");
            $("#upload").hide();
        },
        error: function(xhr, type, exception){
            console.log('upload erroe', type);
            console.log(xhr.responseText, "Failed");
            window.wxc.xcConfirm("上传失败！", window.wxc.xcConfirm.typeEnum.error);
            $("#upload").hide();
        }
    };
    
         function fetch_progress(){
        $.get('./index.php?r=teacher/getProgress',{ '<?php echo ini_get("session.upload_progress.name"); ?>' : 'test'}, function(data){
                var progress = parseInt(data);                              
                $('#number').html(progress + '%');
                if(progress < 100){
                        setTimeout('fetch_progress()', 100);
                }else{           
        }
        }, 'html');
    }
    

$("#myForm").submit(function(){
    $("#upload").show();
    setTimeout('fetch_progress()', 1000);
    $(this).ajaxSubmit(options);   
        // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
    return false;   
});
    
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>
