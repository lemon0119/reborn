<?php

/**
 * This is the model class for table "suite_exercise".
 *
 * The followings are the available columns in table 'suite_exercise':
 * @property integer $suiteID
 * @property integer $exerciseID
 * @property string $type
 */
class SuiteExercise extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'suite_exercise';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, exerciseID, type', 'required'),
			array('suiteID, exerciseID', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('suiteID, exerciseID, type', 'safe', 'on'=>'search'),
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
			'suiteID' => 'Suite',
			'exerciseID' => 'Exercise',
			'type' => 'Type',
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

		$criteria->compare('suiteID',$this->suiteID);
		$criteria->compare('exerciseID',$this->exerciseID);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function insertWork($type,$exerciseID,$suiteID) {
            $newSuiteExercise = new SuiteExercise();
            $newSuiteExercise->type  = $type;
            $newSuiteExercise->exerciseID = $exerciseID;
            $newSuiteExercise->suiteID = $suiteID;
            if($this->findAll(array(
                'condition' => 'type =:type and exerciseID=:exerciseID and suiteID=:suiteID',
                'params' =>array(':type' => $type,':exerciseID'=>$exerciseID,':suiteID'=>$suiteID)
            ))!= NULL) 
            {
                return "HAVEN";
            }
               
            else 
            {
                return $newSuiteExercise->insert();
            }
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SuiteExercise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
