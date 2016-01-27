<?php

/**
 * This is the model class for table "class_exercise".
 *
 * The followings are the available columns in table 'class_exercise':
 * @property integer $exerciseID
 * @property integer $classID
 * @property integer $lessonID
 * @property string $title
 * @property string $content
 * @property string $type
 * @property string $file_path
 * @property string $file_name
 * @property string $create_time
 * @property string $create_person
 * @property integer $is_open
 * @property integer $now_open
 */
class ClassExercise extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_exercise';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classID, lessonID, title, content, type, file_path, file_name, create_time, create_person, is_open, now_open', 'required'),
			array('classID, lessonID, is_open, now_open', 'numerical', 'integerOnly'=>true),
			array('title, file_path, file_name, create_person', 'length', 'max'=>30),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, classID, lessonID, title, content, type, file_path, file_name, create_time, create_person, is_open, now_open', 'safe', 'on'=>'search'),
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
			'classID' => 'Class',
			'lessonID' => 'Lesson',
			'title' => 'Title',
			'content' => 'Content',
			'type' => 'Type',
			'file_path' => 'File Path',
			'file_name' => 'File Name',
			'create_time' => 'Create Time',
			'create_person' => 'Create Person',
			'is_open' => 'Is Open',
                        'now_open' => 'Now Open',
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
		$criteria->compare('classID',$this->classID);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_person',$this->create_person,true);
		$criteria->compare('is_open',$this->is_open);
                $criteria->compare('now_open',$this->now_open);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function insertKey($classID,$lessonID,$title,$content,$createPerson,$category,$speed,$repeatNum,$libstr){
        $newKey    =   new ClassExercise();
        $newKey->title =   $title;
        $newKey->content       =   $content;
        $newKey->classID       =   $classID;
        $newKey->lessonID       =   $lessonID;
        $newKey->create_person  =   $createPerson;
        $newKey->type = $category;
        $newKey->repeatNum = $repeatNum;
        $newKey->chosen_lib = $libstr;
        if($category == "speed")
        {
            $newKey->speed = $speed;
        }
        $newKey->create_time    =   date('y-m-d H:i:s',time());
        return $newKey->insert();
    }
        
        public function insertClassExercise($classID,$lessonID,$title,$content,$type,$createPerson){
        $newClassExercise    =   new ClassExercise();
        $newClassExercise->classID    =   $classID;
        $newClassExercise->lessonID    =   $lessonID;
        $newClassExercise->title =   $title;
        $newClassExercise->content       =   $content;
        $newClassExercise->type       =   $type;
        $newClassExercise->create_person  =   $createPerson;
        $newClassExercise->create_time    =   date('y-m-d H:i:s',time());
        return $newClassExercise->insert();
    }
    
    public function insertListen($classID,$lessonID,$title,$content,$fileName,$filePath,$type,$createPerson){
        $newClassExercise    =   new ClassExercise();
        $newClassExercise->classID    =   $classID;
        $newClassExercise->lessonID    =   $lessonID;
        $newClassExercise->title =   $title;
        $newClassExercise->content       =   $content;
        $newClassExercise->file_name   = $fileName;
        $newClassExercise->file_path = $filePath;
        $newClassExercise->type       =   $type;
        $newClassExercise->create_person  =   $createPerson;
        $newClassExercise->create_time    =   date('y-m-d H:i:s',time());
        return $newClassExercise->insert();
    }
    
    public function getLookLst($type,$value,$lessonID){
        $order  =   " order by exerciseID DESC";
        if($type!="")
            if($type == "content")
            {
                $condition = " WHERE $type like '%$value%'";
            }  else {
                $condition = " WHERE $type = '$value'";
            }           
        else
            $condition= "";
        $select     =   "SELECT * FROM class_exercise";
        $condition2 = "AND lessonID = $lessonID";
        $sql        =   $select.$condition.$condition2.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   10; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $lookLst  =   $result->query();
        
        return ['lookLst'=>$lookLst,'pages'=>$pages,];
    }
    
    public function getKeyLst($lessonID){
        $order  =   " order by exerciseID DESC";
        $condition = " WHERE (type = 'speed' OR type = 'correct' OR type = 'free')";
        $select     =   "SELECT * FROM class_exercise";
        $condition2 = "AND lessonID = $lessonID";
        $sql        =   $select.$condition.$condition2.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   10; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $lookLst  =   $result->query();
        
        return ['keyLst'=>$lookLst,'pages'=>$pages,];
    }
    public function getListenLst($type,$value,$lessonID){
        $order  =   " order by exerciseID DESC";
        if($type!="")
            if($type == "content")
            {
                $condition = " WHERE $type like '%$value%'";
            }else{
                $condition = " WHERE $type = '$value'";
            }          
        else
            $condition= "";
        $select     =   "SELECT * FROM class_exercise";
        $condition2 = "AND lessonID = $lessonID";
        $sql        =   $select.$condition.$condition2.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   10; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $listenLst  =   $result->query();
        
        return ['listenLst'=>$listenLst,'pages'=>$pages,];
    }
    
    
    public function getExerciseByType($exerciseID,$type){
        $sql = "SELECT * FROM class_exercise WHERE exerciseID = '$exerciseID' AND type = '$type'";
        return Yii::app()->db->createCommand($sql)->query();
    }
    
    public function updateLook($exerciseID,$title,$content){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->find("exerciseID = '$exerciseID'");
        $classExercise->title = $title;
        $classExercise->content = $content;
        $classExercise->update();
    }
    
    public function deleteExercise($exerciseID){
        $classExercise = new ClassExercise();
        $FileName = $classExercise->find("exerciseID = '$exerciseID'")['file_name'];
        Resourse::model()->delName($FileName);
        $classExercise->deleteAll("exerciseID = '$exerciseID'");
    }
    
    public function openExercise($exerciseID){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->find("exerciseID = '$exerciseID'");
        $classExercise->is_open = 1;
        $classExercise->update();
    }
    public function openExerciseNow($exerciseID){
//        $classExercise = new ClassExercise();
//        $classExercise = $classExercise->findAll("now_open = 1");
//        if($classExercise!==""){
//            foreach ($classExercise as $v){
//                $v->now_open = 0;
//                $v->update();
//            }
//        }
            $classExercise = new ClassExercise();
            $classExercise = $classExercise->find("exerciseID = '$exerciseID'");
            $classExercise->is_open = 1;
            $classExercise->now_open = 1;
            $classExercise->update();
    }
    
    public function closeAllOpenExerciseNow(){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->findAll("now_open = 1");
        if($classExercise!==""){
            foreach ($classExercise as $v){
                $v->now_open = 0;
                $v->update();
            }
        }
    }


    public function isHasClassExerciseOpen($classID,$lessonID){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->find("classID = '$classID' AND lessonID = '$lessonID' AND now_open = 1");
        return $classExercise['type'];
    }
    
    public function getNowOpenExercise(){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->find("now_open = 1");
        return $classExercise;
    }
    
    public function getAllNowOpenExercise(){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->findAll("now_open = 1");
        return $classExercise;
    }
    
    public function getByExerciseID($exerciseID){
        $classExercise = new ClassExercise();
        $classExercise = $classExercise->find("exerciseID = '$exerciseID'");
        return $classExercise;
    }
    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassExercise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
