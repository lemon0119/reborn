
<html lang="en"><!--<![endif]--> 
	<head>
		<meta charset="utf-8">
		<title>Profile - Akira</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
                <script src="<?php echo JS_URL;?>jquery.min.js"></script>
                <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
                <script src="<?php echo JS_URL;?>site.js"></script>
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body>
<div class="span9">
    <h3>忘记密码</h3>
    <form id="myForm" method="post" action="./index.php?r=user/set"> 
   
            <div class="control-group">
                <label class="control-label" for="input01">账号</label>
                <div class="controls">
                        <input name="account" type="text" class="input-xlarge" id="input01" style="height: 30px;"/>
                </div>
                <label class="control-label" for="input02">邮箱</label>
                <div class="controls">
                        <input name="email" type="text" class="input-xlarge" id="input02" onblur="test()" style="height: 30px;"/>
                </div>
                <div class="controls">
                    <input name="yzm" type="text" class="input-xlarge" id="input02" hidden="true" style="width: 150px;height: 30px;"/>
                    <input name="yzm" type="text" class="input-xlarge" id="input02" hidden="true" style="width: 120px;height: 30px;"/>
                </div>
                
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">确认</button> 
                <a href="./index.php?r=student/<?php echo Yii::app()->session['lastUrl'];?>&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">取消</a>
            </div>
        
    </form>   
</div>
            </body>
</html>






