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
        $connection = Yii::app()->db;
        $sql = "SELECT * FROM chat_lesson_1 ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $all_chats = $dataReader->readAll();

        $this->renderJSON($all_chats);
    }

    public function actionPutChat() {
        $username = (string) Yii::app()->request->getParam('username');
        $chat = (string) Yii::app()->request->getParam('chat');
        $publishtime = (string) Yii::app()->request->getParam('time');

        $connection = Yii::app()->db;
        $sql = "INSERT INTO chat_lesson_1 (username, chat, time) values ($username, $chat, $publishtime)";
        $command = $connection->createCommand($sql);
        $command->execute();
    }

    public function actionGetLatestBulletin() {
        $connection = Yii::app()->db;
        $sql = "SELECT * FROM bulletin_lesson_1 ORDER BY id DESC LIMIT 1";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $bulletin = $dataReader->readAll();

        $this->renderJSON($bulletin);
    }

    public function actionPutBulletin() {
        $bulletin = (string) Yii::app()->request->getParam('bulletin');
        $publishtime = (string) Yii::app()->request->getParam('time');

        $connection = Yii::app()->db;
        $sql = "INSERT INTO bulletin_lesson_1 (content, time) values ($bulletin, $publishtime)";
        $command = $connection->createCommand($sql);
        $command->execute();
    }

}
