<?php

/**
 * This is the model class for table "answer_data".
 *
 * The followings are the available columns in table 'answer_data':
 * @property string $answerID
 * @property string $correct_Number
 * @property string $error_Number
 * @property string $missing_Number
 * @property string $redundant_Number
 * @property string $standard_Number
 * @property string $answer_Number
 * @property string $standard_Ignore_symbol
 * @property string $answer_Ignore_symbol
 */
class AnswerData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'answer_data';
	}
        
        public static function updataAnswerData1($answerID,$error_Number,$missing_Number,$redundant_Number,$standard_Number,$standard_lgnore_symbol,$answer_lgnore_symbol,$correct_Answer){
            $connection = Yii::app()->db;    
            $sql = "UPDATE `answer_data` SET error_Number = '$error_Number' , missing_Number = '$missing_Number', standard_Number = '$standard_Number',redundant_Number = '$redundant_Number',standard_Ignore_symbol='$standard_lgnore_symbol',answer_Ignore_symbol='$answer_lgnore_symbol',correct_Answer='$correct_Answer' where answerID = $answerID";
            $command = $connection->createCommand($sql);
            $command->execute();
        }
        
        public static function saveAnswerData($answerID,$correct_Number,$error_Number,$missing_Number,$redundant_Number,$standard_Number,$answer_Number,$standard_Ignore_symbol,$answer_Ignore_symbol){
            $oldAnswer = AnswerData::model()->find("answerID=?",array($answerID));
            if ($oldAnswer == null) {
                $newAnswer = new AnswerData();
                $newAnswer->answerID = $answerID;
                $newAnswer->correct_Number = $correct_Number;
                $newAnswer->standard_Number = $standard_Number;
                $newAnswer->answer_Number = $answer_Number;
                $newAnswer->standard_Ignore_symbol = $standard_Ignore_symbol;
                $newAnswer->answer_Ignore_symbol = $answer_Ignore_symbol;
                if (!($newAnswer->insert())) {
                    echo Tool::jsLog('创建答案记录失败！');
                }
            } else {
//                $oldAnswer = new AnswerData();
//                $oldAnswer->correct_Number = $oldAnswer['correct_Number'] ;
//                $oldAnswer->error_Number = $oldAnswer['error_Number'] . "&" . $error_Number;
//                $oldAnswer->missing_Number = $oldAnswer['missing_Number'] . "&" . $missing_Number;
//                $oldAnswer->redundant_Number = $oldAnswer['redundant_Number'] . "&" . $redundant_Number;
//                $oldAnswer->standard_Number = $oldAnswer['standard_Number'] . "&" . $standard_Number;
//                $oldAnswer->answer_Number = $oldAnswer['answer_Number'] . "&" . $answer_Numbere;
//                $oldAnswer->standard_Ignore_symbol = $oldAnswer['standard_Ignore_symbol'] . "&" . $standard_Ignore_symbol;
//                $oldAnswer->answer_Ignore_symbol = $oldAnswer['answer_Ignore_symbol'] . "&" . $answer_Ignore_symbol;
//                if (!($oldAnswer->update())) {
//                    echo Tool::jsLog('创建答案记录失败！');
//                }
//                $oldAnswer->correct_Number = "";
//                $oldAnswer->error_Number = "";
//                $oldAnswer->missing_Number = "";
//                $oldAnswer->redundant_Number = "";
//                $oldAnswer->standard_Number = "";
//                $oldAnswer->answer_Number = "";
//                $oldAnswer->standard_Ignore_symbol = "";
//                $oldAnswer->answer_Ignore_symbol = "";
//                return "";
                
        $oldcn_1 = $oldAnswer['correct_Number'] . "&" .$correct_Number;
//        $olden_2 = $oldAnswer['error_Number'] . "&" . $error_Number;
        $olden_6 = $oldAnswer['answer_Number'] . "&" . $answer_Number;
        $connection = Yii::app()->db;    
        $sql = "UPDATE `answer_data` SET correct_Number = '$oldcn_1' , answer_Number = '$olden_6' where answerID = $answerID";
        $command = $connection->createCommand($sql);
        $command->execute();
            }
        }


        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('answerID', 'required'),
			array('answerID', 'length', 'max'=>30),
			array('correct_Number, error_Number, missing_Number, redundant_Number, standard_Number, answer_Number, standard_Ignore_symbol, answer_Ignore_symbol，correct_Answer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('answerID, correct_Number, error_Number, missing_Number, redundant_Number, standard_Number, answer_Number, standard_Ignore_symbol, answer_Ignore_symbol，correct_Answer', 'safe', 'on'=>'search'),
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
			'correct_Number' => 'Correct Number',
			'error_Number' => 'Error Number',
			'missing_Number' => 'Missing Number',
			'redundant_Number' => 'Redundant Number',
			'standard_Number' => 'Standard Number',
			'answer_Number' => 'Answer Number',
			'standard_Ignore_symbol' => 'Standard Ignore Symbol',
			'answer_Ignore_symbol' => 'Answer Ignore Symbol',
                        'correct_Answer' => 'Correct Answer',
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
		$criteria->compare('correct_Number',$this->correct_Number,true);
		$criteria->compare('error_Number',$this->error_Number,true);
		$criteria->compare('missing_Number',$this->missing_Number,true);
		$criteria->compare('redundant_Number',$this->redundant_Number,true);
		$criteria->compare('standard_Number',$this->standard_Number,true);
		$criteria->compare('answer_Number',$this->answer_Number,true);
		$criteria->compare('standard_Ignore_symbol',$this->standard_Ignore_symbol,true);
		$criteria->compare('answer_Ignore_symbol',$this->answer_Ignore_symbol,true);
                $criteria->compare('correct_Answer',$this->correct_Answer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AnswerData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
