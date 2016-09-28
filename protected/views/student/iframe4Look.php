<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/timep.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script> <script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<body style="background-image: none;background-color: #fff">
    <button id="toggle" style="position: relative;" class="btn">展开</button>
    <div id="span" class="hero-unit" align="center">
        <!--        <div style="width: 660px">
                                <button class="fl btn" id="pause">暂停统计</button>
                    <button id="finish" onclick="finish()" style="margin-left:30px;" class="fl btn btn-primary" >完成练习</button>
                    
                </div>-->
        <div id="Analysis">
            <?php if (isset($_GET['ispractice'])) { ?><tr><h3><?php echo $classExercise['title'] ?></h3></tr><?php } ?>
            <table style="width: 580px"  border = '0px'> 
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
            <table style="width: 580px"  border = '0px'> 
                <tr>
                    <td><span class="fl"   style="color: #000;font-weight: bolder">瞬时速度：</span></td>
                    <td style="width: 64px;"><span style="color: #f46500" id="getMomentSpeed">0</span ></td>
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
        <input id="content" type="hidden" style="height: 5px;" value="<?php
        $str = str_replace("\n", "`", $classExercise['content']);
        $str = str_replace("\r", "", $str);
        $str = str_replace(" ", "}", $str);
        echo $str;
        ?>">
        <div id ="templet" style="text-align: left;height: 260px" class="questionBlock" front-size ="25px" onselectstart="return false">
        </div>
        <br/>
        <object id="typeOCX4Look" type="application/x-itst-activex" 
                clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                width ='660' height='300' 
                event_OnStenoPress="onStenoPressKey">
        </object>
    </div>
</body>
<script>
    var yaweiOCX4Look = document.getElementById("typeOCX4Look");
    var briefCode = "";
    var briefOriginalYaweiCode = "";
    var briefType = "";
    <?php $titleFalse=strpos($classExercise['title'],"-不提示略码"); ?>
    var titleFalse = "<?php echo $titleFalse; ?>";
    $(document).ready(function () {
        window.G_isLook = 1;
        document.getElementById('Analysis').scrollIntoView();
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
                console.log('GetAverageSpeed error', type);
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });
        
//        $("#pause").click(function () {
//            if (window.G_startFlag === 1&&window.G_isOverFlag ===0 ) {
//                if (window.G_isPause === 0) {
//                    window.G_isPause = 1;
//                }
//                if (window.G_pauseFlag === 1) {
//                    $("#pause").html("暂停统计");
//                    
//                } else {
//                    $("#pause").html("继续统计");
//                }
//            }
//        });
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

    });
    var originalContent = '<?php echo $str; ?>';
    window.GA_originalContent = originalContent.replace(/}/g, "").replace(/`/g, "");
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
    
    function onChange(){
        yaweiOCX4Look.UpdateView();
        var input = getContent(yaweiOCX4Look);
        yaweiOCX4Look.Locate(input.length);
    }

    function onStenoPressKey(pszStenoString, device) {
        var inputO = getContent(yaweiOCX4Look);
        window.GA_answer = yaweiOCX4Look.GetContentWithSteno();
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
        var allInput2 = yaweiOCX4Look.GetContentWithSteno().replace(/\r\n/g, "`").replace(/ /g, "}").split(">,");
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
        if (inputO.length < text.length) {
            var left = document.getElementById("content").value.substr(0 - (text.length - longIsAgo));
            createFont("#000000", left, "left");
        }
    }


    function getWordLength() {
        var input = getContent(yaweiOCX4Look);
        return input.length;
    }
    $(document).ready(function () {
        //菜单栏变色
        $("li#li-look-<?php echo $classExercise['exerciseID']; ?>").attr('class', 'active');
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
    //document.getElementById("templet").style.font_size = "25px";
//    function createFontWithP(color, text, p, father) {
//        var f = document.createElement("font");
//        f.style = "color:" + color;
//        //var t = document.createTextNode(text);
//        //f.appendChild(t);
//        f.innerHTML = text;
//        p.appendChild(f);
//        father.appendChild(p);
//    }

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
            if(titleFalse){
            }else{
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
                if(titleFalse){
                }else{
                    checkYaweiCode(content);
                    }
                f.innerHTML = content.content;
                father.appendChild(f);
            } else {
                for (var i = 0; i < text.length; i++) {
                    content.content += text[i];
                }
                f.style = "color:"+color
                //var t = document.createTextNode(text);
                //f.appendChild(t);
                if (color === "#f44336") {
                    content.content = content.content.replace(/`/g, "↓<br/>").replace(/}/g, "█");
                    if(titleFalse){
                    }else{
                        checkYaweiCode(content);
                    }
                } else {
                    content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
                    if(titleFalse){
                    }else{
                    checkYaweiCode(content);
                }
                }
                f.innerHTML = content.content;
                father.appendChild(f);
            }
        }
    }
    function controlScroll() {
        var input = getContent(yaweiOCX4Look);
        var addLine = (input.split('\n\r')).length - 1;
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 23) + addLine;
        if (line > 3) {
            div.scrollTop = (line - 3) * 30;
        }
    }

    function finish() {
        if (window.G_startFlag === 1) {
            window.G_isOverFlag = 1;
            $("#finish").attr("disabled", "disabled");
            briefCode = null;
            briefOriginalYaweiCode = "";
            window.parent.finish();
        }
    }
    window.onbeforeunload = onbeforeunload_handler;
    window.onunload = onunload_handler;
    function onbeforeunload_handler() {
        yaweiOCX4Look.remove();

    }
    function onunload_handler() {
        yaweiOCX4Look.remove();
    }

</script>