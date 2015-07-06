<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo EXER_CSS_URL;?>bootstrap-responsive.min.css" rel="stylesheet">
<link href="<?php echo EXER_CSS_URL;?>site.css" rel="stylesheet">

<!-- BEGIN CONTAINER -->
<div class="row" id="cont">
  <?php require  Yii::app()->basePath."\\views\\student\\exerMenu.php";  ?>
  <?php 
      if(!isset(Yii::app()->session['suiteID']))
          echo "<div class=\"span9\"><div class=\"hero-unit\"><h3>请在左侧选择习题</h3></div></div>";
      if(isset(Yii::app()->session['exerType'])) {
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
</div>
<!-- END CONTAINER -->


