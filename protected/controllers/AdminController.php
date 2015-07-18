<?php

class AdminController extends CController
{
    public $layout='//layouts/adminBar';
    public function actionIndex()
    {        
        $this->render('index');
    }
    
    public function actionHardDeleteStu()
    {
        $pass = $_POST['password'];
        $id = Yii::app()->session['userid_now'];
        $admin = Admin::model()->findByPK($id);
        if($admin->password !== $pass){
            return $this->render('confirmPass',['wrong'=>'密码错误，请重新输入。']);
        }
        $rows = 0;
        if(isset(Yii::app()->session['deleteStuID'])){
            $userID = Yii::app()->session['deleteStuID'];
            unset(Yii::app()->session['deleteStuID']);
            $rows = Student::model()->deleteByPK("$userID");
        } else if(isset(Yii::app()->session['deleteStuBox'])){
            $ids = Yii::app()->session['deleteStuBox'];
            unset(Yii::app()->session['deleteStuBox']);
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition."'$value',";
            }
            $condition = $condition."''";
            $rows = Student::model()->deleteAll("userID in ($condition)");
        }
        $stuLst = Student::model()->findAll("is_delete = '1'");
        $this->render('recycleStu',array(
        'stuLst'=>$stuLst,
        'rows'=>$rows,
        ));
    }
    public function actionConfirmPass()
    {
        if(isset($_GET['userID'])){
            Yii::app()->session['deleteStuID'] = $_GET['userID'];
        } else if(isset($_POST['checkbox'])){
            Yii::app()->session['deleteStuBox'] = $_POST['checkbox'];
        }
        return $this->render('confirmPass');
    }
    
    public function actionRevokeStu()
    {
        $rows = 0;
        if(isset($_GET['userID'])){
            $userID = $_GET['userID'];
            $rows = Student::model()->updateAll(array('is_delete'=>'0'),'userID=:userID',array(':userID'=>$userID));
        } else if(isset($_POST['checkbox'])){
            $ids = $_POST['checkbox'];
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition."'$value',";
            }
            $condition = $condition."''";
            $rows = Student::model()->updateAll(array('is_delete'=>'0'),"userID in ($condition)");
        }
        $stuLst = Student::model()->findAll("is_delete = '1'");
        $this->render('recycleStu',array(
        'stuLst'=>$stuLst,
        'rows'=>$rows,
        ));
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
        
        public function actionRecycleStu()
        {
            $stuLst = Student::model()->findAll("is_delete = '1'");
            $this->render('recycleStu',array(
            'stuLst'=>$stuLst,
            ));
        }
        
    	public function actionStuLst()
	{       
            Yii::app()->session['lastUrl']="stuLst";
            $result = Student::model()->getStuLst("", "");
            $stuLst=$result['stuLst'];
            $pages=$result['pages'];
            $this->render('stuLst',array(
            'stuLst'=>$stuLst,
            'pages'=>$pages,
            ));
	}
        
        public function actionSearchStu()
        {
            Yii::app()->session['lastUrl']="searchStu";
            if(isset($_POST['type'])){
                $type=$_POST['type'];
                $value=$_POST['value'];
                Yii::app()->session['searchStuType']=$type;
                Yii::app()->session['searchStuValue']=$value;
            } else {
                $type = Yii::app()->session['searchStuType'];
                $value = Yii::app()->session['searchStuValue'];
            }
            $result = Student::model()->getStuLst($type, $value);
            $stuLst=$result['stuLst'];
            $pages=$result['pages'];
            $this->render('searchStu',array(
                        'stuLst'=>$stuLst,
                        'pages'=>$pages)
                    );
        }
            
        public function actionAddStu(){
            $result = 'no';
            if(isset($_POST['userID'])){
                $result = Student::model()->insertStu($_POST['userID'], $_POST['userName'], $_POST['password1'], $_POST['classID']);
            }
            $classAll = TbClass::model()->findAll("");
            $userAll = Student::model()->findAll();
            $this->render('addStu',['classAll'=>$classAll,'userAll'=>$userAll,'result'=>$result]);
        }
        
        public function actionInfoStu()
	{        
            if(Yii::app()->session['lastUrl']=="infoClass")
            {
                   $this->render('infoStu',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                   'class'=>$_GET['classID']
            ));
            }else if(isset($_GET['flag']))
            {
                $this->render('infoStu',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                    'class'=>$_GET['class'],
                    'flag'=>$_GET['flag']
                    ));
            }else{
                $this->render('infoStu',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                    'class'=>$_GET['class']
                    ));
            }
        }

        public function actionDeleteStuSearch()
	{
            $userID = $_GET['id'];
            $thisStu = new Student();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu -> is_delete = '1';
            $thisStu -> update();
            $type = Yii::app()->session['searchStuType'];
            $value = Yii::app()->session['searchStuValue'];
            $result = Student::model()->getStuLst($type, $value);
            $stuLst=$result['stuLst'];
            $pages=$result['pages'];
            $this->render('searchStu',array(
                        'stuLst'=>$stuLst,
                        'pages'=>$pages)
                    );
        }
        
        public function actionDeleteStu()
	{
            $userID = $_GET['id'];
            $thisStu = new Student();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu -> is_delete = '1';
            $thisStu -> update();
            $result = Student::model()->getStuLst("", "");
            $stuLst=$result['stuLst'];
            $pages=$result['pages'];
            $this->render('stuLst',array(
            'stuLst'=>$stuLst,
            'pages'=>$pages,
            ));
        }
        
        public function actionDeleteStuDontHaveClass()
	{
            $userID = $_GET['id'];
            $thisStu = new Student();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu -> is_delete = '1';
            $thisStu -> update();
            Yii::app()->session['lastUrl']="stuDontHaveClass";
            $result = Student::model()->getStuLst("classID", 0);
            $this->render("stuDontHaveClass",["stuLst"=>$result["stuLst"],"pages"=>$result['pages']]);
        }
        
        public function actionResetPass()
	{
            $userID = $_GET['id'];
            $thisStu = new Student();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu -> password = '000';
            
            $thisStu -> update();
            $classAll = TbClass::model()->findAll();
            $userAll = Student::model()->findAll();
            if(isset($_GET['flag']))
            {
                 $this->render('editStu',array(
                'userID'=>$_GET['id'],
                'userName'=>$thisStu ->userName,
                'classID'=>$thisStu ->classID,
                'classAll'=>$classAll,
                'userAll'=>$userAll,
                'result'=>'密码重置成功！',
                'flag'=>'search'
                ));
            }else{
            $this->render('editStu',array(
                'userID'=>$_GET['id'],
                'userName'=>$thisStu ->userName,
                'classID'=>$thisStu ->classID,
                'classAll'=>$classAll,
                'userAll'=>$userAll,
                'result'=>'密码重置成功！'
                ));
            }
        }
        
        public function actionEditStuInfo()
	{
            $userID = $_GET['id'];
            $thisStu = new Student();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu -> userID =$_POST['userID'];
            $thisStu -> userName =$_POST['userName'];
            $thisStu -> classID =$_POST['classID'];
            $thisStu -> update();
            $classAll = TbClass::model()->findAll();
            $userAll = Student::model()->findAll();
            if(isset($_GET['flag']))
            {
                $this->render('editStu',array(
                'userID'=>$thisStu -> userID,
                'userName'=>$thisStu ->userName,
                'classID'=>$thisStu ->classID,
                'classAll'=>$classAll,
                'userAll'=>$userAll,
                'result'=>'信息修改成功！',
                'flag'=>$_GET['flag']
                ));
            }  else {
                $this->render('editStu',array(
                'userID'=>$thisStu -> userID,
                'userName'=>$thisStu ->userName,
                'classID'=>$thisStu ->classID,
                'classAll'=>$classAll,
                'userAll'=>$userAll,
                'result'=>'信息修改成功！'
                ));
            }      
        }
        
        public function actionEditStu()
	{
            $classAll = TbClass::model()->findAll();
            $userAll = Student::model()->findAll();
            if(isset($_GET['flag']))
            {
                $this->render('editStu',array(
                'userID'=>$_GET['id'],
                'userName'=>$_GET['name'],
                'classID'=>$_GET['class'],
                'classAll'=>$classAll,
                'userAll'=>$userAll,
                'flag'=>'search'
                ));
            }else{
            $this->render('editStu',array(
                'userID'=>$_GET['id'],
                'userName'=>$_GET['name'],
                'classID'=>$_GET['class'],
                'classAll'=>$classAll,
                'userAll'=>$userAll
                ));
            }
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
            Yii::app()->session['lastUrl']="=teaLst";
            $result = Teacher::model()->getTeaLst("", "");
            $teaLst=$result['teaLst'];
            $pages=$result['pages'];
            $this->render('teaLst',array(
            'teaLst'=>$teaLst,
            'pages'=>$pages,
            ));
	}
        
        public function actionSearchTea()
        {
            Yii::app()->session['lastUrl']="=searchTea";
            if(isset($_POST['type'])){
                $type=$_POST['type'];
                $value=$_POST['value'];
                Yii::app()->session['searchTeaType']=$type;
                Yii::app()->session['searchTeaValue']=$value;
            } else {
                $type = Yii::app()->session['searchTeaType'];
                $value = Yii::app()->session['searchTeaValue'];
            }
            $result = Teacher::model()->getTeaLst($type, $value);
            $teaLst=$result['teaLst'];
            $pages=$result['pages'];
            $this->render('searchTea',array(
                        'teaLst'=>$teaLst,
                        'pages'=>$pages)
                    );
        }
        
        public function actionInfoTea()
	{        
            if(Yii::app()->session['lastUrl']=="infoClass")
            {
                   $this->render('infoTea',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                   'classID'=>$_GET['classID']
                    ));
            }else if(isset($_GET['flag']))
            {
                $this->render('infoTea',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                    'flag'=>$_GET['flag']
                    ));
            }else{
                $this->render('infoTea',array(
                   'id'=>$_GET['id'],
                   'name'=>$_GET['name'],
                    ));
            }
        }
        
        public function actionAddTea(){
            $result = 'no';
            if(isset($_POST['userID'])){
                $result = Teacher::model()->insertTea($_POST['userID'], $_POST['userName'], $_POST['password1']);
            }
            $userAll = Teacher::model()->findAll();
            $this->render('addTea',['userAll'=>$userAll,'result'=>$result]);
        }
        
        public function actionResetTeaPass()
	{
            $userID = $_GET['id'];
            $thisTea = new Teacher();
            $thisTea = $thisTea->find("userID = '$userID'");
            $thisTea -> password = '000';
            
            $thisTea -> update();
            $userAll = Teacher::model()->findAll();
            if(isset($_GET['flag']))
            {
                 $this->render('editTea',array(
                'userID'=>$_GET['id'],
                'userName'=>$thisTea ->userName,
                'userAll'=>$userAll,
                'result'=>'密码重置成功！',
                'flag'=>'search'
                ));
            }else{
            $this->render('editTea',array(
                'userID'=>$_GET['id'],
                'userName'=>$thisTea ->userName,
                'userAll'=>$userAll,
                'result'=>'密码重置成功！'
                ));
            }
        }
        
        public function actionEditTeaInfo()
	{
            $userID = $_GET['id'];
            $thisTea = new Teacher();
            $thisTea = $thisTea->find("userID = '$userID'");
            $thisTea -> userID =$_POST['userID'];
            $thisTea -> userName =$_POST['userName'];
            $thisTea -> update();
            $userAll = Teacher::model()->findAll();
            if(isset($_GET['flag']))
            {
                $this->render('editTea',array(
                'userID'=>$thisTea -> userID,
                'userName'=>$thisTea ->userName,
                'userAll'=>$userAll,
                'result'=>'信息修改成功！',
                'flag'=>$_GET['flag']
                ));
            }  else {
                $this->render('editTea',array(
                'userID'=>$thisTea -> userID,
                'userName'=>$thisTea ->userName,
                'userAll'=>$userAll,
                'result'=>'信息修改成功！'
                ));
            }      
        }
        
        public function actionEditTea()
	{
            $userAll = Teacher::model()->findAll();
            if(isset($_GET['flag']))
            {
                $this->render('editTea',array(
                'userID'=>$_GET['id'],
                'userName'=>$_GET['name'],
                'userAll'=>$userAll,
                'flag'=>'search'
                ));
            }else{
            $this->render('editTea',array(
                'userID'=>$_GET['id'],
                'userName'=>$_GET['name'],
                'userAll'=>$userAll
                ));
            }
        }
        
        public function actionDeleteTeaSearch()
	{
            $userID = $_GET['id'];
            $thisTea = new Teacher();
            $thisTea = $thisTea->find("userID = '$userID'");
            $thisTea -> is_delete = '1';
            $thisTea -> update();
            $type = Yii::app()->session['searchTeaType'];
            $value = Yii::app()->session['searchTeaValue'];
            $result = Teacher::model()->getTeaLst($type, $value);
            $teaLst=$result['teaLst'];
            $pages=$result['pages'];
            $this->render('searchTea',array(
                        'teaLst'=>$teaLst,
                        'pages'=>$pages)
                    );
        }
        
        public function actionDeleteTea()
	{
            $userID = $_GET['id'];
            $thisTea = new Teacher();
            $thisTea = $thisTea->find("userID = '$userID'");
            $thisTea -> is_delete = '1';
            $thisTea -> update();
            $result = Teacher::model()->getTeaLst("", "");
            $teaLst=$result['teaLst'];
            $pages=$result['pages'];
            $this->render('teaLst',array(
            'teaLst'=>$teaLst,
            'pages'=>$pages,
            ));
        }
        
    public function actionHardDeleteTea()
    {
        $pass = $_POST['password'];
        $id = Yii::app()->session['userid_now'];
        $admin = Admin::model()->findByPK($id);
        if($admin->password !== $pass){
            return $this->render('confirmTeaPass',['wrong'=>'密码错误，请重新输入。']);
        }
        $rows = 0;
        if(isset(Yii::app()->session['deleteTeaID'])){
            $userID = Yii::app()->session['deleteTeaID'];
            unset(Yii::app()->session['deleteTeaID']);
            $rows = Teacher::model()->deleteByPK("$userID");
        } else if(isset(Yii::app()->session['deleteTeaBox'])){
            $ids = Yii::app()->session['deleteTeaBox'];
            unset(Yii::app()->session['deleteTeaBox']);
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition."'$value',";
            }
            $condition = $condition."''";
            $rows = Teacher::model()->deleteAll("userID in ($condition)");
        }
        $teaLst = Teacher::model()->findAll("is_delete = '1'");
        $this->render('recycleTea',array(
        'teaLst'=>$teaLst,
        'rows'=>$rows,
        ));
    }
    public function actionConfirmTeaPass()
    {
        if(isset($_GET['userID'])){
            Yii::app()->session['deleteTeaID'] = $_GET['userID'];
        } else if(isset($_POST['checkbox'])){
            Yii::app()->session['deleteTeaBox'] = $_POST['checkbox'];
        }
        return $this->render('confirmTeaPass');
    }
    
    public function actionRevokeTea()
    {
        $rows = 0;
        if(isset($_GET['userID'])){
            $userID = $_GET['userID'];
            $rows = Teacher::model()->updateAll(array('is_delete'=>'0'),'userID=:userID',array(':userID'=>$userID));
        } else if(isset($_POST['checkbox'])){
            $ids = $_POST['checkbox'];
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition."'$value',";
            }
            $condition = $condition."''";
            $rows = Teacher::model()->updateAll(array('is_delete'=>'0'),"userID in ($condition)");
        }
        $teaLst = Teacher::model()->findAll("is_delete = '1'");
        $this->render('recycleTea',array(
        'teaLst'=>$teaLst,
        'rows'=>$rows,
        ));
    }
        
    public function actionRecycleTea()
    {
        $teaLst = Teacher::model()->findAll("is_delete = '1'");
        $this->render('recycleTea',array(
        'teaLst'=>$teaLst,
        ));
    }
        
        public function actionClassLst()
        {
             //显示结果列表并分页
            Yii::app()->session['lastUrl']="classLst";
	    $result = TbClass::model()->getClassLst();
            $this->render('classLst',array(
            'posts'=>$result['classLst'],
            'pages'=>$result['pages'],
            'nums'=> TbClass::model()->numInClass(),
            'teacher'=>TbClass::model()->teaInClass(),
            'teacherOfClass' =>TbClass::model()->teaByClass(),
            ));
        }
        public function actionStuDontHaveClass()
        {
            Yii::app()->session['lastUrl']="stuDontHaveClass";
            $result = Student::model()->getStuLst("classID", 0);
            $this->render("stuDontHaveClass",["stuLst"=>$result["stuLst"],"pages"=>$result['pages']]);
        }
        
        public function actionSearchClass()
        {
            if(isset($_POST['which']))
            {   
                if ($_POST['which'] == "classID"||$_POST['which'] == "className")
                {
                    $ex_sq = " WHERE " . $_POST['which'] . " = '" . $_POST['value'] . "'";
                }else if($_POST['which'] == "courseID"){
                    $ex_sq = " WHERE currentCourse = '" . $_POST['value'] . "'";
                } else if($_POST['which'] == "teaName")
                {
                    $sql="SELECT * FROM teacher WHERE userName ='". $_POST['value'] . "'";
                    $an = Yii::app()->db->createCommand($sql)->query();
                    $temp=$an->read();
                    if(!empty($temp))
                        $teaID=$temp['userID'];
                    else $teaID=-1;
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
                }else{
                    $ex_sq="";
                }
            }
            $sql = "SELECT * FROM tb_class ".$ex_sq;
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $posts=$result->query();
            $this->render('searchClass',array(
            'posts'=>$posts,
            'pages'=>$pages,
            'nums'=> TbClass::model()->numInClass(),
            'teacher'=>TbClass::model()->teaInClass(),
            'teacherOfClass' =>TbClass::model()->teaByClass(),
            ));
            
        }
        
        public function actionAddClass()
	{        
            $result = 'no';
            if(isset($_POST['className'])){
                $result = TbClass::model()->insertClass($_POST['className'], $_POST['courseID']);
            }
            $this->render('addClass',['result'=>$result]);
        }
        
        public function actionInfoClass()
	{   
            
            Yii::app()->session['lastUrl']="infoClass";
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
             $curLesson=$class['currentLesson'];
             
	    $sql = "SELECT * FROM student WHERE classID = '$classID' AND is_delete = 0";
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
                                             'curLesson'=>$curLesson,
                                             'teacher'=>TbClass::model()->teaInClass(),
                                             'teacherOfClass' =>$teacherOfClass,
                                             'nums'=>$nums,        //学生人数
                                             'posts'=>$posts,      //学生
                                             'pages'=>$pages,      //分页
                                            'result'=>$act_result,
                    ),false,true);
        }
        
        public function actionAddStuClass()
	{        
            $sql = "SELECT * FROM student WHERE classID = '0' AND is_delete = 0";
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
                                                'teachers'=>TbClass::model()->teaInClass(),
                                                
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
            'teacher'=>  TbClass::model()->teaInClass(),
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
            'teacher'=>TbClass::model()->teaInClass(),
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
            'teacher'=>  TbClass::model()->teaInClass(),
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
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst=$result['choiceLst'];
            $pages=$result['pages'];
            Yii::app()->session['lastUrl']="choiceLst";
            $this->render('choiceLst',array(
            'choiceLst'=>$choiceLst,
            'pages'=>$pages,
            'teacher'=>  Teacher::model()->findall()
            ));
	}
        
        public function actionSearchChoice()
        {
            if(isset($_POST['type'])){
                $type=$_POST['type'];
                $value=$_POST['value'];
                Yii::app()->session['searchChoiceType']=$type;
                Yii::app()->session['searchChoiceValue']=$value;
            } else {
                $type = Yii::app()->session['searchChoiceType'];
                $value = Yii::app()->session['searchChoiceValue'];
            }
            Yii::app()->session['lastUrl']="searchChoice";
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst=$result['choiceLst'];
            $pages=$result['pages'];
            $this->render('searchChoice',array(
                        'choiceLst'=>$choiceLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall()
                    )
                    );
        }
        
        public function actionReturnFromAddChoice()
        {
            if(Yii::app()->session['lastUrl']=="searchChoice")
            {
                $type = Yii::app()->session['searchChoiceType'];
                $value = Yii::app()->session['searchChoiceValue'];
                $result = Choice::model()->getChoiceLst($type, $value);
                $choiceLst=$result['choiceLst'];
                $pages=$result['pages'];
                $this->render('searchChoice',array(
                        'choiceLst'=>$choiceLst,
                        'pages'=>$pages,
                        'teacher'=>  Teacher::model()->findall()
                    )
                    );
            }else {
                $result = Choice::model()->getChoiceLst("", "");
                $choiceLst=$result['choiceLst'];
                $pages=$result['pages'];
                Yii::app()->session['lastUrl']="choiceLst";
                $this->render('choiceLst',array(
                             'choiceLst'=>$choiceLst,
                             'pages'=>$pages,
                             'teacher'=>  Teacher::model()->findall()
                ));
            }
        }
                
         public function actionEditChoiceInfo()
	{
            $exerciseID = $_GET['exerciseID'];
            $thisCh= new Choice();
            $thisCh = $thisCh->find("exerciseID = '$exerciseID'");
            $thisCh -> requirements =$_POST['requirements'];
            $thisCh -> options =$_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'];
            $thisCh -> answer =$_POST['answer'];
            $thisCh -> update();
            $this->render("editChoice",array(
                'exerciseID'=>$exerciseID,
                'requirements' =>$thisCh -> requirements,
                'options'=> $thisCh -> options,
                'answer' =>$thisCh -> answer,
                'result' => "修改习题成功"
            ));
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
            $result = 'no';
            if(isset($_POST['requirements'])){
                $result = Choice::model()->insertChoice($_POST['requirements'], $_POST['A']."$$".$_POST['B']."$$".$_POST['C']."$$".$_POST['D'], $_POST['answer'], 0);
            }
            $this->render('addChoice',['result'=>$result]);
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
            'teacher'=>  TbClass::model()->teaInClass(),
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