<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//crt by LC 2015-4-21

class TeacherController extends CController {

    public $layout = '//layouts/teacherBar';

    public function actionVirtualClass() {
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
        $sql = "SELECT backTime FROM student";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $n = 0;
        $b = 0;
        foreach ($time as $t) {
            if (time() - strtotime($time[$b++]['backTime']) < 10) {
                $n++;
            }
        }
        return $this->render('virtualClass', ['userName' => $username, 'classID' => $_GET['classID'], 'on' => $_GET['on'], 'count' => $n]);
    }

//add by LC 2015-10-13
    public function actionSetTimeAndScoreExam() {
        $examID = $_GET['examID'];
        $teacherID = Yii::app()->session['userid_now'];
        $array_class = array();
        $result = TbClass::model()->getClassByTeacherID($teacherID);
        foreach ($result as $class) {
            array_push($array_class, $class);
        }
        //得到当前显示班级
        if (isset($_GET['classID'])) {
            Yii::app()->session['currentClass'] = $_GET['classID'];
        } else if ($array_class != NULL) {
            Yii::app()->session['currentClass'] = $array_class[0]['classID'];
        } else {
            Yii::app()->session['currentClass'] = 0;
        }
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
            'array_exam' => $array_suite,
            'examExer' => $examExer,
            'examID' => $examID,
            'choiceScore' => $choiceScore,
            'fillingScore' => $fillingScore,
            'questScore' => $questScore,
            'listenAll' => $listenAll,
        ));
    }

    public function actionSaveTimeAll() {
        $examID = (isset($_GET['examID'])) ? $_GET['examID'] : 0;
        $choiceScore = (isset($_POST['choiceScore'])) ? $_POST['choiceScore'] : 0;
        $fillScore = (isset($_POST['fillScore'])) ? $_POST['fillScore'] : 0;
        $questScore = (isset($_POST['questScore'])) ? $_POST['questScore'] : 0;
        if (!!$choiceScore) {
            $choiceAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'choice']);
            foreach ($choiceAll as $choice) {
                $choice->score = $choiceScore;
                $choice->update();
            }
        }
        if (!!$fillScore) {
            $fillingAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'filling']);
            foreach ($fillingAll as $exer) {
                $exer->score = $fillScore;
                $exer->update();
            }
        }
        if (!!$questScore) {
            $questAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'question']);
            foreach ($questAll as $exer) {
                $exer->score = $questScore;
                $exer->update();
            }
        }
        $listenAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'listen']);
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
        $lookAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'look']);
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
        $keyAll = ExamExercise::model()->findAll("examID = ? and type = ?", [$examID, 'key']);
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
            $email = $_POST['email'];

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
            $user->mail_address = $email;
            $result = $user->update();
            $mail = $email;
        }

        $this->render('set', ['result' => $result, 'mail' => $mail]);
    }

    public function actionChangeProgress() {
        $classID = $_GET['classID'];
        $on = $_GET['on'];
        $class = new TbClass();
        $class = $class->find("classID = '$classID'");
        $class->currentLesson = $on;
        $class->update();
        $stu = Array();
        $stu = Student::model()->findAll("classID=? and is_delete=?", array($classID, 0));
        return $this->render('startCourse', [
                    'classID' => $classID,
                    'progress' => $on,
                    'on' => $on,
                    'stu' => $stu
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

    public function actionPptTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
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
        $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        $dir = "resources/" . $pptFilePath;
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
                return;
            }
        }
        if ($_FILES["file"]["type"] == "application/vnd.ms-powerpoint") {
            if ($_FILES["file"]["size"] < 30000000) {
                if ($_FILES["file"]["error"] > 0) {
                    $result = "Return Code: " . $_FILES["file"]["error"];
                } else {
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

    public function actionDeletePpt() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['ppt'];
        $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        $dir = "resources/" . $pptFilePath;
        $file = $dir . $fileName;
        $result = 0;               //不显示提示
        if (!isset(Yii::app()->session['ppt2del']) ||
                Yii::app()->session['ppt2del'] != $fileName) {
            Yii::app()->session['ppt2del'] = $fileName;
            if (file_exists(iconv('utf-8', 'gb2312', $file)))
                unlink(iconv('utf-8', 'gb2312', $file));
            Resourse::model()->delName($fileName);
            $result = "删除成功！";
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
            $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
            $dir = "resources/" . $pptFilePath . $fileDir;
        }
        return $this->render('lookPpt', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'dir' => $dir,
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

    public function actionVideoTable() {
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        return $this->renderPartial('videoTable', [
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
        $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        $dir = "resources/" . $videoFilePath;
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
    }

    public function actionDeleteVideo() {
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $classID = $_GET['classID'];
        $progress = $_GET['progress'];
        $on = $_GET['on'];
        $fileName = $_GET['video'];
        Resourse::model()->delName($fileName);
        $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        $dir = "resources/" . $videoFilePath;
        $file = $dir . $fileName;
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        $result = "删除成功！";
        return $this->render('videoLst', [
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
            $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
            $file = "resources/" . $videoFilePath . $file;
        }

        return $this->render('lookvideo', [
                    'classID' => $classID,
                    'progress' => $progress,
                    'on' => $on,
                    'file' => $file,
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

        //get student
        $stu = Array();
        $stu = Student::model()->findAll("classID=? and is_delete=?", array($classID, 0));
        return $this->render('startCourse', [
                    'classID' => $classID,
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
            $result = LookType::model()->insertLook($_POST['title'], $_POST['content'], Yii::app()->session['userid_now']);
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
        $thisLook->title = $_POST['title'];
        $thisLook->content = $_POST['content'];
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
        $deleteResult = $thisLook->deleteAll("exerciseID = '$exerciseID'");

        if (Yii::app()->session['lastUrl'] == "lookLst") {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result['lookLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "lookLst";
            $this->render('lookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult
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
                'searchKey' => $searchKey
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
                error_log("1");
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
            error_log("2");
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
            error_log("3");
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
            $i = 2;
            $answer = $_POST['in1'];
            for (; $i <= 3 * 10; $i++) {
                if ($_POST['in' . $i] != "") {
                    if ($i % 3 == 0)
                        $answer = $answer . "_" . $_POST['in' . $i];
                    else
                        $answer = $answer . ":" . $_POST['in' . $i];
                } else
                    break;
            }
            $result = KeyType::model()->insertKey($_POST['title'], $answer, Yii::app()->session['userid_now']);
        }
        $this->render('addKey', ['result' => $result]);
    }

    public function actionEditKey() {
        $exerciseID = $_GET["exerciseID"];
        $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        $result['content'] = str_replace("_", ":", $result['content']);
        if (!isset($_GET['action'])) {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'content' => $result['content']
            ));
        } else if ($_GET['action'] = 'look') {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'title' => $result['title'],
                'content' => $result['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditKeyInfo() {
        $exerciseID = $_GET['exerciseID'];
        $thisKey = new KeyType();
        $thisKey = $thisKey->find("exerciseID = '$exerciseID'");
        $i = 2;
        $answer = $_POST['in1'];
        for (; $i <= 3 * 10; $i++) {
            if ($_POST['in' . $i] != "") {
                if ($i % 3 == 0)
                    $answer = $answer . "_" . $_POST['in' . $i];
                else
                    $answer = $answer . ":" . $_POST['in' . $i];
            } else
                break;
        }
        $thisKey->title = $_POST['title'];
        $thisKey->content = $answer;
        $thisKey->update();

        if (Yii::app()->session['lastUrl'] == "modifyWork" || Yii::app()->session['lastUrl'] == "modifyExam") {
            $this->render("ModifyEditKey", array(
                'type' => "key",
                'exerciseID' => $exerciseID,
                'title' => $thisKey->title,
                'content' => $thisKey->content,
                'result' => "修改习题成功"
            ));
        } else {
            $this->render("editKey", array(
                'exerciseID' => $thisKey->exerciseID,
                'title' => $thisKey->title,
                'content' => $thisKey->content,
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
        $deleteResult = $thisKey->deleteAll("exerciseID = '$exerciseID'");

        if (Yii::app()->session['lastUrl'] == "KeyLst") {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result['keyLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "keyLst";
            $this->render('keyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult
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
            $this->render('searchKey', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult
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
                    $oldKey = $thisKey->findAll("exerciseID = '$exerciseID'");
                    $insertresult = KeyType::model()->insertKey($oldKey[0]['title'], $oldKey[0]['content'], Yii::app()->session['userid_now']);
                    Yii::app()->session['code'] = $_GET["code"];
                }
            }
        }
        if (isset($_POST['checkbox'])) {
            $exerciseIDlist = $_POST['checkbox'];
            foreach ($exerciseIDlist as $v) {
                $thisKey = new KeyType ();
                $oldKey = $thisKey->find("exerciseID = '$v'");
                $insertresult = KeyType::model()->insertKey($oldKey['title'], $oldKey['content'], Yii::app()->session['userid_now']);
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
        $deleteResult = $thisListen->deleteAll("exerciseID = '$exerciseID'");
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
        if (Yii::app()->session['lastUrl'] == "listenLst") {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result['listenLst'];
            $pages = $result['pages'];
            Yii::app()->session['lastUrl'] = "listenLst";
            $this->render('listenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult
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
                'searchKey' => $searchKey
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

                if (file_exists($dir . iconv("UTF-8", "gb2312", $fileName))) {
                    //表示复制的文件已存在
                    $insertresult = '2';
                } else {
                    $newName = Tool::createID() . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    if (file_exists($sourcefilePath . iconv("UTF-8", "gb2312", $fileName)))
                        copy($sourcefilePath . iconv("UTF-8", "gb2312", $fileName), $dir . iconv("UTF-8", "gb2312", $newName));
                    $insertresult = ListenType::model()->insertListen($oldListen['title'], $oldListen['content'], $newName, $filePath, Yii::app()->session['userid_now']);
                }
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
        $deleteResult = $thisFill->deleteAll("exerciseID = '$exerciseID'");
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
                'deleteResult' => $deleteResult
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
        $deleteResult = $thisChoice->deleteAll("exerciseID = '$exerciseID'");
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
        $deleteResult = $thisQuestion->deleteAll("exerciseID = '$exerciseID'");
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
                'deleteResult' => $deleteResult
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

        $this->render('assignWork', array(
            'array_class' => $array_class,
            'array_lesson' => $array_lesson,
            'array_suite' => $array_suite,
            'array_allsuite' => $array_allsuite,
            'pages' => $pages,
            'res' => $res
        ));
    }

    public function ActionAssignExam() {
        $res = 0;
        $teacherID = Yii::app()->session['userid_now'];
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
            'array_class' => $array_class,
            'array_exam' => $array_suite,
            'array_allexam' => $array_allexam,
            'pages' => $pages,
            'res' => $res
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
            'res'=>$res
        ));     
     }
     
      public function renderModifyExam($type,$examID)
     {
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
     public function getSuiteInfo($type , $suiteID)
     {
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
     
     public function ActionDeleteSuite()
     {$res=0;
         $suiteID = $_GET['suiteID'];
         $teacherID = Yii::app()->session['userid_now'];
         
         $workID=ClassLessonSuite::model()->find('suiteID=?',array($suiteID))['workID'];
         if($workID){
         $recordID=SuiteRecord::model()->find('workID=? and studentID=?',array($workID,$teacherID))['recordID'];
         SuiteRecord::model()->deleteAll("recordID='$recordID'");
         }
         Suite::model()->deleteAll("suiteID='$suiteID'");
         SuiteExercise::model()->deleteAll("suiteID='$suiteID'");
         ClassLessonSuite::model()->deleteAll("suiteID='$suiteID'");
         $currentClass = Yii::app()->session['currentClass'];
         $currentLesson=Yii::app()->session['currentLesson'];
         $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
         $array_lesson = array();        
         $array_class = array();
         $result = Suite::model()->getAllSuiteByPage(5,$teacherID);
         $array_allsuite = $result['suiteLst'];
         $pages = $result['pages'];
            
         if(!empty($teacher_class))
         {        
           foreach ($teacher_class as $class)
             {
                 $id = $class['classID'];
                 $result = TbClass::model()->findAll("classID ='$id'");               
                 array_push($array_class, $result[0]);
             }     

             $array_lesson = Lesson::model()->findAll("classID = '$currentClass'"); 
         }
         $array_suite = ClassLessonSuite::model()->findAll('classID=? and lessonID=?', array(Yii::app()->session['currentClass'],Yii::app()->session['currentLesson']));
         $this->render('assignWork',array(
             'array_class' => $array_class,
             'array_lesson' => $array_lesson,
             'array_suite'  => $array_suite,
             'array_allsuite' => $array_allsuite,
             'pages' => $pages,
             'res'=>$res

         )); 
     }
     
     
          public function ActionDeleteExam()
     {$res=0;
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
             array_push ($array_class, $class);
         
         $result = Exam::model()->getAllExamByPage(10);
         $array_allexam = $result['examLst'];
         $pages = $result['pages'];
         $array_suite = ClassExam::model()->findAll('classID=? and open=?', array(Yii::app()->session['currentClass'],1));
           
             $this->render('assignExam' , array(
             'array_class' => $array_class,
             'array_exam'  => $array_suite,
             'array_allexam' => $array_allexam,
             'pages' => $pages,
             'res'=>$res
             ));
     }
     
     
     public function ActionAddSuite(){
         $res=0;
         $teacherID = Yii::app()->session['userid_now'];
         if(isset($_GET['title']))
         {
         $title = $_GET['title'];
         Yii::app()->session['title'] = $title;
         }
         else{
             $title = Yii::app()->session['title'];
         }
         $suiteLst = Suite::model()->findAll();
         foreach ($suiteLst as $all) {
             if($all['suiteName']==$title){
                 $res=1;
                 $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
                    $array_lesson = array();          
                    $array_class = array();
                    $result = Suite::model()->getAllSuiteByPage(10,$teacherID);
                    $array_allsuite = $result['suiteLst'];
                    $pages = $result['pages'];
                    if(!empty($teacher_class))
                    {
                      if(isset($_GET['classID']))
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

                $this->render('assignWork', array(
                    'array_class' => $array_class,
                    'array_lesson' => $array_lesson,
                    'array_suite' => $array_suite,
                    'array_allsuite' => $array_allsuite,
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
        $res = 0;
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
            Yii::app()->session['title'] = $title;
        } else {
            $title = Yii::app()->session['title'];
        }
        $allExam = Exam::model()->findAll();
        foreach ($allExam as $all) {
            if ($all['examName'] == $title) {
                $res = 1;
                $teacherID = Yii::app()->session['userid_now'];
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
            if(!isset($_GET['selectClassID'])){
                 if ($array_class != NULL) {
                $selectClassID = $array_class[0]['classID'];
            } else {
                $selectClassID = -1;
            }
        }
        if($selectClassID==-1){
            $result = ClassLessonSuite::model()->getSuiteClassByTeacherID($teacherID, "");
        }else{
            $result = ClassLessonSuite::model()->getSuiteClassByTeacherID($teacherID, $selectClassID);
        }
        

        $array_suiteLessonClass1 = $result['suiteLst'];

        $pages = $result['pages'];


        $array_lesson1 = Lesson::model()->getLessonByTeacherID($teacherID);
        $array_suite1 = Suite::model()->getSuiteByClassLessonSuite($teacherID);

        foreach ($array_suiteLessonClass1 as $result)
            array_push($array_suiteLessonClass, $result);

        foreach ($array_lesson1 as $result)
            array_push($array_lesson, $result);
        foreach ($array_suite1 as $result)
            array_push($array_suite, $result);
        $workID = -1;
        $classID = -1;
        if ($array_suiteLessonClass != NULL) {
            $workID = $array_suiteLessonClass[0]['workID'];
            $classID = $array_suiteLessonClass[0]['classID'];
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
            $result = SuiteRecord::model()->find("workID=? and studentID=?", array($workID, $userID));

            if ($result != NULL && $result['ratio_accomplish'] == 1)
                array_push($array_accomplished, $student);
            else
                array_push($array_unaccomplished, $student);
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
        $this->render('assignWork', array(
            'array_class' => $array_class,
            'array_lesson' => $array_lesson,
            'array_suite' => $array_suite,
            'array_allsuite' => $array_allsuite,
            'pages' => $pages,
            'res' => $res
        ));
    }

    public function ActionChangeExamClass() {
        $res = 0;
        $examID = $_GET['examID'];
        $isOpen = $_GET['isOpen'];
        $duration = $_GET['duration'];
        $startTime = $_GET['beginTime'];
        if ($isOpen == 0)
            Exam::model()->updateByPk($examID, array('begintime' => $startTime, 'duration' => $duration));
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
        $type = $_GET['type'];
        $suiteID = $_GET['suiteID'];
        $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
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
        if(!isset($classID)){
            $classID=  Student::model()->findClassByStudentID($studentID);
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

        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        $accomplish = $_GET['accomplish'];
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
                    'correct' => $answer['ratio_correct']]);
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

        $answer = $recordID == NULL ? NULL : AnswerRecord::getAnswer($recordID, 'key', $exerID);
        $score = AnswerRecord::model()->getAndSaveScoreByRecordID($recordID);
        $accomplish = $_GET['accomplish'];
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
                    'correct' => $answer['ratio_correct']]);
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
                if ($arr[$m] != " ")
                    AnswerRecord::model()->changeScore($ansWork[$k]['answerID'], $arr[$m++]);
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
             if(!isset($sqlcurrentClass)){
                 $sqlcurrentClass = "none";
             }
            if (!empty($array_course)) {
                if (isset($_GET['courseID'])) {
                    Yii::app()->session['currentCourse'] = $_GET['courseID'];
                } else
                    Yii::app()->session['currentCourse'] = $array_course[0]['courseID'];
            }
        }

        if (isset($_GET['classID'])) {
            //查询任课班级科目
            $classResult = ScheduleClass::model()->findAll("classID='$currentClass'");
            return $this->render('scheduleDetil', ['teacher' => $sqlTeacher, 'result' => $classResult, 'array_class' => $array_class,
                        'array_course' => $array_course, 'sqlcurrentClass' => $sqlcurrentClass]);
        } else if (isset($_GET['courseID']) && !isset($_GET['lessonName'])) {
            //显示课程列表逻辑
            $courseID = $_GET ['courseID'];
            $result = Lesson::model()->getLessonLst("", "", $courseID);
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
            ));
        } else if (isset($_GET['lessonName'])) {
            $currentClass = Yii::app()->session['currentClass'];
             $sqlcurrentClass = TbClass::model()->find("classID = '$currentClass'");
             if(!isset($sqlcurrentClass)){
                 $sqlcurrentClass = "none";
             }
            $lessonName = $_GET['lessonName'];
            $newName = $_GET['newName'];
            $sql = "UPDATE `lesson` SET `lessonName`= '$newName' WHERE lessonName= '$lessonName'";
            Yii::app()->db->createCommand($sql)->query();
            $courseID = $_GET ['courseID'];
            $result = Lesson::model()->getLessonLst("", "", $courseID);
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
            ));
        } else {
            $currentClass = Yii::app()->session['currentClass'];
             $sqlcurrentClass = TbClass::model()->find("classID = '$currentClass'");
             if(!isset($sqlcurrentClass)){
                 $sqlcurrentClass = "none";
             }
            //查询老师课程表
            $teaResult = ScheduleTeacher::model()->findAll("userID='$teacherID'");
            return $this->render('scheduleDetil', ['teacher' => $sqlTeacher, 'result' => $teaResult, 'array_class' => $array_class,
                        'array_course' => $array_course, 'sqlcurrentClass' => $sqlcurrentClass]);
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
    
    public function Actionshitup(){
        $userid = $_GET['userid'];
        $student = new Student();
        $student = Student::model()->find("userID ='$userid'");
        $student->forbidspeak = '1';
        $student->update(); 
    }
    
public function Actioncheckforbid(){
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

public function ActionrecoverForbidStu(){
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

}
