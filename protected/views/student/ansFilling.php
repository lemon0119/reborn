<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9"style="height:480px; overflow:auto; border:0px;">
        <div class="hero-unit">
            <?php 
            echo '<h2>填空题</h2>';
                $SNum = 0;
                foreach ($exercise['filling'] as $value) {
                    echo ($SNum+1).'. ';
                    $str = $value['requirements'];
                    $answer = isset($ansFilling[$value['exerciseID']]) ? $ansFilling[$value['exerciseID']] : '';
                    $ansArr = explode('$$', $answer);
                    echo $str.'<br/>';
                    $i = 1;
                    echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                    echo '<div>';
                    while($i < count($ansArr)+1){
                        echo '('.$i.') ';
                        echo '<div class=\'answer-filling\'>'.$ansArr[$i-1].'</div>';
                        if(!($i%3))
                            echo '<br/>';
                        $i++;
                    }
                    echo '</div>';
                    echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                    echo '<div>';
                    $right = $value['answer'];
                    $rightArr = explode('$$', $right);
                    $i = 1;
                    while($i < count($rightArr)+1){
                        echo '('.$i.') ';
                        echo '<div class=\'answer-filling\'>'.$rightArr[$i-1].'</div>';
                        if(!($i%3))
                            echo '<br/>';
                        $i++;
                    }
                    echo '</div>';
                    echo '<br/>';
                    $SNum++;
                }
            ?>
        </div>
</div>
<script>
$(document).ready(function(){
    $("li#li-filling").attr('class','active');
});
</script>