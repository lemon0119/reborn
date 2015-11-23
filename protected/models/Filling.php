<?php

/**
 * This is the model class for table "filling".
 *
 * The followings are the available columns in table 'filling':
 * @property integer $exerciseID
 * @property integer $courseID
 * @property string $requirements
 * @property string $answer
 * @property integer $createPerson
 * @property string $createTime
 * @property string $changeLog
 */
class Filling extends CActiveRecord
{
    
    public function insertFill($requirements,$answer,$createPerson){
        $sql="select max(exerciseID) as id from filling";
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
        $newFill = new Filling();
        $newFill->exerciseID = $new_id;
        $newFill->requirements = $requirements;
        $newFill->answer =$answer;
        $newFill->createPerson =$createPerson;
        $newFill->createTime = date('y-m-d H:i:s',time());
        return $newFill->insert();
    }
    
    public function getFillLst($type,$value){
        $order = " order by exerciseID DESC";
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
        $select = "SELECT * FROM filling";
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $fillLst=$result->query();
        
        return ['fillLst'=>$fillLst,'pages'=>$pages,];
    }
    
        public function getFillLstPage($type,$value,$pagesize){
        $order = " order by exerciseID ASC";
        $user_id=Yii::app()->session['userid_now'];
        if($type!="")
            if($type == "requirements")
            {
                $condition = " where $type like '%$value%' and createPerson='$user_id'";
            }else
            {
                $condition = " WHERE $type = '$value' and createPerson='$user_id'";
            }           
        else
            $condition= " where createPerson='$user_id'";
        $select = "SELECT * FROM filling";
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=$pagesize; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
            $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $fillLst=$result->query();
        
        return ['fillLst'=>$fillLst,'pages'=>$pages,];
    }
    
//    //宋杰 2015-7-30 获取老师编写的填空题
//        public function getTeaFillLst($type,$value){
//        $order = " order by exerciseID ASC";
//        $teacher_id = Yii::app()->session['userid_now'];
//        if($type!="")
//            $condition = " WHERE $type = '$value' and createPerson = '$teacher_id'";
//        else
//            $condition= " WHERE createPerson = '$teacher_id'";
//        $select = "SELECT * FROM filling";
//        $sql = $select.$condition.$order;
//        $criteria=new CDbCriteria();
//        $result = Yii::app()->db->createCommand($sql)->query();
//        $pages=new CPagination($result->rowCount);
//        $pages->pageSize=10; 
//        $pages->applyLimit($criteria); 
//        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
//        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
//        $result->bindValue(':limit', $pages->pageSize); 
//        $fillLst=$result->query();
//        
//        return ['fillLst'=>$fillLst,'pages'=>$pages,];
//    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'filling';
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
			array('answer', 'length', 'max'=>100),
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
	 * @return Filling the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
