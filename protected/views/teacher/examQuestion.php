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
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                echo '<div class=\'answer-question\'>'.$ansWork['answer'].'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
        ?>
    </div>
   配分:<?php echo $exam_exercise['score'];?>
   得分:<input teyp="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button>
</div>
<script>
     function nextWork(answerID,recordID,examID,exerciseID){
        if(<?php echo $isLast?> == 1)
        {
            window.wxc.xcConfirm("已是最后一题", window.wxc.xcConfirm.typeEnum.warning);
            return ;
        }
        var user = {
            recordID:recordID,
            type:"question",
            workID:"<?php echo $workID;?>",
            studentID:"<?php echo $studentID;?>",
            accomplish:"<?php echo $accomplish;?>",
            examID:examID,
            exerciseID:exerciseID,
            score:$("#input")[0].value,
            answerID:answerID
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxExam",
          data:user,
          dataType:"html",
          success:function(html){     
              $("#ziji").html(html);
          }
      })
    }
</script>
