<?php

class TDModelDAO {

	public static function getModel($tableName, $pkId = null, $defaultNew = false) { $model = TDDataFiles::getModelClass($tableName);
	if (!empty($pkId)) { $model = $model->findByPk($pkId); if (!empty($model)) { $model->isNewRecord = false; } else if ($defaultNew) { $model = TDDataFiles::getModelClass($tableName); } } return $model; }
	public static function addRow($tableName, $data) { $row = self::getModel($tableName); foreach ($data as $key => $value) { $row->$key = $value; } if ($row->save()) { return $row->getPrimaryKey(); } else { return 0; } }
	public static function addAll($tableName,$datas){foreach($datas as $data){$row=self::getModel($tableName);foreach($data as $key => $value){$row->$key=$value;}$res=$row->save(); if(!$res){return false;} }return true; }
	public static function saveRowByData($tableName, $pkId, $data) { $row = self::getModel($tableName, $pkId); foreach ($data as $key => $value) { $row->$key = $value; } if ($row->save()) { return $row->getPrimaryKey(); } else { return 0; } }
	public static function saveRowByModel($model, $data) { foreach ($data as $key => $value) { $model->$key = $value; } if ($model->save()) { return $model->getPrimaryKey(); } else { return 0; } }
	public static function getDB($tableName="") { if(!empty($tableName) && strpos($tableName,"too_") === 0) { return Yii::app()->too; }	return Yii::app()->db; } 
	public static function getTooDB() { return Yii::app()->too; }
	public static function getCommDB() { return Yii::app()->db; }
	public static function getCommonDBName() { $str = TDModelDAO::getCommDB()->connectionString; return trim(explode('dbname=',$str)[1]); }

	public static function getDBBySQL($sql) {
		$tbName = "";
		if(strpos($sql,"from") !== false) {
			$tbName = explode(" ",trim(explode("from ",$sql)[1]))[0];
		} else if(strpos($sql,"FROM") !== false) {
			$tbName = explode(" ",trim(explode("FROM ",$sql)[1]))[0];
		}
		return self::getDB($tbName);
	}
	public static function getFieldById($tableName, $pkId, $field, $default = null) { 
		return self::getDB($tableName)->createCommand("select `".$field."` from `".$tableName.
		"` where ".TDTableColumn::getPrimaryKeyColumnName($tableName)."=".$pkId)->queryScalar(); $res = !empty($res) ? $res : $default;
	}
	public static function queryRowByPk($tableName,$pkId,$select="*"){ 
		return self::getDB($tableName)->createCommand("select ".$select." from `".$tableName.
		"` where ".TDTableColumn::getPrimaryKeyColumnName($tableName)."=".$pkId)->queryRow();
		/*
		$needCache = self::isNeedCache($tableName);
		if($needCache) {
			$cacheKey = 'queryRowByPk'.$tableName.$pkId.$select; 
			$cacheValue = Yii::app()->cache->get($cacheKey);
			if($cacheValue) { return $cacheValue; }
		}
		$res = ->createCommand("select ".$select." from `".$tableName."` where id=".$pkId)->queryRow();
		if($needCache) {
			Yii::app()->cache->set($cacheKey,$res); 
		}	
		return $res;
		*/
	}
	public static function queryRowByCondtion($tableName,$condition,$select="*"){ 
		return self::getDB($tableName)->createCommand("select ".$select." from `".$tableName."` where ".$condition." limit 1")->queryRow();
	}
	public static function queryScalarByPk($tableName,$pkId,$select){ 
		return self::getDB($tableName)->createCommand("select ".$select." from `".$tableName.
		"` where ".TDTableColumn::getPrimaryKeyColumnName($tableName)."=".$pkId)->queryScalar();
	}
	public static function queryScalar($tableName,$condition,$select) { 
		return self::getDB($tableName)->createCommand("select ".$select." from `".$tableName."` where ".$condition)->queryScalar();
	}
	public static function queryAll($tableName,$condition="",$select="*") { 
		return self::getDB($tableName)->createCommand("select ".$select." from `".$tableName."` ".(!empty($condition) ? " where ".$condition : ""))->queryAll();
	}
	public static function deleteByPk($tableName,$pkId) {
		return self::getDB($tableName)->createCommand("delete from `".$tableName.
		"` where `".TDTableColumn::getPrimaryKeyColumnName($tableName)."`=".$pkId."")->execute();
	}
	//TDModelDAO::deleteByCondition($tableName, $condition);
	public static function deleteByCondition($tableName,$condition) {
		return self::getDB($tableName)->createCommand("delete from `".$tableName."` where ".$condition."")->execute();
	}
	//TDModelDAO::updateRowByPk($tableName, $pkId, $data);
	public static function updateRowByPk($tableName,$pkId,$data) {
		$setStr = ""; foreach($data as $key => $value) { $setStr .= empty($setStr) ? "" : ","; $setStr .= "`".$key."`='".$value."'"; } 
		if(!empty($setStr)) { return self::getDB($tableName)->createCommand("update `".$tableName."` set ".$setStr
		." where `".TDTableColumn::getPrimaryKeyColumnName($tableName)."`=".$pkId."")->execute(); } 
		return false;
	}
	//TDModelDAO::updateRowByCondition($tableName, $condition, $data);
	public static function updateRowByCondition($tableName,$condition,$data) {
		$setStr = ""; foreach($data as $key => $value) { $setStr .= empty($setStr) ? "" : ","; $setStr .= "`".$key."`='".$value."'"; } 
		if(!empty($setStr)) { return self::getDB($tableName)->createCommand("update `".$tableName."` set ".$setStr." where ".$condition)->execute(); } 
		return false;	
	}
}
