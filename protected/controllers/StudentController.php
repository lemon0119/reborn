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
    public $layout = '//layouts/studentBar';

    public function actionOverSuite() {
        //if (isset($_GET['isExam']) && $_GET['isExam'] =='false') {
        if ($_GET['isExam']) {
            //这里，应该改成修改考试记录examRecord
            ExamRecord::saveExamRecord($recordID);
            ExamRecord::overExam($recordID);
            //ExamRecord::overExam();
        } else {
            SuiteRecord::saveSuiteRecord($recordID);
            SuiteRecord::overSuite($recordID);
        }
    }
     protected function renderJSON($data) {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        foreach (Yii::app()->log->routes as $route) {
            if ($route instanceof CWebLogRoute) {
                $route->enabled = false; // disable any weblogroutes
            }
        }
        Yii::app()->end();
    }

    public function actionVirtualClass() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $userID = Yii::app()->session['userid_now'];
        $student = Student::model()->findByPK($userID);
        $userName = $student->userName;
        $classID = $student->classID;
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $student = Student::model()->find("userID = '$userID'");
        $exerciseIsOpenNow = ClassExercise::model()->getAllNowOpenExercise($classID);
        return $this->render('virtualClass', [ 'userID' => $student ['userID'], 'lessons' => $lessons, 'currentLesn' => $currentLesn, 'userName' => $userName, 'classID' => $classID, 'class' => $student ['classID'], 'exerciseIsOpenNow' => $exerciseIsOpenNow]);
    }

    public function actionAnslookType() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }

        $exerID = $_GET['exerID'];
        $type = $_GET['type'];
        $exer = Exercise::getExerise($exerID, $type);
        $studentID = Yii::app()->session['userid_now'];
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $recordID = ExamRecord::getRecord($workID, $studentID);
            $flag = $_GET['flag'];
        } else {
            $recordID = SuiteRecord::getRecord($workID, $studentID);
            $flag = '';
        }

        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, $type, $exerID);
        $score = AnswerRecord::model()->getScore($recordID, $exerID,$type);
        $correct = $answer['ratio_correct'];
        $n = strrpos($correct, "&");
        $correct = substr($correct, $n + 1);

        if(isset($answer['answerID'])){
            $answer_id=$answer['answerID'];
            $answer_data=  AnswerData::model()->find("answerID=?",array($answer_id));
        }else{
            $answer_data=NULL;
            $answer_id=NULL;
        }
        $correct_Number=$answer_data['correct_Number'];
        $n1 = strrpos($correct_Number, "&");
        $correct_Number = substr($correct_Number, $n1 + 1);
        //标准文本字数
        $standard_Number = $answer_data['standard_Number'];
        //作答文本字数
        $answer_number=$answer_data['answer_Number'];
        $n6=strrpos($answer_number,"&");
        $answer_Number = substr($answer_number, $n6 + 1);
        
        return $this->render('ansDetail_1', ['exercise' => $classwork,
                    'exer' => $exer,
                    'answer' => $answer['answer'],
                    'correct' => $correct,
                    'correct_Number' => $correct_Number,
                    'answer_Number' => $answer_Number,
                    'standard_Number' => $standard_Number,
                    'answer_id' =>$answer_id,
                    'type' => $type,
                    'flag' => $flag,
                    'score' =>$score
        ]);
    }

    public function actionAnsKeyType() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);

        $studentID = Yii::app()->session['userid_now'];
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $recordID = ExamRecord::getRecord($workID, $studentID);
            $flag = $_GET['flag'];
        } else {
            $recordID = SuiteRecord::getRecord($workID, $studentID);
            $flag = '';
        }
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        $score = AnswerRecord::model()->getScore($recordID, $exerID,'key');
        $correct = $answer['ratio_correct'];
        $n = strrpos($correct, "&");
        $correct = substr($correct, $n + 1);
        
        return $this->render('ansKey', ['exercise' => $classwork,
                    'exer' => $result,
                    'answer' => $answer['answer'],
                    'correct' => $correct,
                    'flag' =>$flag,
                    'score' =>$score
            ]);
    }

    public function actionAnsQuestion() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $recordID = ExamRecord::getRecord($workID, $studentID);
            $flag = $_GET['flag'];
        } else {
            $recordID = SuiteRecord::getRecord($workID, $studentID);
            $flag = '';
        }
        
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $score = AnswerRecord::model()->getChoiceScore($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        return $this->render('ansQuest', ['exercise' => $classwork, 'ansQuest' => $ansArr,'flag'=>$flag,'score'=>$score]);
    }

    public function actionAnsFilling() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $recordID = ExamRecord::getRecord($workID, $studentID);
            $flag = $_GET['flag'];
        } else {
            $recordID = SuiteRecord::getRecord($workID, $studentID);
            $flag = '';
        }
        $score = AnswerRecord::model()->getChoiceScore($recordID, 'filling');
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        return $this->render('ansFilling', ['exercise' => $classwork, 'ansFilling' => $ansArr,'flag'=>$flag,'score'=>$score]);
    }

    public function actionAnsChoice() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $studentID = Yii::app()->session['userid_now'];
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $recordID = ExamRecord::getRecord($workID, $studentID);
            $flag = $_GET['flag'];
        } else {
            $recordID = SuiteRecord::getRecord($workID, $studentID);
            $flag = '';
        }
        $score = AnswerRecord::model()->getChoiceScore($recordID,'choice');
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        return $this->render('ansChoice', ['exercise' => $classwork, 'ansChoice' => $ansArr,'flag' =>$flag ,'score' =>$score]);
    }

    public function actionViewAns() {
        if (isset($_GET['workID'])) {
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
        if (!$isExam) {
            $flag = '';
            foreach (Tool::$EXER_TYPE as $type) {
                $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            }
        } else {
            $flag = $_GET['flag'];
            foreach (Tool::$EXER_TYPE as $type) {
                $classwork[$type] = Exam::model()->getExamExerByType($suiteID, $type);
            }
        }

        return $this->render('suiteAns', ['exercise' => $classwork,'flag' =>$flag]);
    }

    public function actionSaveQuestion() {
        //查看是否有answer，即是否是用户提交了答案。
        if (isset($_POST['qType']) && $_POST['qType'] == "question") {
            if (Yii::app()->session['isExam'])
                ExamRecord::saveExamRecord($recordID);
            else
                SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveQuestion($recordID);
            if ($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
        }
    }

    public function actionSaveChoice() {
        //查看是否有answer，即是否是用户提交了答案。
        if (isset($_POST['qType']) && $_POST['qType'] == "choice") {
            if (Yii::app()->session['isExam'])
                ExamRecord::saveExamRecord($recordID);
            else
                SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveChoice($recordID);
            if ($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
        }
    }

    public function actionSaveFilling() {
        //查看是否有answer，即是否是用户提交了答案。
        if (isset($_POST['qType']) && $_POST['qType'] == "filling") {
            if (Yii::app()->session['isExam'])
                ExamRecord::saveExamRecord($recordID);
            else
                SuiteRecord::saveSuiteRecord($recordID);
            $result = AnswerRecord::saveFilling($recordID);
            if ($result == TRUE)
                echo '保存答案成功！';
            else
                echo '保存答案失败，请重新提交!';
        }
    }

    //我的科目
    public function actionMyCourse() {
        $isExam = false;
        Yii::app()->session['isExam'] = $isExam;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $level = Student::model()->findLevelByStudentID($studentID);
        if ($classID != "0") {
            $lessons = Lesson::model()->findAll("classID = '$classID'");
        } else {
            $lessons = array();
        }
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $currentLesn = isset($_GET['lessonID']) ? $_GET['lessonID'] : $currentLesn;
        $myCourse = Suite::model()->getClassworkAll($currentLesn,$level);
        $myCourses = array();
        $n = 0;
        $ratio_accomplish = array();
        foreach ($myCourse as $c) {
            array_push($myCourses, $c);
            $recordID[$n] = SuiteRecord::model()->find("workID=? and studentID=?", array($c['workID'], $studentID))['recordID'];
            if ($recordID == null) {
                return $this->render('myCourse', ['lessons' => $lessons, 'currentLesn' => $currentLesn, 'myCourse' => $myCourses]);
            } else {
                $ratio_accomplish[$n] = SuiteRecord::model()->getSuitRecordAccomplish($recordID[$n]);
            }
            $n++;
        }
        return $this->render('myCourse', ['lessons' => $lessons, 'currentLesn' => $currentLesn, 'myCourse' => $myCourses, 'ratio_accomplish' => $ratio_accomplish]);
    }

    public function actionlistenType() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        $result = ListenType::model()->findByPK($exerID);
        $isExam = false;
        $wID = Yii::app()->session['workID'];
        $isOver = '0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        if (isset($_GET['repeat'])) {
            $recordID = $record['recordID'];
            $exerciseID = $result['exerciseID'];
            $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'listen' AND createPerson LIKE '$studentID'");
            if ($sqlClassExerciseRecord !== null) {
                
                $answerID = $sqlClassExerciseRecord['answerID'];
                $answerData = AnswerData::model()->find("answerID = '$answerID'");
                $rankAnswer = RankAnswer::model()->find("answerID = '$answerID'");
                if($answerData != NULL) {
                 $answerData->delete();
                }
                if($rankAnswer!=NULL){
                    $rankAnswer ->delete();
                }
                $sqlClassExerciseRecord->delete();
            }
        }
        $finishRecord = Array();
        $exerciseID = $result['exerciseID'];
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if ($record == null) {
            SuiteRecord::saveSuiteRecord($recordID);
            return $this->render('listenExer', array('recordID' => $recordID, 'exercise' => $classwork, 'exercise2' => $classwork2, 'exerOne' => $result, 'isExam' => $isExam, 'cent' => $cent, 'workId' => $wID, 'isOver' => $isOver,'type_name' => "key",
                        'exer_id'=>$exerciseID));
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }
        

        return $this->render('listenExer', array(
                    'recordID' => $record['recordID'],
                    'exercise' => $classwork,
                    'exercise2' => $classwork2,
                    'exerOne' => $result,
                    'isExam' => $isExam,
                    'cent' => $cent,
                    'workId' => $wID, 'isOver' => $isOver,
                    'type_name' => "look",
                    'exer_id'=>$exerciseID
        ));
    }

    //2015-8-3 宋杰 获取考试听打练习
    public function actionExamlistenType() {
        if(isset(Yii::app()->session['userid_now'])){
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));

        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        if (isset($_GET['over'])) {
            $over = $_GET['over'];
        }
        Yii::app()->session['exerType'] = 'listen';
        //edit by LC
        //$result = ListenType::model()->findByPK($exerID);
        foreach ($classexam['listen'] as $listenType) {
            if ($listenType['exerciseID'] == $exerID) {
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
        $isOver = 0;
        if ($totalTime != 0)
            $isOver = $costTime < $totalTime ? 0 : 1;
        if (isset($over) && $over == 1) {
            $isOver = 1;
            $t = AnswerRecord::model()->find("recordID=? and type=?", array($record->recordID, 'listen'));
            $t->costTime = $totalTime;
            $t->update();
        }
        //end
        if ($recordID == null) {
            ExamRecord::saveExamRecord($recordID);
            return $this->renderpartial('listenExer', array('recordID' => $recordID, 'exercise' => $classexam, 'exerID' => $exerID, 'exercise2' => $classexam2, 'exerOne' => $result, 'cent' => $cent, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'listen', 'isOver' => $isOver, 'costTime' => $costTime,'exer_id'=>$exerID,'type_name'=>"listen"));
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'filling'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }


        return $this->renderpartial('listenExer', array(
                    'recordID' => $recordID,
                    'exercise' => $classexam,
                    'exercise2' => $classexam2,
                    'exerOne' => $result,
                    'cent' => $cent,
                    'isExam' => $isExam,
                    'examInfo' => $examInfo,
                    'exerID' => $exerID,
                    'typeNow' => 'listen',
                    'isOver' => $isOver, //edit by LC
                    'costTime' => $costTime,
                    'exer_id'=>$exerID,
                    'type_name'=>"listen"
        ));
    }
    else {
    $this->render('index');    
    }
    }
    //课堂看打练习
    public function actionlookType() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        $result = LookType::model()->findByPK($exerID);
        $isExam = false;
        $wID = Yii::app()->session['workID'];
        $isOver = '0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        if (isset($_GET['repeat'])) {
            $recordID = $record['recordID'];
            $exerciseID = $result['exerciseID'];
            $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'look' AND createPerson LIKE '$studentID'");
            if ($sqlClassExerciseRecord !== null) {
                $answerID = $sqlClassExerciseRecord['answerID'];
                $answerData = AnswerData::model()->find("answerID = '$answerID'");
                $rankAnswer = RankAnswer::model()->find("answerID = '$answerID'");
                if($answerData != NULL) {
                 $answerData->delete();
                }
                if($rankAnswer!=NULL){
                    $rankAnswer ->delete();
                }
                $sqlClassExerciseRecord->delete();
            }
        }
        $finishRecord = Array();
        $exerciseID = $result['exerciseID'];
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if ($record == null) {
            SuiteRecord::saveSuiteRecord($recordID);
            return $this->render('lookExer', array('recordID' => $recordID, 'exercise' => $classwork, 'exercise2' => $classwork2, 'exerOne' => $result, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID, 'isOver' => $isOver,'type_name' => "key",
                        'exer_id'=>$exerciseID));
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }
        
        return $this->render('lookExer', array(
                    'recordID' => $record['recordID'],
                    'exercise' => $classwork,
                    'exercise2' => $classwork2,
                    'exerOne' => $result,
                    'isExam' => $isExam,
                    'cent' => $cent,
                    'workID' => $wID, 'isOver' => $isOver,
                    'type_name' => "look",
                    'exer_id'=>$exerciseID
        ));
    }

    //2015-8-3 宋杰 获取考试看打练习
    public function actionExamlookType() {
        if(isset(Yii::app()->session['userid_now'])){
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));

        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        if (isset($_GET['over'])) {
            $over = $_GET['over'];
        }
        Yii::app()->session['exerType'] = 'look';
        //edit by LC
        //$result = LookType::model()->findByPK($exerID);
        foreach ($classexam['look'] as $lookType) {
            if ($lookType['exerciseID'] == $exerID) {
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
        $isOver = 0;
        if ($totalTime != 0)
            $isOver = $costTime < $totalTime ? 0 : 1;

        if (isset($over) && $over == 1) {
            $isOver = 1;
            $t = AnswerRecord::model()->find("recordID=? and type=?", array($record->recordID, 'look'));
            $t->costTime = $totalTime;
            $t->update();
        }
        //end
        if ($recordID == null) {
            ExamRecord::saveExamRecord($recordID);
            return $this->renderpartial('lookExer', array(
                        'recordID' => $recordID,
                        'exercise' => $classexam,
                        'exercise2' => $classexam2,
                        'exerOne' => $result,
                        'exerID' => $exerID,
                        'cent' => $cent,
                        'isExam' => $isExam,
                        'examInfo' => $examInfo,
                        'typeNow' => 'look',
                        'isOver' => $isOver, //edit by LC
                        'costTime' => $costTime,
                        'exer_id'=>$exerID,
                        'type_name'=>"look"));
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'filling'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }


        return $this->renderpartial('lookExer', array(
                    'recordID' => $recordID,
                    'exercise' => $classexam,
                    'exercise2' => $classexam2,
                    'exerOne' => $result,
                    'cent' => $cent,
                    'isExam' => $isExam,
                    'examInfo' => $examInfo,
                    'exerID' => $exerID,
                    'typeNow' => 'look',
                    'isOver' => $isOver, //edit by LC
                    'costTime' => $costTime,
                    'exer_id'=>$exerID,
                    'type_name'=>"look"
        ));
    }
    else{
        $this->render('index');
    }
    }
    //课堂键位练习
    public function actionKeyType() {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classwork = Array();
        $classwork2 = Array();
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        $isExam = false;
        $wID = Yii::app()->session['workID'];
        $isOver = '0';
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        if (isset($_GET['repeat'])) {
            $recordID = $record['recordID'];
            $exerciseID = $result['exerciseID'];
            $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = $recordID AND exerciseID = '$exerciseID' AND type = 'key' AND createPerson LIKE '$studentID'");
            
            if ($sqlClassExerciseRecord !== null) {
                $answerID = $sqlClassExerciseRecord['answerID'];
                $answerData = AnswerData::model()->find("answerID = '$answerID'");
                $rankAnswer = RankAnswer::model()->find("answerID = '$answerID'");
                if($answerData != NULL) {
                 $answerData->delete();
                }
                if($rankAnswer!=NULL){
                    $rankAnswer ->delete();
                }
                $sqlClassExerciseRecord->delete();
             }
             
        }
        $finishRecord = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerciseID = $result['exerciseID'];
        if ($record == null) {
            SuiteRecord::saveSuiteRecord($recordID);
            return $this->render('keyExer', array('recordID' => $recordID, 'exercise' => $classwork, 'exercise2' => $classwork2, 'exerOne' => $result, 'isExam' => $isExam, 'cent' => $cent, 'workId' => $wID, 'isOver' => $isOver,'type_name' => "key",
                        'exer_id'=>$exerciseID));
        } else {
            foreach (Tool::$EXER_TYPE as $type) {
                $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
                $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
                $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
            }
            $n = 0;
            foreach (Tool::$EXER_TYPE as $type) {
                if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                    $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
                else
                    $cent[$n] = '0';
                $n++;
            }

            return $this->render('keyExer', array(
                        'recordID' => $record->recordID,
                        'exercise' => $classwork,
                        'exercise2' => $classwork2,
                        'exerOne' => $result,
                        'isExam' => $isExam,
                        'cent' => $cent,
                        'workId' => $wID,
                        'isOver' => $isOver,
                        'type_name' => "key",
                        'exer_id'=>$exerciseID
            ));
        }
    }

    public function actionExamKeyType() {
//           if(!AnswerRecord::saveExamRecord($recordID))
//                    return false;
//            AnswerRecord::saveAnswer($recordID,0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0,1);
        if(isset(Yii::app()->session['userid_now'])){
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        if (isset($_GET['over'])) {
            $over = $_GET['over'];
        }
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        //edit by LC
        //$result = KeyType::model()->findByPK($exerID);
        foreach ($classexam['key'] as $keyType) {
            if ($keyType['exerciseID'] == $exerID) {
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
        $isOver = 0;
        if ($totalTime != 0)
            $isOver = $costTime < $totalTime ? 0 : 1;
        if (isset($over) && $over == 1) {
            $isOver = 1;
            $t = AnswerRecord::model()->find("recordID=? and type=?", array($record->recordID, 'key'));
            $t->costTime = $totalTime;
            $t->update();
        }
        //end
        if ($recordID == null) {
            //SuiteRecord::saveSuiteRecord($recordID);
            ExamRecord::saveExamRecord($recordID);
            return $this->renderpartial('keyExer', array('recordID' => $recordID, 'exercise' => $classexam, 'exerID' => $exerID, 'exerOne' => $result, 'exercise2' => $classexam2, 'cent' => $cent, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'key', 'isOver' => $isOver, 'costTime' => $costTime,'exer_id'=>$exerID,'type_name'=>"key"));
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }
        return $this->renderpartial('keyExer', array(
                    'recordID' => $record->recordID,
                    'exercise' => $classexam,
                    'exerOne' => $result,
                    'exercise2' => $classexam2,
                    'cent' => $cent,
                    'isExam' => $isExam,
                    'examInfo' => $examInfo,
                    'typeNow' => 'key',
                    'exerID' => $exerID,
                    'isOver' => $isOver, //edit by LC
                    'costTime' => $costTime,
                    'exer_id'=>$exerID,
                    'type_name'=>"key"
        ));
    }
    $this->render('index');
    }
    //课堂作业简答题 
    public function actionQuestion() {
        $isExam = FALSE;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $qNum = Choice::model()->choiceCount('question');
        Yii::app()->session['num'] = $qNum;
        $classwork = Array();
        $classwork = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);

        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        //分页
        $result = Suite::model()->getQuestion2($suiteID);
        $questionLst = $result ['questionLst'];
        $pages = $result ['pages'];
        $wID = Yii::app()->session['workID'];
        Yii::app()->session['questionNum'] = count($classwork['question']);
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        $finishRecord = Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
        }
        if ($record == null) {
            return $this->render('questionExer', ['number' => $number, 'ansQuest' => $ansArr, 'questionLst' => $questionLst, 'exercise2' => $classwork2, 'exercise' => $classwork, 'pages' => $pages, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'question'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }

        return $this->render('questionExer', ['number' => $number, 'ansQuest' => $ansArr, 'questionLst' => $questionLst, 'exercise2' => $classwork2, 'exercise' => $classwork, 'pages' => $pages, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
    }

    //2015-8-3 宋杰 获取考试简答题
    public function actionExamQuestion() {
        $isExam = true;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $qNum = Choice::model()->choiceCount('question');
        Yii::app()->session['num'] = $qNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $examInfo = Exam::model()->findByPK($suiteID);
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansQuest = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'question');
        $ansArr = AnswerRecord::model()->ansToArray($ansQuest);
        //分页
        $result = Exam::model()->getQuestion2($suiteID);
        $questionLst = $result ['questionLst'];
        $pages = $result ['pages'];

        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        if ($record == null) {
            return $this->renderpartial('questionExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansQuest' => $ansArr, 'questionLst' => $questionLst, 'pages' => $pages, 'exercise2' => $classexam2, 'exercise' => $classexam, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'question']);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'question'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }

        return $this->renderpartial('questionExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansQuest' => $ansArr, 'questionLst' => $questionLst, 'pages' => $pages, 'exercise2' => $classexam2, 'exercise' => $classexam, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'question']);
    }

    //课堂作业选择题 
    public function actionChoice() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $isExam = FALSE;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $cNum = Choice::model()->choiceCount('choice');   //动态长度
        Yii::app()->session['num'] = $cNum;
        $classwork = Array();
        $classwork2 = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        $finishRecord = Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        $wID = Yii::app()->session['workID'];
        //显示选择题列表并分页  
        $result = Suite::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if ($record == null) {
            return $this->renderpartial('choiceExer', ['number' => $number, 'ansChoice' => $ansArr, 'choiceLst' => $choiceLst, 'pages' => $pages, 'exercise2' => $classwork2, 'exercise' => $classwork, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'choice'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }



        return $this->render('choiceExer', ['number' => $number, 'ansChoice' => $ansArr, 'choiceLst' => $choiceLst, 'pages' => $pages, 'exercise2' => $classwork2, 'exercise' => $classwork, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
    }

    //2015-8-3 宋杰 获取试题，跳转到选择题页面 isExam为true加载examsidebar
    public function actionExamChoice() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $isExam = true;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $cNum = Choice::model()->choiceCount('choice');   //动态长度
        Yii::app()->session['num'] = $cNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $examInfo = Exam::model()->findByPK($suiteID);
        //
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansChoice = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'choice');
        $ansArr = AnswerRecord::model()->ansToArray($ansChoice);
        //显示选择题列表并分页  
        $result = Exam::model()->getChoice2($suiteID);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];

        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        if ($record == null) {
            return $this->renderpartial('choiceExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansChoice' => $ansArr, 'exercise' => $classexam, 'exercise2' => $classexam2, 'choiceLst' => $choiceLst, 'pages' => $pages, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'choice']);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'choice'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0) {
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            } else
                $cent[$n] = '0';
            $n++;
        }

        return $this->renderpartial('choiceExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansChoice' => $ansArr, 'exercise' => $classexam, 'exercise2' => $classexam2, 'choiceLst' => $choiceLst, 'pages' => $pages, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'choice']);
    }

    //课堂作业填空题   
    public function actionfilling() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $isExam = false;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $studentID = Yii::app()->session['userid_now'];
        $fNum = Choice::model()->choiceCount('filling');
        Yii::app()->session['num'] = $fNum;
        $classwork = Array();
        $classwork2 = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        $finishRecord = Array();
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        //分页
        $result = Suite::model()->getFilling2($suiteID);
        $fillingLst = $result ['fillingLst'];
        $pages = $result ['pages'];

        $wID = Yii::app()->session['workID'];
        $recordID = SuiteRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
        }
        if ($record == null) {
            return $this->render('fillingExer', ['number' => $number, 'ansFilling' => $ansArr, 'ansFilling' => $ansArr, 'fillingLst' => $fillingLst, 'exercise2' => $classwork2, 'exercise' => $classwork, 'pages' => $pages, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $classwork2[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'filling'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }


        return $this->render('fillingExer', ['number' => $number, 'ansFilling' => $ansArr, 'ansFilling' => $ansArr, 'fillingLst' => $fillingLst, 'exercise2' => $classwork2, 'exercise' => $classwork, 'pages' => $pages, 'isExam' => $isExam, 'cent' => $cent, 'workID' => $wID]);
    }

    //2015-8-3 宋杰 加载考试填空题
    public function actionExamfilling() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $isExam = true;
        Yii::app()->session['isExam'] = $isExam;
        $suiteID = Yii::app()->session['examsuiteID'];
        $workID = Yii::app()->session['examworkID'];
        $studentID = Yii::app()->session['userid_now'];
        $fNum = Choice::model()->choiceCount('filling');
        Yii::app()->session['num'] = $fNum;
        $classexam = Array();
        $classexam2 = Array();
        $finishRecord = Array();
        $number = Array();
        $arg = $_GET['cent'];
        $cent = explode(',', $arg);
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        $examInfo = Exam::model()->findByPK($suiteID);
        //
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $ansFilling = $recordID == NULL ? NULL : AnswerRecord::model()->getAnswerByType($recordID, 'filling');
        $ansArr = AnswerRecord::model()->ansToArray($ansFilling);
        //分页
        $result = Exam::model()->getFilling2($suiteID);
        $fillingLst = $result ['fillingLst'];
        $pages = $result ['pages'];
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }
        if ($recordID == null) {
            return $this->renderpartial('fillingExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansFilling' => $ansArr, 'fillingLst' => $fillingLst, 'pages' => $pages, 'exercise' => $classexam, 'exercise2' => $classexam2, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'filling']);
        }
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $classexam2[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $number = AnswerRecord::model()->findAll("recordID=? and type=?", array($record->recordID, 'filling'));
        $n = 0;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classexam[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }

        return $this->renderpartial('fillingExer', ['number' => $number, 'cent' => $cent, 'workID' => $workID, 'ansFilling' => $ansArr, 'fillingLst' => $fillingLst, 'pages' => $pages, 'exercise' => $classexam, 'exercise2' => $classexam2, 'isExam' => $isExam, 'examInfo' => $examInfo, 'typeNow' => 'filling']);
    }

    //课堂作业套题  
    public function actionClswkOne() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $workID = $_GET['suiteID'];
        $isExam = false;
        $type_name="";
        $exer_id="";
        if(isset($_GET['exerID'])){
            $exer_id=$_GET['exerID'];
        }
        if(isset($_GET['type'])){
            error_log(1111);
            if($_GET['type']===4){
                $type_name="key";
            }else if($_GET['type']===5){
                $type_name="look";
            }else if($_GET['type']===6){
                $type_name="listen";
            }
        }
        Yii::app()->session['isExam'] = $isExam;
        Yii::app()->session['workID'] = $workID;
        $clsLesnSuite = ClassLessonSuite::model()->findByPK($workID);
        Yii::app()->session['suiteID'] = $clsLesnSuite->suiteID;
        $suiteID = Yii::app()->session['suiteID'];
        $studentID = Yii::app()->session['userid_now'];
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $studentID));
        $cent = Array("0" => "0", "1" => "0", "2" => "0", "3" => "0", "4" => "0", "5" => "0");
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if ($record == null) {
            return $this->render('suiteDetail', ['exercise' => $classwork, 'isExam' => $isExam, 'cent' => $cent,'recordID'=>$record['recordID'],'exer_id'=>$exer_id,'type_name'=>$type_name]);
        }
        $finishRecord = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($clsLesnSuite->suiteID, $type);
            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson=?", array($record->recordID, $type, $studentID));
        }
        $n = 0;
        $cNum = count($classwork['choice']);
        $fNum = count($classwork['filling']);
        $qNum = count($classwork['question']);
        $num = (($cNum > $fNum) ? $cNum : $fNum) > $qNum ? (($cNum > $fNum) ? $cNum : $fNum) : $qNum;
        Yii::app()->session['num'] = $num;
        foreach (Tool::$EXER_TYPE as $type) {
            if (count($finishRecord[$type]) != 0 && count($classwork[$type]) != 0)
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classwork[$type]), 2) . "%";
            else
                $cent[$n] = '0';
            $n++;
        }
        return $this->render('suiteDetail', ['exercise' => $classwork, 'isExam' => $isExam, 'cent' => $cent,'recordID'=>$record['recordID'],'exer_id'=>$exer_id,'type_name'=>$type_name]);
    }

    //获取考试套题
    public function actionClsexamOne() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $type_name="";
        $exer_id="";
        $isExam = true;
        $suiteID = $_GET['suiteID'];
        $workID = $_GET['workID'];
        $studentID = Yii::app()->session['userid_now'];
        Yii::app()->session['isExam'] = $isExam;
        Yii::app()->session['examsuiteID'] = $suiteID;
        Yii::app()->session['examworkID'] = $_GET['workID'];
        Yii::app()->session['suiteID'] = $suiteID;
        if(isset($_GET['exerID'])){
            $exer_id=$_GET['exerID'];
        }
        if(isset($_GET['type'])){
            if($_GET['type']===4){
                $type_name="key";
            }else if($_GET['type']===5){
                $type_name="look";
            }else if($_GET['type']===6){
                $type_name="listen";
            }
        }
        //Yii::app()->session['examID'] = $suiteID;
        $classexam = Array();
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $studentID,));

        $cent = Array("0" => "0", "1" => "0", "2" => "0", "3" => "0", "4" => "0", "5" => "0");
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);
        }

        $examInfo = Exam::model()->findByPK($suiteID);
        if ($record == null) {
            return $this->render('suiteDetail', ['exercise' => $classexam, 'isExam' => $isExam, 'examInfo' => $examInfo, 'cent' => $cent,'recordID'=>$record['recordID'],'exer_id'=>$exer_id,'type_name'=>$type_name]);
        }
        $finishRecord = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classexam[$type] = ExamExercise::model()->getExamExerByType($suiteID, $type);


            $finishRecord[$type] = AnswerRecord::model()->findAll("recordID=? and type=? and createPerson", array($record->recordID, $type));
        }
        $n = 0;
        $cNum = count($classexam['choice']);
        $fNum = count($classexam['filling']);
        $qNum = count($classexam['question']);
        $num = (($cNum > $fNum) ? $cNum : $fNum) > $qNum ? (($cNum > $fNum) ? $cNum : $fNum) : $qNum;
        Yii::app()->session['num'] = $num;

        foreach (Tool::$EXER_TYPE as $type) {

            if (count($classexam[$type]) != 0) {
                $cent[$n] = round(count($finishRecord[$type]) * 100 / count($classexam[$type]), 2) . "%";
            }
            $n++;
        }
        return $this->render('suiteDetail', ['exercise' => $classexam, 'isExam' => $isExam, 'examInfo' => $examInfo, 'cent' => $cent,'recordID'=>$record['recordID'],'exer_id'=>$exer_id,'type_name'=>$type_name]);
    }

    public function actionWebrtc() {
        $studentID = Yii::app()->session['userid_now'];
        $studentName = Student::model()->findByPK($studentID)->userName;
        return $this->render('webrtc', ['studentName' => $studentName]);
    }

    public function actionProDetail() {
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['recordID'] = $_GET['recordID'];
        $suiteName = Suite::model()->find('suiteID=?', [$suiteID])['suiteName'];
        $result = Student::model()->getAnswerRecordAll($suiteID);
        return $this->render('proDetail', ['result' => $result, 'suiteName' => $suiteName]);
    }

    public function actionSaveAnswer() {
        if ($this->saveAnswer())
            echo '提交答案成功！';
        else
            echo '对不起，提交答案失败。';
    }

    public function actionAnswerDetail() {
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"], "", $type);
        $exer = Exercise::getExerise($exerID, $type);
        $answer = Yii::app()->session['answer'];
        return $this->render('ansDetail', ['exer' => $exer, 'answer' => $answer]);
    }

    //课堂作业
    public function actionClasswork() {
        $isExam = false;
        Yii::app()->session['isExam'] = $isExam;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $level = Student::model()->findLevelByStudentID($studentID);
        $lessons = Lesson::model()->findAll("classID = '$classID'");
        $currentLesn = TbClass::model()->findlessonByClassID($classID);
        $workID = Yii::app()->session['workID'];
        $classworks = Suite::model()->getClassworkAll($currentLesn,$level);
        $classwork = array();
        $ratio_accomplish = '0';
        $n = 0;
        foreach ($classworks as $c) {
            array_push($classwork, $c);
            $recordID[$n] = SuiteRecord::model()->find("workID=? and studentID=?", array($c['workID'], $studentID))['recordID'];
            if ($recordID == null) {
                return $this->render('classwork', ['lessons' => $lessons, 'currentLesn' => $currentLesn, 'classwork' => $classwork]);
            } else {
                $ratio_accomplish[$n] = SuiteRecord::model()->getSuitRecordAccomplish($recordID[$n]);
            }
            $n++;
        }
        return $this->render('classwork', ['lessons' => $lessons, 'currentLesn' => $currentLesn, 'classwork' => $classwork, 'ratio_accomplish' => $ratio_accomplish]);
    }

    //宋杰 2015-7-30 课堂考试
    public function actionClassExam() {
        Yii::app()->session['isExam'] = 1;
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $classexams = Exam::model()->getClassexamAll($classID);
        $classexam = array();
        $ratio_accomplish = '0';
        $n = 0;
        $score = '';
        foreach ($classexams as $c) {
            array_push($classexam, $c);
            $recordID[$n] = ExamRecord::model()->find("workID=? and studentID=?", array($c['workID'], $studentID))['recordID'];
            $score[$n] = ExamRecord::model()->find("workID=? and studentID=?", array($c['workID'], $studentID))['score'];
            if ($recordID == null || $score == null) {
                return $this->render('classexam', ['score' => $score, 'classexams' => $classexam]);
            } else {
                $ratio_accomplish[$n] = ExamRecord::model()->getExamRecordAccomplish($recordID[$n]);
            }
            $n++;
        }
        return $this->render('classexam', ['score' => $score, 'classexams' => $classexam, 'ratio_accomplish' => $ratio_accomplish, 'classID' => $classID]);
    }

    public function actionProgress() {
        $type = $_GET['type'];
        Yii::app()->session['type'] = $type;
        Yii::app()->session['progress'] = 'true';
        $result = Student::model()->getAnswerRecordSub();
        return $this->render('progress', ['result' => $result]);
    }

    public function actionPreExer() {
        $type = Yii::app()->session['type'];
        $page = Yii::app()->session['page'];
        $exerType = Yii::app()->session['exerType'];
        $progress = Yii::app()->session['progress'];
        if ($progress === 'true') {
            $this->redirect(['student/progress', 'type' => $type]);
        } else {
            if ($type === 'classwork')
                $this->redirect(['student/classwork', 'exerType' => $exerType, "page" => $page]);
            else
                $this->redirect(['student/Exer', 'exerType' => $exerType, "page" => $page]);
        }
    }

    public function actionExer() {
        $this->saveParam();
        $exerType = Yii::app()->session['exerType'];
        $suiteID = Yii::app()->session['suiteID'];
        Yii::app()->session['type'] = 'exercise';
        Yii::app()->session['progress'] = 'false';
        //判断内部选择的题型，以便包含不同的页面。
        if ($exerType == 'listenExer') {
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

    public function saveAnswer() {
        //查看是否有answer，即是否是用户提交了答案。
        if (isset($_POST['nm_answer'])) {
            $answer = $_POST['nm_answer'];
            $seconds = $_POST['nm_cost'];
            if (Yii::app()->session['isExam']) {
                $workID = Yii::app()->session['examworkID'];
                $createPerson = Yii::app()->session['userid_now'];
                $oldID = ExamRecord::model()->getRecord($workID, $createPerson);
                $studentID = Yii::app()->session['userid_now'];
                $recordID = ExamRecord::getRecord($workID, $studentID);
                return AnswerRecord::model()->updateAnswer($recordID, $answer, $seconds);
            } else {
                $workID = Yii::app()->session['workID'];
                $createPerson = Yii::app()->session['userid_now'];
                $recordID = SuiteRecord::model()->getRecord($workID, $createPerson);
                return AnswerRecord::model()->updateAnswer($recordID, $answer, $seconds);
            }
        }
    }

    public function saveParam() {
        //将get参数，存入session。
        if (isset($_GET['courseID'])) {
            $courseID = $_GET['courseID'];
            Yii::app()->session['courseID'] = $courseID;
            unset($_GET['courseID']);
        }
        if (isset($_POST['suite'])) {
            $suiteID = $_POST["suite"];
            Yii::app()->session['suiteID'] = $suiteID;
        }
        if (isset($_GET['exerType'])) {
            $exerType = $_GET['exerType'];
            Yii::app()->session['exerType'] = $exerType;
            unset($_GET['exerType']);
        }
    }

    public function doListenExer($suiteID) {
        $result = ListenType::model()->getListenExer($suiteID);
        return $this->render('Exer', array(
                    'exercise' => $result["exercise"],
                    'pages' => $result["pages"],
                        ), false, true);
    }

    public function doKnlgExer($suiteID) {
        $choice = Suite::model()->getchoice($suiteID);
        $filling = Suite::model()->getFilling($suiteID);
        $question = Suite::model()->getQuestion($suiteID);
        return $this->render('Exer', array(
                    'choice' => $choice,
                    'filling' => $filling,
                    'question' => $question,
        ));
    }

    public function doLookExer($suiteID) {
        $result = LookType::model()->getLookExer($suiteID);
        return $this->render('Exer', array(
                    'exercise' => $result["exercise"],
                    'pages' => $result["pages"],
                        ), false, true);
    }

    public function doKeyExer($suiteID) {
        $result = KeyType::model()->getKeyExer($suiteID);
        return $this->render('Exer', array(
                    'exercise' => $result["exercise"],
                    'pages' => $result["pages"],
                        ), false, true);
    }

    public function actionIndex() {
//        if(isset($_GET['insert'])){
//            //导入数据库略码
//            $file_dir="E:\\php_workstation\\reborn\\7.txt"; 
//                $fp=fopen($file_dir,"r"); 
//                $content=fread($fp,filesize($file_dir));//读文件 
//                fclose($fp); 
//                $str = explode("\r\n", $content);
//                foreach ($str as $v){
//                    if($v!==''){
//                        WordsLibBrief::model()->insertBrief($v); 
//                    }
//                }
//                //向略码表导入正常输入的yaweiCode
//            $TwoWordsLibBrief=TwoWordsLibBrief::model()->findAll("yaweiCode LIKE ''");
//            foreach ($TwoWordsLibBrief as $value) {
//                $v = $value['words'];
//                $TwoWordsLib = TwoWordsLib::model()->find("words LIKE '$v'");
//                $yaweiCode = $TwoWordsLib['yaweiCode'];
//                $newTwoWordsLibBrief = new TwoWordsLibBrief();
//                $newTwoWordsLibBrief = TwoWordsLibBrief::model()->find("words LIKE '$v'");
//                $newTwoWordsLibBrief->yaweiCode = $yaweiCode;
//                $newTwoWordsLibBrief->update();
//            }
//            
//        }
//        判断重复登录
//        $userID = Yii::app()->session['userid_now'];
//        Student::model()->isLogin($userID, 1);        
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
          Yii::app()->session['lastUrl'] = "index";
          if(isset(Yii::app()->session['userid_now'])){
           $userID =  Yii::app()->session['userid_now'];
          $publishtime = date('y-m-d H:i:s',time());
          $sn = strtotime($publishtime);
          Student::model()->isLogin($userID, $sn);
          $isl = Student::model()->find("userID = '$userID'");
          $isl = $isl['is_login'];
          $s = Yii::app()->session['islogin']=$isl;
          $this->render('index'); //,['info'=>$info]);
          }else{
              $this->render('index');
          }
    }

    public function actionHeadPic() {
        if(isset(Yii::app()->session['userid_now'])){
        $picAddress = "";
        $result = 0;
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        $picAddress = $user['img_address'];
        $this->render('headPic', ['result' => $result, 'picAddress' => $picAddress]);
    }
    else{
      $this->render('index');  
    }
    }
    public function actionAddHeadPic() {
        $result = "上传失败!";
        $flag = 0;
        $picAddress = "";
        $userid_now = Yii::app()->session['userid_now'];
        $user = Student::model()->find('userID=?', array($userid_now));
        $picAddress = $user['img_address'];
        if (!isset($_FILES["file"])) {
            $result = "请选择文件！";
            $this->render('headPic', ['result' => $result, 'picAddress' => $picAddress]);
        }
        if (($_FILES ["file"] ["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/pjpeg")) {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES ["file"] ["tmp_name"], "img/head/" . $newName);
                $result = "上传成功！";
                $user->img_address = "img/head/" . $newName;
                $picAddress = "img/head/" . $newName;
                $user->update();
            }
        } else {
            $result = "请上传正确的文件！";
        }
        $this->render('headPic', ['result' => $result, 'picAddress' => $picAddress]);
    }

    public function actionSet() {       //set
        if(isset(Yii::app()->session['userid_now'])){
        $result = 'no';
        $mail = '';
        //head img
        $y = '0';
        $picAddress = '0';
        $flag = 'no';
        if (isset($_POST ['flag'])) {
            $flag = '1';
        }
        if ($flag == '1') {
            if (!isset($_FILES["file"])) {
                echo "请选择文件！";
                return;
            }
            if (!empty($_FILES ['file'] ['name'])) {
                if ((($_FILES ["file"] ["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/pjpeg")) && ($_FILES ["file"] ["size"] < 200000000)) {
                    if ($_FILES ["file"] ["error"] > 0) {
                        $result = "Return Code: " . $_FILES["file"]["error"];
                    } else {
                        if (file_exists("img/head/" . $_FILES ["file"] ["name"])) {
                            $result = "already exists.";
                        } else {
                            $y = '1';
                            $oldName = $_FILES["file"]["name"];
                            $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                            move_uploaded_file($_FILES ["file"] ["tmp_name"], "img/head/" . $newName);
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
        $picAddress = $user->img_address;
        if (!empty($user->mail_address)) {
            $mail = $user->mail_address;
        }
        if (isset($_POST['old'])) {
            $new1 = $_POST['new1'];
            $defnew = $_POST['defnew'];
//            $email = $_POST['email'];

            $usertype = Yii::app()->session['role_now'];
            $user = Student::model()->find('userID=?', array($userid_now));
            if ($user->password == md5($_POST['old'])) {
                $user->password = md5($new1);
//                $user->mail_address = $email;
                if ($y == '1')
                    $user->img_address = "img/head/" . $newName;
                $picAddress = $user->img_address;
                $result = $user->update();
//                $mail = $email;
            }else {
                $result = 'old error';
                $this->render('set', ['flag' => $flag, 'result' => $result, 'mail' => $mail, 'picAddress' => $picAddress]);
                return;
            }
        }
        $this->render('set', ['flag' => $flag, 'result' => $result, 'mail' => $mail, 'picAddress' => $picAddress]);
    }
    else{$this->render('index');}
    }
    public function actionHello() {
        return $this->render('hello', array(null));
    }

    //公告信息
    public function actionStuNotice() {
        if(isset(Yii::app()->session['userid_now'])){
        $result = Notice::model()->findNotice();
        $noticeRecord = $result ['noticeLst'];
        $pages = $result ['pages'];
        $studentID = Yii::app()->session['userid_now'];
        $noticeS = Student::model()->findByPK($studentID);
        $noticeS->noticestate = '0';
        $noticeS->update();
        $this->render('stuNotice', array('noticeRecord' => $noticeRecord, 'pages' => $pages));
    }
    else{
       $this->render('index'); 
    }
    }
    //公告内容
    public function ActionNoticeContent() {
        $result = 0;
        if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            $result = 1;
        }
        $id = $_GET['id'];
        $noticeRecord = Notice::model()->find("id= '$id'");
        $this->render('noticeContent', array('noticeRecord' => $noticeRecord));
    }

    //速录百科
    public function actionSuLu() {
        return $this->render('suLu');
    }
    //获取帮助
    public function actionGetHelp() {
        return $this->renderpartial('getHelp');
    }
    //联系我们
    public function actionContact(){
        return $this->renderpartial('contact');
    }
    //法律声明
    public function actionlegalNotice() {
        return $this->renderpartial('legalNotice');
    }
            
    public function actionScheduleDetil() {
        //查询任课班级科目
        $userID = Yii::app()->session['userid_now'];
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
    public function actionStuInformation() {
        if(isset(Yii::app()->session['userid_now'])){
        $ID = Yii::app()->session['userid_now'];
        $student = Student::model()->find("userID = '$ID'");
        return $this->render('stuInformation', array(
                    'id' => $student ['userID'],
                    'name' => $student ['userName'],
                    'class' => $student ['classID'],
                    'sex' => $student['sex'],
                    'age' => $student['age'],
                    'password' => $student['password'],
                    'mail_address' => $student['mail_address'],
                    'phone_number' => $student['phone_number']
        ));
    }
    else{$this->render('index');}
    }
    public function actionFreePractice() {
        $classExerciseLsts = Array();
        $nowLesson = "";
        $studentID = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($studentID);
        $level = Student::model()->findLevelByStudentID($studentID);
        if ($classID != "0") {
            $lessons = Lesson::model()->findAll("classID = '$classID'");
        } else {
            $lessons = array();
        }
        if (isset($_GET['lessonID'])) {
            $lessonID = $_GET['lessonID'];
            $nowLesson = Lesson::model()->find("lessonID = '$lessonID'");
            $classExerciseLst = ClassExercise::model()->getClassExerciselst($lessonID, $classID, $level);
            
            $array = Array();
            foreach ($classExerciseLst as $list) {
                
                if (strstr($list['content'], "$$")) {
                    $string = "";
                    $list['content'] = str_replace("$$", " ", $list['content']);
                    $array = explode(" ", $list['content']);

                    foreach ($array as $arr) {
                        $pos = strpos($arr, "0");
                        $arr = substr($arr, $pos + 1);
                        $string = $string . " " . $arr;
                    }
                    $list['content'] = $string;
                }
                array_push($classExerciseLsts, $list);
            }
        }
        return $this->render('freePractice', ['lessons' => $lessons, 'nowlesson' => $nowLesson, 'classExerciseLst' => $classExerciseLsts]);
    }
    public function actionStartClassExercise() {
        
        $array_exerciseID = [];
        $array_type = [];
        $array_title = [];
        $classID = $_GET['classID'];
        $lessonID = $_GET['lessonID'];
        $studentID = Yii::app()->session['userid_now'];
        $level = Student::model()->findLevelByStudentID($studentID);
        $classExercise = ClassExercise::model()->getClassExercise($lessonID, $classID, $level);
        foreach ($classExercise as $key => $value) {           
            $array_exerciseID[$key]=$value['exerciseID'];
            $array_type[$key]=$value['type'];
            $array_title[$key]=$value['title'];
        }
        $this->renderJSON(['exerciseID'=>$array_exerciseID,'type'=>$array_type,'title'=>$array_title]);
    }
    
    
    

    public function actionPassClassExercise() {
        $exerciseID = $_GET['exerciseID'];
        $data = ClassExercise::model()->getByExerciseID($exerciseID)['type'];
        echo $data;
    }

    public function actionIframe4finish() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getNowOpenExercise($exerciseID);
        $this->renderPartial("Iframe4finish", ["classExercise" => $classExercise]);
    }

    public function actionIframe4Look() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getNowOpenExercise($exerciseID);
        $this->renderPartial("Iframe4Look", ["classExercise" => $classExercise]);
    }

    public function actionIframe4Listen() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getNowOpenExercise($exerciseID);
        $this->renderPartial("Iframe4Listen", ["classExercise" => $classExercise]);
    }

    public function actionIframe4Key() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getNowOpenExercise($exerciseID);
        $this->renderPartial("Iframe4Key", ["classExercise" => $classExercise]);
    }

    public function actionFreeIframe4Look() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getByExerciseID($exerciseID);
        $this->renderPartial("Iframe4Look", ["classExercise" => $classExercise]);
    }
    public function actionFreeIframe4Looks() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getByExerciseID($exerciseID);
        $this->renderPartial("Iframe4Looks", ["classExercise" => $classExercise]);
    }
    public function actionFreeIframe4Listen() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getByExerciseID($exerciseID);
        $this->renderPartial("Iframe4Listen", ["classExercise" => $classExercise]);
    }

    public function actionFreeIframe4Key() {
        $exerciseID = $_GET['exerciseID'];
        $classExercise = ClassExercise::model()->getByExerciseID($exerciseID);
        $this->renderPartial("Iframe4Key", ["classExercise" => $classExercise]);
    }

    public function ActionWatchData() {
        $classID = $_GET['classID'];
        $array_lesson = Lesson::model()->findAll("classID = '$classID'");
        if (!empty($array_lesson)) {
            if (isset($_GET['lessonID']))
                Yii::app()->session['currentLesson'] = $_GET['lessonID'];
            else
                Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
        }
        $array_work = ClassLessonSuite::model()->findAll("classID = '$classID'and open = 1");
        $array_suite = Suite::model()->findAll();
        $array_examList = ClassExam::model()->findAll("classID='$classID' and open = 1");
        $array_exam = Exam::model()->findAll();
        $this->render('dataAnalysis', array(
            'array_lesson' => $array_lesson,
            'array_work' => $array_work,
            'array_suite' => $array_suite,
            'classID' => $classID,
            'array_examList' => $array_examList,
            'array_exam' => $array_exam,
        ));
    }
    //将这套试卷打的练习插入数据库
    public function actionSaveExamData() {
        $workID = $_POST['workID'];
        $examID = $_POST['examID'];
        $classID = $_POST['classID'];
        $studentLst = Student::model()->findAll("classID = '$classID'");
        foreach ($studentLst as $stu) {
          $studentID = $stu['userID'];
          $rankLst = RankAnswer::model()->findAll("workID = '$workID' and isExam = 1 and userID = '$studentID'");
        if($rankLst == NULL){
             $exam_recordLst = ExamRecord::model()->findAll("workID = '$workID' and studentID = '$studentID'");
            foreach ($exam_recordLst as $exam_record) {
                $recordID = $exam_record['recordID'];
                $answerLst = AnswerRecord::model()->getAnswerResult($recordID);
                foreach ($answerLst as $ans) {
                   $answerID = $ans['answerID'];
                   $exerciseID = $ans['exerciseID'];
                   $userID = $ans['createPerson'];
                   $userName = Student::model()->find("userID = '$userID'")['userName'];
                  
                   $answerData = AnswerData::model()->find("answerID = '$answerID'");
                   $type = $ans['type'];
                   if($type=="key"){
                       $missing_Number = 0;
                       $redundant_Number = 0;
                       $correct = $ans['ratio_correct'];
                       $n1 = strrpos($correct, "&");
                       $correct = substr($correct, $n1 + 1);
                       $correct = round($correct,1);
                   }  else {
                        if($answerData['missing_Number']==""){
                            $missing_Number=0;
                            
                        } else{
                            $missing_Number = $answerData['missing_Number'];
                        }
                        if($answerData['redundant_Number']==""){
                            $redundant_Number=0;}
                        else{
                           $redundant_Number = $answerData['redundant_Number'];}
                        if($answerData['correct_Answer']==""){
                           $correct=0;
                        }else{
                          $correct = $answerData['correct_Answer'];
                          $correct = round($correct,1);
                       }
                   }
                   $speed = $ans['ratio_speed'];
                   $n3 = strrpos($speed, "&");
                   $speed = substr($speed, $n3 + 1);
                   
                   $backDelete = $ans['ratio_backDelete'];
                   $n4 = strrpos($backDelete, "&");
                   $backDelete = substr($backDelete, $n4 + 1);
                   RankAnswer::model()->insertData($answerID, $exerciseID, $workID, $userID, $userName, $correct, $missing_Number, $redundant_Number, $speed, $type, $backDelete, 1);
                }
            }
        }
        else {
            foreach ($rankLst as $rank) {
                $ansID = $rank['answerID'];
                $answerData = AnswerData::model()->find("answerID = '$ansID'");
                if($answerData!=NULL){
                 if($rank['type']!="key"){
                   $rank['missing_Number'] = $answerData['missing_Number'];
                   $rank['redundant_Number'] = $answerData['redundant_Number'];
                   $correct = $answerData['correct_Answer'];
                   $correct = round($correct,1);
                   $rank['correct'] = $correct;
                 
                   $rank->update();
                }
             }
            }
        }
       }
    }
    //将这套作业打的练习插入数据库
    public function actionSaveSuiteData() {
        $workID = $_POST['workID'];
        $suiteID = $_POST['suiteID'];
        $classID = $_POST['classID'];
        $level = ClassLessonSuite::model()->find("workID = '$workID'")['level'];
        if($level==''){
         $studentLst = Student::model()->findAll("classID = '$classID'");
        }else{
         $studentLst = Student::model()->findAll("classID = '$classID' and level = '$level'");   
        }
        foreach ($studentLst as $stu) {
          $studentID = $stu['userID'];
          $rankLst = RankAnswer::model()->findAll("workID = '$workID' and isExam = 0 and userID = '$studentID'");
          if($rankLst == NULL){
             $suite_recordLst = SuiteRecord::model()->findAll("workID = '$workID' and studentID = '$studentID'");
            foreach ($suite_recordLst as $suite_record) {
                $recordID = $suite_record['recordID'];
                $answerLst = AnswerRecord::model()->getAnswerResult($recordID);
                foreach ($answerLst as $ans) {
                   $answerID = $ans['answerID'];
                   $exerciseID = $ans['exerciseID'];
                   $userID = $ans['createPerson'];
                   $userName = Student::model()->find("userID = '$userID'")['userName'];
                   
                   $answerData = AnswerData::model()->find("answerID = '$answerID'");
                   $type = $ans['type'];
                   if($type=="key"){
                       $missing_Number = 0;
                       $redundant_Number = 0;
                       $correct = $ans['ratio_correct'];
                       $n1 = strrpos($correct, "&");
                       $correct = substr($correct, $n1 + 1);
                       $correct = round($correct,1);
                   }  else {
                        if($answerData['missing_Number']==""){
                            $missing_Number=0;}
                        else{
                            $missing_Number = $answerData['missing_Number'];
                            }
                        if($answerData['redundant_Number']==""){
                            $redundant_Number=0;}
                       else{
                           $redundant_Number = $answerData['redundant_Number'];}
                       if($answerData['correct_Answer']==""){
                           $correct=0;
                       }else{
                       $correct = $answerData['correct_Answer'];
                       $correct = round($correct,1);
                       }
                   }
                   
                   $speed = $ans['ratio_speed'];
                   $n3 = strrpos($speed, "&");
                   $speed = substr($speed, $n3 + 1);
                   
                   $backDelete = $ans['ratio_backDelete'];
                   $n4 = strrpos($backDelete, "&");
                   $backDelete = substr($backDelete, $n4 + 1);
                   RankAnswer::model()->insertData($answerID, $exerciseID, $workID, $userID, $userName, $correct, $missing_Number, $redundant_Number, $speed, $type, $backDelete, 0);
                }
            }
        } 
        else {
            foreach ($rankLst as $rank) {
                $ansID = $rank['answerID'];
                $answerData = AnswerData::model()->find("answerID = '$ansID'");
                if($answerData!=NULL){
                if($rank['type']!="key"){
                 $rank['missing_Number'] = $answerData['missing_Number'];
                 $rank['redundant_Number'] = $answerData['redundant_Number'];
                 $correct = $answerData['correct_Answer'];
                 $correct = round($correct,1);
                 $rank['correct'] = $correct;
                 
                 $rank->update();
                }
               }  
            }
         }  
        }
        
    }
    //获得学生课堂练习情况并存入数据库
    public function actionSaveClassExerciseData(){
        $lessonID = $_POST['lessonID'];
        $classID = $_POST['classID'];
        $array_exercise = ClassExercise::model()->findAll("lessonID= '$lessonID'"); 
         $studentLst = Student::model()->findAll("classID = '$classID'");
        foreach ($studentLst as $student) {
        foreach ($array_exercise as $exercise) {           
            $exerciseID = $exercise['exerciseID'];            
                $studentID = $student['userID'];
                $rankLst = RankAnswer::model()->find("exerciseID = '$exerciseID' and isExam = 2 and userID = '$studentID' ");
                if($rankLst == NULL){
                   $exerciseRecord = ClassexerciseRecord::model()->getSingleRecord($studentID, $exerciseID);
                    if($exerciseRecord != NULL) {
//                        break;
//                    }  else {
                        $answerID = Tool::createID();
                        $userName = $userName = Student::model()->find("userID = '$studentID'")['userName'];
                        $correct = $exerciseRecord['ratio_correct'];
                        $n1 = strrpos($correct, "&");
                        $correct = substr($correct, $n1 + 1);
                        $correct = round($correct,1);
                        $speed = $exerciseRecord['ratio_speed'];
                        $n3 = strrpos($speed, "&");
                        $speed = substr($speed, $n3 + 1);
                        $type = $exercise['type'];
                        $backDelete = $exerciseRecord['ratio_backDelete'];
                        $n4 = strrpos($backDelete, "&");
                        $backDelete = substr($backDelete, $n4 + 1);
                        RankAnswer::model()->insertData($answerID, $exerciseID, $lessonID, $studentID, $userName, $correct, 0, 0, $speed, $type, $backDelete, 2); 
                    }
                }else {
                   $exerciseRecord = ClassexerciseRecord::model()->getSingleRecord($studentID, $exerciseID);
//                   $rankLst['answerID'] = $exerciseRecord['id'];
                   $correct = $exerciseRecord['ratio_correct'];
                   $n1 = strrpos($correct, "&");
                   $correct = substr($correct, $n1 + 1);
                   $correct = round($correct,1);
                   $rankLst['correct'] = $correct;  
                   $speed = $exerciseRecord['ratio_speed'];
                   $n3 = strrpos($speed, "&");
                   $speed = substr($speed, $n3 + 1);
                   $rankLst['speed'] = $speed;
                   $backDelete = $exerciseRecord['ratio_backDelete'];
                   $n4 = strrpos($backDelete, "&");
                   $backDelete = substr($backDelete, $n4 + 1);
                   $rankLst['backDelete'] = $backDelete;
                   $rankLst ->update();
                }
            }
        }
        
        
    }

    public function actionGetStudentRanking(){
        $workID = $_POST['workID'];
        $type = $_POST['type'];
        $exerciseID = $_POST['exerciseID'];
        $choice = $_POST['choice'];
        $isExam=$_POST['isExam'];
        if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
         
        if($isExam == 1){
            $rankAnswer = RankAnswer::model()->findAll ("workID = '$workID' and exerciseID ='$exerciseID' and isExam ='$isExam' and type = '$type'");
           if($rankAnswer == NULL){
               $this->renderJSON("1");
           }else {
            $rankLst = RankAnswer::model()->getRankResult($workID, $exerciseID, $type, $isExam, $choice);
            $this->renderJSON($rankLst);
            
        }        
    }else if($isExam == 0){
        $rankAnswer = RankAnswer::model()->findAll("workID = '$workID' and exerciseID ='$exerciseID' and isExam ='$isExam' and type = '$type'");
           if($rankAnswer == NULL){
               $this->renderJSON("1");
           }else {
            $rankLst = RankAnswer::model()->getRankResult($workID, $exerciseID, $type, $isExam, $choice);
            $this->renderJSON($rankLst);
            
        }        
    }else if($isExam == 2){
       $rankAnswer = RankAnswer::model()->findAll("exerciseID ='$exerciseID'");
           if($rankAnswer == NULL){
               $this->renderJSON("1");
           }else {
            
            $rankLst = RankAnswer::model()->getRankResult($workID, $exerciseID, $type, $isExam, $choice);
            $this->renderJSON($rankLst);           
        }         
    }
   }
    //判断是否弹出签到提示框
      public function actionStartSign() {
        $studentID = Yii::app()->session['userid_now'];
        $array_Sign_ID = [];
        $array_studentSign_ID = [];
        $classID = $_GET['classID'];
        $lessonID = $_GET['lessonID'];
        $TeacherSign = TeacherSign::model()->issign($classID, $lessonID);
        $studentsign = StudentSign::model()->ismark($studentID, $lessonID,$classID);       
        foreach ($TeacherSign as $key => $value) {
            $array_Sign_ID[$key]=$value['Sign_ID'];
        }
        foreach ($studentsign as $k => $v) {
           $array_studentSign_ID[$k]=$v['Sign_ID'];
        }
       $this->renderJSON(['TeacherSign_ID'=>$array_Sign_ID,'StudentSign_ID'=>$array_studentSign_ID,]);
    }
   public function actionSaveSign(){
        $studentID = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $lessonID = $_GET['lessonID'];
        $publishtime = date('y-m-d H:i:s',time());
        $sql1 = "select * FROM teacher_sign WHERE lessonID = '$lessonID' AND classID = $classID Order By Sign_Time Desc limit 0,1";
        $criteria   =   new CDbCriteria();
        $maxtimes  =   Yii::app()->db->createCommand($sql1)->queryAll();
        foreach ($maxtimes as $key){
        $times = $key['times'];
        $connection = Yii::app()->db;
        $sql = "UPDATE `student_sign` SET mark = '1' , time = '$publishtime' WHERE userID = '$studentID' and lessonID= '$lessonID' and classID = '$classID'and times ='$times'";
        $command = $connection->createCommand($sql);
        $command->execute();

        }   


   }
   public function actionIsReleaseSign(){
        $studentID = Yii::app()->session['userid_now'];
        $array_Sign_ID = [];
        $classID = $_GET['classID'];
        $lessonID = $_GET['lessonID'];
        $TeacherSign = TeacherSign::model()->hassign($classID, $lessonID);      
        foreach ($TeacherSign as $key => $value) {
            $array_Sign_ID[$key]=$value['Sign_ID'];
        }
       $this->renderJSON(['TeacherSign_ID'=>$array_Sign_ID,]);
    }
    public function actionRequestlogin(){
         Yii::app()->session['cfmLogin']=0;
         $login_model = new LoginForm;
          $userID = Yii::app()->session['userid_now'];
          $isl = Student::model()->find("userID = '$userID'");
          $isl = $isl['is_login'];
          $s = Yii::app()->session['islogin'];
          if($isl==$s){
          }else{
              $result="已在其他地方登陆";
              unset(Yii::app()->session['userid_now']);
              $tislogin = 1;
              $studentislogin = array();
              array_push($studentislogin, $tislogin);
              $this->renderJSON(['studentislogin'=>$studentislogin,]);
          }
        }
    
}
