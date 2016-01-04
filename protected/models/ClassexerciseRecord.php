<?php

/**
 * This is the model class for table "classexercise_record".
 *
 * The followings are the available columns in table 'classexercise_record':
 * @property integer $id
 * @property integer $classExerciseID
 * @property string $studentID
 * @property integer $squence
 * @property string $ratio_speed
 * @property string $ratio_correct
 * @property string $ratio_maxSpeed
 * @property string $ratio_backDelete
 * @property string $ratio_maxKeyType
 * @property string $ratio_averageKeyType
 * @property string $ratio_maxInternalTime
 * @property string $ratio_countAllKey
 */
class ClassexerciseRecord extends CActiveRecord
{
    
     public function insertClassexerciseRecord($classExerciseID,$studentID,$squence,$ratio_speed,$ratio_correct,$ratio_maxSpeed,$ratio_backDelete,$ratio_maxKeyType,$ratio_averageKeyType,$ratio_internalTime,$ratio_maxInternalTime,$ratio_countAllKey){
        $newClassexerciseRecord = new ClassexerciseRecord();
        $newClassexerciseRecord->classExerciseID = $classExerciseID;
        $newClassexerciseRecord->studentID = $studentID;
        $newClassexerciseRecord->squence = $squence;
        $newClassexerciseRecord->ratio_speed = $ratio_speed;
        $newClassexerciseRecord->ratio_correct =$ratio_correct;
        $newClassexerciseRecord->ratio_maxSpeed =$ratio_maxSpeed;
        $newClassexerciseRecord->ratio_backDelete = $ratio_backDelete;
        $newClassexerciseRecord->ratio_maxKeyType = $ratio_maxKeyType;
        $newClassexerciseRecord->ratio_averageKeyType =$ratio_averageKeyType;
        $newClassexerciseRecord->ratio_internalTime =$ratio_internalTime;
        $newClassexerciseRecord->ratio_maxInternalTime= $ratio_maxInternalTime;
        $newClassexerciseRecord->ratio_countAllKey = $ratio_countAllKey;
        
        return $newClassexerciseRecord->insert();
    }
    public function updateClassexerciseRecord($classExerciseID,$studentID,$squence,$ratio_speed,$ratio_correct,$ratio_maxSpeed,$ratio_backDelete,$ratio_maxKeyType,$ratio_averageKeyType,$ratio_internalTime,$ratio_maxInternalTime,$ratio_countAllKey){
                $sqlClassExerciseRecord = ClassexerciseRecord::model()->find("classExerciseID = '$classExerciseID' and squence = '$squence' and studentID = '$studentID'");
                $sqlClassExerciseRecord->ratio_averageKeyType = $sqlClassExerciseRecord['ratio_averageKeyType']."&".$ratio_averageKeyType;
                $sqlClassExerciseRecord->ratio_maxKeyType = $sqlClassExerciseRecord['ratio_maxKeyType']."&".$ratio_maxKeyType;
                $sqlClassExerciseRecord->ratio_maxSpeed = $sqlClassExerciseRecord['ratio_maxSpeed']."&".$ratio_maxSpeed;
                $sqlClassExerciseRecord->ratio_speed = $sqlClassExerciseRecord['ratio_speed']."&".$ratio_speed;
                $sqlClassExerciseRecord->ratio_backDelete = $sqlClassExerciseRecord['ratio_backDelete']."&".$ratio_backDelete;
                $sqlClassExerciseRecord->ratio_internalTime = $sqlClassExerciseRecord['ratio_internalTime']."&".$ratio_internalTime;
                $sqlClassExerciseRecord->ratio_maxInternalTime = $sqlClassExerciseRecord['ratio_maxInternalTime']."&".$ratio_maxInternalTime;
                $sqlClassExerciseRecord->ratio_correct = $sqlClassExerciseRecord['ratio_correct']."&".$ratio_correct;
                $sqlClassExerciseRecord->ratio_countAllKey = $sqlClassExerciseRecord['ratio_countAllKey']."&".$ratio_countAllKey;
                $sqlClassExerciseRecord->update();
                $sqlClassExerciseRecord->ratio_averageKeyType = "";
                $sqlClassExerciseRecord->ratio_maxKeyType = "";
                $sqlClassExerciseRecord->ratio_maxSpeed = "";
                $sqlClassExerciseRecord->ratio_speed = "";
                $sqlClassExerciseRecord->ratio_backDelete = "";
                $sqlClassExerciseRecord->ratio_internalTime = "";
                $sqlClassExerciseRecord->ratio_maxInternalTime = "";
                $sqlClassExerciseRecord->ratio_correct = ""; 
                $sqlClassExerciseRecord->ratio_countAllKey = "";
        return "";
    }
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
			array('classExerciseID, studentID, squence, ratio_speed, ratio_correct, ratio_maxSpeed, ratio_backDelete, ratio_maxKeyType, ratio_averageKeyType, ratio_maxInternalTime, ratio_countAllKey', 'required'),
			array('classExerciseID, squence', 'numerical', 'integerOnly'=>true),
			array('studentID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, classExerciseID, studentID, squence, ratio_speed, ratio_correct, ratio_maxSpeed, ratio_backDelete, ratio_maxKeyType, ratio_averageKeyType, ratio_maxInternalTime, ratio_countAllKey', 'safe', 'on'=>'search'),
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
			'squence' => 'Squence',
			'ratio_speed' => 'Ratio Speed',
			'ratio_correct' => 'Ratio Correct',
			'ratio_maxSpeed' => 'Ratio Max Speed',
			'ratio_backDelete' => 'Ratio Back Delete',
			'ratio_maxKeyType' => 'Ratio Max Key Type',
			'ratio_averageKeyType' => 'Ratio Average Key Type',
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
		$criteria->compare('squence',$this->squence);
		$criteria->compare('ratio_speed',$this->ratio_speed,true);
		$criteria->compare('ratio_correct',$this->ratio_correct,true);
		$criteria->compare('ratio_maxSpeed',$this->ratio_maxSpeed,true);
		$criteria->compare('ratio_backDelete',$this->ratio_backDelete,true);
		$criteria->compare('ratio_maxKeyType',$this->ratio_maxKeyType,true);
		$criteria->compare('ratio_averageKeyType',$this->ratio_averageKeyType,true);
		$criteria->compare('ratio_maxInternalTime',$this->ratio_maxInternalTime,true);
		$criteria->compare('ratio_countAllKey',$this->ratio_countAllKey,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getSingleRecord($studentID,$exerciseID){
            $classexerciseRecord = new ClassexerciseRecord();
            $sql = "SELECT MAX(squence) FROM classexercise_record WHERE studentID = '$studentID' AND classExerciseID = $exerciseID";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $squenceSQL = $command->query();
            $squence = "";
            foreach ($squenceSQL as $v){
                if($v['MAX(squence)']!=""){
                     $squence = $v['MAX(squence)'];
                }
            }
            $classexerciseRecord = $this->find("studentID = '$studentID' AND classExerciseID = $exerciseID AND squence = '$squence'");
            return $classexerciseRecord;
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
