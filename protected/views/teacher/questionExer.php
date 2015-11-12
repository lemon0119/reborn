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
            <input name ="qType" type="hidden" value="question"/>
            <?php 
                $SNum = 0;
                foreach ($exercise['question'] as $value) {
                    echo ($SNum+1).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    echo '<textarea disabled="disabled" style="width:600px; height:200px;" name = "quest'.$value["exerciseID"].'"></textarea>';
                    echo '<br/>';
                    $SNum++;
                }
            ?>
        </div>
</div>
<script>
$(document).ready(function(){
    $("li#li-question").attr('class','active');
});
</script>