<?php
class WebrtcController extends CController{
    public $layout='';
    
    public function actionTeaCam(){
        $this->render('teaCam',['classID'=>$_GET['classID']]);
    }
    public function actionstuCam(){
        $this->render('stuCam',['classID'=>$_GET['classID']]);
    }
    public function actionNull(){
        $this->render('null');
    }
}