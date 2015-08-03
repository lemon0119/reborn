<?php

/**
 * This is the model class for table "exam".
 *
 * The followings are the available columns in table 'exam':
 * @property integer $suiteID
 * @property string $suiteName
 * @property string $begintime
 * @property string $endtime
 * @property string $createTime
 * @property integer $createPerson
 */
class Exam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, suiteName, begintime, endtime, createTime, createPerson', 'required'),
			array('suiteID, createPerson', 'numerical', 'integerOnly'=>true),
			array('suiteName', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('suiteID, suiteName, begintime, endtime, createTime, createPerson', 'safe', 'on'=>'search'),
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
			'suiteName' => 'Suite Name',
			'begintime' => 'Begintime',
			'endtime' => 'Endtime',
			'createTime' => 'Create Time',
			'createPerson' => 'Create Person',
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
		$criteria->compare('suiteName',$this->suiteName,true);
		$criteria->compare('begintime',$this->begintime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('createPerson',$this->createPerson);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
 //宋杰 2015-7-31 查找班级所有考试       
    public function getClassexamAll(){
        $userid =Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($userid);
        $select = 'select begintime , endtime , exam.suiteID , suiteName from exam , classsuite';
        $time = date("Y-m-d  H:i:s");
        $condition = " where exam.suiteID = classsuite.suiteID and classId = '$classID'";
        $order = ' order by suiteID';
        $sql = $select.$condition.$order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
