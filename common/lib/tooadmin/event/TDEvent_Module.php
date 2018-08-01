<?php

class TDEvent_Module {

	public static function beforeDelete($model) {
		$module_id = intval($model->id);
		//删除模块对应的表单和列表	
		TDModelDAO::getModel(TDTable::$too_module_formEdit)->deleteAll("module_id=".$module_id);	
		TDModelDAO::getModel(TDTable::$too_module_formmodule)->deleteAll("form_module_id=".$module_id." or ntable_module_id=".$module_id);	
		TDModelDAO::getModel(TDTable::$too_module_gridview)->deleteAll("module_id=".$module_id);	
		//删除模块对应的菜单
		$rows = TDModelDAO::queryAll(TDTable::$too_menu_items,"module_id=".$module_id." group by menu_id");	
		TDModelDAO::getModel(TDTable::$too_menu_items)->deleteAll("module_id=".$module_id);	
		foreach($rows as $row) {
			if(TDModelDAO::queryScalar(TDTable::$too_menu_items,"menu_id=".$row["menu_id"],"count(*)") == 0) {
				TDModelDAO::getModel(TDTable::$too_menu)->deleteAll("id=".$row["menu_id"]);	
			}
		}
	}
}
