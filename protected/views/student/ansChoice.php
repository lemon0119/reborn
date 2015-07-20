<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'ansSideBar.php';
?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="span9">
    <div class="hero-unit">
        <?php $SNum = 0;
            foreach ($exercise['choice'] as $value){
                $right = $value['answer'];
                $uAns = $ansChoice[$value['exerciseID']];?>
        <div class="<?php if($uAns === $right) echo 'answer-right'; else echo 'answer-wrong';?>"></div>
        <?php   echo ($SNum+1).'. ';
                echo $value['requirements'];
                echo '<br/>';
                $opt = $value['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <span class='answer-check' id="answer-check-<?php echo $SNum;?>"></span>
                    <?php }?>
                    <br/>
                <?php $mark++;}?>
                    <button id = 'view-answer-<?php echo $SNum;?>' class="btn btn-primary">显示答案</button>
        <?php $SNum++;}?>
    </div>
</div>
<script>
$(document).ready(function(){
    
    $("li#li-choice").attr('class','active');
    
    $(function(){
        $('.answer-check').hide();
    });
    
    $('button.btn').click(function(){
        var id = $(this).attr('id');
        var index = id.substring('view-answer-'.length);
        var checkID = '#answer-check-'+index;
        $(checkID).toggle(200);
        if($(this).html() === '显示答案')
            $(this).html('隐藏答案');
        else
            $(this).html('显示答案');
    });
});
</script>