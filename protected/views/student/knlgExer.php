<?php 
    $BNum = 0;
    $SNum = 0;
?>
<div class="span9">
        <div class="hero-unit">
            <h3><?php 
                if(count($choice) > 0){
                    $BNum++;
                    echo '一、选择题';
                }
                $host = Yii::app()->request->hostInfo;
                $path = Yii::app()->request->baseUrl;
                $rout = $_REQUEST['r'];
                $page = '/index.php?r='.$rout;
            ?></h3>
            <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
                <input name ="qType" type="hidden" value="knlg"/>
            <?php 
                foreach ($choice as $value) {
                    echo ($SNum+1).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    $opt = $value['options'];
                    $optArr = explode("$$",$opt);
                    $mark = 'A';
                    foreach ($optArr as $aOpt) {
                        echo '<input type="radio" value="'.$mark.'" name="choice'.$value["exerciseID"].'">&nbsp'.$mark.'.'.$aOpt.'</input>&nbsp&nbsp';
                        $mark++;
                    }
                    echo '<br/>';
                    $SNum++;
                }
            ?>
            <h3><?php
                if(count($filling) > 0){
                    $BNum++;
                    if($BNum == 1)
                        echo '一';
                    else if($BNum == 2)
                        echo '二';
                    echo '、填空题';
                }
            ?></h3>
            <?php 
                $SNum = 0;
                foreach ($filling as $value) {
                    echo ($SNum+1).'. ';
                    $str = $value['requirements'];
                    $strArry = explode("$$",$str);
                    echo $strArry[0];
                    $i = 1;
                    while($i < count($strArry)){
                        echo '<input type="text" name="'.$i.'filling'.$value["exerciseID"].'"></input>';
                        echo $strArry[$i];
                        $i++;
                    }
                    echo '<br/>';
                    $SNum++;
                }
            ?>
            <h3><?php
                if(count($question) > 0){
                    $BNum++;
                    if($BNum == 1)
                        echo '一';
                    else if($BNum == 2)
                        echo '二';
                    else if($BNum == 3)
                        echo '三';
                    echo '、简答题';
                }
            ?></h3>
            <?php 
                $SNum = 0;
                foreach ($question as $value) {
                    echo ($SNum+1).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    echo '<textarea style="width:600px; height:200px;" name = "quest'.$value["exerciseID"].'"></textarea>';
                    echo '<br/>';
                    $SNum++;
                }
            ?>
            <?php if($BNum > 0){//this.submit()?>
                <a type="button" class="btn btn-primary btn-large" onclick="document.getElementById('klgAnswer').submit();">提交</a><a class="btn btn-large" onclick="">暂存</a>
            <?php }?>
            </form>
        </div>
</div>


