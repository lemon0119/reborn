
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div class="hero-unit">
          <?php
                $right = $work['answer'];
                $uAns = $ansWork['answer'];
                if($uAns == "")
                {
                    echo "<font color=red>未作答</font>";
                    echo '</br>';
                }
                else{
                ?>
        <div class="<?php if($uAns === $right ) echo 'answer-right'; else echo 'answer-wrong';?>"></div>
        <?php }?>
        <?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <span class='answer-check'></span>
                    <?php }?>
                    <br/>
                <?php $mark++;}?>
</div>
<script>
    $(document).ready(function(){
    var isLast = <?php echo $isLast?>;
    if(isLast == 1)
        alert("已是最后一题");
    });
</script>


