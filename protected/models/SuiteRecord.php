<?php

/**
 * This is the model class for table "suite_record".
 *
 * The followings are the available columns in table 'suite_record':
 * @property string $recordID
 * @property string $suiteID
 * @property string $studentID
 * @property string $createTime
 * @property string $modifyTime
 */
class SuiteRecord extends CActiveRecord
{
    public static function getRecord($workID, $createPerson, $lesnID = '0') {
        //$select = "select recordID from suite_record ";
        //$condition = "where studentID = '$createPerson' and workID = ";
        $record = SuiteRecord::model()->find('workID=? and studentID=?', array($workID,$createPerson));
        if($record == null) {
            return null;
        } else {
            return $record->recordID;
        }
    }
    public static function overSuite($recordID){
        $record = SuiteRecord::model()->findByPK($recordID);
        $record -> ratio_accomplish = 1;
        if(!($record->upDate())) {
            echo Tool::jsLog('最后提交失败！');
            return false;
        }
        return true;
    }
    public function getSuitRecordAccomplish($recordID){
        $ratio_accomplish = $this->find("recordID = '$recordID'");
		return $ratio_accomplish['ratio_accomplish'];
    }

    public static function getClassworkAll($type) {
        $createPerson = Yii::app()->session['userid_now'];
        $getSuiteID = "select suiteID from suite where suiteType='$type'";
        $condition = "where suiteID in ($getSuiteID)";
        $select = "select * from suite_record ";
        $sql = $select.$condition;
        $result = Yii::app()->db->createCommand($sql)->query();
        //if($result == NULL) echo $sql."<br/>";
        return $result;
    }
    
    public static function saveSuiteRecord (&$recordID) {
        $suiteID = Yii::app()->session['suiteID'];
        $workID = Yii::app()->session['workID'];
        $createPerson = Yii::app()->session['userid_now'];
        $oldID = SuiteRecord::getRecord($workID, $createPerson);
        if($oldID == null) {
            $newID = Tool::createID();
            $newRecord = new SuiteRecord();
            $newRecord->recordID = $newID;
            $newRecord->studentID = $createPerson;
            $newRecord->workID = $workID;
            $newRecord->createTime = date("Y-m-d  H:i:s");
            $newRecord->modifyTime = $newRecord->createTime;
            if(!($newRecord->insert())) {
                echo Tool::jsLog('创建练习记录失败！');
                return false;
            }
            $recordID = $newID;
            return true;
        } else {
            $oldRecord = SuiteRecord::model()->find('recordID=?', array($oldID));
            $oldRecord->modifyTime = date("Y-m-d H:i:s");
            if(!($oldRecord->upDate())) {
                echo Tool::jsLog('更新练习记录失败！');
                return false;
            }
            $recordID = $oldRecord->recordID;
            return true;
        }
    }
    
    public function getNextStudentID($workID,$studentID,$accomplished,$classID)
    {
        if($accomplished !=1)
        {
             $result =  Student::model()->find('classID=? and userID>? and userID not in(select studentID from suite_record where workID=? and ratio_accomplish=1) order by userID ASC', array($classID,$studentID,$workID));
             if($result != NULL)
                 return $result['userID'];   
             else
                 return -1;
        }else
        {           
            $result =  SuiteRecord::model()->find('workID=? and studentID>? and ratio_accomplish=1 order by studentID ASC', array($workID,$studentID));
            if($result != NULL)
                return $result['studentID'];
            else
                return -1;
         }
    }
    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'suite_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, studentID, createTime, modifyTime', 'required'),
			array('recordID, suiteID, studentID', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recordID, suiteID, studentID, createTime, modifyTime', 'safe', 'on'=>'search'),
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
			'recordID' => 'Record',
			'suiteID' => 'Suite',
			'studentID' => 'Student',
			'createTime' => 'Create Time',
			'modifyTime' => 'Modify Time',
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

		$criteria->compare('recordID',$this->recordID,true);
		$criteria->compare('suiteID',$this->suiteID,true);
		$criteria->compare('studentID',$this->studentID,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('modifyTime',$this->modifyTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SuiteRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
