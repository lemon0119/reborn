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
            <style>
            a.disabled {
   pointer-events: none;
   cursor: default;
}
        </style>    
        </head>
        <body>
             <div class="container">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container" style="width:1300px">
                            <a class="brand" style='   pointer-events: none; cursor: default;' href="./index.php?r=student/index"></a>
                            <div class="nav-collapse">
                                <ul class="nav">
                                    <li>
                                        <a class="disabled" id="onlineCourse" href="./index.php?r=student/virtualClass"  >课 堂</a>
                                    </li>
                                    <li>
                                        <a class="disabled" id="practice" href="./index.php?r=student/freePractice"  >练 习</a>
                                    </li>
                                    <li class="dropdown"><a class="disabled" id="myCourse" href="./index.php?r=student/myCourse" >作 业</a>
                                    </li>
                                    <li><a class="disabled" id="courseExam" href="./index.php?r=student/classExam">考 试</a></li>

                                    <li><a class="disabled" id ="exam_statistics_stu" href="">统 计</a></li>
                                    <li><a class="disabled" id="suLu" href="./index.php?r=student/suLu">百 科</a></li>
                                    <li><a class="disabled" id="schedule_manager_stu"  href="./index.php?r=student/scheduleDetil">课程表</a></li>
                                    <li><a class="disabled" id="blank_stu"></a></li>
                                    <li>
                                        <?php if (Tool::stuNotice() == 0) { ?>                                         
                                            <a id="stuMail_off" class="disabled" href="./index.php?r=student/stuNotice"></a>
                                        <?php } else { ?>
                                            <a id="stuMail_on" class="disabled" href="./index.php?r=student/stuNotice"></a>  
                                        <?php } ?>
                                    </li> 
                                    <li >
                                        <div class="userUI">
                                            <a class="disabled" href="#" id="userUI"  data-toggle="dropdown" title="<?php echo Yii::app()->session['userName']; ?>">
                                                
                                                    <?php $name=Yii::app()->session['userName'];
                                                            if (Tool::clength($name) <= 3)
                                                                echo $name;
                                                            else
                                                                echo Tool::csubstr($name, 0, 3) . "...";
                                                    ?>
                                                    <?php echo Yii::app()->session['className']; ?><b class="user_dropdown_logo"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="./index.php?r=student/stuInformation">个人设置</a></li>
                                                <li>
                                                <li><a href="./index.php?r=user/login&exit=1&usertype=student">退出</a> </li>
                                            </ul>   
                                        </div>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

