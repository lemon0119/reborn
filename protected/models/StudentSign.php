<?php

/**
 * This is the model class for table "student_sign".
 *
 * The followings are the available columns in table 'student_sign':
 * @property integer $Sign_ID
 * @property string $userID
 * @property integer $classID
 * @property integer $lessonID
 * @property string $mark
 * @property string $time
 */
class StudentSign extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_sign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Sign_ID, userID, classID, lessonID, mark, time', 'required'),
			array('Sign_ID, classID, lessonID', 'numerical', 'integerOnly'=>true),
			array('userID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Sign_ID, userID, classID, lessonID, mark, time', 'safe', 'on'=>'search'),
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
			'Sign_ID' => 'Sign',
			'userID' => 'User',
			'classID' => 'Class',
			'lessonID' => 'Lesson',
			'mark' => 'Mark',
			'time' => 'Time',
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

		$criteria->compare('Sign_ID',$this->Sign_ID);
		$criteria->compare('userID',$this->userID,true);
		$criteria->compare('classID',$this->classID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('mark',$this->mark,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    public function ismark($studentID,$lessonID){
        $studentsign = new StudentSign();
        $studentsign = $studentsign->findAll("userID = '$studentID' AND lessonID = '$lessonID' AND mark = 1");
        return $studentsign;
    } 
//     public function Absence($studentID,$lessonID){
//
//     }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentSign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
