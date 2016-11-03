<?php

/**
 * This is the model class for table "exercise_level".
 *
 * The followings are the available columns in table 'exercise_level':
 * @property integer $practiceID
 * @property integer $exerciseID
 * @property integer $classID
 * @property integer $lessonID
 * @property string $level
 */
class ExerciseLevel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exercise_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exerciseID, classID, lessonID', 'required'),
			array('exerciseID, classID, lessonID', 'numerical', 'integerOnly'=>true),
			array('level', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('practiceID, exerciseID, classID, lessonID, level', 'safe', 'on'=>'search'),
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
			'practiceID' => 'Practice',
			'exerciseID' => 'Exercise',
			'classID' => 'Class',
			'lessonID' => 'Lesson',
			'level' => 'Level',
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

		$criteria->compare('practiceID',$this->practiceID);
		$criteria->compare('exerciseID',$this->exerciseID);
		$criteria->compare('classID',$this->classID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('level',$this->level,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        public function insertLevel($classID,$lessonID,$exerciseID,$level) {
            $sql = "select max(practiceID) as id from exercise_level";
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
            $newLevel = new ExerciseLevel();
            $newLevel->practiceID=$new_id;
            $newLevel->classID = $classID;           
            $newLevel->lessonID = $lessonID;
            $newLevel->exerciseID = $exerciseID;
            $newLevel->level = $level;
            $newLevel->insert();
        }

        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExerciseLevel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
