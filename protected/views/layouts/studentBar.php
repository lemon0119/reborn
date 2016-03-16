<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<?php
if (isset(Yii::app()->session['userid_now'])&& Yii::app()->session['role_now']=='student' ) {
    ?>
    <?php
    $classID = Student::model()->findClassByStudentID(Yii::app()->session['userid_now']);
    
    ?>
    <html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
            <script src="<?php echo JS_URL; ?>jquery.min.js" ></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js" ></script>
            <script src="<?php echo JS_URL; ?>site.js" ></script>
            <!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js" ></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js"   ></script>
<!--            -->
        </head>
        <body>
            <div class="container">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container" style="width:1300px">
                            <a class="brand" href="./index.php?r=student/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li>
                                        <a id="onlineCourse" href="./index.php?r=student/virtualClass"  >课 堂</a>
                                    </li>
                                    <li>
                                        <a id="practice" href="./index.php?r=student/freePractice"  >练 习</a>
                                    </li>
                                    <li class="dropdown"><a id="myCourse" href="./index.php?r=student/myCourse" >作 业</a>
                                    </li>
                                    <li><a id="courseExam" href="./index.php?r=student/classExam">考 试</a></li>
                                    
                                    <li><a id ="exam_statistics_stu" href="./index.php?r=student/watchData&&classID=<?php echo $classID;?>">统 计</a></li>
                                    <li><a id="suLu" href="./index.php?r=student/suLu">百 科</a></li>
                                    <li><a id="schedule_manager_stu"  href="./index.php?r=student/scheduleDetil">课程表</a></li>
                                    <li><a id="blank_stu"></a></li>
                                     <li>
                                       <?php if(Tool::stuNotice() == 0){?>                                         
                                       <a id="stuMail_off" href="./index.php?r=student/stuNotice"></a>
                                       <?php }else {?>
                                          <a id="stuMail_on" href="./index.php?r=student/stuNotice"></a>  
                                       <?php }?>
                                   </li> 
                                    <li >
                                        <div class="userUI">
                                            <a href="#" id="userUI"  data-toggle="dropdown">
                                                <?php echo Yii::app()->session['userName']; ?><?php echo Yii::app()->session['className']; ?><b class="user_dropdown_logo"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="./index.php?r=student/stuInformation">个人设置</a></li>
                                                <li>
                                                <li><a href="./index.php?r=user/login&exit=1">退出</a> </li>
                                            </ul>   
                                        </div>
                                   </li>
                                </ul>
                               
                            </div>
                        </div>
                    </div>
                </div>
                
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

