<?php

/**
 * This is the model class for table "suite".
 *
 * The followings are the available columns in table 'suite':
 * @property string $suiteID
 * @property string $courseID
 * @property string $suiteName
 * @property string $suiteType
 * @property string $createTime
 * @property string $createPerson
 * @property string $classID
 */
class Suite extends CActiveRecord
{
    public function getClassworkNow(){
        $userid =Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($userid);
        $course = TbClass::model()->findCourseByClassID($classID);
        $lesson = TbClass::model()->findLessonByCourse($course[0]['courseID']);
        $time = date("Y-m-d  H:i:s");
        $getRightSuite = "select suiteID from classwork where begintime<'$time' and endtime>'$time'";
        $condition = " where classID = '$classID' and suiteType = 'classwork' and suiteID in ($getRightSuite)";
        $select = "select * from suite";
        $sql = $select.$condition;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }
    
    public function getExerNum($suiteID){
        foreach (Tool::$EXER_TYPE as $type) {
            $record = $this->getSuiteExerByType( $suiteID, $type);
            $result[$type] = $record->getRowCount();
        }
        return $result;
    }
    
    public function getSuiteExerByType( $suiteID, $type)
    {
        switch ($type){
            case 'choice':
                $result = $this->getchoice($suiteID);
                break;
            case 'filling':
                $result = $this->getFilling($suiteID);
                break;
            case 'question':
                $result = $this->getQuestion($suiteID);
                break;
            case 'key':
                $result = $this->getKeyExer($suiteID);
                break;
            case 'listen':
                $result = $this->getListenExer($suiteID);
                break;
            default :
                $result = $this->getLookExer($suiteID);
        }
        return $result;
    }
    
    	public function getchoice($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='choice')";
            $select = "select * from choice";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
    	public function getFilling($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='filling')";
            $select = "select * from filling";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
    	public function getQuestion($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='question')";
            $select = "select * from question";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
        public function getKeyExer($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='key')";
            $select = "select * from key_type";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
        public function getListenExer($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='listen')";
            $select = "select * from listen_type";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
        public function getLookExer($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='look')";
            $select = "select * from look_type";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'suite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, courseID, suiteName, suiteType, createTime, createPerson', 'required'),
			array('suiteID, courseID, createPerson, classID', 'length', 'max'=>30),
			array('suiteName', 'length', 'max'=>60),
			array('suiteType', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('suiteID, courseID, suiteName, suiteType, createTime, createPerson, classID', 'safe', 'on'=>'search'),
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
			'courseID' => 'Course',
			'suiteName' => 'Suite Name',
			'suiteType' => 'Suite Type',
			'createTime' => 'Create Time',
			'createPerson' => 'Create Person',
			'classID' => 'Class',
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

		$criteria->compare('suiteID',$this->suiteID,true);
		$criteria->compare('courseID',$this->courseID,true);
		$criteria->compare('suiteName',$this->suiteName,true);
		$criteria->compare('suiteType',$this->suiteType,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('createPerson',$this->createPerson,true);
		$criteria->compare('classID',$this->classID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Suite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
