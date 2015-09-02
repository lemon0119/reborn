<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>亚伟速录</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link href="<?php echo CSS_URL; ?>login.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet" type="text/css"/>
</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
                <?php $form=$this->beginWidget('CActiveForm');?>
			<h3 class="form-title">用户登录</h3>
                        
			<div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>请输入用户名和密码</span>
			</div>

			<div class="control-group">
				<!--<label class="control-label visible-ie8 visible-ie9">Username</label>-->
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
                                       <?php echo $form->textField($login_model,'username',array('class'=>'m-wrap placeholder-no-fix','placeholder'=>"请输入您的用户名")); ?>
                                       <?php echo $form->error($login_model,'username'); ?>
					</div>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
                                                <!--<input class="m-wrap placeholder-no-fix" type="password" placeholder="请输入您的密码" name="password" />-->
                                                <?php echo $form->passwordField($login_model,'password',array('class'=>'m-wrap placeholder-no-fix','placeholder'=>"请输入您的密码")); ?>
                                                <?php echo $form->error($login_model,'password'); ?>
                                        </div>
				</div>
			</div>
                        
                        <div class="control-group">
                                <div class="controls">
                                    <div class="input-icon left">
                                        <?php $login_model->usertype = 'student';
                                        echo $form->dropDownList($login_model,'usertype',array('empty'=>'请选择身份','student'=>"学生",'teacher'=>'老师','admin'=>"管理员")); 
                                        ?>
                                    </div>
                                </div>
                        </div>
                        
			<div class="form-actions">
				<label class="checkbox">
				<input type="checkbox" name="remember" value="1"/>下次自动登录
				</label>
				<button type="submit" class="btn btn-primary pull-right">
				登录 <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
			<div class="forget-password">
                            <p>
                                <a href="javascript:;" class="" id="forget-password">忘记密码?</a>
			    </p>
			</div>
                <?php $this->endWidget(); ?>
		<!-- END LOGIN FORM -->        

		<!-- BEGIN FORGOT PASSWORD FORM -->

		<form class="form-vertical forget-form" action="http://<?php echo HOST_IP; ?>:8383/front/index.html">

			<h3 class="">输入您的邮箱找回密码</h3>
			<div class="control-group">
				<div class="controls">
					<div class="input-icon left">
                                            <i class="icon-envelope"></i>
                                            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email" />
					</div>
				</div>
			</div>

			<div class="form-actions">
				<button type="button" id="back-btn" class="btn">
				<i class="m-icon-swapleft"></i> 返回
				</button>

				<button type="submit" class="btn green pull-right">
				提交<i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
		</form>

		<!-- END FORGOT PASSWORD FORM -->

		<!-- BEGIN REGISTRATION FORM -->

		<form class="form-vertical register-form" action="index.html">

			<h3 class="">Sign Up</h3>

			<p>Enter your account details below:</p>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">Username</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-user"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Username" name="username"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">Password</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-lock"></i>

						<input class="m-wrap placeholder-no-fix" type="password" id="register_password" placeholder="Password" name="password"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-ok"></i>

						<input class="m-wrap placeholder-no-fix" type="password" placeholder="Re-type Your Password" name="rpassword"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

				<label class="control-label visible-ie8 visible-ie9">Email</label>

				<div class="controls">

					<div class="input-icon left">

						<i class="icon-envelope"></i>

						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email"/>

					</div>

				</div>

			</div>

			<div class="control-group">

				<div class="controls">

					<label class="checkbox">

					<input type="checkbox" name="tnc"/> I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>

					</label>  

					<div id="register_tnc_error"></div>

				</div>

			</div>

			<div class="form-actions">

				<button id="register-back-btn" type="button" class="btn">

				<i class="m-icon-swapleft"></i>  Back

				</button>

				<button type="submit" id="register-submit-btn" class="btn green pull-right">

				Sign Up <i class="m-icon-swapright m-icon-white"></i>

				</button>            

			</div>

		</form>

		<!-- END REGISTRATION FORM -->

	</div>

	<!-- END LOGIN -->

	<!-- BEGIN COPYRIGHT -->

	<div class="copyright">
		2015 &copy;北京亚伟速录科技有限公司.
	</div>
	<script>
		jQuery(document).ready(function() {     
		  App.init();
		  Login.init();
		});
	</script>
</html>