<?php

class Too {
	/*根据模块ID获取数据表ID*/
	public static function gTbIdByMdId($mdid) { return !empty($mdid) ? TDModelDAO::queryScalarByPk(TDTable::$too_module, $mdid, 'table_collection_id') : 0; }

	/*普通操作的按钮*/
	public static $TYPE_ADD = 0;
	public static $TYPE_UPDATE = 1;
	public static function opModule($btnText,$moduleId,$editType,$pkId=0) {
		$url = "";
		$title = "";
		if($editType == self::$TYPE_ADD) {
			$url = TDPathUrl::createGridviewOpUrl($moduleId,TDPathUrl::$createGridviewOpUrl_TYPE_ADD);
		} else if($editType == self::$TYPE_UPDATE) {
			$url = TDPathUrl::createGridviewOpUrl($moduleId,TDPathUrl::$createGridviewOpUrl_TYPE_UPDATE);
		}
		return  '<input type="btn" onclick="popupWindow(\''.$title.'\',\''.$url.'\')" value="'.$btnText.'" />';	
	}

	public static function createPageUrl($pageId) {
		return TDPathUrl::createUrl("tDSite/index/tpageid/".$pageId);	
	}
	public static function createPagePath($pageId) {
		return TDDataFiles_Website::getPagePath($pageId);
	}

	//加载自定义文件
	public static function viewFile($obj,$fileName) {
		if(strrpos($fileName,".php") !== false) {
			$fileName = substr($fileName,0,strrpos($fileName,".php"));
		} elseif(strrpos($fileName,".PHP") !== false) {
			$fileName = substr($fileName,0,strrpos($fileName,".PHP"));
		} 
		$file = Yii::app()->params["cus_file_path"].$fileName;
		if(!is_file($file.".php")) {
			throw new Exception("未找到文件".$file.".php");	
		}
		$obj->beginContent($file); $obj->endContent();	
	} 
	public static function daoFile($fileName) {
		if(strrpos($fileName,".php") !== false) {
			$fileName = substr($fileName,0,strrpos($fileName,".php"));
		} elseif(strrpos($fileName,".PHP") !== false) {
			$fileName = substr($fileName,0,strrpos($fileName,".PHP"));
		} 
		$file = Yii::app()->params["cus_file_path"].$fileName;
		if(!is_file($file.".php")) {
			throw new Exception("未找到文件".$file.".php");	
		}
		include_once $file.'.php';
	} 
	public static function refresh() {
		return "$('#layoutCompos".TDRequestData::getGetData('mitemId')."').html('');loadMenuItemUrl('layoutCompos".TDRequestData::getGetData('mitemId')."','".Yii::app()->request->url."');";
	}
}
