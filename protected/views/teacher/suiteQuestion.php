<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
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
            type:"question",
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
