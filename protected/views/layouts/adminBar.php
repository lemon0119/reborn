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
                <script src="<?php echo JS_URL;?>jquery.min.js"></script>
                <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
                <script src="<?php echo JS_URL;?>site.js"></script>
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body>
            <div class="container">
                    <div class="navbar">
                            <div class="navbar-inner">
                                    <div class="container">
                                        <a class="brand" href="./index.php?r=admin/index">亚伟速录</a>
                                            <div class="nav-collapse">
                                                    <ul class="nav">
                                                        <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">人员管理<b class="caret"></b></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="./index.php?r=admin/stuLst">学生管理</a></li>
                                                                <li><a href="./index.php?r=admin/teaLst">老师管理</a></li>
                                                            </ul>
                                                        </li>
                                                            <li>
                                                                <a href="./index.php?r=admin/classLst">班级管理</a>
                                                            </li>
                                                            <li class="dropdown">
                                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">题库管理<b class="caret"></b></a>
                                                                    <ul class="dropdown-menu">
                                                                        <li class="nav-header">基础知识</li>
                                                                        <li><a href="./index.php?r=admin/choiceLst">选择</a></li>
                                                                        <li><a href="./index.php?r=admin/fillLst">填空</a></li>
                                                                        <li><a href="./index.php?r=admin/questionLst">简答</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="./index.php?r=admin/listenLst">听打练习</a></li>
                                                                        <li><a href="./index.php?r=admin/lookLst">看打练习</a></li>
                                                                        <li><a href="./index.php?r=admin/keyLst">键位练习</a></li>
                                                                    </ul>
                                                            </li>
                                                            <li><a href="./index.php?r=admin/courseLst">课程管理</a></li>
                                                    </ul>
                                                    <ul class="nav pull-right">
                                                        <li class="dropdown">
                                                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">管理员<b class="caret"></b></a>
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

