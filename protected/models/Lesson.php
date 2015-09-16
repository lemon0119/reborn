<?php

/**
 * This is the model class for table "lesson".
 *
 * The followings are the available columns in table 'lesson':
 * @property integer $lessonID
 * @property integer $number
 * @property string $lessonName
 * @property integer $courseID
 * @property integer $createPerson
 * @property string $createTime
 */
class Lesson extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lesson';
	}
        
        public function newID() {
            //得到当前最大的lessonID
            $sql        = "select max(lessonID) as id from lesson";
            $max_id     = Yii::app()->db->createCommand($sql)->query();
            $temp       = $max_id->read();
            if (empty($temp)) {
                $new_id = 1;
            } else {
                $new_id = $temp ['id'] + 1;
            }
            return $new_id;
        }
        
        public function newNum($courseID,$classID){
            // 得到该lesson最大的number
            $sql = "select max(number) as number from lesson WHERE courseID = '$courseID' AND classID = '$classID'";
            $max_num = Yii::app()->db->createCommand($sql)->query();
            $temp = $max_num->read();
            if (empty($temp)) {
                $new_num = 1;
            } else {
                $new_num = $temp ['number'] + 1;
            }
            return $new_num;
        }
        
        public function insertLesson($lessonName,$courseID,$createPerson,$classID){   
            //添加课程
            $newLesson                  =   new Lesson();
            $newLesson->lessonID        =   Lesson::model()->newID();
            $newLesson->classID         =   $classID;
            $newLesson->number          =   Lesson::model()->newNum($courseID,$classID);
            $newLesson->lessonName      =   $lessonName;
            $newLesson->courseID        =   $courseID;
            $newLesson->createPerson    =   $createPerson;
            $newLesson->createTime      =   date('y-m-d H:i:s',time());
            return $newLesson->insert();
        }
        
        public function getLessonLst($type,$value,$courseID){
            $order  =   " order by lessonID ASC";
            if($type!="")
                $condition = " WHERE $type = '$value' AND ";
            else
                $condition= " WHERE ";
            $select     =   "SELECT * FROM lesson";
            $sql        =   $select.$condition."courseID = '$courseID' AND classID = '0'".$order;
            $criteria   =   new CDbCriteria();
            $result     =   Yii::app()->db->createCommand($sql)->query();
            $pages      =   new CPagination($result->rowCount);
            $pages->pageSize    =   10; 
            $pages->applyLimit($criteria); 
            $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
            $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
            $result->bindValue(':limit', $pages->pageSize); 
            $lessonLst  =   $result->query();
        
            return ['lessonLst'=>$lessonLst,'pages'=>$pages,];
        }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lessonID, number, lessonName, courseID, createPerson, createTime', 'required'),
			array('lessonID, number, courseID, createPerson', 'numerical', 'integerOnly'=>true),
			array('lessonName', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lessonID, number, lessonName, courseID, createPerson, createTime', 'safe', 'on'=>'search'),
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
			'lessonID' => 'Lesson',
			'number' => 'Number',
			'lessonName' => 'Lesson Name',
			'courseID' => 'Course',
			'createPerson' => 'Create Person',
			'createTime' => 'Create Time',
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

		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('number',$this->number);
		$criteria->compare('lessonName',$this->lessonName,true);
		$criteria->compare('courseID',$this->courseID);
		$criteria->compare('createPerson',$this->createPerson);
		$criteria->compare('createTime',$this->createTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        public function getLessonByTeacherID($teacherID)
        {
            $sql = "select * from lesson";
            $condition = " where classID in(select classID from teacher_class where teacherID = '$teacherID')";
            $sql = $sql.$condition;
            return Yii::app()->db->createCommand($sql)->query();
                
            
        }
                

        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lesson the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
