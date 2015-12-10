<?php

/**
 * This is the model class for table "classexercise_record".
 *
 * The followings are the available columns in table 'classexercise_record':
 * @property integer $id
 * @property integer $classExerciseID
 * @property string $studentID
 * @property double $ratio_speed
 * @property double $ratio_correct
 * @property double $ratio_maxSpeed
 * @property double $ratio_momentSpeed
 * @property double $ratio_backDelete
 * @property double $ratio_momentKeyType
 * @property double $ratio_maxKeyType
 * @property double $ratio_averageKeyType
 * @property double $ratio_internalTime
 * @property double $ratio_maxInternalTime
 * @property double $ratio_countAllKey
 */
class ClassexerciseRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'classexercise_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classExerciseID, studentID, ratio_speed, ratio_correct, ratio_maxSpeed, ratio_momentSpeed, ratio_backDelete, ratio_momentKeyType, ratio_maxKeyType, ratio_averageKeyType, ratio_internalTime, ratio_maxInternalTime, ratio_countAllKey', 'required'),
			array('classExerciseID', 'numerical', 'integerOnly'=>true),
			array('ratio_speed, ratio_correct, ratio_maxSpeed, ratio_momentSpeed, ratio_backDelete, ratio_momentKeyType, ratio_maxKeyType, ratio_averageKeyType, ratio_internalTime, ratio_maxInternalTime, ratio_countAllKey', 'numerical'),
			array('studentID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, classExerciseID, studentID, ratio_speed, ratio_correct, ratio_maxSpeed, ratio_momentSpeed, ratio_backDelete, ratio_momentKeyType, ratio_maxKeyType, ratio_averageKeyType, ratio_internalTime, ratio_maxInternalTime, ratio_countAllKey', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'classExerciseID' => 'Class Exercise',
			'studentID' => 'Student',
			'ratio_speed' => 'Ratio Speed',
			'ratio_correct' => 'Ratio Correct',
			'ratio_maxSpeed' => 'Ratio Max Speed',
			'ratio_momentSpeed' => 'Ratio Moment Speed',
			'ratio_backDelete' => 'Ratio Back Delete',
			'ratio_momentKeyType' => 'Ratio Moment Key Type',
			'ratio_maxKeyType' => 'Ratio Max Key Type',
			'ratio_averageKeyType' => 'Ratio Average Key Type',
			'ratio_internalTime' => 'Ratio Internal Time',
			'ratio_maxInternalTime' => 'Ratio Max Internal Time',
			'ratio_countAllKey' => 'Ratio Count All Key',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('classExerciseID',$this->classExerciseID);
		$criteria->compare('studentID',$this->studentID,true);
		$criteria->compare('ratio_speed',$this->ratio_speed);
		$criteria->compare('ratio_correct',$this->ratio_correct);
		$criteria->compare('ratio_maxSpeed',$this->ratio_maxSpeed);
		$criteria->compare('ratio_momentSpeed',$this->ratio_momentSpeed);
		$criteria->compare('ratio_backDelete',$this->ratio_backDelete);
		$criteria->compare('ratio_momentKeyType',$this->ratio_momentKeyType);
		$criteria->compare('ratio_maxKeyType',$this->ratio_maxKeyType);
		$criteria->compare('ratio_averageKeyType',$this->ratio_averageKeyType);
		$criteria->compare('ratio_internalTime',$this->ratio_internalTime);
		$criteria->compare('ratio_maxInternalTime',$this->ratio_maxInternalTime);
		$criteria->compare('ratio_countAllKey',$this->ratio_countAllKey);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassexerciseRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
