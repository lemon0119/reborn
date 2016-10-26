<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9"style="height:570px; overflow:auto; border:0px;">
    <div class="hero-unit">
        <input name ="qType" type="hidden" value="question"/>
        <?php 
        echo '<h2>简答题</h2>';
            $SNum = 0;
            foreach ($exercise['question'] as $value) {
                echo ($SNum+1).'. ';
                echo $value['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                $answerQ = isset($ansQuest[$value['exerciseID']]) ? $ansQuest[$value['exerciseID']] : '';
                echo '<div class=\'answer-question\'>'.$answerQ.'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div class=\'answer-question\'>'.$value['answer'].'</div>';
                echo '<br/>';
                $SNum++;
            }
        ?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("li#li-question").attr('class','active');
});
</script>