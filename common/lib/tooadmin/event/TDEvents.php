<?php

class TDEvents {

	private static function getEvenResult($tableName,$evenName,$model) {
		$result = null;
		$className = 'event_'.$tableName;
		if(is_file(dirname(__FILE__).'/'.$className.'.php') && method_exists($className,$evenName)) {
			$class = new $className();	
			$result = $class->$evenName($model);
		}
		return $result;
	}
	
	private static function getDeleteEventsObj($tableName,$pkId) {
		$eventsObj = new TDEventsObj(); 
		$model = TDModelDAO::getModel($tableName,$pkId);
		$eventsObj->addRalationModel($model);
		$resObj = self::getEvenResult($tableName,"deleteEven",$model); 
		if(!empty($resObj)) {
			$eventsObj->addEventsObj($resObj);
		}
		return $eventsObj;
	}
	private static function deleteModelByEventsObj($eventsObj) {
		foreach($eventsObj->getRalationModelArray() as $modelItem) {
			$modelItem->delete();
		}
		foreach($eventsObj->getEventsObjArray() as $eventsItem) {
			self::deleteModelByEventsObj($eventsItem);
		}
	}

	private static function getSaveEventsObj($model) {
		$eventsObj = new TDEventsObj();
		$eventsObj->addRalationModel($model);
		$resObj = self::getEvenResult($model->tableName,"saveEven",$model);
		if(!empty($resObj)) {
			$eventsObj->addEventsObj($resObj);
		}
		return $eventsObj;
	}
	private static function saveModelByEventsObj($eventsObj,$err = '') {
		foreach($eventsObj->getRalationModelArray() as $modelItem) {
			$modelItem->save();
			if(!empty($modelItem->errors)) {
				foreach($modelItem->errors as $col => $errors) {
					foreach($errors as $error) {
						$err = empty($err) ? (is_array($error) ? $error[0] : $error) : $err.",".$error;
					}
				}
			}
		}
		foreach($eventsObj->getEventsObjArray() as $eventsItem) {
			$err = self::saveModelByEventsObj($eventsItem,$err);
		}
		return $err;
	}
	
	private static function excuteSQLByEventsObj($eventsObj) {
		foreach($eventsObj->getExcuteSQLArray() as $sql) {
			TDDataDAO::executeSQL($sql);
		}
		foreach($eventsObj->getEventsObjArray() as $eventsItem) {
			self::excuteSQLByEventsObj($eventsItem);
		}
	}
		
	public static function saveEven($model,$useTransaction = true) {
		$errorMsg = '';
		$eventsObj = self::getSaveEventsObj($model);
		if($useTransaction) {
			$db = TDModelDAO::getDB($model->tableName);
			$tran = $db->beginTransaction();
			try {
				$errorMsg = self::saveModelByEventsObj($eventsObj);
				if(empty($errorMsg)) {
					self::excuteSQLByEventsObj($eventsObj);	
					$tran->commit();
				}
			} catch(Exception $e) {
				$tran->rollback();
				$errorMsg = $e->getMessage();
			}
		} else {
			$errorMsg = self::saveModelByEventsObj($eventsObj);
			if(empty($errorMsg)) {
				self::excuteSQLByEventsObj($eventsObj);	
			}
		}
		if(empty($errorMsg)) {
			self::getEvenResult($model->tableName,"afterSave",$model);
		}
		return $errorMsg;
	} 

	public static function deleteEven($tableName,$pkId,$useTransaction = true) {
		$eventsObj =  self::getDeleteEventsObj($tableName,$pkId);
		if($useTransaction) {
			$db = TDModelDAO::getDB($tableName);
			$tran = $db->beginTransaction();
			try {
				self::deleteModelByEventsObj($eventsObj);
				self::excuteSQLByEventsObj($eventsObj);
				self::getEvenResult($tableName,"afterDelete",TDModelDAO::getModel($tableName,$pkId));
				$tran->commit();
				return true;
			} catch(Exception $e) {
				$tran->rollback();
				throw new Exception($e->getMessage(),$e->getCode(),$e->getPrevious());
			}	
		} else {
			self::deleteModelByEventsObj($eventsObj);
			self::excuteSQLByEventsObj($eventsObj);	
			self::getEvenResult($tableName,"afterDelete",TDModelDAO::getModel($tableName,$pkId));
			return true;
		}
		return false;
	}
}