<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>
<script src="<?php echo JS_URL;?>exerJS/time.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<?php  if($isExam == false){ 
    require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } 
    //add by lc 
    $type = 'look'; 
    if($isExam){
        $seconds = $exerOne['time'];
        $hh = floor(($seconds) / 3600);
        $mm = floor(($seconds) % 3600 / 60);
        $ss = floor(($seconds) % 60);
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
    <div class="hero-unit" align="center">
            <?php 
                Yii::app()->session['exerID'] = $exerOne['exerciseID'];
            ?>
        <table border = '0px'>
                <tr><h3><?php echo $exerOne['title']?></h3></tr>
                <tr>
                    <?php if($isExam){?>
                        <td width = '250px'>分数：<?php echo $exerOne['score']?></td>
                        <td width = '250px'>总时间：<?php echo $strTime?></td>
                    <?php }?>
                    <td width = '250px'>计时：<span id="time">00:00:00</span></td>
                    <td width = '250px'>字数：<span id="wordCount">0</span> 字</td>
                    <td width = '250px'>速度：<span id="wordps">0</span> 字/分</td>
                </tr>
        </table>
        <br/>
        <input id="content" type="hidden" style="height: 5px;" value="<?php echo $exerOne['content'];?>">
        <div id ="templet" class="questionBlock" front-size ="25px" onselectstart="return false" style="height: 120px">
        </div>
        <br/>
        <object id="typeOCX" type="application/x-itst-activex" 
                clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                width ='750' height='350' 
                event_OnChange="onChange">
        </object>
    </div>
    <?php require  Yii::app()->basePath."\\views\\student\\submitAnswer.php";?>
</div>
  <?php } else {?>
 <h3 align="center">本题时间已经用完</h3>
<?php }?>
<script>
    
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    
    $(document).ready(function(){
        var v=<?php echo Tool::clength($exerOne['content']);?>;
        $("#wordCount").text(v);
        if(isExam){
            alert("本题作答时，不能中途退出，做完需点击保存后方可做下一题！！");
            var isover = setInterval(function(){
                var time = getSeconds();
                //console.log(time + "time");
                var seconds = <?php if($isExam) echo $exerOne['time']; else echo '0';?>;
                //console.log(seconds + "seconds");
                if(seconds==0){}
                else if(time >= seconds&&seconds!=0){
                    clearInterval(isover);
                    doSubmit(true,function(){
                        window.location.href="index.php?r=student/clsexamOne&&suiteID=<?php echo Yii::app()->session['examsuiteID'];?>&&workID=<?php echo Yii::app()->session['examworkID']?>";
                    });
                    
                }
            },1000);
        }
    });
    
    function getWordLength(){
        var input = getContent(document.getElementById("typeOCX"));
        return input.length;
    }
    
    $(document).ready(function(){
        //菜单栏变色
        $("li#li-look-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
        //显示题目
        createFont("#000000", document.getElementById("content").value);
    });
    //document.getElementById("templet").style.font_size = "25px";
    function createFont(color, text){
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        f.style = "color:"+color;
        //var t = document.createTextNode(text);
        //f.appendChild(t);
        f.innerHTML = text;
        father.appendChild(f);
    }
    function controlScroll(){
        var input = getContent(document.getElementById("typeOCX"));
        var div = document.getElementById('templet');
        var line = parseInt(input.length / 23);
        if (line > 3){
            div.scrollTop = (line - 3) * 30;
        }
    }
    function onChange(){
        controlScroll();
        changWordPS();
        var input = getContent(document.getElementById("typeOCX")).split("");
        var text = document.getElementById("content").value.split("");
        var old = "";
        var isWrong = false;
        var wrong = "";
        var div = document.getElementById("templet");
        while(div.hasChildNodes()) {//当div下还存在子节点时 循环继续
            div.removeChild(div.firstChild);
        }
        for(var i = 0; i < input.length && i < text.length; i++){
            if(input[i] == text[i]) {
                if(isWrong == true){
                    isWrong = false;
                    createFont("#ff0000", wrong);
                    wrong = "";
                    old = text[i];
                } else {
                    old += text[i];
                }
            }
            else {
                if(isWrong == true)
                    wrong += text[i];
                else{
                    isWrong = true;
                    createFont("#808080", old);
                    old = "";
                    wrong = text[i];
                }
            }
        }
        createFont("#808080", old);
        createFont("#ff0000", wrong);
        if(input.length < text.length) {
            var left = document.getElementById("content").value.substr( 0 - (text.length - i));
            createFont("#000000", left);
        }
    }
</script>
