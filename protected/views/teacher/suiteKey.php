<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
    <div class="hero-unit">
        <table border = '0px' width="100%">
            <tr>
                <td width = '100px' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$correct * 100);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>您的回答：</div>
                    <div id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()"></div>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()"></div>
                </td>
            </tr>
        </table>
    </div>

<script>
function createFont(element, color, text){
    var father = document.getElementById(element);
    var f = document.createElement("font");
    f.style = "color:"+color;
    f.innerHTML = text;
    father.appendChild(f);
}
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
function start(){
    var right = '<?php echo ($exer['content']);?>'.split(' ');
    var answer = '<?php echo ($answer);?>'.split(' ');
    var i, j, sright;
    i = j = 0;
    sright = false;
    var right_text = '';
    var answer_right = '';
    var answer_wrong = '';
    while(i < right.length && j < answer.length){
        if(right[i] === answer[j]){
            right_text += (right[i] + ' ');
            answer_right += (answer[j] + ' ');
            i++;
            j++;
            if(sright === false){
                sright = true;
                createFont('answer', '#ff0000', answer_wrong);
                answer_wrong = '';
            }
        } else {
            answer_wrong += (answer[j] + ' ');
            j++;
            if(sright === true){
                sright = false;
                createFont('answer', '#808080', answer_right);
                answer_right = '';
            }
        }
    }
    createFont('templet', '#808080', right_text);
    if(i < right.length){
        right.splice(0,i);
        createFont('templet', '#000000', right.join(' '));
    }
    createFont('answer', '#808080', answer_right);
    answer_right = '';
    createFont('answer', '#ff0000', answer_wrong);
    answer_wrong = '';
    if(j < answer.length){
        answer.splice(0,j);
        createFont('answer', '#ff0000', answer.join(' '));
    }
}
$(document).ready(function(){
    $("li#li-key-<?php echo $exer['exerciseID'];?>").attr('class','active');
    start();
});
</script>

