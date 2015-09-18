
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
            type:"choice",
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


