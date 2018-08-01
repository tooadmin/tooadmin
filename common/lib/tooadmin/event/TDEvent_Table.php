<?php

class TDEvent_Table {
	
	public static function beforeDelete($tableName) {
		$tableId = intval(TDTableColumn::getTableCollectionID($tableName));
		//删除字段
		TDModelDAO::getModel(TDTable::$too_table_column)->deleteAll("table_collection_id=".$tableId);
		//删除表对应的模块
		$moduleRows = TDModelDAO::queryAll(TDTable::$too_module,"table_collection_id=".$tableId,"id");	
		foreach($moduleRows as $mdrow) {
			//删除模块对应的表单和列表	
			TDModelDAO::getModel(TDTable::$too_module_formEdit)->deleteAll("module_id=".$mdrow['id']);	
			TDModelDAO::getModel(TDTable::$too_module_formmodule)->deleteAll("form_module_id=".$mdrow['id']." or ntable_module_id=".$mdrow['id']);	
			TDModelDAO::getModel(TDTable::$too_module_gridview)->deleteAll("module_id=".$mdrow['id']);	
			//删除模块对应的菜单
			$rows = TDModelDAO::queryAll(TDTable::$too_menu_items,"module_id=".$mdrow['id']." group by menu_id");	
			TDModelDAO::getModel(TDTable::$too_menu_items)->deleteAll("module_id=".$mdrow['id']);	
			foreach($rows as $row) {
				if(TDModelDAO::queryScalar(TDTable::$too_menu_items,"menu_id=".$row["menu_id"],"count(*)") == 0) {
					TDModelDAO::getModel(TDTable::$too_menu)->deleteAll("id=".$row["menu_id"]);	
				}
			}
		}
		TDModelDAO::getModel(TDTable::$too_module)->deleteAll("table_collection_id=".$tableId);	
	}
}
