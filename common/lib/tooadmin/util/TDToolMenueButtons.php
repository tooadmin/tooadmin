<?php

class TDToolMenueButtons {

	public static function getGridviewTools($moduleID,$queryFormId,$gridviewId) {
		$array = array();
		if (TDSessionData::currentUserIsAdmin()) {
			$array[] = array(
				'jsfunction' => 'refashGridView(\''.$gridviewId.'\')', //'to_gridview_refresh()',
				'iclass' => 'icon icon-blue icon-refresh',
				'title' => TDLanguage::$to_refresh,
			);
			if ($moduleID != TDStaticDefined::$mysqlCommonModuleId) {
				/*
				$array[] = array(
					'jsfunction' => 'to_gridview_admin(' . $moduleID . ')',
					'iclass' => 'icon icon-blue icon-clipboard',
					'title' => TDLanguage::$to_columns_admin,
				);
				*/
				$array[] = array(
					'jsfunction' => 'to_gridview_set(' . $moduleID . ')',
					'iclass' => 'icon icon-blue icon-edit',
					'title' => TDLanguage::$to_gridview_set,
				);
				/*
				if ($moduleID == TDStaticDefined::$tableManageModuleId) {
					$array[] = array(
						'jsfunction' => 'refreshAllTablesStructure()',
						'iclass' => 'icon icon-blue icon-arrowrefresh-e',
						'title' => TDLanguage::$refreshAllTablesStructure,
					);
				}
				*/
			}
		}
		/*
		$array[] = array(
			'jsfunction' => 'exportTbToExcel(\'' .$queryFormId. '\')',
			'iclass' => 'icon icon-color icon-redo',
			'title' => TDLanguage::$to_export_excel,
		);
		*/
		return $array;
	}

}
