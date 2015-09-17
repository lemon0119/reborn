<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">

<div id="ziji">
        <div class="hero-unit">
            <?php 
                    $str = $work['requirements'];
                    $answer = $ansWork['answer'];
                    $ansArr = explode('$$', $answer);
                    if($answer == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                    echo $str.'<br/>';
                    $i = 1;
                    echo '<div class=\'answer-tip-text1\'>您的回答：</div>';
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
            ?>
        </div>
<button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $suite_exercise['suiteID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button> 
</div>

<script>
    $(document).ready(function(){
    var isLast = <?php echo $isLast?>;
    if(isLast == 1)
        alert("已是最后一题");
    });
    
    function nextWork(answerID,recordID,suiteID,exerciseID){
        var user = {
            answerID:answerID,
            recordID:recordID,
            type:"filling",
            suiteID:suiteID,
            exerciseID:exerciseID
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxChoice",
          data:user,
          dataType:"html",
          success:function(html){
              $("#ziji").html(html);
          }
      })
    }
</script>

