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
        $sql = "SELECT backTime FROM student";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $time = $dataReader->readAll();
        $n=0;$b=0;
        foreach ($time as $t) {
            if(time()-strtotime($time[$b++]['backTime']) < 10){
                $n++;
            }
        }
        $this->renderJSON($n);
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
}
