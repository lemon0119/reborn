<?php

/**
 * This is the model class for table "exam_record".
 *
 * The followings are the available columns in table 'exam_record':
 * @property string $recordID
 * @property double $ratio_accomplish
 * @property double $ratio_correct
 * @property integer $createPerson
 * @property string $createTime
 * @property integer $workID
 * @property integer $score
 * @property string $modifyTime
 * @property integer $studentID
 */
class ExamRecord extends CActiveRecord
{
        public static function saveExamRecord (&$recordID) {
            if(Yii::app()->session['isExam'])
              $workID = Yii::app()->session['examworkID'];
            else 
              $workID = Yii::app()->session['workID']; 
            $createPerson = Yii::app()->session['userid_now'];
            $oldID = ExamRecord::getRecord($workID, $createPerson);
            Yii::app()->session['examRecordID']=$oldID;
            if($oldID == null) {
                $newID = Tool::createID();
                $newRecord = new ExamRecord();
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
            }
//            else {
//                $oldRecord = ExamRecord::model()->find('recordID=?', array($oldID));
//                $oldRecord->modifyTime = date("Y-m-d H:i:s");
//                if(!($oldRecord->upDate())) {
//                    echo Tool::jsLog('更新练习记录失败！');
//                    return false;
//                }
//                $recordID = $oldRecord->recordID;
//                return true;
//            }
        }
        public static function getRecord($workID, $createPerson, $lesnID = '0') {
            //$select = "select recordID from suite_record ";
            //$condition = "where studentID = '$createPerson' and workID = ";
            $record = ExamRecord::model()->find('workID=? and studentID=?', array($workID,$createPerson));
            if($record == null) {
                return null;
            } else {
                return $record->recordID;
            }
        }
        public static function overExam($recordID){
        if(Yii::app()->session['examRecordID']){
            $recordID=Yii::app()->session['examRecordID'];
        }
        $record = ExamRecord::model()->findByPK($recordID);
        $record -> ratio_accomplish = 1;
        if(!($record->upDate())) {
            echo Tool::jsLog('最后提交失败！');
            return false;
        }
        return true;
    }
     public function getExamRecordAccomplish($recordID){
        $ratio_accomplish = $this->find("recordID = '$recordID'");
		return $ratio_accomplish['ratio_accomplish'];
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('recordID, createPerson, createTime', 'required'),
			array('createPerson, workID, score, studentID', 'numerical', 'integerOnly'=>true),
			array('ratio_accomplish, ratio_correct', 'numerical'),
			array('recordID', 'length', 'max'=>30),
			array('modifyTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recordID, ratio_accomplish, ratio_correct, createPerson, createTime, workID, score, modifyTime, studentID', 'safe', 'on'=>'search'),
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
			'ratio_accomplish' => 'Ratio Accomplish',
			'ratio_correct' => 'Ratio Correct',
			'createPerson' => 'Create Person',
			'createTime' => 'Create Time',
			'workID' => 'Work',
			'score' => 'Score',
			'modifyTime' => 'Modify Time',
			'studentID' => 'Student',
		);
	}
        
        
            public function getNextStudentID($workID,$studentID,$accomplished,$classID)
    {
        if($accomplished !=1)
        {
             $result =  Student::model()->find('classID=? and userID>? and userID not in(select studentID from exam_record where workID=? and ratio_accomplish=1) order by userID ASC', array($classID,$studentID,$workID));
             if($result != NULL)
                 return $result['userID'];   
             else
                 return -1;
        }else
        {   
            $result =  ExamRecord::model()->find('workID=? and studentID>? and ratio_accomplish=1 order by studentID ASC', array($workID,$studentID));
            if($result != NULL)
                return $result['studentID'];
            else
                return -1;
         }
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
		$criteria->compare('ratio_accomplish',$this->ratio_accomplish);
		$criteria->compare('ratio_correct',$this->ratio_correct);
		$criteria->compare('createPerson',$this->createPerson);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('workID',$this->workID);
		$criteria->compare('score',$this->score);
		$criteria->compare('modifyTime',$this->modifyTime,true);
		$criteria->compare('studentID',$this->studentID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
