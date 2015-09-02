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
