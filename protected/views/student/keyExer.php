<script src="<?php echo JS_URL;?>exerJS/time.js"></script>
<script src="<?php echo JS_URL;?>exerJS/AnalysisTool.js"></script>
<?php  if($isExam == false){ 
    require 'suiteSideBar.php';
}else{ 
    require 'examSideBar.php';
} 
     //add by lc 
    $type = 'key'; 
    if($isExam){
        $seconds = $exerOne['time'];
        $hh = floor(($seconds*60) / 3600);
        $mm = floor(($seconds*60) % 3600 / 60);
        $ss = floor(($seconds*60) % 60);
        $strTime = "";
        $strTime .= $hh < 10 ? "0".$hh : $hh;
        $strTime .= ":";
        $strTime .= $mm < 10 ? "0".$mm : $mm;
        $strTime .= ":";
        $strTime .= $ss < 10 ? "0".$ss : $ss;
    }//end
 ?>
<?php if(!$isOver){?>

<div class="span9">
    
    <div class="hero-unit"  align="center">
        <?php Yii::app()->session['exerID'] = $exerOne['exerciseID'];?>  
        
        <table border = '0px'>
                <tr><h3><?php echo $exerOne['title']?></h3>                
                <tr>
                    
                <span id="repeatNum" style="display: none"><?php echo $exerOne['repeatNum']?></span>
                    <?php if($isExam){?>
                        <td width = '250px'>分数：<?php echo $exerOne['score']?></td>
                        <td width = '250px'>剩余时间：<span id="time"><?php echo $strTime?></span><input id="timej" type="hidden"/></td>
                        <span id="wordisRightRadio" style="display: none;">0</span>
                        <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                    <?php }else{?>
                    <td width = '250px'>计时：<span id="timej">00:00:00</span></td>                  
                    <td width = '250px'>准确率：<span id="wordisRightRadio">0</span>%</td>       
                    <td width = '250px'>循环次数：<span id="repeatNum"><?php echo $exerOne['repeatNum']?></span></td>
                     <?php }?>
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
            if($exerOne['category'] == "correct")
                require  Yii::app()->basePath."\\views\\student\\correct_keyboard.php";
            
            else if($exerOne['category'] == "free" )

                require  Yii::app()->basePath."\\views\\student\\keyboard.php";
            
            else
                require  Yii::app()->basePath."\\views\\student\\speed_keyboard.php";
            ?>
        </div>
        <?php
            $host = Yii::app()->request->hostInfo;
            $path = Yii::app()->request->baseUrl;
            $page = '/index.php?r=student/saveAnswer';
            if(isset($_GET['page']))
                $index = $_GET['page'];
            else 
                $index = 1;
            $param = '&page='.$index;
            if(isset(Yii::app()->session['type']))
                $param = $param.'&&type='.Yii::app()->session['type'];
        ?>
    </div>
    <form name='nm_answer_form' id='id_answer_form' method="post" action="<?php echo $host.$path.$page.$param;?>">
        <input id="id_content" type="hidden" value="<?php echo $exerOne['content'];?>">
        <input id="id_speed" type="hidden" value="<?php echo $exerOne['speed'];?>">
        <input  name="nm_answer"id="id_answer" type="hidden">
        <input  name="nm_cost" id="id_cost" type="hidden">
    </form>
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
  <?php } else {?>
 <h3 align="center">本题时间已经用完</h3>
<?php }?>
<script>
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    
    $(document).ready(function(){
        <?php   if (!$isOver){?>         
        <?php }?>
      if(<?php  if($isExam){echo $exerOne['time'];}else {echo 0;}?>!=0){ 
        <?php if($isExam){?>
            reloadTime2(<?php echo $exerOne['time'];?>,isExam);
            var isover = setInterval(function(){
                var time = getSeconds();
                var seconds =<?php if($isExam) echo $exerOne['time']; else echo '0';?>;
               if(time==0){
                    window.wxc.xcConfirm("本题时间已到，不可答题！", window.wxc.xcConfirm.typeEnum.error);
                    clearInterval(isover);
                    doSubmit(true,function(){
                        window.location.href="index.php?r=student/examkeyType&&exerID=<?php echo $exerID;?>&&cent=<?php $arg= implode(',', $cent);echo $arg;?>";
                    });
                    
                }
                
            },1000);
     <?php }?>
    }
        startParse();
    });
    
    $(document).ready(function(){
        $("li#li-key-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
    });
    
    function getWordLength(){
        var input = document.getElementById("id_answer");
        var answer = input.value;
        var reg = new RegExp(":", "g");
        var res = answer.match(reg);
        var length = res === null ? 0 : res.length;
        return length;
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
   function formSubmit(){
					var option = {
						title: "提示",
						btn: parseInt("0011",4),
						onOk: function(){
                                                   doSubmit(false);
						} 
					};
					window.wxc.xcConfirm("是否确认保存答案！！", "custom", option);
    }
    function submitSuite(simple){
      
        var option = {
						title: "提交试卷",
						btn: parseInt("0011",4),
						onOk: function(){
							 doSubmit(true);
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
						} 
					};
					window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);
       
    }
    function doSubmit(simple,doFunction){
        //$('#id_answer_form').submit();
        $.post($('#id_answer_form').attr('action'),$('#id_answer_form').serialize(),function(result){
            if(!simple){               
            }else{
                doFunction();
            }
        });
    }
     document.getElementById("id_new").firstChild.nodeValue = document.getElementById("id_content").value;
    function restart(){
        var obj =  document.getElementById("typeOCX");
        var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							clearContent(obj);
                                                        reloadTime();
                                                        keyReSet();
                                                        clearWord();
                                                        clearTemplate();
						} 
					};        
    }   
    window.G_saveToDatabase = 1;
    window.G_squence = 0;
    window.G_exerciseType = "answerRecord";
//    var answer = document.getElementById("id_answer").value;
//    var cost = document.getElementById("id_cost").value;
   window.G_exerciseData = Array("1");
</script>