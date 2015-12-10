<?php

/**
 * This is the model class for table "class_exercise".
 *
 * The followings are the available columns in table 'class_exercise':
 * @property integer $exerciseID
 * @property integer $classID
 * @property integer $lessonID
 * @property string $title
 * @property string $content
 * @property string $type
 * @property string $file_path
 * @property string $file_name
 * @property string $create_time
 * @property string $create_person
 * @property integer $is_open
 */
class ClassExercise extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_exercise';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classID, lessonID, title, content, type, file_path, file_name, create_time, create_person, is_open', 'required'),
			array('classID, lessonID, is_open', 'numerical', 'integerOnly'=>true),
			array('title, file_path, file_name, create_person', 'length', 'max'=>30),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, classID, lessonID, title, content, type, file_path, file_name, create_time, create_person, is_open', 'safe', 'on'=>'search'),
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
			'exerciseID' => 'Exercise',
			'classID' => 'Class',
			'lessonID' => 'Lesson',
			'title' => 'Title',
			'content' => 'Content',
			'type' => 'Type',
			'file_path' => 'File Path',
			'file_name' => 'File Name',
			'create_time' => 'Create Time',
			'create_person' => 'Create Person',
			'is_open' => 'Is Open',
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

		$criteria->compare('exerciseID',$this->exerciseID);
		$criteria->compare('classID',$this->classID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_person',$this->create_person,true);
		$criteria->compare('is_open',$this->is_open);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassExercise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
