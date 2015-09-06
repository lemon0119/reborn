<?php

/**
 * This is the model class for table "class_exam".
 *
 * The followings are the available columns in table 'class_exam':
 * @property integer $classID
 * @property integer $examID
 * @property integer $open
 * @property integer $workID
 */
class ClassExam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_exam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classID, examID, open', 'required'),
			array('classID, examID, open, workID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('classID, examID, open, workID', 'safe', 'on'=>'search'),
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
			'classID' => 'Class',
			'examID' => 'Exam',
			'open' => 'Open',
			'workID' => 'Work',
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

		$criteria->compare('classID',$this->classID);
		$criteria->compare('examID',$this->examID);
		$criteria->compare('open',$this->open);
		$criteria->compare('workID',$this->workID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function insertExam($classID,$examID)
        {
            $sql = "select max(workID) as id from class_exam";
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
            
            $newExam = new ClassExam();
            $newExam->workID = $new_id;
            $newExam->classID = $classID;           
            $newExam->examID = $examID;
            $newExam->open = true;
            $newExam->insert();           
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassExam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
