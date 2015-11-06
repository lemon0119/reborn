<!--声音播放-->
<script src="<?php echo JS_URL;?>audioplayer.js"></script>
<!--打字控件-->
<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>
<!--打字计时-->
<script src="<?php echo JS_URL;?>exerJS/time.js"></script>

<?php
    //2015-8-3 宋杰 判断加载suitesidebar还是examsiderbar
    if($isExam == false){ 
        require 'suiteSideBar.php';
     }else{ 
        require 'examSideBar.php';
     }
    //add by lc 
    $type = 'listen'; 
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
            <table border = '0px'>
                <tr><h3><?php echo $exerOne['title']?></h3></tr>
                <tr>
                    <?php if($isExam){?>
                        <td width = '250px'>分数：<?php echo $exerOne['score']?></td>
                        <td width = '250px'>剩余时间：<span id="time"><?php echo $strTime?></span><input id="timej" type="hidden"/></td>
                        <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                    <?php }else{?>
                    <td width = '250px'>计时：<span id="timej">00:00:00</span></td>
                    <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                     <?php }?>
                </tr>
            </table>
            <?php 
                $listenpath = EXER_LISTEN_URL.$exerOne['filePath'].$exerOne['fileName'];
                Yii::app()->session['exerID'] = $exerOne['exerciseID'];
            ?>
            <div align="left">
                <br/>
                <audio src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
            </div>
            <br/>
            <input id="content" type="hidden" value="<?php echo $exerOne['content'];?>">
            <object id="typeOCX" type="application/x-itst-activex" 
                    clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                    width ='750' height='350' 
                    event_OnStenoPress="onStenoPress">
            </object>
            <br/>
        </div>
    <?php require  Yii::app()->basePath."\\views\\student\\submitAnswer.php";?>
</div>
  <?php } else {?>
 <h3 align="center">本题时间已经用完</h3>
<?php }?>
<script>
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    $(document).ready(function(){
        <?php   if (!$isOver){?>
        alert("本题作答时，不能中途退出，做完需点击保存后方可做下一题！！");
        <?php }?>
        if(<?php if($isExam){echo $exerOne['time'];}else {echo 0;} ?>!=0){
        <?php if($isExam){?>
            reloadTime2(<?php echo $exerOne['time'];?>,isExam);
            var isover = setInterval(function(){
                var time = getSeconds();

                var seconds =<?php if($isExam) echo $exerOne['time']; else echo '0';?>;
               
        if(time==0){
                    alert("本题时间已到，不可答题！");
                    clearInterval(isover);
                   doSubmit(true,function(){
                      window.location.href="index.php?r=student/examlistenType&&exerID=<?php echo $exerID;?>&&cent=<?php $arg= implode(',', $cent);echo $arg;?>";
                    });
                }
            },1000);
      <?php }?>
}
    });
    
    $(document).ready(function(){
        //菜单栏变色
        $("li#li-listen-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
    });
    
    function getWordLength(){
        var input = getContent(document.getElementById("typeOCX"));
        return input.length;
    }
</script>