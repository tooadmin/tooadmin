<?php

class TDTableColumn {

	public static $COLUMN_TYPE_DB_COLUMN = 0; //数据表的字段
	public static $COLUMN_TYPE_CUSTOM_COLUMN = 1; //自定义的字段(公式列)
	public static $COLUMN_TYPE_COMMENT_CUSTOM_STR = 'customcolumn'; //获取列的名称时判断是否为自定义列

	public static function getColumnDBName($columnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, "`name`"); }
	public static function getColumnDBType($columnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, "`db_type`"); }
	public static function getColumnColumnType($columnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, "`column_type`"); }
	public static function checkColumnIsForeignkey($columnId) { $result = self::getColumnForeignColumnId($columnId); return empty($result) ? false : true; }
	public static function getColumnTableDBName($columnId) { $tbid = self::getColumnTableCollectionId($columnId);	return self::getTableDBName($tbid); }
	public static function getColumnForeignTableName($columnId) { $forColumnId = self::getColumnForeignColumnId($columnId);return self::getColumnTableDBName($forColumnId); }
	public static function getColumnLabelName($columnId, $fromDB = false) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, "`label`"); }
	public static function getColumnForeignColumnId($columnId) { $row = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$columnId,"foreign_table_column_id,map_table_collection_id");
	if(!empty($row["foreign_table_column_id"])) { return $row["foreign_table_column_id"]; } else if(!empty($row["map_table_collection_id"])) { 
	return TDModelDAO::queryScalar(TDTable::$too_table_column, "table_collection_id=".$row["map_table_collection_id"]." and is_primary_key=1", "id"); } return 0; }
	public static function getColumnTableCollectionId($columnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, "`table_collection_id`"); }
	public static function getColumnFormulaStr($columnId) { return TDModelDAO::queryScalar(TDTable::$too_table_column,"id=".$columnId." and `column_type`=".TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN,"`formula`"); }
	public static function getTableColumnClassGroupName($classId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class, $classId, "`group_name`"); }
	public static function getCustomColumnFormula($columnIdOrLadderColumnIds) { $columnId = $columnIdOrLadderColumnIds; if (strpos($columnIdOrLadderColumnIds, ',') !== false) { 
	$columnId = explode(',', $columnIdOrLadderColumnIds); $columnId = $columnId[count($columnId) - 1]; } return self::getColumnFormulaStr($columnId); }
	public static function checkIsCustomColumn($columnIdOrLadderColumnIds) { $columnId = $columnIdOrLadderColumnIds; if (strpos($columnIdOrLadderColumnIds, ',') !== false) { $columnId = explode(',', $columnIdOrLadderColumnIds); 
	$columnId = $columnId[count($columnId) - 1]; } $columntype = self::getColumnColumnType($columnId); if ($columntype == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN) { return true; } return false; }
	public static function getMapTableCollectionId($columnId) { return TDModelDAO::getFieldById(TDTable::$too_table_column, $columnId, "map_table_collection_id"); }
	public static function getColumnIdByTableAndColumnName($tableName, $columnName, $fromDB = false) {
		$tbid = TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$tableName."'","id");
		if (empty($tbid)) { throw new Exception($tableName . ' unfind is system '); }
		$res = TDModelDAO::queryScalar(TDTable::$too_table_column,"`table_collection_id`=" . $tbid . " and `name`='" . $columnName . "'","id");
		if (empty($res)) { throw new Exception($tableName . ' unfind the column ' . $columnName); }
		return $res;
	}
	public static function getInputTypeByInputId($inputId, $isClassName = true) { $value = TDModelDAO::queryScalarByPk(TDTable::$too_table_column_input,$inputId,"`name`"); if ($isClassName) { $value = "Fie_" . $value; } return $value; }
	public static function getInputTypeByColumnId($columnId, $isClassName = true) { return self::getInputTypeByInputId(TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$columnId,"table_column_input_id"),$isClassName);	}
	public static function getTableDBName($tbCollid, $fromDB = false) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_collection,$tbCollid,"`table`"); }

	public static function getTableDBNameByModuleId($moduleId) { $tbid = TDModule::getModuleTableId($moduleId); return TDModelDAO::queryScalarByPk(TDTable::$too_table_collection,$tbid,"`table`"); }
	public static function getTableCollectionID($tbName) { return TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$tbName."'", "`id`"); }
	public static function getColumnStaticData($columnId) { $row = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$columnId,"`name`,`static_array`,`edit_static_array`,`table_column_input_id`,`pid_view_columnid`"); 
	$row["static_array"] = str_replace("'","\'",$row["static_array"]); $row["edit_static_array"] = str_replace("'","\'",$row["edit_static_array"]); return $row; }
	public static function checkColumnIdIsValid($table_column_id) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$table_column_id,"count(*)") > 0 ? true : false;	}
	public static function checkColumnIdIsFromTable($columnId,$tableId){return TDModelDAO::queryScalar(TDTable::$too_table_column,"id=".$columnId." and table_collection_id=".$tableId,"count(*)") > 0 ? true : false;}

	public static function getCustomColumnNames($tableName) {
		$result = TDSessionData::getCache("getCustomColumnNames_".$tableName);
		if($result === false) {
			$result = array();
			$rows = TDModelDAO::queryAll(TDTable::$too_table_column,"`column_type`='".TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN.
			"' and `table_collection_id`=".TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$tableName."'","id"),"`name`");	
			foreach($rows as $row) { $result[] = $row["name"]; }	
 			TDSessionData::setCache("getCustomColumnNames_" . $tableName,$result);	
		}
		return $result;
	}

	public static function getColumnAppendStr($tableColumnId, $belongOrderColumnIds = null, $appendLastColumnName = true) {
		$cacheValue = TDSessionData::getCache("getColumnAppendStr_" . $tableColumnId . "_" . $belongOrderColumnIds . "_" . $appendLastColumnName);
		if ($cacheValue === false) {
			$appendStr = "";
			if (!empty($belongOrderColumnIds)) {
				$beIdsArray = explode(",", $belongOrderColumnIds);
				foreach ($beIdsArray as $columnId) {
					if (!empty($appendStr)) {
						$appendStr .= '->';
					}
					$appendStr .= TDTableColumn::getColumnDBName($columnId) . TDSearch::$foreignKey_tableName
							. TDTableColumn::getColumnForeignTableName($columnId);
				}
				if ($appendLastColumnName) {
					$appendStr .= '->' . TDTableColumn::getColumnDBName($tableColumnId);
				}
			} else {
				$appendStr = TDTableColumn::getColumnDBName($tableColumnId);
			}
			$cacheValue = $appendStr;
			TDSessionData::setCache("getColumnAppendStr_" . $tableColumnId . "_" . $belongOrderColumnIds . "_" . $appendLastColumnName, $cacheValue);
		}
		return $cacheValue;
	}

	public static function getLadderColumnAppendStr($ladderColumnIds, $appendLastColumnName = true) {
		$cacheValue = TDSessionData::getCache("getLadderColumnAppendStr_" . $ladderColumnIds . "_" . $appendLastColumnName);
		if ($cacheValue === false) {
			$appendStr = "";
			$beIdsArray = TDCommon::trimArray(explode(",", $ladderColumnIds));
			if (count($beIdsArray) > 1) {
				for ($i = 0; $i < count($beIdsArray) - 1; $i++) {
					if (!empty($appendStr)) {
						$appendStr .= '->';
					}
					$appendStr .= TDTableColumn::getColumnDBName($beIdsArray[$i]) . TDSearch::$foreignKey_tableName . TDTableColumn::getColumnForeignTableName($beIdsArray[$i]);
				}
			}
			if(count($beIdsArray) > 0) {
				if ($appendLastColumnName) {
					if (!empty($appendStr)) {
						$appendStr .= '->';
					}
					$appendStr .= TDTableColumn::getColumnDBName($beIdsArray[count($beIdsArray) - 1]);
				}
			}
			$cacheValue = $appendStr;
			TDSessionData::setCache("getLadderColumnAppendStr_" . $ladderColumnIds . "_" . $appendLastColumnName, $cacheValue);
		}
		return $cacheValue;
	}

	public static function getBelongOrderColumnIds($model, $appendColumnModelStr) {
		$belongOrderColumnIds = '';
		$tmpAppendModelStr = '';
		$tmpStr = explode("->", $appendColumnModelStr);
		for ($i = 0; $i < count($tmpStr); $i++) {
			$str = explode(TDStaticDefined::$foreignKey_tableName, $tmpStr[$i]);
			$tmpTableName = '';
			if (empty($belongOrderColumnIds)) {
				$tmpTableName = $model->tableName;
			} else {
				$tmpChirldModel = TDFormat::getModelAppendColumnValue($model, $tmpAppendModelStr);
				$tmpTableName = $tmpChirldModel->tableName;
				$belongOrderColumnIds .= TDStaticDefined::$formFieldColumnBelongToOrder;
				$tmpAppendModelStr .= '->';
			}
			$tmpAppendModelStr .= $tmpStr[$i];
			$belongOrderColumnIds .= TDTableColumn::getColumnIdByTableAndColumnName($tmpTableName, $str[0]);
		}
		return $belongOrderColumnIds;
	}

	public static function checkColumnhasBelongRelation($baseColumnId, $expandColumnId) {
		$cacheValue = TDSessionData::getCache("checkColumnhasBelongRelation" . $baseColumnId . "_" . $expandColumnId);
		if ($cacheValue === false) {
			$result = false;
			$expandTableName = TDTableColumn::getTableDBName(TDTableColumn::getColumnTableCollectionId($expandColumnId));
			$baseTableObj = TDTable::getTableObj(TDTableColumn::getTableDBName(TDTableColumn::getColumnTableCollectionId($baseColumnId)));
			foreach ($baseTableObj->columns as $column) {
				if ($column->isForeignKey && isset($baseTableObj->foreignKeys[$column->name])) {
					if ($baseTableObj->foreignKeys[$column->name][0] == $expandTableName) {
						$result = true;
					}
				}
			}
			$cacheValue = $result;
			TDSessionData::setCache("checkColumnhasBelongRelation" . $baseColumnId . "_" . $expandColumnId, $cacheValue);
		}
		return $cacheValue;
	}

}
