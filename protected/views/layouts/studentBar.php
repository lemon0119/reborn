<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="zh-cn"><!--<![endif]--> 
	<head>
		<meta charset="utf-8">
		<title>亚伟速录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
                
                <script src="<?php echo JS_URL;?>jquery.min.js"></script>
                <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
                <script src="<?php echo JS_URL;?>site.js"></script>
	</head>
	<body>
            <div class="container">
                    <div class="navbar">
                            <div class="navbar-inner">
                                    <div class="container">
                                        <a class="brand" href="./index.php?r=student/index">亚伟速录</a>
                                            <div class="nav-collapse">
                                                    <ul class="nav">
                                                        <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">在线课堂<b class="caret"></b></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="./index.php?r=student/webrtc">虚拟课堂</a></li>
                                                                <li><a href="./index.php?r=student/classwork">课堂作业</a></li>
                                                            </ul>
                                                        </li>
                                                            <li><a href="./index.php?r=student/classLst">我的课程</a></li>
                                                            <li><a href="./index.php?r=student/courseLst">课堂考试</a></li>
                                                    </ul>
                                                    <ul class="nav pull-right">
                                                        <li class="dropdown">
                                                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                                                                        <?php echo Yii::app()->user->name; ?><b class="caret"></b>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                            <li>
                                                                                    <a href="./index.php?r=user/login&exit=1">退出</a>
                                                                            </li>
                                                                    </ul>                    
                                                        </li>
                                                    </ul>
                                            </div>
                                    </div>
                            </div>
                    </div>
                    <div class="row">
                        <?php echo $content; ?>
                    </div>
            </div>
	</body>
</html>

