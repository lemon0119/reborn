<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit">
          <?php
          $totalScore=0;
          $realScore=0;
          $n=1;$m=0;
          $f;
          echo "<h2>选择题</h2>"; 
         foreach ($works  as $k=>$work){         
                $right = $work['answer'];
                $f=$k;
                if($choiceAnsWork[$k]!="no1"){
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
    <div class="<?php if($uAns === $right ){  $realScore=$realScore+$exam_exercise[$m]['score'];} else {}?>"></div>
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
         <?php $mark++;} $totalScore=$totalScore+$exam_exercise[$m]['score'];
                     $n++;
                    $m++;}?>

   <?php if($works){?>
       分数:<?php echo $totalScore;?><br/>
   得分:<input type="text" id="input" style="width: 50px" value ="<?php  echo $realScore?>" disabled="disabled"> 
       <?php }?>
   </div>
<!--    按键配分-->
   <?php //if(count($works)>0){?>
<!--        <button onclick="saveScore(<?php //if(isset($ansWork)) echo $ansWork[0]['answerID'];else echo 1;?>)" class="btn btn-primary">保存</button>-->
   <?php //}?>
</div>
<script>
   $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
      //自动配分
       saveScore();
    });
     
    function saveScore(){
        var value1 = $("#input")[0].value;
            var user = {
            type:"choice",
            workID:"<?php echo $workID;?>",
            studentID:"<?php echo $studentID;?>",
            accomplish:"<?php echo $accomplish;?>",
            examID:<?php echo $examID;?>,
            score:value1,
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
</script>


