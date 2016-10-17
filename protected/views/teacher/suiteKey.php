<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<?php


require 'workAnsSideBar.php';
?>
<div class="span9">
<div id="ziji">
    <div class="hero-unit">
        <h2>键打练习</h2>
                <?php if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
        <table border = '0px' width="100%">
            <tr>
                <td width = '50%;' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$correct);echo '%';?></span></td>
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
<!--                    <div><?php// echo ($exer['content']);?></div>-->
                    <div style="text-align: left" id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                        <font id="originalContent"></font>
                    </div>                    
                </td>
            </tr>
        </table>
    </div>
</div>
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
        var i, j, sright;
        i = 0;
        sright = false;
        var answer_right = '';
        var rightkey = '';
        var answer_wrong = '';
        var answerSingle = '';
        var right_text = '';
        while (i < answer.length) {
            if(right[i]!==undefined){
                if(i<right.length){
                    rightkey = right[i].substring(right[i].indexOf(':0')+2); 
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
//        if (i < answer.length) {
//            answer.splice(0, i);
//            createFont('answer', '#ff0000', answer.join(' '));
//        }
    }
$(document).ready(function(){
    $("li#li-key-<?php echo $exer['exerciseID'];?>").attr('class','active');
   // start();
});
</script>
    

