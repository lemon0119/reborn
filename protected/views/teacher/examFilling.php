<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
        <div class="hero-unit">
            <h2>填空题</h2>
            <?php
            $n=1;
            foreach ($works  as $k=>$work){ 
                    $str = $work['requirements'];
                    if(isset($choiceAnsWork[$k])){
                        $answer = $choiceAnsWork[$k];
                        $uAns = $choiceAnsWork[$k];  
                        $ansArr = explode('$$', $answer);
                     }else{
                         $uAns = "";
                     }     
                     if($uAns == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                    echo $n.". ";
                    echo $str.'<br/>';
                    $i = 1;
                    echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                    echo '<div>';
                    while($i < count($ansArr)+1){
                        echo '('.$i.') ';
                        echo '<div class=\'answer-filling\'>'.$ansArr[$i-1].'</div>';
                        if(!($i%3))
                            echo '<br/>';
                        $i++;
                    }
                    echo '</div>';
                    echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                    echo '<div>';
                    $right = $work['answer'];
                    $rightArr = explode('$$', $right);
                    $i = 1;
                    while($i < count($rightArr)+1){
                        echo '('.$i.') ';
                        echo '<div class=\'answer-filling\'>'.$rightArr[$i-1].'</div>';
                        if(!($i%3))
                            echo '<br/>';
                        $i++;
                    }
                    echo '</div>';
                    echo '<br/>';
                    $n++;
            }?>
        </div>
   配分:<?php echo $exam_exercise['score'];?><br/>
   得分:<input teyp="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="saveScore(<?php echo $ansWork['score']?>,<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存</button>
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
        var user = {
            recordID:recordID,
            type:"filling",
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

