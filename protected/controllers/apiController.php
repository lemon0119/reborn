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
        $backtime = date('y-m-d H:i:s',time());
        $connection = Yii::app()->db;
        $sql = "UPDATE tb_class SET backTime='$backtime' WHERE classID='$classID'";
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
        $sql = "SELECT userName,backTime FROM student";
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
        $connection = Yii::app()->db;
        $sql = "SELECT backTime FROM tb_class where classID = '$classID'";
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
    
    //AnalysisTool create by pengjingcheng_2015_12_3  @qq:390928903  ------>{
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
        if($exerciseType === "classExercise"){
            $classExerciseID = $exerciseData[0];
            $studentID = $exerciseData[1];
            $sqlClassExerciseRecord = ClassexerciseRecord::model()->find("classExerciseID = '$classExerciseID' and squence = '$squence' and studentID = '$studentID'");
            if(!isset($sqlClassExerciseRecord)){
                 ClassexerciseRecord::model()->insertClassexerciseRecord($classExerciseID, $studentID, $squence, $ratio_speed, $ratio_correct, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey);
            }else{
                 ClassexerciseRecord::model()->updateClassexerciseRecord($classExerciseID, $studentID, $squence, $ratio_speed, $ratio_correct, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey);
            }
        }       
        if($exerciseType === "answerRecord"){
           if(Yii::app()->session['isExam']){
                if(!ExamRecord::saveExamRecord($recordID))
                if($squence>0){
                    error_log("已有");
                    $createPerson = Yii::app()->session['userid_now'];
                    AnswerRecord::model()->deleteRecordByIDandPerson($exerciseData, $createPerson);
                    
                }
                return false;
                return AnswerRecord::saveAnswer($recordID,$ratio_correct,$ratio_speed, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey, $squence,1);
            }else {
                if($squence>0){
                    error_log("已有");
                    $createPerson = Yii::app()->session['userid_now'];
                    AnswerRecord::model()->deleteRecordByIDandPerson($exerciseData, $createPerson);
                }
                if(!SuiteRecord::saveSuiteRecord ($recordID))
                    return false;
                return AnswerRecord::saveAnswer($recordID,$ratio_correct,$ratio_speed, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey,$squence,0);
            }   
        }    
        $this->renderJSON("");
    }
    //<--------------AnalysisTool create by pengjingcheng_2015_12_3  @qq:390928903 }
    
    
    
  
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
                    if($exercise['type'] == 'key')
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
                 $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
                 $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];

                 array_push($data, $arrayData);
                 array_push($data2, $arrayData2);
            }
            $data = Tool::quickSort($data,$choice);
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
                 
                 if($student==$name){
                     //通过名字获取相应记录
                    $myData = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
                 }
                 $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$time,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
                 $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];

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
         $this->renderJSON($allData);
     }
     public function ActiongetClassExerRanking(){
         $exerciseID = $_POST['exerciseID'];
         $type=$_POST['type'];
         $choice=$_POST['choice'];
         $all=Array();
         if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
         $all= ClassexerciseRecord::model()->findAll('classExerciseID=?',array($exerciseID));
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         $data = Array();
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
              $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
              $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
              array_push($data, $arrayData);
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
                        error_log($num1[$key]);
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
         $name=$_POST['name'];
         $all=Array();
         if($type==1){
             $type='key';
         }else if($type==2){
             $type='listen';
         }else if($type==3){
             $type='look';
         }
         $all= ClassexerciseRecord::model()->findAll('classExerciseID=?',array($exerciseID));
         $arrayData = Array();
         $arrayData2 = Array();
         $arrayData3 = Array();$arrayData4 = Array();
         $data = Array();
         $data2 = Array();
         $data3 = Array();
         $allData=Array();
         $myData=Array();
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
              if($student==$name){
                   $myData = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
              }
              $studentName=Student::model()->find('userID=?',array($student))['userName'];
              $arrayData = ["studentID"=>$student,"studentName"=>$studentName,"speed"=>$speed,"maxSpeed"=>$maxSpeed,"correct"=>$correct,"time"=>$student,"backDelete"=>$backDelete,'maxInternalTime'=>$maxInternalTime];
              $arrayData2 = ["speed"=>$speed2,"maxSpeed"=>$maxSpeed2,"correct"=>$correct2,"backDelete"=>$backDelete2,'maxInternalTime'=>$maxInternalTime2];
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
         array_push($allData, $myDataReturn);
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
        $data = array();
        $data2 = array();
         foreach ($array_brief as $v){
             array_push($data, $v['words']);
             array_push($data2, $v['yaweiCode']);
         }
         $data = implode('&',$data)."$".implode('&',$data2);
         echo $data;
    }
}


