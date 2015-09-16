<?php

/**
 * This is the model class for table "tb_class".
 *
 * The followings are the available columns in table 'tb_class':
 * @property string $classID
 * @property string $className
 * @property string $currentCourse
 *
 * The followings are the available model relations:
 * @property TeacherClass[] $teacherClasses
 */
class TbClass extends CActiveRecord
{
    public function findlessonByClassID($classID){
    	//得到当前的学生的当前课程
    	$tb_class = $this->find("classid = '$classID'");
    	return $tb_class->currentLesson;
    }
    
    
    
    public function insertClass($courseName,$currentCourse){
        //添加班级
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
        $newClass = new TbClass();
        $newClass->classID          =   $new_id;
        $newClass->className        =   $courseName;
        $newClass->currentCourse    =   $currentCourse;
        $newClass->currentLesson    =   1;
        $newClass->insert();
        return $new_id;
    }
    
    //查看班级人数
    public function numInClass() {
         $sql="SELECT classID,count(classID) FROM student WHERE is_delete = '0' GROUP BY classID;";
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
    
    public function getClassLst(){
        //显示结果列表并分页
        $sql = "SELECT * FROM tb_class ";
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts=$result->query();
        return ['classLst'=>$posts,'pages'=>$pages,];
    }
    
    public function findCourseByClassID($classID) {
        $courseID = "select currentCourse from tb_class where classID = '$classID'";
        $sql = "select * from course where courseID in ( $courseID ) order by courseID asc;";
        $allCourse = (new Course())->findAllBySql($sql);
        return $allCourse;
    }

    public function findLessonByCourse($courseID){
        $criteria = new CDbCriteria;      
        $criteria->addCondition("courseID='$courseID'");
        $criteria->order = 'number ASC';   // 排序
        $lesson = Lesson::model()->findAll($criteria);
        return $lesson;
    }
   
    
    public function findClassByIds($Ids)
    {
        $sql = "select * from tb_class";
        $condition  = " where ";
        foreach($Ids as $classID){
            $condition = $condition."classID = '$classID' ";
        }
        $sql = $sql.$condition;
        return Yii::app()->db->createCommand($sql)->query();
    }
    
    
    public function getClassByTeacherID($teacherID){
        $sql = "select * from tb_class";
        $condition = " where classID in(select classID from teacher_class where teacherID = '$teacherID')";
        $order = " order by classID ASC";
        $sql = $sql.$condition.$order;
        return Yii::app()->db->createCommand($sql)->query();
    }


    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_class';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classID, className, currentCourse', 'required'),
			array('classID, className, currentCourse', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('classID, className, currentCourse', 'safe', 'on'=>'search'),
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
			'teacherClasses' => array(self::HAS_MANY, 'TeacherClass', 'classID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'classID' => 'Class',
			'className' => 'Class Name',
			'currentCourse' => 'Current Course',
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

		$criteria->compare('classID',$this->classID,true);
		$criteria->compare('className',$this->className,true);
		$criteria->compare('currentCourse',$this->currentCourse,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbClass the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
