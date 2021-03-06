<?php

/**
 * This is the model class for table "exam_exercise".
 *
 * The followings are the available columns in table 'exam_exercise':
 * @property integer $suiteID
 * @property integer $exerciseID
 * @property string $type
 * @property integer $score
 */
class ExamExercise extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_exercise';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('suiteID, exerciseID, type, score', 'required'),
			array('suiteID, exerciseID, score', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('suiteID, exerciseID, type, score', 'safe', 'on'=>'search'),
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
			'score' => 'Score',
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
		$criteria->compare('score',$this->score);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
       public function insertExam($type,$exerciseID,$examID) {
            $newSuiteExercise = new ExamExercise();
            $newSuiteExercise->type  = $type;
            $newSuiteExercise->exerciseID = $exerciseID;
            $newSuiteExercise->examID = $examID;
            if($this->findAll(array(
                'condition' => 'type =:type and exerciseID=:exerciseID and examID=:examID',
                'params' =>array(':type' => $type,':exerciseID'=>$exerciseID,':examID'=>$examID)
            ))!= NULL) 
            {
                return "HAVEN";
            }     
            else 
            {
                return $newSuiteExercise->insert();
            }
        }
    /*
     * 
     * LC add
     */
    public function getExamExerAll($suiteID){
        $examExer = Array();
        foreach(Tool::$EXER_TYPE as $type){
            $thisExer = $this->getExamExerByType($suiteID, $type);
            if($thisExer != NULL){
                $examExer[$type] = $thisExer;
            }
        }
        return $examExer;
    }
//宋杰 2015-7-31 根据题目类型从相应题库中获取题目       
    public function getExamExerByType( $suiteID, $type)
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
        
//宋杰 2015-7-31 获取选择题   
       public function getchoice($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='choice')";
            $select = "select * from choice";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        //宋杰 2015-7-31 获取填空题 
    	public function getFilling($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='filling')";
            $select = "select * from filling";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        //宋杰 2015-7-31 获取简答题 
    	public function getQuestion($suiteID)
	{
            $order = " order by exerciseID ASC";
            $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='question')";
            $select = "select * from question";
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        //宋杰 2015-7-31 获取键打练习 
        public function getKeyExer($suiteID)
	{
            //edit by LC
            //$order = " order by exerciseID ASC";
            //$condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='key')";
            //$select = "select * from key_type";
            $select = 'select key_type.exerciseID, '
                    . 'key_type.content, '
                    . 'key_type.title, '
                    .'key_type.repeatNum,'
                    . 'exam_exercise.score, '
                    . 'key_type.category,'
                    . 'key_type.speed,'
                    . 'exam_exercise.time from key_type, exam_exercise ';
            $condition = "where exam_exercise.exerciseID=key_type.exerciseID "
                    . "and exam_exercise.examID='$suiteID' "
                    . "and exam_exercise.type='key'";
            $order = " order by key_type.exerciseID ASC";
            //end
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        //宋杰 2015-7-31 获取听打练习 
        public function getListenExer($suiteID)
	{
            //edit by lc
            //$order = " order by exerciseID ASC";
            //$condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='listen')";
            //$select = "select * from listen_type";
            $select = 'select listen_type.exerciseID, '
                    . 'listen_type.content, '
                    . 'listen_type.filePath, '
                    . 'listen_type.fileName, '
                    . 'listen_type.title, '
                    . 'exam_exercise.score, '
                    . 'exam_exercise.time from listen_type, exam_exercise ';
            $condition = "where exam_exercise.exerciseID=listen_type.exerciseID "
                    . "and exam_exercise.examID='$suiteID' "
                    . "and exam_exercise.type='listen'";
            $order = " order by listen_type.exerciseID ASC";
            //end
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        //宋杰 2015-7-31 获取看打练习 
        public function getLookExer($suiteID)
	{
            //edit by LC
            //$order = " order by exerciseID ASC";
            //$condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$suiteID' and type='look')";
            //$select = "select * from look_type";
            $select = 'select look_type.exerciseID, '
                    . 'look_type.content, '
                    . 'look_type.title, '
                    . 'exam_exercise.score, '
                    . 'exam_exercise.time from look_type, exam_exercise ';
            $condition = "where exam_exercise.exerciseID=look_type.exerciseID "
                    . "and exam_exercise.examID='$suiteID' "
                    . "and exam_exercise.type='look'";
            $order = " order by look_type.exerciseID ASC";
            //end
            $sql = $select.$condition.$order;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            return $result;
	}
        
        public function updateScore($exerciseID,$type,$examID,$score)
        {
            $exercise = $this->find("exerciseID = '$exerciseID' and type = '$type' and examID = '$examID'");
            $exercise->score = $score;
            $exercise->update();
        }
        
        public function updateTime($exerciseID,$type,$examID,$time)
        {
            $exercise = $this->find("exerciseID = '$exerciseID' and type = '$type' and examID = '$examID'");
            $exercise->time = $time*60;
            $exercise->update();
        }
        
        public function getTotalScore($examID)
        {
           
            $sql = "select sum(score) as totalScore from exam_exercise where examID = '$examID'";
            $result = Yii::app()->db->createCommand($sql)->query();
            foreach ($result as $total)
            {
                $totalScore = $total['totalScore'];
            }
            return $totalScore;
        }
        
        public function getCountExercise($examID){
            $exercise = $this->findAll('examID ='.$examID);
            $num = count($exercise);
            return $num;
        }

        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamExercise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
