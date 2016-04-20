<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<?php
if (isset(Yii::app()->session['userid_now']) && Yii::app()->session['role_now'] == 'teacher') {
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
            <script src="<?php echo JS_URL; ?>jquery.min.js" ></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js" ></script>
            <script src="<?php echo JS_URL; ?>site.js" ></script>
            <!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
            <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
            <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js" ></script>
            <script src="<?php echo XC_Confirm; ?>js/xcConfirm.js"></script>
            <!--            -->
                        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        </head>
        <body>
            <div class="container">

                <?php if(!isset($_GET['nobar'])){?>
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="brand" href="./index.php?r=teacher/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li class="dropdown">
                                        <a href="#"  id ="startclass" data-toggle="dropdown" >课 堂</a>
                                        <ul class="dropdown-menu">
                                            <?php if(isset($classNameInfo)){
                                                foreach ($classNameInfo as $key => $value): ?>
                                                <li><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $key;?>&&progress=<?php echo $classProgress[$key]; ?>&&on=<?php echo $classProgress[$key]; ?>"><?php echo $value; ?></a></li>
                                            <?php endforeach;} ?>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="topicmanager">题 库</a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-header" style="color:#AAA9A9;">基础知识</li>
                                            <li><a href="./index.php?r=teacher/choiceLst">选择</a></li>
                                            <li><a href="./index.php?r=teacher/fillLst">填空</a></li>
                                            <li><a href="./index.php?r=teacher/questionLst">简答</a></li>
                                            <li class="divider"></li>
                                            <li class="nav-header" style="color:#AAA9A9;">打字练习</li>
                                            <li><a href="./index.php?r=teacher/keyLst">键打练习</a></li>
                                            <li><a href="./index.php?r=teacher/lookLst">看打练习</a></li>
                                            <li><a href="./index.php?r=teacher/listenLst">听打练习</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="homework">作 业</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./index.php?r=teacher/assignWork&&progress=<?php if(isset($classProgress)) echo $classProgress[$key]; ?>">布置作业</a></li>
                                            <li><a href="./index.php?r=teacher/stuWork">学生作业</a></li>
                                        </ul>
                                    </li>
                                     <li class="dropdown">
                                        <a href="#"  data-toggle="dropdown" id="exammanager">考 试</a>
                                        <ul class="dropdown-menu">
                                             <li><a href="./index.php?r=teacher/assignExam">试卷管理</a></li>
                                            <li><a href="./index.php?r=teacher/stuExam">批改试卷</a></li>
                                        </ul>
                                    </li>  
                                    <li><a id ="exam_statistics" href="#"  data-toggle="dropdown">统 计</a>
                                        <ul class="dropdown-menu" style="left:650px;top:54px;">
                                            <?php if(isset($classNameInfo)){
                                                foreach ($classNameInfo as $key => $value): ?>
                                                <li><a  href="./index.php?r=teacher/watchData&&classID=<?php if(isset($key)){echo $key;}?>"><?php echo $value; ?></a></li>
                                            <?php endforeach;} ?>
                                        </ul>
                                    </li>
                                    <li><a id="schedule_manager"  href="./index.php?r=teacher/scheduleDetil">课程表</a></li>
                                    <li><a id="blank_teacher"></a></li>
                                     <li>
                                       <?php if(Tool::teacherNotice() == 0){?>
                                       <a id="stuMail_off" href="./index.php?r=teacher/teacherNotice"></a>
                                       <?php }else {?>
                                          <a id="stuMail_on" href="./index.php?r=teacher/teacherNotice"></a>  
                                       <?php }?>
                                   </li> 
                                        <li class="dropdown">
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
    <?php } ?>
                <div class="row" style="min-height: 700px">
    <?php echo $content; ?>
                </div>
            </div> 
            <div  class="copyright">
                2015 &copy;南京兜秘网络科技有限公司.&nbsp;&nbsp;&nbsp;<a href="#"  class="copyright">法律声明</a><a href="#"  class="copyright">联系我们</a><a href="#"  class="copyright">获得帮助</a>
            </div>
        </body>
    </html>
<?php } else { ?>
    <script>    window.location.href = "./index.php?r=user/login"</script>
<?php } ?>
