<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TDUpgrade1_0_9
 *
 * @author ThinkPad User
 */
class TDUpgrade1_3_0 {
	public static function upgrade() {
		$curCode = Yii::app()->params->sys_version;
		if($curCode == "1.2.9") {
			$rows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id="
			.TDTableColumn::getTableCollectionID(TDTable::$too_table_collection));	
			foreach($rows as $row) {
				$row->before_delete = 'TDEvent_Table::beforeDelete($model);';
				$row->after_delete = 'TDEvent_Table::afterDelete($model);';
				$row->form_save_php_code = 'TDEvent_Table::beforeSave($model);';
				$row->save();
			}
			$rows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id="
			.TDTableColumn::getTableCollectionID(TDTable::$too_table_column));	
			foreach($rows as $row) {
				$row->after_save_code = 'TDEvent_Column::afterSave($model);';
				$row->before_delete = 'TDEvent_Column::beforeDelete($model);';
				$row->form_save_php_code = 'TDEvent_Column::beforeSave($model);';
				$row->save();
			}
			$rows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id="
			.TDTableColumn::getTableCollectionID(TDTable::$too_module));	
			foreach($rows as $row) {
				$row->after_save_code = 'TDEvent_Module::afterSave($model);';
				$row->before_delete = 'TDEvent_Module::beforeDelete($model);';
				$row->save();
			}	
			$rows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id="
			.TDTableColumn::getTableCollectionID(TDTable::$too_menu));	
			foreach($rows as $row) {
				$row->after_delete = 'TDEvent_Menu::afterDelete($model);';
				$row->after_save_code = 'TDEvent_Menu::afterSave($model);';
				$row->save();
			}
			//TDSysConfig::setSysVersion("1.3.0");
			$curCode = Yii::app()->params->sys_version;
			echo "1.2.9 => 1.3.0 升级完毕".date("Y-m-d H:i:s")."<br/>";
		}
	}
}
