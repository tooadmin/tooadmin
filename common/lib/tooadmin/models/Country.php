<?php

/**
 * This is the model class for table "bim_country".
 *
 * The followings are the available columns in table 'bim_country':
 * @property integer $id
 * @property string $cn_country
 * @property string $lan_country
 * @property string $flag
 * @property string $cn_name
 * @property string $lan_name
 * @property integer $order
 * @property integer $status
 */
class Country extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Country the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bim_country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cn_country', 'required'),
			array('order, status', 'numerical', 'integerOnly'=>true),
			array('cn_country, lan_country, flag, cn_name, lan_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('flag','file','allowEmpty'=>true,'types'=>'jpg,gif,png','maxSize'=>1024*500,'tooLarge'=>'最大不超过500KB'),
			array('cn_country, lan_country, flag, cn_name, lan_name, order, status', 'safe', 'on'=>'search'),
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
			'cn_country' => 'Cn Country',
			'lan_country' => 'Lan Country',
			'flag' => 'Flag',
			'cn_name' => 'Cn Name',
			'lan_name' => 'Lan Name',
			'order' => 'Order',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('cn_country',$this->cn_country,true);
		$criteria->compare('lan_country',$this->lan_country,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('cn_name',$this->cn_name,true);
		$criteria->compare('lan_name',$this->lan_name,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}