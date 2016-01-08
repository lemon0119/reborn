<script src="<?php echo JS_URL; ?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/timep.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL;?>exerJS/AnalysisTool.js"></script>
<?php
  if($isExam == false) {
    require 'suiteSideBar.php';
} else {
    require 'examSideBar.php';
}
//add by lc 
$type = 'look';
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
<?php if (!$isOver) { ?>
    <div class="span9">
        <div class="hero-unit" align="center">
            <?php
            Yii::app()->session['exerID'] = $exerOne['exerciseID'];
            ?>
            <table border = '0px'>
                <tr><h3><?php echo $exerOne['title'] ?></h3></tr>
                <tr>
                    <?php if ($isExam) { ?>
                        <td width = '250px'>分数：<?php echo $exerOne['score'] ?></td>
                        <td width = '250px'>剩余时间：<span id="time"><?php echo $strTime ?></span><input id="timej" type="hidden"/></td>
                        <td width = '250px'>字数：<span id="wordCount">0</span></td>
                        <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                    <?php } else { ?>
                        <td width = '250px'>计时：<span id="timej">00:00:00</span></td>
                        <td width = '250px'>字数：<span id="wordCount">0</span></td>
                        <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                    <?php } ?>
                </tr>
            </table>
            <br/>
            <input id="content" type="hidden" style="height: 5px;" value="<?php echo str_replace('\r\n', '<br/>', $exerOne['content']); ?>">
            <div id ="templet" class="questionBlock" front-size ="25px" onselectstart="return false" style="height: 120px">
            </div>
            <br/>
            <object id="typeOCX" type="application/x-itst-activex" 
                    clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                    width ='750' height='310' 
                    event_OnChange="onChange"
                    event_OnStenoPress="onStenoPressKey">
            </object>
        </div>
        <?php require Yii::app()->basePath . "\\views\\student\\submitAnswer.php"; ?>
    </div>


<div  class="analysisTool" id="analysis" style="background-color: #fff;left: 1170px; height: 670px; width: 220px;">
        <table style="margin: 0px auto; font-size: 18px;position:relative;top: -250px;" cellpadding="20"  >
            <tr>
                <td ><span  style="font-weight: bolder">平均速度：</span><span style="color: #f46500" id="getAverageSpeed">0</span><span style="color: gray"> 字/分</span> </td></tr>
                 <tr><td><span style="font-weight: bolder">最高速度：</span><span style="color: #f46500" id="getHighstSpeed">0</span ><span style="color: gray"> 字/分</span></td></tr>
                <tr><td><span style="font-weight: bolder">瞬时速度：</span><span style="color: #f46500" id="getMomentSpeed">0</span ><span style="color: gray"> 字/分</span></td></tr>
                <tr><td><span style="font-weight: bolder">回改字数：</span><span style="color: #f46500" id="getBackDelete">0</span ><span style="color: gray"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
                
            <tr>
                <td><span style="font-weight: bolder">平均击键：</span><span style="color: #f46500" id="getAverageKeyType">0</span ><span style="color: gray"> 次/分</span></td></tr>
                <tr><td><span style="font-weight: bolder">最高击键：</span><span style="color: #f46500" id="getHighstCountKey">0</span ><span style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span style="font-weight: bolder">瞬时击键：</span><span style="color: #f46500" id="getMomentKeyType">0</span ><span style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span style="font-weight: bolder">总击键数：</span><span style="color: #f46500" id="getcountAllKey">0</span ><span style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
                <tr><td><span style="font-weight: bolder">击键间隔：</span><span style="color: #f46500" id="getIntervalTime">0</span ><span style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr><tr><td><span style="font-weight: bolder">最高间隔：</span><span style="color: #f46500" id="getHighIntervarlTime">0</span ><span style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr>
        </table>
    </div>
<?php } else { ?>
    <h3 align="center">本题时间已经用完</h3>
<?php } ?>
<script>

function onStenoPressKey(pszStenoString ,device){
     //使用统计JS必须在绑定的此onStenoPressKey事件中写入如下代码
        window.G_keyBoardBreakPause =0;
        var myDate = new Date();
         window.G_pressTime = myDate.getTime();
         if(window.G_startFlag ===0){
                    window.G_startTime = myDate.getTime();
                    window.G_startFlag = 1; 
                    window.G_oldStartTime = window.G_pressTime;
                }
                window.G_countMomentKey++;
                window.G_countAllKey++;
                window.G_content = document.getElementById("typeOCX").GetContent();
                window.G_keyContent = window.G_keyContent +"&"+pszStenoString;
                
                          //每击统计击键间隔时间 秒
                          //@param id=getIntervalTime 请将最高平均速度统计的控件id设置为getIntervalTime 
                          //每击统计最高击键间隔时间 秒
                          //@param id=getHighIntervarlTime 请将最高平均速度统计的控件id设置为getHighIntervarlTime 
          if(window.G_endAnalysis===0){
                 var pressTime = window.G_pressTime;
                 if(pressTime - window.G_oldStartTime >0){
                     var IntervalTime = parseInt((pressTime - window.G_oldStartTime)/10)/100;
                      $("#getIntervalTime").html(IntervalTime);
                      window.GA_IntervalTime  = IntervalTime;
                     window.G_oldStartTime = pressTime;
                 }
                 if(IntervalTime-window.G_highIntervarlTime>0){
                     window.G_highIntervarlTime = IntervalTime;
                      window.GA_IntervalTime  = window.G_highIntervarlTime ;
                     $("#getHighIntervarlTime").html(IntervalTime);
                 }             
          }                
           
        //--------------------------------------------------
     }

    $(document).ready(function () {

        var isExam = <?php
if ($isExam) {
    echo 1;
} else {
    echo 0;
}
?>;
        var v =<?php echo Tool::clength($exerOne['content']); ?>;
        $("#wordCount").text(v);
        <?php   if (!$isOver){?>
                        					var option = {
						title: "提示",
						btn: parseInt("0011",4),
					};					
        <?php }?>
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
                    var seconds =<?php if ($isExam)
        echo $exerOne['time'];
    else
        echo '0';
    ?>;

                    if (time == 0) {
                        var option = {
						title: "提示",
						btn: parseInt("0011",4),
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

    function getWordLength() {
        var input = getContent(document.getElementById("typeOCX"));
        return input.length;
    }

    $(document).ready(function () {
        yaweiOCX = document.getElementById("typeOCX");
        yaweiOCX.HideToolBar();
        
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

    function createFont(color, text) {
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        f.style = "color:" + color;
        //var t = document.createTextNode(text);
        //f.appendChild(t);
        f.innerHTML = text;
        father.appendChild(f);
    }


    function controlScroll() {
        var input = getContent(document.getElementById("typeOCX"));
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 23);
        if (line > 3) {
            div.scrollTop = (line - 3) * 30;
        }
    }
    function onChange() {
        controlScroll();
        changWordPS();

        var text_old = document.getElementById("content").value;
        if (text_old.indexOf("\n") > 0) {
            var div = document.getElementById("templet");
            while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
                div.removeChild(div.firstChild);
            }
            var input_old = getContent(document.getElementById("typeOCX"));
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
                                createFontWithP("#ff0000", wrong ,p, father);
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
                }else if(!arrayinput[s]){
                    
                    createFontWithP("#000000", arraytext[s], p, father);
                }

            }


        } else {
            var input = getContent(document.getElementById("typeOCX")).split("");
            var text = text_old.split("");
            var old = "";
            var isWrong = false;
            var wrong = "";
            var div = document.getElementById("templet");
            while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
                div.removeChild(div.firstChild);
            }
            for (var i = 0; i < input.length && i < text.length; i++) {
                if (input[i] == text[i]) {
                    if (isWrong == true) {
                        isWrong = false;
                        createFont("#ff0000", wrong);
                        wrong = "";
                        old = text[i];
                    } else {
                        old += text[i];
                    }
                }
                else {
                    if (isWrong == true)
                        wrong += text[i];
                    else {
                        isWrong = true;
                        createFont("#808080", old);
                        old = "";
                        wrong = text[i];
                    }
                }
            }
            createFont("#808080", old);
            createFont("#ff0000", wrong);
            if (input.length < text.length) {
                var left = document.getElementById("content").value.substr(0 - (text.length - i));
                createFont("#000000", left);
            }

        }
    }
</script>
