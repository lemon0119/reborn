<?php

/**
 * This is the model class for table "two_words_lib_personal".
 *
 * The followings are the available columns in table 'two_words_lib_personal':
 * @property integer $id
 * @property string $spell
 * @property string $yaweiCode
 * @property string $words
 * @property string $list
 * @property string $name
 * @property string $createPerson
 */
class TwoWordsLibPersonal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'two_words_lib_personal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spell, yaweiCode, words, list, name, createPerson', 'required'),
			array('spell, yaweiCode, createPerson', 'length', 'max'=>30),
			array('words, list, name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, spell, yaweiCode, words, list, name, createPerson', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'spell' => 'Spell',
			'yaweiCode' => 'Yawei Code',
			'words' => 'Words',
			'list' => 'List',
			'name' => 'Name',
			'createPerson' => 'Create Person',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('spell',$this->spell,true);
		$criteria->compare('yaweiCode',$this->yaweiCode,true);
		$criteria->compare('words',$this->words,true);
		$criteria->compare('list',$this->list,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('createPerson',$this->createPerson,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

         public static function insertPersonalLib($spell,$yaweiCode,$words,$name,$createPerson){
            $TwoWordsLibPersonal = new TwoWordsLibPersonal();
            $TwoWordsLibPersonal->list = '个人词库';
            $TwoWordsLibPersonal->spell =  $spell;
            $TwoWordsLibPersonal->yaweiCode =  $yaweiCode;
            $TwoWordsLibPersonal->words =  $words;
            $TwoWordsLibPersonal->name =  $name;
            $TwoWordsLibPersonal->createPerson =  $createPerson;
            $TwoWordsLibPersonal->insert();
        }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TwoWordsLibPersonal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
