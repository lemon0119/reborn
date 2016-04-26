<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script> <script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<?php
if ($isExam == false) {
    Yii::app()->session['isExam'] = 0;
    require 'suiteSideBar.php';
    ?>
    <?php
} else {
    Yii::app()->session['isExam'] = 1;
    require 'examSideBar.php';
    ?>
    <?php
}
//add by lc 
$type = 'key';
if ($isExam) {
    $seconds = $exerOne['time'];
    $hh = floor(($seconds * 60) / 3600);
    $mm = floor(($seconds * 60) % 3600 / 60);
    $ss = floor(($seconds * 60) % 60);
    $strTime = "";
    $strTime .= $hh < 10 ? "0" . $hh : $hh;
    $strTime .= ":";
    $strTime .= $mm < 10 ? "0" . $mm : $mm;
    $strTime .= ":";
    $strTime .= $ss < 10 ? "0" . $ss : $ss;
}//end
?>
<?php
$sqlClassExerciseRecord = null;
$squence = 0;
$exerciseID = 0;
$category = $exerOne['category'];
if (!$isOver) {
    $exerciseID = $exerOne['exerciseID'];
    $studentID = Yii::app()->session['userid_now'];
    $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'key' AND createPerson LIKE '$studentID'");
    $countSquence = count($sqlClassExerciseRecord);
    $squence = $countSquence + 1;
    if ($sqlClassExerciseRecord != null) {
        ?>
        <div id="span" class="span9" style="height: 800px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
            <?php if (!$isExam) { ?><h3>点击此处&nbsp;<a id="repeat" style="cursor: pointer">重做</a></h3><?php } ?>
            <div id="Analysis" hidden="hidden"></div>
            <input id="content" hidden="hidden"/>  
            <div id ="templet" hidden="hidden"> <font id="id_right"style="color:#808080"></font><font id="id_wrong" style="color:#ff0000"></font><font id="id_new" style="color:#000000"> </font></div>
            <form name='nm_answer_form' hidden="hidden" id='id_answer_form' method="post" action="<?php //echo $host . $path . $page . $param;   ?>">
                <input id="id_content" type="hidden" value="<?php echo $exerOne['content']; ?>">
                <input id="id_speed" type="hidden" value="<?php echo $exerOne['speed']; ?>">
                <input  name="nm_answer"id="id_answer" type="hidden">
                <input  name="nm_cost" id="id_cost" type="hidden">
            </form>
        </div>
    <?php } else { ?>
        <div class="span9"  style="height: 700px">

            <!--    <div class="hero-unit fl"  align="center">
            <?php Yii::app()->session['exerID'] = $exerOne['exerciseID']; ?>  
                    
                    <table border = '0px'>
                            <tr><h3><?php echo $exerOne['title'] ?></h3>                
                            <tr>
                                
                            <span id="repeatNum" style="display: none"><?php echo $exerOne['repeatNum'] ?></span>
            <?php if ($isExam) { ?>
                -->                                        <td width = '250px'>分数：<?php echo $exerOne['score'] ?></td>
            <!--                <td width = '250px'>剩余时间：<span id="time"><?php //echo $strTime  ?></span><input id="time" type="hidden"/></td>
                <span id="wordisRightRadio" style="display: none;">0</span>
                <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
            <?php } else { ?>
            <td width = '250px'>计时：<span id="timej">00:00:00</span></td>                  
            <td width = '250px'>准确率：<span id="wordisRightRadio">0</span>%</td>       
            <td width = '250px'>循环次数：<span id="repeatNum"><?php //echo $exerOne['repeatNum']  ?></span></td>
            <?php } ?>
        </tr>
        </table>
        <br/>
        <table id="keyMode" style="height: 60px; font-size: 50px; border: 1px solid #000">
        <tr>
        <td id="word" style="border-right: 1px solid #000; width: 400px;text-align:right;"></td>              
        </tr>
        </table>
        <br/>
        <div id ="templet" class ="questionBlock" onselectstart="return false" style="display: none">
        <font id="id_right"style="color:#808080"> </font><font id="id_wrong" style="color:#ff0000"> </font><font id="id_new" style="color:#000000"> </font>
        </div>
        <div style="width: 750px; height: 350px;">
            <?php
//        if ($exerOne['category'] == "correct")
//            require Yii::app()->basePath . "\\views\\student\\correct_keyboard.php";
//
//        else if ($exerOne['category'] == "free")
//            require Yii::app()->basePath . "\\views\\student\\keyboard.php";
//        else
//            require Yii::app()->basePath . "\\views\\student\\speed_keyboard.php";
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
        <input id="id_content" type="hidden" value="<?php echo $exerOne['content']; ?>">
        <input id="id_speed" type="hidden" value="<?php echo $exerOne['speed']; ?>">
        <input  name="nm_answer"id="id_answer" type="hidden">
        <input  name="nm_cost" id="id_cost" type="hidden">
        </form>-->
            <div id="span" class="hero-unit" align="center">
                <table style="width: 580px"  border = '0px'>
                    <!--                    <button id="finish" onclick="finish()" style="margin-left:30px;" class="fl btn btn-primary" >完成</button>-->
                    <tr><h3 ><?php echo $exerOne['title'] ?></h3></tr>
                    <tr>
                        <?php //if($isExam){ ?>
        <!--                             <td><span class="fl"  style="color: #000;font-weight: bolder">剩余时间：</span></td>
                        <td><span style="color: #f46500" id="time"><?php //echo $strTime  ?></span></td>-->
                        <?php //}else{ ?>
                        <td><span class="fl"  style="color: #000;font-weight: bolder">练习计时：</span></td>
                        <td><span style="color: #f46500" id="timej">00:00:00</span></td>
                        <?php //} ?>
                        <td></td>
                        <td><span class="fl"   style="color: #000;font-weight: bolder">正确率：&nbsp;&nbsp;</span></td>
                        <td style="width: 60px;"><span style="color: #f46500" id="wordisRightRadio">0</span></td>
                        <td><span class="fr" style="color: gray"> %&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                        <td><span class="fl"   style="color: #000;font-weight: bolder">完成进度：</span></td>
                        <td colspan="2"><span style="color:green" id="isDone">0</span>/<span  style="color: #f46500" id="AllOfWord"></span><span hidden="true" style="color: #f46500" id="repeatNum"><?php echo $exerOne['repeatNum'] ?></span></td>
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
                    if ($exerOne['category'] == "correct")
                        require Yii::app()->basePath . "\\views\\student\\correct_keyboard.php";

                    else if ($exerOne['category'] == "free")
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
                <input id="id_content" type="hidden" value="<?php echo $exerOne['content']; ?>">
                <input id="id_speed" type="hidden" value="<?php echo $exerOne['speed']; ?>">
                <input  name="nm_answer"id="id_answer" type="hidden">
                <input  name="nm_cost" id="id_cost" type="hidden">
            </form>
            <?php require Yii::app()->basePath . "\\views\\student\\submitAnswer.php"; ?>
        </div>
        <?php
    }
}else {
    ?>
    <div id="span" class="span9" style="height: 800px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
        <div id="Analysis" hidden="hidden"></div>
        <input id="content" hidden="hidden"/>  
        <div id ="templet" hidden="hidden"> <font id="id_right"style="color:#808080"></font><font id="id_wrong" style="color:#ff0000"></font><font id="id_new" style="color:#000000"> </font></div>
        <form name='nm_answer_form' hidden="hidden" id='id_answer_form' method="post" action="<?php //echo $host . $path . $page . $param;   ?>">
            <input id="id_content" type="hidden" value="">
            <input id="id_speed" type="hidden" value="">
            <input  name="nm_answer"id="id_answer" type="hidden">
            <input  name="nm_cost" id="id_cost" type="hidden">
        </form>
    </div>
<?php } ?>
<script>

    $(document).ready(function () {
<?php if (!$isOver) { ?>
<?php } ?>
        if (<?php
if ($isExam) {
    echo $exerOne['time'];
} else {
    echo 0;
}
?> != 0) {
<?php if ($isExam) { ?>
                reloadTime2(<?php echo $exerOne['time']; ?>, isExam);
                var isover = setInterval(function () {
                    var time = getSeconds();
                    var seconds =<?php
    if ($isExam)
        echo $exerOne['time'];
    else
        echo '0';
    ?>;
                    if (time == 0) {
                        window.wxc.xcConfirm("本题时间已到，不可答题！", window.wxc.xcConfirm.typeEnum.error);
                        clearInterval(isover);
                        $.post('index.php?r=student/overSuite&&isExam=<?php if ($isExam) {
        echo 'true';
    } else {
        echo 'false';
    } ?>', function () {
                            if (<?php if ($isExam) {
        echo 'true';
    } else {
        echo 'false';
    } ?>) {
                                window.location.href = "index.php?r=student/classExam";
                            }
                            else
    <?php if (isset($_GET['lessonID'])) { ?>
                                window.location.href = "index.php?r=student/myCourse&&lessonID=<?php echo $_GET['lessonID']; ?>";
    <?php } else { ?>
                                window.location.href = "index.php?r=student/myCourse";
    <?php } ?>
                        });

                    }

                }, 1000);
<?php } ?>
        }

        $("li#li-key-<?php echo $exerOne['exerciseID']; ?>").attr('class', 'active');
<?php if ($sqlClassExerciseRecord == null && !$isOver) { ?>
            window.wxc.xcConfirm("即将开始！您将有3秒准备时间！", window.wxc.xcConfirm.typeEnum.warning, {
                onOk: function () {
                    start();
                }
            });
<?php } ?>
    });


    function getWordLength() {
        var input = document.getElementById("id_answer");
        var answer = input.value;
        var reg = new RegExp(":", "g");
        var res = answer.match(reg);
        var length = res === null ? 0 : res.length;
        return length;
    }



    function submitSuite(simple) {
        var option = {
            title: "提交试卷",
            btn: parseInt("0011", 4),
            onOk: function () {
                //doSubmit(true);
                $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (<?php if ($isExam) {
    echo 'true';
} else {
    echo 'false';
} ?>)
                        window.location.href = "index.php?r=student/classExam";
                    else
                        window.location.href = "index.php?r=student/classwork";
                });
            }
        };
        window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);

    }
    function doSubmit(simple, doFunction) {
        $.post($('#id_answer_form').attr('action'), $('#id_answer_form').serialize(), function (result) {
            if (!simple) {
            } else {
                doFunction();
            }
        });
    }
    document.getElementById("id_new").firstChild.nodeValue = document.getElementById("id_content").value;

    $(document).ready(function () {
        var content = document.getElementById("id_content").value;
        var cont_array = content.split("$$");
        var repeatNum = $("#repeatNum").html();
        $("#AllOfWord").html(cont_array.length * repeatNum);
        //document.getElementById('span').scrollIntoView();
    });
    window.G_saveToDatabase = 1;
    window.G_exerciseType = "answerRecord";
    window.G_squence = <?php echo $squence; ?>;
    var recordID = <?php echo $recordID ?>;
    var classExerciseID = <?php echo $exerciseID; ?>;
    var category = '<?php echo $category; ?>';
    var type = "key";
    window.G_exerciseData = Array(classExerciseID, type, recordID, category);


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
        }
        var suiteID = <?php echo Yii::app()->session['suiteID']; ?>;
        window.location.href = "./index.php?r=student/clswkOne&&suiteID=" + suiteID;
    }

    if (document.getElementById('repeat') !== null) {
        document.getElementById('repeat').onclick = function () {
            window.location.href = "./index.php?r=student/keyType&&repeat=repeat&&exerID=<?php echo $_GET['exerID']; ?>&&cent=<?php echo $_GET['cent']; ?>";
        };
    }
</script>