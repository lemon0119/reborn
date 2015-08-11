<?php

/**
 * This is the model class for table "question".
 *
 * The followings are the available columns in table 'question':
 * @property integer $exerciseID
 * @property integer $courseID
 * @property string $requirements
 * @property string $answer
 * @property integer $createPerson
 * @property string $createTime
 * @property string $changeLog
 */
class Question extends CActiveRecord
{
    public function insertQue($requirements,$answer,$createPerson){
        $sql        =   "select max(exerciseID) as id from question";
        $max_id     =   Yii::app()->db->createCommand($sql)->query();
        $temp       =   $max_id->read();
        if(empty($temp))
        {
            $new_id =   1;
        }
        else
        {
            $new_id =   $temp['id'] + 1;
        }
        $newQue    =   new Question();
        $newQue->exerciseID    =   $new_id;
        $newQue->requirements  =   $requirements;
        $newQue->answer        =   $answer;
        $newQue->createPerson  =   $createPerson;
        $newQue->createTime    =   date('y-m-d H:i:s',time());
        return $newQue->insert();
    }
    
    public function getQuestionLst($type,$value){
        $order = " order by exerciseID ASC";
        if($type!="")
            $condition = " WHERE $type = '$value'";
        else
            $condition= "";
        $select = "SELECT * FROM question";
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $questionLst=$result->query();
        
        return ['questionLst'=>$questionLst,'pages'=>$pages,];
    }
    
    
//    //宋杰 2015-7-30 获取登录老师写的简单题
//        public function getTeaQuestionLst($type,$value){
//        $order = " order by exerciseID ASC";
//        $teacher_id = Yii::app()->session['userid_now'];
//        if($type!="")
//            $condition = " WHERE $type = '$value' and createPerson = '$teacher_id'";
//        else
//            $condition= " WHERE createPerson = '$teacher_id'";
//        $select = "SELECT * FROM question";
//        $sql = $select.$condition.$order;
//        $criteria=new CDbCriteria();
//        $result = Yii::app()->db->createCommand($sql)->query();
//        $pages=new CPagination($result->rowCount);
//        $pages->pageSize=10; 
//        $pages->applyLimit($criteria); 
//        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
//        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
//        $result->bindValue(':limit', $pages->pageSize); 
//        $questionLst=$result->query();
//        
//        return ['questionLst'=>$questionLst,'pages'=>$pages,];
//    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exerciseID, requirements, answer, createPerson, createTime, changeLog', 'required'),
			array('exerciseID, courseID, createPerson', 'numerical', 'integerOnly'=>true),
			array('requirements', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, courseID, requirements, answer, createPerson, createTime, changeLog', 'safe', 'on'=>'search'),
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
			'exerciseID' => 'Exercise',
			'courseID' => 'Course',
			'requirements' => 'Requirements',
			'answer' => 'Answer',
			'createPerson' => 'Create Person',
			'createTime' => 'Create Time',
			'changeLog' => 'Change Log',
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

		$criteria->compare('exerciseID',$this->exerciseID);
		$criteria->compare('courseID',$this->courseID);
		$criteria->compare('requirements',$this->requirements,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('createPerson',$this->createPerson);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('changeLog',$this->changeLog,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Question the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
