
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<div id="ziji">
<div class="hero-unit" style="height: 480px;overflow:auto">
          <?php
          echo '<h2>选择题</h2>';
           $n=1;
           foreach ($works  as $k=>$work){ 
                $right = $work['answer'];
                if(isset($choiceAnsWork[$k])){
                    $uAns = $choiceAnsWork[$k];
                }else{
                    $uAns="";
                }
                if($uAns == "")
                {
                    echo "<font color=red>未作答</font>";
                    echo '</br>';
                }
               ?> 
        <?php echo "<font>$n</font>"?>. <?php  echo $work['requirements'];
                echo '<br/>';
                $opt = $work['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {?>
                    <input type="radio" disabled <?php if($mark === $uAns) echo 'checked';?> >&nbsp <?php echo $mark.'.'.$aOpt;?>
                    <?php if($mark === $right){?>
                        <font color="green" font="12px">&nbsp;  &nbsp;正确答案</font>
                    <?php }?>
                    <br/>
                <?php $mark++;
                    }$n++;}?>



