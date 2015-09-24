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
    //我的课程
    public function actionMyCourse(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        print_r($currentLesn);
        $currentLesn = isset($_GET['lessonID'])?$_GET['lessonID']:$currentLesn;
        $myCourse = Suite::model()->getClassworkAll( $currentLesn);
        $myCourses = array();
        if($myCourse==null){
               return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'myCourse'=>$myCourse]);
            }  
            $ratio_accomplish='0';
        foreach ($myCourse as $c){
            array_push($myCourses, $c);
            $recordID=SuiteRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            $ratio_accomplish = SuiteRecord::model()->getSuitRecordAccomplish($recordID);
        
        }  
        return $this->render('myCourse',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'myCourse'=>$myCourses,'ratio_accomplish'=>$ratio_accomplish]);
    }
    public function actionlistenType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        $result = ListenType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
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
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
         $classexam2 = Array();
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
        $examInfo = Exam::model()->find($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $workID = Yii::app()->session['workID'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'listen', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$totalTime'.$totalTime;
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
        return $this->render('listenExer',array( 
            'exercise'=>$classexam,
            'exercise2'=>$classexam2,
                'exerOne'=>$result,
             'cent' =>$cent,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
            'typeNow' => 'listen',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime
        ));
    }
    
   //课堂看打练习
    public function actionlookType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        $result = LookType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
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
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
         $classexam2 = Array();
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
        $examInfo = Exam::model()->find($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $workID = Yii::app()->session['workID'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'look', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$totalTime'.$totalTime;
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
        return $this->render('lookExer',array( 
            'exercise'=>$classexam,
            'exercise2'=>$classexam2,
                'exerOne'=>$result,
            'cent'=>$cent,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo,
            'typeNow' => 'look',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime
        ));
    }
    

   //课堂键位练习
    public function actionKeyType(){
        $suiteID = Yii::app()->session['suiteID'];
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classwork = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
             $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        $isExam = false;
        $wID=Yii::app()->session['workID'];
        $isOver='0';
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
         $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
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
        $examInfo = Exam::model()->find($suiteID);
        //edit by LC
        $studentID = Yii::app()->session['userid_now'];
        $workID = Yii::app()->session['workID'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        $costTime = isset($answer['costTime']) ? $answer['costTime'] : 0;
        //echo '$costTime'.$costTime;
        $totalTime = $result['time'];
        //echo '$costTime'.$costTime;
        $isOver = $costTime < $totalTime ? 0 : 1;
        //end
        return $this->render('keyExer',array( 
            'exercise'=>$classexam,
                'exerOne'=>$result,
            'exercise2'=>$classexam2,
            'cent'=>$cent,
            'isExam'=>$isExam,
                'examInfo'=>$examInfo, 
            'typeNow' => 'key',
            'isOver' => $isOver, //edit by LC
            'costTime' => $costTime
        ));
    }
    
   //课堂作业简答题 
     public function actionQuestion(){
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
            if($finishRecord[$type]!=null && $classwork[$type]!=null)
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
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $qNum=  Choice::model()->choiceCount('question');
       Yii::app()->session['num']=$qNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $examInfo = Exam::model()->find($suiteID);
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
            $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            $n++;
        }
        
        return $this->render('questionExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansQuest'=>$ansArr,'questionLst'=>$questionLst ,'pages'=>$pages,'exercise2'=>$classexam2 ,'exercise'=>$classexam , 'isExam' => $isExam , 'examInfo'=>$examInfo,'typeNow' => 'question']);
    }
    
   //课堂作业选择题 
    public function actionChoice(){
        
        
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
        foreach(Tool::$EXER_TYPE as $type){
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
         }
        //
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        
        $wID=Yii::app()->session['workID']; 
        //显示选择题列表并分页  
        $result = Suite::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
         
         $isExam = FALSE;
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
            if($finishRecord[$type]!=null && $classwork[$type]!=null)
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
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $cNum=  Choice::model()->choiceCount('choice');   //动态长度
        Yii::app()->session['num']=$cNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord=Array();
        $number=Array();
        $arg=$_GET['cent'];
        $cent=  explode(',', $arg);
        $examInfo = Exam::model()->find($suiteID);
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
            $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            $n++;
        }
        
        return $this->render('choiceExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansChoice'=>$ansArr,'exercise'=>$classexam ,'exercise2'=>$classexam2 ,'choiceLst'=>$choiceLst,'pages'=>$pages, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'choice']);
    }
 //课堂作业填空题   
    public function actionfilling(){
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
        $isExam = false;
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
            if($finishRecord[$type]!=null && $classwork[$type]!=null)
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
       $suiteID = Yii::app()->session['examsuiteID'];
       $workID = Yii::app()->session['workID'];
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
         $examInfo = Exam::model()->find($suiteID);
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
            $cent[$n]=round(count($finishRecord[$type])*100/count($classexam[$type]),2)."%"; 
            $n++;
        }
       
        return $this->render('fillingExer',['number'=>$number,'cent'=>$cent,'workID'=>$workID,'ansFilling'=>$ansArr,'fillingLst'=>$fillingLst ,'pages'=>$pages,'exercise'=>$classexam ,'exercise2'=>$classexam2, 'isExam' => $isExam , 'examInfo'=>$examInfo, 'typeNow' => 'filling']);
    }
    
    
  //课堂作业套题  
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
        $workID = $_GET['workID'];
        $studentID = Yii::app()->session['userid_now'];
        Yii::app()->session['examsuiteID'] = $suiteID;
        Yii::app()->session['workID'] = $_GET['workID'];
        Yii::app()->session['suiteID'] = $suiteID;
        $classexam = Array();
        $record = SuiteRecord::model()->find("workID=? and studentID=?",array($workID,$studentID));
        $cent=Array("0"=>"0","1"=>"0","2"=>"0","3"=>"0","4"=>"0","5"=>"0");
        foreach(Tool::$EXER_TYPE as $type){
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $isExam = true;
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
        Yii::app()->session['isExam'] = $isExam;
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
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $workID = Yii::app()->session['workID'];
        $classworks = Suite::model()->getClassworkAll($currentLesn);
        $classwork = array();
        $ratio_accomplish='0';
        foreach ($classworks as $c){
            array_push($classwork, $c);
            $recordID=SuiteRecord::model()->find("workID=? and studentID=?",array($c['workID'],$studentID))['recordID'];
            print_r($recordID);
            if($recordID==null){
               
            }else{
                $ratio_accomplish = SuiteRecord::model()->getSuitRecordAccomplish($recordID);
            }
        }     
        return $this->render('classwork',['lessons'=>$lessons,'currentLesn'=>$currentLesn,'classwork'=>$classwork,'ratio_accomplish'=>$ratio_accomplish]);
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
    		$user = Student::model()->find('userID=?', array($userid_now));
                if($user->password== md5($_POST['old'])){
                    $user->password=md5($new1);
                    $user->mail_address=$email;
                    $result=$user->update();
                    echo $result;
                    $mail=$email;
    			
    		}else{
                    $result='old error';
    			$this->render('set',['result'=>$result,'mail'=>$mail]);
    			return;
                }
    		
    	}
    	
    	$this->render('set',['result'=>$result,'mail'=>$mail]);
    }
    
    public function actionHello(){
        return $this->render('hello',array(null));
    }
}