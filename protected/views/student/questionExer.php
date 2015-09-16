<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 if($isExam == false){ 
require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } 
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveQuestion';
$page = '/index.php?r='.$rout;
$SNum = 0;
?>
<div class="span9">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="question"/>
            <?php 
                $SNum = 0;
                foreach ($exercise['question'] as $value) {
                    echo ($SNum+1).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    echo '<textarea style="width:600px; height:200px;" name = "quest'.$value["exerciseID"].'"></textarea>';
                    echo '<br/>';
                    $SNum++;
                }
            ?>
        </div>
        <?php if(count($exercise['question']) > 0){?>
            <a type="button" class="btn btn-primary btn-large" onclick="formSubmit();" style="margin-left: 200px">提交</a>
        <?php }?>
        <?php 
            $last = Tool::getLastExer($exercise);
            if($last['type'] == 'question'){
        ?>
            <a class="btn btn-large" style="margin-left: 200px" onclick="submitSuite();">提交</a>
        <?php }?>
    </form>
</div>
<script>
$(document).ready(function(){
    $("li#li-question").attr('class','active');
});
function submitSuite(){
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    if(confirm("提交以后，不能重新进行答题，你确定提交吗？")){
        $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){});
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
    }
}
function formSubmit(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      alert(result);
  });
}
</script>