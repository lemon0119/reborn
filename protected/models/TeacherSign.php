<?php

/**
 * This is the model class for table "teacher_sign".
 *
 * The followings are the available columns in table 'teacher_sign':
 * @property integer $Sign_ID
 * @property string $teacherID
 * @property integer $classID
 * @property integer $lessonID
 * @property integer $mark
 * @property string $Sign_Time
 */
class TeacherSign extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teacher_sign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classID, lessonID, mark', 'numerical', 'integerOnly'=>true),
			array('teacherID', 'length', 'max'=>30),
			array('Sign_Time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Sign_ID, teacherID, classID, lessonID, mark, Sign_Time', 'safe', 'on'=>'search'),
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
			'teacherID' => 'Teacher',
			'classID' => 'Class',
			'lessonID' => 'Lesson',
			'mark' => 'Mark',
			'Sign_Time' => 'Sign Time',
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
		$criteria->compare('teacherID',$this->teacherID,true);
		$criteria->compare('classID',$this->classID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('mark',$this->mark);
		$criteria->compare('Sign_Time',$this->Sign_Time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
   //签到     
        public function issign($classID,$lessonID){
        $TeacherSign = new TeacherSign();
        $TeacherSign = $TeacherSign->findAll("classID = '$classID' AND lessonID = '$lessonID' AND mark = 1");
        return $TeacherSign;
    }
        public function hassign($classID,$lessonID){
        $TeacherSign = new TeacherSign();
        $TeacherSign = $TeacherSign->findAll("classID = '$classID' AND lessonID = '$lessonID'");
        return $TeacherSign;
    }
        

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TeacherSign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
