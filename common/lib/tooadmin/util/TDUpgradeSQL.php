<?php
class TDUpgradeSQL {

	public function getInsertRowSQL($row,$replaceValueArray=array()) {
		if(empty($row)) {
			return "";
		}
		$sql = "insert into `".$row->tableName()."` ";	
		$columnObjs = TDTable::getTableObj($row->tableName(), false)->columns;
		$columns = array_keys($columnObjs);
		$columnValues = '';
		$columnStrs = '';
		foreach($columns as $columnName) {
			if($columnObjs[$columnName]->autoIncrement) {
				continue;
			}
			if(!empty($columnValues)) { 
				$columnValues .= ','; 
				$columnStrs .= ','; 
			}
			$tmpValue = $row->$columnName; 
			if(isset($replaceValueArray[$columnName])) {
				$tmpValue = $replaceValueArray[$columnName];
			}
			$columnValues .= "'".addslashes($tmpValue)."'"; 
			///$columnValues .= "'".mysql_escape_string($tmpValue)."'"; 
			$columnStrs .= '`'.$columnName.'`';
		}
             	$sql .= "(".$columnStrs.") values(".$columnValues.");\n";	
		return $sql;
	}
	
	public function getCreateTableSQL($tableName,$bakData = false) {
		$tabledump = "DROP TABLE IF EXISTS $tableName;\n";
         	$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE $tableName")->query();
		foreach($createtable as $row) {
			$tabledump .=  $row['Create Table'].";\n\n";
		}
		if($bakData) {
         		$rows = TDModelDAO::getModel($tableName)->findAll(); 
			foreach($rows as $row) {
             			$tabledump .= $this->getInsertRowSQL($row);
          		}
         		$tabledump .= "\n";
		}
          	return $tabledump;
	}
}
