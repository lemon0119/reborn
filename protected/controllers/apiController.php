<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class apiController extends Controller {

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

    public function actionGetLatestChat() {
        $classID = $_GET['classID'];
        $connection = Yii::app()->db;
        $sql = "SELECT * FROM chat_lesson_1 where classID = '$classID' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $all_chats = $dataReader->readAll();

        $this->renderJSON($all_chats);
    }

    public function actionPutChat() {
        $classID = $_GET['classID'];
        $identity = (String)Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        if($identity == "student")
        {       
            $student = Student::model()->find("userID='$userid'");             
            echo $student->forbidspeak;   
            if($student->forbidspeak == "1")
                return;
        }
        $username = (string) Yii::app()->request->getParam('username');
        $chat = (string) Yii::app()->request->getParam('chat');
        //改为使用服务器时间
        $publishtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $sql = "INSERT INTO chat_lesson_1 (username, chat, time, classID,identity,userid) values ($username, $chat, '$publishtime', '$classID','$identity','$userid')";
        $command = $connection->createCommand($sql);
        $command->execute();    
    }
    
    public function actionUpdateVirClass(){
        $classID = $_GET['classID'];
        $number = $_GET['number'];
        $backtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $sql = "UPDATE lesson SET backTime='$backtime' WHERE classID='$classID' AND number ='$number'";
        $command = $connection->createCommand($sql);
        echo $command->execute();
    }
    public function actionUpdateStuOnLine(){
        $classID = $_GET['classID'];
        $backtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $userID=(string) Yii::app()->request->getParam('userid');
        $sql = "UPDATE student SET backTime='$backtime' WHERE userID='$userID'";
        $command = $connection->createCommand($sql);
        echo $command->execute();
    }

    public function actionGetLatestBulletin() {
        $classID = $_GET['classID'];
        $connection = Yii::app()->db;
        $sql = "SELECT * FROM bulletin_lesson_1 where classID = '$classID' ORDER BY id DESC LIMIT 1";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $bulletin = $dataReader->readAll();

        $this->renderJSON($bulletin);
    }
    public function actionGetBackTime(){
        $classID = $_GET['classID'];
        $connection = Yii::app()->db;
        $sql = "SELECT backTime FROM tb_class where classID = '$classID'";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $time[0]['backTime']=strtotime($time[0]['backTime']);
        $this->renderJSON($time);
    }
    public function actionGetStuOnLine(){
        $classID = $_GET['classID'];
        $connection = Yii::app()->db;
        $userID=array(Yii::app()->session['userid_now']);
        $sql = "SELECT userName,backTime FROM student WHERE classID ='$classID'";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $n=0;$b=0;
        $onLineStudent = array();
        foreach ($time as $t) {
            if(time()-strtotime($time[$b++]['backTime']) < 10){
                array_push($onLineStudent, $t['userName']);
                $n++;
            }
        }
        $sqlstudent = Student::model()->findAll("classID = '$classID'");
        $student = array();
        foreach ($sqlstudent as $v){
            $flag = 0;
            foreach ($onLineStudent as $vo){
                if($v['userName']==$vo){
                    $flag = 1;
                }
            }
            if($flag==0){
                array_push($student, $v['userName']);
            }
        }
        $this->renderJSON(array($onLineStudent,$student,$n));
    }
    
    public function actionGetClassState(){
        $classID = $_GET['classID'];
        $number = $_GET['number'];
        $connection = Yii::app()->db;
        $sql = "SELECT backTime FROM lesson where classID = '$classID' AND number = '$number'";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $state = time()-strtotime($time[0]['backTime']) > 10 ? false : true;
        $this->renderJSON($state);
    }
    public function actionPutBulletin() {
        $bulletin = (string) Yii::app()->request->getParam('bulletin');
        //$publishtime = (string) Yii::app()->request->getParam('time');
        //改为使用服务器时间
        $publishtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $classID = $_GET['classID'];
        $sql = "INSERT INTO bulletin_lesson_1 (content, time, classID) values ($bulletin, '$publishtime','$classID')";
        $command = $connection->createCommand($sql);
        $command->execute();
    }
    public function actionPutNotice() {
        $title = (string) Yii::app()->request->getParam('title');
        $content = (string) Yii::app()->request->getParam('content');
        $new_content = str_replace("\n", "<br/>", $content);
        //改为使用服务器时间
        $publishtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $sql = "INSERT INTO notice (noticetime,noticetitle,content) values ( '$publishtime','$title','$new_content')";
        $command = $connection->createCommand($sql);
        $command->execute();
        
        $connection = Yii::app()->db;
        $sql = "UPDATE student SET noticestate='1'";
        $command = $connection->createCommand($sql);
        $command->execute();
        
        $connection = Yii::app()->db;
        $sql = "UPDATE teacher SET noticestate='1'";
        $command = $connection->createCommand($sql);
        $command->execute();

    }
    public function actionPutNotice2() {
        $class=$_GET['class'];
        $title = (string) Yii::app()->request->getParam('title');
        $content = (string) Yii::app()->request->getParam('content');
        $new_content = str_replace("\n", "<br/>", $content);
        //改为使用服务器时间
        $publishtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $sql = "INSERT INTO notice (noticetime,noticetitle,content) values ( '$publishtime','$title','$new_content')";
        $command = $connection->createCommand($sql);
        $command->execute();
        
        $connection = Yii::app()->db;
        $sql = "UPDATE student SET noticestate='1' WHERE classID='$class'";
        $command = $connection->createCommand($sql);
        $command->execute();

    }
    
    public function actionChangeNotice(){
        $id=$_GET['id'];
        $content = (string) Yii::app()->request->getParam('content');
        $connection = Yii::app()->db;
        $sql = "UPDATE notice SET content='$content' WHERE id='$id'";
        $command = $connection->createCommand($sql);
        $command->execute();
        
        $connection = Yii::app()->db;
        $sql = "UPDATE student SET noticestate='1'";
        $command = $connection->createCommand($sql);
        $command->execute();
        
        $connection = Yii::app()->db;
        $sql = "UPDATE teacher SET noticestate='1'";
        $command = $connection->createCommand($sql);
        $command->execute();
    }

    public function actionGetTime(){
        echo time();
    }

    /**
     * @author Wang fei <1018484601@qq.com>
     * @purpose 返回一个不包含子文件夹的文件家中的文件数目
     * @return  返回文件数目，不存在文件夹时亦返回0
     */
    public function actionGetDirFileNums() {
        $dir = $_GET['dirName'];
        if(is_dir(iconv("UTF-8","gb2312",$dir)))
        {
            $num = sizeof(scandir(iconv("UTF-8","gb2312",$dir))); 
            $num = ($num>2)?($num-2):0; 
            echo $num;
        }else {
            echo 0;
        }
    }
    
    public function actionGetAverageSpeed(){
        $time = $_POST['startTime'];
        $content = $_POST['content'];
        $data = AnalysisTool::getAverageSpeed($time, $content);
        $this->renderJSON($data);
    }
    
    public function actionGetMomentSpeed(){
        $setTime = $_POST['setTime'];
        $contentlength = $_POST['contentlength'];
        $data = AnalysisTool::getMomentSpeed($setTime, $contentlength);
        $this->renderJSON($data);
    }
    
    public function actionGetBackDelete(){
        $doneCount = $_POST['doneCount'];
        $keyType = $_POST['keyType'];
        $donecount = AnalysisTool::getBackDelete($doneCount, $keyType);
        $this->renderJSON($donecount);
    }
    
    public function actionGetRight_Wrong_AccuracyRate(){
        $originalContent = $_POST['originalContent'];
        $currentContent = $_POST['currentContent'];
        $data = AnalysisTool::getRight_Wrong_AccuracyRate($originalContent, $currentContent);
        $this->renderJSON($data);
    }
    
    public function actionAnswerDataSave(){
        $error_Number=$_POST['error_Number'];
        $missing_Number=$_POST['missing_Number'];
        $redundant_Number=$_POST['redundant_Number'];
        $answerID=$_POST['answerID'];
        $standard_lgnore_symbol=$_POST['standard_lgnore_symbol'];
        $answer_lgnore_symbol=$_POST['answer_lgnore_symbol'];
        $correct_Answer=$_POST['correct_Answer'];
        AnswerData::model()->updataAnswerData1($answerID, $error_Number, $missing_Number, $redundant_Number,$standard_lgnore_symbol,$answer_lgnore_symbol,$correct_Answer);
    }
    
    public function ActionChangeSuiteType() {
        $thisLessonId = $_POST['thisLessonId'];
        $thisClassId = $_POST['thisClassId'];
        $thisSuiteId = $_POST['thisSuiteId'];
            $result = ClassLessonSuite::model()->findAll("classID=? and lessonID=? and suiteID=?", array($thisClassId, $thisLessonId ,$thisSuiteId));
            if(count($result)>0){
                foreach ($result as $lessonSuite) {
                    $lessonSuite->open = 0;
                    $lessonSuite->update();
                }
            }
    }

    public function actionAnalysisSaveToDatabase(){
        $exerciseType = $_POST['exerciseType'];
        $exerciseData = $_POST['exerciseData'];
        $ratio_averageKeyType = $_POST['averageKeyType'];
        $ratio_maxKeyType = $_POST['highstCountKey'];
        $ratio_maxSpeed = $_POST['highstSpeed'];
        $ratio_speed = $_POST['averageSpeed'];
        $ratio_backDelete = $_POST['CountBackDelete'];
        $ratio_internalTime = $_POST['IntervalTime'];
        $ratio_maxInternalTime = $_POST['highIntervarlTime'];
        $ratio_correct = $_POST['RightRadio'];
        $ratio_countAllKey = $_POST['CountAllKey'];
        $squence = $_POST['squence'];
        $answer = $_POST['answer'];
        $rightCount=$_POST['rightCount'];
        $originalCount=$_POST['originalCount'];
        $currentCount=$_POST['currentCount'];
        if(isset($_POST['errorNumber'])){
            $errorNumber=$_POST['errorNumber'];
        }else{
            $errorNumber=0;
        }
        if(isset($_POST['missingNumber'])){
            $missingNumber=$_POST['missingNumber'];
        }else{
            $missingNumber=0;
        }
        if(isset($_POST['redundantNumber'])){
            $redundantNumber=$_POST['redundantNumber'];
        }else{
            $redundantNumber=0;
        }
        if(isset($_POST['correctRate'])){
            $correctRate=$_POST['correctRate'];
        }else{
            $correctRate=0;
        }
        if($exerciseType === "classExercise"){
            $classExerciseID = $exerciseData[0];
            $studentID = $exerciseData[1];
            $sqlClassExerciseRecord = ClassexerciseRecord::model()->find("classExerciseID = '$classExerciseID' and squence = '$squence' and studentID = '$studentID'");
            if(!isset($sqlClassExerciseRecord)){
                 ClassexerciseRecord::model()->insertClassexerciseRecord($classExerciseID, $studentID, $squence,$answer,$ratio_speed, $ratio_correct, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey);
            }else{
                 ClassexerciseRecord::model()->updateClassexerciseRecord($classExerciseID, $studentID, $squence,$answer,$ratio_speed, $ratio_correct, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey);
            }
        }       
        if($exerciseType === "answerRecord"){
            $createPerson = Yii::app()->session['userid_now'];
            $recordID = $exerciseData[2];
            $exerciseID = $exerciseData[0];
            $type = $exerciseData[1];
            $category = $exerciseData[3];
           if(Yii::app()->session['isExam']==1){
                 AnswerRecord::model()->saveAnswer($recordID, $exerciseID, $type, $category, $ratio_correct, $answer, $createPerson, $ratio_speed, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey, $squence, 1, $ratio_internalTime);                  
            }else{
                 AnswerRecord::model()->saveAnswer($recordID, $exerciseID, $type, $category, $ratio_correct, $answer, $createPerson, $ratio_speed, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey, $squence, 0, $ratio_internalTime); 
            }
            $answer_record =  AnswerRecord::model()->findAll("recordID = ? AND exerciseID = ? AND type = ?",array($recordID, $exerciseID, $type));
            foreach ($answer_record as $record){
                     $answer_id=$record['answerID']; 
                     if((isset($_POST['errorNumber']) || isset($_POST['redundantNumber']) || isset($_POST['missingNumber']) || isset($_POST['correctRate'])) && $record!="key"){
                        
                         AnswerData::model()->updataAnswerData1($answer_id,$errorNumber,$missingNumber,$redundantNumber,$originalCount,0,0,$correctRate);
                     }else{
                        AnswerData::model()->saveAnswerData($answer_id,$rightCount,$errorNumber,$missingNumber,$redundantNumber,$originalCount,$currentCount,0,0);
                     }
                 }
        }    
        $this->renderJSON("");
    }

    
    
    
  
    public function actionGetTxtValue(){
        $file = $_POST['url'];
        $content = file_get_contents($file); //读取文件中的内容
        $data = mb_convert_encoding($content, 'utf-8', 'gbk');
        $this->renderJSON($data);
    }
    
    
         public function ActiongetExercise(){
            if(isset($_POST['suiteID'])){
                $suiteID = $_POST['suiteID'];
                $array_exercise = SuiteExercise::model()->findAll("suiteID='$suiteID'");
                $array_result = array();
                foreach ($array_exercise as $exercise)
                {
                    if($exercise['type'] == 'key')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = KeyType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];
                        //用数字代替类型，后面js好弄
                        $result['type'] = 1;
                        array_push($array_result, $result);
                    }else 
                    if($exercise['type'] == 'listen')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = ListenType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];
                        $result['type'] = 2;
                        array_push($array_result, $result);
                    }else
                    if($exercise['type'] == 'look')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = LookType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];                       
                        $result['type'] = 3;
                        array_push($array_result,$result);
                    }
                }
            }            
            $this->renderJSON($array_result);          
     }   
     public function ActiongetExamExercise(){
            if(isset($_POST['examID'])){
                $examID = $_POST['examID'];
                $array_exercise = ExamExercise::model()->findAll("examID='$examID'");
                $array_result = array();
                foreach ($array_exercise as $exercise)
                {
                    if($exercise['type'] == 'key')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = KeyType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];
                        //用数字代替类型，后面js好弄
                        $result['type'] = 1;
                        array_push($array_result, $result);
                    }else 
                    if($exercise['type'] == 'listen')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = ListenType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];
                        $result['type'] = 2;
                        array_push($array_result, $result);
                    }else
                    if($exercise['type'] == 'look')
                    {
                        $exerciseID = $exercise['exerciseID'];
                        $result = LookType::model()->findAll("exerciseID = '$exerciseID'");
                        $result['workID'] = $_POST['workID'];                       
                        $result['type'] = 3;
                        array_push($array_result,$result);
                    }
                }
            }            
            $this->renderJSON($array_result);          
     }  
     public function ActiongetClassExer(){
            if(isset($_POST['lessonID'])){
                $lessonID = $_POST['lessonID'];
                $array_exercise = ClassExercise::model()->findAll("lessonID='$lessonID'");
                $array_result = array();
                foreach ($array_exercise as $exercise)
                {
                    if($exercise['type'] == 'speed')
                    {
                        $result['exerciseID'] = $exercise['exerciseID'];
                        $result['title'] = $exercise['title'];
                        $result['type'] = 1;
                        array_push($array_result, $result);
                    }else 
                    if($exercise['type'] == 'listen')
                    {
                        $result['exerciseID']= $exercise['exerciseID'];
                        $result['title'] = $exercise['title'];
                        $result['type'] = 2;
                        array_push($array_result, $result);
                    }else
                    if($exercise['type'] == 'look')
                    {
                        $result['exerciseID']= $exercise['exerciseID'];
                        $result['title'] = $exercise['title'];                       
                        $result['type'] = 3;
                        array_push($array_result,$result);
                    }else
                    if($exercise['type'] == 'correct')
                    {
                        $result['exerciseID']= $exercise['exerciseID'];
                        $result['title'] = $exercise['title'];                       
                        $result['type'] = 4;
                        array_push($array_result,$result);
                    }else
                    if($exercise['type'] == 'free')
                    {
                        $result['exerciseID']= $exercise['exerciseID'];
                        $result['title'] = $exercise['title'];                       
                        $result['type'] = 5;
                        array_push($array_result,$result);
                    }
                }
            }            
            $this->renderJSON($array_result);          
     }
     public function ActiongetStudentRanking(){
         $workID = $_POST['workID'];
         $exerciseID = $_POST['exerciseID'];
         $type=$_POST['type'];
         $isExam=$_POST['isExam'];
         $choice=$_POST['choice'];
         $all=Array();
         $all2=array();
         if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
         $recordIDs=Array();
         if($isExam==1){
             $recordIDs=  ExamRecord::model()->findAll('workID=?',array($workID));
         }else{
             $recordIDs= SuiteRecord::model()->findAll('workID=?',array($workID));
         }
         
         $all=Array();
         foreach ($recordIDs as $ids) {
             $result=  AnswerRecord::model()->find('type=? and exerciseID=? and isExam=? and recordID=?',array($type,$exerciseID,$isExam,$ids['recordID']));
             if($result){
                array_push($all, $result);  
             }
         }
         //$all=  AnswerRecord::model()->findAll('type=? and exerciseID=? and isExam=?',array($type,$exerciseID,$isExam));
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         $data = Array();
         $data2 = Array();
         $data3 = Array();
         $allData=Array();
         if($all){
            foreach ($all as $a) {
                //correct
                 $correct=$a['ratio_correct'];
                 $correct2=$a['ratio_correct'];
                 if(strpos($correct,"&") === false){     
                      $correct=$correct."&".$correct;
                 }
                 $n=  strrpos($correct, "&");
                 $correct= substr($correct, $n+1);
                 //speed
                 $speed=$a['ratio_speed'];
                 $speed2=$a['ratio_speed'];
                 if(strpos($speed,"&") === false){     
                      $speed=$speed."&".$speed;
                 }
                 $n=  strrpos($speed, "&");
                 $speed= substr($speed, $n+1);
                 //maxSpeed
                 $maxSpeed=$a['ratio_maxSpeed'];
                 $maxSpeed2=$a['ratio_maxSpeed'];
                 if(strpos($maxSpeed,"&") === false){     
                      $maxSpeed=$maxSpeed."&".$maxSpeed;
                 }
                 $n=  strrpos($maxSpeed, "&");
                 $maxSpeed= substr($maxSpeed, $n+1);
                 //backDelete
                 $backDelete=$a['ratio_backDelete'];
                 $backDelete2=$a['ratio_backDelete'];
                 if(strpos($backDelete,"&") === false){     
                      $backDelete=$backDelete."&".$backDelete;
                 }
                 $n=  strrpos($backDelete, "&");
                 $backDelete= substr($backDelete, $n+1);
                 //maxInternalTime
                 $maxInternalTime=$a['ratio_maxInternalTime'];
                 $maxInternalTime2=$a['ratio_maxInternalTime'];
                 if(strpos($maxInternalTime,"&") === false){     
                      $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
                 }
                 $n=  strrpos($maxInternalTime, "&");
                 $maxInternalTime= substr($maxInternalTime, $n+1);
                 //time
                 $time = count($speed)*2-2;
                 $time2 = count($speed)*2-2;
                 if($isExam==1){
                     $student=  ExamRecord::model()->find('recordID=?',array($a['recordID']))['studentID'];
                 }else{
                    $student=SuiteRecord::model()->find('recordID=?',array($a['recordID']))['studentID'];
                 }
                 $studentName=Student::model()->find('userID=?',array($student))['userName'];
                 $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime,
                 ];
                 $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2,
                 ];

                 array_push($data, $arrayData);
                 array_push($data2, $arrayData2);
            }
            $data = Tool::quickSort($data,$choice);
            $allCorrect=0;$allSpeed=0;$allMaxSpeed=0;$allDelete=0;$allMaxInternalTime=0;$correctNumber=0;
            $corrects=Array();
            $speeds=Array();
            $maxSpeeds=Array();
            $deletes=Array();
            $correctNumber=array();
            $maxCorrectNum=0;
            $maxSpeedNum=0;
            $maxMaxSpeedNum=0;
            $maxDeleteNum=0;
            $maxInternalTimeNum=0;
            $maxcorrectNumber=0;
            foreach ($data2 as $da) {
                $correct=$da['correct'];     
                $corrects=explode("&", $correct);
                $maxCorrectNum=  (count($corrects)>$maxCorrectNum)?count($corrects):$maxCorrectNum;
              
                $speed=$da['speed'];
                $speeds=explode("&", $speed);
                $maxSpeedNum=  (count($speeds)>$maxSpeedNum)?count($speeds):$maxSpeedNum;
                
                $maxSpeed=$da['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                $maxMaxSpeedNum=  (count($maxSpeeds)>$maxMaxSpeedNum)?count($maxSpeeds):$maxMaxSpeedNum;
                
                $delete=$da['backDelete'];
                $deletes=explode("&", $delete);
                $maxDeleteNum=  (count($speeds)>$maxDeleteNum)?count($deletes):$maxDeleteNum;
                
                $maxInternalTime=$da['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                $maxInternalTimeNum=  (count($speeds)>$maxInternalTimeNum)?count($maxInternalTimes):$maxInternalTimeNum;
            }
            $allCorrect=Array();
            $allSpeed=Array();
            $allMaxSpeed=Array();
            $allDelete=Array();
            $allMaxInternalTime=Array();
            $allcorrectNumber=array();
            
            $num1=Array();
            $num2=Array();
            $num3=Array();
            $num4=Array();
            $num5=Array();
            $num6=array();
            foreach ($data2 as $d) {
                $correct=$d['correct'];     
                $corrects=explode("&", $correct);
                
                $speed=$d['speed'];
                $speeds=explode("&", $speed);
                
                $maxSpeed=$d['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                
                $delete=$d['backDelete'];
                $deletes=explode("&", $delete);
                
                $maxInternalTime=$d['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                
                //正确字数
                if($choice=='speed'){
                    foreach ($speeds as $key => $value) {
                        if(isset($allSpeed[$key])){
                            $allSpeed[$key]+=$value;
                            $num1[$key]++;
                        }else{
                            $allSpeed[]+=$value;
                            $num1[]+=1;
                        }
                    }
                }else if($choice=='correct'){
                    foreach ($corrects as $key => $value) {
                        if(isset($allCorrect[$key])){
                            $allCorrect[$key]+=$value;
                            $num2[$key]++;
                        }else{
                            $allCorrect[]+=$value;
                            $num2[]+=1;
                        }
                    }
                }else if($choice=='maxSpeed'){
                    foreach ($maxSpeeds as $key => $value) {
                        if(isset($allMaxSpeed[$key])){
                            $allMaxSpeed[$key]+=$value;
                            $num3[$key]++;
                        }else{
                            $allMaxSpeed[]+=$value;
                            $num3[]+=1;
                        }
                    }
                }else if($choice=='backDelete'){
                    foreach ($deletes as $key => $value) {
                        if(isset($allDelete[$key])){
                            $allDelete[$key]+=$value;
                            $num4[$key]++;
                        }else{
                            $allDelete[]+=$value;
                            $num4[]+=1;
                        }
                    }
                }else if($choice=='maxInternalTime'){
                    foreach ($maxInternalTimes as $key => $value) {
                        if(isset($allMaxInternalTime[$key])){
                            $allMaxInternalTime[$key]+=$value;
                            $num5[$key]++;
                        }else{
                            $allMaxInternalTime[]+=$value;
                            $num5[]+=1;
                        }
                    }
                }
            }
            //求平均值，返回
            if($choice=='correct'){
                foreach ($allCorrect as $key => $value) {
                    
                    $allCorrect[$key]=$allCorrect[$key]/$num2[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"correct"=>$allCorrect[$key]];
                }
                
            }else if($choice=='speed'){
                foreach ($allSpeed as $key => $value) {
                    $allSpeed[$key]=$allSpeed[$key]/$num1[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"speed"=>$allSpeed[$key]];
                }
            }else if($choice=='maxSpeed'){
                foreach ($allMaxSpeed as $key => $value) {
                    $n=$key*2;
                    $allMaxSpeed[$key]=$allMaxSpeed[$key]/$num3[$key];
                    $arrayData4[] = ["duration"=>$n,"maxSpeed"=>$allMaxSpeed[$key]];
                }
            }else if($choice=='backDelete'){
                foreach ($allDelete as $key => $value) {
                    $allDelete[$key]=$allDelete[$key]/$num4[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"backDelete"=>$allDelete[$key]];
                }
            }else if($choice=='maxInternalTime'){
                foreach ($allMaxInternalTime as $key => $value) {
                    $allMaxInternalTime[$key]=$allMaxInternalTime[$key]/$num4[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"maxInternalTime"=>$allMaxInternalTime[$key]];
                }
            }
            array_push($data3, $arrayData4);
         }
         array_push($allData, $data);
         array_push($allData, $data3);
         $this->renderJSON($allData);
     }
     public function ActiongetStudentRankingAll(){
         $workID = $_POST['workID'];
         $exerciseID = $_POST['exerciseID'];
         $type=$_POST['type'];
         $isExam=$_POST['isExam'];
         $choice=$_POST['choice'];
         $name=$_POST['name'];
         $all=Array();
         if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
         $recordIDs=Array();
         if($isExam==1){
             $recordIDs=  ExamRecord::model()->findAll('workID=?',array($workID));
         }else{
             $recordIDs= SuiteRecord::model()->findAll('workID=?',array($workID));
         }
         $all=Array();
         foreach ($recordIDs as $ids) {
             $result=  AnswerRecord::model()->find('type=? and exerciseID=? and isExam=? and recordID=?',array($type,$exerciseID,$isExam,$ids['recordID']));
             if($result){
                array_push($all, $result);
             }
         }
         
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         $data = Array();
         $data2 = Array();
         $data3 = Array();
         $allData=Array();
         $myData=Array();
         $myDetail=Array();
         $averageData=Array();
         $maxData=Array();
         $n1=0;$n2=0;$n3=0;$n4=0;$n5=0;$n6=0;$n7=0;$n8=0;
         $f1=0;$f2=0;$f3=0;$f4=0;$f5=0;$f6=0;$f7=0;$f8=0;$f9=0;
         if($all){
            foreach ($all as $a) {
                //correct
                 $correct=$a['ratio_correct'];
                 $correct2=$a['ratio_correct'];
                 if(strpos($correct,"&") === false){     
                      $correct=$correct."&".$correct;
                 }
                 $n=  strrpos($correct, "&");
                 $correct= substr($correct, $n+1);
                 $n1+=$correct;
                 if($f1<$correct){
                     $f1=$correct;
                 }
                 
                 //speed
                 $speed=$a['ratio_speed'];
                 $speed2=$a['ratio_speed'];
                 if(strpos($speed,"&") === false){     
                      $speed=$speed."&".$speed;
                 }
                 $n=  strrpos($speed, "&");
                 $speed= substr($speed, $n+1);
                 $n2+=$speed;
                 if($f2<$speed){
                     
                     $f2=$speed;
                 }
                 
                 //maxSpeed
                 $maxSpeed=$a['ratio_maxSpeed'];
                 $maxSpeed2=$a['ratio_maxSpeed'];
                 if(strpos($maxSpeed,"&") === false){     
                      $maxSpeed=$maxSpeed."&".$maxSpeed;
                 }
                 $n=  strrpos($maxSpeed, "&");
                 $maxSpeed= substr($maxSpeed, $n+1);
                 $n3+=$maxSpeed;
                 if($f3<$maxSpeed){
                     
                     $f3=$maxSpeed;
                 }
                 
                 //backDelete
                 $backDelete=$a['ratio_backDelete'];
                 $backDelete2=$a['ratio_backDelete'];
                 if(strpos($backDelete,"&") === false){     
                      $backDelete=$backDelete."&".$backDelete;
                 }
                 $n=  strrpos($backDelete, "&");
                 $backDelete= substr($backDelete, $n+1);
                 $n4+=$backDelete;
                 if($f4<$backDelete){
                     
                     $f4=$backDelete;
                 }
                 
                 //maxInternalTime
                 $maxInternalTime=$a['ratio_maxInternalTime'];
                 $maxInternalTime2=$a['ratio_maxInternalTime'];
                 if(strpos($maxInternalTime,"&") === false){     
                      $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
                 }
                 $n=  strrpos($maxInternalTime, "&");
                 $maxInternalTime= substr($maxInternalTime, $n+1);
                 $n5+=$maxInternalTime;
                 if($f5<$maxInternalTime){
                     
                     $f5=$maxInternalTime;
                 }
                 
                 //time
                 $time = count($speed)*2-2;
                 $time2 = count($speed)*2-2;
                 //averageKeytype
                 $averageKeyType=$a['ratio_averageKeyType'];   
                 if(strpos($averageKeyType,"&") === false){     
                      $averageKeyType=$averageKeyType."&".$averageKeyType;
                 }
                 $n=  strrpos($averageKeyType, "&");
                 $averageKeyType= substr($averageKeyType, $n+1);
                 $n6+=$averageKeyType;
                 if($f6<$averageKeyType){
                    
                     $f6=$averageKeyType;
                 }
                 
                 //maxKeyType
                 $maxKeyType=$a['ratio_maxKeyType'];     
                 if(strpos($maxKeyType,"&") === false){     
                      $maxKeyType=$maxKeyType."&".$maxKeyType;
                 }
                 $n=  strrpos($maxKeyType, "&");
                 $maxKeyType= substr($maxKeyType, $n+1);
                 $n7+=$maxKeyType;
                 if($f7<$maxKeyType){
                    
                     $f7=$maxKeyType;
                 }
                 
                 //countAllKey
                 $countAllKey=$a['ratio_countAllKey'];     
                 if(strpos($countAllKey,"&") === false){     
                      $countAllKey=$countAllKey."&".$countAllKey;
                 }
                 $n=  strrpos($countAllKey, "&");
                 $countAllKey= substr($countAllKey, $n+1);
                 $n8+=$countAllKey;
                 if($f8<$countAllKey){
                     
                     $f8=$countAllKey;
                 }
                 
                 //createTime
                 $createTime=$a['createTime'];
                 if($f9<$createTime){
                     
                     $f9=$createTime;
                 }
                 
                 if($isExam==1){
                     $student=  ExamRecord::model()->find('recordID=?',array($a['recordID']))['studentID'];
                 }else{
                    $student=SuiteRecord::model()->find('recordID=?',array($a['recordID']))['studentID'];
                 }
                 $studentName=Student::model()->find('userID=?',array($student))['userName'];
                 
                 if($student==$name){
                     //通过名字获取相应记录
                    $myData = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
                    $myDetail=["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$time,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime,
                     'averageKeyType'=>$averageKeyType,"maxKeyType"=>$maxKeyType,"countAllKey"=>$countAllKey,"createTime"=>$createTime];
                 }
                 $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime,
                     'averageKeyType'=>$averageKeyType,"maxKeyType"=>$maxKeyType,"countAllKey"=>$countAllKey];
                 $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
                 $maxData=["correct"=>$f1,"speed"=>$f2,"maxSpeed"=>$f3,"backDelete"=>$f4,'maxInternalTime'=>$f5,
                     'averageKeyType'=>$f6,"maxKeyType"=>$f7,"countAllKey"=>$f8,"createTime"=>$f9];
                 //$averageData=["correct"=>$n1/count($all),"speed"=>$n2/count($all),"maxSpeed"=>$n3/count($all),"backDelete"=>$n4/count($all),'maxInternalTime'=>$n5/count($all),
                 //    'averageKeyType'=>$n6/count($all),"maxKeyType"=>$n7/count($all),"countAllKey"=>$n8/count($all)];
                 array_push($data, $arrayData);
                 array_push($data2, $arrayData2);
            }
          
            $data = Tool::quickSort($data,$choice);
            //解析myData
            $myCorrect=Array();
            $myDataReturn=Array();
            $mySpeed=  explode("&",$myData['speed']);
            $myCorrect=  explode("&",$myData['correct']);
            $myMaxSpeed=  explode("&",$myData['maxSpeed']);
            $myBackDelete=  explode("&",$myData['backDelete']);
            $myInternalTime=  explode("&",$myData['maxInternalTime']);
            $myCorrectNum=  count($myCorrect);
            if($choice=='correct'){
                foreach ($myCorrect as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"correct"=>$myCorrect[$key]];
                }
            }else if($choice=='speed'){
                foreach ($mySpeed as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"speed"=>$mySpeed[$key]];
                }
            }else if($choice=='maxSpeed'){
                foreach ($myMaxSpeed as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"maxSpeed"=>$myMaxSpeed[$key]];
                }
            }else if($choice=='backDelete'){
                foreach ($myBackDelete as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"backDelete"=>$myBackDelete[$key]];
                }
            }else if($choice=='maxInternalTime'){
                foreach ($myInternalTime as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"maxInternalTime"=>$myInternalTime[$key]];
                }
            }
            $allCorrect=0;$allSpeed=0;$allMaxSpeed=0;$allDelete=0;$allMaxInternalTime=0;
            $corrects=Array();
            $speeds=Array();
            $maxSpeeds=Array();
            $deletes=Array();
            $maxCorrectNum=0;
            $maxSpeedNum=0;
            $maxMaxSpeedNum=0;
            $maxDeleteNum=0;
            $maxInternalTimeNum=0;
            foreach ($data2 as $da) {
                $correct=$da['correct'];     
                $corrects=explode("&", $correct);
                $maxCorrectNum=  (count($corrects)>$maxCorrectNum)?count($corrects):$maxCorrectNum;
                
                $speed=$da['speed'];
                $speeds=explode("&", $speed);
                $maxSpeedNum=  (count($speeds)>$maxSpeedNum)?count($speeds):$maxSpeedNum;
                
                $maxSpeed=$da['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                $maxMaxSpeedNum=  (count($maxSpeeds)>$maxMaxSpeedNum)?count($maxSpeeds):$maxMaxSpeedNum;
                
                $delete=$da['backDelete'];
                $deletes=explode("&", $delete);
                $maxDeleteNum=  (count($speeds)>$maxDeleteNum)?count($deletes):$maxDeleteNum;
                
                $maxInternalTime=$da['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                $maxInternalTimeNum=  (count($speeds)>$maxInternalTimeNum)?count($maxInternalTimes):$maxInternalTimeNum;
            }
            $allCorrect=Array();
            $allSpeed=Array();
            $allMaxSpeed=Array();
            $allDelete=Array();
            $allMaxInternalTime=Array();
            $num1=Array();
            $num2=Array();
            $num3=Array();
            $num4=Array();
            $num5=Array();
            foreach ($data2 as $d) {
                $correct=$d['correct'];     
                $corrects=explode("&", $correct);
                $speed=$d['speed'];
                $speeds=explode("&", $speed);
                $maxSpeed=$d['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                $delete=$d['backDelete'];
                $deletes=explode("&", $delete);
                $maxInternalTime=$d['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                if($choice=='speed'){
                    foreach ($speeds as $key => $value) {
                        if(isset($allSpeed[$key])){
                            $allSpeed[$key]+=$value;
                            $num1[$key]++;
                        }else{
                            $allSpeed[]+=$value;
                            $num1[]+=1;
                        }
                    }
                }else if($choice=='correct'){
                    foreach ($corrects as $key => $value) {
                        if(isset($allCorrect[$key])){
                            $allCorrect[$key]+=$value;
                            $num2[$key]++;
                        }else{
                            $allCorrect[]+=$value;
                            $num2[]+=1;
                        }
                    }
                }else if($choice=='maxSpeed'){
                    foreach ($maxSpeeds as $key => $value) {
                        if(isset($allMaxSpeed[$key])){
                            $allMaxSpeed[$key]+=$value;
                            $num3[$key]++;
                        }else{
                            $allMaxSpeed[]+=$value;
                            $num3[]+=1;
                        }
                    }
                }else if($choice=='backDelete'){
                    foreach ($deletes as $key => $value) {
                        if(isset($allDelete[$key])){
                            $allDelete[$key]+=$value;
                            $num4[$key]++;
                        }else{
                            $allDelete[]+=$value;
                            $num4[]+=1;
                        }
                    }
                }else if($choice=='maxInternalTime'){
                    foreach ($maxInternalTimes as $key => $value) {
                        if(isset($allMaxInternalTime[$key])){
                            $allMaxInternalTime[$key]+=$value;
                            $num5[$key]++;
                        }else{
                            $allMaxInternalTime[]+=$value;
                            $num5[]+=1;
                        }
                    }
                }
            }
            //求平均值，返回
            if($choice=='correct'){
                foreach ($allCorrect as $key => $value) {
                    
                    $allCorrect[$key]=$allCorrect[$key]/$num2[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"correct"=>$allCorrect[$key]];
                }
                
            }else if($choice=='speed'){
                foreach ($allSpeed as $key => $value) {
                    $allSpeed[$key]=$allSpeed[$key]/$num1[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"speed"=>$allSpeed[$key]];
                }
            }else if($choice=='maxSpeed'){
                foreach ($allMaxSpeed as $key => $value) {
                    $allMaxSpeed[$key]=$allMaxSpeed[$key]/$num3[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"maxSpeed"=>$allMaxSpeed[$key]];
                }
            }else if($choice=='backDelete'){
                foreach ($allDelete as $key => $value) {
                    $allDelete[$key]=$allDelete[$key]/$num4[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"backDelete"=>$allDelete[$key]];
                }
            }else if($choice=='maxInternalTime'){
                foreach ($allMaxInternalTime as $key => $value) {
                    $allMaxInternalTime[$key]=$allMaxInternalTime[$key]/$num5[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"maxInternalTime"=>$allMaxInternalTime[$key]];
                }
            }
            array_push($data3, $arrayData4);
         }
         array_push($allData, $data);
         array_push($allData, $data3);
         
         array_push($allData, $myDataReturn);
         array_push($allData, $myDetail);
         array_push($allData, $maxData);
         $this->renderJSON($allData);
     }
     public function ActiongetClassExerRanking(){
         $classID=$_POST['classID'];
         $exerciseID = $_POST['exerciseID'];
         $type=$_POST['type'];
         $choice=$_POST['choice'];
         $all=Array();
         if($type==1){
             $type='speed';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }else if($type==4){
             $type='correct';
         }else if($type==5){
             $type='free';
         }
         $all= ClassexerciseRecord::model()->findAll('classExerciseID=?',array($exerciseID));
         $allStudent=  Student::model()->findAll('classID=?',array($classID));
         $all2=Array();
         $all3=Array();
         foreach ($allStudent as $allStu) {
             $n=0;
             $all2=Array();
             foreach ($all as $al) {
                 if($al['studentID']==$allStu['userID']){
                     $n=1;
                     array_push($all2,$al);
                 }
             }
             if($n==1)
                array_push($all3, $all2);
         }
         
         $studentName='';
         $data = Array();
         foreach ($all3 as $al) {
             $n1=0;$n2=0;$n3=0;$n4=0;$n5=0;
             foreach ($al as $a) {
                $correct=$a['ratio_correct'];       //correct
                 if(strpos($correct,"&") === false){     
                      $correct=$correct."&".$correct;
                 }
                 $n=  strrpos($correct, "&");
                 $correct= substr($correct, $n+1);
                 $n1+=$correct;

                 $speed=$a['ratio_speed'];        //speeed
                 if(strpos($speed,"&") === false){     
                      $speed=$speed."&".$speed;
                 }
                 $n=  strrpos($speed, "&");
                 $speed= substr($speed, $n+1);
                 $n2+=$speed;

                 $maxSpeed=$a['ratio_maxSpeed'];     //maxSpeed
                 if(strpos($maxSpeed,"&") === false){     
                      $maxSpeed=$maxSpeed."&".$maxSpeed;
                 }
                 $n=  strrpos($maxSpeed, "&");
                 $maxSpeed= substr($maxSpeed, $n+1);
                 $n3+=$maxSpeed;

                 $backDelete=$a['ratio_backDelete'];     //backDelete
                 if(strpos($backDelete,"&") === false){     
                      $backDelete=$backDelete."&".$backDelete;
                 }
                 $n=  strrpos($backDelete, "&");
                 $backDelete= substr($backDelete, $n+1);
                 $n4+=$backDelete;

                 $maxInternalTime=$a['ratio_maxInternalTime'];        //maxInternalTime
                 if(strpos($maxInternalTime,"&") === false){     
                      $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
                 }
                 $n=  strrpos($maxInternalTime, "&");
                 $maxInternalTime= substr($maxInternalTime, $n+1);
                 $n5+=$maxInternalTime;
                 $studentName=Student::model()->find('userID=?',array($a['studentID']))['userName'];
             }
             $arrayData = ["studentID"=>$a['studentID'],"studentName"=>$studentName,"speed"=>$n2/count($al),"maxSpeed"=>$n3/count($al),"correct"=>$n1/count($al),"time"=>$studentName,"backDelete"=>$n4/count($al),'maxInternalTime'=>$n5/count($al)];
             array_push($data, $arrayData);
         }
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         
         $data2 = Array();
         $data3 = Array();
         $allData=Array();
         
         foreach ($all as $a) {
              $correct=$a['ratio_correct'];
              $correct2=$a['ratio_correct'];
              if(strpos($correct,"&") === false){     
                   $correct=$correct."&".$correct;
              }
              $n=  strrpos($correct, "&");
              $correct= substr($correct, $n+1);
              
              $speed=$a['ratio_speed'];
              $speed2=$a['ratio_speed'];
              if(strpos($speed,"&") === false){     
                   $speed=$speed."&".$speed;
              }
              $n=  strrpos($speed, "&");
              $speed= substr($speed, $n+1);
              
              $maxSpeed=$a['ratio_maxSpeed'];
              $maxSpeed2=$a['ratio_maxSpeed'];
              if(strpos($maxSpeed,"&") === false){     
                   $maxSpeed=$maxSpeed."&".$maxSpeed;
              }
              $n=  strrpos($maxSpeed, "&");
              $maxSpeed= substr($maxSpeed, $n+1);
              
              //backDelete
              $backDelete=$a['ratio_backDelete'];
              $backDelete2=$a['ratio_backDelete'];
              if(strpos($backDelete,"&") === false){     
                   $backDelete=$backDelete."&".$backDelete;
              }
              $n=  strrpos($backDelete, "&");
              $backDelete= substr($backDelete, $n+1);
              //maxInternalTime
              $maxInternalTime=$a['ratio_maxInternalTime'];
              $maxInternalTime2=$a['ratio_maxInternalTime'];
              if(strpos($maxInternalTime,"&") === false){     
                   $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
              }
              $n=  strrpos($maxInternalTime, "&");
              $maxInternalTime= substr($maxInternalTime, $n+1);
              //time
              $time = count($speed)*2-2;
              $time2 = count($speed)*2-2;
              $student=$a['studentID'];
              $studentName=Student::model()->find('userID=?',array($student))['userName'];
              //$arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
              $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
              //array_push($data, $arrayData);
              array_push($data2, $arrayData2);
         }
         $data = Tool::quickSort($data,$choice);
         //平均成绩
         $allCorrect=0;$allSpeed=0;$allMaxSpeed=0;$allDelete=0;$allMaxInternalTime=0;
            $corrects=Array();
            $speeds=Array();
            $maxSpeeds=Array();
            $deletes=Array();
            $maxCorrectNum=0;
            $maxSpeedNum=0;
            $maxMaxSpeedNum=0;
            $maxDeleteNum=0;
            $maxInternalTimeNum=0;
            foreach ($data2 as $da) {
                $correct=$da['correct'];     
                $corrects=explode("&", $correct);
                $maxCorrectNum=  (count($corrects)>$maxCorrectNum)?count($corrects):$maxCorrectNum;
                
                $speed=$da['speed'];
                $speeds=explode("&", $speed);
                $maxSpeedNum=  (count($speeds)>$maxSpeedNum)?count($speeds):$maxSpeedNum;
                
                $maxSpeed=$da['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                $maxMaxSpeedNum=  (count($maxSpeeds)>$maxMaxSpeedNum)?count($maxSpeeds):$maxMaxSpeedNum;
                
                $delete=$da['backDelete'];
                $deletes=explode("&", $delete);
                $maxDeleteNum=  (count($speeds)>$maxDeleteNum)?count($deletes):$maxDeleteNum;
                
                $maxInternalTime=$da['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                $maxInternalTimeNum=  (count($speeds)>$maxInternalTimeNum)?count($maxInternalTimes):$maxInternalTimeNum;
            }
            $allCorrect=Array();
            $allSpeed=Array();
            $allMaxSpeed=Array();
            $allDelete=Array();
            $allMaxInternalTime=Array();
            $num1=Array();
            $num2=Array();
            $num3=Array();
            $num4=Array();
            $num5=Array();
            foreach ($data2 as $d) {
                $correct=$d['correct'];     
                $corrects=explode("&", $correct);
                
                $speed=$d['speed'];
                $speeds=explode("&", $speed);
                
                $maxSpeed=$d['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                
                $delete=$d['backDelete'];
                $deletes=explode("&", $delete);
                
                $maxInternalTime=$d['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                
                if($choice=='speed'){
                    foreach ($speeds as $key => $value) {
                        if(isset($allSpeed[$key])){
                            $allSpeed[$key]+=$value;
                            $num1[$key]++;
                        }else{
                            $allSpeed[]+=$value;
                            $num1[]+=1;
                        }
                    }
                }else if($choice=='correct'){
                    foreach ($corrects as $key => $value) {
                        if(isset($allCorrect[$key])){
                            $allCorrect[$key]+=$value;
                            $num2[$key]++;
                        }else{
                            $allCorrect[]+=$value;
                            $num2[]+=1;
                        }
                    }
                }else if($choice=='maxSpeed'){
                    foreach ($maxSpeeds as $key => $value) {
                        if(isset($allMaxSpeed[$key])){
                            $allMaxSpeed[$key]+=$value;
                            $num3[$key]++;
                        }else{
                            $allMaxSpeed[]+=$value;
                            $num3[]+=1;
                        }
                    }
                }else if($choice=='backDelete'){
                    foreach ($deletes as $key => $value) {
                        if(isset($allDelete[$key])){
                            $allDelete[$key]+=$value;
                            $num4[$key]++;
                        }else{
                            $allDelete[]+=$value;
                            $num4[]+=1;
                        }
                    }
                }else if($choice=='maxInternalTime'){
                    foreach ($maxInternalTimes as $key => $value) {
                        if(isset($allMaxInternalTime[$key])){
                            $allMaxInternalTime[$key]+=$value;
                            $num5[$key]++;
                        }else{
                            $allMaxInternalTime[]+=$value;
                            $num5[]+=1;
                        }
                    }
                }
            }
            //求平均值，返回
            if($choice=='correct'){
                foreach ($allCorrect as $key => $value) {
                    
                    $allCorrect[$key]=$allCorrect[$key]/$num2[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"correct"=>$allCorrect[$key]];
                }
                
            }else if($choice=='speed'){
                foreach ($allSpeed as $key => $value) {
                    $allSpeed[$key]=$allSpeed[$key]/$num1[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"speed"=>$allSpeed[$key]];
                }
            }else if($choice=='maxSpeed'){
                foreach ($allMaxSpeed as $key => $value) {
                    $n=$key*2;
                    $allMaxSpeed[$key]=$allMaxSpeed[$key]/$num3[$key];
                    $arrayData4[] = ["duration"=>$n,"maxSpeed"=>$allMaxSpeed[$key]];
                }
            }else if($choice=='backDelete'){
                foreach ($allDelete as $key => $value) {
                    $allDelete[$key]=$allDelete[$key]/$num4[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"backDelete"=>$allDelete[$key]];
                }
            }else if($choice=='maxInternalTime'){
                foreach ($allMaxInternalTime as $key => $value) {
                    $allMaxInternalTime[$key]=$allMaxInternalTime[$key]/$num5[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"maxInternalTime"=>$allMaxInternalTime[$key]];
                }
            }
            array_push($data3, $arrayData4);
         
         array_push($allData, $data);
         array_push($allData, $data3);
         $this->renderJSON($allData);
     }
    
     public function ActiongetClassExerRankingAll(){
         $exerciseID = $_POST['exerciseID'];
         $type=$_POST['type'];
         $choice=$_POST['choice'];
         $id=$_POST['id'];
         $classID=$_POST['classID'];
         $seq=$_POST['seq'];
         $all=Array();
         if($type==1){
             $type='speed';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }else if($type==4){
             $type='correct';
         }else if($type==5){
             $type='free';
         }
         
         $all= ClassexerciseRecord::model()->findAll('classExerciseID=?',array($exerciseID));
         $allStudent=  Student::model()->findAll('classID=?',array($classID));
         $all2=Array();
         $all3=Array();
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         $arrayDetail=Array();
         $arrayDetailData=Array();
        
         $data2 = Array();
         $data3 = Array();
         $allData=Array();
         $myData=Array();
         $myDataAll=Array();
         foreach ($allStudent as $allStu) {
             $key=0;
             $n=0;
             $all2=Array();
             foreach ($all as $al) {
                 if($al['studentID']==$allStu['userID']){
                     $n++;
                     array_push($all2, $al);
                     //$key++;
                 }
                 
             }
             if($n!=0 && $allStu['userID']==$id){
                 $nn=Array();
                 $nn=["sequence"=>$n,"a"=>$n];
                 array_push($allData,$nn);
              }
             if($n!=0)
                array_push($all3, $all2);
         }
         
         $studentName='';
         $data = Array();
         $averageData=Array();
         $maxData=Array();
         $nn1=0;$nn2=0;$nn3=0;$nn4=0;$nn5=0;$nn6=0;$nn7=0;$nn8=0;
         $f1=0;$f2=0;$f3=0;$f4=0;$f5=0;$f6=0;$f7=0;$f8=0;$f9=0;
         
         foreach ($all3 as $al) {
             $f=0;
             $n1=0;$n2=0;$n3=0;$n4=0;$n5=0;$n6=0;$n7=0;$n8=0;
             $icon1=0;$icon2=0;$icon3=0;$icon4=0;$icon5=0;$icon6=0;$icon7=0;$icon8=0;$icon9=0;
             $ff1=0;$ff2=0;$ff3=0;$ff4=0;$ff5=0;$ff6=0;$ff7=0;$ff8=0;$ff9=0;
             $i1=0;
             foreach ($al as $a) {
                 if($a['studentID']==$id){
                     $i1++;
                 }
                $correct=$a['ratio_correct'];       //correct
                 if(strpos($correct,"&") === false){     
                      $correct=$correct."&".$correct;
                 }
                 $n=  strrpos($correct, "&");
                 $correct= substr($correct, $n+1);
                 $n1+=$correct;
                 $nn1+=$correct;
                 if($f1<$correct){
                     $f1=$correct;
                 }
                 if($correct>=$ff1&& $a['studentID']==$id){
                     $icon1=$i1;
                     $ff1=$correct;
                 }

                 $speed=$a['ratio_speed'];        //speeed
                 if(strpos($speed,"&") === false){     
                      $speed=$speed."&".$speed;
                 }
                 $n=  strrpos($speed, "&");
                 $speed= substr($speed, $n+1);
                 $n2+=$speed;
                 $nn2+=$speed;
                 if($f2<$speed){
                     
                     $f2=$speed;
                 }
                 if($speed>=$ff2 && $a['studentID']==$id){
                     $ff2=$speed;
                     $icon2=$i1;
                 }

                 $maxSpeed=$a['ratio_maxSpeed'];     //maxSpeed
                 if(strpos($maxSpeed,"&") === false){     
                      $maxSpeed=$maxSpeed."&".$maxSpeed;
                 }
                 $n=  strrpos($maxSpeed, "&");
                 $maxSpeed= substr($maxSpeed, $n+1);
                 $n3+=$maxSpeed;
                 $nn3+=$maxSpeed;
                 if($f3<$maxSpeed){
                     
                     $f3=$maxSpeed;
                 }
                 if($maxSpeed>=$ff3&& $a['studentID']==$id){
                     $ff3=$maxSpeed;
                     $icon3=$i1;
                 }

                 $backDelete=$a['ratio_backDelete'];     //backDelete
                 if(strpos($backDelete,"&") === false){     
                      $backDelete=$backDelete."&".$backDelete;
                 }
                 $n=  strrpos($backDelete, "&");
                 $backDelete= substr($backDelete, $n+1);
                 $n4+=$backDelete;
                 $nn4+=$backDelete;
                 if($f4<$backDelete){
                     
                     $f4=$backDelete;
                 }
                 if($backDelete>=$ff4&& $a['studentID']==$id){
                     $ff4=$backDelete;
                     $icon4=$i1;
                 }

                 $maxInternalTime=$a['ratio_maxInternalTime'];        //maxInternalTime
                 if(strpos($maxInternalTime,"&") === false){     
                      $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
                 }
                 $n=  strrpos($maxInternalTime, "&");
                 $maxInternalTime= substr($maxInternalTime, $n+1);
                 $n5+=$maxInternalTime;
                 $nn5+=$maxInternalTime;
                 if($f5<$maxInternalTime){
                     
                     $f5=$maxInternalTime;
                 }
                 if($maxInternalTime>=$ff5&& $a['studentID']==$id){
                     $ff5=$maxInternalTime;
                     $icon5=$i1;
                 }
                 
                 $averageKeyType=$a['ratio_averageKeyType'];   
                 if(strpos($averageKeyType,"&") === false){     
                      $averageKeyType=$averageKeyType."&".$averageKeyType;
                 }
                 $n=  strrpos($averageKeyType, "&");
                 $averageKeyType= substr($averageKeyType, $n+1);
                 $n6+=$averageKeyType;
                 $nn6+=$averageKeyType;
                 if($f6<$averageKeyType){
                    
                     $f6=$averageKeyType;
                 }
                 if($averageKeyType>=$ff6&& $a['studentID']==$id){
                     $ff6=$averageKeyType;
                     $icon6=$i1;
                 }
                 
                 $maxKeyType=$a['ratio_maxKeyType'];     
                 if(strpos($maxKeyType,"&") === false){     
                      $maxKeyType=$maxKeyType."&".$maxKeyType;
                 }
                 $n=  strrpos($maxKeyType, "&");
                 $maxKeyType= substr($maxKeyType, $n+1);
                 $n7+=$maxKeyType;
                 $nn7+=$maxKeyType;
                 if($f7<$maxKeyType){
                    
                     $f7=$maxKeyType;
                 }
                 if($maxKeyType>=$ff7&& $a['studentID']==$id){
                     $ff7=$maxKeyType;
                      $icon7=$i1;
                 }
                 
                 $countAllKey=$a['ratio_countAllKey'];     
                 if(strpos($countAllKey,"&") === false){     
                      $countAllKey=$countAllKey."&".$countAllKey;
                 }
                 $n=  strrpos($countAllKey, "&");
                 $countAllKey= substr($countAllKey, $n+1);
                 $n8+=$countAllKey;
                 $nn8+=$countAllKey;
                 if($f8<$countAllKey){
                     
                     $f8=$countAllKey;
                 }
                 if($countAllKey>=$ff8&& $a['studentID']==$id){
                     $ff8=$countAllKey;
                      $icon8=$i1;
                 }
                 
                 $finishDate=$a['finishDate'];          //finishDate
                 if($f9==0) $f9=$finishDate;
                 if($f9>$finishDate){
                     $f9=$finishDate;
                 }
                 
                 if($finishDate<=$ff9 && $a['studentID']==$id){
                     $ff9=$finishDate;
                     $icon9=$i1;
                 }
                 
                 $studentName=Student::model()->find('userID=?',array($a['studentID']))['userName'];
                 $f++;
                 if($a['studentID']==$id){
                    $arrayDetail=["studentID"=>$a['studentID'],"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,
                    "time"=>$studentName,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime,'sequence'=>$f,'averageKeyType'=>$averageKeyType,
                    'maxKeyType'=>$maxKeyType,'countAllKey'=>$countAllKey,'finishDate'=>$finishDate,'icon1'=>$icon1,'icon2'=>$icon2,'icon3'=>$icon3,'icon4'=>$icon4,'icon5'=>$icon5,'icon6'=>$icon6,'icon7'=>$icon7,'icon8'=>$icon8,'icon9'=>$icon9];
                    array_push($arrayDetailData, $arrayDetail);
                 }
             }
             $arrayData = ["studentID"=>$a['studentID'],"studentName"=>$studentName,"speed"=>$n2/count($al),"maxSpeed"=>$n3/count($al),"correct"=>$n1/count($al),
                 "time"=>$studentName,"backDelete"=>$n4/count($al),'maxInternalTime'=>$n5/count($al),'sequence'=>$f,'averageKeyType'=>$n6/count($al),
                 'maxKeyType'=>$n7/count($al),'countAllKey'=>$n8/count($al)];
             
             array_push($data, $arrayData);
             
         }
         $averageData=["correct"=>$nn1/count($all3),"speed"=>$nn2/count($all3),"maxSpeed"=>$nn3/count($all3),"backDelete"=>$nn4/count($all3),'maxInternalTime'=>$nn5/count($all3),
                     'averageKeyType'=>$nn6/count($all3),"maxKeyType"=>$nn7/count($all3),"countAllKey"=>$nn8/count($all3)];
         $maxData=["correct"=>$f1,"speed"=>$f2,"maxSpeed"=>$f3,"backDelete"=>$f4,'maxInternalTime'=>$f5,
                     'averageKeyType'=>$f6,"maxKeyType"=>$f7,"countAllKey"=>$f8,"finishDate"=>$f9];
         foreach ($all as $a) {
              $correct=$a['ratio_correct'];
              $correct2=$a['ratio_correct'];
              if(strpos($correct,"&") === false){     
                   $correct=$correct."&".$correct;
              }
              $n=  strrpos($correct, "&");
              $correct= substr($correct, $n+1);
              
              $speed=$a['ratio_speed'];
              $speed2=$a['ratio_speed'];
              if(strpos($speed,"&") === false){     
                   $speed=$speed."&".$speed;
              }
              $n=  strrpos($speed, "&");
              $speed= substr($speed, $n+1);
              
              $maxSpeed=$a['ratio_maxSpeed'];
              $maxSpeed2=$a['ratio_maxSpeed'];
              if(strpos($maxSpeed,"&") === false){     
                   $maxSpeed=$maxSpeed."&".$maxSpeed;
              }
              $n=  strrpos($maxSpeed, "&");
              $maxSpeed= substr($maxSpeed, $n+1);
              
              //backDelete
              $backDelete=$a['ratio_backDelete'];
              $backDelete2=$a['ratio_backDelete'];
              if(strpos($backDelete,"&") === false){     
                   $backDelete=$backDelete."&".$backDelete;
              }
              $n=  strrpos($backDelete, "&");
              $backDelete= substr($backDelete, $n+1);
              //maxInternalTime
              $maxInternalTime=$a['ratio_maxInternalTime'];
              $maxInternalTime2=$a['ratio_maxInternalTime'];
              if(strpos($maxInternalTime,"&") === false){     
                   $maxInternalTime=$maxInternalTime."&".$maxInternalTime;
              }
              $n=  strrpos($maxInternalTime, "&");
              $maxInternalTime= substr($maxInternalTime, $n+1);
              //time
              $time = count($speed)*2-2;
              $time2 = count($speed)*2-2;
              $student=$a['studentID'];
              if($a['studentID']==$id){
                   $myData = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
              }
              $studentName=Student::model()->find('userID=?',array($student))['userName'];
              //$arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
              $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
              //array_push($data, $arrayData);
              array_push($data2, $arrayData2);
              if($a['studentID']==$id){
                array_push($myDataAll, $myData);
              }
         }
         $data = Tool::quickSort($data,$choice);
         //解析myData
         $myCorrect=Array();
            $myDataReturn=Array();
            $myDataReturn2=Array();
            $s1=0;$s2=0;$s3=0;$s4=0;
            $n1=-1;$n2=-1;$n3=-1;$n4=-1;
            $minN1=0;$minN2=0;$minN3=0;$minN4=0;
            
           $min=0;$max=0;
           $minN=0;$maxN=0;
            foreach ($myDataAll as $my2) {
                
                
                $myDataReturn=Array();
                $myData=$my2;
            
            //$myData=$myDataAll[$seq];
            $mySpeed=  explode("&",$myData['speed']);
            $myCorrect=  explode("&",$myData['correct']);
            $myMaxSpeed=  explode("&",$myData['maxSpeed']);
            $myBackDelete=  explode("&",$myData['backDelete']);
            $myInternalTime=  explode("&",$myData['maxInternalTime']);
            $myCorrectNum=  count($myCorrect);
            if($choice=='correct'){
                $n1++;
                $s1=0;
                foreach ($myCorrect as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"correct"=>$myCorrect[$key]];
                    $s1+=intval($value);
                    
                }
                $s1=$s1/($key+1);
                               
                if($n1==0){
                    $min=$s1;
                    $max=$s1;
                }
                if($s1<=$min){
                    $min=$s1;
                    $minN=$n1;
                }
                if($s1>=$max){
                    $max=$s1;
                    $maxN=$n1;
                }
            }else if($choice=='speed'){
                $n2++;
                $s2=0;
                foreach ($mySpeed as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"speed"=>$mySpeed[$key]];
                    $s2+=intval($value);
                }
                $s2=$s2/($key+1);
                if($n2==0){
                   $min=$s2;
                   $max=$s2;
                }
                if($s2<=$min){
                    $min=$s2;
                    $minN=$n2;
                }
                if($s2>=$max){
                    $max=$s2;
                    $maxN=$n2;
                }
            }else if($choice=='maxSpeed'){
                $n3++;
                $s3=0;
                foreach ($myMaxSpeed as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"maxSpeed"=>$myMaxSpeed[$key]];
                    $s3+=intval($value);
                }
                
                $s3=$s3/($key+1);
                
                if($n3==0){
                    $min=$s3;
                    $max=$s3;
                }
                if($s3<=$min){
                    $min=$s3;
                    $minN=$n3;
                }
                if($s3>=$max){
                    $max=$s3;
                    $maxN=$n3;
                }

            }else if($choice=='backDelete'){
                $n4++;
                $s4=0;
                foreach ($myBackDelete as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"backDelete"=>$myBackDelete[$key]];
                    $s4+=intval($value);
                }
                $s4=$s4/($key+1);
                if($n4==0){
                    $min=$s4;
                    $max=$s4;
                }
                if($s4<=$min){
                    $min=$s4;
                    $minN=$n4;
                }
                if($s4>=$max){
                    $max=$s4;
                    $maxN=$n4;
                }
            }else if($choice=='maxInternalTime'){
                foreach ($myInternalTime as $key => $value) {
                    $n=$key*2;
                    $myDataReturn[] = ["time"=>$n,"maxInternalTime"=>$myInternalTime[$key]];
                }
            }
            $re=Array();
            //$re=["minN"=>$minN,"maxN"=>$maxN];
           // array_push($myDataReturn, $re);
            array_push($myDataReturn2, $myDataReturn);
          }
          $myDataReturn3=Array();
          
          $f=-1;
          foreach ($myDataReturn2 as $my) {
              $f++;
              $myDataRet=Array();
              if($f==$minN || $f==$maxN){
                    foreach ($my as $m) {
                            $myDataRet[]=$m;
                    }
              }
              if($myDataRet!=NULL){
                  array_push($myDataReturn3,$myDataRet);
              }
          }
         //平均成绩
         $allCorrect=0;$allSpeed=0;$allMaxSpeed=0;$allDelete=0;$allMaxInternalTime=0;
            $corrects=Array();
            $speeds=Array();
            $maxSpeeds=Array();
            $deletes=Array();
            $maxCorrectNum=0;
            $maxSpeedNum=0;
            $maxMaxSpeedNum=0;
            $maxDeleteNum=0;
            $maxInternalTimeNum=0;
            foreach ($data2 as $da) {
                $correct=$da['correct'];     
                $corrects=explode("&", $correct);
                $maxCorrectNum=  (count($corrects)>$maxCorrectNum)?count($corrects):$maxCorrectNum;
                
                $speed=$da['speed'];
                $speeds=explode("&", $speed);
                $maxSpeedNum=  (count($speeds)>$maxSpeedNum)?count($speeds):$maxSpeedNum;
                
                $maxSpeed=$da['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                $maxMaxSpeedNum=  (count($maxSpeeds)>$maxMaxSpeedNum)?count($maxSpeeds):$maxMaxSpeedNum;
                
                $delete=$da['backDelete'];
                $deletes=explode("&", $delete);
                $maxDeleteNum=  (count($speeds)>$maxDeleteNum)?count($deletes):$maxDeleteNum;
                
                $maxInternalTime=$da['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                $maxInternalTimeNum=  (count($speeds)>$maxInternalTimeNum)?count($maxInternalTimes):$maxInternalTimeNum;
            }
            $allCorrect=Array();
            $allSpeed=Array();
            $allMaxSpeed=Array();
            $allDelete=Array();
            $allMaxInternalTime=Array();
            $num1=Array();
            $num2=Array();
            $num3=Array();
            $num4=Array();
            $num5=Array();
            foreach ($data2 as $d) {
                $correct=$d['correct'];     
                $corrects=explode("&", $correct);
                
                $speed=$d['speed'];
                $speeds=explode("&", $speed);
                
                $maxSpeed=$d['maxSpeed'];
                $maxSpeeds=explode("&", $maxSpeed);
                
                $delete=$d['backDelete'];
                $deletes=explode("&", $delete);
                
                $maxInternalTime=$d['maxInternalTime'];
                $maxInternalTimes=explode("&", $maxInternalTime);
                
                if($choice=='speed'){
                    foreach ($speeds as $key => $value) {
                        if(isset($allSpeed[$key])){
                            $allSpeed[$key]+=$value;
                            $num1[$key]++;
                        }else{
                            $allSpeed[]+=$value;
                            $num1[]+=1;
                        }
                        
                    }
                }else if($choice=='correct'){
                    foreach ($corrects as $key => $value) {
                        if(isset($allCorrect[$key])){
                            $allCorrect[$key]+=$value;
                            $num2[$key]++;
                        }else{
                            $allCorrect[]+=$value;
                            $num2[]+=1;
                        }
                    }
                }else if($choice=='maxSpeed'){
                    foreach ($maxSpeeds as $key => $value) {
                        if(isset($allMaxSpeed[$key])){
                            $allMaxSpeed[$key]+=$value;
                            $num3[$key]++;
                        }else{
                            $allMaxSpeed[]+=$value;
                            $num3[]+=1;
                        }
                    }
                }else if($choice=='backDelete'){
                    foreach ($deletes as $key => $value) {
                        if(isset($allDelete[$key])){
                            $allDelete[$key]+=$value;
                            $num4[$key]++;
                        }else{
                            $allDelete[]+=$value;
                            $num4[]+=1;
                        }
                    }
                }else if($choice=='maxInternalTime'){
                    foreach ($maxInternalTimes as $key => $value) {
                        if(isset($allMaxInternalTime[$key])){
                            $allMaxInternalTime[$key]+=$value;
                            $num5[$key]++;
                        }else{
                            $allMaxInternalTime[]+=$value;
                            $num5[]+=1;
                        }
                    }
                }
            }
            //求平均值，返回
            if($choice=='correct'){
                foreach ($allCorrect as $key => $value) {
                    
                    $allCorrect[$key]=$allCorrect[$key]/$num2[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"correct"=>$allCorrect[$key]];
                }
                
            }else if($choice=='speed'){
                foreach ($allSpeed as $key => $value) {
                    $allSpeed[$key]=$allSpeed[$key]/$num1[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"speed"=>$allSpeed[$key]];
                }
            }else if($choice=='maxSpeed'){
                foreach ($allMaxSpeed as $key => $value) {
                    $n=$key*2;
                    $allMaxSpeed[$key]=$allMaxSpeed[$key]/$num3[$key];
                    $arrayData4[] = ["duration"=>$n,"maxSpeed"=>$allMaxSpeed[$key]];
                }
            }else if($choice=='backDelete'){
                foreach ($allDelete as $key => $value) {
                    $allDelete[$key]=$allDelete[$key]/$num4[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"backDelete"=>$allDelete[$key]];
                }
            }else if($choice=='maxInternalTime'){
                foreach ($allMaxInternalTime as $key => $value) {
                    $allMaxInternalTime[$key]=$allMaxInternalTime[$key]/$num5[$key];
                    $n=$key*2;
                    $arrayData4[] = ["duration"=>$n,"maxInternalTime"=>$allMaxInternalTime[$key]];
                }
            }
            array_push($data3, $arrayData4);
         
         array_push($allData, $data);
         array_push($allData, $data3);
         array_push($allData, $myDataReturn3);
         array_push($allData, $arrayDetailData);
         //array_push($allData, $averageData);
         array_push($allData, $maxData);

         $nn=Array();
        $nn=["minN"=>$minN,"maxN"=>$maxN];
        array_push($allData,$nn);
        array_push($allData, $myDataReturn2);
         $this->renderJSON($allData);
     }

     public function actionCheckBrief(){
         $word = $_POST['word'];
         $isBrief = FALSE;
         $content = str_replace("<", "", explode("><", $word)[0]);
         $yaweiCode = str_replace(">", "", explode("><", $word)[1]);
         $array_brief = TwoWordsLibBrief::model()->findAll();
         foreach ($array_brief as $v){
             if($v['words']===$content){
                 $origenCode = str_replace(":0", "", $v['yaweiCode']);
                 if($origenCode===$yaweiCode){
                     $isBrief = TRUE;
                 }
             }
         }
         if(iconv_strlen($content,"UTF-8")>2){
             echo  "true";   
         }else if($isBrief){
             echo  "true";
         }else{
              echo "false";  
         }
     }
     
    public function actionGetBrief(){
        $array_brief = TwoWordsLibBrief::model()->findAll();
        $array_brief2 = WordsLibBrief::model()->findAllOrderByIDDesc();
        $data = array();//word
        $data2 = array();//yaweiCode
        $data3 = array();//type
         foreach ($array_brief as $v){
             array_push($data, $v['words']);
             array_push($data2, $v['yaweiCode']);
             array_push($data3, $v['type']);
         }
         foreach ($array_brief2 as $v){
             array_push($data, $v['words']);
         }
         $data = implode('&',$data)."$".implode('&',$data2)."$".implode('&',$data3);
         echo $data;
    }
    
    public function actionLoginOut(){
        $user = $_POST['user'];
        $userID = $_POST['userID'];
        if($user=='student'){
            $result = Student::model()->isLogin($userID, 0);
        }else if($user=='teacher'){
            $result = Teacher::model()->isLogin($userID, 0);
        }
        echo $result;
    }
    public function actionGetDiligence(){
    $classID = $_GET['classID'];
    $all=Array();
    $sqlstudent=  Student::model()->findAll("classID= '$classID'");
    $student = array();
    $look = array();
    $listen = array();
    $tlook = array();
    $tlisten = array();
    $Dili =array();
    $SID=array();
        foreach ($sqlstudent as $v){
            $n = $v['userName'];
            $u = $v['userID'];
            $look= AnswerRecord::model()->findAll("createPerson= '$u' and type ='look'");
            if($look == null){
                 $looktotal = 0;
                 array_push($look, $looktotal);
                 $c = array_sum($look);
            }else{ 
            foreach ($look as $k){
                 $str1 = $k['answer'];
                 $count = Tool::filterContentOfInputWithYaweiCode($str1);
                 $looktotal = (strlen($count)/3);
                 error_log($looktotal);
                 array_push($look, $looktotal);
            }
                 $c = array_sum($look);
            }
            $listen = AnswerRecord::model()->findAll("createPerson= '$u' and type ='listen'");
            if($listen == null){
              $listentotal = 0;
              array_push($listen, $listentotal);
                 $b = array_sum($listen);

            }else{
             foreach ($listen as $k){
                 $str2 = $k['answer'];
                 $count = Tool::filterContentOfInputWithYaweiCode($str2);
                 $listentotal = (strlen($count)/3);
                 error_log(strlen($count));
                 array_push($listen, $listentotal);
            }
                 $b = array_sum($listen);    
             }
             $diligence = ($b+$c);
             $studentID = $v['userID'];
        $connection = Yii::app()->db;
        $sql = "UPDATE `student` SET diligence = '$diligence' WHERE userID = '$studentID'";
        $command = $connection->createCommand($sql);
        $command->execute();
            } 
        $sql = "select * FROM student WHERE classID = '$classID'Order By diligence Desc";
        $criteria   =   new CDbCriteria();
        $Dil  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($Dil as $key){
         array_push($student, $key['userName']);
         array_push($SID, $key['userID']);
         array_push($Dili, $key['diligence']);
        }
        $this->renderJSON(['student'=>$student,'SID'=>$SID,'Dili'=>$Dili,]);
        }
        
        
        public function actionGetClassExerciseRanking(){
          $isexam = $_POST['isexam'];
          $workID = $_POST['workID'];
//          $exerciseID =$_POST['exerciseID'];
          if($isexam==1){
          $classwork = ExamRecord::model()->findAll("workID = '$workID'");
          }
          else{
          $classwork = SuiteRecord::model()->findAll("workID = '$workID'");}
          foreach ($classwork as $work){
          $recordID = $work['recordID'];
          $sql = "SELECT * FROM answer_record where recordID = '$recordID' and type in('look','listen','key')";
          $answer = Yii::app()->db->createCommand($sql)->query();
          foreach ($answer as $ans){
          $answerID = $ans['answerID'];
        //正确率
        $correct=$ans['ratio_correct'];
        $correct2=$ans['ratio_correct'];
        if(strpos($correct,"&") === false){     
        $correct=$correct."&".$correct;
                 }
        $n=  strrpos($correct, "&");
        $correct= substr($correct, $n+1);
        //回删
        $backDelete=$ans['ratio_backDelete'];
        $backDelete2=$ans['ratio_backDelete'];
                 if(strpos($backDelete,"&") === false){     
                      $backDelete=$backDelete."&".$backDelete;
                 }
                 $n=  strrpos($backDelete, "&");
                 $backDelete= substr($backDelete, $n+1); 
                 
         //速度        
         $speed=$ans['ratio_speed'];
         $speed2=$ans['ratio_speed'];
              if(strpos($speed,"&") === false){     
                   $speed=$speed."&".$speed;
              }
              $n=  strrpos($speed, "&");
              $speed= substr($speed, $n+1); 
              //创建人
              $userID = $ans['createPerson'];
              //类型
              $type = $ans['type'];
              $exerciseID = $ans['exerciseID'];
          
        $sql = "SELECT * FROM rank_answer where answerID = '$answerID'";
        $oldans= Yii::app()->db->createCommand($sql)->query();
        foreach ($oldans as $oldrank){
        $oldtype = $oldrank['type'];}
        $scccc = count($oldans);
        if($scccc==1){
        $connection = Yii::app()->db;
        $sql = "UPDATE rank_answer SET correct = '$correct',speed='$speed',backDelete = '$backDelete',speed ='$speed' WHERE answerID = '$answerID'";
        $command = $connection->createCommand($sql);
        $command->execute();
        
                  $sql = "SELECT * FROM answer_data where answerID = '$answerID'";
          $answer2 = Yii::app()->db->createCommand($sql)->query();
          $scc = count($answer2);
            if($scc!=0){
         foreach ($answer2 as $ans2){  
         //少打字数        
        $missing=$ans2['missing_Number'];
        $missing2=$ans2['missing_Number'];
        if(strpos($missing,"&") === false){     
        $missing=$missing."&".$missing;
                 }
        $n=  strrpos($missing, "&");
        $missing= substr($missing, $n+1);
        //多打字数
        $redundant=$ans2['redundant_Number'];
        $redundant=$ans2['redundant_Number'];
        if(strpos($redundant,"&") === false){     
        $redundant=$redundant."&".$redundant;
                 }
        $n=  strrpos($redundant, "&");
        $redundant= substr($redundant, $n+1);
        
        $oanswerID = $ans2['answerID'];
        if($oldtype=='key'){

            $sql = "UPDATE rank_answer SET redundant_Number = '0', missing_Number = '0' where answerID = '$oanswerID'";    
            $command = $connection->createCommand($sql);
            $command->execute(); 
        }else{
            $connection = Yii::app()->db;
            $sql = "UPDATE rank_answer SET redundant_Number = '$redundant', missing_Number = '$missing' where answerID = '$oanswerID'";
            $command = $connection->createCommand($sql);
            $command->execute(); 
        }
        }
        }
        }
        else{ 
            $studentname=  Student::model()->find("userID= '$userID'"); 
            $name = $studentname['userName'];
        if($type=='key'){
            $connection = Yii::app()->db;
            $sql = "INSERT INTO `rank_answer` (correct,answerID,backDelete,speed,userID,type,userName,isExam,workID,exerciseID,redundant_Number,missing_Number) values ('$correct','$answerID','$backDelete','$speed','$userID','$type','$name','$isexam','$workID','$exerciseID',0,0)";
            $command = $connection->createCommand($sql);
            $command->execute();
        }else{
            $connection = Yii::app()->db;
            $sql = "INSERT INTO `rank_answer` (correct,answerID,backDelete,speed,userID,type,userName,isExam,workID,exerciseID) values ('$correct','$answerID','$backDelete','$speed','$userID','$type','$name','$isexam','$workID','$exerciseID')";
            $command = $connection->createCommand($sql);
            $command->execute();}
            $sql = "SELECT * FROM answer_data where answerID = '$answerID'";
            $answer2 = Yii::app()->db->createCommand($sql)->query();
            $scc = count($answer2);
            if($scc!=0){
         foreach ($answer2 as $ans2){  
         //少打字数        
            $missing=$ans2['missing_Number'];
            $missing2=$ans2['missing_Number'];
        if(strpos($missing,"&") === false){     
            $missing=$missing."&".$missing;
                 }
            $n=  strrpos($missing, "&");
            $missing= substr($missing, $n+1);
        //多打字数
            $redundant=$ans2['redundant_Number'];
            $redundant=$ans2['redundant_Number'];
        if(strpos($redundant,"&") === false){     
            $redundant=$redundant."&".$redundant;
                 }
            $n=  strrpos($redundant, "&");
            $redundant= substr($redundant, $n+1);
        
            $oanswerID = $ans2['answerID'];
            $connection = Yii::app()->db;
            $sql = "UPDATE rank_answer SET redundant_Number = '$redundant', missing_Number = '$missing' where answerID = '$oanswerID'";
            $command = $connection->createCommand($sql);
            $command->execute();}
        }
        }
          }
        }
          $student =11;
         $this->renderJSON(['student'=>$student]);   
        }
        
        public function actionShowSuiteRank(){
         $workID = $_POST['workID'];
         $type = $_POST['type'];
         $exerciseID = $_POST['exerciseID'];
         $isexam = $_POST['isexam'];
         error_log($exerciseID);
          if(isset($_POST['choice'])){
          $choice =   $_POST['choice'];
         }else{
         $choice = 'correct';}
            $id = array();
            $name = array();
            $speed = array();
            $correct = array();
            $missing = array();
            $redundant = array();
            $backDelete = array();
         if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
        $sql = "select * FROM rank_answer WHERE workID = '$workID' and type = '$type' and exerciseID ='$exerciseID' and isexam = '$isexam' Order By $choice Desc";
        $criteria   =   new CDbCriteria();
        $rank  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rank as $r){
            array_push($id, $r['userID']);
            array_push($name, $r['userName']);
            array_push($speed, $r['speed']);
            array_push($correct, $r['correct']);
            array_push($missing, $r['missing_Number']);
            array_push($redundant, $r['redundant_Number']);
            array_push($backDelete, $r['backDelete']);
        }   
          $this->renderJSON(['id'=>$id,'name'=>$name,'speed'=>$speed,'correct'=>$correct,'missing'=>$missing,'redundant'=>$redundant,'backDelete'=>$backDelete]);  
        }
    public function actionGetExerciseRanking(){
        $exerciseID = $_POST['exerciseID'];
        $isExam = $_POST['isexam']; 
        $type = $_POST['type'];
        error_log($type);
         if($type==1){
             $type='speed';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }else if($type==4){
             $type='correct';
         }else if($type==5){
             $type='free';
         }
        $sql = "SELECT MAX(squence),studentID,classExerciseID,ratio_speed,ratio_correct,ratio_backDelete FROM `classexercise_record` WHERE classExerciseID = '$exerciseID' GROUP BY studentID";
        $criteria   =   new CDbCriteria();
        $exerciseRecord  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($exerciseRecord as $Record){
          $studentID =  $Record['studentID'];
          //速度
          $speed=$Record['ratio_speed'];
         $speed2=$Record['ratio_speed'];
          if(strpos($speed,"&") === false){     
                   $speed=$speed."&".$speed;
              }
          $n=  strrpos($speed, "&");
          $speed= substr($speed, $n+1); 
              //正确率
          $correct=$Record['ratio_correct'];
          $correct2=$Record['ratio_correct'];
          if(strpos($correct,"&") === false){     
          $correct=$correct."&".$correct;
           }
          $n=  strrpos($correct, "&");
          $correct= substr($correct, $n+1);  
        //回删
        $backDelete=$Record['ratio_backDelete'];
        $backDelete2=$Record['ratio_backDelete'];
        if(strpos($backDelete,"&") === false){     
        $backDelete=$backDelete."&".$backDelete;
                 }
        $n=  strrpos($backDelete, "&");
        $backDelete= substr($backDelete, $n+1);
        $sql = "SELECT * FROM `rank_answer` WHERE exerciseID = '$exerciseID' and userID ='$studentID'";
        $criteria   =   new CDbCriteria();
        $exer  =   Yii::app()->db->createCommand($sql)->queryAll();
        $scccc = count($exer);
        if($scccc==1){
        $connection = Yii::app()->db;
        $sql = "UPDATE rank_answer SET correct = '$correct',speed='$speed',backDelete = '$backDelete' WHERE exerciseID = '$exerciseID' and userID ='$studentID'";
        $command = $connection->createCommand($sql);
        $command->execute();     
        }  else {
        $studentname=  Student::model()->find("userID= '$studentID'"); 
        $name = $studentname['userName'];
        $answerID = Tool::createID();
        $connection = Yii::app()->db;
        $sql = "INSERT INTO `rank_answer` (correct,answerID,backDelete,speed,userID,userName,isExam,exerciseID,type,missing_Number,redundant_Number) values ('$correct','$answerID','$backDelete','$speed','$studentID','$name','$isExam','$exerciseID','$type',0,0)";
        $command = $connection->createCommand($sql);
        $command->execute();
        }
        }
        $student =11;
        $this->renderJSON(['student'=>$student]);  
    }
    public function actionShowExerciseRank(){
         $exerciseID = $_POST['exerciseID'];
         $isexam = $_POST['isexam'];
          if(isset($_POST['choice'])){
          $choice =   $_POST['choice'];
         }else{
         $choice = 'correct';}
            $id = array();
            $name = array();
            $speed = array();
            $correct = array();
            $missing = array();
            $redundant = array();
            $backDelete = array();
        $sql = "select * FROM rank_answer WHERE exerciseID = '$exerciseID' and isexam = '$isexam' Order By $choice Desc";
        $criteria   =   new CDbCriteria();
        $rank  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rank as $r){
            array_push($id, $r['userID']);
            array_push($name, $r['userName']);
            array_push($speed, $r['speed']);
            array_push($correct, $r['correct']);
            array_push($missing, $r['missing_Number']);
            array_push($redundant, $r['redundant_Number']);
            array_push($backDelete, $r['backDelete']);
        }   
          $this->renderJSON(['id'=>$id,'name'=>$name,'speed'=>$speed,'correct'=>$correct,'missing'=>$missing,'redundant'=>$redundant,'backDelete'=>$backDelete]);  
    }
        
}


