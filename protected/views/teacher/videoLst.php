<?php
$lessons = Lesson::model()->findall("classID='$classID'");
foreach ($lessons as $key => $value) {
    $lessonsName[$value['number']] = $value['lessonName'];
}

$username = Yii::app()->user->name;
$role = Yii::app()->session['role_now'];
$userid = Yii::app()->session['userid_now'];
$pptFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
$pdir = "./resources/" . $pptFilePath;
?>
<script src="<?php echo JS_URL; ?>jquery-form.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header">当前科目</li>
            <li id="li-<?php echo $progress; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $progress; ?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress]; ?></a></li>
            <li class="divider"></li>
            <li class="nav-header">其余科目</li>
            <?php
            foreach ($lessonsName as $key => $value):
                if ($key != $progress) {
                    ?>
                    <li id="li-<?php echo $key; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $key; ?>"><i class="icon-list-alt"></i> <?php echo $value; ?></a></li>
                    <?php
                }
            endforeach;
            ?>
        </ul>
    </div>
    <?php if (isset($_GET['url'])) { ?>
        <a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>" class="btn btn-primary">返回</a>
    <?php } else { ?>
        <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>" class="btn btn-primary">返回</a>
<?php } ?>
</div>
<div class="span9" style="position: relative; left: 20px">
    <h2 style="display:inline-block;">视频列表</h2>
    <span>(支持mp4及flv格式,最大2G)</span>
    <div id ="video-table"></div>
    <form name="form1" class="form-horizontal" id="myForm"  method="post" action="./index.php?r=teacher/addVideo&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>&&isnew=0" enctype="multipart/form-data"> 
        <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" />
        <div class="control-group">
            <div class="controls">
                <input type="file" name="file" id="input02">       
                <div id="upload" style="display:inline;" hidden="true">
                    <img src="./img/dformefault/upload-small.gif"  alt="正在努力上传.."/>                      
                    <div id="number">0%</div>              
                </div>      
                <button onclick="formSubmit()" type="button" class="btn btn-primary">上传</button>
                <span style="position: relative;left: 10px">
                    <input type="checkbox" name="checkbox"  value="" />
                    是否上传为公共资源
                </span>
            </div> 
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {/*
<?php //if (isset($result)) {
    //if ($result == '删除成功！') { ?>
               // window.wxc.xcConfirm("<?php// echo $result; ?>", window.wxc.xcConfirm.typeEnum.success);
    <?php// }
//} ?>*/
        $("#upload").hide();
    });

    $("#video-table").load("./index.php?r=teacher/videoTable&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>&&isnew=0");

    var options = {
        success: function (info) {
            window.wxc.xcConfirm(info, window.wxc.xcConfirm.typeEnum.info);
            $("#video-table").load("./index.php?r=teacher/videoTable&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>&&isnew=0");
            $("#upload").hide();
        },
        error: function (xhr, type, exception) {
            console.log('upload erroe', type);
            console.log(xhr.responseText, "Failed");
            window.wxc.xcConfirm("上传失败！", window.wxc.xcConfirm.typeEnum.error);
            $("#upload").hide();
        }
    };

    function fetch_progress() {
        $.get('./index.php?r=teacher/getProgress', {'<?php echo ini_get("session.upload_progress.name"); ?>': 'test'}, function (data) {
            var progress = parseInt(data);
            $('#number').html(progress + '%');
            if (progress < 100) {
                setTimeout('fetch_progress()', 100);
            } else {
                
            }
        }, 'html');
    }

    function formSubmit() {
        var s = document.form1.file.value;
        if (s == "") {
            window.wxc.xcConfirm("请选择文件！", window.wxc.xcConfirm.typeEnum.info);
        } else {
            $("#upload").show();
                
                $('#myForm').ajaxSubmit(options);
                setTimeout('fetch_progress()', 1000);
                return false;
                // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
       }
    }


    $(document).ready(function () {
        $("#li-<?php echo $on; ?>").attr("class", "active");
    });
</script>
