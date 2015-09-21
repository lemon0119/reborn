<?php

/**
 * This is the model class for table "choice".
 *
 * The followings are the available columns in table 'choice':
 * @property integer $exerciseID
 * @property string $type
 * @property integer $courseID
 * @property string $requirements
 * @property string $options
 * @property string $answer
 * @property integer $createPerson
 * @property string $createTime
 * @property string $changeLog
 */
class Choice extends CActiveRecord
{
    
    public function insertChoice($requirements,$options,$answer,$createPerson){
        //得到当前最大的exerciseID
        $sql="select max(exerciseID) as id from choice";
        $max_id = Yii::app()->db->createCommand($sql)->query();
        $temp=$max_id->read();
        if(empty($temp))
        {
            $new_id=1;
        }
        else
        {
            $new_id = $temp['id'] + 1;
        }
        $newCh = new Choice();
        $newCh->exerciseID = $new_id;
        $newCh->type = "danxuan";
        $newCh->requirements = $requirements;
        $newCh->options = $options;
        $newCh->answer =$answer;
        $newCh->createPerson =$createPerson;
        $newCh->createTime = date('y-m-d H:i:s',time());
        return $newCh->insert();
    }
    
    public function getChoiceLst($type,$value){
        $order  =   " order by exerciseID ASC";
        if($type!="")
            if($type == "requirements")
            {
            $condition = " where $type like '%$value%'";
            }else
            {
            $condition = " WHERE $type = '$value'";
            }
        else
            $condition= "";
        $select     =   "SELECT * FROM choice";
        $sql        =   $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   10; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $choiceLst  =   $result->query();
        
        return ['choiceLst'=>$choiceLst,'pages'=>$pages,];
    }
    
    
    //可以设置pagesize大小
      public function getChoiceLstPage($type,$value,$pagesize){
        $order  =   " order by exerciseID ASC";
        if($type!="")
            if($type == "requirements")
            {
            $condition = " where $type like '%$value%'";
            }else
            {
            $condition = " WHERE $type = '$value'";
            }
        else
            $condition= "";
        $select     =   "SELECT * FROM choice";
        $sql        =   $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   $pagesize; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
            $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $choiceLst  =   $result->query();
        
        return ['choiceLst'=>$choiceLst,'pages'=>$pages,];
    }
    
//    //宋杰 2015-7-30 得到登录老师的选择题列表
//        public function getTeaChoiceLst($type,$value){
//        $order = " order by exerciseID ASC";
//        $teacher_id = Yii::app()->session['userid_now'];
//        if($type!="")
//            $condition = " WHERE $type = '$value' and createPerson = '$teacher_id'";
//        else
//            $condition= "where createPerson = '$teacher_id'";
//        $select = "SELECT * FROM choice";
//        $sql = $select.$condition.$order;
//        $criteria=new CDbCriteria();
//        $result = Yii::app()->db->createCommand($sql)->query();
//        $pages=new CPagination($result->rowCount);
//        $pages->pageSize=10; 
//        $pages->applyLimit($criteria); 
//        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
//        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
//        $result->bindValue(':limit', $pages->pageSize); 
//        $choiceLst=$result->query();
//        
//        return ['choiceLst'=>$choiceLst,'pages'=>$pages,];
//    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'choice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exerciseID, requirements, options, answer, createPerson, createTime, changeLog', 'required'),
			array('exerciseID, courseID, createPerson', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>11),
			array('requirements', 'length', 'max'=>200),
			array('answer', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, type, courseID, requirements, options, answer, createPerson, createTime, changeLog', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'courseID' => 'Course',
			'requirements' => 'Requirements',
			'options' => 'Options',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('courseID',$this->courseID);
		$criteria->compare('requirements',$this->requirements,true);
		$criteria->compare('options',$this->options,true);
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
	 * @return Choice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
