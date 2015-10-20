<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL;?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL;?>exerJS/accounting.js"></script>
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
                    <div class='answer-tip-text1'>作答结果：</div>
                    <div id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()"><?php echo ($ansWork['answer']);?></div>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()"><?php echo ($work['content']);?></div>
                </td>
            </tr>
        </table>
    </div>
    配分:<?php echo $exam_exercise['score'];?>
   得分:<input teyp="text" id="input" style="width: 50px" value ="<?php echo $ansWork['score']?>" >      
   <button onclick="nextWork(<?php if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php echo $exam_exercise['examID'];?>,<?php echo $work['exerciseID'];?>)" class="btn btn-primary">保存/下一题</button>
</div>
<?php
    if(isset(Yii::app()->session['type'])){
        $type = Yii::app()->session['type'];
        echo "<script>var type = '$type';</script>"; 
    }
?>
<script type="text/javascript">
    $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
       if(<?php echo $isLast?> == 1)
        {
            window.wxc.xcConfirm("已是最后一题", window.wxc.xcConfirm.typeEnum.info);
            return ;
        }
    });
    function load(){
            var url = "./index.php?r=student/preExer&&type=classwork";
        $("#cont").load(url);
    }
    function createFont(element, color, text){
        var father = document.getElementById(element);
        var f = document.createElement("font");
        f.style = "color:"+color;
        f.innerHTML = text;
        father.appendChild(f);
    }
    function doScrollLeft(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divright.scrollTop = divleft.scrollTop;
    }
    function doScrollRight(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divleft.scrollTop = divright.scrollTop;
    }
    function start(){
        var lcs = new LCS('<?php echo ($work['content']);?>', '<?php echo ($ansWork['answer']);?>');
        if(lcs == null)
            return;
        lcs.doLCS();
        var tem = lcs.getStrOrg(1);
        var ans = lcs.getStrOrg(2);
        var modTem = lcs.getSubString(1);
        var modAns = lcs.getSubString(2);
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        displayTemp('templet', tem, modTem);
        displayTemp('answer', ans, modAns);
    }
    function displayTemp(id, temp, modTem){
        var flag = false;
        var j = 0;
        for(var i = 0; i < modTem.length && i < temp.length; i++){
            if(modTem[i] === '*'){
                if(!flag){
                    flag = true;
                    createFont(id,'#000000',temp.substring(j, i));
                    j = i;
                }
            } else {
                if(flag){
                    flag = false;
                    createFont(id,'#ff0000',temp.substring(j, i));
                    j = i;
                }
            }
        }
        if(j < i){
            if(!flag)
                createFont(id,'#000000',temp.substring(j, i));
            else
                createFont(id,'#ff0000',temp.substring(j, i));
        }
        if(i < temp.length)
            createFont(id,'#ff0000',temp.substr(i));
        if(i < modTem.length)
            createFont(id,'#ff0000',modTem.substr(i));
    }   
   
     function nextWork(answerID,recordID,examID,exerciseID){
       var value1 = $("#input")[0].value;
         var totalscore = <?php echo $exam_exercise['score'];?>;
        if(value1>totalscore){
            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
        }else{
        var user = {
            recordID:recordID,
            type:"look",
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
      });
      }
    }

</script>

