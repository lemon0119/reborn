<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//crt by LC 2015-4-21

class TeacherController extends CController {
    
    public $layout='//layouts/teacherBar';
    
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
    {       
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加班级
            if($_GET['action']=='add')
           {
               if(!empty($_POST['que1'])&&!empty($_POST['que2'])&&!empty($_POST['answer']))
               {
                   //得到当前最大的班级ID
                   $sql="select max(exerciseID) as id from filling";
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
                   $sql= "INSERT INTO filling VALUES ('".$new_id ."','0','" .$_POST['que1']."$$".$_POST['que2']."','".$_POST['answer']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";

                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="添加习题成功！";
                   unset($_GET['action']);
               }else
               {
                   //用户输入参数不足
                   $this->render('addFill',array(
                                                'shao'=>"输入全不能为空"
                                                ));
                   return;
               }
           }else if($_GET['action']=='edit'){
                $sql= "UPDATE filling SET requirements= '".$_POST['que1']."$$".$_POST['que2'] ."', answer ='".$_POST['answer']."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
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
        $sql = "SELECT * FROM filling ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('fillLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }

    public function actionEditFill(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM filling WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editFill",array(
            'exerciseID'=>$result['exerciseID'],
            'requirements' =>$result['requirements'],
            'answer' =>$result['answer']
        ));
    }

    public function actionAddFill(){
        $this->render("addFill",array(

        ));
    }

    public function actionChoiceLst()
    {       
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加班级
            if($_GET['action']=='add')
           {
               if(!empty($_POST['requirements'])&&!empty($_POST['answer'])&&!empty($_POST['A'])&&!empty($_POST['B'])&&!empty($_POST['C'])&&!empty($_POST['D']))
               {
                   //得到当前最大的班级ID
                   $sql="select max(exerciseID) as id from choice";
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
                   $sql= "INSERT INTO choice VALUES ('".$new_id ."','danxuan','0','" .$_POST['requirements']."','".$_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'] ."','".$_POST['answer']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";

                   Yii::app()->db->createCommand($sql)->query();
                   $act_result="添加习题成功！";
                   unset($_GET['action']);
               }else
               {
                   //用户输入参数不足
                   $this->render('addChoice',array(
                                                'shao'=>"输入全不能为空"
                                                ));
                   return;
               }
           }else if($_GET['action']=='edit'){
                $sql= "UPDATE choice SET requirements= '".$_POST['requirements'] ."',options = '".$_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'] ."',answer ='".$_POST['answer']."'  WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
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
        $sql = "SELECT * FROM choice ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('choiceLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }


    public function actionEditChoice(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editChoice",array(
            'exerciseID'=>$result['exerciseID'],
            'requirements' =>$result['requirements'],
            'options'=>$result['options'],
            'answer' =>$result['answer']
        ));
    }

    public function actionAddChoice(){
        $this->render("addChoice",array(

        ));
    }


    public function actionQuestionLst()
    {       
        //定义动作
        $act_result="";

       //添加动作
       if(isset($_GET['action']))
       {
               //添加班级
            if($_GET['action']=='add')
           {
               if(!empty($_POST['requirements'])&&!empty($_POST['answer']))
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
                   $sql= "INSERT INTO question VALUES ('".$new_id ."','0','" .$_POST['requirements']."','".$_POST['answer']."','".Yii::app()->session['userid_now']."','". date('y-m-d H:i:s',time()) ."','')";

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
                $sql= "UPDATE question SET requirements= '".$_POST['requirements']."', answer='".$_POST['answer'] ."' WHERE exerciseID= '" .$_GET['exerciseID']."'" ;
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
        $sql = "SELECT * FROM question ".$ex_sq;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        $this->render('questionLst',array(
        'posts'=>$posts,
        'pages'=>$pages,
        'teacher'=>$this->teaInClass(),
        'result'=>$act_result,
        ),false,true);
    }

    public function actionEditQuestion(){
        $exerciseID=$_GET["exerciseID"];
        $sql = "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result =$result->read();
        $this->render("editQuestion",array(
            'exerciseID'=>$result['exerciseID'],
            'requirements' =>$result['requirements'],
            'answer'=>$result['answer']
        ));
    }

    public function actionAddQuestion(){
        $this->render("addQuestion",array(

        ));
    }
    
}