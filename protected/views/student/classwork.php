<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo EXER_CSS_URL;?>bootstrap-responsive.min.css" rel="stylesheet">
<link href="<?php echo EXER_CSS_URL;?>site.css" rel="stylesheet">
<!-- BEGIN CONTAINER -->
            <?php 
                if(isset($noExer))
                    echo "<div class=\"span9\"><div class=\"hero-unit\"><h3>现在没有开放的课堂练习</h3></div></div>";
                else if(isset(Yii::app()->session['exerType'])) {
                    $exerType = Yii::app()->session['exerType'];
                    if($exerType == 'lookExer')
                        require  Yii::app()->basePath."\\views\\student\\lookExer.php";
                    else if($exerType == 'listenExer')
                        require  Yii::app()->basePath."\\views\\student\\listenExer.php";
                    else if($exerType == 'keyExer')
                        require  Yii::app()->basePath."\\views\\student\\keyExer.php";
                    else if ($exerType == 'knlgExer')
                        require  Yii::app()->basePath."\\views\\student\\knlgExer.php";
                }
            ?>
<!-- END CONTAINER -->