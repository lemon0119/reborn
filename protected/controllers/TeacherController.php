<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//crt by LC 2015-4-21

class TeacherController extends CController {
    
    public $layout='//layouts/teacherBar';
    
    public function actionWebrtc(){
        $teacherID = Yii::app()->session['userid_now'];
        $teacherName = Student::model()->findByPK($teacherID)->userName;
        return $this->render('webrtc',['teacherName'=>$teacherName]);
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
    public function actionVirtualClass(){
        $user_model = new User;
        $username_now=Yii::app()->user->name;
        $info=$user_model->find("username='$username_now'");
        return $this->render('virtualClass',array('info'=>$info));
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
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加
            if($_GET['action']=='add')
           {
               if(!empty($_POST['title'])&&!empty($_POST['content']))
               {
                   //得到当前最大的ID
                   $sql="select max(exerciseID) as id from look_type";
                   $max_id = Yii::app()->db->createCommand($sql)->query();
                   $temp=$max_id->read();
                   if(empty($temp))
                   {
                       $new_id=1;
                   }
                   else
                   {
                       $new_id = $temp['id'] + 1;
                   }
                   $sql= "INSERT INTO look_type VALUES ('".$new_id ."','0','" .$_POST['title']."','".$_POST['content']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";

                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="添加习题成功！";
                    unset($_GET['action']);
               }else
               {
                   //用户输入参数不足
                   $this->render('addLook',array(
                                                'shao'=>"输入全不能为空"
                                                ));
                   return;
               }
           }else if($_GET['action']=='edit'){
                $sql= "UPDATE look_type SET content= '".$_POST['content'] ."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
                Yii::app()->db->createCommand($sql)->query();
                $act_result="编辑习题成功！";
                unset($_GET['action']);
           }
       }


        //搜索习题
        if(isset($_POST['which']))
        {   
            if(!empty($_POST['name']))
            $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
            else  $ex_sq = "";
        }
        else  $ex_sq = "";

        //显示结果列表并分页
        $sql = "SELECT * FROM look_type ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('lookLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }

    public function actionAddLook(){
        $this->render("addLook",array(

        ));
    }

    public function actionEditLook(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editLook",array(
            'exerciseID'=>$result['exerciseID'],
            'title' =>$result['title'],
            'content'=>$result['content']
        ));
    }


    public function actionKeyLst()
    {       
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加
            if($_GET['action']=='add')
           {
               if(!empty($_POST['title'])&&!empty($_POST['content']))
               {
                   //得到当前最大的ID
                   $sql="select max(exerciseID) as id from key_type";
                   $max_id = Yii::app()->db->createCommand($sql)->query();
                   $temp=$max_id->read();
                   if(empty($temp))
                   {
                       $new_id=1;
                   }
                   else
                   {
                       $new_id = $temp['id'] + 1;
                   }
                   $sql= "INSERT INTO key_type VALUES ('".$new_id ."','0','" .$_POST['title']."','".$_POST['content']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";

                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="添加习题成功！";
                    unset($_GET['action']);
               }else
               {
                   //输入参数不足
                   $this->render('addKey',array(
                                                'shao'=>"输入全不能为空"
                                                ));
                   return;
               }
           }else if($_GET['action']=='edit'){
                $sql= "UPDATE key_type SET content= '".$_POST['content'] ."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
                Yii::app()->db->createCommand($sql)->query();
                $act_result="编辑习题成功！";
                unset($_GET['action']);
           }
       }


        //搜索习题
        if(isset($_POST['which']))
        {   
            if(!empty($_POST['name']))
            $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
            else  $ex_sq = "";
        }
        else  $ex_sq = "";

        //显示结果列表并分页
        $sql = "SELECT * FROM key_type ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('keyLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }

    public function actionAddKey(){
        $this->render("addKey",array(

        ));
    }

    public function actionEditKey(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editKey",array(
            'exerciseID'=>$result['exerciseID'],
            'title' =>$result['title'],
            'content'=>$result['content']
        ));
    }


    public function actionListenLst()
    {       
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加班级
            if($_GET['action']=='add')
           {                  
               if(!empty($_POST['title'])&&!empty($_POST['content']))
               {
                   //得到当前最大的ID
                   $sql="select max(exerciseID) as id from listen_type";
                   $max_id = Yii::app()->db->createCommand($sql)->query();
                   $temp=$max_id->read();
                   if(empty($temp))
                   {
                       $new_id=1;
                   }
                   else
                   {
                       $new_id = $temp['id'] + 1;
                   }

                   if($_FILES['file']['type']!='audio/mpeg')
                   {
                       $shao='文件格式错误，应为MP3文件';
                    }else if ($_FILES["file"]["size"] < 200000000)
                    {
                      if ($_FILES["file"]["error"] > 0)
                        {
                          $shao='文件上传失败';
                        }
                      else
                        {
                        if (!file_exists($_FILES["file"]["tmp_name"]))
                          {
                             $shao='服务器上存在相同文件';
                          }
                        else
                          {
                             move_uploaded_file($_FILES["file"]["tmp_name"],'resources/'.iconv("UTF-8","gb2312",$_FILES["file"]["name"]));
                            $shao='成功';
                          }
                        }
                      }else
                      {
                      echo "无效文件";
                      }

                   if($shao=='成功')
                   {
                   $sql= "INSERT INTO listen_type VALUES ('".$new_id ."','0','" .$_POST['content']."','','".$_FILES["file"]["name"]."','".$_POST['title']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";
                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="添加习题成功！";
                   unset($_GET['action']);
                   }else {
                         //文件出现问题
                         $this->render('addListen',array(
                                                'shao'=>$shao
                                                ));
                   return;
                   }
               }else
               {
                   //用户输入参数不足
                   $this->render('addListen',array(
                                                'shao'=>"输入全不能为空"
                                                ));
                   return;
               }
           }else if($_GET['action']=='edit'){

               if(isset($_POST['checkbox']))
               {
                   if($_FILES['file']['type']!='audio/mpeg')
                   {
                       $shao='文件格式错误，应为MP3文件';
                    }else if ($_FILES["file"]["size"] < 200000000)
                      {
                      if ($_FILES["file"]["error"] > 0)
                        {
                          $shao='文件上传失败';
                        }
                      else
                        {
                        if (!file_exists($_FILES["file"]["tmp_name"]))
                          {
                             $shao='服务器上存在相同文件';
                          }
                        else
                          {
                             move_uploaded_file($_FILES["file"]["tmp_name"],'resources/'.iconv("UTF-8","gb2312",$_FILES["file"]["name"]));
                            $shao='成功';
                          }
                        }
                      }
                    else
                      {
                      echo "无效文件";
                      }

                   if($shao=='成功')
                   {
                   $sql= "UPDATE listen_type SET content= '".$_POST['content'] ."', fileName = '".$_FILES["file"]["name"]."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="编辑习题成功！";
                   unset($_GET['action']);
                   }else {
                       //文件出现问题
                        $exerciseID=$_GET["exerciseID"];
                        $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
                        $result = Yii::app()->db->createCommand($sql)->query();
                        $result =$result->read();
                        $this->render("editListen",array(
                            'shao'=>$shao,
                            'exerciseID'=>$result['exerciseID'],
                            'title' =>$result['title'],
                            'content'=>$result['content'],
                            'filename'=>$result['fileName']      
                        ));


                   return;
                   }

               }else{
                $sql= "UPDATE listen_type SET content= '".$_POST['content'] ."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
                Yii::app()->db->createCommand($sql)->query();
                $act_result="编辑习题成功！";
                unset($_GET['action']);
               }
           }
       }


        //搜索习题
        if(isset($_POST['which']))
        {   
            if(!empty($_POST['name']))
            $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
            else  $ex_sq = "";
        }
        else  $ex_sq = "";

        //显示结果列表并分页
        $sql = "SELECT * FROM listen_type ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('listenLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }

    public function actionAddListen(){
        $this->render("addListen",array(

        ));
    }

    public function actionEditlisten(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editListen",array(
            'exerciseID'=>$result['exerciseID'],
            'title' =>$result['title'],
            'content'=>$result['content'],
            'filename'=>$result['fileName']      
        ));
    }

    public function actionFillLst()
    {       $result = Filling::model()->getTeaFillLst("", "");
            $fillLst=$result['fillLst'];
            $pages=$result['pages'];
            Yii::app()->session['lastUrl']="fillLst";
            $this->render('fillLst',array(
            'fillLst'=>$fillLst,
            'pages'=>$pages,          
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
            $result = Filling::model()->getTeaFillLst($type, $value);
            $fillLst=$result['fillLst'];
            $pages=$result['pages'];
            $this->render('searchFill',array(
                        'fillLst'=>$fillLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall()
                    )
                    );
        }
    
           public function actionReturnFromAddFill(){
            if(Yii::app()->session['lastUrl']=="searchFill")
            {
                $type = Yii::app()->session['searchFillType'];
                $value = Yii::app()->session['searchFillValue'];
                $result = Filling::model()->getTeaFillLst($type, $value);
                $fillLst=$result['fillLst'];
                $pages=$result['pages'];
                $this->render('searchFill',array(
                        'fillLst'=>$fillLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall()
                        )
                    );
            }else{
                $result = Filling::model()->getTeaFillLst("", "");
                $fillLst=$result['fillLst'];
                $pages=$result['pages'];
                Yii::app()->session['lastUrl']="fillLst";
                $this->render('fillLst',array(
                    'fillLst'=>$fillLst,
                    'pages'=>$pages,
                    'teacher'=>  Teacher::model()->findall()
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
                        $answer =   $answer."$$".$_POST['in'.$i];
                    else
                        break;
                }
                $result =   Filling::model()->insertFill($_POST['requirements'], $answer, Yii::app()->session['userid_now']);
            }
            $this->render('addFill',['result'   =>  $result]);
    }

    
    //宋杰 2015-7-30 选择题列表
    public function actionChoiceLst()
    {       
            
            $teachr_id  =   Yii::app()->session['userid_now'];
            $result     =   Choice::model()->getChoiceLst("createPerson", $teachr_id);
            $choiceLst  =   $result['choiceLst'];
            $pages      =   $result['pages'];
            Yii::app()->session['lastUrl']  =   "choiceLst";
            $this->render('choiceLst',array(
                    'choiceLst'     =>  $choiceLst,
                    'pages'         =>  $pages,
                    
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
                $result     =   Choice::model()->getChoiceLst($type, $value);
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages,
                            'teacher'   =>  Teacher::model()->findall()
                    )
                    );
            }else {
                $result     =   Choice::model()->getChoiceLst("createPerson",Yii::app()->session['userid_now'] );
                $choiceLst  =   $result['choiceLst'];
                $pages      =   $result['pages'];
                Yii::app()->session['lastUrl']  =   "choiceLst";
                $this->render('choiceLst',array(
                        'choiceLst' =>  $choiceLst,
                        'pages'     =>  $pages,                     
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
     
            $result     =   Choice::model()->getTeaChoiceLst($type, $value);
            $choiceLst  =   $result['choiceLst'];
            $pages      =   $result['pages'];
            $this->render('searchChoice',array(
                            'choiceLst' =>  $choiceLst,
                            'pages'     =>  $pages                          
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
    

//宋杰 2015-7-30 简答题列表
    public function actionQuestionLst()
    {       
                   Yii::app()->session['lastUrl']  =   "questionLst";
            $result      =  Question::model()->getTeaQuestionLst("", "");
            $questionLst =  $result['questionLst'];  
            $pages       =  $result["pages"];
            $this->render('questionLst',array(
                    'questionLst'   =>  $questionLst,
                    'pages'         =>  $pages,
                    
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
                Yii::app()->session['searchType']   =   $type;
                Yii::app()->session['searchValue']  =   $value;
            } else {
                $type   =   Yii::app()->session['searchType'];
                $value  =   Yii::app()->session['searchValue'];
            }
            Yii::app()->session['lastUrl']  =   "searchQuestion";
            $result      =  Question::model()->getTeaQuestionLst($type, $value);
            $questionLst =  $result['questionLst'];  
            $pages       =  $result["pages"];
            $this->render('searchQuestion',array(
                    'questionLst'   =>  $questionLst,
                    'pages'         =>  $pages,
            ));
        }

    public function actionAddQuestion(){
            $result =   'no';
            if(isset($_POST['requirements'])){
                $result =   Question::model()->insertQue($_POST['requirements'], $_POST['answer'], Yii::app()->session['userid_now']);
            }
            $this->render('addQuestion',['result'   =>  $result]);
    }
     
}