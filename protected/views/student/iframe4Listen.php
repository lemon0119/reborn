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
        <table style="width: 580px"  border = '0px'><button class="fl btn" id="pause">暂停统计</button><button id="close_exercise" class="fr btn btn-primary">关闭</button>
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
        <?php 
                $listenpath = EXER_LISTEN_URL.$classExercise['file_path'].$classExercise['file_name'];
            ?>
        <div style="position: relative;top: -130px" align="left">
                <br/>
                <audio id="music" style='position:absolute; z-index:2; width:300px; height:28px; left:50px; top:150px; '  src = "<?php echo $listenpath;?>"   preload = "auto"    controls=""></audio>
            </div>
        <input id="content" type="hidden" style="height: 5px;" value="<?php  $str = str_replace("\n", "", $classExercise['content']); $str = str_replace("\r", "", $classExercise['content']);echo $str;?>">
        <br/>
        <object id="typeOCX4Listen" type="application/x-itst-activex" 
                clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                width ='700' height='480' 
                event_OnChange="onChange"
                event_OnStenoPress="onStenoPressKey">
        </object>
    </div>
</body>
<script>
    var yaweiOCX4Listen = null;
    $(document).ready(function () {
        document.getElementById('span').scrollIntoView();
        yaweiOCX4Listen = document.getElementById("typeOCX4Listen");
            $("#pause").click(function(){
            if(window.G_startFlag===1){
            if(window.G_isPause ===0){
                window.G_isPause = 1;
            }
                var audio = document.getElementById('music');  
                if(window.G_pauseFlag===1){
                    audio.play();
                    $("#pause").html("暂停统计");
                    
                }else{
                    $("#pause").html("继续统计");
                    audio.pause();
                }
            }
        });
    });
     var originalContent = "<?php  $str = str_replace("\n", "", $classExercise['content']); $str = str_replace("\r", "", $classExercise['content']);echo $str;?>";
     window.GA_originalContent = originalContent;
     //获取学生信息转入统计JS 实时存入数据库
    window.G_saveToDatabase = 1;
    <?php  $exerciseID = $classExercise['exerciseID'];
            $studentID = Yii::app()->session['userid_now'];
            $sqlClassExerciseRecord = ClassexerciseRecord::model()->findAll("classExerciseID = '$exerciseID' AND studentID = '$studentID'");
            $countSquence = count($sqlClassExerciseRecord);
            $squence = $countSquence+1;
            ?>
    window.G_squence = <?php echo $squence;?>;
    window.G_exerciseType = "classExercise";
    var classExerciseID = <?php echo $exerciseID; ?>;
    var studentID = "<?php echo Yii::app()->session['userid_now']; ?>";
    window.G_exerciseData = Array(classExerciseID,studentID);
        $("#close_exercise").click(function () {
        yaweiOCX4Listen.remove();
        window.parent.closeClassExercise();
    });

    function onChange(){
        yaweiOCX4Listen.UpdateView();
        var input = getContent(yaweiOCX4Listen);
        yaweiOCX4Listen.Locate(input.length);
    }
    
    function onStenoPressKey(pszStenoString, device) {
        window.GA_answer = yaweiOCX4Listen.GetContentWithSteno();
        //使用统计JS必须在绑定的此onStenoPressKey事件中写入如下代码
        window.G_keyBoardBreakPause = 0;
        var audio = document.getElementById('music');
         if(window.G_pauseFlag===1){
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
        window.G_content = yaweiOCX4Listen.GetContent();
        window.G_keyContent = window.G_keyContent + "&" + pszStenoString;
        
        AjaxGetRight_Wrong_AccuracyRate("","","wordisRightRadio",originalContent,window.G_content);
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
    $(document).ready(function(){
    yaweiOCX4Listen.HideToolBar();
        //菜单栏变色
        $("li#li-listen-<?php echo $classExercise['exerciseID'];?>").attr('class','active');
    });
    
    function getWordLength(){
        var input = getContent(yaweiOCX4Listen);
        return input.length;
    }
    
</script>