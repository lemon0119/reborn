<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">

<div id="ziji">
        <div class="hero-unit"style="height: 574px;overflow:auto">
            <?php 
            echo '<h2>填空题</h2>';
             $n=1;
           foreach ($works  as $k=>$work){ 
                    $str = $work['requirements'];
                    
                    if(isset($choiceAnsWork[$k])){
                   $answer = $choiceAnsWork[$k];
                }else{
                    $answer="";
                }
                    $ansArr = explode('$$', $answer);
                    if($answer == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                    echo "<font>$n </font>.";
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
                    $right = $work['answer'];
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
           $n++;}?>




