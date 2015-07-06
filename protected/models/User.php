<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $username
 * @property integer $sex
 * @property integer $age
 * @property string $userid
 * @property string $id_card
 * @property string $password
 * @property integer $role
 * @property integer $show
 * @property integer $xueli
 * @property string $tel
 * @property string $email
 * @property string $qq
 * @property string $address
 * @property integer $mianmao
 * @property string $img_small
 * @property string $img_big
 * @property string $xuehao
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, sex, userid, id_card, password, role, xueli, tel, email, qq, address, mianmao, img_small, img_big, xuehao', 'required'),
			array('sex, age, role, show, xueli, mianmao', 'numerical', 'integerOnly'=>true),
			array('username, userid', 'length', 'max'=>30),
			array('id_card', 'length', 'max'=>40),
			array('password', 'length', 'max'=>70),
			array('tel', 'length', 'max'=>11),
			array('email', 'length', 'max'=>50),
			array('qq, xuehao', 'length', 'max'=>20),
			array('address', 'length', 'max'=>200),
			array('img_small, img_big', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username, sex, age, userid, id_card, password, role, show, xueli, tel, email, qq, address, mianmao, img_small, img_big, xuehao', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'sex' => 'Sex',
			'age' => 'Age',
			'userid' => 'Userid',
			'id_card' => 'Id Card',
			'password' => 'Password',
			'role' => 'Role',
			'show' => 'Show',
			'xueli' => 'Xueli',
			'tel' => 'Tel',
			'email' => 'Email',
			'qq' => 'Qq',
			'address' => 'Address',
			'mianmao' => 'Mianmao',
			'img_small' => 'Img Small',
			'img_big' => 'Img Big',
			'xuehao' => 'Xuehao',
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

		$criteria->compare('username',$this->username,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('age',$this->age);
		$criteria->compare('userid',$this->userid,true);
		$criteria->compare('id_card',$this->id_card,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('show',$this->show);
		$criteria->compare('xueli',$this->xueli);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mianmao',$this->mianmao);
		$criteria->compare('img_small',$this->img_small,true);
		$criteria->compare('img_big',$this->img_big,true);
		$criteria->compare('xuehao',$this->xuehao,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
