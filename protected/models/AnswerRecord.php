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
        $filling = Suite::model()->getFilling(Yii::app()->session['suiteID']);
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
        $choice = Suite::model()->getchoice(Yii::app()->session['suiteID']);
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
        $quest = Suite::model()->getQuestion(Yii::app()->session['suiteID']);
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
    
    public static function saveAnswer($recordID, $answer, $seconds) {
        $userID = Yii::app()->session['userid_now'];
        $exerID = Yii::app()->session['exerID'];
        $type = Yii::app()->session['exerType'];
        $type = str_replace(["Exer"],"",$type);
        $ratio = self::getRatio($exerID, $type, $answer);
        $oldAnswer = AnswerRecord::getAnswer($recordID, $type, $exerID);
        if( $oldAnswer == null) {
            $newAnswer = new AnswerRecord();
            $newAnswer -> answerID = Tool::createID();
            $newAnswer -> recordID = $recordID;
            $newAnswer -> exerciseID = $exerID;
            $newAnswer -> type = $type;
            $newAnswer -> answer = $answer;
            $newAnswer -> costTime = $seconds;
            $newAnswer -> ratio_correct = $ratio['correct'];
            $newAnswer -> ratio_accomplish = $ratio['accomplish'];
            $newAnswer -> createPerson = Yii::app()->session['userid_now'];
            $newAnswer -> createTime = date("Y-m-d  H:i:s");
            if(!($newAnswer->insert())) {
                echo Tool::jsLog('创建答案记录失败！');
                return false;
            } else 
                return true;
        }else {
            $oldAnswer -> answer = $answer;
            $oldAnswer -> costTime = $seconds;
            $oldAnswer -> ratio_correct = $ratio['correct'];
            $oldAnswer -> ratio_accomplish = $ratio['accomplish'];
            $oldAnswer -> createTime = date("Y-m-d  H:i:s");
            if(!($oldAnswer->upDate())) {
                echo Tool::jsLog('更新答案记录失败！');
                return false;
            } else
                return true;
        }
    }
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
			array('answerID, recordID, exerciseID, type, answer, createPerson, createTime', 'required'),
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
