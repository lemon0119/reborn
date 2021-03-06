<?php

/**
 * This is the model class for table "look_type".
 *
 * The followings are the available columns in table 'look_type':
 * @property string $exerciseID
 * @property string $content
 * @property string $createPerson
 * @property string $createTime
 */
class LookType extends CActiveRecord
{
    
    public function getLookExer($suiteID) {
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from suite_exercise where suiteID='$suiteID' and type='look')";
        $select = "select * from look_type";
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=1; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $exercise=$result->query();
        
        return ['exercise'=>$exercise,'pages'=>$pages,];
    }
    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'look_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exerciseID, content, createPerson, createTime', 'required'),
			array('exerciseID, createPerson', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exerciseID, content, createPerson, createTime', 'safe', 'on'=>'search'),
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
			'content' => 'Content',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('createPerson',$this->createPerson,true);
		$criteria->compare('createTime',$this->createTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
     public function getLookLst($type,$value){
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
        $select     =   "SELECT * FROM look_type";
        $sql        =   $select.$condition.$order;
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
    
         public function getLookLstPage($type,$value,$pagesize){
        $order  =   " order by exerciseID DESC";
        $user_id=Yii::app()->session['userid_now'];
        if($type!="")
            if($type == "content")
            {
                $condition = " WHERE $type like '%$value%' and createPerson='$user_id'";
            }  else {
                $condition = " WHERE $type = '$value' and createPerson='$user_id'";
            }           
        else
            $condition= " where createPerson='$user_id'";
        $select     =   "SELECT * FROM look_type";
        $sql        =   $select.$condition.$order;
        $criteria   =   new CDbCriteria();
        $result     =   Yii::app()->db->createCommand($sql)->query();
        $pages      =   new CPagination($result->rowCount);
        $pages->pageSize    =   $pagesize; 
        $pages->applyLimit($criteria); 
        $result     =   Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
            $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $lookLst  =   $result->query();
        
        return ['lookLst'=>$lookLst,'pages'=>$pages,];
    }
    
    
        public function insertLook($title,$content,$createPerson){
        $sql        =   "select max(exerciseID) as id from look_type";
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
        $newLook    =   new LookType();
        $newLook->exerciseID    =   $new_id;
        $newLook->title =   $title;
        $newLook->content       =   $content;
        $newLook->createPerson  =   $createPerson;
        $newLook->createTime    =   date('y-m-d H:i:s',time());
        return $newLook->insert();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LookType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
