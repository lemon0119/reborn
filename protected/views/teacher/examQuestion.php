<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
    <div class="hero-unit">
        <?php 
        $n=1;
        $m=0;
        $j=0;
            echo "<h2>简答题</h2>"; 
            foreach ($works  as $k=>$work){ 
                   if($choiceAnsWork[$k]!="no1"){
                        $uAns = $choiceAnsWork[$k];
                        $ii=$j++;
                     }else{
                         $uAns = "";
                     }     
                     if($uAns == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    }
                echo $n.". ";
                echo $work['requirements'];
                echo '<br/>';
                echo '<div class=\'answer-tip-text1\'>作答结果：</div>';
                if($uAns != ""){
                echo '<div style="min-width: 99%" class=\'answer-question\'>'.$choiceAnsWork[$k].'</div>';}
                echo '<div class=\'answer-tip-text2\'>正确答案：</div>';
                echo '<div style="min-width: 99%" class=\'answer-question\'>'.$work['answer'].'</div>';
                echo '<br/>';
                   
                $n++;
                ?>分数:<span class="limit"><?php  $s=$m; echo $exam_exercise[$m++]['score'];?></span><br/>
                得分:
                     <input class="value" type="text" id="input"  onblur="judge(<?php echo $exam_exercise[$s]['score'];?>,this)" style="width: 50px" value ="<?php if($uAns!=""){ echo $ansWork[$ii]['score'];}else{ echo 0;}?>" <?php if($uAns==""){echo 'disabled="disabled"';} ?>> 
                
                    <?php echo "<br/>";}?>
                     <?php if(count($works)>0){?>
                <button onclick="saveScore()" class="btn btn-primary">保存</button>
                 <?php }?>
    </div>

</div>
<script>
    function judge(limit,a){
        var score = parseInt(a.value);
        if(a.value<0 || a.value!=score){
             window.wxc.xcConfirm("分值只能为0、正整数！", window.wxc.xcConfirm.typeEnum.error);
            a.value=0;
        }
        if(a.value>limit){
            window.wxc.xcConfirm("配分超过上限！", window.wxc.xcConfirm.typeEnum.error);
            a.value=0;
        }
    }
    $(document).ready(function(){   
      $("#score").html(<?php echo $score;?>);
    });
//    $('.value').blur(function (){ 
//        for(var i = 0 ; i < <?php echo count($works);?>; ++i){
//            var limit = $(".limit:eq("+i+")").html();
//            var input = $(".value:eq("+i+")").val();
////            console.log('input',parseInt(input));
////            console.log('limit',parseInt(limit));
//         var value1 = $("#input")[0].value;
//            var re = /^([1-9]\d*|[0]{1,1})$/; 
//            if(!re.test(value1)){
//                window.wxc.xcConfirm("分值只能为0、正整数！", window.wxc.xcConfirm.typeEnum.error);
//                $(".value:eq("+i+")").val('');
//            }
//            if(value1 > parseInt(limit)){
//                window.wxc.xcConfirm("配分超过上限！", window.wxc.xcConfirm.typeEnum.error);
//                $(".value:eq("+i+")").val('');
//            }
//        }
//    });
     function saveScore(){
            var scores = new Array();
            var n=0;
            $(".value").each(function(){
                scores[n++]=$(this).val();
            });
            var s=scores.join(",");
            console.log(s);
            var user = {
                type:"question",
                workID:"<?php echo $workID;?>",
                studentID:"<?php echo $studentID;?>",
                accomplish:"<?php echo $accomplish;?>",
                examID:<?php echo $examID;?>,
                score:s
            };
            $.ajax({
                type:"POST",
                url:"./index.php?r=teacher/ajaxExam&&classID=<?php echo $classID?>",
                data:user,
                dataType:"html",
                success:function(html){     
                    window.wxc.xcConfirm('打分成功！', window.wxc.xcConfirm.typeEnum.success,{
                        onOk:function(){
                             $("#ziji").html(html);
                        }
                    });
                },
                error: function(xhr, type, exception){
                  console.log('get Bulletin erroe', type);
                  console.log(xhr.responseText, "Failed");
              }
            });
    }

</script>
