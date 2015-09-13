<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 if($isExam == FALSE){ 
require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } 
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveChoice';
$page = '/index.php?r='.$rout;
$SNum = 0;
?>
<div class="span9">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
        <input name ="qType" type="hidden" value="choice"/>
        <?php 
            foreach ($exercise['choice'] as $value) {
                echo ($SNum+1).'. ';
                echo $value['requirements'];
                echo '<br/>';
                $opt = $value['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {
                    echo '<input type="radio" value="'.$mark.'" name="choice'.$value["exerciseID"].'">&nbsp'.$mark.'.'.$aOpt.'</input><br/>';
                    $mark++;
                }
                echo '<br/>';
                $SNum++;
            }
        ?>
        </div>
        <?php if(count($exercise['choice']) > 0){//this.submit()?>
            <a type="button" class="btn btn-primary btn-large" onclick="formSubmit();" style="margin-left: 200px">提交</a>
            <!--<a class="btn btn-large" style="margin-left: 200px">暂存</a>-->
        <?php }?>
    </form>
</div>
<script>
$(document).ready(function(){
    $("li#li-choice").attr('class','active');
});
function formSubmit(){
    $('#klgAnswer').submit();
        /*
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      alert(result);
  });
  */
}
</script>