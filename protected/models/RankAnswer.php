<?php

/**
 * This is the model class for table "rank_answer".
 *
 * The followings are the available columns in table 'rank_answer':
 * @property string $answerID
 * @property integer $exerciseID
 * @property integer $workID
 * @property string $userID
 * @property string $userName
 * @property integer $correct
 * @property integer $missing_Number
 * @property integer $redundant_Number
 * @property string $speed
 * @property string $type
 * @property integer $backDelete
 * @property integer $isExam
 */
class RankAnswer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rank_answer';
	}
        //插入数据
        public function insertData($answerID,$exerciseID,$workID,$userID,$userName,$correct,$missing_Number,$redundant_Number,$speed,$type,$backDelete,$isExam) {
            error_log("zhixi-----------5");
            $newRank = new RankAnswer();
            $newRank->answerID = $answerID;
            $newRank->exerciseID = $exerciseID;
            $newRank->workID = $workID;
            $newRank->userID = $userID;
            $newRank->userName = $userName;
            $newRank->correct = $correct;
            $newRank->missing_Number = $missing_Number;
            $newRank->redundant_Number = $redundant_Number;
            $newRank->speed = $speed;
            $newRank->type = $type;
            $newRank->backDelete = $backDelete;
            $newRank->isExam = $isExam;
            $newRank->insert();
        }
        //排序
        public function getRankResult($workID,$exerciseID,$type,$isExam,$choice){
            $select = "select * from rank_answer";
            if($isExam == 2){
            $condition = " where exerciseID = '$exerciseID'";  
            }else{
            $condition = " where exerciseID = '$exerciseID' and workID = '$workID' and type = '$type' and isExam = '$isExam'";
            }
            if($choice=='correct'){
                $tag = 'correct';
            }else if( $choice == 'speed'){
                $tag ='speed';
            }else if ($choice == 'less'){
                $tag ='missing_Number';
            }else if ($choice == 'backDelete') {
                $tag ='backDelete';
            }else if ($choice == 'many'){
                $tag ='redundant_Number';
            }
            $order =" order by ".$tag." DESC";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->query();
            return $result;
        }

        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('answerID, isExam', 'required'),
			array('exerciseID, workID, correct, missing_Number, redundant_Number, backDelete, isExam', 'numerical', 'integerOnly'=>true),
			array('answerID, userID, userName', 'length', 'max'=>30),
			array('speed, type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('answerID, exerciseID, workID, userID, userName, correct, missing_Number, redundant_Number, speed, type, backDelete, isExam', 'safe', 'on'=>'search'),
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
			'answerID' => 'Answer',
			'exerciseID' => 'Exercise',
			'workID' => 'Work',
			'userID' => 'User',
			'userName' => 'User Name',
			'correct' => 'Correct',
			'missing_Number' => 'Missing Number',
			'redundant_Number' => 'Redundant Number',
			'speed' => 'Speed',
			'type' => 'Type',
			'backDelete' => 'Back Delete',
			'isExam' => 'Is Exam',
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

		$criteria->compare('answerID',$this->answerID,true);
		$criteria->compare('exerciseID',$this->exerciseID);
		$criteria->compare('workID',$this->workID);
		$criteria->compare('userID',$this->userID,true);
		$criteria->compare('userName',$this->userName,true);
		$criteria->compare('correct',$this->correct);
		$criteria->compare('missing_Number',$this->missing_Number);
		$criteria->compare('redundant_Number',$this->redundant_Number);
		$criteria->compare('speed',$this->speed,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('backDelete',$this->backDelete);
		$criteria->compare('isExam',$this->isExam);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RankAnswer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
