<?php

class TDModule {

	public static function getModuleTableId($moduleId) { 
		if($moduleId == TDStaticDefined::$mysqlCommonModuleId) { return intval($_GET['mysqlCommonMuduleTabId']); }
		return TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId, 'table_collection_id');
	}

	public static function getModuleIdByTableName($tableName) {
		$moduleId = 0;
		$tableId = TDTableColumn::getTableCollectionID($tableName);
		if (!empty($tableId)) {
			$moduleId = TDModelDAO::queryScalar(TDTable::$too_module,"`table_collection_id`=" . $tableId,"min(id)");
		}
		return intval($moduleId);
	}

	public static function getModuleTableName($moduleId) { return TDTableColumn::getTableDBName(self::getModuleTableId($moduleId)); }

	private static function getAllowColumnArray($moduleId, $allowColumn) {
		$cacheValues = TDSessionData::getCache("getAllowColumnArray_" . $moduleId . "_" . $allowColumn);
		if ($cacheValues === false) {
			$rows = TDModelDAO::queryAll(TDTable::$too_module_gridview,'`module_id`=\'' . $moduleId . '\' and `' . $allowColumn . '`=1','`table_column_id`,`belong_order_column_ids`');
			$columns = array(null);
			foreach ($rows as $row) {
				$columns[] = TDTableColumn::getColumnAppendStr($row["table_column_id"],$row["belong_order_column_ids"]);
			}
			$cacheValues = $columns;
			TDSessionData::setCache("getAllowColumnArray_" . $moduleId . "_" . $allowColumn, $cacheValues);
		}
		return $cacheValues;
	}

	public static function getOrderColumns($moduleId) { return self::getAllowColumnArray($moduleId, 'allow_order'); }

	public static function getUpdateButtonViewBool($data, $moduleId) {
		$update_button_view = TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId, "update_button_view");
		if (empty($update_button_view)) { return true; } else { return Fie_formula::getValue($data,$update_button_view); }
	}

	public static function getDeleteButtonViewBool($data, $moduleId) {
		$delete_button_view = TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId, "delete_button_view");
		if (empty($delete_button_view)) { return true; } else { return Fie_formula::getValue($data,$delete_button_view); }
	}

	public static function getViewButtonViewBool($data, $moduleId) {
		$view_button_view = TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId,"view_button_view");
		if (empty($view_button_view)) { return true; } else { return Fie_formula::getValue($data,$view_button_view); }
	}

	public static function getExpandeOperateButtonHtml($data, $moduleId) {
		$expande_operate_button = TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId,"expande_operate_button");
		if (empty($expande_operate_button)) { return null; } else { return Fie_formula::getValue($data,$expande_operate_button); }
	}

	public static function getReSetTableRowSpanJsHtml($columns, $moduleId, $gridViewId) {
		$mergeRows = TDModelDAO::queryAll(TDTable::$too_module_gridview, "module_id=" . $moduleId . " and is_merge=1", "table_column_id,belong_order_column_ids");
		$mergeCols = array();
		foreach ($mergeRows as $row) {
			$mergeCols[] = TDTableColumn::getColumnAppendStr($row["table_column_id"],$row["belong_order_column_ids"]); 
		}
		$colindexStr = "";
		foreach ($columns as $colindex => $columnObj) {
			foreach ($mergeCols as $mgcol) {
				if (isset($columnObj["name"]) && $columnObj["name"] == $mgcol) {
					$colindexStr .= !empty($colindexStr) ? ",".$colindex : $colindex;
					break;
				}
			}
		}
		if(!empty($colindexStr)) {
			$jsHtml = '<script>setTimeout("gridviewColumnsMerge(\''.$gridViewId.'\',\''.$colindexStr.'\')",2000);</script>';
			return $jsHtml;	
		}
		/*
		  $jsHtmlSS = '
					<script>
					function testTT'.str_replace('-','_',$gridViewId).$colindex.'() {
						alert("'.str_replace('-','_',$gridViewId).$colindex.'");
							alert($("#' . $gridViewId . ' table tbody tr").size());
						var lastrowText = "";
						var spanCount = 1;
						var rowIndex = 0;
						var lastSpanCount = 1;
						var rowIndexKeySapnCount = new Array();
						$("#' . $gridViewId . ' table tbody tr").each(function(){
							rowIndexKeySapnCount[rowIndex] = 1;
							var tdind = 0;
							var isDelete = false;
							var curText = $(this).find("td:eq(' . $colindex . ')").text();
							if(lastrowText != "" && lastrowText == curText) { 
								$(this).find("td:eq(' . $colindex . ')").remove(); 
								spanCount++;
							} else {
								$(this).parent().find("tr:eq("+(rowIndex-spanCount)+")").find("td:eq(' . $colindex . ')").attr("rowspan",spanCount);
								spanCount = 1;
								lastrowText = curText;
							}
							lastSpanCount = spanCount; 
							rowIndex++;
						});
						if(lastSpanCount > 1) {
							$("#' . $gridViewId . ' table tbody").find("tr:eq("+(rowIndex-lastSpanCount)+")").find("td:eq(' . $colindex . ')").attr("rowspan",lastSpanCount);
						}
					}
					setTimeout("testTT'.str_replace('-','_',$gridViewId).$colindex.'()",6000);
					</script> ';
		 */
	}

	public static function createModuleByTableId($tableId) {
		$tableName = TDTableColumn::getTableDBName($tableId);
		$moduleModel = TDModelDAO::getModel(TDTable::$too_module);
		$moduleModel->name = $tableName;
		$moduleModel->table_collection_id = $tableId;
		if($moduleModel->save()){
			return $moduleModel->id;
		}
		return false;
	}
	
}
