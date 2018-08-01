<?php

class TDTooActiveRecord extends CActiveRecord {

	public function getDbConnection()
	{
		return Yii::app()->getComponent('too');
		if(self::$db!==null)
			return self::$db;
		else
		{
			self::$db=Yii::app()->getComponent('too');
			if(self::$db instanceof CDbConnection)
				return self::$db;
			else
				throw new CDbException(Yii::t('yii','Active Record requires a "dbAdmin" CDbConnection application component.'));
		}
	}
}