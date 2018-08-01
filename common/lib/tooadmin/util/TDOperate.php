<?php

class TDOperate {

	public static $PARAM_OPERATE_TYPE = "OPERATE_TYPE";
	public static $PARAM_COLUMN_ID = "OPERATE_columnId";
	public static $PARAM_PKID = "OPERATE_pkId";	
	public static $PARAM_TABLE_ID = "OPERATE_tableId";
	public static $PARAM_CHOOSEED_IDS = "OPERATE_chooseed_ids";
	public static $PARAM_WEBSITE_ID = "OPERATE_websiteId";
	public static $PARAM_PUBLISH_ALL = "OPERATE_isPublishAll";
	public static $TYPE_DELETE_FILE = "deleteFile";
	public static $TYPE_DELETE_CHOOSEED_ROWS = "deleteChooseedRows";
	public static $TYPE_REFRESH_TABLE_STRUCTURE = "refreshTableStructure";
	public static $TYPE_REFRESH_ALL_TABLES_STRUCTURE = "refreshAllTablesStructure";
	public static $TYPE_WEBSITE_PUBLISH = "websitePublish";

	public static function createDeleteFileAppendUrlStr($columnId,$pkId) { return self::$PARAM_OPERATE_TYPE."/"
	.self::$TYPE_DELETE_FILE."/".self::$PARAM_COLUMN_ID."/".$columnId."/".self::$PARAM_PKID."/".$pkId;} 
	public function deleteFile() { 
		$array = array(); $array["columnId"] = 0; $array["pkId"] = 0;
		if(isset($_GET[self::$PARAM_COLUMN_ID])) { $array["columnId"] = $_GET[self::$PARAM_COLUMN_ID]; }
		if(isset($_GET[self::$PARAM_PKID])) { $array["pkId"] = $_GET[self::$PARAM_PKID]; } 
		$result = Fie_file::deleteFile($array["columnId"],$array["pkId"]); return $result; 
	}

	public function deleteChooseedRows() {
		$result = new TDOperateResult();
		$tableId = isset($_GET[self::$PARAM_TABLE_ID]) ? $_GET[self::$PARAM_TABLE_ID] : 0;
		$chooseIds = isset($_GET[self::$PARAM_CHOOSEED_IDS]) ? $_GET[self::$PARAM_CHOOSEED_IDS] : 0;
		if(empty($tableId) || !is_numeric($tableId)) {
			$result->setMsg("error data _GET ".self::$PARAM_TABLE_ID);
		} else if(empty($chooseIds)) {
			$result->setMsg("empty data _GET ".self::$PARAM_CHOOSEED_IDS);
		} else {
			TDModelDAO::getModel(TDTableColumn::getTableDBName($tableId))->deleteAll("`id` in (".$chooseIds.")");	
			$result->setResult(true);
		}
		return $result;	
	}
	
	public function refreshTableStructure() {
		return TDTable::synchronizeDBWithSys(isset($_GET[self::$PARAM_TABLE_ID]) ? $_GET[self::$PARAM_TABLE_ID] : 0);
	}

	public function refreshAllTablesStructure() {
		ini_set('max_execution_time','0');
		ini_set('memory_limit', '-1');
		$result = new TDOperateResult();
		$tables = TDDataFiles::getDBTables();
		foreach($tables as $tb){
			if(strpos($tb,"too_") === 0) {
				continue;
			}
			$res = TDTable::synchronizeDBWithSys(0,$tb);	
			if(!$res->getIsSuccess()) {
				$result->setMsg($tb." fail ");			
			}
		}
		$errorMsg = $result->getMsg();
		if(empty($errorMsg)) {
			$result->setResult(true);
		}
		return $result;
	}

	public function websitePublish() {
		$result = new TDOperateResult();
		$websiteId = isset($_GET[self::$PARAM_WEBSITE_ID]) ? $_GET[self::$PARAM_WEBSITE_ID] : 0;
		$isAll = isset($_GET[self::$PARAM_PUBLISH_ALL]) && $_GET[self::$PARAM_PUBLISH_ALL] == 1 ? true : false; 
		if(empty($websiteId) || !is_numeric($websiteId)) {
			$result->setMsg("error data _GET ".self::$PARAM_WEBSITE_ID);
		} else {
			$res = TDDataFiles_Website::websitePublish($websiteId,$isAll);	
			if($res) {
				$result->setResult(true);
			} else {
				$result->setMsg(TDLanguage::$sys_website_publish_error);
			}
		}
		return $result;	
	}

	
	/*
	public static $PARAM_NEW_VALUE = "OPERATE_new_value";
	public static $TYPE_UPDATE_AROW = "updateARow";
	public function updateARow() {
		$result = new TDOperateResult();
		$columnId = isset($_GET[self::$PARAM_COLUMN_ID]) ? $_GET[self::$PARAM_COLUMN_ID] : 0;  
		if(empty($columnId) || !is_numeric($columnId)) { $result->setMsg("error data _GET ".self::$PARAM_COLUMN_ID); return $result; }
		$tableName = TDTableColumn::getColumnTableDBName($columnId); 
		if(empty($tableName)) { $result->setMsg("query table name by column id ".$columnId." is empty"); return $result; }
		$pkId =isset($_GET[self::$PARAM_PKID]) ? $_GET[self::$PARAM_PKID] : 0;  
		if(empty($pkId) || !is_numeric($pkId)) { $result->setMsg("error data _GET ".self::$PARAM_PKID); return $result; }
		$model = ModelDAO::getModel($tableName,$pkId);
		if(empty($model)) { $result->setMsg("empty model pkid=".$pkId); return $result; }
		$vs = new TDFormValidateSave($model,array(TDField::createFieldIdOrName($columnId,null,true)=>isset($_GET[self::$PARAM_NEW_VALUE]) ? $_GET[self::$PARAM_NEW_VALUE] : ""));
		$vs->setModelFormData();
		$vs->validateSave(true);
		$errorStr = $vs->getErrorStr();
		if(!empty($errorStr)) {
			$result->setMsg($errorStr);
		} else {
			$result->setResult(true);
		}
		return $result;
	}
	*/

}
