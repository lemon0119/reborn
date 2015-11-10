
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit" style="height: 480px;overflow:auto">
          <?php
          echo '<h2>选择题</h2>';
           $n=1;
           foreach ($works  as $k=>$work){ 
                $right = $work['answer'];
                if(isset($choiceAnsWork[$k])){
                    $uAns = $choiceAnsWork[$k];
                }else{
                    $uAns="";
                }
                if($uAns == "")
                {
                    echo "<font color=red>未作答</font>";
                    echo '</br>';
                }
                else{
                ?>
    <div class="<?php if($uAns === $right ){  $realScore=$realScore+$exam_exercise['score'];} else {}?>"></div>
        <?php }?>
        <?php echo "<font>$n</font>"?>. <?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <font color="green" font="12px">&nbsp;  &nbsp;正确答案</font>
                    <?php }?>
                    <br/>
                <?php $mark++;
                    }$n++;}?>
<script>
    $(document).ready(function(){
    var isLast = <?php echo $isLast?>;
    if(isLast == 1)
    window.wxc.xcConfirm("已是最后一题", window.wxc.xcConfirm.typeEnum.info);
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


