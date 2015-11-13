
<?php

require 'examAnsSideBar.php';
?>

<div class="span9">
    <div id="ziji">
    <div class="hero-unit">
        <h2>键位练习</h2>
                <?php if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
        <table border = '0px' width="100%">
            <tr>
                <td width = '50%' align='center'><?php echo $exer['title']?></td>
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
    配分:<?php echo $exam_exercise['score'];?><br/>
   得分:<input teyp="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="saveScore(<?php echo $ansWork['score']?>,<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $exerID;?>)" class="btn btn-primary">保存</button>
</div>
</div>

<script>
     $(document).ready(function(){   
         $("li#li-key-<?php echo $exer['exerciseID'];?>").attr('class','active');
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
          url:"./index.php?r=teacher/ajaxExam&&classID=<?php echo $classID?>",
          data:user,
          dataType:"html",
          success:function(html){  
                  
                  location.reload();
                  console.log("set time");
              },
            error: function(xhr, type, exception){
                window.wxc.xcConfirm(xhr.responseText, window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
      });
      }
    }

</script>


