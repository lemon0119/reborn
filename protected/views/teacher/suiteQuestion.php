<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
    <div class="hero-unit"style="height: 480px;overflow:auto">
        <?php 
        echo '<h2>简答题</h2>';
         $n=1;
          foreach ($works  as $k=>$work){ 
                   if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                echo "<font>$n </font>.";
                echo $work['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                echo '<div class=\'answer-question\'>'.$ansWork['answer'].'</div>';
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
          $n++;}?>

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
            type:"question",
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
