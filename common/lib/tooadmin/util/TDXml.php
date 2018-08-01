<?php

class TDXml {

	public static $common_db_info = "./assets/datafiles/common_db_info.xml";

	public static function getXmlPath($tableName) { return "./common/lib/tooadmin/datafiles/xml/".$tableName.".xml"; }

	private static function getXmlChildByRow(&$dom,$drow) {
		$xrow = $dom->createElement("row");
		$columnObjs = TDTable::getTableObj($drow->tableName(),false)->columns;
		$columns = array_keys($columnObjs);	
		foreach($columns as $columnName) {
			$xrow->appendChild($dom->createElement($columnName,$drow->$columnName));
			/// "'".mysql_escape_string($tmpValue)."'"; 
		}
		return $xrow;
	}
	
	public static function iniTableDataToXml($tableName) {
		//header( "content-type: application/xml; charset=utf-8" );
		$dom = new DOMDocument("1.0","utf-8");
		$xrows = $dom->createElement("rows");
		$dom->appendChild($xrows);
		$drows = TDModelDAO::getModel($tableName)->findAll();	
		foreach($drows as $drow) {
			$xrows->appendChild(self::getXmlChildByRow($dom,$drow));	
		}
		$fp = fopen(self::getXmlPath($tableName),"w");
		fwrite($fp,$dom->saveXML());
		fclose($fp);
	}

	public static function loadXmlToRows($tableName,$condition = array()) {
		$xml = simplexml_load_file(self::getXmlPath($tableName));
		$rows = array();
		foreach ($xml->row as $row) {
			$model = TDModelDAO::getModel($tableName);
			$columnObjs = TDTable::getTableObj($tableName,false)->columns;
			$columns = array_keys($columnObjs);
			$isCantoAdd = true;
			foreach($columns as $columnName) {
				if(isset($condition[$columnName]) && $condition[$columnName] != $row->$columnName) {
					$isCantoAdd = false;
					break;
				}
				$model->setAttribute($columnName,$row->$columnName);
				$model->isNewRecord = false;
			}
			if($isCantoAdd) {
				$rows[] = $model;
			}
		}	
		return $rows;
	}

	public static function findByPk($tableName,$pkId) {
		$rows = self::loadXmlToRows($tableName,array("id"=>$pkId));
		if(!empty($rows)) { return $rows[0]; } else { return null; }
	}

	public static function find($tableName,$condition=array()) {
		$rows = self::loadXmlToRows($tableName,$condition);
		if(!empty($rows)) { return $rows[0]; } else { return null; }
	}
	
	private static function getCreateNewId($tableName) {
		$xml = simplexml_load_file(self::getXmlPath($tableName));
		$newId = 0; foreach($xml->row as $row) { if(intval($row->primaryKey) > $newId) { $newId = intval($row->primaryKey); } }
		return $newId+1;
	}
	
	public static function save($model) {
		$dom = new DOMDocument("1.0","utf-8");
		$dom->load(self::getXmlPath($model->tableName));
		if($model->isNewRecord) {
			$model->isNewRecord = false;
			$mdtbPk = TDTable::getTableObj($model->tableName)->primaryKey;
			$model->$mdtbPk = self::getCreateNewId($model->tableName);
			$xrows = $dom->getElementsByTagName("rows");
			$xrows->item(0)->appendChild(self::getXmlChildByRow($dom,$model));		
			$fp = fopen(self::getXmlPath($model->tableName),"w");
			fwrite($fp,$dom->saveXML());
			fclose($fp);	
			return true;
		} else {
			$xml = simplexml_load_file(self::getXmlPath($model->tableName));
			$columnObjs = TDTable::getTableObj($model->tableName,false)->columns;
			$columns = array_keys($columnObjs);
			foreach($xml->row as $row) {
				if(intval($row->primaryKey) == intval($model->primaryKey)) {
					foreach($columns as $columnName) {	
						$row->$columnName = $model->$columnName;
					}
				}
			}
			$xml->saveXML(self::getXmlPath($model->tableName));
			return true;
		}	
	}

	public static function delete($tableName,$pkId) {
		$dom = new DOMDocument("1.0","utf-8");
		$dom->load(self::getXmlPath($tableName));
		$xrows = $dom->documentElement;
		$row = $xrows->getElementsByTagName("row");
		for($i=0; $i<$row->length; $i++){
			$tmpItems = $row->item($i)->childNodes;
			for($t=0; $t<$tmpItems->length; $t++) {
				if($tmpItems->item($t)->nodeName == "id") {
					$gid = $tmpItems->item($t)->textContent;
					if(intval($gid) == $pkId) {
						$xrows->removeChild($row->item($i));
						break;
					}	
				}
			}
		}
		$fp = fopen(self::getXmlPath($tableName),"w");
		fwrite($fp,$dom->saveXML());
		fclose($fp);	
		return true;
	}
}
