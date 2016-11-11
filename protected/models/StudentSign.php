<?php

/**
 * This is the model class for table "student_sign".
 *
 * The followings are the available columns in table 'student_sign':
 * @property integer $Sign_ID
 * @property string $userID
 * @property integer $classID
 * @property integer $lessonID
 * @property integer $mark
 * @property string $time
 * @property integer $times
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
			array('userID, classID, lessonID, mark, time', 'required'),
			array('classID, lessonID, mark, times', 'numerical', 'integerOnly'=>true),
			array('userID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Sign_ID, userID, classID, lessonID, mark, time, times', 'safe', 'on'=>'search'),
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
			'times' => 'Times',
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
		$criteria->compare('mark',$this->mark);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('times',$this->times);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    public function ismark($studentID,$lessonID,$classID){
        $sql = "select * FROM student_sign WHERE classID = '$classID' AND lessonID = '$lessonID' AND userID = '$studentID'Order By time Desc limit 0,1 ";
        $criteria   =   new CDbCriteria();
        $LastSign  =   Yii::app()->db->createCommand($sql)->queryAll(); 
        foreach ($LastSign as $key){
        $last = $key['times'];
        $studentsign = new StudentSign();
        $studentsign = $studentsign->findAll("userID = '$studentID' AND lessonID = '$lessonID' AND mark = 1 AND times = '$last'");
        return $studentsign;}
    } 
        public function allabsence($classID, $lessonID,$all,$times){
            if($times != null){
        $publishtime = date('y-m-d H:i:s',time());    
        $connection = Yii::app()->db;
        $sql = "INSERT INTO `student_sign` (time,mark,classID,userID,lessonID,times) values ('$publishtime',0,$classID,'$all',$lessonID,$times)"; 
        $command = $connection->createCommand($sql);
        $command->execute();
            }
            else
            {
        $publishtime = date('y-m-d H:i:s',time());    
        $connection = Yii::app()->db;
        $sql = "INSERT INTO `student_sign` (time,mark,classID,userID,lessonID,times) values ('$publishtime',0,$classID,'$all',$lessonID,1)"; 
        $command = $connection->createCommand($sql);
        $command->execute();
            }
    }  
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
