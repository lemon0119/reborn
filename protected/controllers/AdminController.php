<?php

class AdminController extends CController
{
    public $layout='//layouts/adminBar';
	public function actionIndex()
	{        
	    $this->render('index');
	}
     
        
        public function actionChangeLog()
	{           
            $sql = "SELECT changeLog FROM course WHERE courseID=".$_GET['courseID'];
            $result = Yii::app()->db->createCommand($sql)->query();
            $log=$result->read()['changeLog'];
            $this->render('changeLog',array(
                                             'log'=>$log,
                                             'source'=>$_GET['source'],
                                             'teacher'=>$this->teaInClass()
                ));
        }
        
    	public function actionStuLst()
	{       
            //定义动作
            $act_result="";
            
            
            //搜索动作
            if(isset($_POST['which']))
            {   
                if(!empty($_POST['name']))
                {
                    if($_POST['which']=='classID'&&$_POST['name']=="无")
                        $ex_sq =" WHERE ". $_POST['which'].  " = '0'";
                    else $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
                }
                else  $ex_sq = "";
            }
            else  $ex_sq = "";
            
            //删除动作
            if(isset($_GET['flag']))
            {
                if($_GET['flag']=='delete')
                {
                    $sql ="DELETE FROM student WHERE userID =" . $_GET['id'];
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="删除成功！";
                    unset($_GET['flag']);
                }
            }
            
            //添加动作
            if(isset($_GET['action']))
            {
                //添加学生
                 if($_GET['action']=='add')
                {
                    if(!empty($_POST['userName']) and !empty($_POST['password']))
                    {
                         if(empty($_POST['classID'])||$this->exClass($_POST['classID']))
                         {
                        //得到当前最大的学生ID
                             $sql="select max(userID) as id from student";
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
                             if(!empty($_POST['classID']))
                             $sql= "INSERT INTO student VALUES ('".$new_id ."','" .$_POST['userName'] ."','".$_POST['password'] ."','" .$_POST['classID']."')";
                             else
                             $sql= "INSERT INTO student VALUES ('".$new_id ."','" .$_POST['userName'] ."','".$_POST['password'] ."','0')";    
                             Yii::app()->db->createCommand($sql)->query();
                             $act_result="添加学生成功！";
                             unset($_GET['action']);
                         }  else {
                             //不存在该班级
                             $this->render('addStu',array(
                                                     'shao'=>"不存在该班级"
                                                     ));
                             return;
                         }
                         
                    }else
                    {
                        //用户输入参数不足
                        $this->render('addStu',array(
                                                     'shao'=>"输入全不能为空"
                                                     ));
                        return;
                    }
                } else if($_GET['action']=='edit')
                {
                    //更改学生信息
                    if(!empty($_POST['password']))
                    {
                    //更新学生信息
                    $sql= "UPDATE student SET password= '".$_POST['password'] ."' WHERE userID= '" .$_GET['id']."'" ;
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="编辑学生信息成功！";
                    unset($_GET['action']);
                    }else
                    {
                        //用户输入参数不足
                        $this->render('editStu',array(
                                                    'id'=>$_GET['id'],
                                                    'name'=>$_GET['name'],
                                                    'class'=>$_GET['class'],
                                                     'shao'=>"null"
                                                     ));
                        return;
                    }
                }
                
            }
            
            //显示结果列表并分页
	    $sql = "SELECT * FROM student ". $ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('stuLst',array(
            'posts'=>$posts,
            'pages'=>$pages,
            'result'=>$act_result,
            ),false,true);
	}
            
         public function actionAddStu()
	{        
            $this->render('addStu');
        }
        
        public function actionInfoStu()
	{        
            $this->render('infoStu',array(
                                             'id'=>$_GET['id'],
                                             'name'=>$_GET['name'],
                                             'class'=>$_GET['class'],
                    ));
        }

        public function actionEditStu()
	{           
            $this->render('editStu',array(
                                             'id'=>$_GET['id'],
                                             'name'=>$_GET['name'],
                                            'class'=>$_GET['class']
                                         ));
        }
        
        //是否存在指定班级
        public function exClass($classID)
        {
            $sql = "select * from tb_class where classID = '$classID'";
            $course = Yii::app()->db->createCommand($sql)->query();
            if(empty($course->read()))
                return FALSE;
            else
                return TRUE;
        }
        
        public function actionTeaLst()
	{        
            $act_result="";
                        
            //删除老师
            if(isset($_GET['flag']))
            {
                if($_GET['flag']=='delete')
                {
                    $sql ="DELETE FROM teacher WHERE userID =" . $_GET['id'];
                    Yii::app()->db->createCommand($sql)->query();
                    $sql ="DELETE FROM teacher_class WHERE teacherID =" . $_GET['id'];
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="删除老师成功！";
                }         
            }    
            
            
            //添加动作
            if(isset($_GET['action']))
            {
                //添加老师
                 if($_GET['action']=='add')
                {
                    if(!empty($_POST['userName']) and !empty($_POST['password']))
                    {
                    //得到当前最大的老师ID
                    $sql="select max(userID) as id from teacher";
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
                    $sql= "INSERT INTO teacher VALUES ('".$new_id ."','" .$_POST['userName'] ."','".$_POST['password'] ."')";
                    
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="添加老师成功！";
                    unset($_GET['action']);
                    }else
                    {
                        //用户输入参数不足
                        $this->render('addTea',array(
                                                     'shao'=>"null"
                                                     ));
                        return;
                    }
                } else if($_GET['action']=='edit')
                {
                    //更改老师信息
                    if(!empty($_POST['password']))
                    {
                    //更新老师信息
                    $sql= "UPDATE teacher SET password= '".$_POST['password']."' WHERE userID= '" .$_GET['id']."'" ;
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="编辑老师信息成功！";
                    unset($_GET['action']);
                    }else
                    {
                        //用户输入参数不足
                        $this->render('editTea',array(
                                                    'id'=>$_GET['id'],
                                                    'name'=>$_GET['name'],
                                                     'shao'=>"null"
                                                     ));
                        return;
                    }
                }
                
            }
            
            //搜索老师
            if(isset($_POST['which']))
            {   
                if(!empty($_POST['name']))
                $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
                else  $ex_sq = "";
            }
            else  $ex_sq = "";
            
        
	    $sql = "SELECT * FROM teacher " . $ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('teaLst',array(
            'posts'=>$posts,
            'pages'=>$pages,
            'result'=>$act_result,
            ),false,true);
	}
        
         public function actionInfoTea()
	{   
            $act_result="";
             
            $teaID=$_GET['id'];
             
             if(isset($_GET['action'])){
                 if($_GET['action']=="delete"){
                    $sql= "DELETE FROM teacher_class WHERE teacherID = '$teaID' AND classID = '".$_GET['classID']."'";
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="删除成功！";
                 }
             }
             
             

            //显示所带班级结果列表并分页
	    $sql = "SELECT * FROM tb_class WHERE classID IN (SELECT classID FROM teacher_class WHERE teacherID = '$teaID')";
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=6;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
             
             if(isset($_GET['classID'])){
                  $this->render('infoTea',array(
                                    'posts'=>$posts,
                                    'pages'=>$pages,
                                    'id'=>$teaID,
                                    'name'=>$_GET['name'],
                                    'flag'=>$_GET['flag'],
                                    'classID'=>$_GET['classID'],
                                    'result'=>$act_result,
                                ));
             }  else {
                    $this->render('infoTea',array(
                                    'posts'=>$posts,
                                    'pages'=>$pages,
                                    'id'=>$teaID,
                                    'name'=>$_GET['name'],
                                    'flag'=>$_GET['flag'],
                                    'result'=>$act_result
                                ));
             }
        }
        
        public function actionAddTea()
	{        
            $this->render('addTea');
        }
        
        public function actionEditTea()
	{        
            $this->render('editTea',array(
                                             'id'=>$_GET['id'],
                                             'name'=>$_GET['name'],
                                         ));
        }
        
        //查看班级人数
        public function numInClass() {
             $sql="SELECT classID,count(classID) FROM student GROUP BY classID;";
             $an = Yii::app()->db->createCommand($sql)->query();
             return $an;
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


        
        public function actionClassLst()
        {
         
        $act_result="";
            
         //添加动作
        if(isset($_GET['action']))
        {
                //添加班级
             if($_GET['action']=='add')
            {
                if(!empty($_POST['className']))
                {
                    //得到当前最大的班级ID
                    $sql="select max(classID) as id from tb_class";
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
                    $sql= "INSERT INTO tb_class VALUES ('".$new_id ."','" .$_POST['className']."','" ."')";

                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="添加班级成功！";
                    unset($_GET['action']);
                }else
                {
                    //用户输入参数不足
                    $this->render('addStu',array(
                                                 'shao'=>"输入班级名不能为空"
                                                 ));
                    return;
                }
            }
        }
            
             //搜索动作
            if(isset($_POST['which']))
            {   
                if (!empty($_POST['name'])) {
                    if ($_POST['which'] == "classID")
                    {
                        $ex_sq = " WHERE " . $_POST['which'] . " = '" . $_POST['name'] . "'";
                    }else{
                 
                        if($_POST['which'] == "teaName")
                        {
                            $sql="SELECT * FROM teacher WHERE userName ='". $_POST['name'] . "'";
                            $an = Yii::app()->db->createCommand($sql)->query();
                            $temp=$an->read();
                            if(!empty($temp))
                                $teaID=$temp['userID'];
                            else $teaID=-1;
                        }
                        else if($_POST['which'] == "teaID")
                        {
                            $teaID=$_POST['name'];
                        }
                        $sql="SELECT * FROM teacher_class WHERE teacherID ='". $teaID . "'";
                        $an = Yii::app()->db->createCommand($sql)->query();          
                        $temp =$an->read();
                        if(!empty($temp))
                        {
                            $ex_sq= " WHERE ";
                            $id=$temp['classID'];
                            $ex_sq =$ex_sq ."classID = '$id'";
                            $temp =$an->read(); 
                            while(!empty($temp))
                            {
                                $id=$temp['classID'];
                                $ex_sq =$ex_sq ." OR classID = '$id'";
                                $temp =$an->read();
                            }
                        }  else {
                            $ex_sq=" WHERE classID = 0";    
                        }

                    }
                }
                else
                $ex_sq = "";
            }
            else  $ex_sq = "";
            
             //显示结果列表并分页
	    $sql = "SELECT * FROM tb_class " .$ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('classLst',array(
            'posts'=>$posts,
            'pages'=>$pages,
            'nums'=>$this->numInClass(),
            'teacher'=>$this->teaInClass(),
            'teacherOfClass' =>$this->teaByClass(),
            'result'=>$act_result,
            ),false,true);
        }
        
        public function actionAddClass()
	{        
            $this->render('addClass');
        }
        
        public function actionInfoClass()
	{   
            $act_result="";
            $classID=$_GET["classID"];
            
            //删除某学生的班级
            if(isset($_GET['flag']))
            {
                if($_GET['flag']=='deleteStu')
                {
                    $sql= "UPDATE student SET classID= '0' WHERE userID= '" .$_GET['id']."'" ;
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="删除成功！";
                }else if($_GET['flag']=='deleteTea')
                {
                   $sql= "DELETE FROM teacher_class WHERE teacherID = '".$_GET['id']."' AND classID = '".$classID."'";
                    Yii::app()->db->createCommand($sql)->query();
                    $act_result="删除成功！";
                }
                unset($_GET['flag']);
            }
            

            
            if(isset($_GET['action'])&&isset($_POST['checkbox'])){
            $checkbox = $_POST['checkbox'];
            if($_GET['action']=="addStu")
            {
                for($i=0;$i<count($checkbox);$i++) 
                { 
                    if(!is_null($checkbox[$i])) 
                    {
                        $stuID=$checkbox[$i];
                        $sql= "UPDATE student SET classID= '".$classID ."' WHERE userID= '".$stuID."'" ;
                        Yii::app()->db->createCommand($sql)->query();
                    }                   
                }
                $act_result="添加 $i 名学生成功！";
            }else if($_GET['action']=="addTea"){
                for($i=0;$i<count($checkbox);$i++) 
                { 
                    if(!is_null($checkbox[$i])) 
                    {
                        $teaID=$checkbox[$i];
                        $sql= "INSERT INTO teacher_class VALUES ('".$teaID ."','" .$classID ."','')";
                        Yii::app()->db->createCommand($sql)->query();
                    }                   
                }
                $act_result="添加 $i 位老师成功！";
            }
            }
            
             $sql = "SELECT * FROM tb_class WHERE classID = '$classID'";
             $an=Yii::app()->db->createCommand($sql)->query();
             $class=$an->read();
             $className=$class['className'];
             $curCourse=$class['currentCourse'];
             
            //显示结果列表并分页
	    $sql = "SELECT * FROM student WHERE classID = '$classID'";
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $nums=$result->rowCount;
            $pages=new CPagination($nums);
            $pages->pageSize=8;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            
            $sql="SELECT * FROM teacher_class WHERE classID =$classID";
            $teacherOfClass = Yii::app()->db->createCommand($sql)->query();
            
            $this->render('infoCLass',array(
                                             'classID'=>$classID,
                                             'className'=>$className,
                                             'curCourse'=>$curCourse,
                                             'teacher'=>$this->teaInClass(),
                                             'teacherOfClass' =>$teacherOfClass,
                                             'nums'=>$nums,        //学生人数
                                             'posts'=>$posts,      //学生
                                             'pages'=>$pages,      //分页
                                            'result'=>$act_result,
                    ),false,true);
        }
        
        public function actionAddStuClass()
	{        
            $sql = "SELECT * FROM student WHERE classID = '0'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $this->render('addStuClass',array(
                                                'classID'=>$_GET["classID"],
                                                'posts'=>$result
                                                
            ));
        }
        public function actionAddTeaClass()
	{        
            $classID=$_GET["classID"];
            $sql = "SELECT teacherID FROM teacher_class WHERE classID = '$classID'  order by teacherID ASC";
            $result = Yii::app()->db->createCommand($sql)->query();
            $this->render('addTeaClass',array(
                                                'classID'=>$classID,
                                                'posts'=>$result,
                                                'teachers'=>$this->teaInClass(),
                                                
            ));
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
                       $sql= "INSERT INTO look_type VALUES ('".$new_id ."','0','" .$_POST['title']."','".$_POST['content']."','0','". date('y-m-d H:i:s',time()) ."','')";

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
                       $sql= "INSERT INTO key_type VALUES ('".$new_id ."','0','" .$_POST['title']."','".$_POST['content']."','0','". date('y-m-d H:i:s',time()) ."','')";

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
                       $sql= "INSERT INTO listen_type VALUES ('".$new_id ."','0','" .$_POST['content']."','','".$_FILES["file"]["name"]."','".$_POST['title']."','0','". date('y-m-d H:i:s',time()) ."','')";
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
                       $sql="select * from listen_type WHERE exerciseID='".$_GET['exerciseID']."'";
                       $result = Yii::app()->db->createCommand($sql)->query();
                       $result=$result->read();
                       unlink('resources/'.$result['fileName']);
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
                       $sql= "INSERT INTO filling VALUES ('".$new_id ."','0','" .$_POST['que1']."$$".$_POST['que2']."','".$_POST['answer']."','0','". date('y-m-d H:i:s',time()) ."','')";

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
                       $sql= "INSERT INTO choice VALUES ('".$new_id ."','danxuan','0','" .$_POST['requirements']."','".$_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'] ."','".$_POST['answer']."','0','". date('y-m-d H:i:s',time()) ."','')";

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
                       $sql="select max(exerciseID) as id from question";
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
                       $sql= "INSERT INTO question VALUES ('".$new_id ."','0','" .$_POST['requirements']."','".$_POST['answer']."','0','". date('y-m-d H:i:s',time()) ."','')";

                       Yii::app()->db->createCommand($sql)->query();
                       $act_result="添加习题成功！";
                       unset($_GET['action']);
                   }else
                   {
                       //用户输入参数不足
                       $this->render('addQuestion',array(
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
        
        public function actionCourseLst()
	{       
            //定义动作
            $act_result="";
            
            
            //搜索动作
            if(isset($_POST['which']))
            {   
                if(!empty($_POST['name']))
                {
                     $ex_sq =" WHERE ". $_POST['which'].  " = '" .$_POST['name']."'";
                }
                else  $ex_sq = "";
            }
            else  $ex_sq = "";
            
            //添加动作
            if(isset($_GET['action']))
            {
                //添加课程
                 if($_GET['action']=='add')
                {
                    if(!empty($_POST['courseName']))
                    {
   
                        //得到当前最大的学生ID
                        $sql="select max(courseID) as id from course";
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
                        $sql= "INSERT INTO course VALUES ('".$new_id ."','" .$_POST['courseName'] ."','0','".date('y-m-d H:i:s',time()) ."','')";    
                        Yii::app()->db->createCommand($sql)->query();
                        $act_result="新建课程成功！";
                        unset($_GET['action']); 
                         
                    }else
                    {
                        //用户输入参数不足
                        $this->render('addCourse',array(
                                                     'shao'=>"输入不能为空"
                                                     ));
                        return;
                    }
                } 
                
            }
            
            //显示结果列表并分页
	    $sql = "SELECT * FROM course ". $ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('courseLst',array(
            'posts'=>$posts,
            'pages'=>$pages,
            'result'=>$act_result,
            'teacher'=>$this->teaInClass()
            ),false,true);
	}
            
         public function actionAddCourse()
	{        
            $this->render('addCourse');
        }
        
        public function actionInfoCourse()
	{        
            $courseID=$_GET['courseID'];
            $courseName=$_GET['courseName'];
            $createPerson=$_GET['createPerson'];
                    
             //定义动作
            $act_result="";
            
            
            //搜索动作
            if(isset($_POST['which']))
            {   
                if(!empty($_POST['name']))
                {
                     $ex_sq =" AND ". $_POST['which'].  " = '" .$_POST['name']."'";
                }
                else  $ex_sq = "";
            }
            else  $ex_sq = "";
            
            //添加动作
            if(isset($_GET['action']))
            {
                //添加课程
                 if($_GET['action']=='add')
                {
                    if(!empty($_POST['lessonName']))
                    {
   
                        //得到当前最大的lessonID
                        $sql="select max(lessonID) as id from lesson";
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
                        //得到该lesson最大的number
                        $sql="select max(number) as number from lesson WHERE courseID = '".$courseID."'";
                        $max_num = Yii::app()->db->createCommand($sql)->query();
                        $temp=$max_num->read();
                        if(empty($temp))
                        {
                            $new_num=1;
                        }
                        else
                        {
                            $new_num = $temp['number'] + 1;
                        }
                        
                        
                        $sql= "INSERT INTO lesson VALUES ('".$new_id ."','".$new_num."','" .$_POST['lessonName'] ."','".$courseID."','0','".date('y-m-d H:i:s',time()) ."')";    
                        Yii::app()->db->createCommand($sql)->query();
                        
                        //得到该suite最大的
                        $sql="select max(suiteID) as id from suite";
                        $max_s = Yii::app()->db->createCommand($sql)->query();
                        $temp=$max_s->read();
                        if(empty($temp))
                        {
                            $new_s=1;
                        }
                        else
                        {
                            $new_s = $temp['id'] + 1;
                        }
                        $sql= "INSERT INTO suite VALUES ('".$new_s ."','".$new_id."','课堂练习','classwork','".date('y-m-d H:i:s',time())."','0','0')";    
                        Yii::app()->db->createCommand($sql)->query();
                        $new_s=$new_s+1;
                        $sql= "INSERT INTO suite VALUES ('".$new_s ."','".$new_id."','自我练习','exercise','".date('y-m-d H:i:s',time())."','0','0')";    
                        Yii::app()->db->createCommand($sql)->query();
   
                        $act_result="新建课程成功！";
                        unset($_GET['action']); 
                         
                    }else
                    {
                        //用户输入参数不足
                        $this->render('addCourse',array(
                                                     'shao'=>"输入不能为空",
                                                    'courseID'=>$courseID,
                                                      'courseName'=>$courseName,
                                                    'createPerson'=>$createPerson,
                                                     ));
                        return;
                    }
                } 
                
            }
            
            //显示结果列表并分页
	    $sql = "SELECT * FROM lesson WHERE courseID = $courseID ". $ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('infoCourse',array(
                'courseID'=>$courseID,
                'courseName'=>$courseName,
                'createPerson'=>$createPerson,
                'posts'=>$posts,
                'pages'=>$pages,
                'result'=>$act_result,
                ),false,true);
        }
        
        public function actionAddLesson()
	{        
            $courseID=$_GET['courseID'];
            $courseName=$_GET['courseName'];
            $createPerson=$_GET['createPerson'];
            
            $this->render('addLesson',array(
                'courseID'=>$courseID,
                'courseName'=>$courseName,
                'createPerson'=>$createPerson,
                ));
        }
        
        public function actionLessonBranch() {
            $sql = "SELECT * FROM suite WHERE lessonID = '".$_GET['lessonID']."'" ; 
            $result=Yii::app()->db->createCommand($sql)->query();
            $class=$result->read();
            $exer=$result->read();
            $this->render('lessonBranch',array(
                'lessonName'=>$_GET['lessonName'],
                'class'=>$class,
                'exer'=>$exer,
                ));
            
        }
        
        public function actionGoverLesson(){
            $suiteID=$_GET['suiteID'];
            $suiteName=$_GET['suiteName'];
            $suiteType=$_GET['suiteType'];
            
            
        }
        // Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}