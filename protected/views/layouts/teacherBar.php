<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<?php
if (isset(Yii::app()->session['userid_now'])) {
    ?>

    <?php
    $class = Teacher::model()->getClassNow();
    foreach ($class as $key => $value) {
        $classNameInfo[$value['classID']] = $value['className'];
        $classProgress[$value['classID']] = $value['currentLesson'];
    }
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
                            <a class="brand" href="./index.php?r=teacher/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li class="dropdown">
                                        <a href="#"  id ="startclass" data-toggle="dropdown" ></a>
                                        <ul class="dropdown-menu">
                                            <?php if(isset($classNameInfo)){
                                                foreach ($classNameInfo as $key => $value): ?>
                                                <li><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $key; ?>&&progress=<?php echo $classProgress[$key]; ?>&&on=<?php echo $classProgress[$key]; ?>"><?php echo $value; ?></a></li>
                                            <?php endforeach;} ?>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="topicmanager"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=teacher/choiceLst">选择</a></li>
                                            <li><a href="./index.php?r=teacher/fillLst">填空</a></li>
                                            <li><a href="./index.php?r=teacher/questionLst">简答</a></li>
                                            <li class="divider"></li>
                                            <li><a href="./index.php?r=teacher/listenLst">听打练习</a></li>
                                            <li><a href="./index.php?r=teacher/lookLst">看打练习</a></li>
                                            <li><a href="./index.php?r=teacher/keyLst">键位练习</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="homework"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=teacher/assignWork">布置作业</a></li>
                                            <li><a href="./index.php?r=teacher/stuWork">学生作业</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="exammanager"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=teacher/assignExam">试卷管理</a></li>
                                            <li><a href="./index.php?r=teacher/stuExam">批改试卷</a></li>
                                        </ul>
                                    </li>
                                    <li><a id="blank_admin"></a></li>
                                    <li >
                                        <div class="userUI">
                                        <a href="" data-toggle="dropdown" id="userUI" >
                                             <?php echo Yii::app()->session['userName']; ?><b class="user_dropdown_logo"></b>
                                        </a>
                                       
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=teacher/set">设置</a></li>
                                            <li><a href="./index.php?r=user/login&exit=1">退出</a></li>
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
