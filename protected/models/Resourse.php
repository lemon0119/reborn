<?php

/**
 * This is the model class for table "resourse".
 *
 * The followings are the available columns in table 'resourse':
 * @property string $resourseID
 * @property integer $lessonID
 * @property string $type
 * @property string $name
 * @property string $path
 */
class Resourse extends CActiveRecord
{
    
        /**
         * @author Wang fei <1018484601@qq.com>
         * @purpose 上传资源时，会将资源重命名为一个随机的名字，在本表中，保留该
         * 随机字符串和该资源原名程的映射。
        */

    
    
        /**
         * @author Wang fei <1018484601@qq.com>
         * @param $newName 该资源的新的随机的名称
         *         $oldName 该资源的原名称
         * @return 返回插入是否成功
         */
        public function insertRela($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="ppt";
            return $resource->insert();
        }
        public function insertRelaVideo($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="video";
            return $resource->insert();
        }
         public function insertRelaTxt($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="txt";
            return $resource->insert();
        }
        
             
        public function insertRelaPicture($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="picture";
            return $resource->insert();
        }
        
        public function insertRelaVoice($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="voice";
            return $resource->insert();
        }
        
        
        
        public function insertRelaRadio($newName,$oldName)
        {
            $resource             = new Resourse();
            $resource->resourseID = $newName;
            $resource->name       = $oldName;
            $resource->type="radio";
            return $resource->insert();
        }

        /**
         * @author Wang fei <1018484601@qq.com>
         * @param $name  该资源存在本地的名称
         * @return  该资源上传时候名称
         */
        public function  getOriName($name)
        {
            $resource      =   new Resourse();
            $thisRes       =   $resource->find("resourseID = '$name'");
            return $thisRes['name'];
        }

        /**
         * @author Wang fei <1018484601@qq.com>
         * @param $name  该资源存在本地的名称
         * @return  该资源上传时候名称
         */
        public function  delName($name)
        {
            $resource      =   new Resourse();
            $thisRes       =   $resource->deleteAll("resourseID = '$name'");
        }
        
        /**
         * @author Wang fei <1018484601@qq.com>
         * @param $oldID   被删除的资源的名称
         *         $newName 变化的资源的新的随机的名称
         *         $oldName 新增资源的原名称
         * @return 返回修改是否成功
         */
        public function replaceRela($oldID,$newName,$oldName)
        {
            $resource             = new Resourse();
            $thisRes              = $resource->find("resourseID = '$oldID'");
            if(empty($thisRes)){
                $resource->resourseID = $newName;
                $resource->name       = $oldName;
            }else{
                $thisRes->resourseID  = $newName;
                $thisRes->name        = $oldName;
                return $thisRes->update();
            }
            
        }
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'resourse';
	}
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('resourseID, lessonID, type, name, path', 'required'),
			array('lessonID', 'numerical', 'integerOnly'=>true),
			array('resourseID', 'length', 'max'=>30),
			array('type', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('resourseID, lessonID, type, name, path', 'safe', 'on'=>'search'),
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
			'resourseID' => 'Resourse',
			'lessonID' => 'Lesson',
			'type' => 'Type',
			'name' => 'Name',
			'path' => 'Path',
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

		$criteria->compare('resourseID',$this->resourseID,true);
		$criteria->compare('lessonID',$this->lessonID);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Resourse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
