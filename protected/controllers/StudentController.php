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
    
    public function actionOverSuite(){
        if(isset($_GET['isExam']) && $_GET['isExam'] == false){
            SuiteRecord::saveSuiteRecord($recordID);
            SuiteRecord::overSuite($recordID);
        } else {
            //这里，应该改成修改考试记录examRecord
            SuiteRecord::saveSuiteRecord($recordID);
            SuiteRecord::overSuite($recordID);
        }
    }
    
    public function actionVirtualClass() {
        $userID = Yii::app()->session['userid_now'];
        $student    =   Student::model()->findByPK($userID);
        $userName   =   $student->userName;
        $classID    =   $student->classID;
        return $this->render('virtualClass',['userName'=>$userName,'classID'=>$classID]);
    }
    
    public function actionAnslookType(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        
        $exerID = $_GET['exerID'];
        $type = $_GET['type'];
        $exer = Exercise::getExerise($exerID, $type);
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, $type, $exerID);
        return $this->render('ansDetail_1',['exercise' => $classwork,
            'exer' => $exer,
            'answer' => $answer['answer'],
            'correct' => $answer['ratio_correct']]);
    }
    
    public function actionAnsKeyType(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        return $this->render('ansKey',
            ['exercise' => $classwork,
            'exer' => $result,
            'answer' => $answer['answer'],
            'correct' => $answer['ratio_correct']]);
    }
    
    public function actionAnsQuestion(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        return $this->render('ansQuest',['exercise'=>$classwork,'ansQuest'=>$ansArr]);
    }
    
    public function actionAnsFilling(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        return $this->render('ansFilling',['exercise'=>$classwork,'ansFilling'=>$ansArr]);
    }
    
    public function actionAnsChoice(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        return $this->render('ansChoice',['exercise'=>$classwork,'ansChoice'=>$ansArr]);
    }
    public function actionViewAns(){
        if(isset($_GET['workID'])){
            $workID = $_GET['workID'];
            $suiteID = $_GET['suiteID'];
        } else {
            $workID = $_GET['suiteID'];
            $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
            $suiteID = $clsLesnSuite->suiteID;
        }
        Yii::app()->session['workID'] = $workID;
        Yii::app()->session['suiteID'] = $suiteID;
        $classwork = Array();
        $isExam = Yii::app()->session['isExam'];
        if(!$isExam){
            foreach(Tool::$EXER_TYPE as $type){
                $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            }
        } else {
            foreach(Tool::$EXER_TYPE as $type){
                $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            }
        }
        
        return $this->render('suiteAns',['exercise'=>$classwork]);
    }
    public function actionSaveQuestion(){
        //查看是否有answer，即是否是用户提交了答案。
        if(isset($_POST['qType']) && $_POST['qType']=="question") {
            SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveQuestion($recordID);
            if($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
        }
    }
    
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
    
    public function actionSaveFilling(){
        //查看是否有answer，即是否是用户提交了答案。
        if(isset($_POST['qType']) && $_POST['qType']=="filling") {
            SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveFilling($recordID);
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
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $currentLesn = isset($_GET['lessonID'])?$_GET['lessonID']:$currentLesn;
        $myCourse = Suite::model()->getClassworkAll( $currentLesn);
        $ratio_accomplish = ExamRecord::model()->getExamAccomplish($studentID);
        return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'myCourse'=>$myCourse,'ratio_accomplish'=>$ratio_accomplish]);
    }
    public function actionlistenType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        $result = ListenType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        return $this->render('listenExer',array( 
            'exercise'=>$classwork,
            'exerOne'=>$result,
            'isExam' =>$isExam,
            'cent' =>$cent,
            'workId'=>$wID
        ));
    }
    
    //2015-8-3 宋杰 获取考试听打练习
        public function actionExamlistenType(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        //edit by LC
        //$result = ListenType::model()->findByPK($exerID);
        foreach($classexam['listen'] as $listenType){
            if($listenType['exerciseID'] == $exerID){
                $result = $listenType;
                break;
            }
        }
        //end
        $isExam = true;
        $examInfo = Exam::model()->find($suiteID);
        return $this->render('listenExer',array( 
            'exercise'=>$classexam,
                'exerOne'=>$result,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
            'typeNow' => 'listen'
        ));
    }
    
   
    public function actionlookType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        $result = LookType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        return $this->render('lookExer',array( 
            'exercise'=>$classwork,
            'exerOne'=>$result,
            'isExam' =>$isExam,
            'cent' =>$cent,
            'workID' =>$wID
        ));
    }
    
    //2015-8-3 宋杰 获取考试看打练习
        public function actionExamlookType(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        //edit by LC
        //$result = LookType::model()->findByPK($exerID);
        foreach($classexam['look'] as $lookType){
            if($lookType['exerciseID'] == $exerID){
                $result = $lookType;
                break;
            }
        }
        //end
        $isExam = true;
        $examInfo = Exam::model()->find($suiteID);
        return $this->render('lookExer',array( 
            'exercise'=>$classexam,
                'exerOne'=>$result,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
            'typeNow' => 'look'
        ));
    }
    

   
    public function actionKeyType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        return $this->render('keyExer',array( 
            'exercise'=>$classwork,
                'exerOne'=>$result,
            'isExam' => $isExam,
                'cent' => $cent,
            'workId' =>$wID
        ));
    }
    
    //2015-8-3 宋杰 获取考试键位练习
       public function actionExamKeyType(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        //edit by LC
        //$result = KeyType::model()->findByPK($exerID);
        foreach($classexam['key'] as $keyType){
            if($keyType['exerciseID'] == $exerID){
                $result = $keyType;
                break;
            }
        }
        //end
        $isExam = true;
        $examInfo = Exam::model()->find($suiteID);
        return $this->render('keyExer',array( 
            'exercise'=>$classexam,
                'exerOne'=>$result,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo, 
            'typeNow' => 'key'
        ));
    }
    
    
     public function actionQuestion(){
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        $arg=$_GET['cent'];
        $cent= explode(',', $arg);
       
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        //分页
        $result = Suite::model()->getQuestion2($suiteID);
        $questionLst = $result ['questionLst'];
        $pages = $result ['pages'];
        $isExam = FALSE;
        $wID=Yii::app()->session['workID'];
         Yii::app()->session['questionNum']=count($classwork['question']);
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            $n++;
        }

        return $this->render('questionExer',['ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' => $isExam,'cent'=>$cent,'workID'=>$wID]);
    }

    
    //2015-8-3 宋杰 获取考试简答题
        public function actionExamQuestion(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $examInfo = Exam::model()->find($suiteID);
        $isExam = true;
        return $this->render('questionExer',['exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo,'typeNow' => 'question']);
    }
    
    
    public function actionChoice(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
         $studentID = Yii::app()->session['userid_now'];
        $classwork = Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
       
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            $n++;
        }
       
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        $wID=Yii::app()->session['workID']; 
        //显示选择题列表并分页  
        $result = Suite::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
         $isExam = FALSE;
        return $this->render('choiceExer',['ansChoice'=>$ansArr,'choiceLst'=>$choiceLst,'pages'=>$pages,'exercise'=>$classwork ,'isExam' =>$isExam ,'cent'=>$cent,'workID'=>$wID]);
    }
   //2015-8-3 宋杰 获取试题，跳转到选择题页面 isExam为true加载examsidebar
    public function actionExamChoice(){
       $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $examInfo = Exam::model()->find($suiteID);
        $isExam = true;
        return $this->render('choiceExer',['exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo]);
    }
    
public function actionfilling(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
         $studentID = Yii::app()->session['userid_now'];
        $classwork = Array();

        $arg=$_GET['cent'];
        $cent= explode(',', $arg);

         $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            $n++;
        }

         //分页
        $result = Suite::model()->getFilling2($suiteID);
        $fillingLst = $result ['fillingLst'];
        $pages = $result ['pages'];
        $isExam = false;
        $wID=Yii::app()->session['workID'];
         Yii::app()->session['fillingNum']=count($classwork['filling']);
       
       
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        return $this->render('fillingExer',['ansFilling'=>$ansArr,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' =>$isExam,'cent'=>$cent,'workID'=>$wID]);
    }

    
    //2015-8-3 宋杰 加载考试填空题
        public function actionExamfilling(){
       $suiteID = Yii::app()->session['examsuiteID'];
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $examInfo = Exam::model()->find($suiteID);
        $isExam = true;
        return $this->render('fillingExer',['exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'filling']);
    }
    
    
    
    public function actionClswkOne(){
        $workID = $_GET['suiteID'];
         $isExam = false;
        Yii::app()->session['workID'] = $workID;
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        Yii::app()->session['suiteID'] = $clsLesnSuite->suiteID;
        $suiteID=Yii::app()->session['suiteID'];
        $classwork = Array();
        $studentID = Yii::app()->session['userid_now'];
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $cent=Array("0"=>"0","1"=>"0","2"=>"0","3"=>"0","4"=>"0","5"=>"0");
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
         }
        if($record==null){
            return $this->render('suiteDetail',['exercise'=>$classwork,'isExam' => $isExam,'cent'=>$cent]);
        }
            
        $finishRecord=Array();
       
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
        $n=0;
        $cNum=count($classwork['choice']);
        $fNum=count($classwork['filling']);
        $qNum=count($classwork['question']);
       $num=(($cNum>$fNum)?$cNum:$fNum)>$qNum?(($cNum>$fNum)?$cNum:$fNum):$qNum;
       Yii::app()->session['num']=$num;
       
        
        foreach(Tool::$EXER_TYPE as $type){
        
            if(count($classwork[$type])!=0 ){
              $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%";    
            }          
            $n++;
        }
        
         return $this->render('suiteDetail',['exercise'=>$classwork,'isExam' => $isExam,'cent'=>$cent]);


      
    }
    
    //获取考试套题
    public function actionClsexamOne(){
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['examsuiteID'] = $suiteID;
        //Yii::app()->session['workID'] = $_GET['workID'];
        Yii::app()->session['suiteID'] = $suiteID;
        $classexam = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $examInfo = Exam::model()->find($suiteID);
        $isExam = true;
        Yii::app()->session['isExam'] = $isExam;
        return $this->render('suiteDetail',['exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo]);
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
        if($this->saveAnswer())
            echo '提交答案成功！';
        else
            echo '对不起，提交答案失败。';
    }
    
//    public function actionGotoDetail(){
//        $recordID = Yii::app()->session['recordID'];
//        $type = $_GET['exerType'];
//        $exerID = $_GET['exerID'];
//        Yii::app()->session['exerID'] = $exerID;
//        Yii::app()->session['exerType'] = $type;
//        $answer = AnswerRecord::getAnswerID($recordID, $type, $exerID);
//        Yii::app()->session['answer'] = $answer['answer'];
//        $this->redirect(['/student/answerDetail']);
//    }
    public function actionAnswerDetail(){
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
        $exer = Exercise::getExerise($exerID, $type);
        $answer = Yii::app()->session['answer'];
        return $this ->render('ansDetail',['exer'=>$exer, 'answer'=>$answer]);
    }
    public function actionClasswork(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        
        $classworks = Suite::model()->getClassworkAll($currentLesn);
        
        $classwork = array();
        foreach ($classworks as $c){
        array_push($classwork, $c);
        $recordID=SuiteRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            
        }
        
        
        
        $ratio_accomplish = SuiteRecord::model()->getSuitRecordAccomplish($recordID);
        return $this->render('classwork',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'classwork'=>$classwork,'ratio_accomplish'=>$ratio_accomplish]);
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
    
    //宋杰 2015-7-30 课堂考试
    public function actionClassExam(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);     
        $classexams = Exam::model()->getClassexamAll($classID);
        return $this->render('classexam',['classexams'=>$classexams]);
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
            if(!SuiteRecord::saveSuiteRecord ($recordID))
                return false;
            return AnswerRecord::saveAnswer($recordID, $answer, $seconds);
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
    public function actionSet(){       //set
    	$result ='no';
        $mail='';
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        if (!empty($user->mail_address)) {
            $mail = $user->mail_address;
        }
        if(isset($_POST['old'])){
    		$new1=$_POST['new1'];
    		$defnew=$_POST['defnew'];
                $email=$_POST['email'];
    		
    		$usertype=Yii::app()->session['role_now'];
    		
    		//$thisStudent=new Student();
    		//$thisStudent->password=$new1;
    		//$result=$thisStudent->update();
    		$user = Student::model()->find('userID=?', array($userid_now));
                if($user->password !== $_POST['old']){
    			$result='old error';
    			$this->render('set',['result'=>$result,'mail'=>$mail]);
    			return;
    		}
    		$user->password=$new1;
                $user->mail_address=$email;
    		$result=$user->save();
                $mail=$email;
    	}
    	
    	$this->render('set',['result'=>$result,'mail'=>$mail]);
    }
    
    public function actionHello(){
        return $this->render('hello',array(null));
    }
}