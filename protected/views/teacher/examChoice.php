<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit">
          <?php
          //print_r($work);
         //print_r($ansWork['answer']); 无法打印
         //print_r($exam_exercise);
         //print_r($isLast);
         //print_r($score);
         foreach ($works as $work){
             
                $right = $work['answer'];
                $uAns = $ansWork['answer'];              
                if($uAns == "")
                {
                    echo "<font color=red>未作答</font>";
                    echo '</br>';
                }
                else{
                ?>
                 <div class="<?php if($uAns === $right ) echo 'answer-right'; else echo 'answer-wrong';?>"></div>
        <?php }?>
        <?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <span class='answer-check'></span>
                    <?php }?>
                    <br/>
         <?php $mark++;}}?>
</div>
   配分:<?php echo $exam_exercise['score'];?>
   得分:<input type="text" id="input" style="width: 50px" value ="<?php  echo $ansWork['score']?>" > 
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button>
</div>
<script>
   $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
       if(<?php echo $isLast?> == 1)
        {
            alert("已是最后一题");
            return ;
        }
    });
     
    function nextWork(answerID,recordID,examID,exerciseID){
        var value1 = $("#input")[0].value;
        var user = {
            recordID:recordID,
            type:"choice",
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
      })
    }
</script>


