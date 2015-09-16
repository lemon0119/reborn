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
 <h3 >课 堂 作 业</h3>
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
        <a type="button" class="btn btn-primary btn-large" onclick="formSubmit();" style="margin-left: 100px">保存</a>
        <a  href="./index.php?r=student/classwork" type="button" class="btn btn-primary btn-large" style="margin-left: 200px">退出</a>
            <!--<a class="btn btn-large" style="margin-left: 200px">暂存</a>-->
        <?php }?>
    </form>
</div>
<script>
$(document).ready(function(){
    $("li#li-question").attr('class','active');
});
function formSubmit(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      alert(result);
      window.location.href = './index.php?r=student/clswkOne&&suiteID=<?php echo $workID;?>';   
  });
}
</script>