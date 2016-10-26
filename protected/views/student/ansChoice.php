<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9" style="height:570px; overflow:auto; border:0px;">
    <div class="hero-unit">
        <?php 
        echo '<h2>选择题</h2>';
        $SNum = 0;
            foreach ($exercise['choice'] as $value){
                $right = $value['answer'];
                if(isset($ansChoice[$value['exerciseID']])){
                $uAns = $ansChoice[$value['exerciseID']];
                }else{
                    $uAns ="";
                }
?>
        
        <?php   echo ($SNum+1).'. ';
                echo $value['requirements'];
                echo '<br/>';
                $opt = $value['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?>>&nbsp <?php if($mark === $right) echo "<font color=green>$mark.$aOpt</font>"; else echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?><font color="green" font="12px">&nbsp;  &nbsp;正确答案</font>
                    <?php }?>
                    <br/>
                <?php $mark++;}?>
        <?php $SNum++;}?>
    </div>
</div>
<script>
$(document).ready(function(){
    
    $("li#li-choice").attr('class','active');
});
</script>