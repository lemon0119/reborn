<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>亚伟速录</title>
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache,no-store, must-revalidate">
    <META HTTP-EQUIV="pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="expires" CONTENT="0"> 
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
    <link href="<?php echo CSS_URL; ?>login.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>reset.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>supersized.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>style_login.css">
</head>

<body oncontextmenu="return false">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
    <div class="page-container">
        <h1>用户登录</h1>
        <div>
            <?php
            $cookie = Yii::app()->request->getCookies();
            if (!empty($cookie['usernamecookie']->value))
                $login_model->username = $cookie['usernamecookie']->value;
            echo $form->textField($login_model, 'username', array('class' => 'm-wrap placeholder-no-fix', 'placeholder' => "请输入您的用户名"));
            ?>
<?php echo $form->error($login_model, 'username'); ?>
        </div>
        <div>
                                <!--<input class="m-wrap placeholder-no-fix" type="password" placeholder="请输入您的密码" name="password" />-->
            <?php echo $form->passwordField($login_model, 'password', array('class' => 'm-wrap placeholder-no-fix','id'=>"pass", 'placeholder' => "请输入您的密码")); ?>
<?php echo $form->error($login_model, 'password'); ?>
        </div>
        <div>
            <?php
            $cookie = Yii::app()->request->getCookies();
            if (!empty($cookie['usertypecookie']->value))
                $login_model->usertype = $cookie['usertypecookie']->value;
            echo $form->dropDownList($login_model, 'usertype', array('empty' => '请选择身份', 'student' => "学生", 'teacher' => '老师', 'admin' => "管理员"),array('style'=>'width:285px;position: relative;top:15px;color:#000;border-color:#000;background-color: #fff;'));
            ?>
        </div>
        <label style="position: relative;left: 30px;" class="checkbox">
            <?php
            $cookie = Yii::app()->request->getCookies();
            if (!empty($cookie['remcookie']->value))
                echo $form->checkBox($login_model, 'rememberMe', array('checked' => 'checked'));
            else
                echo $form->checkBox($login_model, 'rememberMe', array('checked' => ''));
            ?>
            <p style="position: relative;right: 90px;top: 15px;">记住用户名和身份</p>
        </label>
        <button id="submit" type="submit">登录</button>
        <div class="forget-password">
            <p>
                <a href="./index.php?r=user/forgetpassword" class="" id="forget-password" style="position: relative;top: 30px;left: 95px;">忘记密码?</a>
            </p>
            <div class="connect">
                <p style="margin-right: 500px;margin-top:20px;">2015 &copy;南京兜秘网络科技有限公司.</p>
            </div>
        </div>
        <?php $this->endWidget(); ?>

        <!-- Javascript -->
        <script src="<?php echo JS_URL; ?>supersized.3.2.7.min.js"></script>
        <script src="<?php echo JS_URL; ?>supersized-init.js"></script>
        <script>
            $(document).ready(function(){
                  document.getElementById("pass").focus();
            });
            window.onload = function ()
            {
                
                $(".connect p").eq(0).animate({"left": "0%"}, 600);
                $(".connect p").eq(1).animate({"left": "0%"}, 400);
            }
        </script>
</body>

</html>