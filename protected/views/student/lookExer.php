<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/timep.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>
<?php
if ($isExam == false) {
    require 'suiteSideBar.php';
} else {
    require 'examSideBar.php';
}
//add by lc 
$type = 'look';
$str = '';
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
$squence=0;
$exerciseID=0;
if (!$isOver) {
    $exerciseID = $exerOne['exerciseID'];
    $studentID = Yii::app()->session['userid_now'];
    $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'look' AND createPerson LIKE '$studentID'");
    $countSquence = count($sqlClassExerciseRecord);
    $squence = $countSquence + 1;
    if ($sqlClassExerciseRecord != null) {
        ?>
        <div class="span9" style="height: 800px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
            <?php if (!$isExam) { ?><h3>点击此处&nbsp;<a id="repeat" style="cursor: pointer">重做</a></h3><?php }?>
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
        <div class="span9" style="height: 800px">
            <?php if ($isExam) { ?>
            <?php } else { ?>
                <div  id="span" class="hero-unit" align="center">
                    <div style="width: 660px">
<!--                        <button id="finish" onclick="finish()" class="fl btn btn-primary" >完成</button>-->
                        <button id="toggle" style="margin-left:30px;" class="btn fr">展开</button>
                    </div>
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
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;平均击键：</span></td>
                                <td><span style="color: #f46500" id="getAverageKeyType">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次/分</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;总击键数：</span></td>
                                <td><span style="color: #f46500" id="getcountAllKey">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                            </tr>
                        </table>
                    </div>
                    <div id="allAnalysis" style="display:none">
                        <table style="width: 660px"  border = '0px'> 
                            <tr>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">瞬时速度：</span></td>
                                <td style="width: 80px;"><span style="color: #f46500" id="getMomentSpeed">0</span ></td>
                                <td><span class="fr" style="color:gray"> 字/分</span></td>
                                <td><span class="fl"  style="color: #000;font-weight: bolder">&nbsp;&nbsp;瞬时击键：</span></td>
                                <td style="width: 60px;"><span style="color:#f46500" id="getMomentKeyType">0</span ></td>
                                <td><span class="fr" style="color:gray"> 次/秒</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高击键：</span></td>
                                <td style="width: 60px;"><span style="color: #f46500" id="getHighstCountKey">0</span ></td>
                                <td><span class="fr" style="color: gray"> 次/秒</span></td>
                            </tr>
                            <tr>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">击键间隔：</span></td>
                                <td><span style="color: #f46500" id="getIntervalTime">0</span ></td>
                                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高间隔：</span></td>
                                <td><span style="color: #f46500" id="getHighIntervarlTime">0</span ></td>
                                <td><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                <td><span class="fl"   style="color: #000;font-weight: bolder">&nbsp;&nbsp;最高速度：</span></td>
                                <td><span style="color: #f46500" id="getHighstSpeed">0</span ></td>
                                <td><span class="fr" style="color:gray"> 字/分</span></td>
                            </tr>

                        </table>
                    </div>
                <?php } ?>
                <div class="hero-unit" align="center">
                    <?php
                    Yii::app()->session['exerID'] = $exerOne['exerciseID'];
                    ?>
                    <table border = '0px'>
                        <?php if ($isExam) { ?>
                            <tr><h3><?php echo $exerOne['title'] ?></h3></tr>
                            <tr>
                                <td width = '250px'>分数：<?php echo $exerOne['score'] ?></td>
                                <td width = '250px'>剩余时间：<span id="time"><?php echo $strTime ?></span><input id="timej" type="hidden"/></td>
                                <td width = '250px'>字数：<span id="wordCount">0</span></td>
                            <?php } else { ?>
                            <?php } ?>
                        </tr>
                    </table>
                    <br/>
                    <input id="content" type="hidden" style="height: 5px;" value="<?php
                    $str = str_replace("\n", "`", $exerOne['content']);
                    $str = str_replace("\r", "", $str);
                    $str = str_replace(" ", "}", $str);
                    echo $str;
                    ?>">
                    <div id ="templet" style="text-align: left;height: 260px;width: 660px" class="questionBlock" front-size ="25px" onselectstart="return false">
                    </div>
                    <br/>
                    <object id="typeOCX" type="application/x-itst-activex" 
                            clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                            width ='700' height='280' 
                            event_OnChange="onChange"
                            event_OnStenoPress="onStenoPressKey">
                    </object>
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
            <form name='nm_answer_form' hidden="hidden" id='id_answer_form' method="post" action="<?php //echo $host . $path . $page . $param;  ?>">
                <input id="id_content" type="hidden" value="">
                <input id="id_speed" type="hidden" value="">
                <input  name="nm_answer"id="id_answer" type="hidden">
                <input  name="nm_cost" id="id_cost" type="hidden">
            </form>
        </div>
<?php } ?>
</div>
<script>
    var yaweiOCX = null;
    var briefCode = "";
    var briefOriginalYaweiCode = "";

    $(document).ready(function () {
        window.G_isLook = 1;
        var isExam = <?php
if ($isExam) {
    Yii::app()->session['isExam'] = 'isExam';
    echo 1;
} else {
    Yii::app()->session['isExam'] = '';
    echo 0;
}
?>;
        var v =<?php echo Tool::clength($exerOne['content']); ?>;
        $("#wordCount").text(v);
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
                        var option = {
                            title: "提示",
                            btn: parseInt("0011", 4),
                        };
                        window.wxc.xcConfirm("本题时间已到，不可答题！", "custom", option);
                        clearInterval(isover);
                        doSubmit(true, function () {
                            window.location.href = "index.php?r=student/clsexamOne&&suiteID=<?php echo Yii::app()->session['examsuiteID']; ?>&&workID=<?php echo Yii::app()->session['examworkID'] ?>";
                        });
                    }
                }, 1000);
<?php } ?>
        }

    });

    $("#toggle").click(function () {
        var flag = $("#toggle").text();
        if (flag == '展开') {
            $("#toggle").text("收起");
            $("#templet").css('height', '180px');
        } else {
            $("#toggle").text("展开");
            $("#templet").css('height', '260px');

        }
        $("#allAnalysis").toggle(0);
    });
    var yaweiOCX = null;

    $(document).ready(function () {
        setInterval(function () {
            window.G_squence = 0;
        }, 3000);


        $.ajax({
            type: "POST",
            url: "index.php?r=api/getBrief",
            async: false,
            data: {},
            success: function (data) {
                briefCode = (data.split("$")[0]).split("&");
                briefOriginalYaweiCode = (data.split("$")[1]).split("&");
            },
            error: function (xhr, type, exception) {
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });
        yaweiOCX = document.getElementById("typeOCX");
        var originalContent = "<?php echo $str; ?>";
        window.GA_originalContent = originalContent.replace(/}/g, "").replace(/`/g, "");
        //获取学生信息转入统计JS 实时存入数据库
        window.G_saveToDatabase = 1;
        //菜单栏变色
        $("li#li-look-<?php echo $exerOne['exerciseID']; ?>").attr('class', 'active');
        //显示题目
        var text = document.getElementById("content").value;
        if (text.indexOf("\n") > 0) {
            var arraytext = text.split("\n");
            for (var i = 0; i < arraytext.length; i++) {
                var p = document.createElement("p");
                var father = document.getElementById("templet");
                createFontWithP("#000000", arraytext[i], p, father);
            }
        } else {
            createFont("#000000", document.getElementById("content").value);
        }

    });

    function getWordLength() {
        var input = getContent(yaweiOCX);
        return input.length;
    }

    function checkYaweiCode(content) {
        for (var i = 0; i < briefCode.length; i++) {
            if (content.content.indexOf(briefCode[i]) >= 0) {
                var re = new RegExp(briefCode[i], "g");
                if (briefCode[i].length < 3) {
                    content.content = content.content.replace(re, "<span style='border-bottom:1px solid green'>" + briefCode[i] + "</span>");
                } else if (4 > briefCode[i].length > 2) {
                    content.content = content.content.replace(re, "<span style='border-bottom:2px solid green'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length > 3) {
                    content.content = content.content.replace(re, "<span style='border-bottom:3px solid green'>" + briefCode[i] + "</span>");
                }

            }
        }
    }

    function onStenoPressKey(pszStenoString, device) {
        window.GA_answer = yaweiOCX.GetContentWithSteno();
        //使用统计JS必须在绑定的此onStenoPressKey事件中写入如下代码
//        if(window.G_pauseFlag===1){
//             window.G_keyBoardBreakPause = 0;
//              $("#pause").html("暂停统计");
//        }
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

    function createFont(color, text, code) {
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        var content = {content: ""};
        var isBrief = 0;
        if (color == "#808080") {
            for (var i = 0; i < text.length; i++) {
                if (text[i].length < 3) {
                    for (var j = 0; j < briefOriginalYaweiCode.length; j++) {
                        if (text[i] == briefCode[j]) {
                            isBrief++;
                            if (code[i] == briefOriginalYaweiCode[j].replace(":0", "") && (code[i] != "W:X")) {
                                isBrief--;
                            }
                        }
                    }
                } else {
                    isBrief++;
                }
                if (isBrief === 0) {
                    content.content += text[i];
                } else {
                    content.content += "<span style='color:green'>" + text[i] + "</span>";
                    isBrief--;
                }
            }
            f.style = "color:" + color;
            content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
            checkYaweiCode(content);
            f.innerHTML = content.content;
            father.appendChild(f);
        } else {
            for (var i = 0; i < text.length; i++) {
                content.content += text[i];
            }
            f.style = "color:" + color;
            //var t = document.createTextNode(text);
            //f.appendChild(t);
            var tempContent = "";
            if (color === "#ff0000") {
                content.content = content.content.replace(/`/g, "↓<br/>").replace(/}/g, "█");
                checkYaweiCode(content);
            } else {
                content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
                checkYaweiCode(content);
            }
            f.innerHTML = content.content;
            father.appendChild(f);
        }
    }

    function controlScroll() {
        var input = getContent(yaweiOCX);
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 23);
        if (line > 3) {
            div.scrollTop = (line - 3) * 30;
        }
    }
    function onChange() {
        yaweiOCX.UpdateView();
        var input = getContent(yaweiOCX);
        yaweiOCX.Locate(input.length);
        controlScroll();
        changWordPS();
        var text_old = "<?php echo $str; ?>";
//        if (text_old.indexOf("<br/>") > 0) {
//            var div = document.getElementById("templet");
//            while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
//                div.removeChild(div.firstChild);
//            }
//            var input_old = getContent(yaweiOCX);
//            var arrayinput = input_old.split("\n");
//            var father = document.getElementById("templet");
//            var arraytext = text_old.split("<br/>");
//            for (var s = 0; s < arraytext.length; s++) {
//                var p = document.createElement("p");
//                if (arrayinput[s]) {
//                    var input_p = arrayinput[s].split("");
//                    var text_p = arraytext[s].split("");
//                    var old = "";
//                    var isWrong = false;
//                    var wrong = "";
//                    for (var i = 0; i < input_p.length && i < text_p.length; i++) {
//                        if (input_p[i] == text_p[i]) {
//                            if (isWrong == true) {
//                                isWrong = false;
//                                createFontWithP("#ff0000", wrong, p, father);
//                                wrong = "";
//                                old = text_p[i];
//                            } else {
//                                old += text_p[i];
//                            }
//                        }
//                        else {
//                            if (isWrong == true)
//                                wrong += text_p[i];
//                            else {
//                                isWrong = true;
//                                createFontWithP("#808080", old, p, father);
//                                old = "";
//                                wrong = text_p[i];
//                            }
//                        }
//                    }
//                    createFontWithP("#808080", old, p, father);
//                    createFontWithP("#ff0000", wrong, p, father);
//                    if (input_p.length < text_p.length) {
//                        var left_p = arraytext[s].substr(input_p.length);
//                        createFontWithP("#000000", left_p, p, father);
//                    }
//                } else if (!arrayinput[s]) {
//                    createFontWithP("#000000", arraytext[s], p, father);
//                }
//            }
//        } else {
        var input = getContent(yaweiOCX).replace(/\r\n/g, "`").replace(/ /g, "}").split("");
        var text = text_old.split("");
        var allInput2 = yaweiOCX.GetContentWithSteno().replace(/\r\n/g, "`").replace(/ /g, "}").split(">,");
        var longIsAgo = 0;
        var old = new Array();
        var oldCode = new Array();
        var isWrong = false;
        var wrong = new Array();
        var div = document.getElementById("templet");
        while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
            div.removeChild(div.firstChild);
        }
        var length = allInput2.length;
        var countLength = 0;
        for (var i = 0; i < length; i++) {
            if (allInput2[i] !== undefined) {
                var num = allInput2[i].indexOf(">");
                var content = allInput2[i].substring(1, num);
                var yaweiCode = allInput2[i].substring(num + 2, allInput2[i].length).replace(">", "");
                var long = content.length;
                countLength += content.length;
                if (countLength >= text.length) {
                    length = i;
                }
                longIsAgo += long;
                if (text[longIsAgo - long] != undefined) {
                    var stringText = text[longIsAgo - long];
                }
                for (var j = 1; j < long; j++) {
                    if (text[longIsAgo - long + j] != undefined) {
                        stringText += text[longIsAgo - long + j];
                    }
                }
                if (content == stringText) {
                    if (isWrong == true) {
                        isWrong = false;
                        createFont("#ff0000", wrong, "");
                        wrong = new Array();
                        old = new Array();
                        old.push(stringText);
                        oldCode = new Array();
                        oldCode.push(yaweiCode);
                    } else {
                        old.push(stringText);
                        oldCode.push(yaweiCode);
                    }
                } else {
                    if (isWrong == true)
                        wrong.push(stringText);
                    else {
                        isWrong = true;
                        createFont("#808080", old, oldCode);
                        old = new Array();
                        oldCode = new Array();
                        wrong = new Array();
                        wrong.push(stringText);
                    }
                }
            }
        }

        if (countLength !== 0) {
            createFont("#808080", old, oldCode);
            createFont("#ff0000", wrong, "");
        }
        if (input.length < text.length) {
            var left = document.getElementById("content").value.substr(0 - (text.length - longIsAgo));
            createFont("#000000", left, "");
        }
//            if((text.length - longIsAgo)<1){
//                window.G_isOverFlag = 1;
//            }
        // }
    }
    window.G_exerciseType = "answerRecord";
    window.G_squence = <?php echo $squence; ?>;
    var recordID = <?php echo $recordID ?>;
    var classExerciseID = <?php echo $exerciseID; ?>;
    var category = 'look';
    var type = "look";
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
            window.location.href = "./index.php?r=student/lookType&&repeat=repeat&&exerID=<?php echo $_GET['exerID']; ?>&&cent=<?php echo $_GET['cent']; ?>";
        };
    }

</script>
