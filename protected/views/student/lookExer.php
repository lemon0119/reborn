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
<div class="span9" >
    <?php if($isExam){?>
                    <?php }else{?>
    <div align="center">
                      <table style="width: 580px"  border = '0px'> 
                          <tr><td colspan="9"><h3><?php echo $exerOne['title']?></h3></td></tr>
            <tr>
                <td><span class="fl"  style="color: #000;font-weight: bolder">练习计时：</span></td>
                <td><span style="color: #f46500" id="timej">00:00:00</span></td>
                <td></td>
                <td><span class="fl"   style="color: #000;font-weight: bolder">正确率：&nbsp;&nbsp;</span></td>
                <td style="width: 60px;"><span style="color: #f46500" id="">----</span></td>
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
    </div>
                     <?php }?>
        <div class="hero-unit fl" align="center">
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
            <input id="content" type="hidden" style="height: 5px;" value="<?php  $str = str_replace("\n", "", $exerOne['content']);
$str = str_replace("\r", "", $str);$str = str_replace(" ", "", $str); echo $str;?>">
            <div id ="templet" class="questionBlock" front-size ="25px" onselectstart="return false" style="height: 120px">
            </div>
            <br/>
            <object id="typeOCX" type="application/x-itst-activex" 
                    clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                    width ='750' height='250' 
                    event_OnChange="onChange"
                    event_OnStenoPress="onStenoPressKey">
            </object>
        </div>
        <?php require Yii::app()->basePath . "\\views\\student\\submitAnswer.php"; ?>
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
   
var briefCode = "";
    var briefOriginalYaweiCode = "";   
var yaweiOCX = null;
    $(document).ready(function () {
        setInterval(function () {    //setInterval才是轮询，setTimeout是一定秒数后，执行一次的！！
            writeData();
            doSubmit(); 
            window.G_squence = 0;
       }, 3000);
       <?php
$studentID = Yii::app()->session['userid_now'];
$sqlAnswerRecord = AnswerRecord::model()->findAll("createPerson = '$studentID' AND recordID = '$recordID'");
$countSquence = count($sqlAnswerRecord);
$squence = $countSquence + 1;
?>
       
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
    
     function getWordLength() {
        var input = getContent(yaweiOCX);
        return input.length;
    }
    
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
                 if(text[i].length<3){
                        for(var j=0;j<briefCode.length;j++){
                            if((text[i]==briefCode[j])&&(code[i]!=briefOriginalYaweiCode[j].replace(":0",""))){
                                isBrief ++;
                            }
                         }
                 }else{
                     isBrief ++;
                 }
                 if(isBrief===0){
                    content += text[i];
                 }else{
                    content += "<font style='color:green'>"+text[i]+"</font>";
                    isBrief--;
                 }
             }
             f.style = "color:" + color;
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
        var text_old = "<?php echo $str;?>";
            var input = getContent(yaweiOCX).split("");
            var text = text_old.split("");
            var allInput2 = yaweiOCX.GetContentWithSteno().split(">,");
            var longIsAgo = 0;
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
            if((text.length - longIsAgo)<1){
                window.G_isOverFlag = 1;
            }
        }
        
    window.G_saveToDatabase = 1;
    window.G_squence = <?php echo $squence; ?>;
    window.G_exerciseType = "answerRecord";
//    var answer = document.getElementById("id_answer").value;
//    var cost = document.getElementById("id_cost").value;
   window.G_exerciseData = <?php if(isset($recordID)){
       echo $recordID;
   }else {
       echo '0';
   }?>
</script>
