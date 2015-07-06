<?php
class WebrtcController extends Controller{
    public function actionCourse(){
        $classID = $_GET['classid'];
        $course = TbClass::model()->findCourseByClassID($classID);
        $lesson = TbClass::model()->findLessonByCourse($course[0]['courseID']);
        return $this->renderPartial('course',['course'=>$lesson]);
    }
    public function actionSelectCourse(){
        $class = Teacher::model()->getClassNow();
        return $this->renderPartial('selectCourse',['class'=>$class]);
    }
    /*
    public function actionIndex(){
        $userid = Yii::app()->session['userid_now'];
        if (Yii::app()->session['role_now'] == 'student') {
            $user = Student::model()->find("userID=?",[$userid]);
        } else if(Yii::app()->session['role_now'] == 'teacher'){
            $user = Teacher::model()->find("userID=?",[$userid]);
        }else{
            $user = Admin::model()->find("userID=?",[$userid]);
        }
        $userName = $user['userName'];
        $this->render('index',array('userID'=>$userid,'userRole'=>Yii::app()->session['role_now'],'userName'=>$userName));
    }*/
    public function actionIndex(){   
        $user_model = new User;
        $username_now=Yii::app()->user->name;
        $info=$user_model->find("username='$username_now'");
        $this->render('index',array('info'=>$info));
    }
//        public function actionDianbo()
//        {       $user_model = new User;
//                $username_now=Yii::app()->user->name;
//                $info=$user_model->find("username='$username_now'");
//                $this->render('dianbo',array('info'=>$info));
//        }
//        public function actionBroadcast(){
//            $user_model = new User;
//            $username_now=Yii::app()->user->name;
//            $info=$user_model->find("username='$username_now'");
//            $this->render('broadcast',array('info'=>$info));
//        }
//        public function actionDraw(){
//            $user_model = new User;
//            $username_now=Yii::app()->user->name;
//            $info=$user_model->find("username='$username_now'");
//            $this->render('draw',array('info'=>$info));
//        }
//        public function actionPpt(){
//            $user_model = new User;
//            $username_now=Yii::app()->user->name;
//            $info=$user_model->find("username='$username_now'");
//            $this->render('ppt',array('info'=>$info));
//        }
//        public function actionTest(){
//            $this->render('test');
//        }
}