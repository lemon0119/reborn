<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
    <div class="hero-unit"style="height: 574px;overflow:auto">
        <?php 
        echo '<h2>简答题</h2>';
         $n=1;
          foreach ($works  as $k=>$work){ 
                   if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                echo "<font>$n </font>.";
                echo $work['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                echo '<div class=\'answer-question\'>'.$choiceAnsWork[$k].'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
          $n++;}?>

