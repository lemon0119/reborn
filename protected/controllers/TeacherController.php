<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TeacherController extends CController {

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

    public $layout = '//layouts/teacherBar';

    public function actionVirtualClass() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $cou = 0;
        $classID = $_GET['classID'];
        $cls = TbClass::model()->findByPK($classID);
        $userID = Yii::app()->session['userid_now'];
        $backtime = date('y-m-d H:i:s', time());
        $cls->backTime = $backtime;
        $cls->update();
        $username = Teacher::model()->findByPK($userID)->userName;

        //在线student个数
        $classID = $_GET['classID'];
        $connection = Yii::app()->db;
        $userID = array(Yii::app()->session['userid_now']);
        $sql = "SELECT userName,backTime FROM student WHERE";
        $where = " classID = '$classID'";
        $command = $connection->createCommand($sql . $where);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $n = 0;
        $b = 0;
        $totle = 0;
        $onLineStudent = array();
        foreach ($time as $t) {
            if (time() - strtotime($time[$b++]['backTime']) < 10) {
                array_push($onLineStudent, $t['userName']);
                $n++;
            }
            $totle++;
        }


        return $this->render('virtualClass', ['userName' => $username, 'classID' => $_GET['classID'], 'on' => $_GET['on'], 'onLineStudent' => $onLineStudent, 'count' => $n, 'totle' => $totle]);
    }

//add by LC 2015-10-13
    public function actionSetTimeAndScoreExam() {
        $flag = $_GET['flag'];
        $examID = $_GET['examID'];
        $duration = $_GET['duration'];
        $beginTime = $_GET['beginTime'];
        $isOpen = $_GET['isOpen'];
        $teacherID = Yii::app()->session['userid_now'];
        $array_class = array();
        $result = TbClass::model()->getClassByTeacherID($teacherID);
        foreach ($result as $class) {
            array_push($array_class, $class);
        }
        $result = Exam::model()->getAllExamByPage(10);
        $array_allexam = $result['examLst'];
        $pages = $result['pages'];
        //得到当前显示班级
//        if (isset($_GET['classID'])) {
//            Yii::app()->session['currentClass'] = $_GET['classID'];
//        } else if ($array_class != NULL) {
//            Yii::app()->session['currentClass'] = $array_class[0]['classID'];
//        } else {
//            Yii::app()->session['currentClass'] = 0;
//        }
        $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));
        $examExer = ExamExercise::model()->getExamExerAll($examID);
        //获取分数和时间
        $choiceAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'choice']);
        $fillingAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'filling']);
        $questAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'question']);
        $listenAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'listen']);

        $choiceScore = $fillingScore = $questScore = 0;
        if ($choiceAll)
            $choiceScore = $choiceAll[0]['score'];
        if ($fillingAll)
            $fillingScore = $fillingAll[0]['score'];
        if ($questAll)
            $questScore = $questAll[0]['score'];
        $this->render('setExamExerTime', array('array_class' => $array_class,
            'flag' => $flag,
            'array_allexam' => $array_allexam,
            'duration' => $duration,
            'beginTime' => $beginTime,
            'isOpen' => $isOpen,
            'pages' => $pages,
            'array_exam' => $array_suite,
            'examExer' => $examExer,
            'examID' => $examID,
            'choiceScore' => $choiceScore,
            'fillingScore' => $fillingScore,
            'questScore' => $questScore,
            'listenAll' => $listenAll,
        ));
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
    public function actionSaveTimeAll() {
        $examID = (isset($_GET['examID'])) ? $_GET['examID'] : 0;
        $choiceScore = (isset($_POST['choiceScore'])) ? $_POST['choiceScore'] : 0;
        $fillScore = (isset($_POST['fillScore'])) ? $_POST['fillScore'] : 0;
        $questScore = (isset($_POST['questScore'])) ? $_POST['questScore'] : 0;
        if (!!$choiceScore) {
            $choiceAll = ExamExercise::model()->findAll("examID = ? and type = ?  and exerciseID in ( select exerciseID from choice)", [$examID, 'choice']);
            foreach ($choiceAll as $choice) {
                $choice->score = $choiceScore;
                $choice->update();
            }
        }
        if (!!$fillScore) {
            $fillingAll = ExamExercise::model()->findAll("examID = ? and type = ? and exerciseID in ( select exerciseID from filling)", [$examID, 'filling']);
            foreach ($fillingAll as $exer) {
                $exer->score = $fillScore;
                $exer->update();
            }
        }
        if (!!$questScore) {
            $questAll = ExamExercise::model()->findAll("examID = ? and type = ?  and exerciseID in ( select exerciseID from question)", [$examID, 'question']);
            foreach ($questAll as $exer) {
                $exer->score = $questScore;
                $exer->update();
            }
        }
        $listenAll = ExamExercise::model()->findAll("examID = ? and type = ? and exerciseID in ( select exerciseID from listen_type)", [$examID, 'listen']);
        foreach ($listenAll as $one) {
            $scoreGetKey = "listen" . $one['exerciseID'] . 'Score';
            $timeGetKey = "listen" . $one['exerciseID'] . 'Time';
            $score = $_POST[$scoreGetKey];
            $time = $_POST[$timeGetKey];
            if (!!$score) {
                $one->score = $score;
            }
            if (!!$time) {
                $one->time = $time;
            }
            $one->update();
        }
        $lookAll = ExamExercise::model()->findAll("examID = ? and type = ? and exerciseID in ( select exerciseID from look_type)", [$examID, 'look']);
        foreach ($lookAll as $one) {
            $scoreGetKey = "look" . $one['exerciseID'] . 'Score';
            $timeGetKey = "look" . $one['exerciseID'] . 'Time';
            $score = $_POST[$scoreGetKey];
            $time = $_POST[$timeGetKey];
            if (!!$score) {
                $one->score = $score;
            }
            if (!!$time) {
                $one->time = $time;
            }
            $one->update();
        }
        $keyAll = ExamExercise::model()->findAll("examID = ? and type = ? and exerciseID in ( select exerciseID from key_type)", [$examID, 'key']);
        foreach ($keyAll as $one) {
            $scoreGetKey = "key" . $one['exerciseID'] . 'Score';
            $timeGetKey = "key" . $one['exerciseID'] . 'Time';
            $score = $_POST[$scoreGetKey];
            $time = $_POST[$timeGetKey];
            if (!!$score) {
                $one->score = $score;
            }
            if (!!$time) {
                $one->time = $time;
            }
            $one->update();
        }
        echo '保存成功';
    }

//end add by LC    
    public function actionSet() {       //set
        $result = 'no';
        $mail = '';
        $userid_now = Yii::app()->session['userid_now'];
        $user = Teacher::model()->find('userID=?', array($userid_now));
        if (!empty($user->mail_address)) {
            $mail = $user->mail_address;
        }
        if (isset($_POST['old'])) {
            $new1 = $_POST['new1'];
            $defnew = $_POST['defnew'];
//            $email = $_POST['email'];

            $usertype = Yii::app()->session['role_now'];

            //$thisStudent=new Student();
            //$thisStudent->password=$new1;
            //$result=$thisStudent->update();
            $user = Teacher::model()->find('userID=?', array($userid_now));
            if ($user->password != md5($_POST['old'])) {
                $result = 'old error';
                $this->render('set', ['result' => $result, 'mail' => $mail]);
                return;
            }
            $user->password = md5($new1);
//            $user->mail_address = $email;
            $result = $user->update();
//            $mail = $email;
        }

        $this->render('set', ['result' => $result, 'mail' => $mail]);
    }

    public function actionChangeProgress() {
        $result = '1';
        $classID = $_GET['classID'];
        $on = $_GET['on'];
        $lesson = Lesson::model()->find("classID = '$classID' and number = '$on'");
        $lessonID = $lesson['lessonID'];
        $freePractice = ClassExercise::model()->findAll("classID = '$classID' and lessonID = '$lessonID'");
        $keywork = array();
        $look = array();
        $listen = array();
        $class = new TbClass();
        $class = $class->find("classID = '$classID'");
        $class->currentLesson = $on;
        $class->update();
        $stu = Array();
        $stu = Student::model()->findAll("classID=? and is_delete=?", array($classID, 0));
        foreach ($freePractice as $v) {
            if ($v['type'] == 'speed' || $v['type'] == 'correct' || $v['type'] == 'free') {
                array_push($keywork, $v);
            } else if ($v['type'] === 'look') {
                array_push($look, $v);
            } else if ($v['type'] === 'listen') {
                array_push($listen, $v);
            }
        }
        return $this->render('startCourse', [
                    'classID' => $classID,
                    'progress' => $on,
                    'on' => $on,
                    'stu' => $stu,
                    'keywork' => $keywork,
                    'look' => $look,
                    'listen' => $listen,
                    'result' => $result
        ]);
    }

    public function actionPptLSt() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->render('pptLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddPp() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('addPpt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionPptTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('pptTable_new', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on
            ]);
        }
        return $this->renderPartial('pptTable', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddPpt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $pptFilePath1 = "";
        $dir1 = "";
        if (isset($_POST['checkbox'])) {
           $pptFilePath = "public/ppt/";
           $pptFilePath1 = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        } else {
            $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        }
        $dir = "resources/" . $pptFilePath;
        if($pptFilePath1!=""){
        $dir1 = "resources/" . $pptFilePath1;
        if(!is_dir($dir1)) {
           mkdir($dir1,0777);
          }
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }

        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlPpt = Resourse::model()->findAll("type = 'ppt'");
        foreach ($sqlPpt as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                echo "该文件已存在，如需重复使用请改名重新上传！";
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "application/vnd.ms-powerpoint") {
            if ($_FILES["file"]["size"] < 30000000) {
                if ($_FILES["file"]["error"] > 0) {
                    $result = "Return Code: " . $_FILES["file"]["error"];
                } else {
                    if($dir1!=""){
                    $newName = Tool::createID() . ".ppt";
                    $oldName = $_FILES["file"]["name"];
                    copy($_FILES["file"]["tmp_name"], $dir1 . iconv("UTF-8", "gb2312", $newName));
                    Resourse::model()->insertRela($newName, $oldName);
                    $result = "上传成功！"; 
                    }
                    $newName = Tool::createID() . ".ppt";
                    $oldName = $_FILES["file"]["name"];
                    move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                    Resourse::model()->insertRela($newName, $oldName);
                    $result = "上传成功！";
                   
                }
            } else {
                $reult = "PPT文件限定大小为30M！";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionAddPptNew() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if (isset($_POST['checkbox'])) {
            $pptFilePath = "public/ppt/";
        } else {
            $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        }
        $dir = "resources/" . $pptFilePath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            $result = "请选择文件！";
            return $this->renderPartial('addPpt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        $sqlPpt = Resourse::model()->findAll("type = 'ppt'");
        foreach ($sqlPpt as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return $this->renderPartial('addPpt', [
                            'classID' => $classID,
                            'progress' => $progress,
                            'on' => $on,
                            'result' => $result,
                ]);
            }
        }
        if ($_FILES["file"]["type"] == "application/vnd.ms-powerpoint") {
            if ($_FILES["file"]["size"] < 30000000) {
                if ($_FILES["file"]["error"] > 0) {
                    $result = "Return Code: " . $_FILES["file"]["error"];
                } else{
                    $newName = Tool::createID() . ".ppt";
                    $oldName = $_FILES["file"]["name"];
                    move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                    Resourse::model()->insertRela($newName, $oldName);
                    $result = "上传成功！";
                }
            } else {
                $reult = "PPT文件限定大小为30M！";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
        return $this->renderPartial('addPpt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionDeletePpt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['ppt'];
        if ($_GET['ispublic'] == 1) {
            $pptFilePath = "public/ppt/";
        } else {
            $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        }
        $dir = "resources/" . $pptFilePath;
        $file = $dir . $fileName;
        $result = 0;               //不显示提示
        if (!isset(Yii::app()->session['ppt2del']) ||
                Yii::app()->session['ppt2del'] != $fileName) {
            Yii::app()->session['ppt2del'] = $fileName;
            if (file_exists(iconv('utf-8', 'gb2312', $file))){
                unlink(iconv('utf-8', 'gb2312', $file));
            }
            Resourse::model()->delName($fileName);
            $result = "删除成功！";
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('addPpt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        return $this->render('pptLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result
        ]);
    }

    public function actionLookPpt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileDir = $_GET['ppt'];
        if (isset($_GET['pdir'])) {
            $dir = $_GET['pdir'] . $fileDir;
        } else {
            if ($_GET['ispublic'] == 1)
                $pptFilePath = "public/ppt/";
            else
                $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
            $dir = "resources/" . $pptFilePath . $fileDir;
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('lookPpt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'dir' => $dir,
                        'new' => 1
            ]);
        }
        return $this->render('lookPpt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'dir' => $dir,
                    'new' => 0
        ]);
    }

    public function actionVideoLst() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->render('videoLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddMovie() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('addMovie', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionVoiceLst() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->render('voiceLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddVo() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('addVoice', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionPictureLst() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->render('pictureLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddPic() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('addPicture', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionTxtLst() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->render('txtLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddTx() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('addTxt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionVideoTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('videoTable_new', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on
            ]);
        }
        return $this->renderPartial('videoTable', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionVoiceTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('voiceTable_new', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on
            ]);
        }
        return $this->renderPartial('voiceTable', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionPictureTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('pictureTable_new', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on
            ]);
        }
        return $this->renderPartial('pictureTable', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionTxtTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('txtTable_new', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on
            ]);
        }
        return $this->renderPartial('txtTable', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on
        ]);
    }

    public function actionAddVideo() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $videoFilePath1 = "";
        $dir1 = "";
        if (isset($_POST['checkbox'])) {
            $videoFilePath = "public/video/";
            $videoFilePath1 = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        } else {
            $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        }
        $dir = "resources/" . $videoFilePath;
        if($videoFilePath1!=""){
            $dir1 = "resources/" . $videoFilePath1;
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'video'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "video/mp4" || $_FILES["file"]["type"] == "application/octet-stream" && substr($_FILES["file"]["name"], strrpos($_FILES["file"]["name"], '.') + 1) != "rm" && substr($_FILES["file"]["name"], strrpos($_FILES["file"]["name"], '.') + 1) != "RM") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                if($dir1!=""){
                    $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                copy($_FILES["file"]["tmp_name"], $dir1 . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVideo($newName, $oldName);
                $result = "上传成功!";
                }
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVideo($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionAddVideoNew() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if (isset($_POST['checkbox'])) {
            $videoFilePath = "public/video/";
        } else {
            $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        }
        $dir = "resources/" . $videoFilePath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            $result = "请选择文件！";
            return $this->renderPartial('addMovie', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'video'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return $this->renderPartial('addMovie', [
                            'classID' => $classID,
                            'progress' => $progress,
                            'on' => $on,
                            'result' => $result,
                ]);
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "video/mp4" || $_FILES["file"]["type"] == "application/octet-stream") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {

                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVideo($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
        return $this->renderPartial('addMovie', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionAddTxt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $txtFilePath1 = "";
        $dir1 = "";
        if (isset($_POST['checkbox'])) {
            $txtFilePath = "public/txt/";
            $txtFilePath1 =  $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
        } else {
            $txtFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
        }
        $dir = "resources/" . $txtFilePath;
        if($txtFilePath1!=""){
            $dir1 ="resources/" . $txtFilePath1;
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'txt'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "text/plain") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                if($dir1!=""){
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                copy($_FILES["file"]["tmp_name"], $dir1 . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaTxt($newName, $oldName);
                $result = "上传成功!";   
                }
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaTxt($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionAddTxtNew() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if (isset($_POST['checkbox'])) {
            $txtFilePath = "public/txt/";
        } else {
            $txtFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
        }
        $dir = "resources/" . $txtFilePath;
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            $result = "请选择文件！";
            return $this->renderPartial('addTxt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        $sqlVideo = Resourse::model()->findAll("type = 'txt'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return $this->renderPartial('addTxt', [
                            'classID' => $classID,
                            'progress' => $progress,
                            'on' => $on,
                            'result' => $result,
                ]);
            }
        }
        if ($_FILES["file"]["type"] == "text/plain") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaTxt($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
        return $this->renderPartial('addTxt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionAddVoice() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $voiceFilePath1 = "";
        $dir1 = "";
        if (isset($_POST['checkbox'])) {
            $voiceFilePath = "public/voice/";
            $voiceFilePath1 =$typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
        } else {
            $voiceFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
        }
        $dir = "resources/" . $voiceFilePath;
        if($voiceFilePath1!=""){
            $dir1 ="resources/" . $voiceFilePath1;
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'voice'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "audio/wav" || $_FILES["file"]["type"] == "audio/mpeg") {

            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                if($dir1!=""){
                 $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                copy($_FILES["file"]["tmp_name"], $dir1 . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVoice($newName, $oldName);
                $result = "上传成功!";
                }
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVoice($newName, $oldName);
                $result = "上传成功!";
            }
        } else {

            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionAddVoiceNew() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if (isset($_POST['checkbox'])) {
            $voiceFilePath = "public/voice/";
        } else {
            $voiceFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
        }
        $dir = "resources/" . $voiceFilePath;
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            $result = "请选择文件！";
            return $this->renderPartial('addVoice', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        $sqlVideo = Resourse::model()->findAll("type = 'voice'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return $this->renderPartial('addVoice', [
                            'classID' => $classID,
                            'progress' => $progress,
                            'on' => $on,
                            'result' => $result,
                ]);
            }
        }
        if ($_FILES["file"]["type"] == "audio/wav" || $_FILES["file"]["type"] == "audio/mpeg") {

            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVoice($newName, $oldName);
                $result = "上传成功!";
            }
        } else {

            $result = "请上传正确类型的文件！";
        }
        echo $result;
        return $this->renderPartial('addVoice', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionAddPicture() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $picFilePath1 = "";
        $dir1= "";
        if (isset($_POST['checkbox'])) {
            $picFilePath = "public/picture/";
            $picFilePath1 = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
        } else {
            $picFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
        }
        $dir = "resources/" . $picFilePath;
        if($picFilePath1!=""){
            $dir1 = "resources/" . $picFilePath1;
        }
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'picture'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/bmp" || $_FILES["file"]["type"] == "image/gif") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                if($dir1!=""){
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                copy($_FILES["file"]["tmp_name"], $dir1 . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaPicture($newName, $oldName);
                $result = "上传成功!";
                }
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaPicture($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionAddPictureNew() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        if (isset($_POST['checkbox'])) {
            $picFilePath = "public/picture/";
        } else {
            $picFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
        }
        $dir = "resources/" . $picFilePath;
        $result = "上传失败!";
        $flag = 0;
        if (!isset($_FILES["file"])) {
            return $this->renderPartial('addPicture', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        $sqlVideo = Resourse::model()->findAll("type = 'picture'");
        foreach ($sqlVideo as $v) {
            if ($v['name'] == $_FILES["file"]["name"]) {
                $result = "该文件已存在，如需重复使用请改名重新上传！";
                return $this->renderPartial('addPicture', [
                            'classID' => $classID,
                            'progress' => $progress,
                            'on' => $on,
                            'result' => $result,
                ]);
            }
        }
        if ($_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/bmp" || $_FILES["file"]["type"] == "image/gif") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaPicture($newName, $oldName);
                $result = "上传成功!";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
        return $this->renderPartial('addPicture', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionDeleteVideo() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['video'];
        Resourse::model()->delName($fileName);
        if ($_GET['ispublic'] == 1) {
            $videoFilePath = "public/video/";
        } else {
            $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        }
        $dir = "resources/" . $videoFilePath;
        $file = $dir . $fileName;
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        $result = "删除成功！";
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('addMovie', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        return $this->render('videoLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionDeleteTxt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['txt'];
        Resourse::model()->delName($fileName);
        if ($_GET['ispublic'] == 1) {
            $txtFilePath = "public/txt/";
        } else {
            $txtFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
        }
        $dir = "resources/" . $txtFilePath;
        $file = $dir . $fileName;
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        $result = "删除成功！";
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('addTxt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        return $this->render('txtLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionDeleteVoice() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['voice'];
        Resourse::model()->delName($fileName);

        if ($_GET['ispublic'] == 1) {
            $voiceFilePath = "public/voice/";
        } else {
            $voiceFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
        }
        $dir = "resources/" . $voiceFilePath;
        $file = $dir . $fileName;
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        $result = "删除成功！";
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('addVoice', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        return $this->render('voiceLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionDeletePicture() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['picture'];
        Resourse::model()->delName($fileName);

        if ($_GET['ispublic'] == 1) {
            $picFilePath = "public/picture/";
        } else {
            $picFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
        }
        $dir = "resources/" . $picFilePath;
        $file = $dir . $fileName;
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        $result = "删除成功！";
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('addPicture', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'result' => $result,
            ]);
        }
        return $this->render('pictureLst', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'result' => $result,
        ]);
    }

    public function actionLookVideo() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $file = $_GET['video'];
        if (isset($_GET['vdir'])) {
            $file = $_GET['vdir'] . $file;
        } else {
            if ($_GET['ispublic'] == 1)
                $videoFilePath = "public/video/";
            else
                $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";

            $file = "resources/" . $videoFilePath . $file;
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('lookvideo', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'file' => $file,
                        'new' => 1
            ]);
        }
        return $this->render('lookvideo', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'file' => $file,
                    'new' => 0
        ]);
    }

    public function actionLookTxt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $file = $_GET['txt'];
        if (isset($_GET['vdir'])) {
            $file = $_GET['vdir'] . $file;
        } else {
            if ($_GET['ispublic'] == 1) {
                $txtFilePath = "public/txt/";
            } else {
                $txtFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
            }
            $file = "resources/" . $txtFilePath . $file;
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('looktxt', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'file' => $file,
                        'new' => 1
            ]);
        }
        return $this->render('looktxt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'file' => $file,
                    'new' => 0
        ]);
    }

    public function actionLookVoice() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $file = $_GET['voice'];
        if (isset($_GET['vdir'])) {
            $file = $_GET['vdir'] . $file;
        } else {
            if ($_GET['ispublic'] == 1) {
                $voiceFilePath = "public/voice/";
            } else {
                $voiceFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
            }
            $file = "resources/" . $voiceFilePath . $file;
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('lookvoice', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'file' => $file,
                        'new' => 1
            ]);
        }
        return $this->render('lookvoice', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'file' => $file,
                    'new' => 0
        ]);
    }

    public function actionLookPicture() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $file = $_GET['picture'];
        if (isset($_GET['vdir'])) {
            $file = $_GET['vdir'] . $file;
        } else {
            if ($_GET['ispublic'] == 1) {
                $picFilePath = "public/picture/";
            } else {
                $picFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
            }
            $file = "resources/" . $picFilePath . $file;
        }
        if ($_GET['isnew'] == 1) {
            return $this->renderPartial('lookpicture', [
                        'classID' => $classID,
                        'progress' => $progress,
                        'on' => $on,
                        'file' => $file,
                        'new' => 1
            ]);
        }
        return $this->render('lookpicture', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'file' => $file,
                    'new' => 0
        ]);
    }

    public function actionlookExer() {
        return $this->render('lookExer');
    }

    public function actioncourseMng() {
        return $this->render('courseMng');
    }

    public function actionexerMng() {
        return $this->render('exerMng');
    }

    public function actionIndex() {
        //$user_model = new User;
        //$username_now=Yii::app()->user->name;
        //$info=$user_model->find("username='$username_now'");//,'pageInden'=>$pageIndex
//        if (isset($_GET['modify'])) {
//            TwoWordsLib::model()->modify();
//        }
//        判断重复登录
//        $userID = Yii::app()->session['userid_now'];
//        Teacher::model()->isLogin($userID, 1);
        
        
        
        
        $this->render('index'); //,['info'=>$info]);
    }

    public function actionHello() {
        return $this->render('hello', array(null));
    }

    public function actionStartCourse() {
        $classID = $_GET['classID'];
        $result = '1';
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $lesson = Lesson::model()->find("classID = '$classID' and number = '$on'");
        $lessonID = $lesson['lessonID'];
        $freePractice = ClassExercise::model()->findAll("classID = '$classID' and lessonID = '$lessonID'");
        $keywork = array();
        $look = array();
        $listen = array();
        foreach ($freePractice as $v) {
            if ($v['type'] == 'speed' || $v['type'] == 'correct' || $v['type'] == 'free') {
                array_push($keywork, $v);
            } else if ($v['type'] === 'look') {
                array_push($look, $v);
            } else if ($v['type'] === 'listen') {
                array_push($listen, $v);
            }
        }
        //get student
        $stu = Array();
        $stu = Student::model()->findAll("classID=? and is_delete=?", array($classID, 0));
        return $this->render('startCourse', [
                    'classID' => $classID,
                    'keywork' => $keywork,
                    'look' => $look,
                    'listen' => $listen,
                    'progress' => $progress,
                    'on' => $on,
                    'stu' => $stu,
                    'result' => $result
        ]);
    }

    public function teaInClass() {
        $sql = "SELECT * FROM teacher order by userID ASC";
        $an = Yii::app()->db->createCommand($sql)->query();
        return $an;
    }

    public function teaByClass() {
        $sql = "SELECT * FROM teacher_class order by classID ASC";
        $an = Yii::app()->db->createCommand($sql)->query();
        return $an;
    }

    public function actionLookLst() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        $result = LookType::model()->getLookLst("", "");
        $lookLst = $result['lookLst'];
        $pages = $result['pages'];
        Yii::app()->session['lastUrl'] = "lookLst";
        $this->render('lookLst', array(
            'lookLst' => $lookLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionAddLook() {
        $result = 'no';
        if (isset($_POST['title'])) {
            $newContent = Tool::SBC_DBC($_POST['content'], 0);
            $content4000 = Tool::spliceLookContent($newContent);
            $contentNoSpace = Tool::filterAllSpaceAndTab($content4000);
            if(isset($_POST['checkbox'])){
                $title=$_POST['title']."-不提示略码";
                $result = LookType::model()->insertLook($title, $contentNoSpace, Yii::app()->session['userid_now']);
            }else{
                $result = LookType::model()->insertLook($_POST['title'], $contentNoSpace, Yii::app()->session['userid_now']);
            }
        }
        $this->render('addLook', ['result' => $result]);
    }

    public function actionEditLook() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("editLook", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'content' => $result['content']
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editLook", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'content' => $result['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditLookInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisLook = new LookType();
        $thisLook = $thisLook->find("exerciseID = '$exerciseID'");
        $newContent = Tool::SBC_DBC($_POST['content'], 0);
        $content4000 = Tool::spliceLookContent($newContent);
        if(isset($_POST['checkbox'])){
            if(strpos($_POST['title'],"-不提示略码")){
                $title=$_POST['title'];
                $thisLook->title = $title;
            }else{
                $title=$_POST['title']."-不提示略码";
                $thisLook->title = $title;
            }
        }else{
            $title=str_replace("-不提示略码","",$_POST['title']);
            $thisLook->title = $title;
        }
        $thisLook->content = $content4000;
        $thisLook->update();
        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditLook", array(
                'type' => "look",
                'exerciseID' => $exerciseID,
                'title' => $thisLook->title,
                'content' => $thisLook->content,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editLook", array(
                'exerciseID' => $thisLook->exerciseID,
                'title' => $thisLook->title,
                'content' => $thisLook->content,
                'result' => "修改习题成功"
            ));
        }
    }

    public function actionteaInformation() {
        $ID = Yii::app()->session['userid_now'];
        $teacher = Teacher::model()->find("userID = '$ID'");
        return $this->render('teaInformation', array(
                    'id' => $teacher ['userID'],
                    'name' => $teacher ['userName'],
                    'department' => $teacher ['department'],
                    'school' => $teacher['school'],
                    'sex' => $teacher['sex'],
                    'age' => $teacher['age'],
                    'password' => $teacher['password'],
                    'mail_address' => $teacher['mail_address'],
                    'phone_number' => $teacher['phone_number']
        ));
    }

    public function actionReturnFromAddLook() {
        if (Yii::app()->session['lastUrl'] == "lookLst") {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result['lookLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "lookLst";
            $this->render('lookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else if (Yii::app()->session['lastUrl'] == "searchLook") {
            $type = Yii::app()->session['searchLookType'];
            $value = Yii::app()->session['searchLookValue'];
            Yii::app()->session['lastUrl'] = "searchLook";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = LookType::model()->getLookLst($type, $value);
            $lookLst = $result['lookLst'];
            $pages = $result["pages"];
            $this->render('searchLook', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "look";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "look";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    public function actionSearchLook() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchLookType'] = $type;
            Yii::app()->session['searchLookValue'] = $value;
        } else {
            $type = Yii::app()->session['searchLookType'];
            $value = Yii::app()->session['searchLookValue'];
        }
        Yii::app()->session['lastUrl'] = "searchLook";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        if ($type == "content" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }

        $result = LookType::model()->getLookLst($type, $value);
        $lookLst = $result['lookLst'];
        $pages = $result["pages"];
        $this->render('searchLook', array(
            'lookLst' => $lookLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
            'searchKey' => $searchKey
        ));
    }

    public function actionDeleteLook() {
        $exerciseID = $_GET['exerciseID'];
        $thisLook = new LookType();
        $examLst = Array();
        $examLst = Exam::model()->findAll();
        $flag = 0;
        $deleteResult = "";
        $tip = "";
        foreach ($examLst as $exam) {
            $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'look');
            if ($examExerLst != NULL) {
                foreach ($examExerLst as $examExer) {
                    if ($exerciseID == $examExer['exerciseID']) {
                        $flag = 1;
                        $tip = "此题目已经被占用!";
                        break;
                    }
                }
            }
        }
        if ($flag == 0) {
            $deleteResult = $thisLook->deleteAll("exerciseID = '$exerciseID'");
            $tip = "此题目删除成功!";
        }


        if (Yii::app()->session['lastUrl'] == "lookLst") {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result['lookLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "lookLst";
            $this->render('lookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        } else {
            $type = Yii::app()->session['searchLookType'];
            $value = Yii::app()->session['searchLookValue'];
            Yii::app()->session['lastUrl'] = "searchLook";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }

            $result = LookType::model()->getLookLst($type, $value);
            $lookLst = $result['lookLst'];
            $pages = $result["pages"];
            $this->render('searchLook', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey,
                'tip' => $tip
            ));
        }
    }

    public function actionCopyLook() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            if ($code != Yii::app()->session['code']) {
                $exerciseID = $_GET["exerciseID"];
                $thisLook = new LookType();
                $oldLook = $thisLook->findAll("exerciseID = '$exerciseID'");
                $insertresult = LookType::model()->insertLook($oldLook[0]['title'], $oldLook[0]['content'], Yii::app()->session['userid_now']);
                Yii::app()->session['code'] = $_GET["code"];
            }
        }

        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisLook = new LookType ();
                $oldLook = $thisLook->find("exerciseID = '$v'");
                $insertresult = LookType::model()->insertLook($oldLook['title'], $oldLook['content'], Yii::app()->session['userid_now']);
            }
        }
        if (Yii::app()->session['lastUrl'] == "searchLook") {
            $type = Yii::app()->session['searchLookType'];
            $value = Yii::app()->session['searchLookValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = LookType::model()->getLookLst($type, $value);
            $lookLst = $result['lookLst'];
            $pages = $result['pages'];
            $this->render('searchLook', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result['lookLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "LookLst";
            $this->render('LookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

    public function actionKeyLst() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        $result = KeyType::model()->getKeyLst("", "");
        $keyLst = $result['keyLst'];
        $pages = $result['pages'];
        Yii::app()->session['lastUrl'] = "keyLst";
        $this->render('keyLst', array(
            'keyLst' => $keyLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionAddKey() {
        $result = 'no';
        if (isset($_POST['title'])) {
            $libstr = $_POST['libstr'];
            $arr = explode("$$", $libstr);
            $sql = "";
            foreach ($arr as $a) {
                if (substr($a, 0, 1) != '-') {
                    if ($sql == "")
                        $sql = "select * from two_words_lib  where name LIKE ('" . $a . "')";
                    else
                        $sql = $sql . " union select * from two_words_lib  where name LIKE ('" . $a . "')";
                }
            }
            $order = "";
            if ($arr[count($arr) - 1] == "lib") {
                $order = "order by rand() limit " . $_POST['in1'];
            }
            if ($sql != "") {
                $res = Yii::app()->db->createCommand($sql)->query();
            }


            $sql1 = "";
            foreach ($arr as $a) {
                if (substr($a, 0, 1) == '-') {
                    if ($sql1 == "")
                        $sql1 = "select * from two_words_lib_personal  where name LIKE ('" . $a . "')";
                    else
                        $sql1 = $sql1 . " union select * from two_words_lib_personal  where name LIKE ('" . $a . "')";
                }
            }
            $order1 = "";
            if ($arr[count($arr) - 1] == "lib") {
                $order1 = "order by rand() limit " . $_POST['in1'];
            }
            if ($sql1 != "") {
                $res1 = Yii::app()->db->createCommand($sql1)->query();
            }

            $content = "";
            if (isset($res)) {
                foreach ($res as $record) {
                    if ($content != "")
                        $content = $content . "$$" . $record['yaweiCode'] . $record['words'];
                    else
                        $content = $record['yaweiCode'] . $record['words'];
                }
            }
            if (isset($res1)) {
                foreach ($res1 as $record) {
                    if ($content != "")
                        $content = $content . "$$" . $record['yaweiCode'] . $record['words'];
                    else
                        $content = $record['yaweiCode'] . $record['words'];
                }
            }
            if(isset($_POST['free'])) {
                $title = $_POST['title'].'-自由练习';
                $category = "free";
                 $result = KeyType::model()->insertKey($title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
            if(isset($_POST['speed'])) {
                $title = $_POST['title'].'-速度练习';
                $category = "speed";
                $result = KeyType::model()->insertKey($title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
            if(isset($_POST['correct'])) {
                $title = $_POST['title'].'-准确率练习';
                $category = "correct";
                 $result = KeyType::model()->insertKey($title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
//            $result = KeyType::model()->insertKey($_POST['title'], $content, Yii::app()->session['userid_now'], $_POST['category'], $_POST['speed'], $_POST['in3'], $libstr);
        }
        $this->render('addKey', ['result' => $result]);
    }

    public function actionEditKey() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();

        if (!isset($_GET['action'])) {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'key' => $result,
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'action' => 'look',
                'key' => $result,
            ));
        }
    }

    public function actionEditKeyInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisKey = new KeyType();
        $thisKey = $thisKey->find("exerciseID = '$exerciseID'");
        $libstr = $_POST['libstr'];
        $arr = explode("$$", $libstr);
        $condition = "";
        foreach ($arr as $a) {
            if ($condition == "")
                $condition = "'" . $a . "'";
            else
                $condition = $condition . "," . "'" . $a . "'";
        }
        $condition = " where name in (" . $condition . ")";
        $sql = "select * from two_words_lib";
        $order = "";
        if ($arr[count($arr) - 1] == "lib") {
            $order = "order by rand() limit " . $_POST['in1'];
        }
        $sql = $sql . $condition . $order;
        $res = Yii::app()->db->createCommand($sql)->query();
        $content = "";
        foreach ($res as $record) {
            if ($content != "")
                $content = $content . "$$" . $record['yaweiCode'] . $record['words'];
            else
                $content = $record['yaweiCode'] . $record['words'];
        }
        $thisKey->title = $_POST['title'];
        $thisKey->content = $content;
        $thisKey->category = $_POST['category'];
        $thisKey->speed = $_POST['speed'];
        $thisKey->update();



        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditKey", array(
                'type' => "key",
                'exerciseID' => $exerciseID,
                'key' => $thisKey,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editKey", array(
                'key' => $thisKey,
                'exerciseID' => $exerciseID,
                'result' => "修改习题成功"
            ));
        }
    }

    public function actionReturnFromAddKey() {
        if (Yii::app()->session['lastUrl'] == "keyLst") {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result['keyLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "keyLst";
            $this->render('keyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else if (Yii::app()->session['lastUrl'] == "searchKey") {
            $type = Yii::app()->session['searchKeyType'];
            $value = Yii::app()->session['searchKeyValue'];
            Yii::app()->session['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            $result = KeyType::model()->getKeyLst($type, $value);
            $keyLst = $result['keyLst'];
            $pages = $result["pages"];
            $this->render('searchKey', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "key";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "key";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    public function actionDeleteKey() {
        $exerciseID = $_GET['exerciseID'];
        $thisKey = new KeyType();
        $examLst = Array();
        $examLst = Exam::model()->findAll();
        $flag = 0;
        $deleteResult = "";
        $tip = "";
        foreach ($examLst as $exam) {
            $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'key');
            if ($examExerLst != NULL) {
                foreach ($examExerLst as $examExer) {
                    if ($exerciseID == $examExer['exerciseID']) {
                        $flag = 1;
                        $tip = "此题目已经被占用!";
                        break;
                    }
                }
            }
        }
        if ($flag == 0) {
            $deleteResult = $thisKey->deleteAll("exerciseID = '$exerciseID'");
            $tip = "此题目删除成功!";
        }


        if (Yii::app()->session['lastUrl'] == "KeyLst") {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result['keyLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "keyLst";
            $this->render('keyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        } else {
            $type = Yii::app()->session['searchKeyType'];
            $value = Yii::app()->session['searchKeyValue'];
            Yii::app()->session['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            $result = KeyType::model()->getKeyLst($type, $value);
            $keyLst = $result['keyLst'];
            $pages = $result["pages"];
            $this->render('KeyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        }
    }

    public function actionSearchKey() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchKeyType'] = $type;
            Yii::app()->session['searchKeyValue'] = $value;
        } else {
            $type = Yii::app()->session['searchKeyType'];
            $value = Yii::app()->session['searchKeyValue'];
        }
        Yii::app()->session['lastUrl'] = "searchKey";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        $result = KeyType::model()->getKeyLst($type, $value);
        $keyLst = $result['keyLst'];
        $pages = $result["pages"];
        $this->render('searchKey', array(
            'keyLst' => $keyLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
        ));
    }

    public function actionCopyKey() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            if (isset($_GET['exerciseID'])) {
                if ($code != Yii::app()->session['code']) {
                    $exerciseID = $_GET["exerciseID"];
                    $thisKey = new KeyType();
                    $oldKey = $thisKey->find("exerciseID = '$exerciseID'");
                    $insertresult = KeyType::model()->insertKey($oldKey['title'], $oldKey['content'], Yii::app()->session['userid_now'], $oldKey['category'], $oldKey['speed'], $oldKey['repeatNum'], $oldKey['chosen_lib']);
                    Yii::app()->session['code'] = $_GET["code"];
                }
            }
        }
        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisKey = new KeyType ();
                $oldKey = $thisKey->find("exerciseID = '$v'");
                $insertresult = KeyType::model()->insertKey($oldKey['title'], $oldKey['content'], Yii::app()->session['userid_now'], $oldKey['category'], $oldKey['speed'], $oldKey['repeatNum'], $oldKey['chosen_lib']);
            }
        }



        if (Yii::app()->session['lastUrl'] == "searchKey") {
            $type = Yii::app()->session['searchKeyType'];
            $value = Yii::app()->session['searchKeyValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            $result = KeyType::model()->getKeyLst($type, $value);
            $keyLst = $result['keyLst'];
            $pages = $result['pages'];
            $this->render('searchKey', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
                    )
            );
        }else {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result['keyLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "KeyLst";
            $this->render('KeyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

    public function actionListenLst() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        $result = ListenType::model()->getListenLst("", "");
        $listenLst = $result['listenLst'];
        $pages = $result['pages'];
        Yii::app()->session['lastUrl'] = "listenLst";
        $this->render('listenLst', array(
            'listenLst' => $listenLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionAddListen() {
        $result = 'no';
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $filePath = $typename . "/" . $userid . "/";
        $dir = "resources/" . $filePath;

        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $title = "";
        $content = "";
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $content = $_POST["content"];
            if ($_FILES ['file'] ['type'] != "audio/mpeg" &&
                    $_FILES ['file'] ['type'] != "audio/wav" &&
                    $_FILES ['file'] ['type'] != "audio/x-wav") {
                $result = '文件格式不正确，应为MP3或WAV格式';
            } else if ($_FILES['file']['error'] > 0) {
                $result = '文件上传失败';
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRela($newName, $oldName);
                $result = ListenType::model()->insertListen($_POST['title'], $_POST['content'], $newName, $filePath, Yii::app()->session['userid_now']);
                $result = '1';
            }
        }
        $this->render('addListen', array(
            'result' => $result,
            'title' => $title,
            'content' => $content
        ));
    }

    public function actionSearchListen() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchListenType'] = $type;
            Yii::app()->session['searchListenValue'] = $value;
        } else {
            $type = Yii::app()->session['searchListenType'];
            $value = Yii::app()->session['searchListenValue'];
        }
        Yii::app()->session['lastUrl'] = "searchListen";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        if ($type == "content" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = ListenType::model()->getListenLst($type, $value);
        $listenLst = $result['listenLst'];
        $pages = $result["pages"];
        $this->render('searchListen', array(
            'listenLst' => $listenLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
            'searchKey' => $searchKey
        ));
    }

    public function actionReturnFromAddListen() {
        if (Yii::app()->session['lastUrl'] == "listenLst") {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result['listenLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "listenLst";
            $this->render('listenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else if (Yii::app()->session['lastUrl'] == "searchListen") {
            $type = Yii::app()->session['searchListenType'];
            $value = Yii::app()->session['searchListenValue'];
            Yii::app()->session['lastUrl'] = "searchListen";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = ListenType::model()->getListenLst($type, $value);
            $listenLst = $result['listenLst'];
            $pages = $result["pages"];
            $this->render('searchListen', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "listen";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "listen";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    public function actionDeleteListen() {
        $exerciseID = $_GET['exerciseID'];
        $thisListen = new ListenType();
        $deleteListen = $thisListen->findAll("exerciseID = '$exerciseID'");
        $deleteResult = "";
        $tip = "";
        if ($deleteListen != NULL) {
            $examLst = Array();
            $examLst = Exam::model()->findAll();
            $flag = 0;
            foreach ($examLst as $exam) {
                $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'listen');
                if ($examExerLst != NULL) {
                    foreach ($examExerLst as $examExer) {
                        if ($exerciseID == $examExer['exerciseID']) {
                            $flag = 1;
                            $tip = "此题目已经被占用!";
                            break;
                        }
                    }
                }
            }
            if ($flag == 0) {
                $deleteResult = $thisListen->deleteAll("exerciseID = '$exerciseID'");
                $tip = "此题目删除成功!";
            }


            $filePath = $deleteListen[0]['filePath'];
            $fileName = $deleteListen[0]['fileName'];
            if ($deleteResult == '1') {
                $typename = Yii::app()->session['role_now'];
                $userid = Yii::app()->session['userid_now'];
                //怎么用EXER_LISTEN_URL
                $path = 'resources/' . $filePath . iconv("UTF-8", "gb2312", $fileName);
                if (file_exists($path))
                    unlink($path);
            }
        }

        if (Yii::app()->session['lastUrl'] == "listenLst") {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result['listenLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "listenLst";
            $this->render('listenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        } else {
            $type = Yii::app()->session['searchListenType'];
            $value = Yii::app()->session['searchListenValue'];
            Yii::app()->session['lastUrl'] = "searchListen";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = ListenType::model()->getListenLst($type, $value);
            $listenLst = $result['listenLst'];
            $pages = $result["pages"];
            $this->render('searchListen', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey,
                'tip' => $tip
            ));
        }
    }

    public function actionEditlisten() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("editListen", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'filename' => $result['fileName'],
                'filepath' => $result['filePath'],
                'content' => $result['content']
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editListen", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'filename' => $result['fileName'],
                'filepath' => $result['filePath'],
                'content' => $result['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditListenInfo() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $filePath = $typename . "/" . $userid . "/";
        $dir = "resources/" . $filePath;
        $exerciseID = $_GET['exerciseID'];
        $thisListen = new ListenType ();
        $thisListen = $thisListen->find("exerciseID = '$exerciseID'");
        $filename = $_GET['oldfilename'];
        if ($_FILES['modifyfile']['tmp_name']) {
            if ($_FILES ['modifyfile'] ['type'] != "audio/mpeg" &&
                    $_FILES ['modifyfile'] ['type'] != "audio/wav" &&
                    $_FILES ['modifyfile'] ['type'] != "audio/x-wav") {
                $result = '文件格式不正确，应为MP3或WAV格式';
            } else if ($_FILES['modifyfile']['error'] > 0) {
                $result = '文件上传失败';
            } else {
                $newName = Tool::createID() . "." . pathinfo($_FILES["modifyfile"]["name"], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["modifyfile"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                if (file_exists($dir . iconv("UTF-8", "gb2312", $filename)))
                    unlink($dir . iconv("UTF-8", "gb2312", $filename));
                Resourse::model()->replaceRela($filename, $newName, $_FILES ["modifyfile"] ["name"]);
                $thisListen->fileName = $newName;
                $result = "上传成功";
            }
        }
        if (!isset($result) || $result == "上传成功") {
            $thisListen->title = $_POST ['title'];
            $thisListen->content = $_POST ['content'];
            $thisListen->update();
            $result = "修改成功!";
        } else {
            $result = "修改失败!";
        }
        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditListen", array(
                'type' => "listen",
                'exerciseID' => $exerciseID,
                'title' => $thisListen->title,
                'content' => $thisListen->content,
                'filename' => $thisListen->fileName,
                'filepath' => $thisListen->filePath,
                'result' => $result
            ));
        } else {
            $this->render("editListen", array(
                'exerciseID' => $thisListen->exerciseID,
                'filename' => $thisListen->fileName,
                'filepath' => $thisListen->filePath,
                'title' => $thisListen->title,
                'content' => $thisListen->content,
                'result' => $result
            ));
        }
    }

    public function actionCopyListen() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            $typename = Yii::app()->session['role_now'];
            $userid = Yii::app()->session['userid_now'];
            $filePath = $typename . "/" . $userid . "/";
            $dir = "resources/" . $filePath;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }

            if ($code != Yii::app()->session['code']) {
                $exerciseID = $_GET["exerciseID"];
                $thisListen = new ListenType();
                $oldListen = $thisListen->findAll("exerciseID = '$exerciseID'");
                $sourcefilePath = "resources/" . $oldListen[0]['filePath'];
                $fileName = $oldListen[0]['fileName'];

                if (file_exists($dir . iconv("UTF-8", "gb2312", $fileName))) {
                    //表示复制的文件已存在
                    $insertresult = '2';
                } else {
                    $newName = Tool::createID() . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    if (file_exists($sourcefilePath . iconv("UTF-8", "gb2312", $fileName)))
                        copy($sourcefilePath . iconv("UTF-8", "gb2312", $fileName), $dir . iconv("UTF-8", "gb2312", $newName));
                    $insertresult = ListenType::model()->insertListen($oldListen[0]['title'], $oldListen[0]['content'], $newName, $filePath, Yii::app()->session['userid_now']);
                    //$oldName = $_FILES["file"]["name"];
                    Resourse::model()->insertRela($newName, $fileName);
                }
                Yii::app()->session['code'] = $_GET["code"];
            }
        }
        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            $typename = Yii::app()->session['role_now'];
            $userid = Yii::app()->session['userid_now'];
            $filePath = $typename . "/" . $userid . "/";
            $dir = "resources/" . $filePath;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            foreach ($exerciseIDlist as $v) {
                $thisListen = new ListenType ();
                $oldListen = $thisListen->find("exerciseID = '$v'");
                $sourcefilePath = "resources/" . $oldListen['filePath'];
                $fileName = $oldListen['fileName'];

//                if (file_exists($dir . iconv("UTF-8", "gb2312", $fileName))) {
//                    //表示复制的文件已存在
//                    $insertresult = '2';
//                } else {
                    $newName = Tool::createID() . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    if (file_exists($sourcefilePath . iconv("UTF-8", "gb2312", $fileName)))
                        copy($sourcefilePath . iconv("UTF-8", "gb2312", $fileName), $dir . iconv("UTF-8", "gb2312", $newName));
                    $insertresult = ListenType::model()->insertListen($oldListen['title'], $oldListen['content'], $newName, $filePath, Yii::app()->session['userid_now']);
//                }
            }
        }


        if (Yii::app()->session['lastUrl'] == "searchListen") {
            $type = Yii::app()->session['searchListenType'];
            $value = Yii::app()->session['searchListenValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "content") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = ListenType::model()->getListenLst($type, $value);
            $listenLst = $result['listenLst'];
            $pages = $result['pages'];
            $this->render('searchListen', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result['listenLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "listenLst";
            $this->render('ListenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

    public function actionFillLst() {
        $result = Filling::model()->getFillLst("", "");
        $fillLst = $result['fillLst'];
        $pages = $result['pages'];
        Yii::app()->session['lastUrl'] = "fillLst";
        $this->render('fillLst', array(
            'fillLst' => $fillLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    //宋杰 2015-7-30 查找老师的填空题
    public function actionSearchFill() {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchFillType'] = $type;
            Yii::app()->session['searchFillValue'] = $value;
        } else {
            $type = Yii::app()->session['searchFillType'];
            $value = Yii::app()->session['searchFillValue'];
        }
        Yii::app()->session['lastUrl'] = "searchFill";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = Filling::model()->getFillLst($type, $value);
        $fillLst = $result['fillLst'];
        $pages = $result['pages'];
        $this->render('searchFill', array(
            'fillLst' => $fillLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'searchKey' => $searchKey
                )
        );
    }

    public function actionReturnFromAddFill() {
        if (Yii::app()->session['lastUrl'] == "searchFill") {
            $type = Yii::app()->session['searchFillType'];
            $value = Yii::app()->session['searchFillValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Filling::model()->getFillLst($type, $value);
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            $this->render('searchFill', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
                    )
            );
        } else if (Yii::app()->session['lastUrl'] == "fillLst") {
            $result = Filling::model()->getFillLst("", "");
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "fillLst";
            $this->render('fillLst', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "filling";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "filling";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    public function actionAddFill() {
        $result = 'no';
        if (isset($_POST['requirements'])) {
            $i = 2;
            $answer = $_POST['in1'];
            for (; $i <= 5; $i++) {
                if ($_POST['in' . $i] != "")
                    $answer = $answer . "$$" . $_POST['in' . $i];
                else
                    break;
            }
            $result = Filling::model()->insertFill($_POST['requirements'], $answer, Yii::app()->session['userid_now']);
        }
        $this->render('addFill', ['result' => $result]);
    }

    public function actionDeleteFill() {
        $exerciseID = $_GET["exerciseID"];
        $thisFill = new Filling();
        $examLst = Array();
        $examLst = Exam::model()->findAll();
        $flag = 0;
        $deleteResult = "";
        $tip = "";
        foreach ($examLst as $exam) {
            $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'filling');
            if ($examExerLst != NULL) {
                foreach ($examExerLst as $examExer) {
                    if ($exerciseID == $examExer['exerciseID']) {
                        $flag = 1;
                        $tip = "此题目已经被占用!";
                        break;
                    }
                }
            }
        }
        if ($flag == 0) {
            $deleteResult = $thisFill->deleteAll("exerciseID = '$exerciseID'");
            $tip = "此题目删除成功!";
        }


        if (Yii::app()->session['lastUrl'] == "searchFill") {
            $type = Yii::app()->session['searchFillType'];
            $value = Yii::app()->session['searchFillValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }

            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }

            $result = Filling::model()->getFillLst($type, $value);
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            $this->render('searchFill', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey,
                'tip' => $tip
                    )
            );
        } else {
            $result = Filling::model()->getFillLst("", "");
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "FillLst";
            $this->render('fillLst', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        }
    }

    public function actionCopyFill() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            if ($code != Yii::app()->session['code']) {
                $exerciseID = $_GET["exerciseID"];
                $thisFill = new Filling();
                $oldFill = $thisFill->findAll("exerciseID = '$exerciseID'");
                $insertresult = Filling::model()->insertFill($oldFill[0]['requirements'], $oldFill[0]['answer'], Yii::app()->session['userid_now']);
                Yii::app()->session['code'] = $_GET["code"];
            }
        }

        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisFill = new Filling ();
                $oldFill = $thisFill->find("exerciseID = '$v'");
                $insertresult = Filling::model()->insertFill($oldFill['requirements'], $oldFill['answer'], Yii::app()->session['userid_now']);
            }
        }


        if (Yii::app()->session['lastUrl'] == "searchFill") {
            $type = Yii::app()->session['searchFillType'];
            $value = Yii::app()->session['searchFillValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Filling::model()->getFillLst($type, $value);
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            $this->render('searchFill', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Filling::model()->getFillLst("", "");
            $fillLst = $result['fillLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "FillLst";
            $this->render('fillLst', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

    //宋杰 2015-7-30 选择题列表
    public function actionChoiceLst() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        $teachr_id = Yii::app()->session['userid_now'];
        $result = Choice::model()->getChoiceLst("", "");
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
        Yii::app()->session['lastUrl'] = "choiceLst";
        $this->render('choiceLst', array(
            'choiceLst' => $choiceLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    //宋杰 2015-7-30 点击查看/编辑按钮
    public function actionEditChoice() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("editChoice", array(
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'options' => $result['options'],
                'answer' => $result['answer']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("editChoice", array(
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'options' => $result['options'],
                'answer' => $result['answer'],
                'action' => 'look'
            ));
        }
    }

    //宋杰 2015-7-30 编辑选择题信息
    public function actionEditChoiceInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisCh = new Choice();
        $thisCh = $thisCh->find("exerciseID = '$exerciseID'");
        $thisCh->requirements = $_POST['requirements'];
        $thisCh->options = $_POST['A'] . "$$" . $_POST['B'] . "$$" . $_POST['C'] . "$$" . $_POST['D'];
        $thisCh->answer = $_POST['answer'];
        $thisCh->update();
        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditChoice", array(
                'type' => "choice",
                'exerciseID' => $exerciseID,
                'requirements' => $thisCh->requirements,
                'options' => $thisCh->options,
                'answer' => $thisCh->answer,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editChoice", array(
                'exerciseID' => $exerciseID,
                'requirements' => $thisCh->requirements,
                'options' => $thisCh->options,
                'answer' => $thisCh->answer,
                'result' => "修改习题成功"
            ));
        }
    }

    //宋杰 2015-7-30 添加/编辑选择题的返回
    public function actionReturnFromAddChoice() {
        if (Yii::app()->session['lastUrl'] == "searchChoice") {
            $type = Yii::app()->session['searchChoiceType'];
            $value = Yii::app()->session['searchChoiceValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            $this->render('searchChoice', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
                    )
            );
        } else if (Yii::app()->session['lastUrl'] == "choiceLst") {
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "choiceLst";
            $this->render('choiceLst', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "choice";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "choice";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    //宋杰 2015-7-30 选择题查找按钮
    public function actionSearchChoice() {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchChoiceType'] = $type;
            Yii::app()->session['searchChoiceValue'] = $value;
        } else {
            $type = Yii::app()->session['searchChoiceType'];
            $value = Yii::app()->session['searchChoiceValue'];
        }
        Yii::app()->session['lastUrl'] = "searchChoice";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }

        $result = Choice::model()->getChoiceLst($type, $value);
        $choiceLst = $result['choiceLst'];
        $pages = $result['pages'];
        $this->render('searchChoice', array(
            'choiceLst' => $choiceLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'searchKey' => $searchKey
                )
        );
    }

//宋杰 2015-7-30 老师添加选择题
    public function actionAddChoice() {
        $result = 'no';
        if (isset($_POST['requirements'])) {
            $result = Choice::model()->insertChoice($_POST['requirements'], $_POST['A'] . "$$" . $_POST['B'] . "$$" . $_POST['C'] . "$$" . $_POST['D'], $_POST['answer'], Yii::app()->session['userid_now']);
        }
        $this->render('addChoice', ['result' => $result]);
    }

    public function actionEditFill() {
        $exerciseID = $_GET["exerciseID"];
        $thisFill = new Filling();
        $thisFill = $thisFill->find("exerciseID = '$exerciseID'");
        if (!isset($_GET['action'])) {
            $this->render("editFill", array(
                'exerciseID' => $exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editFill", array(
                'exerciseID' => $exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer,
                'action' => 'look'
            ));
        }
    }

    public function actionEditFillInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisFill = new Filling();
        $thisFill = $thisFill->find("exerciseID = '$exerciseID'");
        $i = 2;
        $answer = $_POST['in1'];
        for (; $i <= 5; $i++) {
            if ($_POST['in' . $i] != "")
                $answer = $answer . "$$" . $_POST['in' . $i];
            else
                break;
        }
        $thisFill->requirements = $_POST['requirements'];
        $thisFill->answer = $answer;
        $thisFill->update();
        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditFilling", array(
                'type' => "choice",
                'exerciseID' => $exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editFill", array(
                'exerciseID' => $thisFill->exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer,
                'result' => "修改习题成功"
            ));
        }
    }

    public function actionDeleteChoice() {
        $exerciseID = $_GET["exerciseID"];
        $thisChoice = new Choice();
        $examLst = Array();
        $examLst = Exam::model()->findAll();
        $flag = 0;
        $deleteResult = "";
        $tip = "";
        foreach ($examLst as $exam) {
            $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'choice');
            if ($examExerLst != NULL) {
                foreach ($examExerLst as $examExer) {
                    if ($exerciseID == $examExer['exerciseID']) {
                        $flag = 1;
                        $tip = "此题目已经被占用!";
                        break;
                    }
                }
            }
        }
        if ($flag == 0) {
            $deleteResult = $thisChoice->deleteAll("exerciseID = '$exerciseID'");
            $tip = "此题目删除成功!";
        }
        if (Yii::app()->session['lastUrl'] == "searchChoice") {
            $type = Yii::app()->session['searchChoiceType'];
            $value = Yii::app()->session['searchChoiceValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            $this->render('searchChoice', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "choiceLst";
            $this->render('choiceLst', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip,
            ));
        }
    }

    public function actionCopyChoice() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            if ($code != Yii::app()->session['code']) {
                $exerciseID = $_GET["exerciseID"];
                $thisChoice = new Choice();
                $oldChoice = $thisChoice->findAll("exerciseID = '$exerciseID'");
                $insertresult = Choice::model()->insertChoice($oldChoice[0]['requirements'], $oldChoice[0]['options'], $oldChoice[0]['answer'], Yii::app()->session['userid_now']);
                Yii::app()->session['code'] = $_GET["code"];
            }
        }
        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisChoice = new Choice ();
                $oldChoice = $thisChoice->find("exerciseID = '$v'");
                $insertresult = Choice::model()->insertChoice($oldChoice['requirements'], $oldChoice['options'], $oldChoice['answer'], Yii::app()->session['userid_now']);
            }
        }


        if (Yii::app()->session['lastUrl'] == "searchChoice") {        //search choice
            $type = Yii::app()->session['searchChoiceType'];
            $value = Yii::app()->session['searchChoiceValue'];
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            $this->render('searchChoice', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst = $result['choiceLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "choiceLst";
            $this->render('choiceLst', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

//宋杰 2015-7-30 简答题列表
    public function actionQuestionLst() {
        Yii::app()->session['lastUrl'] = "questionLst";
        $result = Question::model()->getQuestionLst("", "");
        $questionLst = $result['questionLst'];
        $pages = $result["pages"];
        $this->render('questionLst', array(
            'questionLst' => $questionLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionEditQuestionInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisQue = new Question();
        $thisQue = $thisQue->find("exerciseID = '$exerciseID'");
        $thisQue->requirements = $_POST['requirements'];
        $thisQue->answer = $_POST['answer'];
        $thisQue->update();
        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditQuestion", array(
                'type' => "choice",
                'exerciseID' => $exerciseID,
                'requirements' => $thisQue->requirements,
                'answer' => $thisQue->answer,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editQuestion", array(
                'exerciseID' => $thisQue->exerciseID,
                'requirements' => $thisQue->requirements,
                'answer' => $thisQue->answer,
                'result' => "修改习题成功"
            ));
        }
    }

    //宋杰 2015-7-30 编辑/修改问题
    public function actionEditQuestion() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("editQuestion", array(
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("editQuestion", array(
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer'],
                'action' => 'look'
            ));
        }
    }

    //查找简单题
    public function actionSearchQuestion() {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            $value = $_POST['value'];
            Yii::app()->session['searchQuestionType'] = $type;
            Yii::app()->session['searchQuestionValue'] = $value;
        } else {
            $type = Yii::app()->session['searchQuestionType'];
            $value = Yii::app()->session['searchQuestionValue'];
        }
        Yii::app()->session['lastUrl'] = "searchQuestion";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea['userID'] != "")
                    $value = $tea['userID'];
                else
                    $value = -1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }

        $result = Question::model()->getQuestionLst($type, $value);
        $questionLst = $result['questionLst'];
        $pages = $result["pages"];
        $this->render('searchQuestion', array(
            'questionLst' => $questionLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'searchKey' => $searchKey
        ));
    }

    public function actionAddQuestion() {
        $result = 'no';
        if (isset($_POST['requirements'])) {
            $result = Question::model()->insertQue($_POST['requirements'], $_POST['answer'], Yii::app()->session['userid_now']);
        }
        $this->render('addQuestion', ['result' => $result]);
    }

    public function actionReturnFromAddQuestion() {
        if (Yii::app()->session['lastUrl'] == "searchQuestion") {
            $type = Yii::app()->session['searchQuestionType'];
            $value = Yii::app()->session['searchQuestionValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Question::model()->getQuestionLst($type, $value);
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            $this->render('searchQuestion', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
                    )
            );
        } else if (Yii::app()->session['lastUrl'] == "questionLst") {
            $result = Question::model()->getQuestionLst("", "");
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "QuestionLst";
            $this->render('QuestionLst', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else if (Yii::app()->session['lastUrl'] == "modifyExam") {
            $type = "question";
            $examID = Yii::app()->session['examID'];
            $this->renderModifyExam($type, $examID, "");
        } else {
            $type = "question";
            $suiteID = Yii::app()->session['suiteID'];
            $this->renderModify($type, $suiteID, "");
        }
    }

    public function actionDeleteQuestion() {
        $exerciseID = $_GET["exerciseID"];
        $thisQuestion = new Question();
        $examLst = Array();
        $examLst = Exam::model()->findAll();
        $flag = 0;
        $deleteResult = "";
        $tip = "";
        foreach ($examLst as $exam) {
            $examExerLst = ExamExercise::model()->getExamExerByType($exam['examID'], 'question');
            if ($examExerLst != NULL) {
                foreach ($examExerLst as $examExer) {
                    if ($exerciseID == $examExer['exerciseID']) {
                        $flag = 1;
                        $tip = "此题目已经被占用!";
                        break;
                    }
                }
            }
        }
        if ($flag == 0) {
            $deleteResult = $thisQuestion->deleteAll("exerciseID = '$exerciseID'");
            $tip = "此题目删除成功!";
        }


        if (Yii::app()->session['lastUrl'] == "searchQuestion") {
            $type = Yii::app()->session['searchQuestionType'];
            $value = Yii::app()->session['searchQuestionValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }
            $result = Question::model()->getQuestionLst($type, $value);
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            $this->render('searchQuestion', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey,
                'tip' => $tip
                    )
            );
        } else {
            $result = Question::model()->getQuestionLst("", "");
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "QuestionLst";
            $this->render('QuestionLst', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult,
                'tip' => $tip
            ));
        }
    }

    public function actionCopyQuestion() {
        $insertresult = "no";
        if (isset($_GET['exerciseID'])) {
            $code = $_GET["code"];
            if ($code != Yii::app()->session['code']) {
                $exerciseID = $_GET["exerciseID"];
                $thisQuestion = new Question();
                $oldQuestion = $thisQuestion->findAll("exerciseID = '$exerciseID'");
                $insertresult = Question::model()->insertQue($oldQuestion[0]['requirements'], $oldQuestion[0]['answer'], Yii::app()->session['userid_now']);
                Yii::app()->session['code'] = $_GET["code"];
            }
        }

        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisQuestion = new Question ();
                $oldQuestion = $thisQuestion->find("exerciseID = '$v'");
                $insertresult = Question::model()->insertQue($oldQuestion['requirements'], $oldQuestion['answer'], Yii::app()->session['userid_now']);
            }
        }


        if (Yii::app()->session['lastUrl'] == "searchQuestion") {
            $type = Yii::app()->session['searchQuestionType'];
            $value = Yii::app()->session['searchQuestionValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea['userID'] != "")
                        $value = $tea['userID'];
                    else
                        $value = -1;
                }
            }
            if ($type == "requirements") {
                $searchKey = $value;
            } else {
                $searchKey = "no";
            }

            $result = Question::model()->getQuestionLst($type, $value);
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            $this->render('searchQuestion', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult,
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Question::model()->getQuestionLst("", "");
            $questionLst = $result['questionLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "QuestionLst";
            $this->render('QuestionLst', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'result' => $insertresult
            ));
        }
    }

    public function actionChoice() {           //see choice
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $isExam = FALSE;
        return $this->render('choiceExer', ['exercise' => $classwork]);
    }

    public function actionfilling() {               //see filling
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $isExam = false;
        return $this->render('fillingExer', ['exercise' => $classwork]);
    }

    public function actionQuestion() {        //see question
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $isExam = FALSE;
        return $this->render('questionExer', ['exercise' => $classwork]);
    }

    public function actionKeyType() {           //see keyType
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $result = KeyType::model()->findByPK($exerID);
        return $this->render('keyExer', array('exercise' => $classwork, 'exerOne' => $result,));
    }

    public function actionlookType() {
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'look';
        $result = LookType::model()->findByPK($exerID);
        return $this->render('lookExer', array('exercise' => $classwork, 'exerOne' => $result));
    }

    public function actionlistenType() {
        $suiteID = Yii::app()->session['suiteID'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerType'] = 'listen';
        $result = ListenType::model()->findByPK($exerID);
        return $this->render('listenExer', array(
                    'exercise' => $classwork,
                    'exerOne' => $result));
    }

    public function ActionSeeWork() {           //see work
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['suiteID'] = $suiteID;
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        //return $this->render('seeWork',['exercise'=>$classwork ]);
        return $this->render('choiceExer', ['exercise' => $classwork]);
    }

    public function ActionAssignWork() {
        $res = 0;
        $teacherID = Yii::app()->session['userid_now'];
        if(isset($_GET['on'])){
        Yii::app()->session['on']=$_GET['on'];
        }else{
            Yii::app()->session['on']=1;
        }
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        $array_lesson = array();
        $array_class = array();
        $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
        $array_allsuite = $result['suiteLst'];


        $pages = $result['pages'];
        if (!empty($teacher_class)) {
            if (isset($_GET['classID']))
                Yii::app()->session['currentClass'] = $_GET['classID'];
            else
                Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];
            foreach ($teacher_class as $class) {
                $id = $class['classID'];
                $result = TbClass::model()->find("classID ='$id'");
                array_push($array_class, $result);
            }
            $currentClass = Yii::app()->session['currentClass'];
            $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            if (!empty($array_lesson)) {
                if (isset($_GET['lessonID']))
                    Yii::app()->session['currentLesson'] = $_GET['lessonID'];
                else
                if (isset($_GET['progress'])) {
                    $number = $_GET['progress'];
                    $currentLesson = Lesson::model()->find("classID='$currentClass' and number='$number'");
                    Yii::app()->session['currentLesson'] = $currentLesson['lessonID'];
                } else if (isset($_GET['on'])) {
                    $number = $_GET['on'];
                    Yii::app()->session['currentLesson'] = Lesson::model()->find("classID='$currentClass' and number='$number'")['lessonID'];
                } else {
                    Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
                }
            }
            if (isset(Yii::app()->session['currentClass']) && isset(Yii::app()->session['currentLesson'])) {
                $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=? and open=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson'], 1));
            } else {
                $array_suite = 0;
            }
            if (isset(Yii::app()->session['currentClass'])){
                $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));                
            }else{
                $arrayall_suite=0;                
            }
            $this->render('assignWork', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'array_suite' => $array_suite,
                'array_allsuite' => $array_allsuite,
                'arrayall_suite' => $arrayall_suite,
                'pages' => $pages,
                'res' => $res
            ));
        } else {
            $this->render('index', array(
                'array_class' => $array_class
            ));
        }
    }

    public function ActionAssignExam() {
        $flag = 0;
        $res = 0;
        $teacherID = Yii::app()->session['userid_now'];
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        $array_class = array();
        $result = TbClass::model()->getClassByTeacherID($teacherID);
        foreach ($result as $class)
            array_push($array_class, $class);

        $result = Exam::model()->getAllExamByPage(10);
        $array_allexam = $result['examLst'];
        $pages = $result['pages'];
        //得到当前显示班级
        if (empty($teacher_class)) {
            $this->render('index', array(
                'array_class' => $array_class));
        } else {
            if (isset($_GET['classID']))
                Yii::app()->session['currentClass'] = $_GET['classID'];
            else if ($array_class != NULL)
                Yii::app()->session['currentClass'] = $array_class[0]['classID'];
            else
                Yii::app()->session['currentClass'] = 0;

            $currentClass = Yii::app()->session['currentClass'];

            $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

            $this->render('assignExam', array(
                'flag' => $flag,
                'array_class' => $array_class,
                'array_exam' => $array_suite,
                'array_allexam' => $array_allexam,
                'pages' => $pages,
                'res' => $res
            ));
        }
    }

    public function ActionToOwnTypeExercise() {
        $on = $_GET['on'];
        $type = $_GET['type'];
        $classID = $_GET['classID'];
        $result = ClassExercise::model()->getExerciseExerByTypePage($classID, $on, $type, 5);
        $workChoice = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('toOwnTypeExercise', array(
            'suiteWork' => $workChoice,
            'pages' => $pages,
            'type' => $type,
        ));
    }

    public function ActionModifyClassExercise() {
        $onLesson = $_GET['on'];
        $type = $_GET['type'];
        $classID = $_GET['classID'];
        if (isset($_GET['delete'])) {
            $exerciseID = $_GET['exerciseID'];
            ClassExercise::model()->deleteExercise($exerciseID);
        }
        $this->render('modifyClassExercise', array('classID' => $classID, 'on' => $onLesson, 'type' => $type));
    }

    public function ActionAddExercise() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $on = $_GET['on'];
        $suiteID = $_GET['suiteID'];
        $classID = $_GET['classID'];
        $code = $_GET['code'];
        $result = "";
        $maniResult = "";
        if ($code != Yii::app()->session['code']) {
            $result = ClassExercise::model()->insertFromWork($exerciseID, $type, $classID, $on);
            Yii::app()->session['code'] = $code;
        }
        $suite = Suite::model()->find("suiteID = '$suiteID'");
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $render = "allTypeWork";
        $this->renderPartial($render, array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => $maniResult,
        ));
    }

    public function ActionModifyWork() {
        $res = 0;
        $suiteID = $_GET['suiteID'];
        Yii::app()->session['suiteID'] = $suiteID;
        Yii::app()->session['lastUrl'] = "modifyWork";
        $type = $_GET['type'];
        $this->renderModify($type, $suiteID, $res);
    }

    public function ActionModifyExam() {
        $examID = $_GET['examID'];
        Yii::app()->session['examID'] = $examID;
        Yii::app()->session['lastUrl'] = "modifyExam";
        $type = $_GET['type'];
        $this->renderModifyExam($type, $examID);
    }

    public function ActionUpdateTime() {      //更新时间
        $type = $_GET['type'];
        $examID = $_GET['examID'];
        /*
          $startTime=$_POST['startTime'];
          $endTime=$_POST['endTime'];
          $examTime=$_POST['examTime'];


          $date=floor((strtotime($endTime)-strtotime($startTime))/86400);
          $hour=floor((strtotime($endTime)-strtotime($startTime))%86400/3600);
          $minute=floor((strtotime($endTime)-strtotime($startTime))%86400/60);
          $second=floor((strtotime($endTime)-strtotime($startTime))%86400%60);
          if($second>=60){
          $minute+=($second/60);
          $second=$second%60;
          }

          if($minute>=60){
          $hour=$hour+(int)($hour/60);
          $minute=$minute%60;
          }
          if($hour>=24){
          $date+=($date/24);
          $hour=$hour%24;
          }
          $duration=(strtotime($endTime)-strtotime($startTime))/60;
          $duration=$examTime;
          Exam::model()->updateByPk($examID,array('begintime'=>$startTime,'endtime'=>$endTime,'duration'=>$duration));
         * 
         */
        $this->renderModifyExam($type, $examID);
    }

    public function ActionDeleteSuiteExercise() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $suiteID = $_GET['suiteID'];
        $thisSuiteExercise = new SuiteExercise();
        $deleteResult = $thisSuiteExercise->deleteAll("exerciseID = '$exerciseID' and type = '$type' and suiteID = '$suiteID'");
        if ($type == "key" || $type == "look" || $type == "listen") {
            $this->ActionToOwnTypeWork();
        } else {
            $this->ActionToOwnWork();
        }
    }

    public function ActionDeleteExamExercise() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $examID = $_GET['examID'];
        $thisExamExercise = new ExamExercise();
        $deleteResult = $thisExamExercise->deleteAll("exerciseID = '$exerciseID' and type = '$type' and examID = '$examID'");
        if ($type == "key" || $type == "look" || $type == "listen") {
            $this->ActionToOwnTypeExam();
        } else {
            $this->ActionToOwnExam();
        }
    }

    public function ActionAddwork() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $suiteID = $_GET['suiteID'];
        $code = $_GET['code'];
        $result = "";
        if ($code != Yii::app()->session['code']) {
            $thisSuiteExercise = new SuiteExercise();
            $result = SuiteExercise::model()->insertWork($type, $exerciseID, $suiteID);
            Yii::app()->session['code'] = $code;
        }

        if ($result === "HAVEN")
            $maniResult = "题目已存在";
        else
            $maniResult = "";
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        if ($type == "key" || $type == "look" || $type == "listen")
            $render = "allTypeWork";
        else
            $render = "allWork";
        $this->renderPartial($render, array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => $maniResult,
        ));
    }

    public function ActionAddExamExercise() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $examID = $_GET['examID'];
        $code = $_GET['code'];
        $result = "";
        if ($code != Yii::app()->session['code']) {
            $thisExamExercise = new ExamExercise();
            $result = ExamExercise::model()->insertExam($type, $exerciseID, $examID);
            Yii::app()->session['code'] = $code;
        }

        if ($result === "HAVEN")
            $maniResult = "题目已存在";
        else
            $maniResult = "";
        $exam = Exam::model()->find("examID = '$examID'");
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        if ($type == "key" || $type == "look" || $type == "listen")
            $render = "allTypeExam";
        else
            $render = "allExam";
        $this->renderPartial($render, array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'exam' => $exam,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => $maniResult,
        ));
    }

    public function ActionModifyEditWork($type) {
        switch ($type) {
            case 'choice':
                $this->ModifyEditChoice();
                break;
            case 'filling':
                $this->ModifyEditFilling();
                break;
            case 'question':
                $this->ModifyEditQuestion();

                break;
            case 'key':
                $this->ModifyEditKey();

                break;
            case 'listen':
                $this->ModifyEditListen();

                break;
            case 'look':
                $this->ModifyEditLook();
                break;
            default :
                break;
        }
    }

    public function ModifyEditChoice() {
        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditChoice", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'options' => $result['options'],
                'answer' => $result['answer']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditChoice", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'options' => $result['options'],
                'answer' => $result['answer'],
                'action' => 'look',
            ));
        }
    }

    public function ModifyEditFilling() {

        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM filling WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditFilling", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditFilling", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer'],
                'action' => 'look',
            ));
        }
    }

    public function ModifyEditQuestion() {

        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditQuestion", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditQuestion", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'requirements' => $result['requirements'],
                'answer' => $result['answer'],
                'action' => 'look',
            ));
        }
    }

    public function ModifyEditKey() {

        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditKey", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditKey", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title'],
                'action' => 'look',
            ));
        }
    }

    public function ModifyEditLook() {

        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditLook", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditLook", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title'],
                'action' => 'look',
            ));
        }
    }

    public function ModifyEditListen() {

        $type = $_GET['type'];
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET['action'])) {
            $this->render("ModifyEditListen", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title'],
                'filename' => $result['fileName'],
                'filepath' => $result['filePath']
            ));
        } else if ($_GET['action'] == 'look') {
            $this->render("ModifyEditListen", array(
                'type' => $type,
                'exerciseID' => $result['exerciseID'],
                'content' => $result['content'],
                'title' => $result['title'],
                'filename' => $result['fileName'],
                'filepath' => $result['filePath'],
                'action' => 'look',
            ));
        }
    }

    public function getLstByType($type) {
        switch ($type) {
            case 'choice':
                $result = Choice::model()->getChoiceLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['choiceLst'],
                    'pages' => $result['pages']
                );
                break;
            case 'filling':
                $result = Filling::model()->getFillLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['fillLst'],
                    'pages' => $result['pages']
                );
                break;
            case 'question':
                $result = Question::model()->getQuestionLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['questionLst'],
                    'pages' => $result['pages']
                );
                break;
            case 'key':
                $result = KeyType::model()->getKeyLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['keyLst'],
                    'pages' => $result['pages']
                );
                break;
            case 'listen':
                $result = ListenType::model()->getListenLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['listenLst'],
                    'pages' => $result['pages']
                );
                break;
            case 'look':
                $result = LookType::model()->getLookLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['lookLst'],
                    'pages' => $result['pages']
                );
                break;
            default :
                $result = Choice::model()->getChoiceLstPage("", "", 5);
                $myResult = array(
                    'workLst' => $result['choiceLst'],
                    'pages' => $result['pages']
                );
        }
        return $myResult;
    }

    public function renderModify($type, $suiteID, $res) {
        $currentLesson = Yii::app()->session['currentLesson'];
        $currentClass = Yii::app()->session['currentClass'];
        $suite = Array();
        $class = Array();
        $lesson = Array();
        if ($currentClass == null && $currentLesson == null && $suiteID == null) {
            $this->render($render, array(
                'suite' => $suite,
                'currentClass' => $class,
                'currentLesson' => $lesson,
                'teacher' => Teacher::model()->findall(),
                'type' => $type,
                'res' => $res));
            return;
        }
        $class = TbClass::model()->findAll("classID='$currentClass'")[0];
        $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];

        if ($type == "key" || $type == "look" || $type == "listen")
            $render = "modifyTypework";
        else
            $render = "modifywork";
        $this->render($render, array(
            'suite' => $suite,
            'currentClass' => $class,
            'currentLesson' => $lesson,
            'teacher' => Teacher::model()->findall(),
            'type' => $type,
            'res' => $res
        ));
    }

    public function renderModifyExam($type, $examID) {
        $exam = Exam::model()->find("examID = '$examID'");
        $totalScore = ExamExercise::model()->getTotalScore($examID);
        if ($type == "key" || $type == "look" || $type == "listen")
            $render = "modifyTypeExam";
        else
            $render = "modifyExam";

        $this->render($render, array(
            'exam' => $exam,
            'type' => $type,
            'examID' => $examID,
            'totalScore' => $totalScore
        ));
    }

    //得到修改作业左边框的信息
    public function getSuiteInfo($type, $suiteID) {
        $currentLesson = Yii::app()->session['currentLesson'];
        $currentClass = Yii::app()->session['currentClass'];
        $class = TbClass::model()->findAll("classID='$currentClass'")[0];
        $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        return array(
            'suite' => $suite,
            'lesson' => $lesson,
            'class' => $class
        );
    }

    public function ActionDeleteSuite() {
        $res = 0;
        if (isset($_GET['suiteID'])) {
            $suiteID = $_GET['suiteID'];
            $teacherID = Yii::app()->session['userid_now'];

            $workID = ClassLessonSuite::model()->find('suiteID=?', array($suiteID))['workID'];
            if ($workID) {
                $recordID = SuiteRecord::model()->find('workID=? and studentID=?', array($workID, $teacherID))['recordID'];
                SuiteRecord::model()->deleteAll("recordID='$recordID'");
            }
            Suite::model()->deleteAll("suiteID='$suiteID'");
            SuiteExercise::model()->deleteAll("suiteID='$suiteID'");
            ClassLessonSuite::model()->deleteAll("suiteID='$suiteID'");
            SuiteRecord::model()->deleteALL("workID='$workID'");
            $currentClass = Yii::app()->session['currentClass'];
            $currentLesson = Yii::app()->session['currentLesson'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_lesson = array();
            $array_class = array();
            $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
            $array_allsuite = $result['suiteLst'];
            $pages = $result['pages'];

            if (!empty($teacher_class)) {
                foreach ($teacher_class as $class) {
                    $id = $class['classID'];
                    $result = TbClass::model()->findAll("classID ='$id'");
                    array_push($array_class, $result[0]);
                }

                $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            }
            if (isset(Yii::app()->session['currentClass']) && isset(Yii::app()->session['currentLesson'])) {
                $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=? and open=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson'], 1));
            } else {
                $array_suite = 0;
            }
            if (isset(Yii::app()->session['currentClass'])){
                $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));                
            }else{
                $arrayall_suite=0;                
            }
            $this->render('assignWork', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'array_suite' => $array_suite,
                'array_allsuite' => $array_allsuite,
                'arrayall_suite' => $arrayall_suite,
                'pages' => $pages,
                'res' => $res
            ));
        }
        else if (isset($_POST['checkbox'])) {
            $suiteIDlist = $_POST['checkbox'];
            $teacherID = Yii::app()->session['userid_now'];
            foreach ($suiteIDlist as $suiteID) {
                $workID = ClassLessonSuite::model()->find('suiteID=?', array($suiteID))['workID'];
                if ($workID) {
                    $recordID = SuiteRecord::model()->find('workID=? and studentID=?', array($workID, $teacherID))['recordID'];
                    SuiteRecord::model()->deleteAll("recordID='$recordID'");
                }
                Suite::model()->deleteAll("suiteID='$suiteID'");
                SuiteExercise::model()->deleteAll("suiteID='$suiteID'");
                ClassLessonSuite::model()->deleteAll("suiteID='$suiteID'");
                SuiteRecord::model()->deleteALL("workID='$workID'");
            }
            $currentClass = Yii::app()->session['currentClass'];
            $currentLesson = Yii::app()->session['currentLesson'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_lesson = array();
            $array_class = array();
            $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
            $array_allsuite = $result['suiteLst'];
            $pages = $result['pages'];

            if (!empty($teacher_class)) {
                foreach ($teacher_class as $class) {
                    $id = $class['classID'];
                    $result = TbClass::model()->findAll("classID ='$id'");
                    array_push($array_class, $result[0]);
                }

                $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            }
            $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson']));
            $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));
            $this->render('assignWork', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'array_suite' => $array_suite,
                'array_allsuite' => $array_allsuite,
                'arrayall_suite' => $arrayall_suite,
                'pages' => $pages,
                'res' => $res
            ));
        }else{
            $teacherID = Yii::app()->session['userid_now'];
            $currentClass = Yii::app()->session['currentClass'];
            $currentLesson = Yii::app()->session['currentLesson'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_lesson = array();
            $array_class = array();
            $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
            $array_allsuite = $result['suiteLst'];
            $pages = $result['pages'];

            if (!empty($teacher_class)) {
                foreach ($teacher_class as $class) {
                    $id = $class['classID'];
                    $result = TbClass::model()->findAll("classID ='$id'");
                    array_push($array_class, $result[0]);
                }

                $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            }
            $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson']));
            $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));
            $this->render('assignWork', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'array_suite' => $array_suite,
                'array_allsuite' => $array_allsuite,
                'arrayall_suite' => arrayall_suite,
                'pages' => $pages,
                'res' => $res
            ));
        }
    }

    public function ActionDeleteExam() {
        $flag = 0;
        $res = 0;
        if (isset($_GET['examID'])) {

            $examID = $_GET['examID'];
            $classID = $_GET['classID'];
            $workID = ClassExam::model()->find("classID = '$classID' and examID = '$examID'")['workID'];
            $teacherID = Yii::app()->session['userid_now'];

            Exam::model()->deleteAll("examID='$examID'");
            ExamExercise::model()->deleteAll("examID='$examID'");
            ClassExam::model()->deleteAll("examID='$examID'");
            ExamRecord::model()->deleteALL("workID='$workID'");
            $currentClass = Yii::app()->session['currentClass'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_class = array();
            $result = TbClass::model()->getClassByTeacherID($teacherID);
            foreach ($result as $class)
                array_push($array_class, $class);

            $result = Exam::model()->getAllExamByPage(10);
            $array_allexam = $result['examLst'];
            $pages = $result['pages'];
            $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

            $this->render('assignExam', array(
                'flag' => $flag,
                'array_class' => $array_class,
                'array_exam' => $array_suite,
                'array_allexam' => $array_allexam,
                'pages' => $pages,
                'res' => $res
            ));
        }
        //
        else  if (isset($_POST['checkbox'])) {
            $classID = Yii::app()->session['currentClass'];
            $examIDlist = $_POST['checkbox'];
            $teacherID = Yii::app()->session['userid_now'];
            foreach ($examIDlist as $v) {
                $workID = ClassExam::model()->find("classID = '$classID' and examID = '$v'")['workID'];
                Exam::model()->deleteAll("examID='$v'");
                ExamExercise::model()->deleteAll("examID='$v'");
                ClassExam::model()->deleteAll("examID='$v'");
                ExamRecord::model()->deleteALL("workID='$workID'");
            }
            $currentClass = Yii::app()->session['currentClass'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_class = array();
            $result = TbClass::model()->getClassByTeacherID($teacherID);
            foreach ($result as $class)
                array_push($array_class, $class);

            $result = Exam::model()->getAllExamByPage(10);
            $array_allexam = $result['examLst'];
            $pages = $result['pages'];
            $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

            $this->render('assignExam', array(
                'flag' => $flag,
                'array_class' => $array_class,
                'array_exam' => $array_suite,
                'array_allexam' => $array_allexam,
                'pages' => $pages,
                'res' => $res
            ));
        }else{
            $teacherID = Yii::app()->session['userid_now'];
            $currentClass = Yii::app()->session['currentClass'];
            $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            $array_class = array();
            $result = TbClass::model()->getClassByTeacherID($teacherID);
            foreach ($result as $class)
                array_push($array_class, $class);

            $result = Exam::model()->getAllExamByPage(10);
            $array_allexam = $result['examLst'];
            $pages = $result['pages'];
            $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

            $this->render('assignExam', array(
                'flag' => $flag,
                'array_class' => $array_class,
                'array_exam' => $array_suite,
                'array_allexam' => $array_allexam,
                'pages' => $pages,
                'res' => $res
            ));
        }
    }

    public function ActionAddSuite() {
        $res = 0;
        $teacherID = Yii::app()->session['userid_now'];
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
            Yii::app()->session['title'] = $title;
        } else {
            $title = Yii::app()->session['title'];
        }
        $suiteLst = Suite::model()->findAll("createPerson='$teacherID'");
        foreach ($suiteLst as $all) {
            if ($all['suiteName'] == $title) {
                $res = 1;
                $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
                $array_lesson = array();
                $array_class = array();
                $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
                $array_allsuite = $result['suiteLst'];
                $pages = $result['pages'];
                if (!empty($teacher_class)) {
                    if (isset($_GET['classID']))
                        Yii::app()->session['currentClass'] = $_GET['classID'];
                    else
                        Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];

                    foreach ($teacher_class as $class) {
                        $id = $class['classID'];
                        $result = TbClass::model()->find("classID ='$id'");
                        array_push($array_class, $result);
                    }
                    $currentClass = Yii::app()->session['currentClass'];
                    $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
                    if (!empty($array_lesson)) {
                        if (isset($_GET['lessonID']))
                            Yii::app()->session['currentLesson'] = $_GET['lessonID'];
                        else
                            Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
                        $currentLesson = Yii::app()->session['currentLesson'];
                    }
                }
                if (isset(Yii::app()->session['currentClass']) && isset(Yii::app()->session['currentLesson'])) {
                    $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=? and open=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson'], 1));
                } else {
                    $array_suite = 0;
                }
                if (isset(Yii::app()->session['currentClass'])){
                    $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));                    
                }else{
                    $arrayall_suite=0;                    
                }
                $this->render('assignWork', array(
                    'array_class' => $array_class,
                    'array_lesson' => $array_lesson,
                    'array_suite' => $array_suite,
                    'array_allsuite' => $array_allsuite,
                    'arrayall_suite' => $arrayall_suite,
                    'pages' => $pages,
                    'res' => $res
                ));
                return;
            }
        }
        /////
        $classID = Yii::app()->session['currentClass'];
        $lessonID = Yii::app()->session['currentLesson'];
        $createPerson = Yii::app()->session['userid_now'];
        $suiteID = Suite::model()->insertSuite($classID, $lessonID, $title, $createPerson);
        Yii::app()->session['suiteID'] = $suiteID;
        Yii::app()->session['lastUrl'] = "modifyWork";
        $this->renderModify("choice", $suiteID, "", $res);
    }

    public function ActionAddExam() {
        $flag=0;
        $res = 0;
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
            Yii::app()->session['title'] = $title;
        } else {
            $title = Yii::app()->session['title'];
        }
        $teacherID = Yii::app()->session['userid_now'];
        $allExam = Exam::model()->findAll("createPerson='$teacherID'");
        foreach ($allExam as $all) {
            if ($all['examName'] == $title) {
                $res = 1;
                
                $array_class = array();
                $result = TbClass::model()->getClassByTeacherID($teacherID);
                foreach ($result as $class)
                    array_push($array_class, $class);

                $result = Exam::model()->getAllExamByPage(10);
                $array_allexam = $result['examLst'];
                $pages = $result['pages'];
                //得到当前显示班级
                if (isset($_GET['classID']))
                    Yii::app()->session['currentClass'] = $_GET['classID'];
                else if ($array_class != NULL)
                    Yii::app()->session['currentClass'] = $array_class[0]['classID'];
                else
                    Yii::app()->session['currentClass'] = 0;

                $currentClass = Yii::app()->session['currentClass'];

                $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

                $this->render('assignExam', array(
                    'flag' => $flag,
                    'array_class' => $array_class,
                    'array_exam' => $array_suite,
                    'array_allexam' => $array_allexam,
                    'pages' => $pages,
                    'res' => $res
                ));
                return;
                break;
            }
        }
        $classID = Yii::app()->session['currentClass'];
        $lessonID = Yii::app()->session['currentLesson'];
        $createPerson = Yii::app()->session['userid_now'];
        $examID = Exam::model()->insertExam($title, $createPerson);
        Yii::app()->session['examID'] = $examID;
        Yii::app()->session['lastUrl'] = "modifyExam";
        $this->renderModifyExam("choice", $examID, "");
    }

    public function ActionStuWork() {
        $teacherID = Yii::app()->session['userid_now'];
        $array_class = array();
        $array_suiteLessonClass = array();
        $array_lesson = array();
        $array_suite = array();

        //获取上面部分的作业列表
        //$array_suiteLessonClass表示一共有多少作业
        $selectClassID = -1;
        if (isset($_GET['selectClassID']) && $_GET['selectClassID'] != -1) {
            $result = ClassLessonSuite::model()->getSuiteClassByTeacherID($teacherID, $_GET['selectClassID']);
            $selectClassID = $_GET['selectClassID'];
        } else
            $selectClassID = -1;
        $array_class1 = TbClass::model()->getClassByTeacherID($teacherID);
        foreach ($array_class1 as $result) {
            array_push($array_class, $result);
        }
        if (!isset($_GET['selectClassID'])) {
            if ($array_class != NULL) {
                $selectClassID = $array_class[0]['classID'];
            } else {
                $selectClassID = -1;
            }
        }
        if ($selectClassID == -1) {
            $result = ClassLessonSuite::model()->getSuiteClassByTeacherID($teacherID, "");
        } else {
            $result = ClassLessonSuite::model()->getSuiteClassByTeacherID($teacherID, $selectClassID);
        }
        $suiteResult = Suite::model()->findAll('createPerson=?', array($teacherID));

        $array_suiteLessonClass1 = $result['suiteLst'];
        $pages = $result['pages'];


        $array_lesson1 = Lesson::model()->getLessonByTeacherID($teacherID);
        $array_suite1 = Suite::model()->getSuiteByClassLessonSuite($teacherID);
        foreach ($array_suiteLessonClass1 as $result){
        array_push($array_suiteLessonClass, $result);}
        foreach ($array_lesson1 as $result){
        array_push($array_lesson, $result);}
        foreach ($array_suite1 as $result){
        array_push($array_suite, $result);}
        $workID = -1;
        $classID = -1;
        if ($array_suiteLessonClass != NULL) {
            if ($suiteResult) {
                $workID = ClassLessonSuite::model()->find('suiteID=?', array($suiteResult[0]['suiteID']))['workID'];
                $classID = $array_suiteLessonClass[0]['classID'];
            }
        }
        if (isset($_GET['workID'])) {
            $workID = $_GET['workID'];
            $classID = $_GET['classID'];
        }
        //获取学生作业完成情况 需要currentClass currentLesson currentSuite
        $array_accomplished = array();
        $array_unaccomplished = array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        if ($workID) {
            foreach ($class_student as $student) {
                $userID = $student['userID'];
                $result = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $userID));

                if ($result != NULL && $result['ratio_accomplish'] == 1)
                    array_push($array_accomplished, $student);
                else
                    array_push($array_unaccomplished, $student);
            }
        }
        $this->render('studentWork', array(
            'array_class' => $array_class,
            'array_lesson' => $array_lesson,
            'array_suite' => $array_suite,
            'array_suiteLessonClass' => $array_suiteLessonClass,
            'pages' => $pages,
            'workID' => $workID,
            'array_accomplished' => $array_accomplished,
            'array_unaccomplished' => $array_unaccomplished,
            'selectClassID' => $selectClassID,
        ));
    }

    public function ActionStuExam() {
        $teacherID = Yii::app()->session['userid_now'];
        $array_class = array();
        $array_classExam = array();
        $array_exam = array();
        if (isset($_GET['selectClassID']))
            $selectClassID = $_GET['selectClassID'];

        else {
            $class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
            if ($class != NULL)
                $selectClassID = $class[0]['classID'];
            else
                $selectClassID = -1;
        }
        $result = ClassExam::model()->getExamClassByTeacherID($teacherID, $selectClassID);
        $array_classExam1 = $result['suiteLst'];
        $pages = $result['pages'];

        $array_class1 = TbClass::model()->getClassByTeacherID($teacherID);
        $array_exam1 = Exam::model()->getExamByClassExam($teacherID);
        foreach ($array_classExam1 as $result)
            array_push($array_classExam, $result);
        foreach ($array_class1 as $result)
            array_push($array_class, $result);
        foreach ($array_exam1 as $result)
            array_push($array_exam, $result);

        $workID = -1;
        $classID = -1;
        if ($array_classExam != NULL) {
            $workID = $array_classExam[0]['workID'];
            $classID = $array_classExam[0]['classID'];
        }
        if (isset($_GET['workID'])) {
            $workID = $_GET['workID'];
            $classID = $_GET['classID'];
        }
        //获取学生作业完成情况 需要currentClass currentLesson currentSuite
        $array_accomplished = array();
        $array_unaccomplished = array();
        $class_student = Student::model()->findAll("classID = '$classID'");

        foreach ($class_student as $student) {
            $userID = $student['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));

            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $student['userID'],
                    'userName' => $student['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $student['userID'],
                    'userName' => $student['userName'],
                    'score' => 0
                ));
            }
        }
        $this->render('studentExam', array(
            'array_class' => $array_class,
            'array_exam' => $array_exam,
            'array_classExam' => $array_classExam,
            'pages' => $pages,
            'workID' => $workID,
            'array_accomplished' => $array_accomplished,
            'array_unaccomplished' => $array_unaccomplished,
            'selectClassID' => $selectClassID,
        ));
    }

    public function ActionChangeSuiteClass() {
        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }
        $res = 0;
        $suiteID = $_GET['suiteID'];
        $isOpen = $_GET['isOpen'];
        $currentClass = Yii::app()->session['currentClass'];
        $currentLesson = Yii::app()->session['currentLesson'];
        $result = ClassLessonSuite::model()->find("classID=? and lessonID=? and suiteID=?", array($currentClass, $currentLesson, $suiteID));
        if ($result == NULL)
            ClassLessonSuite::model()->insertSuite($currentClass, $currentLesson, $suiteID);
        else {
            $result->open = 1 - $isOpen;
            $result->update();
        }
        $teacherID = Yii::app()->session['userid_now'];
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        $array_lesson = array();
        $array_class = array();
        $result = Suite::model()->getAllSuiteByPage(10, $teacherID);
        $array_allsuite = $result['suiteLst'];
        $pages = $result['pages'];

        if (!empty($teacher_class)) {
            foreach ($teacher_class as $class) {
                $id = $class['classID'];
                $result = TbClass::model()->findAll("classID ='$id'");
                array_push($array_class, $result[0]);
            }

            $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
        }
        $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=? and open=?', array(Yii::app()->session['currentClass'], Yii::app()->session['currentLesson'], 1));
        $arrayall_suite = ClassLessonSuite::model()->findAll('classID=? and  open=?', array(Yii::app()->session['currentClass'], 1));
        $this->render('assignWork', array(
            'array_class' => $array_class,
            'array_lesson' => $array_lesson,
            'array_suite' => $array_suite,
            'array_allsuite' => $array_allsuite,
            'arrayall_suite' => $arrayall_suite,
            'pages' => $pages,
            'res' => $res
        ));
    }

    public function ActionChangeExamClass() {
        $flag = 0;

        if (!isset(Yii::app()->session['userid_now'])) {
            return $this->render('index');
        }

        $res = 0;
        $examID = $_GET['examID'];
        $isOpen = $_GET['isOpen'];
        $duration = $_GET['duration'];
        if(isset($_GET['flag'])){
            if($_GET['flag']==1){
                $startTime = $_GET['beginTime'];
                $endTime=date("Y-m-d H:i:s",strtotime("$startTime   +$duration   minute"));
            }
        }else{
            $startTime =date("Y-m-d H:i:s", time());
            $endTime=date("Y-m-d H:i:s",strtotime("$startTime   +$duration   minute"));
        }
        if ($isOpen == 0){
            error_log($startTime);
            Exam::model()->updateByPk($examID, array('begintime' => $startTime, 'duration' => $duration,'endtime'=>$endTime));
        }
        $currentClass = Yii::app()->session['currentClass'];
        $result = ClassExam::model()->find("classID=? and examID=?", array($currentClass, $examID));
        if ($result == NULL) {

            ClassExam::model()->insertExam($currentClass, $examID);
        } else {
            $result->open = 1 - $isOpen;
            $result->update();
        }

        $teacherID = Yii::app()->session['userid_now'];
        $array_class = array();
        $result = TbClass::model()->getClassByTeacherID($teacherID);
        foreach ($result as $class)
            array_push($array_class, $class);

        $result = Exam::model()->getAllExamByPage(10);
        $array_allexam = $result['examLst'];
        $pages = $result['pages'];

        $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'], 1));

        $this->render('assignExam', array(
            'flag' => $flag,
            'array_class' => $array_class,
            'array_exam' => $array_suite,
            'array_allexam' => $array_allexam,
            'pages' => $pages,
            'res' => $res
        ));
    }

    public function ActionToOwnWork() {
        $type = $_GET['type'];
        $suiteID = $_GET['suiteID'];
        $this->renderOwnWork($type, $suiteID);
    }

    public function ActionToOwnTypeWork() {
        $type = $_GET['type'];
        $suiteID = $_GET['suiteID'];
        $this->renderOwnTypeWork($type, $suiteID);
    }

    public function ActionToOwnExam() {
        $type = $_GET['type'];
        $examID = $_GET['examID'];
        $exam = Exam::model()->find("examID = '$examID'");
        $result = Exam::model()->getExamExerByTypePage($examID, $type, 5);
        $examExercise = ExamExercise::model()->findAll("examID=? and type=?", array($examID, $type));
        $totalScore = ExamExercise::model()->getTotalScore($examID);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('ownExam', array(
            'examWork' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'exam' => $exam,
            'examExercise' => $examExercise,
            'totalScore' => $totalScore
        ));
    }

    public function ActionToOwnTypeExam() {
        $type = $_GET['type'];
        $examID = $_GET['examID'];
        $exam = Exam::model()->findAll("examID = '$examID'");
        $result = Exam::model()->getExamExerByTypePage($examID, $type, 5);
        $examExercise = ExamExercise::model()->findAll("examID=? and type=?", array($examID, $type));
        $totalScore = ExamExercise::model()->getTotalScore($examID);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('ownTypeExam', array(
            'examWork' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'exam' => $exam,
            'examExercise' => $examExercise,
            'totalScore' => $totalScore
        ));
    }

    public function ActionToAllWork() {
        $type = $_GET['type'];
        $suiteID = $_GET['suiteID'];
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('AllWork', array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => "",
        ));
    }

    public function ActionToAllExam() {
        $type = $_GET['type'];
        $examID = $_GET['examID'];
        $exam = Exam::model()->find("examID = '$examID'");
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('AllExam', array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'exam' => $exam,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => "",
        ));
    }

    public function ActionToAllTypeWork() {
        $suite = null;
        $type = $_GET['type'];
        if (isset($_GET['suiteID'])) {
            $suiteID = $_GET['suiteID'];
            $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        }
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('allTypeWork', array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => "",
        ));
    }

    public function ActionToAllTypeExam() {
        $type = $_GET['type'];
        $examID = $_GET['examID'];
        $exam = Exam::model()->find("examID = '$examID'");
        $result = $this->getLstByType($type);
        $workLst = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('allTypeExam', array(
            'workLst' => $workLst,
            'pages' => $pages,
            'type' => $type,
            'exam' => $exam,
            'teacher' => Teacher::model()->findall(),
            'maniResult' => "",
        ));
    }

    public function renderOwnWork($type, $suiteID) {
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type, 5);
        $workChoice = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('ownWork', array(
            'suiteWork' => $workChoice,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite
        ));
    }

    public function renderOwnTypeWork($type, $suiteID) {
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
        $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type, 5);
        $workKey = $result['workLst'];
        $pages = $result['pages'];
        $this->renderPartial('ownTypeWork', array(
            'suiteWork' => $workKey,
            'pages' => $pages,
            'type' => $type,
            'suite' => $suite
        ));
    }

    public function ActionNextStuWork() {
        $workID = $_GET['workID'];
        $studentID = $_GET['studentID'];
        $accomplish = $_GET['accomplish'];
        $classID = $_GET['classID'];
        $suiteID = $_GET['suiteID'];
        $nextStudentID = SuiteRecord::model()->getNextStudentID($workID, $studentID, $accomplish, $classID);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        if ($nextStudentID == -1) {
            $this->renderStuWork($studentID, $workID, "choice", $accomplish, $classwork, $suiteID, $workID);
        } else {
            $this->renderStuWork($nextStudentID, $workID, "choice", $accomplish, $classwork, $suiteID, $workID);
        }
    }

    public function ActionNextStuExam() {
        $workID = $_GET['workID'];

        $classID = $_GET['classID'];
        $class_student = Student::model()->findAll("classID = '$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        foreach ($class_student as $student) {
            $userID = $student['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $student['userID'],
                    'userName' => $student['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $student['userID'],
                    'userName' => $student['userName'],
                    'score' => 0
                ));
            }
        }



        $studentID = $_GET['studentID'];
        Yii::app()->session['studentID'] = $studentID;
        Yii::app()->session['workID'] = $workID;
        $accomplish = $_GET['accomplish'];
        $classID = $_GET['classID'];
        $classwork = Array();
        $examID = $_GET['examID'];
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        $this->renderStuExam($_GET['studentID'], $workID, "choice", $accomplish, $array_accomplished, $classwork, $examID, $workID);

        $nextStudentID = ExamRecord::model()->getNextStudentID($workID, $studentID, $accomplish, $classID);

        /* if($nextStudentID == -1)
          {
          $this->renderStuExam($studentID,$workID,"choice",$accomplish,$array_accomplished);
          }else{
          $this->renderStuExam($nextStudentID,$workID,"choice",$accomplish,$array_accomplished);
          }
         * 
         */
    }

    public function renderStuWork($studentID, $workID, $type, $accomplish, $classwork, $suiteID, $workID) {
        $student = Student::model()->find("userID='$studentID'");
        $work = ClassLessonSuite::model()->find("workID='$workID'");
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $student['userID']));
        $classID = $work['classID'];
        $lessonID = $work['lessonID'];
        $class = TbClass::model()->find("classID='$classID'");
        $lesson = Lesson::model()->find("lessonID='$lessonID'");
        $this->render('checkStuWork', array(
            'student' => $student,
            'class' => $class,
            'type' => $type,
            'record' => $record,
            'lesson' => $lesson,
            'work' => $work,
            'exercise' => $classwork,
            'suiteID' => $suiteID,
            'workID' => $workID,
            'accomplish' => $accomplish,
        ));
    }

    public function renderStuExam($studentID, $workID, $type, $accomplish, $array_accomplished, $classwork, $examID, $workID) {
        $student = Student::model()->find("userID='$studentID'");
        $work = ClassExam::model()->find("workID='$workID'");
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $student['userID']));
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($record['recordID']);
        $classID = $work['classID'];
        $class = TbClass::model()->find("classID='$classID'");
        $this->render('checkStuExam', array(
            'workID' => $workID,
            'examID' => $examID,
            'exercise' => $classwork,
            'student' => $student,
            'class' => $class,
            'type' => $type,
            'record' => $record,
            'work' => $work,
            'accomplish' => $accomplish,
            'score' => $score,
            'array_accomplished' => $array_accomplished
        ));
    }

    public function ActionCheckStuWork() {
        $workID = $_GET['workID'];
        $classID = $_GET['classID'];
        $studentID = $_GET['studentID'];
        $accomplish = $_GET['accomplish'];
        $ty = $_GET['type'];
        $class_student = Student::model()->findAll("classID = '$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName']
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName']
                ));
            }
        }
        $student = Student::model()->find("userID='$studentID'");
        $work = ClassLessonSuite::model()->find("workID='$workID'");
        $record = SuiteRecord::model()->find("workID=? and studentID=?", array($work['workID'], $student['userID']));

        $classID = $work['classID'];
        $lessonID = $work['lessonID'];
        $suiteID = $work['suiteID'];
        $class = TbClass::model()->find("classID='$classID'");
        $lesson = Lesson::model()->find("lessonID='$lessonID'");
        Yii::app()->session['suiteID'] = $suiteID;
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        $this->render('checkStuWork', array(
            'student' => $student,
            'class' => $class,
            'lesson' => $lesson,
            'record' => $record,
            'type' => $ty,
            'work' => $work,
            'accomplish' => $accomplish,
            'array_accomplished' => $array_accomplished,
            'suiteID' => $suiteID,
            'exercise' => $classwork,
            'workID' => $workID,
        ));
    }

    public function ActionCheckStuExam() {
        $workID = $_GET['workID'];
        $classID = $_GET['classID'];
        $class_student = Student::model()->findAll("classID = '$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        $studentID = $_GET['studentID'];
        $accomplish = $_GET['accomplish'];
        $ty = $_GET['type'];

        $student = Student::model()->find("userID='$studentID'");
        $work = ClassExam::model()->find("workID='$workID'");
        $record = ExamRecord::model()->find("workID=? and studentID=?", array($work['workID'], $student['userID']));

        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($record['recordID']);
        if(!isset($score)){
            $score=0;
        }
        $classID = $work['classID'];
        Yii::app()->session['classID'] = $classID;
        $class = TbClass::model()->find("classID='$classID'");
        $examID = $work->examID;
        Yii::app()->session['examID'] = $examID;
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        $ansWork = AnswerRecord::model()->findAll("recordID = ? and type = ?", array($record['recordID'], $ty));
        $this->render('checkStuExam', array(
            'ansWork' => $ansWork,
            'examID' => $examID,
            'student' => $student,
            'class' => $class,
            'type' => $ty,
            'record' => $record,
            'work' => $work,
            'accomplish' => $accomplish,
            'score' => $score,
            'array_accomplished' => $array_accomplished,
            'exercise' => $classwork,
            'workID' => $workID,
        ));
    }

    public function ActionAjaxChoice() {
        $type = $_POST['type'];
        $recordID = $_POST['recordID'];
        $suiteID = $_POST['suiteID'];
        $exerciseID = $_POST['exerciseID'];
        $isLast = 1;
        $results = Suite::model()->getSuiteExerByType($suiteID, $type);
        $array_exercise = array();
        foreach ($results as $result) {
            array_push($array_exercise, $result);
        }
        foreach ($array_exercise as $result) {
            $work = $result;
            if ($result['exerciseID'] > $exerciseID) {
                $isLast = 0;
                $work = $result;
                break;
            }
        }
        $suite_exercise = SuiteExercise::model()->find("exerciseID=? and suiteID=? and type=?", array($work['exerciseID'], $suiteID, $type));
        $SQLchoiceAnsWork = AnswerRecord::model()->findAll("recordID=? and type=? order by exerciseID", array($recordID, $type));
        $choiceAnsWork = array();
        foreach ($SQLchoiceAnsWork as $v) {
            $answer = $v['answer'];
            array_push($choiceAnsWork, $answer);
        }

        switch ($type) {
            case "choice":
                $render = "suiteChoice";
                break;
            case "filling":
                $render = "suiteFilling";
                break;
            case "question":
                $render = "suiteQuestion";
                break;
            case "key":
                $render = "suiteKey";
                break;
            case "look":
                $render = "suiteLook";
                break;
            case "listen":
                $render = "suiteListen";
                break;
        }
        $ansWork = AnswerRecord::model()->find("recordID=? and type=? and exerciseID=?", array($recordID, $type, $work['exerciseID']));
        $this->renderPartial($render, array(
            'work' => $work,
            'ansWork' => $ansWork,
            'works' => $array_exercise,
            'choiceAnsWork' => $choiceAnsWork,
            'suite_exercise' => $suite_exercise,
            'isLast' => $isLast,
        ));
    }

    public function actionAnsKeyTypeWork() {
        $ty = $_GET['type'];
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        $suiteID = Yii::app()->session['suiteID'];
        $workID = $_GET['workID'];
        $isExam = Yii::app()->session['isExam'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Suite::model()->getSuiteExerByType($suiteID, $type);
        }
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $studentID = $_GET['studentID'];
        $workID = $_GET['workID'];
//        $studentID = Yii::app()->session['userid_now'];
//        if(isset(Yii::app()->session['studentID'])&&isset(Yii::app()->session['workID'])){
//            $studentID = Yii::app()->session['studentID'];
//            $workID = Yii::app()->session['workID'];
//        }
        $recordID = suiteRecord::getRecord($workID, $studentID);
        $suite_exercise = SuiteExercise::model()->find("exerciseID=? and exerciseID=? and type=?", array($_GET['exerID'], $suiteID, $ty));
        $student = Student::model()->find("userID='$studentID'");
        $classID = Yii::app()->session['classID'];
        if (!isset($classID)) {
            $classID = Student::model()->findClassByStudentID($studentID);
        }
        $class = TbClass::model()->find("classID='$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        $class_student = Student::model()->findAll("classID = '$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        $work = ClassLessonSuite::model()->find("workID='$workID'");
        $ansWork = AnswerRecord::model()->find("recordID=? and type=? and exerciseID=?", array($recordID, $ty, $exerID));
        $lessonID = $work['lessonID'];
        $lesson = Lesson::model()->find("lessonID='$lessonID'");
        switch ($ty) {
            case "choice":
                $render = "suiteChoice";
                break;
            case "filling":
                $render = "suiteFilling";
                break;
            case "question":
                $render = "suiteQuestion";
                break;
            case "key":
                $res = KeyType::model()->findByPK($exerID);
                $render = "suiteKey";
                break;
            case "look":
                $res = LookType::model()->findByPK($exerID);
                $render = "suiteLook";
                break;
            case "listen":
                $res = ListenType::model()->findByPK($exerID);
                $render = "suiteListen";
                break;
        }

        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswerAndUserID($recordID, $ty, $exerID, $studentID);
        $accomplish = $_GET['accomplish'];
        $correct = $answer['ratio_correct'];
        $n = strrpos($correct, "&");
        $correct = substr($correct, $n + 1);
        return $this->render($render, ['exercise' => $classwork,
                    'student' => $student,
                    'suiteID' => $suiteID,
                    'class' => $class,
                    'work' => $work,
                    'accomplish' => $accomplish,
                    'ansWork' => $ansWork,
                    'array_accomplished' => $array_accomplished,
                    'exer' => $res,
                    'exerID' => $exerID,
                    'studentID' => $studentID,
                    'classID' => $classID,
                    'workID' => $workID,
                    'type' => $ty,
                    'lesson' => $lesson,
                    'array_accomplished' => $array_accomplished,
                    'exam_exercise' => $suite_exercise,
                    'answer' => $answer['answer'],
                    'correct' => $correct]);
    }

    public function actionAnsKeyType() {
        $ty = $_GET['type'];
        $exerID = $_GET['exerID'];
        Yii::app()->session['exerID'] = $exerID;
        $examID = Yii::app()->session['examID'];
        $workID = $_GET['workID'];
        $isExam = Yii::app()->session['isExam'];
        $classwork = Array();
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        Yii::app()->session['exerID'] = $exerID;
        Yii::app()->session['exerType'] = 'key';
        $studentID = $_GET['studentID'];
        $workID = $_GET['workID'];
//        $studentID = Yii::app()->session['userid_now'];
//        if(isset(Yii::app()->session['studentID'])&&isset(Yii::app()->session['workID'])){
//            $studentID = Yii::app()->session['studentID'];
//            $workID = Yii::app()->session['workID'];
//        }
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $exam_exercise = ExamExercise::model()->find("exerciseID=? and examID=? and type=?", array($_GET['exerID'], $examID, $ty));
        $student = Student::model()->find("userID='$studentID'");
        $classID = Yii::app()->session['classID'];
        $class = TbClass::model()->find("classID='$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        $scorer = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        $work = ClassExam::model()->find("workID='$workID'");
        $ansWork = AnswerRecord::model()->find("recordID=? and type=? and exerciseID=?", array($recordID, $ty, $exerID));
        switch ($ty) {
            case "choice":
                $render = "examChoice";
                break;
            case "filling":
                $render = "examFilling";
                break;
            case "question":
                $render = "examQuestion";
                break;
            case "key":
                $res = KeyType::model()->findByPK($exerID);
                $render = "examKey";
                break;
            case "look":
                $res = LookType::model()->findByPK($exerID);
                $render = "examLook";
                break;
            case "listen":
                $res = ListenType::model()->findByPK($exerID);
                $render = "examListen";
                break;
        }

        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswerAndUserID($recordID, $ty, $exerID, $studentID);
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        $accomplish = $_GET['accomplish'];
        $correct = $answer['ratio_correct'];
        $n = strrpos($correct, "&");
        $correct = substr($correct, $n + 1);
        return $this->render($render, ['exercise' => $classwork,
                    'student' => $student,
                    'examID' => $examID,
                    'class' => $class,
                    'work' => $work,
                    'accomplish' => $accomplish,
                    'ansWork' => $ansWork,
                    'array_accomplished' => $array_accomplished,
                    'exer' => $res,
                    'exerID' => $exerID,
                    'studentID' => $studentID,
                    'classID' => $classID,
                    'score' => $scorer,
                    'workID' => $workID,
                    'type' => $ty,
                    'exam_exercise' => $exam_exercise,
                    'answer' => $answer['answer'],
                    'correct' => $correct]);
    }

    //
    public function ActionAjaxExam2() {
        $classID = $_GET['classID'];
        if (isset($_POST['workID'])) {
            $workID = $_POST['workID'];
            $studentID = $_POST['studentID'];
            $accomplish = $_POST['accomplish'];
        }
        $ty = $_POST['type'];
        $recordID = $_POST['recordID'];
        $examID = $_POST['examID'];
        $exerciseID = $_POST['exerciseID'];
        if (isset($_POST['score']) && isset($_POST['answerID'])) {
            AnswerRecord::model()->changeScore($_POST['answerID'], $_POST['score']);
        }
        $results = Exam::model()->getExamExerByType($examID, $ty);
        $isLast = 1;
        $array_exercise = array();
        foreach ($results as $result) {
            array_push($array_exercise, $result);
        }
        foreach ($array_exercise as $result) {
            $work = $result;
            if ($result['exerciseID'] > $exerciseID) {
                $isLast = 0;
                $work = $result;
                break;
            }
        }
        $exam_exercise = ExamExercise::model()->find("exerciseID=? and examID=? and type=?", array($work['exerciseID'], $examID, $ty));
        $ansWork = AnswerRecord::model()->find("recordID=? and type=? and exerciseID=?", array($recordID, $ty, $work['exerciseID']));

        $SQLchoiceAnsWork = AnswerRecord::model()->findAll("recordID=? and type=? order by exerciseID", array($recordID, $ty));
        $choiceAnsWork = array();
        foreach ($SQLchoiceAnsWork as $v) {
            $answer = $v['answer'];
            array_push($choiceAnsWork, $answer);
        }
        $student = Student::model()->find("userID='$studentID'");
        $class = TbClass::model()->find("classID='$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        switch ($ty) {
            case "choice":
                $render = "examChoice";
                break;
            case "filling":
                $render = "examFilling";
                break;
            case "question":
                $render = "examQuestion";
                break;
            case "key":
                $render = "examKey";
                break;
            case "look":
                $render = "examLook";
                break;
            case "listen":
                $render = "examListen";
                break;
        }
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        $work = ClassExam::model()->find("workID='$workID'");
        $exerID = Yii::app()->session['exerID'];
        $res = KeyType::model()->findByPK($exerID);
        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswerAndUserID($recordID, $ty, $exerID, $studentID);
        $accomplish = $_GET['accomplish'];
        $correct = $answer['ratio_correct'];
        $this->renderPartial($render, array(
            'type' => $ty,
            'student' => $student,
            'examID' => $examID,
            'student' => $student,
            'class' => $class,
            'workID' => $workID,
            'studentID' => $studentID,
            'accomplish' => $accomplish,
            'works' => $array_exercise,
            'work' => $work,
            'exer' => $res,
            'ansWork' => $ansWork,
            'choiceAnsWork' => $choiceAnsWork,
            'exam_exercise' => $exam_exercise,
            'isLast' => $isLast,
            'score' => $score,
            'exerID' => $exerID,
            'classID' => $classID,
            'exercise' => $classwork,
            'array_accomplished' => $array_accomplished,
            'answer' => $answer['answer'],
            'correct' => $correct
        ));
    }

    public function ActionAjaxExam() {
        $classID = $_GET['classID'];
        if (isset($_POST['workID'])) {
            $workID = $_POST['workID'];
            $studentID = $_POST['studentID'];
            $accomplish = $_POST['accomplish'];
        }
        $ty = $_POST['type'];
        if (isset($_POST['recordID']))
            $recordID = $_POST['recordID'];
        $examID = $_POST['examID'];
        if (isset($_POST['exerciseID']))
            $exerciseID = $_POST['exerciseID'];
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $exam_exercise = ExamExercise::model()->findAll("examID = ? and type = ?", array($examID, $ty));
        $ansWork = AnswerRecord::model()->findAll("recordID = ? and type = ?", array($recordID, $ty));
        $results = Exam::model()->getExamExerByType($examID, $ty);
        $isLast = 1;
        $array_exercise = array();
        foreach ($results as $result) {
            array_push($array_exercise, $result);
        }
        /*
          foreach ($array_exercise as $result) {
          $work = $result;
          if ($result['exerciseID'] > $exerciseID) {
          $isLast = 0;
          $work = $result;
          break;
          }
          } */
        if (isset($_POST['score'])) {
            $arr = explode(",", $_POST['score']);
            $m = 0;
            foreach ($array_exercise as $k => $work) {
                if ($arr[$m] != " ") {
                    AnswerRecord::model()->changeScore($ansWork[$k]['answerID'], $arr[$m++]);
                    if ($ty == 'choice')
                        break;
                }
            }
        }
        $SQLchoiceAnsWork = AnswerRecord::model()->findAll("recordID=? and type=? order by exerciseID", array($recordID, $ty));
        $choiceAnsWork = array();
        foreach ($SQLchoiceAnsWork as $v) {
            $answer = $v['answer'];
            array_push($choiceAnsWork, $answer);
        }
        $student = Student::model()->find("userID='$studentID'");
        $class = TbClass::model()->find("classID='$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        switch ($ty) {
            case "choice":
                $render = "examChoice";
                break;
            case "filling":
                $render = "examFilling";
                break;
            case "question":
                $render = "examQuestion";
                break;
            case "key":
                $render = "examKey";
                break;
            case "look":
                $render = "examLook";
                break;
            case "listen":
                $render = "examListen";
                break;
        }
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        $work = ClassExam::model()->find("workID='$workID'");
        $exerID = Yii::app()->session['exerID'];
        $res = KeyType::model()->findByPK($exerID);
        $ansWork = AnswerRecord::model()->findAll("recordID = ? and type = ?", array($recordID, $ty));
        $this->renderPartial($render, array(
            'type' => $ty,
            'student' => $student,
            'examID' => $examID,
            'student' => $student,
            'class' => $class,
            'workID' => $workID,
            'studentID' => $studentID,
            'accomplish' => $accomplish,
            'works' => $array_exercise,
            'work' => $work,
            'exer' => $res,
            'ansWork' => $ansWork,
            'choiceAnsWork' => $choiceAnsWork,
            'exam_exercise' => $exam_exercise,
            'isLast' => $isLast,
            'score' => $score,
            'exerID' => $exerID,
            'classID' => $classID,
            'exercise' => $classwork,
            'array_accomplished' => $array_accomplished,
        ));
    }
    
    public function ActionAjaxExam3() {
        $classID = $_GET['classID'];
        if (isset($_POST['workID'])) {
            $workID = $_POST['workID'];
            $studentID = $_POST['studentID'];
            $accomplish = $_POST['accomplish'];
        }
        $ty = $_POST['type'];
        if (isset($_POST['recordID']))
            $recordID = $_POST['recordID'];
        $examID = $_POST['examID'];
        if (isset($_POST['exerciseID']))
            $exerciseID = $_POST['exerciseID'];
        $recordID = ExamRecord::getRecord($workID, $studentID);
        $exam_exercise = ExamExercise::model()->findAll("examID = ? and type = ?", array($examID, $ty));
        $ansWork = AnswerRecord::model()->findAll("recordID = ? and type = ?", array($recordID, $ty));
        $results = Exam::model()->getExamExerByType($examID, $ty);
        $isLast = 1;
        $array_exercise = array();
        foreach ($results as $result) {
            array_push($array_exercise, $result);
        }
        
        if (isset($_POST['score'])) {
            $arr = explode(",", $_POST['score']);
            $m = 0;
            if(isset($_POST['answerID'])){
                $answerID=$_POST['answerID'];
            }
//            foreach ($array_exercise as $k => $work) {
//                if ($arr[$m] != " ") {
                    AnswerRecord::model()->changeScore($answerID, $arr[$m++]);
//                }
            
        }
        $student = Student::model()->find("userID='$studentID'");
        $class = TbClass::model()->find("classID='$classID'");
        $array_accomplished = Array();
        $array_unaccomplished = Array();
        $class_student = Student::model()->findAll("classID = '$classID'");
        foreach ($class_student as $stu) {
            $userID = $stu['userID'];
            $result = ExamRecord::model()->find("workID=? and studentID=?", array($workID, $userID));
            if ($result != NULL && $result['ratio_accomplish'] == 1) {
                $score = $result['score'];
                array_push($array_accomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => $score
                ));
            } else {
                array_push($array_unaccomplished, array(
                    'userID' => $stu['userID'],
                    'userName' => $stu['userName'],
                    'score' => 0
                ));
            }
        }
        switch ($ty) {
            case "choice":
                $render = "examChoice";
                break;
            case "filling":
                $render = "examFilling";
                break;
            case "question":
                $render = "examQuestion";
                break;
            case "key":
                $render = "examKey";
                break;
            case "look":
                $render = "examLook";
                break;
            case "listen":
                $render = "examListen";
                break;
        }
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        foreach (Tool::$EXER_TYPE as $type) {
            $classwork[$type] = Exam::model()->getExamExerByType($examID, $type);
        }
        $work = ClassExam::model()->find("workID='$workID'");
        $exerID = Yii::app()->session['exerID'];
        $res = KeyType::model()->findByPK($exerID);
        $ansWork = AnswerRecord::model()->findAll("recordID = ? and type = ?", array($recordID, $ty));
        $this->renderPartial($render, array(
            'type' => $ty,
            'student' => $student,
            'examID' => $examID,
            'student' => $student,
            'class' => $class,
            'workID' => $workID,
            'studentID' => $studentID,
            'accomplish' => $accomplish,
            'works' => $array_exercise,
            'work' => $work,
            'exer' => $res,
            'ansWork' => $ansWork,

            'exam_exercise' => $exam_exercise,
            'isLast' => $isLast,
            'score' => $score,
            'exerID' => $exerID,
            'classID' => $classID,
            'exercise' => $classwork,
            'array_accomplished' => $array_accomplished,
        ));
    }

    public function ActionConfigScore() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $examID = $_GET['examID'];
        $score = $_GET['score'];
        $thisExamExercise = new ExamExercise();
        $thisExamExercise->updateScore($exerciseID, $type, $examID, $score);
        if ($type == "key" || $type == "look" || $type == "listen") {
            $this->ActionToOwnTypeExam();
        } else {
            $this->ActionToOwnExam();
        }
    }

    public function ActionConfigTime() {
        $type = $_GET['type'];
        $exerciseID = $_GET['exerciseID'];
        $examID = $_GET['examID'];
        $time = $_GET['time'];
        $thisExamExercise = new ExamExercise();
        $thisExamExercise->updateTime($exerciseID, $type, $examID, $time);
        if ($type == "key" || $type == "look" || $type == "listen") {
            $this->ActionToOwnTypeExam();
        } else {
            $this->ActionToOwnExam();
        }
    }

    //公告信息
    public function actionteacherNotice() {
        $result = Notice::model()->findNotice();
        $noticeRecord = $result ['noticeLst'];
        $pages = $result ['pages'];
        $teacherID = Yii::app()->session['userid_now'];
        $noticeS = Teacher::model()->findByPK($teacherID);
        $noticeS->noticestate = '0';
        $noticeS->update();
        $this->render('teacherNotice', array('noticeRecord' => $noticeRecord, 'pages' => $pages));
    }

    public function ActionNoticeContent() {
        $result = 0;
        if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            $result = 1;
        }
        $id = $_GET['id'];
        $noticeRecord = Notice::model()->find("id= '$id'");
        $this->render('noticeContent', array('noticeRecord' => $noticeRecord));
    }

    public function actionScheduleDetil() {
        $teacherID = Yii::app()->session['userid_now'];
        if (isset($_GET["progress"])) {
            Yii::app()->session['progress'] = $_GET["progress"];
            Yii::app()->session['on'] = $_GET["on"];
        }
        $sqlTeacher = Teacher::model()->find("userID = '$teacherID'");
        $array_course = array();
        $array_class = array();
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        if (!empty($teacher_class)) {
            if (isset($_GET['classID']))
                Yii::app()->session['currentClass'] = $_GET['classID'];
            else
                Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];

            foreach ($teacher_class as $class) {
                $id = $class['classID'];
                $SqlcourseID = TbClass::model()->find("classID ='$id'");
                $courseID = $SqlcourseID['currentCourse'];
                $course = Course::model()->find("courseID = '$courseID'");

                $result = TbClass::model()->find("classID ='$id'");
                array_push($array_course, $course);
                array_push($array_class, $result);
            }
            $currentClass = Yii::app()->session['currentClass'];
            $sqlcurrentClass = TbClass::model()->find("classID = '$currentClass'");
            if (!isset($sqlcurrentClass)) {
                $sqlcurrentClass = "none";
            }
            if (!empty($array_course)) {
                if (isset($_GET['courseID'])) {
                    Yii::app()->session['currentCourse'] = $_GET['courseID'];
                } else
                    Yii::app()->session['currentCourse'] = $array_course[0]['courseID'];
            }
        }

        if (!isset($_GET['courseID']) && !isset($_GET['number'])&&isset($_GET['classID'])) {
            //查询任课班级科目
            $classResult = ScheduleClass::model()->findAll("classID='$currentClass'");
            return $this->render('scheduleDetil', ['teacher' => $sqlTeacher, 'result' => $classResult, 'array_class' => $array_class,
                        'array_course' => $array_course, 'sqlcurrentClass' => $sqlcurrentClass]);
            //} else if (isset($_GET['courseID']) && !isset($_GET['lessonName'])) {   
        } else if (isset($_GET['courseID']) && !isset($_GET['number'])&&isset($_GET['classID'])) {
            //显示课程列表逻辑
            $classID=$_GET['classID'];
            $courseID = $_GET ['courseID'];
            $SqlclassID = TbClass::model()->findAll("currentCourse ='$courseID'");
//            foreach ($teacher_class as $classA) {
//                foreach($SqlclassID as $classB){
//                    if($classA['classID']==$classB['classID']){
//                        $classID=$classA['classID'];
//                        error_log($classID);
//                        break;
//                    }
//                }
//            }
            $result = Lesson::model()->getLessonLst("", "", $courseID,$classID);
            $sqlCourse = Course::model()->find("courseID = '$courseID'");
            $courseName = $sqlCourse['courseName'];
            $createPerson = $sqlCourse['createPerson'];
            $lessonLst = $result ['lessonLst'];
            $pages = $result ['pages'];

            $this->render('scheduleDetil', array(
                'teacher' => $sqlTeacher, 'result' => "", 'array_class' => $array_class,
                'array_course' => $array_course, 'sqlcurrentClass' => $sqlcurrentClass,
                'courseID' => $courseID,
                'courseName' => $courseName,
                'createPerson' => $createPerson,
                'posts' => $lessonLst,
                'pages' => $pages,
                'teacher_class'=>$teacher_class,
            ));
            //} else if (isset($_GET['lessonName'])) {
        } else if (isset($_GET['number'])) {
            //改课名
            $currentClass = Yii::app()->session['currentClass'];
            $sqlcurrentClass = TbClass::model()->find("classID = '$currentClass'");
            if (!isset($sqlcurrentClass)) {
                $sqlcurrentClass = "none";
            }
            //$lessonName = $_GET['lessonName'];
            $number = $_GET['number'];
            $newName = $_GET['newName'];
            $courseID = $_GET ['courseID'];
            $classID=$_GET ['classID'];
            error_log($newName);
            $SqlclassID = TbClass::model()->findAll("currentCourse ='$courseID'");
//            foreach ($teacher_class as $classA) {
//                foreach($SqlclassID as $classB){
//                    if($classA['classID']==$classB['classID']){
//                        $classID=$classA['classID'];
//                        error_log($classID);
//                        break;
//                    }
//                }
//            }
            //$sql = "UPDATE `lesson` SET `lessonName`= '$newName' WHERE lessonName= '$lessonName'";
            $sql = "UPDATE `lesson` SET `lessonName`= '$newName' WHERE number= '$number' and courseID='$courseID'and classID='$classID'";
            Yii::app()->db->createCommand($sql)->query();
            $result = Lesson::model()->getLessonLst("", "", $courseID,$classID);
            $sqlCourse = Course::model()->find("courseID = '$courseID'");
            $courseName = $sqlCourse['courseName'];
            $createPerson = $sqlCourse['createPerson'];
            $lessonLst = $result ['lessonLst'];
            $pages = $result ['pages'];

            $this->render('scheduleDetil', array(
                'teacher' => $sqlTeacher, 'result' => "", 'array_class' => $array_class,
                'array_course' => $array_course, 'sqlcurrentClass' => $sqlcurrentClass,
                'courseID' => $courseID,
                'courseName' => $courseName,
                'createPerson' => $createPerson,
                'posts' => $lessonLst,
                'pages' => $pages,
                'teacher_class'=>$teacher_class,
            ));
        } else {
            $currentClass = Yii::app()->session['currentClass'];
            $sqlcurrentClass = TbClass::model()->find("classID = '$currentClass'");
            if (!isset($sqlcurrentClass)) {
                $sqlcurrentClass = "none";
            }
            if(isset($_GET['courseID'])){
                $courseID=$_GET['courseID'];
            }else{
                $courseID="";
            }
            $result = Lesson::model()->getLessonLst("", "", $courseID,$currentClass);
            $sqlCourse = Course::model()->find("courseID = '$courseID'");
            $courseName = $sqlCourse['courseName'];
            $createPerson = $sqlCourse['createPerson'];
            $lessonLst = $result ['lessonLst'];
            $pages = $result ['pages'];
            //查询老师课程表
            $teaResult = ScheduleTeacher::model()->findAll("userID='$teacherID'");
            return $this->render('scheduleDetil', ['teacher' => $sqlTeacher, 'result' => $teaResult, 'array_class' => $array_class, 'courseID' => $courseID,
                'courseName' => $courseName,'pages' => $pages,'createPerson' => $createPerson,'posts' => $lessonLst, 'array_course' => $array_course, 'teacher_class'=>$teacher_class,'sqlcurrentClass' => $sqlcurrentClass]);
        }
    }

    public function actionEditSchedule() {
        $sequence = $_GET['sequence'];
        $day = $_GET['day'];
        if (isset($_GET['classID'])) {
            $currentClass = Yii::app()->session['currentClass'];
            $sql = "SELECT * FROM schedule_class WHERE classID = '$currentClass' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();
        } else {
            $teacherID = Yii::app()->session['userid_now'];

            $sql = "SELECT * FROM schedule_teacher WHERE userID = '$teacherID' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();
        }
        return $this->renderPartial('editSchedule', ['result' => $sqlSchedule]);
    }

    public function Actionshitup() {
        $userid = $_GET['userid'];
        $student = new Student();
        $student = Student::model()->find("userID ='$userid'");
        $student->forbidspeak = '1';
        $student->update();
    }

    public function Actioncheckforbid() {
        $classID = $_GET['classID'];
        $result = Student::model()->getForbidStuByClass($classID);
        $stuLst = $result ['stuLst'];
        $pages = $result ['pages'];
        $this->renderPartial('forbidStuLst', array(
            'stuLst' => $stuLst,
            'pages' => $pages,
            'classID' => $classID
        ));
    }

    public function ActionrecoverForbidStu() {
        $Stulist = $_POST['checkbox'];
        foreach ($Stulist as $v) {
            $thisStu = new Student ();
            $thisStu = $thisStu->find("userID = '$v'");
            $thisStu->forbidspeak = '0';
            $thisStu->update();
        }
        $classID = $_GET['classID'];
        $result = Student::model()->getForbidStuByClass($classID);
        $stuLst = $result ['stuLst'];
        $pages = $result ['pages'];
        $this->renderPartial('forbidStuLst', array(
            'stuLst' => $stuLst,
            'pages' => $pages,
            'classID' => $classID
        ));
    }

    public function ActionGetProgress() {
        session_start();
        $i = ini_get('session.upload_progress.name');
        $key = ini_get("session.upload_progress.prefix") . $_GET[$i];
        if (!empty($_SESSION[$key])) {
            $current = $_SESSION[$key]["bytes_processed"];
            $total = $_SESSION[$key]["content_length"];
            echo $current < $total ? ceil($current / $total * 100) : 100;
        } else {
            echo 100;
        }
    }

    public function ActionAssignFreePractice() {
        $res = 0;
        $ClassID = $_GET['classID'];
        $deleteresult = 0;
        if (isset($_GET['progress'])) {
            $number = $_GET['progress'];
            Yii::app()->session['progress'] = $_GET['progress'];
            $LessonID = Lesson::model()->find("classID='$ClassID' and number = '$number'")['lessonID'];
        } else if (!isset($_GET['all'])) {
            $LessonID = $_GET['lessonID'];
        }
        if (isset($_GET['isOpen'])) {
            $title = $_GET['title'];
            $up_res = ClassExercise::model()->find("classID='$ClassID' and title = '$title'");
            $up_res->is_open = $_GET['isOpen'];
            $up_res->update();
        }
        if (isset($_GET['delete'])) {
            $title = $_GET['delete'];
            $deleteresult = ClassExercise::model()->deleteAll("classID='$ClassID' and title = '$title'");
        }
        $teacherID = Yii::app()->session['userid_now'];
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        $array_lesson = array();
        $array_class = array();
        if (isset($_GET['all'])) {
            $result = ClassExercise::model()->getAllSuiteByPage(10, $teacherID, $ClassID);
            $array_allpractice = $result['suiteLst'];
        } else {
            $result = ClassExercise::model()->getAllSuiteByPageWithLessonID(10, $teacherID, $LessonID, $ClassID);
            $array_allpractice = $result['suiteLst'];
        }


        $pages = $result['pages'];
        if (!empty($teacher_class)) {
            if (isset($_GET['classID']))
                Yii::app()->session['currentClass'] = $_GET['classID'];
            else
                Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];
            foreach ($teacher_class as $class) {
                $id = $class['classID'];
                $result = TbClass::model()->find("classID ='$id'");
                array_push($array_class, $result);
            }
            $currentClass = Yii::app()->session['currentClass'];
            $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            if (!empty($array_lesson)) {
                if (isset($_GET['lessonID']))
                    Yii::app()->session['currentLesson'] = $_GET['lessonID'];
                else
                if (isset($_GET['progress'])) {
                    $number = $_GET['progress'];
                    $currentLesson = Lesson::model()->find("classID='$currentClass' and number='$number'");
                    Yii::app()->session['currentLesson'] = $currentLesson['lessonID'];
                } else if (isset($_GET['on'])) {
                    $number = $_GET['on'];
                    Yii::app()->session['currentLesson'] = Lesson::model()->find("classID='$currentClass' and number='$number'")['lessonID'];
                } else {
                    Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
                }
            }
        }

        $this->render('assignFreePractice', array(
            'deleteresult' => $deleteresult,
            'array_class' => $array_class,
            'array_lesson' => $array_lesson,
            'array_allpractice' => $array_allpractice,
            'pages' => $pages,
            'res' => $res
        ));
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

    public function ActionAddFreePractice() {
        $res = 0;
        $title = $_POST['title'];
        $classID = $_GET['classID'];
        $type = $_POST['type'];
        $all = 'no';
        $arrayPractice = ClassExercise::model()->findAll("classID = '$classID'");
        if (isset($_GET['all'])) {
            $all = 'all';
        }
        foreach ($arrayPractice as $v) {
            if ($v['title'] == $title) {
                $res = 1;
            }
        }
        if (isset($_GET['progress'])) {
            $number = $_GET['progress'];
            $LessonID = Lesson::model()->find("classID='$classID' and number = '$number'")['lessonID'];
        } else if (!isset($_GET['all'])) {
            $LessonID = $_GET['lessonID'];
        }
        $teacherID = Yii::app()->session['userid_now'];
        $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
        $array_lesson = array();
        $array_class = array();
        if (isset($_GET['all'])) {
            $result = ClassExercise::model()->getAllSuiteByPage(10, $teacherID, $classID);
            $array_allpractice = $result['suiteLst'];
        } else {
            $result = ClassExercise::model()->getAllSuiteByPageWithLessonID(10, $teacherID, $LessonID, $classID);
            $array_allpractice = $result['suiteLst'];
        }
        $pages = $result['pages'];
        if (!empty($teacher_class)) {
            if (isset($_GET['classID']))
                Yii::app()->session['currentClass'] = $_GET['classID'];
            else
                Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];
            foreach ($teacher_class as $class) {
                $id = $class['classID'];
                $result = TbClass::model()->find("classID ='$id'");
                array_push($array_class, $result);
            }
            $currentClass = Yii::app()->session['currentClass'];
            $array_lesson = Lesson::model()->findAll("classID = '$currentClass'");
            if (!empty($array_lesson)) {
                if (isset($_GET['lessonID']))
                    Yii::app()->session['currentLesson'] = $_GET['lessonID'];
                else
                if (isset($_GET['progress'])) {
                    $number = $_GET['progress'];
                    $currentLesson = Lesson::model()->find("classID='$currentClass' and number='$number'");
                    Yii::app()->session['currentLesson'] = $currentLesson['lessonID'];
                } else if (isset($_GET['on'])) {
                    $number = $_GET['on'];
                    Yii::app()->session['currentLesson'] = Lesson::model()->find("classID='$currentClass' and number='$number'")['lessonID'];
                } else {
                    Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
                }
            }
        }
        if ($res == 1) {
            $this->render('assignFreePractice', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'array_allpractice' => $array_allpractice,
                'pages' => $pages,
                'res' => $res
            ));
        } else {
            if (isset($_GET['progress'])) {
                $number = $_GET['progress'];
                $LessonID = Lesson::model()->find("classID='$classID' and number = '$number'")['lessonID'];
            } else if (!isset($_GET['all'])) {
                $LessonID = $_GET['lessonID'];
            } else {
                $LessonID = "";
            }
            $this->render('addFreePractice', array(
                'array_class' => $array_class,
                'array_lesson' => $array_lesson,
                'title' => $title,
                'all' => $all,
                'classID' => $classID,
                'LessonID' => $LessonID,
                'type' => $type));
        }
    }

    public function ActiongetExercise() {
        if (isset($_POST['suiteID'])) {
            $suiteID = $_POST['suiteID'];
            $array_exercise = SuiteExercise::model()->findAll("suiteID='$suiteID'");
            $array_result = array();
            foreach ($array_exercise as $exercise) {
                if ($exercise['type'] == 'key') {
                    $exerciseID = $exercise['exerciseID'];
                    $result = KeyType::model()->findAll("exerciseID = '$exerciseID'");
                    $result['suiteID'] = $exercise['suiteID'];
                    array_push($array_result, $result);
                } else
                if ($exercise['type'] == 'listen') {
                    $exerciseID = $exercise['exerciseID'];
                    $result = ListenType::model()->findAll("exerciseID = '$exerciseID'");
                    $result['suiteID'] = $exercise['suiteID'];
                    array_push($array_result, $result);
                } else
                if ($exercise['type'] == 'look') {
                    $exerciseID = $exercise['exerciseID'];
                    $result = LookType::model()->findAll("exerciseID = '$exerciseID'");
                    $result['suiteID'] = $exercise['suiteID'];
                    array_push($array_result, $result);
                }
            }
        }
        $this->renderJSON($array_result);
    }

    public function ActionDeleteWordLib() {
        $Libs = $_POST['libs'];
        $count = 0;
        foreach ($Libs as $v) {
            $Lib = TwoWordsLibPersonal::model()->findAll("name = '$v'");
            if (count($Lib) > 0) {
                $count++;
                foreach ($Lib as $l) {
                    $l->delete();
                }
            }
        }
        if ($count > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ActionSelectWordLib() {
        $uploadResult = "";
        $libstr = $_GET['libstr'];
        $libstr = explode('$$', $libstr);
        $sql = "SELECT DISTINCT name,list FROM two_words_lib WHERE list != '总复习' ORDER BY name";
        $result = Yii::app()->db->createCommand($sql)->query();
        $sql_1 = "SELECT DISTINCT name,list FROM two_words_lib WHERE list = '总复习' ORDER BY name";
        $result_1 = Yii::app()->db->createCommand($sql_1)->query();
        $list = array();
        $createPerson = Yii::app()->session['userid_now'];
        $sql4Personal = "select distinct name,list from two_words_lib_personal WHERE createPerson LIKE '$createPerson'";
        $resulet4Personal = Yii::app()->db->createCommand($sql4Personal)->query();
        foreach ($resulet4Personal as $v) {
            array_push($list, $v);
        }
        foreach ($result as $res) {
            if ($res['name'] != "lib")
                array_push($list, $res);
        }
        foreach ($result_1 as $res) {
            array_push($list, $res);
        }
        if (isset($_GET['upload'])) {
            if (!empty($_FILES ['file'] ['name'])) {
                $tmp_file = $_FILES ['file'] ['tmp_name'];
                $file_types = explode(".", $_FILES ['file'] ['type']);
                $file_type = $file_types [count($file_types) - 1];
                // 判别是不是excel文件
                if (strtolower($file_type) != "text/plain") {
                    $uploadResult = '不是txt文件';
                } else {
                    // 解析文件并存入数据库逻辑
                    /* 设置上传路径 */
                    $savePath = dirname(Yii::app()->BasePath) . '\\public\\upload\\txt\\';
                    $file_name = "-" . $_FILES ['file'] ['name'] . "-";
                    if (!copy($tmp_file, $savePath . $file_name)) {
                        $uploadResult = '上传失败';
                    } else {
                        $file_dir = $savePath . $file_name;
                        $file_dir = str_replace("\\", "\\\\", $file_dir);
                        $fp = fopen($file_dir, "r");
                        if (filesize($file_dir) < 1) {
                            $uploadResult = '空文件，上传失败';
                        } else {
                            $content = fread($fp, filesize($file_dir)); //读文件 
                            fclose($fp);
                            unlink($file_dir);
                            $content = str_replace("\n", "\r\n", $content);
                            $content = str_replace("\r", "\r\n", $content);
                            $content = str_replace(" ", "\r\n", $content);
                            $content = rtrim($content);
                            $str = explode("\r\n", $content);
                            $name = str_replace(".txt", "", $file_name);
                            $createPerson = Yii::app()->session['userid_now'];
                            foreach ($str as $value) {
                                $words = iconv('GBK', 'utf-8', $value);
                                $strSerchFromLib = TwoWordsLib::model()->find("words LIKE '$words' AND list = 'lib'");
                                $spell = $strSerchFromLib['spell'];
                                $yaweiCode = $strSerchFromLib['yaweiCode'];
                                TwoWordsLibPersonal::model()->insertPersonalLib($spell, $yaweiCode, $words, $name, $createPerson);
                            }
                            $uploadResult = '上传成功';
                        }
                    }
                }
            }
        }
        $this->renderPartial('wordLibLst', array(
            'list' => $list,
            'libstr' => $libstr,
            'uploadResult' => $uploadResult
        ));
    }

    public function actionClassExercise4Look() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_GET['delete'])) {
            $exerciseID = $_GET['exerciseID'];
            ClassExercise::model()->deleteExercise($exerciseID);
        }
        $classID = $_GET['classID'];
        $number = $_GET['on'];
        $sqlClassExercise = Lesson::model()->find("classID = '$classID' and number = '$number'");
        $result = ClassExercise::model()->getLookLst("type", "look", $sqlClassExercise['lessonID']);
        $lookLst = $result['lookLst'];
        $pages = $result['pages'];
        $this->render('classExercise4Look', array(
            'lookLst' => $lookLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionClassExercise4Listen() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_GET['delete'])) {
            $exerciseID = $_GET['exerciseID'];
            ClassExercise::model()->deleteExercise($exerciseID);
        }
        $classID = $_GET['classID'];
        $number = $_GET['on'];
        $sqlClassExercise = Lesson::model()->find("classID = '$classID' and number = '$number'");
        $result = ClassExercise::model()->getListenLst("type", "listen", $sqlClassExercise['lessonID']);
        $listenLst = $result['listenLst'];
        $pages = $result['pages'];
        $this->render('classExercise4Listen', array(
            'listenLst' => $listenLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionClassExercise4Type() {
        if (isset($_GET['page'])) {
            Yii::app()->session['lastPage'] = $_GET['page'];
        } else {
            Yii::app()->session['lastPage'] = 1;
        }
        if (isset($_GET['delete'])) {
            $exerciseID = $_GET['exerciseID'];
            ClassExercise::model()->deleteExercise($exerciseID);
        }
        $classID = $_GET['classID'];
        $number = $_GET['on'];
        $sqlClassExercise = Lesson::model()->find("classID = '$classID' and number = '$number'");
        $result = ClassExercise::model()->getKeyLst($sqlClassExercise['lessonID']);
        $keyLst = $result['keyLst'];
        $pages = $result['pages'];
        $this->render('classExercise4Type', array(
            'keyLst' => $keyLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionAddLook4ClassExercise() {
        $result = 'no';
        $classID = $_GET['classID'];
        $on = $_GET['on'];
        if (isset($_POST['title'])) {
            $sqlLesson = Lesson::model()->find("classID = '$classID' and number = '$on'");
            $newContent = Tool::SBC_DBC($_POST['content'], 0);
            $content4000 = Tool::spliceLookContent($newContent);
            $contentNoSpace = Tool::filterAllSpaceAndTab($content4000);
            if(isset($_POST['checkbox'])){
                $title = $_POST['title']."-不提示略码";
                $result = ClassExercise::model()->insertClassExercise($classID, $sqlLesson['lessonID'], Tool::filterAllSpaceAndTab($title), $contentNoSpace, 'look', Yii::app()->session['userid_now']);
            }else{
                $result = ClassExercise::model()->insertClassExercise($classID, $sqlLesson['lessonID'], Tool::filterAllSpaceAndTab($_POST['title']), $contentNoSpace, 'look', Yii::app()->session['userid_now']);
            }
        }
        $this->render('addLook4ClassExercise', ['result' => $result]);
    }

    public function actionAddKey4ClassExercise() {
        $result = 'no';
        $classID = $_GET['classID'];
        $on = $_GET['on'];
        $content = "";
        if (isset($_POST['title'])) {
            $sqlLesson = Lesson::model()->find("classID = '$classID' and number = '$on'");
            $libstr = $_POST['libstr'];
            $arr = explode("$$", $libstr);
            $condition = "";
            $conditionPersonal = "";
            foreach ($arr as $a) {
                $flag = substr($a, 0, 1);
                if ($flag == '-') {
                    if ($conditionPersonal == "")
                        $conditionPersonal = "'" . $a . "'";
                    else
                        $conditionPersonal = $conditionPersonal . "," . "'" . $a . "'";
                }else {
                    if ($condition == "")
                        $condition = "'" . $a . "'";
                    else
                        $condition = $condition . "," . "'" . $a . "'";
                }
            }
            if ($condition != "") {
                $condition = " where name in (" . $condition . ")";
                $sql = "select * from two_words_lib";
                $order = "";
                if ($arr[count($arr) - 1] == "lib") {
                    $order = "order by rand() limit " . $_POST['in1'];
                }
                $sql = $sql . $condition . $order;
                $res = Yii::app()->db->createCommand($sql)->query();
                foreach ($res as $record) {
                    if ($content != "")
                        $content = $content . "$$" . $record['yaweiCode'] . $record['words'];
                    else
                        $content = $record['yaweiCode'] . $record['words'];
                }
            }
            if ($conditionPersonal != "") {
                $conditionPersonal = " where name in (" . $conditionPersonal . ")";
                $sqlPersonal = "select * from two_words_lib_personal";
                $sqlPersonal = $sqlPersonal . $conditionPersonal;
                $resPersonal = Yii::app()->db->createCommand($sqlPersonal)->query();
                $conditionPersonal = "";
                foreach ($resPersonal as $v) {
                    if ($content != "")
                        $content = $content . "$$" . $v['yaweiCode'] . $v['words'];
                    else
                        $content = $v['yaweiCode'] . $v['words'];
                }
            }
            if(isset($_POST['free'])) {
                $title = Tool::filterAllSpaceAndTab($_POST['title']).'-自由练习';
                $category = 'free';
                $result = ClassExercise::model()->insertKey($classID, $sqlLesson['lessonID'], $title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
            if(isset($_POST['correct'])) {
                $title = Tool::filterAllSpaceAndTab($_POST['title']).'-准确率练习';
                $category = 'correct';
                $result = ClassExercise::model()->insertKey($classID, $sqlLesson['lessonID'], $title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
            if(isset($_POST['speed'])) {
                $title = Tool::filterAllSpaceAndTab($_POST['title']).'-速度练习';
                $category = 'speed';
                $result = ClassExercise::model()->insertKey($classID, $sqlLesson['lessonID'], $title, $content, Yii::app()->session['userid_now'], $category, $_POST['speed1'], $_POST['in3'], $libstr);
            }
            //$result = ClassExercise::model()->insertKey($classID, $sqlLesson['lessonID'], Tool::filterAllSpaceAndTab($_POST['title']), $content, Yii::app()->session['userid_now'], $_POST['category'], $_POST['speed'], $_POST['in3'], $libstr);
        }
        $this->render('addKey4ClassExercise', ['result' => $result]);
    }

    public function actionAddListen4ClassExercise() {
        $result = 'no';
        $classID = $_GET['classID'];
        $on = $_GET['on'];
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $filePath = $typename . "/" . $userid . "/";
        $dir = "resources/" . $filePath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $title = "";
        $content = "";
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $content = $_POST["content"];
            if ($_FILES ['file'] ['type'] != "audio/mpeg" &&
                    $_FILES ['file'] ['type'] != "audio/wav" &&
                    $_FILES ['file'] ['type'] != "audio/x-wav") {
                $result = '文件格式不正确，应为MP3或WAV格式';
            } else if ($_FILES['file']['error'] > 0) {
                $result = '文件上传失败';
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRela($newName, $oldName);
                $sqlLesson = Lesson::model()->find("classID = '$classID' and number = '$on'");
                $result = ClassExercise::model()->insertListen($classID, $sqlLesson['lessonID'], Tool::filterAllSpaceAndTab($_POST['title']), $_POST['content'], $newName, $filePath, "listen", Yii::app()->session['userid_now']);
                $result = '1';
            }
        }
        $this->render('addListen4ClassExercise', array(
            'result' => $result,
            'title' => $title,
            'content' => $content
        ));
    }

    public function actionEditLook4ClassExercise() {
        $exerciseID = $_GET["exerciseID"];
        $update = 0;
        if (isset($_POST['title'])) {
            if(isset($_POST['checkbox'])){
                
                if(strpos($_POST['title'],"-不提示略码")){
                    $title = $_POST['title'];
                }else{
                    $title = $_POST['title']."-不提示略码";
                }
            }else{
                $title = str_replace("-不提示略码", "", $_POST['title']);
//                $title = $_POST['title'];
            }
            $newContent = Tool::SBC_DBC($_POST['content'], 0);
            $content4000 = Tool::spliceLookContent($newContent);
            $update = ClassExercise::model()->updateLook($exerciseID, $title, $content4000);
        }

        $result = ClassExercise::model()->getExerciseByType($exerciseID, "look")->read();
        $this->render("editLook4ClassExercise", array(
            'exerciseID' => $exerciseID,
            'title' => $result['title'],
            'content' => $result['content'],
            'result' => $update
        ));
    }

    public function actionEditType4ClassExercise() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM class_exercise WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();

        if (!isset($_GET['action'])) {
            $this->render("editKey4ClassExercise", array(
                'exerciseID' => $exerciseID,
                'key' => $result,
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editKey4ClassExercise", array(
                'exerciseID' => $exerciseID,
                'action' => 'look',
                'key' => $result,
            ));
        }
    }

    public function actionEditType4ClassExerciseInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisKey = new ClassExercise();
        $thisKey = $thisKey->find("exerciseID = '$exerciseID'");
        $libstr = $_POST['libstr'];
        $arr = explode("$$", $libstr);
        $condition = "";
        foreach ($arr as $a) {
            if ($condition == "")
                $condition = "'" . $a . "'";
            else
                $condition = $condition . "," . "'" . $a . "'";
        }
        $condition = " where name in (" . $condition . ")";
        $sql = "select * from two_words_lib";
        $order = "";
        if ($arr[count($arr) - 1] == "lib") {
            $order = "order by rand() limit " . $_POST['in1'];
        }
        $sql = $sql . $condition . $order;
        $res = Yii::app()->db->createCommand($sql)->query();
        $content = "";
        foreach ($res as $record) {
            if ($content != "")
                $content = $content . "$$" . $record['yaweiCode'] . $record['words'];
            else
                $content = $record['yaweiCode'] . $record['words'];
        }
        $thisKey->title = $_POST['title'];
        $thisKey->content = $content;
        $thisKey->type = $_POST['category'];
        $thisKey->speed = $_POST['speed'];
        $thisKey->update();



        $this->render("editKey4ClassExercise", array(
            'key' => $thisKey,
            'exerciseID' => $exerciseID,
            'result' => "修改习题成功"));
    }

    public function actionEditListen4ClassExercise() {
        $result = "";
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $filePath = $typename . "/" . $userid . "/";
        $dir = "resources/" . $filePath;
        $exerciseID = $_GET['exerciseID'];
        $thisListen = new ClassExercise ();
        $thisListen = $thisListen->find("exerciseID = '$exerciseID' and type = 'listen'");
        if (isset($_GET['oldfilename'])) {
            $filename = $_GET['oldfilename'];

            if ($_FILES['modifyfile']['tmp_name']) {
                if ($_FILES ['modifyfile'] ['type'] != "audio/mpeg" &&
                        $_FILES ['modifyfile'] ['type'] != "audio/wav" &&
                        $_FILES ['modifyfile'] ['type'] != "audio/x-wav") {
                    $result = '文件格式不正确，应为MP3或WAV格式';
                } else if ($_FILES['modifyfile']['error'] > 0) {
                    $result = '文件上传失败';
                } else {
                    $newName = Tool::createID() . "." . pathinfo($_FILES["modifyfile"]["name"], PATHINFO_EXTENSION);
                    move_uploaded_file($_FILES["modifyfile"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                    if (file_exists($dir . iconv("UTF-8", "gb2312", $filename)))
                        unlink($dir . iconv("UTF-8", "gb2312", $filename));
                    Resourse::model()->replaceRela($filename, $newName, $_FILES ["modifyfile"] ["name"]);
                    $thisListen->file_name = $newName;
                    $thisListen->file_path=$filePath;
                    $result = "上传成功";
                }
            }
            if ($result === "" || $result == "上传成功") {
                $thisListen->title = $_POST ['title'];
                $thisListen->content = $_POST ['content'];
                $thisListen->update();
                $result = "修改成功!";
            } else {
                $result = "修改失败!";
            }
        }
        $this->render("editListen4ClassExercise", array(
            'exerciseID' => $thisListen->exerciseID,
            'filename' => $thisListen->file_name,
            'filepath' => $thisListen->file_path,
            'title' => $thisListen->title,
            'content' => $thisListen->content,
            'result' => $result
        ));
    }

    public function actionTableClassExercise4virtual() {
        $number = $_GET['on'];
        $classID = $_GET['classID'];
        $lessonID = Lesson::model()->find("classID = '$classID' AND number = '$number'")['lessonID'];
        $classExerciseLst = ClassExercise::model()->findAll("classID = '$classID' AND lessonID = '$lessonID'");
        $this->renderPartial("tableClassExercise4virtual", ['classExerciseLst' => $classExerciseLst]);
    }

    public function actionTableClassExercise4Analysis() {
        $classID = $_GET['classID'];
        $allIsOpen = Array();
        $exerciseID = $_GET['exerciseID'];
        $ClassExercise = ClassExercise::model()->getByExerciseID($exerciseID);
        $result = ClassExercise::model()->getAllNowOpenExercise($classID);
        foreach ($result as $v) {
            if ($v['exerciseID'] != $exerciseID) {
                array_push($allIsOpen, $v);
            }
        }
        $this->renderPartial("tableClassExercise4Analysis", ['ClassExercise' => $ClassExercise, 'AllIsOpen' => $allIsOpen]);
    }

    public function actionCloseAllOpenExerciseNow() {
        ClassExercise::model()->closeAllOpenExerciseNow();
        echo "关闭成功！";
    }

    public function actionOpenClassExercise4lot() {
        $allClassExercise = $_POST['check'];
        $arrayClassExercise = explode("&", $allClassExercise);
        foreach ($arrayClassExercise as $exerciseID) {
            if ($exerciseID !== "") {
                ClassExercise::model()->openExercise($exerciseID);
                ClassExercise::model()->openExerciseNow($exerciseID);
            }
        }
        if ($allClassExercise !== "") {
            $data = '开放成功！';
        } else {
            $data = '未选中任何内容';
        }
        echo $data;
    }

    public function actionOpenClassExercise() {
        $exerciseID = $_POST['exerciseID'];
        ClassExercise::model()->openExerciseNow($exerciseID);
        if ($exerciseID !== "") {
            $data = 1;
        } else {
            $data = 0;
        }
        echo $data;
    }

    public function actionGetVirtualClassAnalysis() {
        $arrayData = Array();
        $data = Array();
        $exerciseID = $_POST['exerciseID'];
        $title = ClassExercise::model()->getByExerciseID($exerciseID)['title'];
        $classID = $_POST['classID'];
        $sqlStudent = Student::model()->findStudentByClass($classID);
        foreach ($sqlStudent as $v) {
            $studentID = $v['userID'];
            $studentName = $v['userName'];
            $recourd = ClassexerciseRecord::model()->getSingleRecord($studentID, $exerciseID);
            $ratio_speed = explode("&", $recourd['ratio_speed']);
            $ratio_maxSpeed = explode("&", $recourd['ratio_maxSpeed']);
            $ratio_correct = explode("&", $recourd['ratio_correct']);
            $all_count = explode("&", $recourd['ratio_countAllKey']);
            $squence=$recourd['squence'];
            $end = count($ratio_speed) - 1;
            $speed = (int) $ratio_speed[$end];
            $maxSpeed = (int) $ratio_maxSpeed[$end];
            $correct = round($ratio_correct[$end] * 100) / 100;
            $time = count($ratio_speed) * 5 - 5;
            $allKey = (int) $all_count[$end];
            $arrayData = ["studentID" => $studentID, "studentName" => $studentName, "speed" => $speed, "maxSpeed" => $maxSpeed, "correct" => $correct, "time" => $time, "allKey" => $allKey, "title" => $title, "squence"=>$squence];
            array_push($data, $arrayData);
        }
        $data = Tool::quickSort($data, "correct");
        $this->renderJSON($data);
    }

    public function actionExport() {
        $choice = $_GET['choice'];
        $seq = $_GET['seq'];
        $classID = $_GET['classID'];
        $exerciseID = $_GET['exerciseID'];
        $type = $_GET['type'];
        $id = $_GET['id'];

        ////*////
        $all = Array();
        if ($type == 1) {
            $type = 'speed';
        } else if ($type == 2) {
            $type = 'listen';
        } else if ($type == 3) {
            $type = 'look';
        } else if ($type == 4) {
            $type = 'correct';
        } else if ($type == 5) {
            $type = 'free';
        }
        $all = ClassexerciseRecord::model()->findAll('classExerciseID=?', array($exerciseID));
        $allStudent = Student::model()->findAll('classID=?', array($classID));
        $all2 = Array();
        $all3 = Array();
        $arrayData = Array();
        $arrayData2 = Array();
        $arrayData3 = Array();
        $arrayData4 = Array();
        $arrayDetail = Array();
        $arrayDetailData = Array();
        $data2 = Array();
        $data3 = Array();
        $allData = Array();
        $myData = Array();
        $myDataAll = Array();
        foreach ($allStudent as $allStu) {
            $key = 0;
            $n = 0;
            $all2 = Array();
            foreach ($all as $al) {
                if ($al['studentID'] == $allStu['userID']) {
                    $n++;
                    array_push($all2, $al);
                    //$key++;
                }
            }
            if ($n != 0 && $allStu['userID'] == $id) {
                $nn = Array();
                $nn = ["sequence" => $n, "a" => $n];
                array_push($allData, $nn);
            }
            if ($n != 0)
                array_push($all3, $all2);
        }

        $studentName = '';
        $data = Array();
        $averageData = Array();
        $maxData = Array();
        $nn1 = 0;
        $nn2 = 0;
        $nn3 = 0;
        $nn4 = 0;
        $nn5 = 0;
        $nn6 = 0;
        $nn7 = 0;
        $nn8 = 0;
        $f1 = 0;
        $f2 = 0;
        $f3 = 0;
        $f4 = 0;
        $f5 = 0;
        $f6 = 0;
        $f7 = 0;
        $f8 = 0;
        $f9 = 0;

        foreach ($all3 as $al) {
            $f = 0;
            $n1 = 0;
            $n2 = 0;
            $n3 = 0;
            $n4 = 0;
            $n5 = 0;
            $n6 = 0;
            $n7 = 0;
            $n8 = 0;
            $icon1 = 0;
            $icon2 = 0;
            $icon3 = 0;
            $icon4 = 0;
            $icon5 = 0;
            $icon6 = 0;
            $icon7 = 0;
            $icon8 = 0;
            $icon9 = 0;
            $ff1 = 0;
            $ff2 = 0;
            $ff3 = 0;
            $ff4 = 0;
            $ff5 = 0;
            $ff6 = 0;
            $ff7 = 0;
            $ff8 = 0;
            $ff9 = 0;
            $i1 = 0;
            $s = 0;
            foreach ($al as $a) {
                if ($a['studentID'] == $id) {
                    $i1++;
                }
                $correct = $a['ratio_correct'];       //correct
                if (strpos($correct, "&") === false) {
                    $correct = $correct . "&" . $correct;
                }
                $n = strrpos($correct, "&");
                $correct = substr($correct, $n + 1);
                $n1+=$correct;
                $nn1+=$correct;
                if ($f1 < $correct) {
                    $f1 = $correct;
                }
                if ($correct >= $ff1 && $a['studentID'] == $id) {
                    $icon1 = $i1;
                    $ff1 = $correct;
                }

                $speed = $a['ratio_speed'];        //speeed
                if (strpos($speed, "&") === false) {
                    $speed = $speed . "&" . $speed;
                }
                $n = strrpos($speed, "&");
                $speed = substr($speed, $n + 1);
                $n2+=$speed;
                $nn2+=$speed;
                if ($f2 < $speed) {

                    $f2 = $speed;
                }
                if ($speed >= $ff2 && $a['studentID'] == $id) {
                    $ff2 = $speed;
                    $icon2 = $i1;
                }

                $maxSpeed = $a['ratio_maxSpeed'];     //maxSpeed
                if (strpos($maxSpeed, "&") === false) {
                    $maxSpeed = $maxSpeed . "&" . $maxSpeed;
                }
                $n = strrpos($maxSpeed, "&");
                $maxSpeed = substr($maxSpeed, $n + 1);
                $n3+=$maxSpeed;
                $nn3+=$maxSpeed;
                if ($f3 < $maxSpeed) {

                    $f3 = $maxSpeed;
                }
                if ($maxSpeed >= $ff3 && $a['studentID'] == $id) {
                    $ff3 = $maxSpeed;
                    $icon3 = $i1;
                }

                $backDelete = $a['ratio_backDelete'];     //backDelete
                if (strpos($backDelete, "&") === false) {
                    $backDelete = $backDelete . "&" . $backDelete;
                }
                $n = strrpos($backDelete, "&");
                $backDelete = substr($backDelete, $n + 1);
                $n4+=$backDelete;
                $nn4+=$backDelete;
                if ($f4 < $backDelete) {

                    $f4 = $backDelete;
                }
                if ($backDelete >= $ff4 && $a['studentID'] == $id) {
                    $ff4 = $backDelete;
                    $icon4 = $i1;
                }

                $maxInternalTime = $a['ratio_maxInternalTime'];        //maxInternalTime
                if (strpos($maxInternalTime, "&") === false) {
                    $maxInternalTime = $maxInternalTime . "&" . $maxInternalTime;
                }
                $n = strrpos($maxInternalTime, "&");
                $maxInternalTime = substr($maxInternalTime, $n + 1);
                $n5+=$maxInternalTime;
                $nn5+=$maxInternalTime;
                if ($f5 < $maxInternalTime) {

                    $f5 = $maxInternalTime;
                }
                if ($maxInternalTime >= $ff5 && $a['studentID'] == $id) {
                    $ff5 = $maxInternalTime;
                    $icon5 = $i1;
                }

                $averageKeyType = $a['ratio_averageKeyType'];
                if (strpos($averageKeyType, "&") === false) {
                    $averageKeyType = $averageKeyType . "&" . $averageKeyType;
                }
                $n = strrpos($averageKeyType, "&");
                $averageKeyType = substr($averageKeyType, $n + 1);
                $n6+=$averageKeyType;
                $nn6+=$averageKeyType;
                if ($f6 < $averageKeyType) {

                    $f6 = $averageKeyType;
                }
                if ($averageKeyType >= $ff6 && $a['studentID'] == $id) {
                    $ff6 = $averageKeyType;
                    $icon6 = $i1;
                }

                $maxKeyType = $a['ratio_maxKeyType'];
                if (strpos($maxKeyType, "&") === false) {
                    $maxKeyType = $maxKeyType . "&" . $maxKeyType;
                }
                $n = strrpos($maxKeyType, "&");
                $maxKeyType = substr($maxKeyType, $n + 1);
                $n7+=$maxKeyType;
                $nn7+=$maxKeyType;
                if ($f7 < $maxKeyType) {

                    $f7 = $maxKeyType;
                }
                if ($maxKeyType >= $ff7 && $a['studentID'] == $id) {
                    $ff7 = $maxKeyType;
                    $icon7 = $i1;
                }

                $countAllKey = $a['ratio_countAllKey'];
                if (strpos($countAllKey, "&") === false) {
                    $countAllKey = $countAllKey . "&" . $countAllKey;
                }
                $n = strrpos($countAllKey, "&");
                $countAllKey = substr($countAllKey, $n + 1);
                $n8+=$countAllKey;
                $nn8+=$countAllKey;
                if ($f8 < $countAllKey) {

                    $f8 = $countAllKey;
                }
                if ($countAllKey >= $ff8 && $a['studentID'] == $id) {
                    $ff8 = $countAllKey;
                    $icon8 = $i1;
                }
                $finishDate = $a['finishDate'];          //finishDate
                if ($f9 == 0)
                    $f9 = $finishDate;
                if ($f9 > $finishDate) {
                    $f9 = $finishDate;
                }
                if ($finishDate >= $ff9 && $a['studentID'] == $id) {
                    $ff9 = $finishDate;
                    $icon9 = $i1;
                }
                $studentName = Student::model()->find('userID=?', array($a['studentID']))['userName'];
                $f++;
                if ($a['studentID'] == $id) {
                    $s++;
                    $arrayDetail = ["s" => $s, "correct" => $correct, "speed" => $speed,
                        "backDelete" => $backDelete, 'averageKeyType' => $averageKeyType, 'finishDate' => $finishDate, 'countAllKey' => $countAllKey];
                    array_push($arrayDetailData, $arrayDetail);
                }
            }
        }
        $maxData = ["high" => "最高", "correct" => $f1, "speed" => $f2, "backDelete" => $f4,
            'averageKeyType' => $f6, "finishDate" => $f9, "countAllKey" => $f8];
        array_push($arrayDetailData, $maxData);
        //array_push($allData, $arrayDetailData);
        //array_push($allData, $maxData);
        ////*////
        return $this->renderPartial('01simple', ['arrayDetailData' => $arrayDetailData]);
    }

    public function actionExports() {
        $choice = $_GET['choice'];
        $workID = $_GET['workID'];
        $exerciseID = $_GET['exerciseID'];
        $type = $_GET['type'];
        $isExam = $_GET['isExam'];
        $name = $_GET['name'];
        ////////****/////
        $all = Array();
        if ($type == 1) {
            $type = 'key';
        } else if ($type == 2) {
            $type = 'listen';
        } else if ($type == 3) {
            $type = 'look';
        }
        $recordIDs = Array();
        if ($isExam == 1) {
            $recordIDs = ExamRecord::model()->findAll('workID=?', array($workID));
        } else {
            $recordIDs = SuiteRecord::model()->findAll('workID=?', array($workID));
        }
        $all = Array();
        foreach ($recordIDs as $ids) {
            $result = AnswerRecord::model()->find('type=? and exerciseID=? and isExam=? and recordID=?', array($type, $exerciseID, $isExam, $ids['recordID']));
            if ($result) {
                array_push($all, $result);
            }
        }
        $arrayData = Array();
        $arrayData2 = Array();
        $arrayData3 = Array();
        $arrayData4 = Array();
        $data = Array();
        $data2 = Array();
        $data3 = Array();
        $allData = Array();
        $myData = Array();
        $myDetail = Array();
        $averageData = Array();
        $maxData = Array();
        $n1 = 0;
        $n2 = 0;
        $n3 = 0;
        $n4 = 0;
        $n5 = 0;
        $n6 = 0;
        $n7 = 0;
        $n8 = 0;
        $f1 = 0;
        $f2 = 0;
        $f3 = 0;
        $f4 = 0;
        $f5 = 0;
        $f6 = 0;
        $f7 = 0;
        $f8 = 0;
        $f9 = 0;
        if ($all) {
            foreach ($all as $a) {
                //correct
                $correct = $a['ratio_correct'];
                $correct2 = $a['ratio_correct'];
                if (strpos($correct, "&") === false) {
                    $correct = $correct . "&" . $correct;
                }
                $n = strrpos($correct, "&");
                $correct = substr($correct, $n + 1);
                $n1+=$correct;
                if ($f1 < $correct) {
                    $f1 = $correct;
                }

                //speed
                $speed = $a['ratio_speed'];
                $speed2 = $a['ratio_speed'];
                if (strpos($speed, "&") === false) {
                    $speed = $speed . "&" . $speed;
                }
                $n = strrpos($speed, "&");
                $speed = substr($speed, $n + 1);
                $n2+=$speed;
                if ($f2 < $speed) {

                    $f2 = $speed;
                }

                //maxSpeed
                $maxSpeed = $a['ratio_maxSpeed'];
                $maxSpeed2 = $a['ratio_maxSpeed'];
                if (strpos($maxSpeed, "&") === false) {
                    $maxSpeed = $maxSpeed . "&" . $maxSpeed;
                }
                $n = strrpos($maxSpeed, "&");
                $maxSpeed = substr($maxSpeed, $n + 1);
                $n3+=$maxSpeed;
                if ($f3 < $maxSpeed) {

                    $f3 = $maxSpeed;
                }

                //backDelete
                $backDelete = $a['ratio_backDelete'];
                $backDelete2 = $a['ratio_backDelete'];
                if (strpos($backDelete, "&") === false) {
                    $backDelete = $backDelete . "&" . $backDelete;
                }
                $n = strrpos($backDelete, "&");
                $backDelete = substr($backDelete, $n + 1);
                $n4+=$backDelete;
                if ($f4 < $backDelete) {

                    $f4 = $backDelete;
                }

                //maxInternalTime
                $maxInternalTime = $a['ratio_maxInternalTime'];
                $maxInternalTime2 = $a['ratio_maxInternalTime'];
                if (strpos($maxInternalTime, "&") === false) {
                    $maxInternalTime = $maxInternalTime . "&" . $maxInternalTime;
                }
                $n = strrpos($maxInternalTime, "&");
                $maxInternalTime = substr($maxInternalTime, $n + 1);
                $n5+=$maxInternalTime;
                if ($f5 < $maxInternalTime) {

                    $f5 = $maxInternalTime;
                }

                //time
                $time = count($speed) * 2 - 2;
                $time2 = count($speed) * 2 - 2;
                //averageKeytype
                $averageKeyType = $a['ratio_averageKeyType'];
                if (strpos($averageKeyType, "&") === false) {
                    $averageKeyType = $averageKeyType . "&" . $averageKeyType;
                }
                $n = strrpos($averageKeyType, "&");
                $averageKeyType = substr($averageKeyType, $n + 1);
                $n6+=$averageKeyType;
                if ($f6 < $averageKeyType) {

                    $f6 = $averageKeyType;
                }

                //maxKeyType
                $maxKeyType = $a['ratio_maxKeyType'];
                if (strpos($maxKeyType, "&") === false) {
                    $maxKeyType = $maxKeyType . "&" . $maxKeyType;
                }
                $n = strrpos($maxKeyType, "&");
                $maxKeyType = substr($maxKeyType, $n + 1);
                $n7+=$maxKeyType;
                if ($f7 < $maxKeyType) {

                    $f7 = $maxKeyType;
                }

                //countAllKey
                $countAllKey = $a['ratio_countAllKey'];
                if (strpos($countAllKey, "&") === false) {
                    $countAllKey = $countAllKey . "&" . $countAllKey;
                }
                $n = strrpos($countAllKey, "&");
                $countAllKey = substr($countAllKey, $n + 1);
                $n8+=$countAllKey;
                if ($f8 < $countAllKey) {

                    $f8 = $countAllKey;
                }

                //createTime
                $createTime = $a['createTime'];
                if ($f9 < $createTime) {

                    $f9 = $createTime;
                }

                if ($isExam == 1) {
                    $student = ExamRecord::model()->find('recordID=?', array($a['recordID']))['studentID'];
                } else {
                    $student = SuiteRecord::model()->find('recordID=?', array($a['recordID']))['studentID'];
                }
                $studentName = Student::model()->find('userID=?', array($student))['userName'];
                if ($student == $name) {
                    //通过名字获取相应记录
                    $myData = ["speed" => $speed2, "maxSpeed" => $maxSpeed2, "correct" => $correct2, "backDelete" => $backDelete2, 'maxInternalTime' => $maxInternalTime2];
                    $myDetail = ["s" => 'l', "correct" => $correct, "speed" => $speed, "backDelete" => $backDelete,
                        'averageKeyType' => $averageKeyType, "createTime" => $createTime, "countAllKey" => $countAllKey];
                }
                $arrayData = ["studentID" => $student, "studentName" => $studentName, "speed" => $speed, "maxSpeed" => $maxSpeed, "correct" => $correct, "time" => $student, "backDelete" => $backDelete, 'maxInternalTime' => $maxInternalTime,
                    'averageKeyType' => $averageKeyType, "maxKeyType" => $maxKeyType, "countAllKey" => $countAllKey];
                $arrayData2 = ["speed" => $speed2, "maxSpeed" => $maxSpeed2, "correct" => $correct2, "backDelete" => $backDelete2, 'maxInternalTime' => $maxInternalTime2];
                $maxData = ["high" => "最高", "correct" => $f1, "speed" => $f2, "backDelete" => $f4,
                    'averageKeyType' => $f6, "createTime" => $f9, "countAllKey" => $f8];
            }
        }
        $arrayDetailData = Array();
        array_push($arrayDetailData, $myDetail);
        array_push($arrayDetailData, $maxData);

        ////*////
        return $this->renderPartial('01simple', ['arrayDetailData' => $arrayDetailData]);
    }

    public function actionChangeExamName() {
        $examID = $_POST['examID'];
        $newName = $_POST['newName'];
        $result = Exam::model()->changeExamName($examID, $newName);
        echo $result;
    }

    public function actionChangeWorkName() {
        $workID = $_POST['workID'];
        $newName = $_POST['newName'];
        $result = Suite::model()->changeSuiteName($workID, $newName);
        echo $result;
    }

}
