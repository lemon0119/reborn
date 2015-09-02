<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
    <div class="hero-unit">
        <?php 
                   if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                echo $work['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>您的回答：</div>';
                echo '<div class=\'answer-question\'>'.$ansWork['answer'].'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
        ?>
    </div>

<script>
    $(document).ready(function(){
    var isLast = <?php echo $isLast?>;
    if(isLast == 1)
        alert("已是最后一题");
    });
</script>
