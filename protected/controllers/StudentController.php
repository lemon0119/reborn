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
            ExamRecord::saveExamRecord($recordID);
            ExamRecord::overExam($recordID);
        }
    }
    
    public function actionVirtualClass() {
        $userID = Yii::app()->session['userid_now'];
        $student    =   Student::model()->findByPK($userID);
        $userName   =   $student->userName;
        $classID    =   $student->classID;
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $student = Student::model()->find("userID = '$userID'");
        return $this->render('virtualClass',[ 'userID' => $student ['userID'],'lessons'=>$lessons,'currentLesn'=>$currentLesn,'userName'=>$userName,'classID'=>$classID,'class' =>$student ['classID']]);
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
        $isExam=Yii::app()->session['isExam'];
         if($isExam){
            $recordID = ExamRecord::getRecord($workID, $studentID);
        }
        else{
            $recordID = SuiteRecord::getRecord($workID, $studentID);
        }
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, $type, $exerID);
        return $this->render('ansDetail_1',['exercise' => $classwork,
            'exer' => $exer,
            'answer' => $answer['answer'],
            'correct' => $answer['ratio_correct'],
            'type'=>$type,
                ]);
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
        $isExam=Yii::app()->session['isExam'];
         if($isExam){
            $recordID = ExamRecord::getRecord($workID, $studentID);
        }
        else{
            $recordID = SuiteRecord::getRecord($workID, $studentID);
        }
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
        $isExam=Yii::app()->session['isExam'];
        if($isExam){
            $recordID = ExamRecord::getRecord($workID, $studentID);
        }
        else{
            $recordID = SuiteRecord::getRecord($workID, $studentID);
        }
            
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
        $isExam=Yii::app()->session['isExam'];
        if($isExam){
            $recordID = ExamRecord::getRecord($workID, $studentID);
        }
        else{
            $recordID = SuiteRecord::getRecord($workID, $studentID);
        }
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
        $isExam=Yii::app()->session['isExam'];
        if($isExam){
            $recordID = ExamRecord::getRecord($workID, $studentID);
        }
        else{
            $recordID = SuiteRecord::getRecord($workID, $studentID);
        }
            
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
                $classwork[$type] = Exam::model()->getExamExerByType($suiteID, $type);
            }
        }
        
        return $this->render('suiteAns',['exercise'=>$classwork]);
    }
    public function actionSaveQuestion(){
        //查看是否有answer，即是否是用户提交了答案。
        if(isset($_POST['qType']) && $_POST['qType']=="question") {
             if(Yii::app()->session['isExam'])
             ExamRecord::saveExamRecord($recordID);
            else
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
            if(Yii::app()->session['isExam'])
                ExamRecord::saveExamRecord($recordID);
            else
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
            if(Yii::app()->session['isExam'])
                ExamRecord::saveExamRecord($recordID);
            else
                SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveFilling($recordID);
            if($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
        }
    }
    //我的科目
    public function actionMyCourse(){
        $isExam=false;
        Yii::app()->session['isExam']=$isExam;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        if($classID!="0"){
            $lessons = Lesson::model()->findAll("classID = '$classID'");
        }else{
            $lessons = array();
        }
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $currentLesn = isset($_GET['lessonID'])?$_GET['lessonID']:$currentLesn;
        $myCourse = Suite::model()->getClassworkAll( $currentLesn);
        $myCourses = array();
         $n=0;
        $ratio_accomplish = array();
        foreach ($myCourse as $c){
            array_push($myCourses, $c);
            $recordID[$n]=SuiteRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            if($recordID==null){
               return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'myCourse'=>$myCourses]);
            }else{
                $ratio_accomplish[$n] = SuiteRecord::model()->getSuitRecordAccomplish($recordID[$n]);
            }
           $n++;
        }     
        return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'myCourse'=>$myCourses,'ratio_accomplish'=>$ratio_accomplish]);
    }
    public function actionlistenType(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
         $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        $result = ListenType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
         if($record==null){
           return $this->render('listenExer',array( 'exercise'=>$classwork,'exercise2'=>$classwork2, 'exerOne'=>$result,'isExam' =>$isExam, 'cent' =>$cent,'workId'=>$wID,'isOver'=>$isOver ));
         }
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
         foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }

       
        return $this->render('listenExer',array( 
            'exercise'=>$classwork,
            'exercise2'=>$classwork2,
            'exerOne'=>$result,
            'isExam' =>$isExam,
            'cent' =>$cent,
            'workId'=>$wID,'isOver'=>$isOver
        ));
    }
    
    //2015-8-3 宋杰 获取考试听打练习
        public function actionExamlistenType(){
        $suiteID = Yii::app()->session['examsuiteID'];
          $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
         $classexam2 = Array();
          $finishRecord=Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));

        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
               $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
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
        $examInfo = Exam::model()->findByPK($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'listen', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$totalTime'.$totalTime;
        $isOver=0;
        if($totalTime!=0)
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
         if($recordID==null){
          return $this->render('listenExer',array( 'exercise'=>$classexam,'exerID' =>$exerID,'exercise2'=>$classexam2,'exerOne'=>$result,'cent' =>$cent,'isExam'=>$isExam,'examInfo'=>$examInfo,'typeNow' => 'listen', 'isOver' => $isOver, 'costTime' => $costTime ));
         }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $number= AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'filling'));
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }


        return $this->render('listenExer',array( 
            'exercise'=>$classexam,
            'exercise2'=>$classexam2,
                'exerOne'=>$result,
             'cent' =>$cent,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
             'exerID' =>$exerID,
            'typeNow' => 'listen',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime
        ));
    }
    
   //课堂看打练习
    public function actionlookType(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        $result = LookType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
         if($record==null){
           return $this->render('lookExer',array( 'exercise'=>$classwork,'exercise2'=>$classwork2,'exerOne'=>$result,'isExam' =>$isExam, 'cent' =>$cent,'workID' =>$wID,'isOver'=>$isOver ));    
         }
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
         foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }

        return $this->render('lookExer',array( 
            'exercise'=>$classwork,
            'exercise2'=>$classwork2,
            'exerOne'=>$result,
            'isExam' =>$isExam,
            'cent' =>$cent,
            'workID' =>$wID,'isOver'=>$isOver
        ));
    }
    
    //2015-8-3 宋杰 获取考试看打练习
        public function actionExamlookType(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
 
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
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
        $examInfo = Exam::model()->findByPK($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'look', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$totalTime'.$totalTime;
        $isOver=0;
        if($totalTime!=0)
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
       if($recordID==null){
         return $this->render('lookExer',array( 
            'exercise'=>$classexam,
            'exercise2'=>$classexam2,
                'exerOne'=>$result,
             'exerID' =>$exerID,
            'cent'=>$cent,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
            'typeNow' => 'look',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime));
       }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $number= AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'filling'));
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }


        return $this->render('lookExer',array( 
            'exercise'=>$classexam,
            'exercise2'=>$classexam2,
            'exerOne'=>$result,
            'cent'=>$cent,
            'isExam'=>$isExam,
            'examInfo'=>$examInfo,
             'exerID' =>$exerID,
            'typeNow' => 'look',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime
        ));
    }
    

   //课堂键位练习
    public function actionKeyType(){
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
             $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if($record==null){
           return $this->render('keyExer',array(  'exercise'=>$classwork, 'exercise2'=>$classwork2, 'exerOne'=>$result,'isExam' => $isExam,'cent' => $cent,'workId' =>$wID,'isOver'=>$isOver));
        }
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $n=0;
         foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
        return $this->render('keyExer',array( 
            'exercise'=>$classwork,
            'exercise2'=>$classwork2,
                'exerOne'=>$result,
            'isExam' => $isExam,
                'cent' => $cent,
            'workId' =>$wID,'isOver'=>$isOver
        ));
    }
    
    //2015-8-3 宋杰 获取考试键位练习
       public function actionExamKeyType(){
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
             $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
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
        $examInfo = Exam::model()->findByPK($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$costTime'.$costTime;
        $isOver=0;
        if($totalTime!=0)
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
         if($recordID==null){
          return $this->render('keyExer',array( 'exercise'=>$classexam,'exerID' =>$exerID,'exerOne'=>$result,'exercise2'=>$classexam2,'cent'=>$cent, 'isExam'=>$isExam,'examInfo'=>$examInfo,  'typeNow' => 'key','isOver' => $isOver,'costTime' => $costTime));
        }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }

        return $this->render('keyExer',array( 
            'exercise'=>$classexam,
            'exerOne'=>$result,
            'exercise2'=>$classexam2,
            'cent'=>$cent,
            'isExam'=>$isExam,
            'examInfo'=>$examInfo, 
            'typeNow' => 'key',
            'exerID' =>$exerID,
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime,
            
        ));
    }
    
   //课堂作业简答题 
     public function actionQuestion(){
        $isExam = FALSE;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $qNum=  Choice::model()->choiceCount('question');
       Yii::app()->session['num']=$qNum;
        $classwork = Array();
        $classwork = Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent= explode(',', $arg);
       
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        //分页
        $result = Suite::model()->getQuestion2($suiteID);
        $questionLst = $result ['questionLst'];
        $pages = $result ['pages'];
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
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
        }
        if($record==null){
           return $this->render('questionExer',['number'=>$number,'ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' => $isExam,'cent'=>$cent,'workID'=>$wID]); 
        }
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $number= AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'question'));
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
       

        return $this->render('questionExer',['number'=>$number,'ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' => $isExam,'cent'=>$cent,'workID'=>$wID]);
    }

    
   
    //2015-8-3 宋杰 获取考试简答题
        public function actionExamQuestion(){
        $isExam = true;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $qNum=  Choice::model()->choiceCount('question');
        Yii::app()->session['num']=$qNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $examInfo = Exam::model()->findByPK($suiteID);
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        //分页
        $result = Exam::model()->getQuestion2($suiteID);
        $questionLst = $result ['questionLst'];
        $pages = $result ['pages'];
        
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
          foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
          }
        if($record==null){
           return $this->render('questionExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'pages'=>$pages,'exercise2'=>$classexam2 ,'exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo,'typeNow' => 'question']);
    
        }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $number=AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'question'));
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
        
        return $this->render('questionExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'pages'=>$pages,'exercise2'=>$classexam2 ,'exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo,'typeNow' => 'question']);
    }
    
   //课堂作业选择题 
    public function actionChoice(){
        $isExam = FALSE;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
         $studentID = Yii::app()->session['userid_now'];
         $cNum=  Choice::model()->choiceCount('choice');   //动态长度
        Yii::app()->session['num']=$cNum;
        $classwork = Array();
        $classwork2 = Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        $wID=Yii::app()->session['workID']; 
        //显示选择题列表并分页  
        $result = Suite::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
         }
        if($record==null){
           return $this->render('choiceExer',['number'=>$number,'ansChoice'=>$ansArr,'choiceLst'=>$choiceLst,'pages'=>$pages,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'isExam' =>$isExam ,'cent'=>$cent,'workID'=>$wID]); 
        }
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $number=AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'choice'));
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
       
       
        
        return $this->render('choiceExer',['number'=>$number,'ansChoice'=>$ansArr,'choiceLst'=>$choiceLst,'pages'=>$pages,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'isExam' =>$isExam ,'cent'=>$cent,'workID'=>$wID]);
    }
    
   //2015-8-3 宋杰 获取试题，跳转到选择题页面 isExam为true加载examsidebar
    public function actionExamChoice(){
        $isExam = true;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $cNum=  Choice::model()->choiceCount('choice');   //动态长度
        Yii::app()->session['num']=$cNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $examInfo = Exam::model()->findByPK($suiteID);
        //
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        //显示选择题列表并分页  
        $result = Exam::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
    
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
       if( $record==null){
          return $this->render('choiceExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansChoice'=>$ansArr,'exercise'=>$classexam ,'exercise2'=>$classexam2 ,'choiceLst'=>$choiceLst,'pages'=>$pages, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'choice']); 
       }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $number=AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'choice'));
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0){
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            }else
                $cent[$n]='0';
            $n++;
        }
        
        return $this->render('choiceExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansChoice'=>$ansArr,'exercise'=>$classexam ,'exercise2'=>$classexam2 ,'choiceLst'=>$choiceLst,'pages'=>$pages, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'choice']);
    }
 //课堂作业填空题   
    public function actionfilling(){
        $isExam = false;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
         $studentID = Yii::app()->session['userid_now'];
         $fNum=Choice::model()->choiceCount('filling');
        Yii::app()->session['num']=$fNum;
        $classwork = Array();
        $classwork2 = Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent= explode(',', $arg);
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $finishRecord=Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
          //分页
        $result = Suite::model()->getFilling2($suiteID);
        $fillingLst = $result ['fillingLst'];
        $pages = $result ['pages'];
        
        $wID=Yii::app()->session['workID'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
        }
        if($record==null){
           return $this->render('fillingExer',['number'=>$number,'ansFilling'=>$ansArr,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' =>$isExam,'cent'=>$cent,'workID'=>$wID]);
     
        }
         foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
         }
         $number=AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'filling'));
         $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
       
        
        return $this->render('fillingExer',['number'=>$number,'ansFilling'=>$ansArr,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst,'exercise2'=>$classwork2 ,'exercise'=>$classwork ,'pages'=>$pages, 'isExam' =>$isExam,'cent'=>$cent,'workID'=>$wID]);
    }


    
    //2015-8-3 宋杰 加载考试填空题
    public function actionExamfilling(){
        $isExam = true;
        Yii::app()->session['isExam']=$isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $fNum=Choice::model()->choiceCount('filling');
        Yii::app()->session['num']=$fNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
         $examInfo = Exam::model()->findByPK($suiteID);
        //
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        //分页
        $result = Exam::model()->getFilling2($suiteID);
        $fillingLst = $result ['fillingLst'];
        $pages = $result ['pages'];
         foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
         }
        if($recordID==null){
            return $this->render('fillingExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst ,'pages'=>$pages,'exercise'=>$classexam ,'exercise2'=>$classexam2, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'filling']);
     
        }
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
        }
        $number= AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,'filling'));
        $n=0;
        foreach(Tool::$EXER_TYPE as $type){
            if(count($finishRecord[$type])!=0 && count($classexam[$type])!=0)
                $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            else
                $cent[$n]='0';
            $n++;
        }
       
        return $this->render('fillingExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst ,'pages'=>$pages,'exercise'=>$classexam ,'exercise2'=>$classexam2, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'filling']);
    }
    
    
  //课堂作业套题  
   public function actionClswkOne(){
        $workID = $_GET['suiteID'];
        $isExam = false;
        Yii::app()->session['isExam']=$isExam;
        Yii::app()->session['workID'] = $workID;
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        Yii::app()->session['suiteID'] = $clsLesnSuite->suiteID;
        $suiteID=Yii::app()->session['suiteID'];
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
            if(count($finishRecord[$type])!=0 && count($classwork[$type])!=0)
              $cent[$n]=round(count($finishRecord[$type])*100/count($classwork[$type]),2)."%";    
            else
                $cent[$n]='0';
            $n++;
        }
        
         return $this->render('suiteDetail',['exercise'=>$classwork,'isExam' => $isExam,'cent'=>$cent]);     
    }
    
    //获取考试套题
    public function actionClsexamOne(){
        $isExam = true;
        $suiteID = $_GET['suiteID'];
        $workID = $_GET['workID'];
        $studentID = Yii::app()->session['userid_now'];
        Yii::app()->session['isExam'] = $isExam;
        Yii::app()->session['examsuiteID'] = $suiteID;
        Yii::app()->session['examworkID'] = $_GET['workID'];
        //Yii::app()->session['examID'] = $suiteID;
        $classexam = Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        
        $cent=Array("0"=>"0","1"=>"0","2"=>"0","3"=>"0","4"=>"0","5"=>"0");
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        
        $examInfo = Exam::model()->findByPK($suiteID);
        if($record==null){
            return $this->render('suiteDetail',['exercise'=>$classexam,'isExam' => $isExam,'examInfo'=>$examInfo,'cent'=>$cent]);
        }
        $finishRecord=Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            
            
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=?",array($record->recordID,$type));
            
         }
        $n=0;
        $cNum=count($classexam['choice']);
        $fNum=count($classexam['filling']);
        $qNum=count($classexam['question']);
       $num=(($cNum>$fNum)?$cNum:$fNum)>$qNum?(($cNum>$fNum)?$cNum:$fNum):$qNum;
       Yii::app()->session['num']=$num;
        
        foreach(Tool::$EXER_TYPE as $type){
        
            if(count($classexam[$type])!=0 ){
              $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%";  
            }          
            $n++;
        }
        return $this->render('suiteDetail',['exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo,'cent'=>$cent]);
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
   
    public function actionAnswerDetail(){
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
        $exer = Exercise::getExerise($exerID, $type);
        $answer = Yii::app()->session['answer'];
        return $this ->render('ansDetail',['exer'=>$exer, 'answer'=>$answer]);
    }
    //课堂作业
    public function actionClasswork(){
        $isExam=false;
        Yii::app()->session['isExam']=$isExam;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $workID = Yii::app()->session['workID']; 
        $classworks = Suite::model()->getClassworkAll($currentLesn);
        $classwork = array();
        $ratio_accomplish='0';
        $n=0;
        foreach ($classworks as $c){
            array_push($classwork, $c);
            $recordID[$n]=SuiteRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            if($recordID==null){
               return $this->render('classwork',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'classwork'=>$classwork]);
            }else{
                $ratio_accomplish[$n] = SuiteRecord::model()->getSuitRecordAccomplish($recordID[$n]);
            }
           $n++;
        }     
        return $this->render('classwork',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'classwork'=>$classwork,'ratio_accomplish'=>$ratio_accomplish]);
    }
    
    //宋杰 2015-7-30 课堂考试
    public function actionClassExam(){
        Yii::app()->session['isExam']=true;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $classexams = Exam::model()->getClassexamAll($classID);
        $classexam = array();
        $ratio_accomplish='0';
        $n=0;
        $score='';
        foreach ($classexams as $c){
            array_push($classexam, $c);
            $recordID[$n]=ExamRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            $score[$n] = ExamRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['score'];
            if($recordID==null||$score==null){
                return $this->render('classexam',['score'=>$score,'classexams'=>$classexam]);
            }else{
                $ratio_accomplish[$n] = ExamRecord::model()->getExamRecordAccomplish($recordID[$n]);
            }
            $n++;
        }     
        return $this->render('classexam',['score'=>$score,'classexams'=>$classexam,'ratio_accomplish'=>$ratio_accomplish,'classID'=>$classID]);
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
            echo $seconds;
            $correct = $_POST['nm_correct'];
            $AverageSpeed = $_POST['nm_AverageSpeed'];
            $HighstSpeed = $_POST['nm_HighstSpeed'];
            $BackDelete = $_POST['nm_BackDelete'];
            $HighstCountKey = $_POST['nm_HighstCountKey'];
            $AveragekeyType = $_POST['nm_AverageKeyType'];
            $HighIntervarlTime = $_POST['nm_HighIntervarlTime'];            
            $countAllKey = $_POST["nm_countAllKey"];      
            if(Yii::app()->session['isExam']){
                if(!ExamRecord::saveExamRecord($recordID))
                    return false;
            }else {
                if(!SuiteRecord::saveSuiteRecord ($recordID))
                    return false;
            }
            return AnswerRecord::saveAnswer($recordID, $answer, $seconds,$correct,$AverageSpeed,$HighstSpeed,$BackDelete,$HighstCountKey,$AveragekeyType,$HighIntervarlTime,$countAllKey);
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
    public function actionHeadPic(){
        $picAddress="";
        $result=0;
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        $picAddress=$user['img_address'];
        $this->render('headPic',['result'=>$result,'picAddress'=>$picAddress]);
    }
    public function actionAddHeadPic(){
        $result ="上传失败!";
        $flag=0;
        $picAddress="";
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        $picAddress=$user['img_address'];
        if(!isset($_FILES["file"]))
        {
            $result= "请选择文件！";
            $this->render('headPic',['result'=>$result,'picAddress'=>$picAddress]);
        }
        if (($_FILES ["file"] ["type"] == "image/gif")|| ($_FILES["file"]["type"] == "image/png") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/pjpeg"))
        {   
            if ($_FILES["file"]["error"] > 0)
            {
                $result = "Return Code: " . $_FILES["file"]["error"];
            }
          else
            {
                $oldName = $_FILES["file"]["name"]; 
                $newName = Tool::createID().".".pathinfo($oldName,PATHINFO_EXTENSION);
                move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "img/head/" . $newName );
                $result = "上传成功！";
                $user->img_address="img/head/" .$newName;
                $picAddress="img/head/" .$newName;
                $user->update();
            }
        }else {
            $result = "请上传正确的文件！";
        }
        $this->render('headPic',['result'=>$result,'picAddress'=>$picAddress]);
    }
    public function actionSet(){       //set
    	$result ='no';
        $mail='';
        //head img
        $y='0';
        $picAddress='0';
        $flag = 'no';
        if (isset($_POST ['flag'])) {
            $flag = '1';
        }
        if($flag == '1'){
            if(!isset($_FILES["file"]))
            {
                echo "请选择文件！";
                return ;
            }
            if (! empty ( $_FILES ['file'] ['name'] )) {
                if ((($_FILES ["file"] ["type"] == "image/gif")|| ($_FILES["file"]["type"] == "image/png") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/pjpeg")) && ($_FILES ["file"] ["size"] < 200000000)) {
                        if ($_FILES ["file"] ["error"] > 0) {
                                $result = "Return Code: " . $_FILES["file"]["error"];
                        } else {
                                if (file_exists ( "img/head/" . $_FILES ["file"] ["name"] )) {
                                        $result = "already exists.";
                                } else {
                                    $y='1';
                                    $oldName = $_FILES["file"]["name"]; 
                                    $newName = Tool::createID().".".pathinfo($oldName,PATHINFO_EXTENSION);
                                    move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "img/head/" . $newName );
                                    $result = "Stored.";
                                }
                        }
                } else {
                        $result = "Invalid file.";
                }
            }
        }
        
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        $picAddress=$user->img_address;
        if (!empty($user->mail_address)) {
            $mail = $user->mail_address;
        }
        if(isset($_POST['old'])){
    		$new1=$_POST['new1'];
    		$defnew=$_POST['defnew'];
                $email=$_POST['email'];
    		
    		$usertype=Yii::app()->session['role_now'];
    		$user = Student::model()->find('userID=?', array($userid_now));
                if($user->password== md5($_POST['old'])){
                    $user->password=md5($new1);
                    $user->mail_address=$email;
                    if($y=='1')
                        $user->img_address="img/head/" .$newName; 
                    $picAddress=$user->img_address;
                    $result=$user->update();
                    $mail=$email;
    			
    		}else{
                    $result='old error';
    			$this->render('set',['flag' => $flag,'result'=>$result,'mail'=>$mail,'picAddress'=>$picAddress]);
    			return;
                }
    		
    	}
    	$this->render('set',['flag' => $flag,'result'=>$result,'mail'=>$mail,'picAddress'=>$picAddress]);
    }
    
    public function actionHello(){
        return $this->render('hello',array(null));
    }
    //公告信息
    public function actionStuNotice(){
        $result=Notice::model()->findNotice();
        $noticeRecord=$result ['noticeLst'];
        $pages = $result ['pages'];
        $studentID = Yii::app()->session['userid_now'];
        $noticeS = Student::model()->findByPK($studentID);
        $noticeS->noticestate = '0';
        $noticeS->update();
       $this->render('stuNotice',  array('noticeRecord'=>$noticeRecord,'pages'=>$pages));
    }
    //公告内容
     public function ActionNoticeContent(){
        $result=0;
        if(isset($_GET['action'])&&$_GET['action']=='edit'){
            $result=1;
        }
       $id = $_GET['id'];
       $noticeRecord=Notice::model()->find("id= '$id'");
       $this->render('noticeContent',  array('noticeRecord'=>$noticeRecord));
     }
     //速录百科
    public function actionSuLu(){
        return $this->render('suLu');

    }
    
     public function actionScheduleDetil() {
             //查询任课班级科目
             $userID= Yii::app()->session['userid_now'];
             $sqlStudent = Student::model()->find("userID = '$userID'");
             $currentClass = $sqlStudent['classID'];
             Yii::app()->session['ScheduleCurrentClass'] = $currentClass;
             $classResult = ScheduleClass::model()->findAll("classID='$currentClass'");
              return $this->render('scheduleDetil', [ 'result' => $classResult]);
    }
    
     public function actionEditSchedule() {
        $sequence = $_GET['sequence'];
        $day = $_GET['day'];
             $currentClass = Yii::app()->session['ScheduleCurrentClass'];
             $sql = "SELECT * FROM schedule_class WHERE classID = '$currentClass' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();
        return $this->renderPartial('editSchedule', ['result' => $sqlSchedule]);
    }
    //学生个人资料
     public function actionStuInformation(){
        $ID= Yii::app()->session['userid_now'];
        $student = Student::model()->find("userID = '$ID'");
        return $this->render('stuInformation',array(
                'id' => $student ['userID'],
                'name' => $student ['userName'],
                'class' =>$student ['classID'],
                'sex' => $student['sex'],
                'age' => $student['age'],
                'password' => $student['password'],
                'mail_address' => $student['mail_address'],
                'phone_number' => $student['phone_number']
        ));

    }
    
    
        public function actionFreePractice() {
             $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        if($classID!="0"){
            $lessons = Lesson::model()->findAll("classID = '$classID'");
        }else{
            $lessons = array();
        }
        return $this->render('freePractice',['lessons'=>$lessons]);
    }
}