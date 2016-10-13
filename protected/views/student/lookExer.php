<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script> <script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>

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
$squence = 0;
$exerciseID = 0;
if (!$isOver) {
    $exerciseID = $exerOne['exerciseID'];
    $studentID = Yii::app()->session['userid_now'];
    $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'look' AND createPerson LIKE '$studentID'");
    $countSquence = count($sqlClassExerciseRecord);
    $squence = $countSquence + 1;
    if ($sqlClassExerciseRecord != null) {
        ?>
        <div class="span9" style="height: 890px;width: 840px;padding: 15px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
            <?php if (!$isExam) { ?><h3>点击此处&nbsp;<a id="repeat" style="cursor: pointer">重做</a></h3><?php } ?>
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
        <div class="span9" style="height: 880px;width: 840px;padding: 15px">
            <?php if ($isExam) { ?>
            <?php } else { ?>
                <div  id="span" class="hero-unit" align="center">
                    <h3 ><?php echo $exerOne['title'] ?></h3>
<!--                    <div style="width: 660px">
                                                <button id="finish" onclick="finish()" class="fl btn btn-primary" >完成</button>
                        <button id="toggle" style="float: right;" class="btn btn-primary">展开</button>
                    </div>-->
<!--                    <div id="Analysis">
                        <h3 ><?php //echo $exerOne['title'] ?></h3>
                        <table style="width: 660px"  border = '0px'> 
                            <tr>
                                <td><span class="fl"  style="color: #000;font-weight: bolder">作答时长：</span></td>
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
                    </div>-->
                <?php } ?>
        <div class="hero-unit" align="center" >
                    <?php
                    Yii::app()->session['exerID'] = $exerOne['exerciseID'];
                    ?>
                    <table border = '0px' >
                        <?php if ($isExam) { ?>
                            <tr><h3><?php echo $exerOne['title'] ?></h3></tr>
                            <tr>
                                <td width = '250px'>分数：<?php echo $exerOne['score'] ?></td>
                                <td width = '250px'>作答时长：<span id="timej">00:00:00</span><input id="timej" type="hidden"/></td>
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
                    <div id ="templet" style="text-align: left;height: 225px;width: 840px" class="questionBlock" front-size ="25px" onselectstart="return false">
                    </div>
                    <br/>
                    <object id="typeOCX" type="application/x-itst-activex" 
                            clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                            width ='840' height='435' 
                            event_OnStenoPress="onStenoPressKey">
                    </object>
                </div>
                <?php require Yii::app()->basePath . "\\views\\student\\submitAnswer.php"; ?>
            </div>



            <?php
        }
    } else {
        ?>
        <div id="span" class="span9" style="height: 800px"><h1><span style="color:#f46500"><?php echo $exerOne['title'] ?>&nbsp;</span>这道题你已经做过了</h1><br/><br/>
            <div id="Analysis" hidden="hidden"></div>
            <input id="content" hidden="hidden"/>  
            <div id ="templet" hidden="hidden"> <font id="id_right"style="color:#727272"></font><font id="id_wrong" style="color:#f44336"></font><font id="id_new" style="color:#000000"> </font></div>
            <form name='nm_answer_form' hidden="hidden" id='id_answer_form' method="post" action="<?php //echo $host . $path . $page . $param;       ?>">
                <input id="id_content" type="hidden" value="">
                <input id="id_speed" type="hidden" value="">
                <input  name="nm_answer"id="id_answer" type="hidden">
                <input  name="nm_cost" id="id_cost" type="hidden">
            </form>
        </div>
    <?php } ?>
</div>
 <?php if (!$isExam) { ?>
<div  class="analysisTool" id="analysis" style="position: relative;left:1190px;bottom: 80px">
    <table style="margin: 0px auto;">
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">作答时长：</span><span style="color: greenyellow" id="timej">00:00:00</span ></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">正&nbsp;确&nbsp;&nbsp率：</span><span style="color: greenyellow" id="wordisRightRadio">0</span ><span class="fr" style="color: #fff"> %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>                       
        <tr>
            <td><span class="fl"  style="color: #fff;font-weight: bolder">平均速度：</span><span style="color: greenyellow" id="getAverageSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span><span class="fr" style="color: #fff"> 字/分</span> </td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时速度：</span><span style="color: greenyellow" id="getMomentSpeed">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高速度：</span><span style="color: greenyellow" id="getHighstSpeed">0</span ><span class="fr" style="color: #fff"> 字/分</span></td></tr>
        <tr>
            <td><span class="fl"  style="color: #fff;font-weight: bolder">平均击键：</span><span style="color: greenyellow" id="getAverageKeyType">0</span ><span class="fr" style="color: #fff"> 次/分</span></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">瞬时击键：</span><span style="color:greenyellow" id="getMomentKeyType">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高击键：</span><span style="color: greenyellow" id="getHighstCountKey">0</span ><span class="fr" style="color: #fff"> 次/秒</span></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">击键间隔：</span><span style="color: greenyellow" id="getIntervalTime">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
        </tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">最高间隔：</span><span style="color: greenyellow" id="getHighIntervarlTime">0</span ><span class="fr" style="color: #fff"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
        </tr><tr><td><span class="fl"  style="color: #fff;font-weight: bolder">总击键数：</span><span style="color: greenyellow" id="getcountAllKey">0</span ><span class="fr" style="color: #fff"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
        <tr><td><span class="fl"  style="color: #fff;font-weight: bolder">回改字数：</span><span style="color: greenyellow" id="getBackDelete">0</span ><span class="fr" style="color: #fff"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
    </table>
</div>
<?php } ?>
<script>
    var yaweiOCX = null;
    var briefCode = "";
    var briefOriginalYaweiCode = "";
    var briefType = "";
    <?php $titleFalse=strpos($exerOne['title'],"-不提示略码"); ?>
    var titleFalse = "<?php echo $titleFalse; ?>";

    $(document).ready(function () {
        window.G_isLook = 1;
        var isExam = <?php
    if ($isExam) {
        Yii::app()->session['isExam'] = 1;
        echo 1;
    } else {
        Yii::app()->session['isExam'] = 0;
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
                            btn: parseInt("0011", 4)
                        };
                        window.wxc.xcConfirm("本题时间已到，不可答题！", "custom", option);
                        clearInterval(isover);
                        $.post('index.php?r=student/overSuite&&isExam=<?php
    if ($isExam) {
        echo 'true';
    } else {
        echo 'false';
    }
    ?>', function () {
                            if (<?php
    if ($isExam) {
        echo 'true';
    } else {
        echo 'false';
    }
    ?>) {
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
                briefType = (data.split("$")[2]).split("&");
            },
            error: function (xhr, type, exception) {
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });
        yaweiOCX = document.getElementById("typeOCX");
        var originalContent = '<?php echo $str; ?>';
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
                if (briefCode[i].length === 2) {
                    if (briefType[i] == 'X') {
                        content.content = content.content.replace(re, "<span style='border-bottom:1px solid blue'>" + briefCode[i] + "</span>");
                    } else if (briefType[i] == 'W') {
                        content.content = content.content.replace(re, "<span style='border-bottom:3px solid blue'>" + briefCode[i] + "</span>");
                    } else {
                        content.content = content.content.replace(re, "<span style='border-bottom:2px solid green'>" + briefCode[i] + "</span>");
                    }
                } else if (briefCode[i].length === 3) {
                    content.content = content.content.replace(re, "<span style='border-bottom:3px solid #0090b0'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length === 4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid green'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length > 4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid #FF84BA'>" + briefCode[i] + "</span>");
                }

            }
        }
    }

    function onChange() {
        yaweiOCX.UpdateView();
        var input = getContent(document.getElementById("typeOCX"));
        yaweiOCX.Locate(input.length);
    }


    function onStenoPressKey(pszStenoString, device) {
        var inputO = getContent(yaweiOCX);
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
        window.G_content = inputO.replace(/\r\n/g, "").replace(/ /g, "");
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
        controlScroll();
        changWordPS();
        var text_old = '<?php echo $str; ?>';
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
                        createFont("#f44336", wrong, "");
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
                        createFont("#727272", old, oldCode);
                        old = new Array();
                        oldCode = new Array();
                        wrong = new Array();
                        wrong.push(stringText);
                    }
                }
            }
        }

        if (countLength !== 0) {
            createFont("#727272", old, oldCode);
            createFont("#f44336", wrong, "");
        }
        if (inputO.length < text.length) {
            var left = document.getElementById("content").value.substr(0 - (text.length - longIsAgo));
            createFont("#000000", left, "");
        }
    }

    function createFont(color, text, code) {
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        var content = {content: ""};
        var isBrief = 0;
        if (color === '#000000') {
            for (var i = 0; i < text.length; i++) {
                content.content += text[i];
            }
            f.style = "color:" + color;
            content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
            if(!titleFalse){
                checkYaweiCode(content);
            }
            f.innerHTML = content.content;
            father.appendChild(f);
        } else {
            if (color == "#727272") {
                for (var i = 0; i < text.length; i++) {
                    if (text[i].length < 3) {
                        for (var j = 0; j < briefOriginalYaweiCode.length; j++) {
                            if (text[i] == briefCode[j]) {
                                isBrief++;
                                if (code[i] == briefOriginalYaweiCode[j].replace(":0", "") || (code[i] == "W:X")) {
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
                        content.content += "<span style='background-color:blue;color:#fff'>" + text[i] + "</span>";
                        isBrief--;
                    }
                }
                f.style = "color:"+color;
                content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
                if(!titleFalse){
                    checkYaweiCode(content);
                }
                f.innerHTML = content.content;
                father.appendChild(f);
            } else {
                for (var i = 0; i < text.length; i++) {
                    content.content += text[i];
                }
                f.style = "color:"+color;
                //var t = document.createTextNode(text);
                //f.appendChild(t);
                if (color === "#f44336") {
                    content.content = content.content.replace(/`/g, "↓<br/>").replace(/}/g, "█");
                    if(!titleFalse){
                        checkYaweiCode(content);
                    }
                } else {
                    content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
                    if(!titleFalse){
                        checkYaweiCode(content);
                    }
                }
                f.innerHTML = content.content;
                father.appendChild(f);
            }
        }

    }


    function controlScroll() {
        var input = getContent(yaweiOCX);
        var addLine = (input.split('\n\r')).length - 1;
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 30) + addLine;
        if (line > 3) {
            div.scrollTop = (line - 3) * 30;
        }
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
                saveToDateBaseNow();
                //doSubmit(true);
                $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (<?php
if ($isExam) {
    echo 'true';
} else {
    echo 'false';
}
?>) {
                        window.location.href = "index.php?r=student/classExam";
                    }
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
