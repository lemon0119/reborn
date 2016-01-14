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
        <table style="width: 580px"  border = '0px'> <button class="fl btn" id="pause">暂停统计</button><button id="close_exercise" class="fr btn btn-primary">结束练习</button>
            <tr><h3><?php echo $classExercise['title'] ?></h3></tr>
            <tr>
                <td><span class="fl"  style="color: #000;font-weight: bolder">练习计时：</span></td>
                <td><span style="color: #f46500" id="timej">00:00:00</span></td>
                <td></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">正确率：&nbsp;&nbsp;</span></td>
                <td style="width: 60px;"><span style="color: #f46500" id="wordisRightRadio">0</span></td>
                <td><span class="fr" style="color: gray"> %&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl" style="color: #000;font-weight: bolder">回改字数：</span></td>
                <td style="width: 60px;"><span style="color: #f46500" id="getBackDelete">0</span></td>
                <td><span class="fr" style="color: gray"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr>
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
        <input id="content" type="hidden" style="height: 5px;" value="<?php  $str = str_replace("\n", "", $classExercise['content']);
$str = str_replace("\r", "", $str); echo $str;?>">
        <div id ="templet" class="questionBlock" front-size ="25px" onselectstart="return false" style="height: 190px">
        </div>
        <br/>
        <object id="typeOCX4Look" type="application/x-itst-activex" 
                clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                width ='700' height='310' 
                event_OnChange="onChange"
                event_OnStenoPress="onStenoPressKey">
        </object>
    </div>
</body>
<script>
    var yaweiOCX4Look = null;
    var briefCode = "";
    var briefOriginalYaweiCode = "";
    $(document).ready(function () {
        $.ajax({
               type:"POST",
               url:"index.php?r=api/getBrief",
               async: false,
               data:{},
               success:function(data){
                    briefCode = (data.split("$")[0]).split("&");
                    briefOriginalYaweiCode = (data.split("$")[1]).split("&");
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
               }
           });
           yaweiOCX4Look = document.getElementById("typeOCX4Look");
        $("#pause").click(function () {
            if (window.G_startFlag === 1) {
                if (window.G_isPause === 0) {
                    window.G_isPause = 1;
                }
                if (window.G_pauseFlag === 1) {
                    $("#pause").html("暂停统计");

                } else {
                    $("#pause").html("继续统计");
                }
            }
        });
    });
    var originalContent = "<?php echo $str;?>";
    //获取学生信息转入统计JS 实时存入数据库
    window.G_saveToDatabase = 1;
<?php
$exerciseID = $classExercise['exerciseID'];
$sqlClassExerciseRecord = ClassexerciseRecord::model()->findAll("classExerciseID = '$exerciseID'");
$countSquence = count($sqlClassExerciseRecord);
$squence = $countSquence + 1;
?>
    window.G_squence = <?php echo $squence; ?>;
    window.G_exerciseType = "classExercise";
    var classExerciseID = <?php echo $exerciseID; ?>;
    var studentID = "<?php echo Yii::app()->session['userid_now']; ?>";
    window.G_exerciseData = Array(classExerciseID, studentID);
    $("#close_exercise").click(function () {
        yaweiOCX4Look.remove();
        window.parent.closeClassExercise();
    });

    function onStenoPressKey(pszStenoString, device) {
        //使用统计JS必须在绑定的此onStenoPressKey事件中写入如下代码
        window.G_keyBoardBreakPause = 0;
        var myDate = new Date();
        window.G_pressTime = myDate.getTime();
        if (window.G_startFlag === 0) {
            window.G_startTime = myDate.getTime();
            window.G_startFlag = 1;
            window.G_oldStartTime = window.G_pressTime;
        }
        window.G_countMomentKey++;
        window.G_countAllKey++;
        window.G_content = yaweiOCX4Look.GetContent();
        window.G_keyContent = window.G_keyContent + "&" + pszStenoString;

        AjaxGetRight_Wrong_AccuracyRate("", "", "wordisRightRadio", originalContent, window.G_content);
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


    function getWordLength() {
        var input = getContent(yaweiOCX4Look);
        return input.length;
    }
    $(document).ready(function () {
       yaweiOCX4Look.HideToolBar();
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
    function createFontWithP(color, text, p, father) {
        var f = document.createElement("font");
        f.style = "color:" + color;
        //var t = document.createTextNode(text);
        //f.appendChild(t);
        f.innerHTML = text;
        p.appendChild(f);
        father.appendChild(p);
    }

    function createFont(color, text,code) {
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        var content = "";
        var isBrief = 0;
        if(color=="#808080"){
             for(var i=0;i<text.length;i++){
                 for(var j=0;j<briefCode.length;j++){
                     if((text[i]==briefCode[j])&&(code[i]!=briefOriginalYaweiCode[j].replace(":0",""))){
                         isBrief ++;
                     }
                 }
                 if(isBrief===0){
                    content += text[i];
                 }else{
                    content += "<font style='color:green'>"+text[i]+"</font>";
                    isBrief--;
                 }
             }
             f.style = "color:" + color;
                    //var t = document.createTextNode(text);
                    //f.appendChild(t);
                    f.innerHTML = content;
                    father.appendChild(f);
        }else{
            f.style = "color:" + color;
                    //var t = document.createTextNode(text);
                    //f.appendChild(t);
                    f.innerHTML = text;
                    father.appendChild(f);
        }
       
       
    }


    function controlScroll() {
        var input = getContent(yaweiOCX4Look);
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 23);
        if (line > 3) {
            div.scrollTop = (line - 3) * 30;
        }
    }
    function onChange() {
        yaweiOCX4Look.UpdateView();
        var input = getContent(yaweiOCX4Look);
        yaweiOCX4Look.Locate(input.length);
        controlScroll();
        changWordPS();
        var text_old = originalContent;
        if (text_old.indexOf("\n") > 0) {
            var div = document.getElementById("templet");
            while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
                div.removeChild(div.firstChild);
            }
            var input_old = getContent(yaweiOCX4Look);
            var arrayinput = input_old.split("\r\n");
            var father = document.getElementById("templet");
            var arraytext = text_old.split("\n");
            for (var s = 0; s < arraytext.length; s++) {
                var p = document.createElement("p");
                if (arrayinput[s]) {
                    var input_p = arrayinput[s].split("");
                    var text_p = arraytext[s].split("");
                    var old = "";
                    var isWrong = false;
                    var wrong = "";
                    for (var i = 0; i < input_p.length && i < text_p.length; i++) {
                        if (input_p[i] == text_p[i]) {
                            if (isWrong == true) {
                                isWrong = false;
                                createFontWithP("#ff0000", wrong, p, father);
                                wrong = "";
                                old = text_p[i];
                            } else {
                                old += text_p[i];
                            }
                        }
                        else {
                            if (isWrong == true)
                                wrong += text_p[i];
                            else {
                                isWrong = true;
                                createFontWithP("#808080", old, p, father);
                                old = "";
                                wrong = text_p[i];
                            }
                        }
                    }
                    createFontWithP("#808080", old, p, father);
                    createFontWithP("#ff0000", wrong, p, father);
                    if (input_p.length < text_p.length) {
                        var left_p = arraytext[s].substr(input_p.length);
                        createFontWithP("#000000", left_p, p, father);
                    }
                } else if (!arrayinput[s]) {
                    createFontWithP("#000000", arraytext[s], p, father);
                }
            }
        } else {
            var input = getContent(yaweiOCX4Look).split("");
            var text = text_old.split("");
            //----实验体
            var allInput2 = yaweiOCX4Look.GetContentWithSteno().split(">,");
            var longIsAgo = 0;
            //-----
            var old = new Array();
            var oldCode = new Array();
            var isWrong = false;
            var wrong = "";
            var div = document.getElementById("templet");
            while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
                div.removeChild(div.firstChild);
            }
            for (var i = 0; i < input.length && i < text.length; i++) {
                if(allInput2[i]!== undefined){
                    var num = allInput2[i].indexOf(">");
                    var content  = allInput2[i].substring(1,num);
                    var yaweiCode = allInput2[i].substring(num+2,allInput2[i].length).replace(">","");
                    var long = content.length;
                    longIsAgo += long;
                    var stringText = text[longIsAgo-long];
                    for(var j=1;j<long;j++){
                        stringText += text[longIsAgo-long+j];
                    }
                    if (content == stringText) {
                        if (isWrong == true) {
                            isWrong = false;
                            createFont("#ff0000", wrong,"");
                            wrong = "";
                            old = new Array();
                            old.push(stringText);
                            oldCode = new Array();
                            oldCode.push(yaweiCode);
                        } else {
                            old.push(stringText);
                            oldCode.push(yaweiCode);
                        }
                    }else {
                        if (isWrong == true)
                            wrong += stringText;
                        else {
                            isWrong = true;
                            createFont("#808080",old,oldCode);
                            old = Array("");
                            oldCode = Array("");
                            wrong = stringText;
                        }
                    }
                }
            }
            createFont("#808080",old,oldCode);
            createFont("#ff0000",wrong,"");
            if (input.length < text.length) {
                var left =document.getElementById("content").value.substr(0 - (text.length - longIsAgo));
                createFont("#000000", left,"");
            }
        }
    }
</script>