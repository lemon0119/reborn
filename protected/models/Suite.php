<?php

/**
 * This is the model class for table "suite".
 *
 * The followings are the available columns in table 'suite':
 * @property string $suiteID
 * @property string $courseID
 * @property string $suiteName
 * @property string $suiteType
 * @property string $createTime
 * @property string $createPerson
 * @property string $classID
 */
class Suite extends CActiveRecord
{
    public function getClassworkNow(){
        $userid =Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($userid);
        $course = TbClass::model()->findCourseByClassID($classID);
        $lesson = TbClass::model()->findLessonByCourse($course[0]['courseID']);
        $time = date("Y-m-d  H:i:s");
        $getRightSuite = "select suiteID from classwork where begintime<'$time' and endtime>'$time'";
        $condition = " where classID = '$classID' and suiteType = 'classwork' and suiteID in ($getRightSuite)";
        $select = "select * from suite";
        $sql = $select.$condition;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    
    /*
     * 
     * 
     *    得到登录学生的所有课堂作业
     */
    public function getClassworkAll($lesnID){
        $userid =Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($userid);
        $select = 'select suite.suiteID , suite.suiteName , class_lesson_suite.open as open, class_lesson_suite.workID from class_lesson_suite, suite';

        $condition = " where class_lesson_suite.suiteID=suite.suiteID and class_lesson_suite.classID='$classID' and class_lesson_suite.lessonID='$lesnID'and class_lesson_suite.open='1'";
        $order = 'order by suite.suiteID';
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    
    public function getExerNum($suiteID){
        foreach (Tool::$EXER_TYPE as $type) {
            $record = $this->getSuiteExerByType( $suiteID, $type);
            $result[$type] = $record->getRowCount();
        }
        return $result;
    }
    
    public function getSuiteExerByType( $suiteID, $type)
    {
        switch ($type){
            case 'choice':
                $result = $this->getchoice($suiteID);    
                break;
            case 'filling':
                $result = $this->getFilling($suiteID);
                break;
            case 'question':
                $result = $this->getQuestion($suiteID);
                break;
            case 'key':
                $result = $this->getKeyExer($suiteID);
                break;
            case 'listen':
                $result = $this->getListenExer($suiteID);
                break;
            default :
                $result = $this->getLookExer($suiteID);
        }
        return $result;
    }
    
    
    
    public function getSuiteExerByTypePage($suiteID, $type,$pagesize)
    {
        $criteria   =   new CDbCriteria();
        $result = $this->getSuiteExerByType( $suiteID, $type);
        
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   $pagesize; 
        $pages->applyLimit($criteria);
        if($type == "key"||$type == "look" || $type == "listen" )
        {
            $databaseType = $type."_type";
        }  else {
            $databaseType = $type;
        }
        $sql = "select * from ".$databaseType;
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='".$type."')";
        $sql = $sql.$condition.$order;
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $workLst  =   $result->query();       
        return ['workLst'=>$workLst,'pages'=>$pages,];     
    }
    
    public function getSuiteByTeacherID($teacherID)
    {
        $sql  = "select * from suite";
        $order = " order by classID ASC";
        $condition = " where classID in (select classID from teacher_class where teacherID = '$teacherID')";
        $sql = $sql.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        $criteria   =   new CDbCriteria();
        $pages     =   new CPagination($result->rowCount);
        $pages->pageSize    =   5; 
        $pages->applyLimit($criteria);

        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize);
        $suiteLst = $result->query();
        return ['suiteLst'=>$suiteLst,'pages'=>$pages,];        
    }
    
    public function getSuiteByClassLessonSuite($teacherID)
        {
        $sql = "select * from suite";
        $condition = " where suiteID in(select suiteID from class_lesson_suite where classID in (select classID from teacher_class where teacherID = '$teacherID')) and createPerson = '$teacherID'";
        $sql = $sql.$condition;
        return Yii::app()->db->createCommand($sql)->query(); 
        }  
        
        
    
    
    public function getAllSuiteByPage($pagesize,$teacherID)
    {
        $sql  = "select * from suite where createPerson =".$teacherID;
        $result = Yii::app()->db->createCommand($sql)->query();
        $criteria   =   new CDbCriteria();
        $pages     =   new CPagination($result->rowCount);
        $pages->pageSize    =   $pagesize; 
        $pages->applyLimit($criteria);
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize);
        $suiteLst = $result->query();
        return ['suiteLst'=>$suiteLst,'pages'=>$pages,]; 
       
    }



    public function getchoice($suiteID){
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='choice')";
        $select = "select * from choice";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    public function getChoice2($suiteID){
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='choice')";
        $select = "select * from choice";
        $sql = $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   1000; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $choiceLst  =   $result->query();
        
        return ['choiceLst'=>$choiceLst,'pages'=>$pages,];
    }
    public function getFilling($suiteID){
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='filling')";
        $select = "select * from filling";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    public function getFilling2($suiteID){
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='filling')";
        $select = "select * from filling";
        $sql = $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   8000; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $fillingLst  =   $result->query();
        
        return ['fillingLst'=>$fillingLst,'pages'=>$pages,];
    }

    public function getQuestion($suiteID)
    {
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='question')";
        $select = "select * from question";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    public function getQuestion2($suiteID)
    {
       $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='question')";
        $select = "select * from question";
        $sql = $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   8000; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $questionLst  =   $result->query();
        
        return ['questionLst'=>$questionLst,'pages'=>$pages,];
    }

    public function getKeyExer($suiteID)
    {
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='key')";
        $select = "select * from key_type";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    public function getListenExer($suiteID)
    {
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='listen')";
        $select = "select * from listen_type";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    public function getLookExer($suiteID)
    {
        $isExam = Yii::app()->session['isExam'];
        if($isExam){
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from $suite_exer where $findID='$suiteID' and type='look')";
        $select = "select * from look_type";
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'suite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, courseID, suiteName, suiteType, createTime, createPerson', 'required'),
			array('suiteID, courseID, createPerson, classID', 'length', 'max'=>30),
			array('suiteName', 'length', 'max'=>60),
			array('suiteType', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('suiteID, courseID, suiteName, suiteType, createTime, createPerson, classID', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'suiteID' => 'Suite',
			'courseID' => 'Course',
			'suiteName' => 'Suite Name',
			'suiteType' => 'Suite Type',
			'createTime' => 'Create Time',
			'createPerson' => 'Create Person',
			'classID' => 'Class',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('suiteID',$this->suiteID,true);
		$criteria->compare('courseID',$this->courseID,true);
		$criteria->compare('suiteName',$this->suiteName,true);
		$criteria->compare('suiteType',$this->suiteType,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('createPerson',$this->createPerson,true);
		$criteria->compare('classID',$this->classID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function insertSuite($classID,$lessonID,$title,$createPerson){
        $sql        =   "select max(suiteID) as id from suite";
        $max_id     =   Yii::app()->db->createCommand($sql)->query();
        $temp       =   $max_id->read();
        if(empty($temp))
        {
            $new_id =   1;
        }
        else
        {
            $new_id =   $temp['id'] + 1;
        }
        $newSuite    =   new Suite();
        $newSuite->suiteID    =   $new_id;
        $newSuite->suiteType =   "exercise";
        $newSuite->createPerson  =   $createPerson;
        $newSuite->createTime    =   date('y-m-d H:i:s',time());
        $newSuite->suiteName = $title;
        $newSuite->insert();
        return $new_id;
        }

        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Suite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
