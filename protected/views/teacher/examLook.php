<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL;?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL;?>exerJS/accounting.js"></script>
<?php

require 'examAnsSideBar.php';
?>
<div class="span9">
    <div id="ziji">
    <div class="hero-unit">
         <h2>看打练习</h2>
        <?php if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
         <input id="text" type="hidden" value="<?php echo $answer;?>"/>
        <input id="content" type="hidden" style="height: 5px;" value="<?php $str = str_replace("\n", "`",$exer['content'] );
$str = str_replace("\r", "", $str);
$str = str_replace(" ", "}", $str);
echo $str;
?>">
        <table border = '0px' width="100%">            
            <tr>
                <td width = '50%' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$correct);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>作答结果：</div>
                    <div id ="answer"  class="answer-question" onselectstart="return false" onscroll="doScrollRight()">
                        <font><?php echo Tool::filterContentOfInputWithYaweiCode($ansWork['answer']); ?></font>
                    </div>
                    
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div id ="templet" style="min-width: 99%"  class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                         <font><?php echo $str; ?></font>
                    </div>
                </td>
            </tr>
        </table>
         
        <div id ="templet" style="text-align: left;height: 260px" class="questionBlock" front-size ="25px" onselectstart="return false">
        </div>
    </div>

分数:<?php echo $exam_exercise['score'];?><br/>
   得分:<input type="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="saveScore(<?php echo $ansWork['score']?>,<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $exerID;?>)" class="btn btn-primary">保存</button>
</div>
</div>
<textarea id="text1" style="display: none"><?php echo ($exer['content']);?></textarea>
<textarea id="text2" style="display: none"><?php echo ($ansWork['answer']);?></textarea>
<?php
    if(isset(Yii::app()->session['type'])){
        $type = Yii::app()->session['type'];
        echo "<script>var type = '$type';</script>"; 
    }
?>
<script>
    $(document).ready(function(){   
        $("li#li-look-<?php echo $exer['exerciseID'];?>").attr('class','active');
      $("#score").html(<?php echo $score;?>);
      //start();
       
    });
    var briefCode = "";
    var briefOriginalYaweiCode = "";
     var briefType = "";
    $(document).ready(function () {
        window.G_isLook = 1;
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
    });

   function checkYaweiCode(content) {
        for (var i = 0; i < briefCode.length; i++) {
            if (content.content.indexOf(briefCode[i]) >= 0) {
                var re = new RegExp(briefCode[i], "g");
                if (briefCode[i].length === 2) {
                    if(briefType[i]=='X'){
                        content.content = content.content.replace(re, "<span style='border-bottom:1px solid blue'>" + briefCode[i] + "</span>");
                    }else if(briefType[i]=='W'){
                         content.content = content.content.replace(re, "<span style='border-bottom:3px solid blue'>" + briefCode[i] + "</span>");
                    }else{
                         content.content = content.content.replace(re, "<span style='border-bottom:2px solid green'>" + briefCode[i] + "</span>");
                    }
                } else if (briefCode[i].length===3) {
                    content.content = content.content.replace(re, "<span style='border-bottom:3px solid #0090b0'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length===4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid green'>" + briefCode[i] + "</span>");
                }else if (briefCode[i].length>4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid #FF84BA'>" + briefCode[i] + "</span>");
                }
            }
        }
    }

    function load(){
            var url = "./index.php?r=student/preExer&&type=classwork";
        $("#cont").load(url);
    }
    
function createFont4Answer(element, color, text) {
        var father = document.getElementById(element);
        var f = document.createElement("font");
        f.style = "color:" + color;
        f.innerHTML = text;
        father.appendChild(f);
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
                    content.content += "<span style='color:blue'>" + text[i] + "</span>";
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
    
//    function createFont(element, color, text){
//        var father = document.getElementById(element);
//        var f = document.createElement("font");
//        f.style = "color:"+color;
//        f.innerHTML = text;
//        father.appendChild(f);
//    }
    function doScrollLeft(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divright.scrollTop = divleft.scrollTop;
    }
    function doScrollRight(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divleft.scrollTop = divright.scrollTop;
    }
    
        function start() {
        var text_old = '<?php echo $str; ?>';
        var input = "";
        var contentAllArray = $('#text').val().substring(1,$('#text').val().length-1).split('>,<');
        for(var i=0;i<contentAllArray.length;i++){
            var content = contentAllArray[i].substring(0,contentAllArray[i].indexOf('><'));
            input+=content;
        }
        var text = text_old.split("");
        var allInput2 = $('#text').val().replace(/\r\n/g, "`").replace(/ /g, "}").split(">,");
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
       
        var right = text_old.split("");
        //var rightKey = '<?php //echo Tool::filterKeyContent($exer['content']); ?>'.split(" ");
        var answer = input.split("");
        var i, j, sright;
        i = 0;
        sright = false;
        var answer_right = '';
        var answer_wrong = '';
        var answerSingle = '';
        var rightkey = '';
        var right_text = '';
        while (i < answer.length) {
            rightkey = right[i];
            //console.log(rightkey);
            answerSingle = answer[i];
//        if(i===0){
//            console.log(rightkey ===answer[i].substring(0,answer[i].indexOf('><')) );
//            console.log(rightkey.charCodeAt());
//            console.log("懊恼".charCodeAt());
//            console.log(answer[i].substring(0,answer[i].indexOf('><')).charCodeAt());
//            console.log('!'+answer[i].substring(0,answer[i].indexOf('><'))+"!");
//            //console.log("步步"===answer[i].substring(0,answer[i].indexOf('><')));
//        }
            if (rightkey === answerSingle) {
                right_text += (rightkey + '');
                answer_right += (answerSingle + '');
                i++;
                if (sright === false) {
                    sright = true;
                    createFont4Answer('answer', '#ff0000', answer_wrong);
                    answer_wrong = '';
                }
            } else {
                answer_wrong += (answerSingle + '');
                i++;
                if (sright === true) {
                    sright = false;
                    createFont4Answer('answer', '#808080', answer_right);
                    answer_right = '';
                }
            }
            answerSingle = '';
        }
        createFont4Answer('answer', '#808080', answer_right);
        answer_right = '';
        createFont4Answer('answer', '#ff0000', answer_wrong);
       
    }
    
//    function start(){
//        var text1 = $('#text1').val();
//        var text2 = $('#text2').val();
//        var lcs = new LCS(text1, text2);
//        if(lcs == null)
//            return;
//        lcs.doLCS();
//        var tem = lcs.getStrOrg(1);
//        var ans = lcs.getStrOrg(2);
//        var modTem = lcs.getSubString(1);
//        var modAns = lcs.getSubString(2);
//        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
//        displayTemp('templet', tem, modTem);
//        displayTemp('answer', ans, modAns);
//    }
//    
    
//    function displayTemp(id, temp, modTem){
//        var flag = false;
//        var j = 0;
//        for(var i = 0; i < modTem.length && i < temp.length; i++){
//            if(modTem[i] === '*'){
//                if(!flag){
//                    flag = true;
//                    createFont(id,'#000000',temp.substring(j, i));
//                    j = i;
//                }
//            } else {
//                if(flag){
//                    flag = false;
//                    createFont(id,'#ff0000',temp.substring(j, i));
//                    j = i;
//                }
//            }
//        }
//        if(j < i){
//            if(!flag)
//                createFont(id,'#000000',temp.substring(j, i));
//            else
//                createFont(id,'#ff0000',temp.substring(j, i));
//        }
//        if(i < temp.length)
//            createFont(id,'#ff0000',temp.substr(i));
//        if(i < modTem.length)
//            createFont(id,'#ff0000',modTem.substr(i));
//    } 
    function saveScore(scoreOld,answerID,recordID,examID,exerciseID){
         var value1 = $("#input")[0].value;
         var re =/^([1-9]\d*|[0]{1,1})$/ ; 
         if(!re.test(value1)){
             window.wxc.xcConfirm("分值只能为0、正整数！", window.wxc.xcConfirm.typeEnum.error);
             $("#input")[0].value=scoreOld;
             return false;
        }
         var totalscore = <?php echo $exam_exercise['score'];?>;
        if(value1>totalscore){
            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
        }else{
            var user = {
                recordID:recordID,
                type:"look",
                workID:"<?php echo $workID;?>",
                studentID:"<?php echo $studentID;?>",
                accomplish:"<?php echo $accomplish;?>",
                examID:examID,
                exerciseID:exerciseID,
                score:$("#input")[0].value,
                answerID:answerID
            };
          $.ajax({
              type:"POST",
              url:"./index.php?r=teacher/ajaxExam2&&classID=<?php echo $classID?>&&accomplish=<?php echo $_GET['accomplish']?>",
              data:user,
              dataType:"html",
              success:function(html){  
                  window.wxc.xcConfirm('打分成功！', window.wxc.xcConfirm.typeEnum.success,{
                        onOk:function(){
                              location.reload();
                        }
                    });
              },
            error: function(xhr, type, exception){
                window.wxc.xcConfirm(xhr.responseText, window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
          });
      }
    }

</script>

