<?php

/**
 * This is the model class for table "teacher".
 *
 * The followings are the available columns in table 'teacher':
 * @property string $userID
 * @property string $userName
 * @property string $password
 */
class Teacher extends CActiveRecord
{
        public function getTeaLst($type,$value){
        $order = " order by userID ASC";
        if($type!=""&&$type!="is_delete")
            $condition = " WHERE $type = '$value' AND is_delete = 0";
        else if($type=="is_delete")
            $condition= " WHERE is_delete = 1";
        else
            $condition= " WHERE is_delete = 0";
        $select = "SELECT * FROM teacher"; 
        $sql = $select.$condition.$order;
        $criteria=new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages=new CPagination($result->rowCount);
        $pages->pageSize=10; 
        $pages->applyLimit($criteria); 
        $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit"); 
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize); 
        $result->bindValue(':limit', $pages->pageSize); 
        $stuLst=$result->query();
        
        return ['teaLst'=>$stuLst,'pages'=>$pages,];
    }
    
    function getClassNow(){
        $teacherID = Yii::app()->session['userid_now'];
        $order = " order by classID ASC ";
        $condition = " where teacherID = $teacherID ";
        $select=" SELECT classID FROM teacher_class ";
        $classid = $select.$condition;
        $sql = "select * from tb_class where classID in (".$classid.")".$order;
        //echo $sql;
        $res = Yii::app()->db->createCommand($sql)->query();
        return $res;
    }

    
    
	/**
	 * @return string the associated database table name
	 */
    
	public function tableName()
	{
		return 'teacher';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID, userName, password', 'required'),
			array('userID, userName, password', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('userID, userName, password', 'safe', 'on'=>'search'),
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
			'userID' => 'User',
			'userName' => 'User Name',
			'password' => 'Password',
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

		$criteria->compare('userID',$this->userID,true);
		$criteria->compare('userName',$this->userName,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Teacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
