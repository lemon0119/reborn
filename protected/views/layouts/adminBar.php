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
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo XC_Confirm; ?>js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--            -->
             <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        </head>
        <body>
            <div class="container">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="brand" href="./index.php?r=admin/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li class="dropdown">
                                        <a href="#" id="person_manager" data-toggle="dropdown"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=admin/stuLst">学生管理</a></li>
                                            <li><a href="./index.php?r=admin/teaLst">老师管理</a></li>
                                        </ul>
                                    </li>
                                     <li><a id="course_manager"  href="./index.php?r=admin/courseLst"></a></li>
                                    <li>
                                        <a id="class_manager" href="./index.php?r=admin/classLst"></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" id="topic_manager" data-toggle="dropdown"></a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-header">基础知识</li>
                                            <li><a href="./index.php?r=admin/choiceLst">选择</a></li>
                                            <li><a href="./index.php?r=admin/fillLst">填空</a></li>
                                            <li><a href="./index.php?r=admin/questionLst">简答</a></li>
                                            <li class="divider"></li>
                                            <li><a href="./index.php?r=admin/keyLst">键位练习</a></li>
                                            <li><a href="./index.php?r=admin/lookLst">看打练习</a></li>
                                            <li><a href="./index.php?r=admin/listenLst">听打练习</a></li>
                                        </ul>
                                    </li>
                                   
                                     <li><a id="schedule_manager"  href="./index.php?r=admin/schedule"></a></li>
                                      <li><a id="notice_manager" href="./index.php?r=admin/noticeLst"></a></li>
                                      <li><a id="blank_admin"></a></li>
                                    <li >
                                        <div class="userUI">
                                            <a href="" id="userUI" data-toggle="dropdown">
                                                <?php echo Yii::app()->session['userName']; ?>
                                                <b class="user_dropdown_logo"></b>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="./index.php?r=admin/set">设置</a>
                                                    <a href="./index.php?r=user/login&exit=1">退出</a>
                                                </li>
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
            <div  class="copyright">
		2015 &copy;南京兜秘网络科技有限公司.
	</div>
        </body>
        
    </html>
<?php } else { ?>
    <script>    window.location.href = "./index.php?r=user/login"</script>
<?php } ?>

