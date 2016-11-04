<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script> 
<script src="<?php echo JS_URL; ?>exerJS/LCS.js">
</script>
<div id="all">
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <br/>
        <ul >
            <li style="padding:0px 0px;" class="nav-header"><button style="padding:6px 60px" class="btn_4superbig" onclick="backToFreePractice()">自主练习</button></li>
        </ul>
        <br/>
<!--        <div class="well-topnoradius" style="padding: 8px 0;">-->
            <ul class="nav nav-list">
               <li class="nav-header"><img src="<?php echo IMG_UIStu_URL; ?>keyb.png">课 时 列 表</li>
            </ul>
<!--        </div>-->
        <div class="well-topnoradius" style="padding: 8px 0;height:626px;overflow:auto;top: 0px">
        <ul class="nav nav-list">
            
            <?php foreach ($lessons as $less) { ?>
                <li <?php
                if (isset($_GET['lessonID'])) {
                    if ($_GET['lessonID'] === $less['lessonID']) {
                        echo "class='active'";
                    }
                }
                ?> id="<?php echo $less['lessonID'] ?>">
                    <a href="./index.php?r=student/freePractice&&lessonID=<?php echo $less['lessonID'] ?>">
                        <img class="act" src="<?php echo IMG_UIStu_URL ?>listOf.png"><?php echo $less['lessonName'] ?>
                    </a>
                </li>
<?php } ?>
        </ul>
        </div>
    </div>
</div>
<?php if (isset($_GET['lessonID'])) { ?>
    <div id="iframeDiv" style="display:none;height: 772px"  class="span9" >
        <iframe id="iframe_classExercise" name="iframe_classExercise"  style="border: 0px;height: 100%;width: 100%;"></iframe>
    </div>
    <div id="exerciseDiv" class="span9" style=" height: 724px">
        <h3 ><font style="color:#f46500"><?php echo $nowlesson['lessonName']; ?></font> 已  开  放  的  练  习</h3>
        <table style="width: 98%;position: relative;" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width:15%" class="font-center">类型</th>
                    <!--<th class="font-center">科目号</th>-->
                    <th style="width:15%" class="font-center">标题</th>
                    <th style="width:55%" class="font-center">内容</th>
                    <th style="width:15%" class="font-center">操作</th>
                </tr>
            </thead>
            <tbody>   
    <?php foreach ($classExerciseLst as $model): ?>
                    <tr>
                        <td class="font-center" style="width: 100px"><?php
                            switch ($model['type']) {
                                case 'look': echo '看打练习';
                                    break;
                                case 'listen': echo '听打练习';
                                    break;
                                case 'speed': echo '速度练习';
                                    break;
                                case 'correct': echo '准确率练习';
                                    break;
                                case 'free': echo '自由练习';
                                    break;
                            }
                            ?></td>
                        <td title="<?php echo $model['title']; ?>" class="font-center"><?php
                            if (Tool::clength($model['title']) <= 10)
                                echo Tool::csubstr($model['title'], 0, 10);
                            else
                                echo Tool::csubstr($model['title'], 0, 10) . "...";
                            ?></td>
                        <td title="<?php echo $model['content']; ?>" class="font-center"><?php
                            if (Tool::clength($model['content']) <= 40)
                                echo Tool::csubstr($model['content'], 0, 25);
                            else
                                echo Tool::csubstr($model['content'], 0, 25) . "...";
                            ?></td>
                        <td><button id="startClassExercise" class="btn btn-primary" onclick="startClassExercise(<?php echo $model['exerciseID']; ?>, '<?php echo $model['type']; ?>')" >开始</a></td>
                    </tr> 
    <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="span9" style=" height: 722px">
        <h3 >自 主 练 习</h3>
        <!--        <button onclick="getSteno()">测试</button>-->
        <br/>
        <div class="fr" style="width:350px; position: relative;right: 10px">
            <table cellpadding="8" style="margin: 0px auto;">
                <tr>
                    <td><span class="fl"  style="font-weight: bolder">平均速度：</span><span style="color: #f46500" id="getAverageSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span><span class="fr" style="color: gray"> 字/分</span> </td>
                    <td><span class="fl"  style="font-weight: bolder">平均击键：</span><span style="color: #f46500" id="getAverageKeyType">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/分</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">瞬时速度：</span><span style="color: #f46500" id="getMomentSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字/分</span></td>
                    <td><span class="fl"  style="font-weight: bolder">瞬时击键：</span><span style="color: #f46500" id="getMomentKeyType">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">最高速度：</span><span style="color: #f46500" id="getHighstSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字/分</span></td>
                    <td><span class="fl"  style="font-weight: bolder">最高击键：</span><span style="color: #f46500" id="getHighstCountKey">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">击键间隔：</span><span style="color: #f46500" id="getIntervalTime">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span class="fl"  style="font-weight: bolder">最高间隔：</span><span style="color: #f46500" id="getHighIntervarlTime">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">总击键数：</span><span style="color: #f46500" id="getcountAllKey">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span class="fl"  style="font-weight: bolder">回改字数：</span><span style="color: #f46500" id="getBackDelete">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
            </table>
        </div>
        <div id="keyboard">
    <?php require Yii::app()->basePath . "\\views\\student\\keyboard_freePractice.php"; ?>
        </div>
    </div>
<?php } ?>
</div>
<script>

    //获取学生信息转入统计JS 实时存入数据库
//    window.G_saveToDatabase = 1;
//    <?php
//$sqlClassExerciseRecord = ClassexerciseRecord::model()->findAll("classExerciseID = '2'");
// $countSquence = count($sqlClassExerciseRecord);
// $squence = $countSquence+1;
?>//
//    window.G_squence = <?php // echo $squence;  ?>;
//    window.G_exerciseType = "classExercise";
//    var classExerciseID = 2;
//    var studentID = "<?php // echo Yii::app()->session['userid_now'];   ?>";
//    window.G_exerciseData = Array(classExerciseID,studentID);

    function startClassExercise(exerciseID, type) {
        $("#iframeDiv").removeAttr("style");
        $("#iframeDiv").attr("style", "height:774px;width :840px; padding:15px;");
        $("#exerciseDiv").attr("style", "display:none");
        if (type === "look") {
            $("#iframe_classExercise").attr("src", "index.php?r=student/freeIframe4Looks&&ispractice&&exerciseID=" + exerciseID);
            var htmltxt = '<div  class="analysisTool" id="analysis" style="position: absolute;left:1277px;bottom: 310px">'+
    '<table style="margin: 0px auto;">'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">练习计时：</span><span style="color: greenyellow" id="timej">00:00:00</span ></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">正&nbsp;确&nbsp;&nbsp率：</span><span style="color: greenyellow" id="wordisRightRadio">0</span ><span class="fr" style="color: #fff"> %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>'+                       
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">平均速度：</span><span style="color: greenyellow" id="getAverageSpee">&nbsp;&nbsp;0&nbsp;&nbsp;</span><span class="fr" style="color: #fff"> 字/分</span> </td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时速度：</span><span style="color: greenyellow" id="getMomentSpee">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高速度：</span><span style="color: greenyellow" id="getHighstSpee">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">平均击键：</span><span style="color: greenyellow" id="getAverageKeyTyp">0</span ><span class="fr" style="color: #fff"> 次/分</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时击键：</span><span style="color:greenyellow" id="getMomentKeyTyp">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高击键：</span><span style="color: greenyellow" id="getHighstCountKe">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">击键间隔：</span><span style="color: greenyellow" id="getIntervalTim">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>'+
        '</tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高间隔：</span><span style="color: greenyellow" id="getHighIntervarlTim">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>'+
        '</tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">总击键数：</span><span style="color: greenyellow" id="getcountAllKe">0</span ><span class="fr" style="color: #fff"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>'+
        '<tr><td><span class="fl"  style="color: #fff;font-weight: bolder">回改字数：</span><span style="color: greenyellow" id="getBackDelet">0</span ><span class="fr" style="color: #fff"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>'+
    '</table>'+
'</div>';
           var all = document.getElementById("all");
           all.innerHTML = all.innerHTML + htmltxt;
        } else if (type === "listen") {
            $("#iframe_classExercise").attr("src", "index.php?r=student/freeIframe4Listen&&ispractice&&exerciseID=" + exerciseID);
        } else {
            $("#iframe_classExercise").attr("src", "index.php?r=student/freeIframe4Key&&ispractice&&exerciseID=" + exerciseID);
        }
    }
    function backToFreePractice() {
        $("#iframe_classExercise").attr("src", "");
        $("#iframeDiv").attr("style", "display:none");
        window.location.href = "./index.php?r=student/freePractice";
    }

    function closeClassExercise() {
        $("#iframe_classExercise").attr("src", "");
        $("#iframeDiv").attr("style", "display:none");
        $("#exerciseDiv").removeAttr("style");
    }

    function alertStartKeyExercise() {
        window.wxc.xcConfirm("即将开始！您将有3秒准备时间！", window.wxc.xcConfirm.typeEnum.warning, {
            onOk: function () {
                iframe_classExercise.window.start();
            }
        });
    }



</script>