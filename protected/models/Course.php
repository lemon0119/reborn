<?php

/**
 * This is the model class for table "course".
 *
 * The followings are the available columns in table 'course':
 * @property string $courseID
 * @property string $courseName
 * @property string $classID
 * @property string $suiteID
 */
class Course extends CActiveRecord
{
    
    public function insertCourse($courseName,$createPerson){   
        //添加科目
        //得到当前最大的科目ID
        $sql    =   "select max(courseID) as id from course";
        $max_id =   Yii::app()->db->createCommand($sql)->query();
        $temp=$max_id->read();
        if(empty($temp))
        {
            $new_id =   1;
        }
        else
        {
            $new_id =   $temp['id'] + 1;
        }  
        $newCourse                  =   new Course();
        $newCourse->courseID        =   $new_id;
        $newCourse->courseName      =   $courseName;
        $newCourse->createPerson    =   $createPerson;
        $newCourse->createTime      =   date('y-m-d H:i:s',time());
        return $newCourse->insert();
    }
    
    public function getCourseLst($type,$value){
        $order  =   " order by courseID ASC";
        if($type!="")
            $condition = " WHERE $type = '$value'";
        else
            $condition= "";
        $select     =   "SELECT * FROM course";
        $sql        =   $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   10; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $courseLst  =   $result->query();
        
        return ['courseLst'=>$courseLst,'pages'=>$pages,];
    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'course';
	}

 	public function getExerciseListByLessonID($lessonID)
	{
            $sql = "select * from suite where lessonID = '$lessonID' and suiteType = 'exercise';";
            $suiteList =(new Suite())->findAllBySql($sql);
            return CHtml::listData($suiteList,'suiteID','suiteName');
	}
        
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('courseID, courseName, classID, suiteID', 'required'),
			array('courseID, courseName, classID, suiteID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('courseID, courseName, classID, suiteID', 'safe', 'on'=>'search'),
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
			'courseID' => 'Course',
			'courseName' => 'Course Name',
			'classID' => 'Class',
			'suiteID' => 'Suite',
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

		$criteria->compare('courseID',$this->courseID,true);
		$criteria->compare('courseName',$this->courseName,true);
		$criteria->compare('classID',$this->classID,true);
		$criteria->compare('suiteID',$this->suiteID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Course the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
