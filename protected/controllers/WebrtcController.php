<?php
class WebrtcController extends CController{
    public $layout='';
    
    public function actionTeaCam(){
        $this->renderPartial('teaCam',['classID'=>$_GET['classID']]);
    }
    public function actionstuCam(){
        $this->renderPartial('stuCam',['classID'=>$_GET['classID']]);
    }
    public function actionNull(){
        $this->renderPartial('null');
    }
}