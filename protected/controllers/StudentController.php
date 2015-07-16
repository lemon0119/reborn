<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//crt by LC 2015-4-9

class StudentController extends CController {
    protected $courseID = '';
    protected $suiteID = '';
    protected $exerType = '';
    
    public $layout='//layouts/studentBar';
    
    public function actionSaveChoice(){
        //查看是否有answer，即是否是用户提交了答案。
        if(isset($_POST['qType']) && $_POST['qType']=="choice") {
            SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveChoice($recordID);
            if($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
            
        }
    }
    
    public function actionMyCourse(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = isset($_GET['lessonID'])?$_GET['lessonID']:0;
        return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn]);
    }
    public function actionClassExam(){
        return $this->render('classExam');
    }
    public function actionlistenType(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        $result = ListenType::model()->findByPK($exerID);
        return $this->render('listenExer',array( 
            'exercise'=>$classwork,
            'exerOne'=>$result,
        ));
    }
    
    public function actionlookType(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        $result = LookType::model()->findByPK($exerID);
        return $this->render('lookExer',array( 
            'exercise'=>$classwork,
            'exerOne'=>$result,
        ));
    }
    
    public function actionKeyType(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        $result = KeyType::model()->findByPK($exerID);
        return $this->render('keyExer',array( 
            'exercise'=>$classwork,
                'exerOne'=>$result,
        ));
    }
    public function actionQuestion(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        return $this->render('questionExer',['exercise'=>$classwork]);
    }
    
    public function actionChoice(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        return $this->render('choiceExer',['exercise'=>$classwork]);
    }
    public function actionfilling(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        return $this->render('fillingExer',['exercise'=>$classwork]);
    }
    public function actionClswkOne(){
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['suiteID'] = $suiteID;
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        return $this->render('suiteDetail',['exercise'=>$classwork]);
    }
    
    public function actionWebrtc(){
        $studentID = Yii::app()->session['userid_now'];
        $studentName = Student::model()->findByPK($studentID)->userName;
        return $this->render('webrtc',['studentName'=>$studentName]);
    }
    
    public function actionProDetail(){
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['recordID'] = $_GET['recordID'];
        $suiteName = Suite::model()->find('suiteID=?',[$suiteID])['suiteName'];
        $result = Student::model()-> getAnswerRecordAll($suiteID);
        return $this->render('proDetail',['result'=>$result,'suiteName'=>$suiteName]);
    }
    public function actionSaveAnswer(){
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        Yii::app()->session['page'] = $page;
        $answer = $_POST['nm_answer'];
        Yii::app()->session['answer'] = $answer;
        $this->saveAnswer();
        $this->redirect(['/student/answerDetail']);
    }
    public function actionGotoDetail(){
        $recordID = Yii::app()->session['recordID'];
        $type = $_GET['exerType'];
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = $type;
        $answer = AnswerRecord::getAnswerID($recordID, $type, $exerID);
        Yii::app()->session['answer'] = $answer['answer'];
        $this->redirect(['/student/answerDetail']);
    }
    public function actionAnswerDetail(){
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
        $exer = Exercise::getExerise($exerID, $type);
        $modAns = $exer['content'];
        $answer = Yii::app()->session['answer'];
        return $this ->render('ansDetail',['exer'=>$exer, 'answer'=>$answer]);
    }
    public function actionClasswork(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = isset($_GET['lessonID'])?$_GET['lessonID']:0;
        //if()
        $classworks = Suite::model()->getClassworkAll($currentLesn);
        return $this->render('classwork',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'classwork'=>$classworks]);
        /*
        $this->saveParam();
        Yii::app()->session['type'] = 'classwork';
        Yii::app()->session['progress'] = 'false';
        $exerType = Yii::app()->session['exerType'];
        //找到当前的课堂练习
        $suite = Suite::model()->getClassworkNow();
        $record = $suite->read();
        if($record == FALSE){
            return $this->render('classwork',array(
                'noExer'=>"true",
            ));
        }
        $suiteID = $record['suiteID'];
        Yii::app()->session['suiteID'] = $suiteID;
        
        //判断内部选择的题型，以便包含不同的页面。
        if($exerType == 'listenExer') {
            $result = ListenType::model()->getListenExer($suiteID);
            return $this->render('classwork',array(
                'exercise'=>$result["exercise"],
                'pages'=>$result["pages"],
            ),false,true);
        } else if ($exerType == 'lookExer') {
            $result = LookType::model()->getLookExer($suiteID);
            return $this->render('classwork',  ['exercise'=>$result["exercise"],
                'pages'=>$result['pages'],
                ],false,true);
        } else if ($exerType == 'keyExer') {
            $result = KeyType::model()->getKeyExer($suiteID);
            return $this->render('classwork',array(
                'exercise'=>$result["exercise"],
                'pages'=>$result['pages'],
            ),false,true);
        } else if ($exerType == 'knlgExer') {
            $choice = Suite::model()->getchoice($suiteID);
            $filling = Suite::model()->getFilling($suiteID);
            $question = Suite::model()->getQuestion($suiteID);
            return $this->render('classwork',array( 
                'choice'=>$choice, 
                'filling'=>$filling, 
                'question'=>$question, 
            ));
        }
         * 
         */
    }
    
    public function actionProgress(){
        $type = $_GET['type'];
        Yii::app()->session['type'] = $type;
        Yii::app()->session['progress'] = 'true';
        $result = Student::model()-> getAnswerRecordSub();
        return $this->render('progress',['result'=>$result]);
    }
    public function actionPreExer(){
        $type = Yii::app()->session['type'];
        $page = Yii::app()->session['page'];
        $exerType = Yii::app()->session['exerType'];
        $progress = Yii::app()->session['progress'];
        if($progress==='true'){
            $this->redirect(['student/progress','type'=>$type]);
        }else{
            if($type === 'classwork')
                $this->redirect(['student/classwork','exerType'=>$exerType,"page"=>$page]);
            else
                $this->redirect(['student/Exer','exerType'=>$exerType,"page"=>$page]);
        }
    }
    public function actionExer(){
        $this->saveParam();
        $exerType = Yii::app()->session['exerType'];
        $suiteID = Yii::app()->session['suiteID'];
        Yii::app()->session['type'] = 'exercise';
        Yii::app()->session['progress'] = 'false';
        //判断内部选择的题型，以便包含不同的页面。
        if($exerType == 'listenExer') {
            return $this->doListenExer($suiteID);
        } else if ($exerType == 'lookExer') {
            return $this->doLookExer($suiteID);
        } else if ($exerType == 'keyExer') {
            return $this->doKeyExer($suiteID);
        } else if ($exerType == 'knlgExer') {
            return $this->doKnlgExer($suiteID);
        }
        
        return $this->render('Exer');
    }
    public function saveAnswer(){
        //查看是否有answer，即是否是用户提交了答案。
        if(isset($_POST['nm_answer'])) {
            $answer = $_POST['nm_answer'];
            $seconds = $_POST['nm_cost'];
            $recordID = SuiteRecord::saveSuiteRecord ();
            $answerID = AnswerRecord::saveAnswer($recordID, $answer, $seconds);
        }
        if(isset($_POST['qType']) && $_POST['qType']=="knlg") {
            $recordID = SuiteRecord::saveSuiteRecord ();
            AnswerRecord::saveChoice($recordID);
            AnswerRecord::saveFilling($recordID);
            AnswerRecord::saveQuestion($recordID);
        }
    }
    public function saveParam() {
        //将get参数，存入session。
        if(isset($_GET['courseID'])){
            $courseID = $_GET['courseID'];
            Yii::app()->session['courseID']=$courseID;
            unset($_GET['courseID']);
        }
        if(isset($_POST['suite'])){
            $suiteID = $_POST["suite"];
            Yii::app()->session['suiteID']=$suiteID;
        }
        if(isset($_GET['exerType'])){
            $exerType = $_GET['exerType'];
            Yii::app()->session['exerType']=$exerType;
            unset($_GET['exerType']);
        }
    }
    
    public function doListenExer($suiteID) {
        $result = ListenType::model()->getListenExer($suiteID);
        return $this->render('Exer',array(
            'exercise'=>$result["exercise"],
            'pages'=>$result["pages"],
        ),false,true);
    }
    
    public function doKnlgExer($suiteID) {
        $choice = Suite::model()->getchoice($suiteID);
        $filling = Suite::model()->getFilling($suiteID);
        $question = Suite::model()->getQuestion($suiteID);
        return $this->render('Exer',array( 
            'choice'=>$choice, 
            'filling'=>$filling, 
            'question'=>$question, 
        ));
    }
    
    public function doLookExer($suiteID) {
        $result = LookType::model()->getLookExer($suiteID);
        return $this->render('Exer',array( 
                'exercise'=>$result["exercise"],
                'pages'=>$result["pages"],
        ),false,true);
    }
    
    public function doKeyExer($suiteID) {
        $result = KeyType::model()->getKeyExer($suiteID);
        return $this->render('Exer',array( 
                'exercise'=>$result["exercise"],
                'pages'=>$result["pages"],
        ),false,true);
    }
    public function actionIndex(){
        $this->render('index');
    }
    public function actionHello(){
        return $this->render('hello',array(null));
    }
}