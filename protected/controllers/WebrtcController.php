<?php
class WebrtcController extends CController{
    public $layout='//layouts/studentBar';
    public function actionCourse(){
        $classID                        =   $_GET['classid'];
        $course                         =   TbClass::model()->findCourseByClassID($classID);
        $lesson                         =   TbClass::model()->findLessonByCourse($course[0]['courseID']);
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
        $userID = Yii::app()->session['userid_now'];
        $userName = '';
        $role = Yii::app()->session['role_now'];
        if($role == 'student'){
            $this->layout='//layouts/studentBar';
            $student    =   Student::model()->findByPK($userID);
            $userName   =   $student->userName;
            $classID    =   $student->classID;
            return $this->render('student',['userName'=>$userName,'classID'=>$classID]);
        }
        else{
            $userName   = Teacher::model()->findByPK($userID)->userName;
            $this->layout='//layouts/teacherBar';
            return $this->render('teacher',['userName'=>$userName,'classID'=>Yii::app()->session['curClass']]);
        }
        $this->render('index',['userName'=>$userName]);
    }
}