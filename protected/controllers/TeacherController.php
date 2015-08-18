<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//crt by LC 2015-4-21

class TeacherController extends CController {
    
    public $layout='//layouts/teacherBar';
    
    public function actionVirtualClass() {
        $userID = Yii::app()->session['userid_now'];
        $userName   = Teacher::model()->findByPK($userID)->userName;
        return $this->render('virtualClass',['userName'=>$userName,'classID'=>$_GET['classID']]);
    }
    
    public function actionlookExer(){
        return $this->render('lookExer');
    }
    public function actioncourseMng(){
        return $this->render('courseMng');
    }
    public function actionexerMng(){
        return $this->render('exerMng');
    }
    public function actionIndex(){
        //$user_model = new User;
        //$username_now=Yii::app()->user->name;
        //$info=$user_model->find("username='$username_now'");//,'pageInden'=>$pageIndex
        $this->render('index');//,['info'=>$info]);
    }
    public function actionHello(){
        return $this->render('hello',array(null));
    }
    
    public function actionStartCourse(){
        $classID=$_GET['classID'];
        $progress=$_GET['progress'];
        $on=$_GET['on'];
        return $this->render('startCourse',[
            'classID'=>$classID,
            'progress'=>$progress,
            'on'=>$on
        ]);
    }
            
    public function teaInClass(){
        $sql="SELECT * FROM teacher order by userID ASC";
        $an = Yii::app()->db->createCommand($sql)->query();
        return $an;
    }

    public function teaByClass(){
        $sql="SELECT * FROM teacher_class order by classID ASC";
        $an = Yii::app()->db->createCommand($sql)->query();
        return $an;
    }


    
    public function actionLookLst()
    {        
        if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            $result     = LookType::model()->getLookLst("", "");
            $lookLst  =   $result['lookLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "lookLst";
            $this->render('lookLst',array(
                    'lookLst'     =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
       
    }

    public function actionAddLook(){
            $result =   'no';
            if(isset($_POST['title'])){
                $result = LookType::model()->insertLook($_POST['title'], $_POST['content'], Yii::app()->session['userid_now']);
            }
            $this->render('addLook',['result'   =>  $result]);
    }

    public function actionEditLook(){
           $exerciseID=$_GET["exerciseID"];
            $sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $result =$result->read();
           if(!isset($_GET['action']))
            {
                $this->render("editLook",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                        'content'=>$result['content']
                ));
            }else if($_GET['action']='look'){
                $this->render("editLook",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                        'content'=>$result['content'],
                        'action'        =>  'look'
                ));
            }
    }
    
           public function actionEditLookInfo(){
            $exerciseID    =   $_GET['exerciseID'];
            $thisLook      =   new LookType();
            $thisLook       =   $thisLook->find("exerciseID = '$exerciseID'");
            $thisLook->title =   $_POST['title'];
            $thisLook->content       =   $_POST['content'];
            $thisLook -> update();
            $this->render("editLook",array(
                'exerciseID'      =>  $thisLook->exerciseID,
                'title'    =>  $thisLook->title,
                'content'          =>  $thisLook->content,
                'result'          =>  "修改习题成功"
            ));
        }
         public function actionReturnFromAddLook(){
            if(Yii::app()->session['lastUrl']=="lookLst")
            {
            $result     = LookType::model()->getLookLst("", "");
            $lookLst  =   $result['lookLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "lookLst";
            $this->render('lookLst',array(
                    'lookLst'     =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
            }  else {
                $type   =   Yii::app()->session['searchLookType'];
                $value  =   Yii::app()->session['searchLookValue'];
                Yii::app()->session['lastUrl']  =   "searchLook";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
            $result      = LookType::model()->getLookLst($type, $value);
            $lookLst =  $result['lookLst'];  
            $pages       =  $result["pages"];
            $this->render('searchLook',array(
                    'lookLst'   =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'searchKey'  => $searchKey
            ));
            }
        }
        
              public function actionSearchLook()
        {
            if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            if(isset($_POST['type'])){
                $type   =   $_POST['type'];
                $value  =   $_POST['value'];
                Yii::app()->session['searchLookType']   =   $type;
                Yii::app()->session['searchLookValue']  =   $value;
            } else {
                $type   =   Yii::app()->session['searchLookType'];
                $value  =   Yii::app()->session['searchLookValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchLook";
            if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }

            $result      = LookType::model()->getLookLst($type, $value);
            $lookLst =  $result['lookLst'];  
            $pages       =  $result["pages"];
            $this->render('searchLook',array(
                    'lookLst'   =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                    'searchKey'  => $searchKey
                
            ));
        }
        
        public function actionDeleteLook(){
            $exerciseID = $_GET['exerciseID'];
            $thisLook = new LookType();
            $deleteResult = $thisLook->deleteAll("exerciseID = '$exerciseID'");
            
            if(Yii::app()->session['lastUrl']=="LookLst")
            {
            $result     = LookType::model()->getLookLst("", "");
            $lookLst  =   $result['lookLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "lookLst";
            $this->render('lookLst',array(
                    'lookLst'     =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall(),
                'deleteResult' => $deleteResult
            ));
            }  else {
                $type   =   Yii::app()->session['searchLookType'];
                $value  =   Yii::app()->session['searchLookValue'];
                Yii::app()->session['lastUrl']  =   "searchLook";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
            
            $result      = LookType::model()->getLookLst($type, $value);
            $lookLst =  $result['lookLst'];  
            $pages       =  $result["pages"];
            $this->render('searchLook',array(
                    'lookLst'   =>  $lookLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult,
                    'searchKey'  => $searchKey
            ));
            }
        }
        
        public function actionCopyLook(){
            $insertresult = "no";
            $code = $_GET["code"];
            if($code != Yii::app()->session['code']){
                            $exerciseID =   $_GET["exerciseID"];
            $thisLook = new LookType();
            $oldLook = $thisLook->findAll("exerciseID = '$exerciseID'");
            $insertresult = LookType::model()->insertLook($oldLook[0]['title'], $oldLook[0]['content'], Yii::app()->session['userid_now']);
            Yii::app()->session['code'] = $_GET["code"];
            }
            
           if(Yii::app()->session['lastUrl']=="searchLook")
            {
                $type       =   Yii::app()->session['searchLookType'];
                $value      =   Yii::app()->session['searchLookValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     = LookType::model()->getLookLst($type, $value);
                $lookLst  =   $result['lookLst'];
                $pages      =   $result['pages'];
                $this->render('searchLook',array(
                            'lookLst' =>  $lookLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult   ,
                         'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = LookType::model()->getLookLst("", "");
                $lookLst  =   $result['lookLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  = "LookLst";
                $this->render('LookLst',array(
                        'lookLst' =>  $lookLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            } 
            
        }


    public function actionKeyLst()
    {       
         if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            $result     = KeyType::model()->getKeyLst("", "");
            $keyLst  =   $result['keyLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "keyLst";
            $this->render('keyLst',array(
                    'keyLst'     =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
    }

    public function actionAddKey(){
            $result =   'no';
            if(isset($_POST['title'])){
                $i      =   2;
                $answer =   $_POST['in1'];
                for(;$i<=3*10;$i++)
                {
                    if($_POST['in'.$i]!="")
                        $answer =   $answer."$".$_POST['in'.$i];
                    else
                        break;
                }
                $result = KeyType::model()->insertKey($_POST['title'], $answer, Yii::app()->session['userid_now']);
            }
            $this->render('addKey',['result'   =>  $result]);
    }

    public function actionEditKey(){
           $exerciseID=$_GET["exerciseID"];
            $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $result =$result->read();
           if(!isset($_GET['action']))
            {
                $this->render("editKey",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                        'content'=>$result['content']
                ));
            }else if($_GET['action']='look'){
                $this->render("editKey",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                        'content'=>$result['content'],
                        'action'        =>  'look'
                ));
            }
    }
    
         public function actionEditKeyInfo(){
            $exerciseID    =   $_GET['exerciseID'];
            $thisKey      =   new KeyType();
            $thisKey       =   $thisKey->find("exerciseID = '$exerciseID'");
                $i      =   2;
                $answer =   $_POST['in1'];
                for(;$i<=3*10;$i++)
                {
                    if($_POST['in'.$i]!="")
                        $answer =   $answer."$".$_POST['in'.$i];
                    else
                        break;
                }
            $thisKey->title =   $_POST['title'];
            $thisKey->content       =   $answer;
            $thisKey -> update();
            $this->render("editKey",array(
                'exerciseID'      =>  $thisKey->exerciseID,
                'title'    =>  $thisKey->title,
                'content'          =>  $thisKey->content,
                'result'          =>  "修改习题成功"
            ));
        }
    
    
     public function actionReturnFromAddKey(){
            if(Yii::app()->session['lastUrl']=="keyLst")
            {
            $result     = KeyType::model()->getKeyLst("", "");
            $keyLst  =   $result['keyLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "keyLst";
            $this->render('keyLst',array(
                    'keyLst'     =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
            }  else {
                $type   =   Yii::app()->session['searchKeyType'];
                $value  =   Yii::app()->session['searchKeyValue'];
                Yii::app()->session['lastUrl']  =   "searchKey";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
            $result      = KeyType::model()->getKeyLst($type, $value);
            $keyLst =  $result['keyLst'];  
            $pages       =  $result["pages"];
            $this->render('searchKey',array(
                    'keyLst'   =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
            ));
            }
        }
        
     public function actionDeleteKey(){
            $exerciseID = $_GET['exerciseID'];
            $thisKey = new KeyType();
            $deleteResult = $thisKey->deleteAll("exerciseID = '$exerciseID'");
            
            if(Yii::app()->session['lastUrl']=="KeyLst")
            {
            $result     = KeyType::model()->getKeyLst("", "");
            $keyLst  =   $result['keyLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "keyLst";
            $this->render('keyLst',array(
                    'keyLst'     =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall(),
                'deleteResult' => $deleteResult
            ));
            }  else {
                $type   =   Yii::app()->session['searchKeyType'];
                $value  =   Yii::app()->session['searchKeyValue'];
                Yii::app()->session['lastUrl']  =   "searchKey";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
            $result      = KeyType::model()->getKeyLst($type, $value);
            $keyLst =  $result['keyLst'];  
            $pages       =  $result["pages"];
            $this->render('searchKey',array(
                    'keyLst'   =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult
            ));
            }
        }
        
      public function actionSearchKey()
        {
            if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            if(isset($_POST['type'])){
                $type   =   $_POST['type'];
                $value  =   $_POST['value'];
                Yii::app()->session['searchKeyType']   =   $type;
                Yii::app()->session['searchKeyValue']  =   $value;
            } else {
                $type   =   Yii::app()->session['searchKeyType'];
                $value  =   Yii::app()->session['searchKeyValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchKey";
            if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
            $result      = KeyType::model()->getKeyLst($type, $value);
            $keyLst =  $result['keyLst'];  
            $pages       =  $result["pages"];
            $this->render('searchKey',array(
                    'keyLst'   =>  $keyLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
            ));
        }
        
        public function actionCopyKey(){
            $insertresult = "no";
            $code = $_GET["code"];
            if($code != Yii::app()->session['code']){
                            $exerciseID =   $_GET["exerciseID"];
            $thisKey = new KeyType();
            $oldKey = $thisKey->findAll("exerciseID = '$exerciseID'");
            $insertresult = KeyType::model()->insertKey($oldKey[0]['title'], $oldKey[0]['content'], Yii::app()->session['userid_now']);
            Yii::app()->session['code'] = $_GET["code"];
            }
            
           if(Yii::app()->session['lastUrl']=="searchKey")
            {
                $type       =   Yii::app()->session['searchKeyType'];
                $value      =   Yii::app()->session['searchKeyValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                $result     = KeyType::model()->getKeyLst($type, $value);
                $keyLst  =   $result['keyLst'];
                $pages      =   $result['pages'];
                $this->render('searchKey',array(
                            'keyLst' =>  $keyLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult                       
                    )
                    );
            }else {
                $result     = KeyType::model()->getKeyLst("", "");
                $keyLst  =   $result['keyLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  = "KeyLst";
                $this->render('KeyLst',array(
                        'keyLst' =>  $keyLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            } 
            
        }
        
    public function actionListenLst()
    {       
       if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            $result     =   ListenType::model()->getListenLst("", "");
            $listenLst  =   $result['listenLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "listenLst";
            $this->render('listenLst',array(
                    'listenLst'     =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
    }

 public function actionAddListen(){
             $result =   'no';            
             $typename = Yii::app()->session['role_now'];
             $userid = Yii::app()->session['userid_now'];
             $filePath =$typename."/".$userid."/"; 
             $dir = "resources/".$filePath;            
             if(!is_dir($dir))
             {
             mkdir($dir,0777);
             }
            
             
            if(isset($_POST['title'])){  
                if($_FILES['file']['type']!= "audio/mpeg")
                {
                    $result = '文件格式不正确，应为MP3格式';            
                }else if($_FILES['file']['error'] > 0)
                {
                    $result = '文件上传失败';
                }else if(file_exists($dir.iconv("UTF-8","gb2312",$_FILES["file"]["name"])))
                {
                    $result='服务器存在相同文件';
                }else{
                     move_uploaded_file($_FILES["file"]["tmp_name"],$dir.iconv("UTF-8","gb2312",$_FILES["file"]["name"]));
                     $result = '1';
                }
                if($result == '1')
                {
                    $result = ListenType::model()->insertListen($_POST['title'],$_POST['content'],$_FILES["file"]["name"],$filePath,Yii::app()->session['userid_now']);                  
                }             
            }
            $this->render('addListen',['result'   =>  $result]);
        }
        
     public function actionSearchListen()
        {
            if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            if(isset($_POST['type'])){
                $type   =   $_POST['type'];
                $value  =   $_POST['value'];
                Yii::app()->session['searchListenType']   =   $type;
                Yii::app()->session['searchListenValue']  =   $value;
            } else {
                $type   =   Yii::app()->session['searchListenType'];
                $value  =   Yii::app()->session['searchListenValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchListen";
            if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
            $result      = ListenType::model()->getListenLst($type, $value);
            $listenLst =  $result['listenLst'];  
            $pages       =  $result["pages"];
            $this->render('searchListen',array(
                    'listenLst'   =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'searchKey'  => $searchKey
                
            ));
        }
          

        
       public function actionReturnFromAddListen(){
            if(Yii::app()->session['lastUrl']=="listenLst")
            {
                    $result     =   ListenType::model()->getListenLst("", "");
            $listenLst  =   $result['listenLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "listenLst";
            $this->render('listenLst',array(
                    'listenLst'     =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()
            ));
            }  else {
                $type   =   Yii::app()->session['searchListenType'];
                $value  =   Yii::app()->session['searchListenValue'];
                Yii::app()->session['lastUrl']  =   "searchListen";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
            $result      = ListenType::model()->getListenLst($type, $value);
            $listenLst =  $result['listenLst'];  
            $pages       =  $result["pages"];
            $this->render('searchListen',array(
                    'listenLst'   =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'searchKey'  => $searchKey
            ));
            }
        }
        
        public function actionDeleteListen(){
            $exerciseID = $_GET['exerciseID'];
            $thisListen = new ListenType();
            $deleteListen = $thisListen->findAll("exerciseID = '$exerciseID'");
            $deleteResult = $thisListen->deleteAll("exerciseID = '$exerciseID'");    
            $filePath = $deleteListen[0]['filePath'];
            $fileName = $deleteListen[0]['fileName'];
            if($deleteResult == '1')
            {             
                $typename = Yii::app()->session['role_now'];
                $userid = Yii::app()->session['userid_now'];    
                //怎么用EXER_LISTEN_URL
                $path = 'resources/'.$filePath.iconv("UTF-8","gb2312",$fileName);
                unlink($path);
            }
            if(Yii::app()->session['lastUrl']=="listenLst")
            {
            $result  =  ListenType::model()->getListenLst("", "");
            $listenLst  =   $result['listenLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "listenLst";
            $this->render('listenLst',array(
                    'listenLst'     =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall(),
                    'deleteResult'   => $deleteResult
            ));
            }  else {
                $type   =   Yii::app()->session['searchListenType'];
                $value  =   Yii::app()->session['searchListenValue'];
                Yii::app()->session['lastUrl']  =   "searchListen";
               if($type=='createPerson')
            {
                if($value=="管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =    $tea['userID'];
                    else 
                        $value = -1;
                }
            }
                        if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
             $result      = ListenType::model()->getListenLst($type, $value);
            $listenLst =  $result['listenLst'];  
            $pages       =  $result["pages"];
            $this->render('searchListen',array(
                    'listenLst'   =>  $listenLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  TbClass::model()->teaInClass(),
                'deleteResult'   => $deleteResult,
                'searchKey'  => $searchKey
            ));
            }
        }

        public function actionEditlisten(){
            $exerciseID=$_GET["exerciseID"];
            $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $result =$result->read();
             if(!isset($_GET['action']))
            {
                $this->render("editListen",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                        'filename' =>$result['fileName'],
                        'filepath' =>$result['filePath'],
                        'content'=>$result['content']
                ));
            }else if($_GET['action']='look'){
                $this->render("editListen",array(
                        'exerciseID'    =>  $exerciseID,
                        'title' =>$result['title'],
                       'filename' =>$result['fileName'],
                        'filepath' =>$result['filePath'],
                        'content'=>$result['content'],
                        'action'        =>  'look'
                ));
            }
        }
        
             public function actionEditListenInfo()
     {                  
             $typename = Yii::app()->session['role_now'];
             $userid = Yii::app()->session['userid_now'];
             $filePath = $typename."/".$userid."/";
             $dir = "resources/".$filePath;
            $exerciseID    =   $_GET['exerciseID'];
            $filename = $_GET['oldfilename'];
            $result = "修改失败";
            if($_FILES['modifyfile']['tmp_name'])
            {
               if($_FILES['modifyfile']['type']!= "audio/mpeg")
                {
                    $result = '文件格式不正确，应为MP3格式';            
                }else if($_FILES['modifyfile']['error'] > 0)
                {
                    $result = '文件上传失败';
                }else if(file_exists($dir.iconv("UTF-8","gb2312",$_FILES["modifyfile"]["name"])))
                {
                    $result='服务器存在相同文件';
                }else{
                     move_uploaded_file($_FILES["modifyfile"]["tmp_name"],$dir.iconv("UTF-8","gb2312",$_FILES["modifyfile"]["name"]));
                     unlink($dir.iconv("UTF-8","gb2312",$filename));
                     $result = '修改失败';
                }
            }           
            $thisListen      =   new ListenType();
            $thisListen       =   $thisListen->find("exerciseID = '$exerciseID'");
            $thisListen->title =   $_POST['title'];
            if($_FILES['modifyfile']['tmp_name'])
            {
                $thisListen->fileName = $_FILES['modifyfile']['name'];
            }  else {
                $thisListen->fileName = $filename;
            }
            $thisListen->content       =   $_POST['content'];
            if($result == '修改失败')
            {
              $thisListen -> update();
              $result = "修改成功";
            }
            $this->render("editListen",array(
                'exerciseID'      =>  $thisListen->exerciseID,
                'filename' => $thisListen->fileName,
                'filepath' =>$thisListen->filePath,
                'title'    =>  $thisListen->title,
                'content'          =>  $thisListen->content,
                'result'          =>  $result
            ));
        }
        
        
      public function actionCopyListen(){
            $insertresult = "no";
            $code = $_GET["code"];
            $typename = Yii::app()->session['role_now'];
            $userid = Yii::app()->session['userid_now'];
            $filePath = $typename."/".$userid."/";
            $dir = "resources/".$filePath;
            if(!is_dir($dir))
             {
             mkdir($dir,0777);
             }
            
            if($code != Yii::app()->session['code']){
            $exerciseID =   $_GET["exerciseID"];
            $thisListen = new ListenType();
            $oldListen = $thisListen->findAll("exerciseID = '$exerciseID'");
            $sourcefilePath = "resources/".$oldListen[0]['filePath'];
            $fileName = $oldListen[0]['fileName'];
            
            if(file_exists($dir.iconv("UTF-8","gb2312",$fileName)))
            {
                //表示复制的文件已存在
                $insertresult = '2';
            }else{
                copy($sourcefilePath.iconv("UTF-8","gb2312",$fileName),$dir.iconv("UTF-8","gb2312",$fileName) );
                $insertresult = ListenType::model()->insertListen($oldListen[0]['title'],$oldListen[0]['content'],$oldListen[0]['fileName'],$filePath , Yii::app()->session['userid_now']);
            }          
            Yii::app()->session['code'] = $_GET["code"];
            }
            
            
           if(Yii::app()->session['lastUrl']=="searchListen")
            {
                $type       =   Yii::app()->session['searchListenType'];
                $value      =   Yii::app()->session['searchListenValue'];
            if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
            if($type == "content")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     = ListenType::model()->getListenLst($type, $value);
                $listenLst  =   $result['listenLst'];
                $pages      =   $result['pages'];
                $this->render('searchListen',array(
                            'listenLst' =>  $listenLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult,
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = ListenType::model()->getListenLst("", "");
                $listenLst  =   $result['listenLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  = "ListenLst";
                $this->render('ListenLst',array(
                        'listenLst' =>  $listenLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            } 
            
        }
    

    public function actionFillLst()
    {       $result = Filling::model()->getFillLst("", "");
            $fillLst=$result['fillLst'];
            $pages=$result['pages'];
            Yii::app()->session['lastUrl']="fillLst";
            $this->render('fillLst',array(
            'fillLst'=>$fillLst,
            'pages'=>$pages, 
            'teacher'       =>  Teacher::model()->findall()
            ));
    }
    
    //宋杰 2015-7-30 查找老师的填空题
    public function actionSearchFill()
        {
            if(isset($_POST['type'])){
                $type=$_POST['type'];
                $value=$_POST['value'];
                Yii::app()->session['searchFillType']=$type;
                Yii::app()->session['searchFillValue']=$value;
            } else {
                $type = Yii::app()->session['searchFillType'];
                $value = Yii::app()->session['searchFillValue'];
            }
            Yii::app()->session['lastUrl']="searchFill";
           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
            $result = Filling::model()->getFillLst($type, $value);
            $fillLst=$result['fillLst'];
            $pages=$result['pages'];
            $this->render('searchFill',array(
                        'fillLst'=>$fillLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall(),
                        'searchKey'  => $searchKey
                    )
                    );
        }
    
           public function actionReturnFromAddFill(){
            if(Yii::app()->session['lastUrl']=="searchFill")
            {
                $type = Yii::app()->session['searchFillType'];
                $value = Yii::app()->session['searchFillValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
          if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result = Filling::model()->getFillLst($type, $value);
                $fillLst=$result['fillLst'];
                $pages=$result['pages'];
                $this->render('searchFill',array(
                        'fillLst'=>$fillLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall(),
                        'searchKey'  => $searchKey
                        )
                    );
            }else{
                $result = Filling::model()->getFillLst("", "");
                $fillLst=$result['fillLst'];
                $pages=$result['pages'];
                Yii::app()->session['lastUrl']="fillLst";
                $this->render('fillLst',array(
                    'fillLst'=>$fillLst,
                    'pages'=>$pages,
                    'teacher'=>  Teacher::model()->findall(),
                    
                ));
            }
        }

    public function actionAddFill(){
            $result =   'no';
            if(isset($_POST['requirements'])){
                $i      =   2;
                $answer =   $_POST['in1'];
                for(;$i<=5;$i++)
                {
                    if($_POST['in'.$i]!="")
                        $answer =   $answer."$".$_POST['in'.$i];
                    else
                        break;
                }
                $result =   Filling::model()->insertFill($_POST['requirements'], $answer, Yii::app()->session['userid_now']);
            }
            $this->render('addFill',['result'   =>  $result]);
    }
    
    
        public function actionDeleteFill(){
            $exerciseID = $_GET["exerciseID"];
            $thisFill = new Filling();
            $deleteResult = $thisFill->deleteAll("exerciseID = '$exerciseID'");           
           if(Yii::app()->session['lastUrl']=="searchFill")
            {
                $type       =   Yii::app()->session['searchFillType'];
                $value      =   Yii::app()->session['searchFillValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
            
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }

                $result     = Filling::model()->getFillLst($type, $value);
                $fillLst  =   $result['fillLst'];
                $pages      =   $result['pages'];
                $this->render('searchFill',array(
                            'fillLst' =>  $fillLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'deleteResult'    =>  $deleteResult,
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = Filling::model()->getFillLst("", "");
                $fillLst  =   $result['fillLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "FillLst";
                $this->render('fillLst',array(
                        'fillLst' =>  $fillLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'deleteResult'      =>  $deleteResult
                ));
            }                   
        }
        
        public function actionCopyFill(){
            $insertresult = "no";
            $code = $_GET["code"];
            if($code != Yii::app()->session['code']){
                            $exerciseID =   $_GET["exerciseID"];
            $thisFill = new Filling();
            $oldFill = $thisFill->findAll("exerciseID = '$exerciseID'");
            $insertresult = Filling::model()->insertFill($oldFill[0]['requirements'], $oldFill[0]['answer'], Yii::app()->session['userid_now']);
            Yii::app()->session['code'] = $_GET["code"];
            }

            
           if(Yii::app()->session['lastUrl']=="searchFill")
            {
                $type       =   Yii::app()->session['searchFillType'];
                $value      =   Yii::app()->session['searchFillValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     = Filling::model()->getFillLst($type, $value);
                $fillLst  =   $result['fillLst'];
                $pages      =   $result['pages'];
                $this->render('searchFill',array(
                            'fillLst' =>  $fillLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult,
                    'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = Filling::model()->getFillLst("", "");
                $fillLst  =   $result['fillLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "FillLst";
                $this->render('fillLst',array(
                        'fillLst' =>  $fillLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            }             
        }

    
    //宋杰 2015-7-30 选择题列表
    public function actionChoiceLst()
    {       
            if(isset($_GET['page']))
            {
                Yii::app()->session['lastPage'] = $_GET['page'];
            }else{
                Yii::app()->session['lastPage'] = 1;
            }
            $teachr_id  =   Yii::app()->session['userid_now'];
            $result     =   Choice::model()->getChoiceLst("", "");
            $choiceLst  =   $result['choiceLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "choiceLst";
            $this->render('choiceLst',array(
                    'choiceLst'     =>  $choiceLst,
                    'pages'         =>  $pages,
                    'teacher'       =>  Teacher::model()->findall()               
            ));
    }


   //宋杰 2015-7-30 点击查看/编辑按钮
    public function actionEditChoice(){
            $exerciseID =   $_GET["exerciseID"];
            $sql        =   "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
            $result     =   Yii::app()->db->createCommand($sql)->query();
            $result     =   $result->read();
            if(!isset($_GET['action']))
            {
                $this->render("editChoice",array(
                    'exerciseID'      =>    $result['exerciseID'],
                    'requirements'    =>    $result['requirements'],
                    'options'         =>    $result['options'],
                    'answer'          =>    $result['answer']
                ));
            }else if($_GET['action'] =='look'){
                $this->render("editChoice",array(
                    'exerciseID'      =>    $result['exerciseID'],
                    'requirements'    =>    $result['requirements'],
                    'options'         =>    $result['options'],
                    'answer'          =>    $result['answer'],
                    'action'          =>    'look'
                ));         
            }
    }
    
    //宋杰 2015-7-30 编辑选择题信息
             public function actionEditChoiceInfo()
	{
            $exerciseID = $_GET['exerciseID'];
            $thisCh     = new Choice();
            $thisCh     = $thisCh->find("exerciseID = '$exerciseID'");
            $thisCh -> requirements =   $_POST['requirements'];
            $thisCh -> options      =   $_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'];
            $thisCh -> answer       =   $_POST['answer'];
            $thisCh -> update();
            $this->render("editChoice",array(
                    'exerciseID'      =>    $exerciseID,
                    'requirements'    =>    $thisCh -> requirements,
                    'options'         =>    $thisCh -> options,
                    'answer'          =>    $thisCh -> answer,
                    'result'          =>    "修改习题成功"
            ));
        }
        
       //宋杰 2015-7-30 添加/编辑选择题的返回
      public function actionReturnFromAddChoice()
        {
            if(Yii::app()->session['lastUrl']=="searchChoice")
            {
                $type       =   Yii::app()->session['searchChoiceType'];
                $value      =   Yii::app()->session['searchChoiceValue'];
           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     =   Choice::model()->getChoiceLst($type, $value);
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     =   Choice::model()->getChoiceLst("", "");
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "choiceLst";
                $this->render('choiceLst',array(
                        'choiceLst' =>  $choiceLst,
                        'pages'     =>  $pages,      
                        'teacher'   =>  Teacher::model()->findall()
                ));
            }
        }
    
    //宋杰 2015-7-30 选择题查找按钮
    public function actionSearchChoice()
        {
            if(isset($_POST['type'])){
                $type   =   $_POST['type'];
                $value  =   $_POST['value'];
                Yii::app()->session['searchChoiceType']     =   $type;
                Yii::app()->session['searchChoiceValue']    =   $value;
            } else {
                $type   =   Yii::app()->session['searchChoiceType'];
                $value  =   Yii::app()->session['searchChoiceValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchChoice";
           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }

            $result     =   Choice::model()->getChoiceLst($type, $value);
            $choiceLst  =   $result['choiceLst'];
            $pages      =   $result['pages'];
            $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages     ,
                            'teacher'   =>  Teacher::model()->findall(),
                            'searchKey'  => $searchKey
                    )
                    );
        }
    
    
    
//宋杰 2015-7-30 老师添加选择题
    public function actionAddChoice(){ 
            $result =   'no';
            if(isset($_POST['requirements'])){          
                $result = Choice::model()->insertChoice($_POST['requirements'], $_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'], $_POST['answer'], Yii::app()->session['userid_now']);
            }
            $this->render('addChoice',['result'=>$result]);
    }
    
    
            public function actionEditFill(){
            $exerciseID =   $_GET["exerciseID"];
            $thisFill   =   new Filling();
            $thisFill   =   $thisFill->find("exerciseID = '$exerciseID'");
            if(!isset($_GET['action']))
            {
                $this->render("editFill",array(
                        'exerciseID'    =>  $exerciseID,
                        'requirements'  =>  $thisFill->requirements,
                        'answer'        =>  $thisFill->answer
                ));
            }else if($_GET['action']='look'){
                $this->render("editFill",array(
                        'exerciseID'    =>  $exerciseID,
                        'requirements'  =>  $thisFill->requirements,
                        'answer'        =>  $thisFill->answer,
                        'action'        =>  'look'
                ));
            }
        }
        public function actionEditFillInfo()
	{
            $exerciseID     =   $_GET['exerciseID'];
            $thisFill       =   new Filling();
            $thisFill       =   $thisFill->find("exerciseID = '$exerciseID'");
            $i      =   2;
            $answer =   $_POST['in1'];
            for(;$i<=5;$i++)
            {
                if($_POST['in'.$i]!="")
                    $answer =    $answer."$$".$_POST['in'.$i];
                else
                    break;
            }
            $thisFill->requirements =   $_POST['requirements'];
            $thisFill->answer       =   $answer;
            $thisFill -> update();
            $this->render("editFill",array(
                'exerciseID'      =>  $thisFill->exerciseID,
                'requirements'    =>  $thisFill->requirements,
                'answer'          =>  $thisFill->answer,
                'result'          =>  "修改习题成功"
            ));
        }
        
    public function actionDeleteChoice(){
            $exerciseID = $_GET["exerciseID"];
            $thisChoice = new Choice();
            $deleteResult = $thisChoice->deleteAll("exerciseID = '$exerciseID'");           
           if(Yii::app()->session['lastUrl']=="searchChoice")
            {
                $type       =   Yii::app()->session['searchChoiceType'];
                $value      =   Yii::app()->session['searchChoiceValue'];
           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     =   Choice::model()->getChoiceLst($type, $value);
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'deleteResult'    =>  $deleteResult,
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     =   Choice::model()->getChoiceLst("", "");
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "choiceLst";
                $this->render('choiceLst',array(
                        'choiceLst' =>  $choiceLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'deleteResult'      =>  $deleteResult,
                       
                ));
            }                   
        }
        
        public function actionCopyChoice(){
            $insertresult = "no";
            $code = $_GET["code"];
            if($code != Yii::app()->session['code']){
                            $exerciseID =   $_GET["exerciseID"];
            $thisChoice = new Choice();
            $oldChoice = $thisChoice->findAll("exerciseID = '$exerciseID'");
            $insertresult =  Choice::model()->insertChoice($oldChoice[0]['requirements'], $oldChoice[0]['options'], $oldChoice[0]['answer'], Yii::app()->session['userid_now']);
            Yii::app()->session['code'] = $_GET["code"];
            }

            
            if(Yii::app()->session['lastUrl']=="searchChoice")
            {
                $type       =   Yii::app()->session['searchChoiceType'];
                $value      =   Yii::app()->session['searchChoiceValue'];
            if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     =   Choice::model()->getChoiceLst($type, $value);
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult,
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     =   Choice::model()->getChoiceLst("", "");
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "choiceLst";
                $this->render('choiceLst',array(
                        'choiceLst' =>  $choiceLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            }
            
        }



//宋杰 2015-7-30 简答题列表
    public function actionQuestionLst()
    {       
            Yii::app()->session['lastUrl']  =   "questionLst";
            $result      =  Question::model()->getQuestionLst("", "");
            $questionLst =  $result['questionLst'];  
            $pages       =  $result["pages"];
            $this->render('questionLst',array(
                    'questionLst'   =>  $questionLst,
                    'pages'         =>  $pages,
                    'teacher'   =>  Teacher::model()->findall()
                    
            ));
    }
    
    
            public function actionEditQuestionInfo(){
            $exerciseID    =   $_GET['exerciseID'];
            $thisQue       =   new Question();
            $thisQue       =   $thisQue->find("exerciseID = '$exerciseID'");
            $thisQue->requirements =   $_POST['requirements'];
            $thisQue->answer       =   $_POST['answer'];
            $thisQue -> update();
            $this->render("editQuestion",array(
                'exerciseID'      =>  $thisQue->exerciseID,
                'requirements'    =>  $thisQue->requirements,
                'answer'          =>  $thisQue->answer,
                'result'          =>  "修改习题成功"
            ));
        }

        //宋杰 2015-7-30 编辑/修改问题
    public function actionEditQuestion(){
            $exerciseID =   $_GET["exerciseID"];
            $sql        =   "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
            $result     =   Yii::app()->db->createCommand($sql)->query();
            $result     =   $result->read();
            if(!isset($_GET['action']))
            {
                $this->render("editQuestion",array(
                    'exerciseID'      =>    $result['exerciseID'],
                    'requirements'    =>    $result['requirements'],
                    'answer'          =>    $result['answer']
                ));
            }else if($_GET['action'] =='look'){
                $this->render("editQuestion",array(
                    'exerciseID'      =>    $result['exerciseID'],
                    'requirements'    =>    $result['requirements'],
                    'answer'          =>    $result['answer'],
                    'action'          =>    'look'
                ));         
            }
    }
    
   //查找简单题
            public function actionSearchQuestion()
        {
            if(isset($_POST['type'])){
                $type   =   $_POST['type'];
                $value  =   $_POST['value'];
                Yii::app()->session['searchQuestionType']   =   $type;
                Yii::app()->session['searchQuestionValue']  =   $value;
            } else {
                $type   =   Yii::app()->session['searchQuestionType'];
                $value  =   Yii::app()->session['searchQuestionValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchQuestion";
                       if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
            if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }

            $result      =  Question::model()->getQuestionLst($type, $value);
            $questionLst =  $result['questionLst'];  
            $pages       =  $result["pages"];           
            $this->render('searchQuestion',array(
                    'questionLst'   =>  $questionLst,
                    'pages'         =>  $pages,
                'teacher'=>  Teacher::model()->findall(),
                'searchKey'  => $searchKey
            ));
        }

    public function actionAddQuestion(){
            $result =   'no';
            if(isset($_POST['requirements'])){
                $result =   Question::model()->insertQue($_POST['requirements'], $_POST['answer'], Yii::app()->session['userid_now']);
            }
            $this->render('addQuestion',['result'   =>  $result]);
    }     
    public function actionReturnFromAddQuestion()
        {
            if(Yii::app()->session['lastUrl']=="searchQuestion")
            {
                $type       =   Yii::app()->session['searchQuestionType'];
                $value      =   Yii::app()->session['searchQuestionValue'];
           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     =   Question::model()->getQuestionLst($type, $value);
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                $this->render('searchQuestion',array(
                            'questionLst' =>  $questionLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     =   Question::model()->getQuestionLst("", "");
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "QuestionLst";
                $this->render('QuestionLst',array(
                        'questionLst' =>  $questionLst,
                        'pages'     =>  $pages,      
                        'teacher'   =>  Teacher::model()->findall()
                ));
            }
        }
        
        public function actionDeleteQuestion(){
            $exerciseID = $_GET["exerciseID"];
            $thisQuestion = new Question();
            $deleteResult = $thisQuestion->deleteAll("exerciseID = '$exerciseID'");           
           if(Yii::app()->session['lastUrl']=="searchQuestion")
            {
                $type       =   Yii::app()->session['searchQuestionType'];
                $value      =   Yii::app()->session['searchQuestionValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }
                $result     = Question::model()->getQuestionLst($type, $value);
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                $this->render('searchQuestion',array(
                            'questionLst' =>  $questionLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'deleteResult'    =>  $deleteResult,
                            'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = Question::model()->getQuestionLst("", "");
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "QuestionLst";
                $this->render('QuestionLst',array(
                        'questionLst' =>  $questionLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'deleteResult'      =>  $deleteResult
                ));
            }                   
        }
        
        public function actionCopyQuestion(){
            $insertresult = "no";
            $code = $_GET["code"];
            if($code != Yii::app()->session['code']){
                            $exerciseID =   $_GET["exerciseID"];
            $thisQuestion = new Question();
            $oldQuestion = $thisQuestion->findAll("exerciseID = '$exerciseID'");
            $insertresult = Question::model()->insertQue($oldQuestion[0]['requirements'], $oldQuestion[0]['answer'], Yii::app()->session['userid_now']);
            Yii::app()->session['code'] = $_GET["code"];
            }

            
           if(Yii::app()->session['lastUrl']=="searchQuestion")
            {
                $type       =   Yii::app()->session['searchQuestionType'];
                $value      =   Yii::app()->session['searchQuestionValue'];
                           if($type=='createPerson')
            {
                if($value   ==  "管理员")
                    $vaule  =   0;
                else
                {
                    $tea    =  Teacher::model()->find("userName = '$value'");
                    if($tea['userID']!="")
                        $value =$tea['userID'];
                    else 
                        $value  =    -1;
                }
            }
                        if($type == "requirements")
            {
                $searchKey = $value;
            }else
            {
                $searchKey = "no";
            }

                $result     = Question::model()->getQuestionLst($type, $value);
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                $this->render('searchQuestion',array(
                            'questionLst' =>  $questionLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall(),
                            'result'    =>  $insertresult    ,
                    'searchKey'  => $searchKey
                    )
                    );
            }else {
                $result     = Question::model()->getQuestionLst("", "");
                $questionLst  =   $result['questionLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "QuestionLst";
                $this->render('QuestionLst',array(
                        'questionLst' =>  $questionLst,
                        'pages'     =>  $pages,
                        'teacher'   =>  Teacher::model()->findall(),
                        'result'      =>  $insertresult
                ));
            }            
        }
        
     public  function ActionAssignWork(){
         $teacherID = Yii::app()->session['userid_now'];
         $teacher_class = TeacherClass::model()->findAll("teacherID = '$teacherID'");
         $array_lesson = array();
         $array_suite = array();
         $array_class = array();
         Yii::app()->session['currentClass'] = 0;
         Yii::app()->session['currentLesson'] = 0;
         
         if(!empty($teacher_class))
         {
           if(isset($_GET['classID']))
             Yii::app()->session['currentClass'] = $_GET['classID'];
           else
             Yii::app()->session['currentClass'] = $teacher_class[0]['classID'];
           
           foreach ($teacher_class as $class)
             {
                 $id = $class['classID'];
                 $result = TbClass::model()->findAll("classID ='$id'");               
                 array_push($array_class, $result[0]);
             }     
             $currentClass = Yii::app()->session['currentClass'];
             $array_lesson = Lesson::model()->findAll("classID = '$currentClass'"); 
             if(!empty($array_lesson))
             {
                if(isset($_GET['lessonID']))
                   Yii::app()->session['currentLesson'] = $_GET['lessonID'];
                else
                   Yii::app()->session['currentLesson'] = $array_lesson[0]['lessonID'];
                $currentLesson = Yii::app()->session['currentLesson'];
                 $array_suite = Suite::model()->findAll("lessonID = '$currentLesson'");
             }
         }       
         $this->render('assignWork',array(
             'array_class' => $array_class,
             'array_lesson' => $array_lesson,
             'array_suite'  => $array_suite,
         ));
     }
     
     public function ActionModifyChoiceWork(){              
         $suiteID = $_GET['suiteID'];
         $type = "choice";
         $currentLesson = Yii::app()->session['currentLesson'];
         $currentClass = Yii::app()->session['currentClass'];
         $class = TbClass::model()->findAll("classID='$currentClass'")[0];
         $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
         $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
         $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type,5);
         $workChoice = $result['workLst'];
         $page1 = $result['pages'];
         $result = Choice::model()->getChoiceLst1("", "",5);
         $choiceLst = $result['choiceLst'];
         $page2 = $result['pages'];        
         $this->render('modifyWork' , array(
             'suite' => $suite,
             'currentClass' =>$class,
             'currentLesson'=>$lesson,
             'suiteWork' => $workChoice,
             'workLst' => $choiceLst,
             'page1' => $page1,
             'page2' =>$page2,
             'teacher'=>  Teacher::model()->findall(),      
             'type' => $type
         ));
     }
     
     
     public function ActionDeleteSuite()
     {
         $type = $_GET['type'];
         $exerciseID = $_GET['exerciseID'];
         $suiteID = $_GET['suiteID'];
         $thisSuiteExercise = new SuiteExercise();
         $deleteResult = $thisSuiteExercise->deleteAll("exerciseID = '$exerciseID' and type = '$type' and suiteID = '$suiteID'");
         $currentLesson = Yii::app()->session['currentLesson'];
         $currentClass = Yii::app()->session['currentClass'];
         $class = TbClass::model()->findAll("classID='$currentClass'")[0];
         $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
         $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
         $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type,5);
         $workChoice = $result['workLst'];
         $page1 = $result['pages'];
         $result = Choice::model()->getChoiceLst1("", "",5);
         $choiceLst = $result['choiceLst'];
         $page2 = $result['pages'];
         
         $this->render('modifyWork' , array(
             'suite' => $suite,
             'currentClass' =>$class,
             'currentLesson'=>$lesson,
             'suiteWork' => $workChoice,
             'workLst' => $choiceLst,
             'page1' => $page1,
             'page2' =>$page2,
             'teacher'=>  Teacher::model()->findall(),
             'type' => $type
         ));
     }
     
     
       public function ActionModifyFillingWork(){              
         $suiteID = $_GET['suiteID'];
         $type = "filling";
         $currentLesson = Yii::app()->session['currentLesson'];
         $currentClass = Yii::app()->session['currentClass'];
         $class = TbClass::model()->findAll("classID='$currentClass'")[0];
         $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
         $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
         $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type,5);
         $workFilling = $result['workLst'];
         $page1 = $result['pages'];
         $result = Filling::model()->getFillLstPage("", "",5);
         $fillingLst = $result['fillLst'];
         $page2 = $result['pages'];        
         $this->render('modifyWork' , array(
             'suite' => $suite,
             'currentClass' =>$class,
             'currentLesson'=>$lesson,
             'suiteWork' => $workFilling,
             'workLst' => $fillingLst,
             'page1' => $page1,
             'page2' =>$page2,
             'teacher'=>  Teacher::model()->findall(),      
             'type' => $type
         ));
     }
     
     
      public function ActionModifyQuestionWork(){              
         $suiteID = $_GET['suiteID'];
         $type = "question";
         $currentLesson = Yii::app()->session['currentLesson'];
         $currentClass = Yii::app()->session['currentClass'];
         $class = TbClass::model()->findAll("classID='$currentClass'")[0];
         $lesson = Lesson::model()->findAll("lessonID='$currentLesson'")[0];
         $suite = Suite::model()->findAll("suiteID = '$suiteID'")[0];
         $result = Suite::model()->getSuiteExerByTypePage($suiteID, $type,5);
         $workQuestion = $result['workLst'];
         $page1 = $result['pages'];
         $result = Question::model()->getQuestionLstPage("", "",5);
         $questionLst = $result['questionLst'];
         $page2 = $result['pages'];        
         $this->render('modifyWork' , array(
             'suite' => $suite,
             'currentClass' =>$class,
             'currentLesson'=>$lesson,
             'suiteWork' => $workQuestion,
             'workLst' => $questionLst,
             'page1' => $page1,
             'page2' =>$page2,
             'teacher'=>  Teacher::model()->findall(),      
             'type' => $type
         ));
     }
     
     
     
     
}
