<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/timep.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>
<body style="background-image: none;background-color: #fff">
    <div id="span" class="hero-unit" align="center">
        <table style="width: 580px"  border = '0px'><button id="finish" onclick="finish()" style="margin-left:30px;" class="fl btn btn-primary" >完成练习</button><button id="close_exercise" class="fr btn btn-primary">关闭</button>
            <tr><h3><?php echo $classExercise['title'] ?></h3></tr>
            <tr>
                <td><span class="fl"  style="color: #000;font-weight: bolder">练习计时：</span></td>
                <td><span style="color: #f46500" id="timej">00:00:00</span></td>
                <td></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">正确率：&nbsp;&nbsp;</span></td>
                <td style="width: 60px;"><span style="color: #f46500" id="wordisRightRadio">0</span></td>
                <td><span class="fr" style="color: gray"> %&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">完成进度：</span></td>
                <td colspan="2"><span style="color:green" id="isDone">0</span>/<span  style="color: #f46500" id="AllOfWord"></span><span hidden="true" style="color: #f46500" id="repeatNum"><?php echo $classExercise['repeatNum'] ?></span ></td>
            <tr>
                <td><span class="fl"   style="color: #000;font-weight: bolder">平均速度：</span></td>
                <td><span style="color: #f46500" id="getAverageSpeed">0</span></td>
                <td><span class="fr" style="color: gray"> 字/分</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">瞬时速度：</span></td>
                <td><span style="color: #f46500" id="getMomentSpeed">0</span ></td>
                <td><span class="fr" style="color:gray"> 字/分</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">最高速度：</span></td>
                <td><span style="color: #f46500" id="getHighstSpeed">0</span ></td>
                <td><span class="fr" style="color:gray"> 字/分</span></td>
            </tr>
            <tr>
                <td><span class="fl"   style="color: #000;font-weight: bolder">平均击键：</span></td>
                <td><span style="color: #f46500" id="getAverageKeyType">0</span ></td>
                <td><span class="fr" style="color: gray"> 次/分</span></td>
                <td><span class="fl"  style="color: #000;font-weight: bolder">瞬时击键：</span></td>
                <td><span style="color:#f46500" id="getMomentKeyType">0</span ></td>
                <td><span class="fr" style="color:gray"> 次/秒</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">最高击键：</span></td>
                <td><span style="color: #f46500" id="getHighstCountKey">0</span ></td>
                <td><span class="fr" style="color: gray"> 次/秒</span></td>
            </tr>
            <tr>
                <td><span class="fl"   style="color: #000;font-weight: bolder">击键间隔：</span></td>
                <td><span style="color: #f46500" id="getIntervalTime">0</span ></td>
                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">最高间隔：</span></td>
                <td><span style="color: #f46500" id="getHighIntervarlTime">0</span ></td>
                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">总击键数：</span></td>
                <td><span style="color: #f46500" id="getcountAllKey">0</span ></td>
                <td><span class="fr" style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr>

        </table>
        <br/>
        <div class="progressbox" align="left">
            <div class="progresstbox">
                <div class="progresstiao"></div>
            </div>
        </div>
        <table id="keyMode" style="height: 60px; font-size: 50px; border: 1px solid #000">
            <tr>
                <td id="wordLast" style="border-right: 1px solid #000; width: 180px;text-align:right;"></td>  
                <td id="word" style="border-right: 1px solid #000; width: 250px;text-align:right;"></td>   
                <td id="wordNext" style="color:#909090;border-right: 1px solid #000; width: 180px;text-align:right;"></td>  
            </tr>
        </table>
        <br/>
        
        <div id ="templet" class ="questionBlock" onselectstart="return false" style="display: none">
            <font id="id_right"style="color:#808080"></font><font id="id_wrong" style="color:#ff0000"></font><font id="id_new" style="color:#000000"> </font>
        </div>
        <div style="width: 700px; height: 350px;">
            <?php
            if ($classExercise['type'] == "correct")
                require Yii::app()->basePath . "\\views\\student\\correct_keyboard.php";

            else if ($classExercise['type'] == "free")
                require Yii::app()->basePath . "\\views\\student\\keyboard.php";
            else
                require Yii::app()->basePath . "\\views\\student\\speed_keyboard.php";
            ?>
        </div>
        <?php
        $host = Yii::app()->request->hostInfo;
        $path = Yii::app()->request->baseUrl;
        $page = '/index.php?r=student/saveAnswer';
        if (isset($_GET['page']))
            $index = $_GET['page'];
        else
            $index = 1;
        $param = '&page=' . $index;
        if (isset(Yii::app()->session['type']))
            $param = $param . '&&type=' . Yii::app()->session['type'];
        ?>
    </div>
    <form name='nm_answer_form' id='id_answer_form' method="post" action="<?php echo $host . $path . $page . $param; ?>">
        <input id="id_content" type="hidden" value="<?php echo $classExercise['content']; ?>">
        <input id="id_speed" type="hidden" value="<?php echo $classExercise['speed']; ?>">
        <input  name="nm_answer"id="id_answer" type="hidden">
        <input  name="nm_cost" id="id_cost" type="hidden">
    </form>
</body>
<script>
    $(document).ready(function () {
        var content = document.getElementById("id_content").value;
        var cont_array = content.split("$$");
        var repeatNum = $("#repeatNum").html();
        $("#AllOfWord").html(cont_array.length*repeatNum);
        document.getElementById('span').scrollIntoView();
//  暂停功能        
//        $("#pause").click(function () {
//            if (window.G_startFlag === 1) {
//                if (window.G_isPause === 0) {
//                    window.G_isPause = 1;
//                }
//                if (window.G_pauseFlag === 1&&window.G_isOverFlag ===0) {
//                    $("#pause").html("暂停统计");
//
//                } else {
//                    $("#pause").html("继续统计");
//                }
//            }
//        });
    });
    var originalContent = "<?php echo $classExercise['content']; ?>";
    window.GA_originalContent = originalContent;
    //获取学生信息转入统计JS 实时存入数据库
    window.G_saveToDatabase = 1;
<?php
$exerciseID = $classExercise['exerciseID'];
$studentID = Yii::app()->session['userid_now'];
$sqlClassExerciseRecord = ClassexerciseRecord::model()->findAll("classExerciseID = '$exerciseID' AND studentID = '$studentID'");
$countSquence = count($sqlClassExerciseRecord);
$squence = $countSquence + 1;
?>
    window.G_squence = <?php echo $squence; ?>;
    window.G_exerciseType = "classExercise";
    var classExerciseID = <?php echo $exerciseID; ?>;
    var studentID = "<?php echo Yii::app()->session['userid_now']; ?>";
    window.G_exerciseData = Array(classExerciseID, studentID);
    $("#close_exercise").click(function () {
        window.parent.closeClassExercise();
    });

    $(document).ready(function () {
        window.parent.alertStartKeyExercise();
    });

    function start() {
        $("#word").html(3);
        var count = 0;
        var ready = setInterval(function () {
            $("#word").html(2 - count);
            count++;
            if (count === 3) {
                startParse();
                var myDate = new Date();
                window.G_pressTime = myDate.getTime();
                if (window.G_startFlag === 0) {
                    window.G_startTime = myDate.getTime();
                    window.G_startFlag = 1;
                    window.G_oldStartTime = window.G_pressTime;
                }
                clearInterval(ready);
            }
        }, 1000);
    }

    $(document).ready(function () {
        $("li#li-key-<?php echo $classExercise['exerciseID']; ?>").attr('class', 'active');
    });

    function getWordLength() {
        var input = document.getElementById("id_answer");
        var answer = input.value;
        var reg = new RegExp(":", "g");
        var res = answer.match(reg);
        var length = res === null ? 0 : res.length;
        return length;
    }

    function finish() {
        if (window.G_startFlag === 1) {
            window.G_isOverFlag = 1;
            $("#finish").attr("disabled", "disabled");
            window.parent.finish();
        }
    }



    /*
     function getCorrect(pattern , answer){
     var ap = pattern.split(' ');
     var aa = answer.split(' ');
     var tl = ap.length;
     var al = aa.length;
     var i = 0 , j = 0;
     var cnum = 0;
     while(i < tl && j < al){
     if(ap[i] == aa[j]){
     cnum++;
     i++;
     j++;
     } else{
     i++;
     }
     }
     return cnum / tl;
     }
     */


</script>
