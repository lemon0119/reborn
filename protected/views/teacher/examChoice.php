<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit-choice">
          <?php
          $totalScore=0;
          $realScore=0;
          $n=1;
          echo "<h2>选择题</h2>"; 
         foreach ($works  as $k=>$work){         
                $right = $work['answer'];
                
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
                else{
                ?>
        <?php }?>
                
                <?php echo "<font>$n</font>"?>.&nbsp<?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                 <input type="radio" disabled <?php  if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <font color="green" font="12px">&nbsp;  &nbsp;正确答案</font>
                    <?php }?>
                    <br/>
         <?php $mark++;} $totalScore=$totalScore+$exam_exercise['score'];
                     $n++;
                    }?>
</div>
   配分:<?php echo $totalScore;?><br/>
   得分:<input type="text" id="input" style="width: 50px" value ="<?php  echo $realScore?>" disabled="disabled"> 
</div>
<script>
   $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
       
    });
     
    function saveScore(answerID,recordID,examID,exerciseID){
        var value1 = $("#input")[0].value;
        if(value1><?php echo $totalScore;?>){
            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
        }else{
            var user = {
            recordID:recordID,
            type:"choice",
            workID:"<?php echo $workID;?>",
            studentID:"<?php echo $studentID;?>",
            accomplish:"<?php echo $accomplish;?>",
            examID:examID,
            exerciseID:exerciseID,
            score:value1,
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


