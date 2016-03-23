<!--声音播放-->
<script src="<?php echo JS_URL; ?>audioplayer.js"></script>
<!--打字控件-->
<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<!--打字计时-->
<script src="<?php echo JS_URL; ?>exerJS/timep.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>

<?php
//2015-8-3 宋杰 判断加载suitesidebar还是examsiderbar
if ($isExam == false) {
    require 'suiteSideBar.php';
} else {
    require 'examSideBar.php';
}
//add by lc 
$type = 'listen';
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
if (!$isOver) {
    $exerciseID = $exerOne['exerciseID'];
    $studentID = Yii::app()->session['userid_now'];
    $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'listen' AND createPerson LIKE '$studentID'");
    $countSquence = count($sqlClassExerciseRecord);
    $squence = $countSquence + 1;
    if ($sqlClassExerciseRecord != null) {
        ?>
        <div class="span9" style="height: 800px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
            <h3>点击此处&nbsp;<a id="repeat" style="cursor: pointer">重做</a></h3>
            <div id="Analysis" hidden="hidden"></div>
            <input id="content" type="hidden" style="height: 5px;" value="<?php
            $str = str_replace("\n", "`", $exerOne['content']);
            $str = str_replace("\r", "", $str);
            $str = str_replace(" ", "}", $str);
            echo $str;
            ?>">
            <div id ="templet" hidden="hidden"></div>
        </div>
    <?php } else { ?>
        <div class="span9"  style="height: 800px" >
            <?php if ($isExam) { ?>
            <?php } else { ?>
                <div id="span" class="hero-unit" align="center">
<!--                    <div style="width: 660px">
                        <button id="finish" onclick="finish()" class="fl btn btn-primary" >完成</button>
                    </div>-->
                    <div id="Analysis">
                        <h3 ><?php echo $exerOne['title'] ?></h3>
                        <table style="width: 660px"  border = '0px'> 
                            <tr>
                                <td><span class="fl"  style="color: #000;font-weight: bolder">练习计时：</span></td>
                                <td><span style="color: #f46500" id="timej">00:00:00</span></td>
                                <td></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;正确率：&nbsp;&nbsp;</span></td>
                                <td style="width: 60px;"><span style="color: #f46500" id="wordisRightRadio">0</span></td>
                                <td><span class="fr" style="color: gray"> %&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                <td><span class="fl" style="color: #000;font-weight: bolder">&nbsp;&nbsp;回改字数：</span></td>
                                <td style="width: 60px;"><span style="color: #f46500" id="getBackDelete">0</span></td>
                                <td><span class="fr" style="color: gray"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                            </tr>
                            <tr>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">平均速度：</span></td>
                                <td><span style="color: #f46500" id="getAverageSpeed">0</span></td>
                                <td><span class="fr" style="color: gray"> 字/分</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;瞬时速度：</span></td>
                                <td><span style="color: #f46500" id="getMomentSpeed">0</span ></td>
                                <td><span class="fr" style="color:gray"> 字/分</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高速度：</span></td>
                                <td><span style="color: #f46500" id="getHighstSpeed">0</span ></td>
                                <td><span class="fr" style="color:gray"> 字/分</span></td>
                            </tr>
                            <tr>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">平均击键：</span></td>
                                <td><span style="color: #f46500" id="getAverageKeyType">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次/分</span></td>
                                <td><span class="fl"  style="color: #000;font-weight: bolder">&nbsp;&nbsp;瞬时击键：</span></td>
                                <td><span style="color:#f46500" id="getMomentKeyType">0</span ></td>
                                <td><span class="fr" style="color:gray"> 次/秒</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高击键：</span></td>
                                <td><span style="color: #f46500" id="getHighstCountKey">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次/秒</span></td>
                            </tr>
                            <tr>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">击键间隔：</span></td>
                                <td><span style="color: #f46500" id="getIntervalTime">0</span ></td>
                                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高间隔：</span></td>
                                <td><span style="color: #f46500" id="getHighIntervarlTime">0</span ></td>
                                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;总击键数：</span></td>
                                <td><span style="color: #f46500" id="getcountAllKey">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                            </tr>

                        </table>
                    </div>
                </div>
            <?php } ?>
            <div class="hero-unit fl"  align="center">
                <table  border = '0px'>
                    <?php if ($isExam) { ?>
                        <tr><h3><?php echo $exerOne['title'] ?></h3></tr>
                        <tr>
                            <td width = '250px'>分数：<?php echo $exerOne['score'] ?></td>
                            <td width = '250px'>剩余时间：<span id="time"><?php echo $strTime ?></span><input id="timej" type="hidden"/></td>
                            <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                        <?php } else { ?>
            <!--                    <td width = '250px'>计时：<span id="timej">00:00:00</span></td>-->
                        <?php } ?>
                    </tr>
                </table>
                <?php
                $listenpath = EXER_LISTEN_URL . $exerOne['filePath'] . $exerOne['fileName'];
                Yii::app()->session['exerID'] = $exerOne['exerciseID'];
                ?>
                <div align="left">
                    <br/>
                    <div  id="audio_hiden"  style='display:none ;position:absolute; z-index:3; width:50px; height:28px; left:50px; <?php if ($isExam) {
            echo 'top:150px;';
        } else {
            echo 'top:260px;';
        } ?>'></div>
                    <div style='position:absolute; z-index:3; width:180px; height:28px; left:74px; <?php if ($isExam) {
            echo 'top:150px;';
        } else {
            echo 'top:260px;';
        } ?>'></div>
                    <audio style='position:absolute; z-index:2; width:300px; height:28px; left:50px;  <?php if ($isExam) {
                   echo 'top:150px;';
               } else {
                   echo 'top:260px;';
               } ?>  '  src = "<?php echo $listenpath; ?>"   preload = "auto"  onplay="start()"  controls=""></audio>
                </div>
                <input id="content" type="hidden" value="<?php
               $str = str_replace("\n", "`", $exerOne['content']);
               $str = str_replace("\r", "", $str);
               $str = str_replace(" ", "}", $str);
               echo $str;
               ?>">
                <br/>
                <object id="typeOCX" type="application/x-itst-activex" 
                        clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                        width ='750' height='430' 
                        event_OnStenoPress="onStenoPressKey"
                        >
                </object>
                <br/>
            </div>
        <?php require Yii::app()->basePath . "\\views\\student\\submitAnswer.php"; ?>
        </div>

    <?php }
} else {
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
    var yaweiOCX = {};
    var isExam = <?php
if ($isExam) {
    Yii::app()->session['isExam'] = 'isExam';
    echo 1;
} else {
    Yii::app()->session['isExam'] = '';
    echo 0;
}
?>;
    $(document).ready(function () {
        yaweiOCX = document.getElementById("typeOCX");
        window.G_isLook = 1;
        setInterval(function () {    //setInterval才是轮询，setTimeout是一定秒数后，执行一次的！！
            window.G_squence = 0;
        }, 3000);
<?php if (!$isOver) { ?>
            var option = {
                title: "提示",
                btn: parseInt("0011", 4),
            };
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
                        alert("本题时间已到，不可答题！");
                        clearInterval(isover);
                        doSubmit(true, function () {
                            window.location.href = "index.php?r=student/examlistenType&&exerID=<?php echo $exerID; ?>&&cent=<?php
    $arg = implode(',', $cent);
    echo $arg;
    ?>";
                        });
                    }
                }, 1000);
<?php } ?>
        }
        var originalContent = "<?php echo $str; ?>";
        window.GA_originalContent = originalContent.replace(/}/g, "").replace(/`/g, "");
    });
    function onStenoPressKey(pszStenoString, device) {
        window.GA_answer = yaweiOCX.GetContentWithSteno();
        //使用统计JS必须在绑定的此onStenoPressKey事件中写入如下代码
//        if(window.G_pauseFlag===1){
//             window.G_keyBoardBreakPause = 0;
//              $("#pause").html("暂停统计");
//        }
        var audio = document.getElementById('music');
        if (window.G_pauseFlag === 1) {
            audio.play();
        }
        var myDate = new Date();
        window.G_pressTime = myDate.getTime();
        if (window.G_startFlag === 0) {
            window.G_startTime = myDate.getTime();
            window.G_startFlag = 1;
            window.G_oldStartTime = window.G_pressTime;
        }
        window.G_countMomentKey++;
        window.G_countAllKey++;
        window.G_content = yaweiOCX.GetContent().replace(/\r\n/g, "").replace(/ /g, "");
        window.G_keyContent = window.G_keyContent + "&" + pszStenoString;

        //每击统计击键间隔时间 秒
        //@param id=getIntervalTime 请将最高平均速度统计的控件id设置为getIntervalTime 
        //每击统计最高击键间隔时间 秒
        //@param id=getHighIntervarlTime 请将最高平均速度统计的控件id设置为getHighIntervarlTime 
        if (window.G_endAnalysis === 0) {
            var pressTime = window.G_pressTime;
            if (pressTime - window.G_oldStartTime > 0) {
                var IntervalTime = parseInt((pressTime - window.G_oldStartTime) / 10) / 100;
                $("#getIntervalTime").html(IntervalTime);
                window.GA_IntervalTime = IntervalTime;
                window.G_oldStartTime = pressTime;
            }
            if (IntervalTime - window.G_highIntervarlTime > 0) {
                window.G_highIntervarlTime = IntervalTime;
                window.GA_IntervalTime = window.G_highIntervarlTime;
                $("#getHighIntervarlTime").html(IntervalTime);
            }
        }
        //--------------------------------------------------
    }

    $(document).ready(function () {

        //菜单栏变色
        $("li#li-listen-<?php echo $exerOne['exerciseID']; ?>").attr('class', 'active');
    });

    function getWordLength() {
        var input = getContent(document.getElementById("typeOCX"));
        return input.length;
    }

    function start() {
        document.getElementById('audio_hiden').style.display = "block";
    }
    window.G_saveToDatabase = 1;
    window.G_exerciseType = "answerRecord";
    window.G_squence = <?php echo $squence; ?>;
    var recordID = <?php echo $recordID ?>;
    var classExerciseID = <?php echo $exerciseID; ?>;
    var category = 'listen';
    var type = "listen";
    window.G_exerciseData = Array(classExerciseID, type, recordID, category);
    function finish() {
        window.G_isOverFlag = 1;
        briefCode = null;
        briefOriginalYaweiCode = null;
        yaweiOCX.remove();
        var suiteID = <?php echo Yii::app()->session['suiteID']; ?>;
        window.location.href = "./index.php?r=student/clswkOne&&suiteID=" + suiteID;
    }

    function submitSuite(simple) {
        var option = {
            title: "提交试卷",
            btn: parseInt("0011", 4),
            onOk: function () {
                doSubmit(true);
                $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (isExam)
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

    if (document.getElementById('repeat') !== null) {
        document.getElementById('repeat').onclick = function () {
            window.location.href = "./index.php?r=student/listenType&&repeat=repeat&&exerID=<?php echo $_GET['exerID']; ?>&&cent=<?php echo $_GET['cent']; ?>";
        };
    }
</script>