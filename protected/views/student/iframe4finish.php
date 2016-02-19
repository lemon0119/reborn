<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
    <div id="span" class="hero-unit" align="center">
         <div style="width: 700px">
          <button id="close_exercise"  style="margin-left:30px;" class="fr btn btn-primary">关闭</button>
        </div>
        <div id="Analysis">
        <h3><span style="color: #f46500;"><?php echo $classExercise['title'] ?>&nbsp;</span>已完成</h3>
        </div>
    </div>
</body>
<script>
    $("#close_exercise").click(function () {
        window.parent.closeClassExercise();
    });
</script>