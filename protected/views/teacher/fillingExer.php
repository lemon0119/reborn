<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'suiteSideBar.php';
$SNum = 0;
?>
<div class="span9">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="filling"/>
            <?php 
                $SNum = 0;
                foreach ($exercise['filling'] as $value) {
                    echo ($SNum+1).'. ';
                    $str = $value['requirements'];
                    $answer = $value['answer'];
                    $ansArr = explode('$$', $answer);
                    echo $str.'<br/>';
                    $i = 1;
                    while($i < count($ansArr)+1){
                        echo '('.$i.') ';
                        echo '<input type="text" name="'.$i.'filling'.$value["exerciseID"].'"></input><br/>';
                        $i++;
                    }
                    echo '<br/>';
                    $SNum++;
                }
            ?>
        </div>
</div>
<script>
$(document).ready(function(){
    $("li#li-filling").attr('class','active');
});
</script>