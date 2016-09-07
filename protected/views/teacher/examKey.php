<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/accounting.js"></script>
<?php

require 'examAnsSideBar.php';
?>

<div class="span9">
    <div id="ziji">
    <div class="hero-unit">
        <h2>键打练习</h2>
                <?php if(count($ansWork) == 0)
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
        <table border = '0px' width="100%">
            <tr>
                <td width = '50%' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$correct); echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>作答结果：</div>
<!--                    <div ><?php //echo $ansWork['answer'];?></div>-->
                    <div style="text-align: left" id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()">
                        <font id="currentContent"></font>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
<!--                    <div><?php //echo ($exer['content']);?></div>-->
                    <div style="text-align: left" id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                         <font id="originalContent"></font>
                    </div>

            </tr>
        </table>
</div>
    分数:<?php echo $exam_exercise['score'];?><br/>
   得分:<input type="text" id="input" style="width: 50px" value ="<?php echo floor($exam_exercise['score']*$correct*0.01);?>" disabled="disabled">   
   </div>
<!--   
手动打分
<button onclick="saveScore(<?php// echo $ansWork['score']?>,<?php// if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php// if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php// echo $exam_exercise['examID'];?>,<?php// echo $exerID;?>)" class="btn btn-primary">保存</button>-->

</div>

<script>
     var currentContent = '<?php
            $str = str_replace("\n", "<br/>", Tool::filterKeyOfInputWithYaweiCode($ansWork['answer']));
            $str = str_replace("\r", "", $str);
            $str = str_replace(" ", "", $str);
            echo $str;
            ?>';
    var originalContent = '<?php
            $str2 = str_replace("\n", "<br/>", Tool::filterKeyContent($exer['content']));
            $str2 = str_replace("\r", "", $str2);
            $str2 = str_replace(" ", "", $str2);
            echo $str2;
            ?>';
    var lcs = new LCS(currentContent, originalContent);
    lcs.doLCS();
    var currentFont = document.getElementById('currentContent');
    var originalFont = document.getElementById('originalContent');
    var currentLCS = lcs.getSubString(1);
    var originalLCS = lcs.getSubString(2);
    var currentInnerHTML='';
    var originalInnerHTML='';
    for (var i = 0; i < currentContent.length; i++) {
        if (typeof (currentContent[i]) !== 'undefined') {
            if (currentContent[i] !== currentLCS[i]) {
                currentInnerHTML += '<font style="background-color:#f44336;color:#fff">' + currentContent[i] + '</font>';
            } else {
                currentInnerHTML += '<font style="background-color:#727272;color:#fff">' + currentContent[i] + '</font>';
            }
        }
    }
    for (var i = 0; i < originalContent.length; i++) {
        if (typeof (originalContent[i]) !== 'undefined') {
            if (originalContent[i] !== originalLCS[i]) {
                originalInnerHTML += '<font style="color:#f44336">' + originalContent[i] + '</font>';
            } else {
                originalInnerHTML += '<font style="color:#727272">' + originalContent[i] + '</font>';
            }
        }
    }
    currentFont.innerHTML = currentInnerHTML;
    originalFont.innerHTML = originalInnerHTML;
    
     $(document).ready(function(){   
         $("li#li-key-<?php echo $exer['exerciseID'];?>").attr('class','active');
      $("#score").html(<?php echo $score;?>);
      saveScore(<?php  echo floor($exam_exercise['score']*$correct*0.01)?>);
     // start(); 
    });
    
    function saveScore(answerID){
        
        var value1 = $("#input")[0].value;
            var user = {
            type:"key",
            workID:"<?php echo $workID;?>",
            studentID:"<?php echo $studentID;?>",
            accomplish:"<?php echo $accomplish;?>",
            examID:<?php echo $examID;?>,
            score:value1,
            answerID:answerID
            };
            $.ajax({
                type:"POST",
                url:"./index.php?r=teacher/ajaxExam3&&classID=<?php echo $classID?>",
                data:user,
                dataType:"html",
                success:function(html){    
                     $("#ziji").html(html);
                }
            });
        
        
    }
    
//    function saveScore(scoreOld,answerID,recordID,examID,exerciseID){
//         var value1 = $("#input")[0].value;
//         var re = /^([1-9]\d*|[0]{1,1})$/ ; 
//         if(!re.test(value1)){
//             window.wxc.xcConfirm("分值只能为0、正整数！", window.wxc.xcConfirm.typeEnum.error);
//             $("#input")[0].value=scoreOld;
//             return false;
//        }
//         var totalscore = <?php// echo $exam_exercise['score'];?>;
//        if(value1>totalscore){
//            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
//        }else{
//        var user = {
//            recordID:recordID,
//            type:"key",
//            workID:"<?php// echo $workID;?>",
//            studentID:"<?php// echo $studentID;?>",
//            accomplish:"<?php// echo $accomplish;?>",
//            examID:examID,
//            exerciseID:exerciseID,
//            score:$("#input")[0].value,
//            answerID:answerID
//        };
//      $.ajax({
//          type:"POST",
//          url:"./index.php?r=teacher/ajaxExam2&&classID=<?php// echo $classID?>&&accomplish=<?php// echo $_GET['accomplish']?>",
//          data:user,
//          dataType:"html",
//          success:function(html){  
//                   window.wxc.xcConfirm('打分成功！', window.wxc.xcConfirm.typeEnum.success,{
//                        onOk:function(){
//                              location.reload();
//                        }
//                    });
//              },
//            error: function(xhr, type, exception){
//                console.log(xhr.responseText);
//                console.log(xhr, "Failed");
//            }
//      });
//      }
//    }
    function createFont(element, color, text) {
        var father = document.getElementById(element);
        var f = document.createElement("font");
        f.style = "color:" + color;
        f.innerHTML = text;
        father.appendChild(f);
    }
    function doScrollLeft() {
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divright.scrollTop = divleft.scrollTop;
    }
    function doScrollRight() {
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divleft.scrollTop = divright.scrollTop;
    }
    function start() {
        var right = '<?php echo $exer['content']; ?>'.split('$$');
        var rightKey = '<?php echo Tool::filterKeyContent($exer['content']); ?>'.split(" ");
        var answer = '<?php echo $answer; ?>'.substring(1,'<?php echo $answer; ?>'.length-1).split('>,<');
        var i, j, sright;
        i = 0;
        sright = false;
        var answer_right = '';
        var answer_wrong = '';
        var answerSingle = '';
        var right_text = '';
        while (i < answer.length) {
            if(right[i]!==undefined){
                if(i<right.length){
                    rightkey = right[i].substring(right[i].indexOf(':0') + 2);
                }
            }
            answerSingle = answer[i].substring(0, answer[i].indexOf('><'));
            if (rightkey === answerSingle || i === 0) {
                if(i<right.length){
                    right_text += (rightkey + ' ');
                }
                answer_right += (answerSingle + ' ');
                i++;
                if (sright === false) {
                    sright = true;
                    createFont('answer', '#ff0000', answer_wrong);
                    answer_wrong = '';
                }
            } else {
                if(i<right.length){
                    right_text += (rightkey + ' ');
                }
                answer_wrong += (answerSingle + ' ');
                i++;
                if (sright === true) {
                    sright = false;
                    createFont('answer', '#808080', answer_right);
                    answer_right = '';
                }
            }
            answerSingle = '';
        }
        i++;
        createFont('templet', '#808080', right_text);
        if (i < rightKey.length) {
            rightKey.splice(0, i);
            createFont('templet', '#000000', rightKey.join(' '));
        }
        createFont('answer', '#808080', answer_right);
        answer_right = '';
        createFont('answer', '#ff0000', answer_wrong);
        answer_wrong = '';
//        if (j < answer.length) {
//            answer.splice(0, j);
//            createFont('answer', '#ff0000', answer.join(' '));
//        }
    }
</script>


