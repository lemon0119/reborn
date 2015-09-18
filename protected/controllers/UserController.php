<?php

class UserController extends Controller
{
    public function actionUpdatePassword(){      //重置密码
        $result ='no';
    	if(isset($_POST['new1'])){
    		$new1=$_POST['new1'];
    		$defnew=$_POST['defnew'];
                $type=$_GET['type'];
                if($type==='0')
                    $user = Student::model()->find('userID=?',$_GET['userid']);
    		else if($type==='1')
                    $user = Teacher::model()->find('userID=?',$_GET['userid']);
                else if($type==='2')
                    $user = Teacher::model()->find('userID=?',$_GET['userid']);
                if($user){
                    $user->password=md5($new1);
                    $result=$user->save();
                }else{
                    $result='0';
                }
    		$this->render('forgetpassword',['result'=>$result]);
                return ;
    	}
    	
    	$this->render('updatepassword',['result'=>$result,'userid'=>$_GET['userid'],'type'=>$_GET['type']]);
    }
    public function actionForgetPassword(){     //忘记密码
        $result ='no';
        $account='0';
        $type='0';
    	if(isset($_POST['account'])){
            $account=$_POST['account'];
    	    $email=$_POST['email'];
            $user=  Student::model()->find("userName ='$account'");
            if($user==null)
            {
                $type='1';
                $user= Teacher::model()->find("userName ='$account'");
            }
            if($user==null)
            {
                $type='2';
                $user= Admin::model()->find("userName ='$account'");
            }
            $userid=$user['userID'];
            if($user!=null){
                if($user['mail_address'] !== $email){
                    $result="email error";
                    $this->render('forgetpassword',['result'=>$result]);
                    return;
                }
                $this->render('updatepassword',['userid'=>$userid,'type'=>$type]);
                return ;
            }
            
        }
	$this->render('forgetpassword',['result'=>$result]);
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