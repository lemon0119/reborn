<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<?php
if (isset(Yii::app()->session['userid_now'])) {
    ?>

    <html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
            <!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo XC_Confirm; ?>js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--            -->
        </head>
        <body>
            <div class="container">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="brand" href="./index.php?r=student/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li class="dropdown">
                                        <a id="onlineCourse" href="#"  data-toggle="dropdown"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=student/virtualClass">虚拟课堂</a></li>
                                            <li><a href="./index.php?r=student/classwork">课堂作业</a></li>
                                        </ul>
                                    </li>
                                    <li><a id="myCourse" href="./index.php?r=student/myCourse"></a></li>
                                    <li><a id="courseExam" href="./index.php?r=student/classExam"></a></li>
                                    <li><a id="blank_stu"></a></li>
                                    <li >
                                        <div class="userUI">
                                            <a href="#" id="userUI"  data-toggle="dropdown">
                                                <?php echo Yii::app()->session['userName']; ?><b class="user_dropdown_logo"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="./index.php?r=student/set">设置</a></li>
                                                <li>
                                                    <a href="./index.php?r=student/headPic">头像</a></li>
                                                <li><a href="./index.php?r=user/login&exit=1">退出</a> </li>
                                            </ul>   
                                        </div>
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

<?php } else { ?>
    <script>    window.location.href = "./index.php?r=user/login"</script>
<?php } ?>
