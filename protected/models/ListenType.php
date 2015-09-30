<?php

/**
 * This is the model class for table "listen_type".
 *
 * The followings are the available columns in table 'listen_type':
 * @property string $exerciseID
 * @property string $filePath
 * @property string $fileName
 * @property string $title
 * @property string $createPerson
 * @property string $createTime
 */
class ListenType extends CActiveRecord
{
        public function getListenLst($type,$value){
        $order  =   " order by exerciseID ASC";
        if($type!="")
            if($type == "content")
            {
                $condition = " WHERE $type like '%$value%'";
            }else{
                $condition = " WHERE $type = '$value'";
            }          
        else
            $condition= "";
        $select     =   "SELECT * FROM listen_type";
        $sql        =   $select.$condition.$order;
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
    
    public function getListenLstPage($type,$value,$pagesize){
        $order  =   " order by exerciseID ASC";
        $user_id=Yii::app()->session['userid_now'];
        if($type!="")
            if($type == "content")
            {
                $condition = " WHERE $type like '%$value%' and createPerson='$user_id'";
            }else{
                $condition = " WHERE $type = '$value' and createPerson='$user_id'";
            }          
        else
            $condition= " where createPerson='$user_id'";
        $select     =   "SELECT * FROM listen_type";
        $sql        =   $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   $pagesize; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
            $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $listenLst  =   $result->query();
        
        return ['listenLst'=>$listenLst,'pages'=>$pages,];
    }
    
    public function getListenExer($suiteID){
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='listen')";
        $select = "select * from listen_type";
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=1; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $exercise=$result->query();
        
        return ['exercise'=>$exercise,'pages'=>$pages,];
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'listen_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exerciseID, filePath, fileName, title, createPerson, createTime', 'required'),
			array('exerciseID, filePath, fileName, title, createPerson', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, filePath, fileName, title, createPerson, createTime', 'safe', 'on'=>'search'),
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
			'filePath' => 'File Path',
			'fileName' => 'File Name',
			'title' => 'Title',
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

		$criteria->compare('exerciseID',$this->exerciseID,true);
		$criteria->compare('filePath',$this->filePath,true);
		$criteria->compare('fileName',$this->fileName,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('createPerson',$this->createPerson,true);
		$criteria->compare('createTime',$this->createTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
      public function insertListen($title,$content,$fileName,$filePath,$createPerson){
        $sql        =   "select max(exerciseID) as id from listen_type";
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
        $newListen    =   new ListenType();
        $newListen->exerciseID    =   $new_id;
        $newListen->title =   $title;
        $newListen->content       =   $content;
        $newListen->fileName   = $fileName;
        $newListen->filePath = $filePath;
        $newListen->createPerson  =   $createPerson;
        $newListen->createTime    =   date('y-m-d H:i:s',time());
        return $newListen->insert();
    }
    
    

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ListenType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
