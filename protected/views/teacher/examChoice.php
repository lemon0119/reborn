
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit">
          <?php
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
                <?php $mark++;}?>
</div>
   配分:<?php echo $exam_exercise['score'];?>
   得分:<input teyp="text" id="score" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button>
</div>
<script>
    function nextWork(answerID,recordID,examID,exerciseID){
        if(<?php echo $isLast?> == 1)
        {
            alert("已是最后一题");
            return ;
        }
        var user = {
            recordID:recordID,
            type:"choice",
            examID:examID,
            exerciseID:exerciseID,
            score:$("#score")[0].value,
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


