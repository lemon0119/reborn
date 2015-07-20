<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9">
    <div class="hero-unit">
        <input name ="qType" type="hidden" value="question"/>
        <?php 
            $SNum = 0;
            foreach ($exercise['question'] as $value) {
                echo ($SNum+1).'. ';
                echo $value['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>您的回答：</div>';
                echo '<div class=\'answer-question\'>'.$ansQuest[$value['exerciseID']].'</div>';
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