<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
    <div class="hero-unit">
        <?php 
        $n=1;
        $m=0;
            echo "<h2>简答题</h2>"; 
            foreach ($works  as $k=>$work){ 
                   if(isset($choiceAnsWork[$k])){
                        $uAns = $choiceAnsWork[$k];     
                     }else{
                         $uAns = "";
                     }     
                     if($uAns == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                echo $n.". ";
                echo $work['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                echo '<div style="min-width: 99%" class=\'answer-question\'>'.$choiceAnsWork[$k].'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div style="min-width: 99%" class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
                $n++;
                ?>配分:<br/>
                得分:<input type="text" id="input" style="width: 50px" value ="<?php echo $ansWork[$k]['score']?>" > 
                <button onclick="saveScore(<?php echo $ansWork[$k]['score']?>,<?php if($ansWork[$k]['answerID'] != "") echo $ansWork[$k]['answerID'];else echo 1;?>,<?php if($ansWork[$k]['recordID'] != "") echo $ansWork[$k]['recordID'];else echo 1;?>,<?php echo $exam_exercise[$m]['examID'];?>,<?php echo $works[$m++]['exerciseID'];?>)" class="btn btn-primary">保存</button>
            <?php echo "<br/>";}?>
    </div>
    
       
   
</div>
<script>
    $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
    });
     function saveScore(scoreOld,answerID,recordID,examID,exerciseID){
         var value1 = $("#input")[0].value;
         var re = /^[0-9]*[1-9][0-9]*$/ ; 
         if(!re.test(value1)){
             window.wxc.xcConfirm("分值只能为正整数！", window.wxc.xcConfirm.typeEnum.error);
             $("#input")[0].value=scoreOld;
             return false;
        }
         var totalscore = <?php echo $exam_exercise['score'];?>;
        if(value1>totalscore){
            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
        }else{
            alert(exerciseID);
            alert(examID);
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
          url:"./index.php?r=teacher/ajaxExam&&classID=<?php echo $classID?>",
          data:user,
          dataType:"html",
          success:function(html){     
              $("#ziji").html(html);
          }
      });
      }
    }

</script>
