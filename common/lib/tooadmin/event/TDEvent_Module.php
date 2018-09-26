<?php

class TDEvent_Module {

	public static function deleteModuleRelationData($module_id) {
		//删除模块对应的表单和列表	
		TDModelDAO::deleteByCondition(TDTable::$too_module_formEdit,"module_id=".$module_id);
		TDModelDAO::deleteByCondition(TDTable::$too_module_formmodule,"form_module_id=".$module_id." or ntable_module_id=".$module_id);
		TDModelDAO::deleteByCondition(TDTable::$too_module_gridview,"module_id=".$module_id);
		TDModelDAO::deleteByCondition(TDTable::$too_module_gridview_expbtn,"too_module_id=".$module_id);
		//删除模块对应的菜单
		$rows = TDModelDAO::queryAll(TDTable::$too_menu_items,"module_id=".$module_id." group by menu_id","menu_id");	
		TDModelDAO::deleteByCondition(TDTable::$too_menu_items,"module_id=".$module_id);
		foreach($rows as $row) {
			if(TDModelDAO::queryScalar(TDTable::$too_menu_items,"menu_id=".$row["menu_id"],"count(*)") == 0) {
				TDModelDAO::deleteByPk(TDTable::$too_menu,$row["menu_id"]);
			}
		}
	}
	
	public static function beforeDelete($model) {
		$module_id = intval($model->id);
		self::deleteModuleRelationData($module_id);	
	}
}
