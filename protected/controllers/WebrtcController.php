<?php
class WebrtcController extends CController{
    public $layout='';
    
    public function actionTeaCam(){
        $this->render('teaCam');
    }
    public function actionstuCam(){
        $this->render('stuCam');
    }
    public function actionTeaScreen(){
        $this->render('teaScreen');
    }
    public function actionStuScreen(){
        $this->render('stuScreen');
    }
    public function actionNull(){
        $this->render('null');
    }
    
}