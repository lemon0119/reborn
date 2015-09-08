<?php

class UserController extends Controller
{
    public function actionForgetPassword(){     //忘记密码
        $result ='no';
    	if(isset($_POST['account'])){
            $account=$_POST['account'];
    	    $email=$_POST['email'];
            
            $user = Student::model()->find('userID=?', array($userid_now));
            if(!empty($user)){
                if($user->mail_address !== $account){
                    $result='old error';
                    $this->render('set',['result'=>$result]);
                    return;
                }
                $this->render('updatepassword');
            }
            
        }
	$this->render('forgetpassword');
    }
    protected function setuser() {
        $login_model=new LoginForm;
        $login_model->attributes=$_POST['LoginForm'];
            if($login_model->validate()&&$login_model->login()){  //用户名密码session持久化
                $username_now=$login_model->username;
                $role_now = $login_model->usertype;
                if ($role_now === 'teacher') {
                    $user = (new Teacher())->find("username='$username_now'");
                    $userid = $user->userID;
                    Yii::app()->session['userid_now']=$userid;
                    Yii::app()->session['role_now']=$role_now;
                    $this->redirect(['/teacher/index']);
                } else if ($role_now === 'student'){
                    $user = (new Student())->find("username='$username_now'");
                    $userid = $user->userID;
                    Yii::app()->session['userid_now']=$userid;
                    Yii::app()->session['role_now']=$role_now;
                    $this->redirect(['/student/index']);
                } else if($role_now === 'admin'){   
                    $user = (new Admin())->find("username='$username_now'");
                    $userid = $user->userID;
                    Yii::app()->session['userid_now']=$userid;
                    Yii::app()->session['role_now']=$role_now;
                    $this->redirect(['/admin/index']);
                }
            }
    }
    public function actionLogin(){
        $login_model=new LoginForm;
        if(isset($_POST['LoginForm'])){
            $this->setuser();
        }
        if(isset($_GET['exit'])) {
            $this->clearTrace();
        }
        $this->renderPartial('login',array('login_model'=>$login_model));
    }
    public function actionHideMenu(){
        //记住菜单栏状态
        if(isset($_POST['menuState']))
            Yii::app()->session['menuState'] = $_POST["menuState"];
    }
    protected function clearTrace() {
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
    }

}