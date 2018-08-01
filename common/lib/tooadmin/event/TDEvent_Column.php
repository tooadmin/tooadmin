<?php

class TDEvent_Column {

	public static function validateColumn($tableName,$columnName) {
		$result = false;
		if(!empty($columnName) && TDSessionData::checkIsTableName($tableName)) {
			$tableObj = TDTable::getTableObj($tableName,false);
			if(isset($tableObj->columns[$columnName])) {
				$result = true;		
			}
		}
		return $result;
	}

	public static function beforDeleveCusColumn($columnId) {
		if(TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId,"column_type") == 1) {
			TDModelDAO::deleteByCondition(TDTable::$too_module_gridview, "table_column_id=".$columnId);
			TDModelDAO::deleteByCondition(TDTable::$too_module_formEdit, "table_column_id=".$columnId);
		}
	}
}
