<?php

/**
 * This is the model class for table "answer_record".
 *
 * The followings are the available columns in table 'answer_record':
 * @property string $answerID
 * @property string $recordID
 * @property string $exerciseID
 * @property string $type
 * @property string $answer
 * @property string $createPerson
 * @property string $createTime
 */
class AnswerRecord extends CActiveRecord
{
    
    public static function ansToArray($answer){
        if($answer == NULL)
            return NULL;
        $result = array();
        $num=Yii::app()->session['num'];
        for($i=1;$i<=$num;$i++){
            $result[$i]="0";
        }
        foreach ($answer as $one) {
            $key = $one['exerciseID'];
            $value = $one['answer'];
            $result[$key] = $value;
        }
        return $result;
    }
    public static function saveFilling($recordID){
        $res = true;
        if(Yii::app()->session['isExam'])
            $suiteID = Yii::app()->session['examsuiteID'];
        else
            $suiteID = Yii::app()->session['suiteID'];
        $filling = Suite::model()->getFilling($suiteID);
        foreach ($filling as $record) {
            $str = $record['answer'];
            $strArry = explode("$$",$str);
            $name = '1'.'filling'.$record['exerciseID'];
            $answer = isset($_POST[$name]) ? $_POST[$name] : '';
            $nullAnswer = '';
            for($i = 2;$i <= count($strArry); $i++){
                $name = $i.'filling'.$record['exerciseID'];
                $answer = isset($_POST[$name]) ? $answer.'$$'.$_POST[$name] : $answer.'$$'.'';
                $nullAnswer .= '$$';
            }
            if($answer !== $nullAnswer) {
                $res = AnswerRecord::saveKnlgAnswer($recordID, $answer, "filling", $record['exerciseID']);
                if($res === false)
                    return false;
            }
        }
        return $res;
    }
    
    public static function saveChoice($recordID){
        $res = true;
        if(Yii::app()->session['isExam'])
            $suiteID = Yii::app()->session['examsuiteID'];
        else
            $suiteID = Yii::app()->session['suiteID'];
        $choice = Suite::model()->getchoice($suiteID);
        foreach ($choice as $record) {
            $name = 'choice'.$record['exerciseID'];
            $answer = isset($_POST[$name]) ? $_POST[$name] : '';
            if($answer !== '') {
                $res = AnswerRecord::saveKnlgAnswer($recordID, $answer, "choice", $record['exerciseID']);
                if($res === false)
                    return false;
            }
        }
        return $res;
    }
    
    public static function saveQuestion($recordID){
        $res = true;
        if(Yii::app()->session['isExam'])
            $suiteID = Yii::app()->session['examsuiteID'];
        else
            $suiteID = Yii::app()->session['suiteID'];
        $quest = Suite::model()->getQuestion($suiteID);
        foreach ($quest as $record) {
            $name = 'quest'.$record['exerciseID'];
            $answer = isset($_POST[$name]) ? $_POST[$name] : '';
            if($answer !== '') {
                $res = AnswerRecord::saveKnlgAnswer($recordID, $answer, "question", $record['exerciseID']);
                if($res === false)
                    return false;
            }
        }
        return $res;
    }    
    public static function getAnswer($recordID, $type, $exerID) {
        $userID = Yii::app()->session['userid_now'];
        $record = AnswerRecord::model()->find('recordID=? and exerciseID=? and type=? and createPerson =?', array($recordID, $exerID, $type, $userID));
        return $record;
    }
    public function getAnswerByType($recordID, $type){
        $userID = Yii::app()->session['userid_now'];
        $record = AnswerRecord::model()->findAll('recordID=? and type=? and createPerson =?', array($recordID, $type, $userID));
        return $record;
    }
    public function getAnswerNum($suiteID){
        $createPerson = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($suiteID, $createPerson);
        foreach (Tool::$EXER_TYPE as $type) {
            $record = $this->getAnswerByType($recordID, $type);
            $result[$type] = count($record);
        }
        return $result;
    }
    public function getAnswerCorrect($suiteID){
        $createPerson = Yii::app()->session['userid_now'];
        $recordID = SuiteRecord::getRecord($suiteID, $createPerson);
        $result = [];
        foreach (Tool::$EXER_TYPE as $type) {
            $record = $this->getAnswerByType($recordID, $type);
            foreach ($record as $row){
                $result[$type][] = $row['ratio_correct'];
            }
        }
        return $result;
    }
    
    public static function getRatio($exerID, $type, $answer){
        switch ($type) {
            case "listen":
                $exer = ListenType::model()->find('exerciseID=?',[$exerID]);
                break;
            case "look":
                $exer = LookType::model()->find('exerciseID=?',[$exerID]);
                break;
            case "key":
                $exer = KeyType::model()->find('exerciseID=?',[$exerID]);
                break;
            default:
                $exer = NULL;
                break;
        }
        $str1 = $exer['content'];
        $ratio['correct'] = $_POST['nm_correct'];
        $accomp = strlen($answer) / strlen($str1);
        $ratio['accomplish'] = $accomp > 1 ? 1 : $accomp;
        return $ratio;
    }
    
//    public static function saveAnswer($recordID, $answer, $seconds) {
//        $userID = Yii::app()->session['userid_now'];
//        $exerID = Yii::app()->session['exerID'];
//        $type = Yii::app()->session['exerType'];
//        $type = str_replace(["Exer"],"",$type);
//        $ratio = self::getRatio($exerID, $type, $answer);
//        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
//        if( $oldAnswer == null) {
//            $newAnswer = new AnswerRecord();
//            $newAnswer -> answerID = Tool::createID();
//            $newAnswer -> recordID = $recordID;
//            $newAnswer -> exerciseID = $exerID;
//            $newAnswer -> type = $type;
//            $newAnswer -> answer = $answer;
//            $newAnswer -> costTime = $seconds;
//            $newAnswer -> ratio_correct = $ratio['correct'];
//            $newAnswer -> ratio_accomplish = $ratio['accomplish'];
//            $newAnswer -> createPerson = Yii::app()->session['userid_now'];
//            $newAnswer -> createTime = date("Y-m-d  H:i:s");
//            if(!($newAnswer->insert())) {
//                echo Tool::jsLog('创建答案记录失败！');
//                return false;
//            } else 
//                return true;
//        }else {
//            $oldAnswer -> answer = $answer;
//            $oldAnswer -> costTime = $seconds;
//            $oldAnswer -> ratio_correct = $ratio['correct'];
//            $oldAnswer -> ratio_accomplish = $ratio['accomplish'];
//            $oldAnswer -> createTime = date("Y-m-d  H:i:s");
//            if(!($oldAnswer->upDate())) {
//                echo Tool::jsLog('更新答案记录失败！');
//                return false;
//            } else
//                return true;
//        }
//    }   
    
     public static function saveAnswer($recordID, $correct=0,$AverageSpeed=0,$HighstSpeed=0,$BackDelete=0,$HighstCountKey=0,$AveragekeyType=0,$ratio_internalTime=0,$HighIntervarlTime=0,$countAllKey=0 ,$squence, $isExam) {  
        $userID = Yii::app()->session['userid_now'];
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
//        $ratio = self::getRatio($exerID, $type, $answer);
        
        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
        
        if( $oldAnswer == null) {
            $newAnswer = new AnswerRecord();
            $newAnswer -> answerID = Tool::createID();
            $newAnswer -> recordID = $recordID;
            $newAnswer -> exerciseID = $exerID;
            $newAnswer -> type = $type;
            $newAnswer->squence= $squence;
            $newAnswer -> ratio_correct = $correct;
//          $newAnswer -> ratio_accomplish = $ratio['accomplish'];
            $newAnswer -> createPerson = Yii::app()->session['userid_now'];
            $newAnswer -> createTime = date("Y-m-d  H:i:s");
            $newAnswer ->ratio_speed = $AverageSpeed;
            $newAnswer ->ratio_maxSpeed = $HighstSpeed;
            $newAnswer->ratio_backDelete = $BackDelete;
            $newAnswer->ratio_maxKeyType = $HighstCountKey;
            $newAnswer->ratio_averageKeyType = $AveragekeyType;
            $newAnswer->ratio_maxInternalTime = $HighIntervarlTime;
            $newAnswer->ratio_countAllKey = $countAllKey;
            $newAnswer->isExam = $isExam;
            if(!($newAnswer->insert())) {
                echo Tool::jsLog('创建答案记录失败！');
                return false;
            } else 
                return true;
        }else {
            $oldAnswer -> ratio_correct = $oldAnswer['ratio_correct'].'&'.$correct;
            
//          $oldAnswer -> ratio_accomplish = $ratio['accomplish'];
            $oldAnswer -> createTime = date("Y-m-d  H:i:s");
            $oldAnswer ->ratio_speed = $oldAnswer['ratio_speed'].'&'.$AverageSpeed;
            $oldAnswer ->ratio_maxSpeed = $oldAnswer['ratio_maxSpeed'].'&'.$HighstSpeed;
            $oldAnswer->ratio_backDelete = $oldAnswer['ratio_backDelete'].'&'.$BackDelete;
            $oldAnswer->ratio_maxKeyType = $oldAnswer['ratio_maxKeyType'].'&'.$HighstCountKey;
            $oldAnswer->ratio_averageKeyType = $oldAnswer['ratio_averageKeyType'].'&'.$AveragekeyType;
            $oldAnswer->ratio_maxInternalTime = $oldAnswer['ratio_maxInternalTime'].'&'.$HighIntervarlTime;
            $oldAnswer->ratio_countAllKey = $oldAnswer['ratio_countAllKey'].'&'.$countAllKey;
            $oldAnswer->isExam = $isExam;
            if(!($oldAnswer->update())) {
                echo Tool::jsLog('更新答案记录失败！');
                return false;
            } else{
                error_log($oldAnswer -> ratio_correct);
                $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
                error_log($oldAnswer['ratio_correct']);
                return true;
            }
        }
    }
  
//    public static function saveInstantAnswer($recordID,$squence, $ratio_speed, $ratio_correct, $ratio_maxSpeed, $ratio_backDelete, $ratio_maxKeyType, $ratio_averageKeyType, $ratio_internalTime, $ratio_maxInternalTime, $ratio_countAllKey){
//        $userID = Yii::app()->session['userid_now'];
//        $exerID = Yii::app()->session['exerID'];
//        $type = Yii::app()->session['exerType'];
//        $type = str_replace(["Exer"],"",$type);
//        $ratio = self::getRatio($exerID, $type, $answer);
//        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
//        if( $oldAnswer == null) {
//            $newAnswer = new AnswerRecord();
//            $newAnswer -> answerID = Tool::createID();
//            $newAnswer -> recordID = $recordID;
//            $newAnswer -> exerciseID = $exerID;
//            $newAnswer -> type = $type;
//            $newAnswer -> answer = $answer;
//            $newAnswer -> costTime = $seconds;
//            $newAnswer -> ratio_correct = $correct;
//            $newAnswer -> ratio_accomplish = $ratio['accomplish'];
//            $newAnswer -> createPerson = Yii::app()->session['userid_now'];
//            $newAnswer -> createTime = date("Y-m-d  H:i:s");
//            $newAnswer ->ratio_speed = $AverageSpeed;
//            $newAnswer ->ratio_maxSpeed = $HighstSpeed;
//            $newAnswer->ratio_backDelete = $BackDelete;
//            $newAnswer->ratio_maxKeyType = $HighstCountKey;
//            $newAnswer->ratio_averageKeyType = $AveragekeyType;
//            $newAnswer->ratio_maxInternalTime = $HighIntervarlTime;
//            $newAnswer->ratio_countAllKey = $countAllKey;
//            $newAnswer->isExam = $isExam;
//            if(!($newAnswer->insert())) {
//                echo Tool::jsLog('创建答案记录失败！');
//                return false;
//            } else 
//                return true;
//        }else {
//            $oldAnswer -> answer = $answer;
//            $oldAnswer -> costTime = $seconds;
//            $oldAnswer -> ratio_correct = $oldAnswer['ratio_correct'].'&'.$correct;
//            $oldAnswer -> ratio_accomplish = $ratio['accomplish'];
//            $oldAnswer -> createTime = date("Y-m-d  H:i:s");
//            $oldAnswer ->ratio_speed = $oldAnswer['ratio_speed'].'&'.$AverageSpeed;
//            $oldAnswer ->ratio_maxSpeed = $oldAnswer['ratio_maxSpeed'].'&'.$HighstSpeed;
//            $oldAnswer->ratio_backDelete = $oldAnswer['ratio_backDelete'].'&'.$BackDelete;
//            $oldAnswer->ratio_maxKeyType = $oldAnswer['ratio_maxKeyType'].'&'.$HighstCountKey;
//            $oldAnswer->ratio_averageKeyType = $oldAnswer['ratio_averageKeyType'].'&'.$AveragekeyType;
//            $oldAnswer->ratio_maxInternalTime = $oldAnswer['ratio_maxInternalTime'].'&'.$HighIntervarlTime;
//            $oldAnswer->ratio_countAllKey = $oldAnswer['ratio_countAllKey'].'&'.$countAllKey;
//            $newAnswer->isExam = $isExam;
//            if(!($oldAnswer->upDate())) {
//                echo Tool::jsLog('更新答案记录失败！');
//                return false;
//            } else
//                return true;
//        }
//    }

    public static function saveKnlgAnswer($recordID, $answer, $type, $exerciseID) {
        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerciseID);
        if( $oldAnswer == null) {
            $newAnswer = new AnswerRecord();
            $newAnswer -> answerID = Tool::createID();
            $newAnswer -> recordID = $recordID;
            $newAnswer -> exerciseID = $exerciseID;
            $newAnswer -> type = $type;
            $newAnswer -> answer = $answer;
            $newAnswer -> createPerson = Yii::app()->session['userid_now'];
            $newAnswer -> createTime = date("Y-m-d  H:i:s");
            if(!($newAnswer->insert())) {
                echo Tool::jsLog('创建答案记录失败！');
                return false;
            } else 
                return true;
        }else {
            $oldAnswer -> answer = $answer;
            $oldAnswer -> createTime = date("Y-m-d  H:i:s");
            if(!($oldAnswer->upDate())) {
                echo Tool::jsLog('更新答案记录失败！');
                return false;
            } else 
                return true;
        }
    }
    
    public function changeScore($answerID , $score){
        $answer = $this->find("answerID='$answerID'");
        if($answer != NULL)
        {
            $answer->score = $score;
            $answer->update();
        }
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'answer_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('answerID, recordID, exerciseID, type,costTime,radio_accomplish,ratio_correct,answer, createPerson, createTime,score,ratio_speed,ratio_maxSpeed,ratio_backDelete,ratio_maxKeyType,ratio_averageKeyType,ratio_maxInternalTime,ratio_countAllKey,squence,isExam', 'required'),
			array('answerID, recordID, exerciseID, createPerson', 'length', 'max'=>30),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('answerID, recordID, exerciseID, type, answer, createPerson, createTime', 'safe', 'on'=>'search'),
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
			'recordID' => 'Record',
			'exerciseID' => 'Exercise',
			'type' => 'Type',
			'answer' => 'Answer',
			'createPerson' => 'Create Person',
			'createTime' => 'Create Time',
                        'costTime'=>'Cost Time',
                        'radio_accomplish'=>'Radio Accomplish',
                        radio_accomplish,ratio_correct,answer, createPerson, createTime,score,ratio_speed,ratio_maxSpeed,ratio_backDelete,ratio_maxKeyType,ratio_averageKeyType,ratio_maxInternalTime,ratio_countAllKey,squence,isExam
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
		$criteria->compare('recordID',$this->recordID,true);
		$criteria->compare('exerciseID',$this->exerciseID,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('createPerson',$this->createPerson,true);
		$criteria->compare('createTime',$this->createTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getAndSaveScoreByRecordID($recordID)
        {
            $sql = "select sum(score) as totalScore from answer_record where recordID = '$recordID'";
            $result = Yii::app()->db->createCommand($sql)->query();
            foreach($result as $total){
                $score = $total['totalScore'];
            }
           $exam_record = ExamRecord::model()->find("recordID = '$recordID'");
           if($exam_record != NULL)
           {
           $exam_record->score = $score;
           $exam_record->update();
           }
           return $score;
        }
        
        public function deleteRecord($recordID, $exerciseID, $type,$createPerson){
            $condition = " where recordID=".$recordID." and exerciseID=".$exerciseID." and type='".$type."' and createPerson=".$createPerson;
            $sql = "delete from answer_record";
            $sql = $sql.$condition;
            Yii::app()->db->createCommand($sql)->query();   
        }
        
        
        public function updateAnswer($recordID, $answer, $seconds){
        $userID = Yii::app()->session['userid_now'];
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
        echo $answer;
        $oldAnswer->answer = $answer;
        $oldAnswer->costTime = $seconds;
        $oldAnswer->ratio_accomplish = 1;
        if(!($oldAnswer->upDate())) {
                echo Tool::jsLog('更新答案记录失败！');
                return false;
            } else
                return true;
        }
        

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AnswerRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        
        
        
        
        
}