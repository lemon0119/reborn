<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    require 'suiteSideBar.php';
    $SNum = 0;
?>
<div class="span9" style="height:480px; overflow:auto; border:1px solid #000000;">
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
   
</div>
 
<script>
$(document).ready(function(){
    $("li#li-choice").attr('class','active');
});
</script>