<?php

class TDCreateSQL {

	public static function formatDefault($default) {
		if(in_array(strtolower($default),array('emptystring','empty string'))) {
			return '';	
		}
		return $default;
	}
	
	public function dropColumnSQL($columnTable,$columnName) {
		return "alter table `".$columnTable."` drop column `".$columnName."`";	
	}	

	public function addColumnSQL($tableName,$columnName,$db_type,$allow_empty,$default_value,$comment='',$autoIncrement=0,$is_primary_key=0) {
		if(TDCommon::getValueBySQL('SELECT count(1) as num FROM information_schema.columns WHERE table_schema=\''.TDDataDAO::getDBName()
		.'\' AND table_name = \''.$tableName.'\' AND column_name = \''.$columnName.'\'') > 0) {
			return '';
		}
		$sql = ' alter table `'.$tableName.'` add column  `'.$columnName.'` '.$db_type
		.' '.($allow_empty == 1 ? "" : 'not null')
		.' '.(empty($default_value) && !is_numeric($default_value) ? "" : " default '".TDCreateSQL::formatDefault($default_value)."'")
		.' '.($autoIncrement == 1 ? " AUTO_INCREMENT " : "").' comment \''.$comment.'\' ';
		if($is_primary_key && !TDTable::checkHasPrimaryKey($tableName,$columnName)) {
			$sql .=',ADD PRIMARY KEY (`'.$columnName.'`);';
		} 
		return $sql;
	}

	public function changeColumnSQL($tableName,$orgColumnName,$columnName,$db_type,$allow_empty,$default_value,$comment='',$autoIncrement=0,$is_primary_key=0) {
		$sql = 'alter table `'.$tableName.'` 
		change column `'.$orgColumnName.'` `'.$columnName.'` '.$db_type
		.' '.($allow_empty == 1 ? "" : 'not null').'  '
		.(empty($default_value) && !is_numeric($default_value) ? "" : " default '".TDCreateSQL::formatDefault($default_value)."'")
		.' '.($autoIncrement == 1 ? " AUTO_INCREMENT " : "").' comment \''.$comment.'\' ';
		if($is_primary_key && !TDTable::checkHasPrimaryKey($tableName,$columnName)) {
			$sql .=',ADD PRIMARY KEY (`'.$columnName.'`);';
		} 
		return $sql;
	}

	public function addForeignKeySQL($tableName,$foreignKeyColumnName,$referenceTableName,$referenceColumnName) {
		return  'ALTER TABLE `'.$tableName.'` ADD FOREIGN KEY (`'.$foreignKeyColumnName.'`) REFERENCES `'
			.$referenceTableName.'` (`'.$referenceColumnName.'`) ON DELETE CASCADE ON UPDATE NO ACTION;';
	} 

	public function addIndexKeySQL($tableName,$columnName) {
		return 'ALTER TABLE `'.$tableName.'` ADD INDEX (`'.$columnName.'`) USING BTREE ;';	
	}

	public function updateForeignColumnZeroToNull($tableName,$columnName) {
		return 'update `'.$tableName.'` set `'.$columnName.'`=null where `'.$columnName.'`=0;';	
	}

	//public function addPrimaryKey($tableName,$columnName) {
	//	return 'ALTER TABLE `'.$tableName.'` ADD PRIMARY KEY (`'.$columnName.'`);';	
	//}

	public function dropPrimaryKey($tableName,$columnName) {
		return 'ALTER TABLE `'.$tableName.'` DROP PRIMARY KEY;';	
	}
}