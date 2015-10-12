<div id="ziji">
    <div class="hero-unit">
                <?php if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
        <table border = '0px' width="100%">
            <tr>
                <td width = '100px' align='center'><?php echo $work['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$ansWork['ratio_correct'] * 100);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>学生答案：</div>
                    <div ><?php echo error_log($ansWork['answerID']."recordID:".$ansWork['recordID']."type:".$ansWork['type']); $ansWork['answer'];?></div>
                </td>
            </tr>
        </table>
</div>
    配分:<?php echo $exam_exercise['score'];?>
   得分:<input teyp="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button>
</div>
<script>
     $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
       if(<?php echo $isLast?> == 1)
        {
            window.wxc.xcConfirm("已是最后一题", window.wxc.xcConfirm.typeEnum.info);
            return ;
        }
    });
    
     function nextWork(answerID,recordID,examID,exerciseID){
        var user = {
            recordID:recordID,
            type:"key",
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

