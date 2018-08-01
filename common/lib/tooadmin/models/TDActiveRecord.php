<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/2
 * Time: 11:21
 */
class TDActiveRecord extends CActiveRecord {

	/**
	 * Returns the database connection used by active record.
	 * By default, the "db" application component is used as the database connection.
	 * You may override this method if you want to use a different database connection.
	 * @return CDbConnection the database connection used by active record.
	 */
	public function getDbConnection()
	{
		return Yii::app()->getComponent('db');
		if(self::$db!==null)
			return self::$db;
		else
		{
			self::$db=Yii::app()->getComponent('db');
			if(self::$db instanceof CDbConnection)
				return self::$db;
			else
				throw new CDbException(Yii::t('yii','Active Record requires a "dbAdmin" CDbConnection application component.'));
		}
	}
}