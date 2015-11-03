<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
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
                <td width = '50%;' align='center'><?php echo "<font>$m</font>";?>. <?php echo $work['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$ansWork['ratio_correct'] * 100);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>学生答案：</div>
                    <div ><?php echo $ansWork['answer'];?></div>
                </td>
            </tr>
        </table>
    </div>
<button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $suite_exercise['suiteID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button> 
</div>
<script>
    $(document).ready(function(){   
       if(<?php echo $isLast?> == 1)
        {
            window.wxc.xcConfirm("已是最后一题", window.wxc.xcConfirm.typeEnum.info);
            return ;
        }
    });
     function nextWork(answerID,recordID,suiteID,exerciseID){
        var user = {
            answerID:answerID,
            recordID:recordID,
            type:"key",
            suiteID:suiteID,
            exerciseID:exerciseID
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxChoice&&m=<?php $m++;echo $m;?>",
          data:user,
          dataType:"html",
          success:function(html){
              $("#ziji").html(html);
          }
      })
    }
</script>

