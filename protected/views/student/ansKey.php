<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9" style="height: 570px">
    <div class="hero-unit">
        <div style="float: left">
        <h2>键打练习</h2>
        </div>
        <?php $isExam = Yii::app()->session['isExam'];
        if($isExam){
            echo '<div id="score" style="position: absolute;right: 79px;top: 54px">';
               if($flag == "未批阅"){
                   echo '未批阅';
                }else {
                    echo'得分：'.$score;
                }
                echo '</div> <br><br> '; 
         
        }
?>
       
        <table border = '0px' width="100%">
            
            <tr>
                <td width = '50%' align='center'>题目：<?php echo $exer['title'] ?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f', $correct);
echo '%'; ?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>作答结果：</div>
                    <div style="text-align: left" id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()">
                        <font id="currentContent"></font>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div style="text-align: left" id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                        <font id="originalContent"></font>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    var currentContent = '<?php
            $str = str_replace("\n", "<br/>", Tool::filterKeyOfInputWithYaweiCode($answer));
            $str = str_replace("\r", "", $str);
            $str = str_replace(" ", "", $str);
            echo $str;
            ?>';
    var originalContent = '<?php
            $str2 = str_replace("\n", "<br/>", Tool::filterKeyContent($exer['content']));
            $str2 = str_replace("\r", "", $str2);
            $str2 = str_replace(" ", "", $str2);
            for($i=1;$i<=$exer['repeatNum'];$i++){
            echo $str2;}
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
                currentInnerHTML += '<font style="color:#f44336">' + currentContent[i] + '</font>';
            } else {
                currentInnerHTML += '<font style="color:#727272">' + currentContent[i] + '</font>';
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
        //console.log(right[0].substring(right[0].indexOf(':0') + 2).charCodeAt());
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
            //console.log(rightkey);
            answerSingle = answer[i].substring(0, answer[i].indexOf('><'));
//        if(i===0){
//            console.log(rightkey ===answer[i].substring(0,answer[i].indexOf('><')) );
//            console.log(rightkey.charCodeAt());
//            console.log("懊恼".charCodeAt());
//            console.log(answer[i].substring(0,answer[i].indexOf('><')).charCodeAt());
//            console.log('!'+answer[i].substring(0,answer[i].indexOf('><'))+"!");
//            //console.log("步步"===answer[i].substring(0,answer[i].indexOf('><')));
//        }
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
        createFont('templet', '#808080', right_text);
        i++;
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
    $(document).ready(function () {
        $("li#li-key-<?php echo $exer['exerciseID']; ?>").attr('class', 'active');
       // start();
    });
</script>