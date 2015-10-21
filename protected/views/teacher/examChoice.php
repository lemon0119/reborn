<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit-choice">
          <?php
          $totalScore=0;
          $realScore=0;
          $n=1;
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
    <div class="<?php if($uAns === $right ){   echo 'answer-right-choice'; $realScore=$realScore+$exam_exercise['score'];} else {echo 'answer-wrong-choice';}?>"></div>
        <?php }?>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "<font color=green size=5px>$n</font>"?>.<?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                 <input style="margin-left: 60px;" type="radio" disabled <?php  if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <span class='answer-check'></span>
                    <?php }?>
                    <br/>
         <?php $mark++;} $totalScore=$totalScore+$exam_exercise['score'];
                     $n++;
                    }?>
</div>
   配分总分:<?php echo $totalScore;?><br/>
   实际得分:<input type="text" id="input" style="width: 50px" value ="<?php  echo $realScore?>" > 
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存</button>
</div>
<script>
   $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
       if(<?php echo $isLast?> == 1)
        {
                window.location.href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=filling&&studentID=<?php echo $studentID;?>&&accomplish=<?php echo $accomplish;?>";
        }
    });
     
    function nextWork(answerID,recordID,examID,exerciseID){
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
          url:"./index.php?r=teacher/ajaxExam",
          data:user,
          dataType:"html",
          success:function(html){     
              $("#ziji").html(html);           
          }
      });
        }
        
    }
</script>


