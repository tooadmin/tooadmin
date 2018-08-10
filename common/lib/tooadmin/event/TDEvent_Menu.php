<?php

class TDEvent_Menu {

	public static function deleteChildMenu($pid) {
		//delete child menu
		TDModelDAO::deleteByPk(TDTable::$too_menu,$pid);
		//delete menu_items
		$items = TDModelDAO::queryAll(TDTable::$too_menu_items,"menu_id=".$pid,"id,module_id");
		foreach($items as $item) {
			//delete menu_items 必须在 delete module 之前执行，因为delete module 也会去检测删除 menu_items
			TDModelDAO::deleteByPk(TDTable::$too_menu_items,$item["id"]);
			//delete module
			if(!empty($item["module_id"])) {
				TDEvent_Module::deleteModuleRelationData($item["module_id"]);	
				TDModelDAO::deleteByPk(TDTable::$too_module,$item["module_id"]);
			}
		}	
		$rows = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=".$pid,"id");
		foreach($rows as $row) {
			if(TDModelDAO::queryScalar(TDTable::$too_menu,"`pid`=".$row["id"],"count(1)") > 0) {
				self::deleteChildMenu($row["id"]);
			}		
		}
	}

	public static function afterDelete($model) {
		//删除子菜单
		if(!empty($model->id)) {
			self::deleteChildMenu($model->id);
		}	
	}

	public static function afterSave($model) { }
}
