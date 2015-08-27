<?php

/**
 * This is the model class for table "class_lesson_suite".
 *
 * The followings are the available columns in table 'class_lesson_suite':
 * @property integer $ID
 * @property integer $suiteID
 * @property integer $lessonID
 * @property integer $classID
 */
class ClassLessonSuite extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_lesson_suite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ID, suiteID, lessonID, classID', 'required'),
			array('ID, suiteID, lessonID, classID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, suiteID, lessonID, classID', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'suiteID' => 'Suite',
			'lessonID' => 'Lesson',
			'classID' => 'Class',
		);
	}
        
        public function getSuiteByClassAndLesson($classID,$lessonID)
        {
            
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('suiteID',$this->suiteID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('classID',$this->classID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function insertSuite($classID,$lessonID,$suiteID)
        {
            $sql = "select max(ID) as id from class_lesson_suite";
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
            
            $newSuite = new ClassLessonSuite();
            $newSuite->ID = $new_id;
            $newSuite->classID = $classID;
            $newSuite->lessonID = $lessonID;
            $newSuite->suiteID = $suiteID;
            $newSuite->insert();           
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassLessonSuite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
