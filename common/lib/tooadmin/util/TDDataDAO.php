<?php

class TDDataDAO {

	public static function queryToMap($params) { $table = $params['table']; $key = $params['key']; $value = $params['value']; 
	$result = array(); $rows = TDModelDAO::queryAll($table,"",'`' . $key . '`,`' . $value . '`'); foreach ($rows as $row) { $result[$row[$key]] = $row[$value]; } return $result; }

	public static function queryValue($params, $queryKeyValue) {
		$result = '';
		$key = $params['key'];
		$value = $params['value'];
		$row = TDModelDAO::queryRowByCondtion($params['table'], '`' . $key . '`=\'' . $queryKeyValue . '\' ');
		if (!empty($row))
			$result = isset($row[$value]) ? $row[$value] : "";
		return $result;
	}

	public static function getPidArray($currentPid, $tableName, $pidColumn, $idColumn) {
		$result = array();
		$rows = TDModelDAO::queryAll($tableName,'`' . $idColumn . '`=' . intval($currentPid),'`' . $pidColumn . '`');
		foreach ($rows as $row) {
			$result[] = $row[$pidColumn];
			$pidArray = self::getPidArray($row[$pidColumn], $tableName, $pidColumn, $idColumn);
			if (!empty($pidArray)) {
				$result = array_merge($result, $pidArray);
			}
		}
		return $result;
	}

	public static function getPidTreeArray($fieldName, $currentId, $currentPid, $tableName, $pidColumn, $idColumn, $strColumn, $pid = 0, $mapCondition = '') {
		//当前节点是否有子节点，而且有子节点的限制，则限制所选的节点必须为同一个层级的节点
		$curIsHasChilds = TDModelDAO::queryScalar($tableName,'`'.$pidColumn.'`='.$currentId,'count(1)') > 0 ? true : false;
		if($pid > 0) {
			//树形节点数限制
			$maxNode = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,TDTableColumn::getColumnIdByTableAndColumnName($tableName,$pidColumn),"tree_node_max");
			if(!empty($maxNode) && $maxNode > 0) {
				$nodeGetCount = 0;
				$nodePidCondition = '`'.$pidColumn.'`=0';
				$checkIds = [];
				while($nodeGetCount < $maxNode) {
					$checkRwos = TDModelDAO::queryAll($tableName,$nodePidCondition ,'`'.$idColumn.'`,`'.$pidColumn.'`'); 
					$tmpIds = [];
					foreach($checkRwos as $checkRow) {
						$checkIds[] = $checkRow[$idColumn];
						$tmpIds[] = $checkRow[$idColumn];
					}
					if($curIsHasChilds && in_array($currentPid, $tmpIds)) {
						return[];	
					}
					$nodeGetCount++;
					if(count($tmpIds)) { break; }
					$nodePidCondition = '`'.$pidColumn.'` in ('.  implode(',',$tmpIds).')';
				}
				if(!in_array($pid,$checkIds)) {
					return [];
				}
			}
		}
		$result = array();
		if ($pid == 0 && !$curIsHasChilds) {
			$tmp = array('text' => '&nbsp;&nbsp;<label class="radio"><div class="radio">
			<span class=""><input type="radio"  value="0"  ' . (empty($currentPid) ? "checked='checked'" : '') . ' 
			name="' . $fieldName . '" style="opacity: 0;"></span>
			</div>' . TDLanguage::$nopid . '</label>');
			$result[] = $tmp;	
		}
		$orderInputTypeColumn = TDTable::geteOrderStr($tableName);
		if (!empty($orderInputTypeColumn)) {
			$orderInputTypeColumn = " order by " . $orderInputTypeColumn;
		}
		$rows = TDModelDAO::queryAll($tableName, '`' . $pidColumn . '`=' .intval($pid) . (!empty($mapCondition) ? ' and ' . $mapCondition : '') 
		. $orderInputTypeColumn, '`' . $idColumn . '`,`' . $strColumn . '`,`'.$pidColumn.'`');
		$myAllPidArray = self::getPidArray($currentPid, $tableName, $pidColumn, $idColumn);
		
		foreach ($rows as $row) {
			if ($row[$idColumn] == $currentId)
				continue;
			$tmp = array('text' => '&nbsp;&nbsp;<label class="radio"><div class="radio">
			<span class=""><input type="radio" ' . ($row[$idColumn] == $currentPid ? "checked='checked'" : '') . ' value="' . $row[$idColumn] . '"  
			name="' . $fieldName . '" style="opacity: 0;"></span>
			</div>' . $row[$strColumn] . '</label>', 'expanded' => in_array($row[$idColumn],$myAllPidArray));
			$childArray = self::getPidTreeArray($fieldName, $currentId, $currentPid, $tableName, $pidColumn, $idColumn, $strColumn, $row[$idColumn], $mapCondition);
			if (!empty($childArray)) {
				$tmp = TDCommon::array_smerge($tmp, array('children' => $childArray));
			}
			$result[] = $tmp;	
		}
		return $result;
	}

	public static function deleteARow($tableName, $pkId, $moduleId = 0) {
		//如果只是修改状态，非物理删除则只更新删除的状态
		$delColumns = TDModelDAO::queryAll(TDTable::$too_table_column, "table_collection_id=".TDTableColumn::getTableCollectionID($tableName)." and table_column_input_id=36", "name");
		if(!empty($delColumns)) {
			$model = TDModelDAO::getModel($tableName,$pkId);
			foreach($delColumns as $delColumn) {
				$delName = $delColumn["name"];
				$model->$delName = 1;
			}
			if($model->save()) {
				return true;
			} else {
				return false;
			}
		}

		//判断是否存在PID,存在则删除其下的子数据
		$pidColumns = TDModelDAO::queryAll(TDTable::$too_table_column, "table_collection_id=".TDTableColumn::getTableCollectionID($tableName)." and table_column_input_id=15", "name");
		$pidColumnStr = "";
		foreach($pidColumns as $pidColumn) {
			$pidColumnStr = $pidColumn["name"];	
		}
		if(!empty($pidColumnStr)) {
			$childRows = TDModelDAO::queryAll($tableName, "`".$pidColumnStr."`=".$pkId,"id");
			foreach($childRows as $childrow) {
				self::deleteARow($tableName,$childrow["id"],$moduleId);
			}
		}
		$model = TDModelDAO::getModel($tableName, $pkId);
		if (!empty($moduleId)) {
			$moduleRow = TDModelDAO::queryRowByPk(TDTable::$too_module, $moduleId);
			$begin = TDModelDAO::getDB(TDTable::$too_module)->beginTransaction();
			if (!empty($moduleRow) && !empty($moduleRow["before_delete"])) {
				//$model 一定要设置model变量
				eval($moduleRow["before_delete"]);
			}
		}
		if(!empty($model)) {
			$model->delete();
		}
		if (!empty($moduleId)) {
			if (!empty($moduleRow) && !empty($moduleRow["after_delete"])) {
				//$model 一定要设置model变量
				eval($moduleRow["after_delete"]);
			}
			$begin->commit();
			if (!empty($moduleRow) && !empty($moduleRow["delete_after_commit"])) {
				//$model 一定要设置model变量
				eval($moduleRow["delete_after_commit"]);
			}
		}
		return true;
	}

	public static function getPidChildCount($currentPid, $tableName, $pidColumn) { return TDModelDAO::queryScalar($tableName, '`' . $pidColumn . '`=' . intval($currentPid), 'count(1)'); }
	public static function getUserInfoStr($userId = null) { return !empty($userId) ? TDModelDAO::queryScalarByPk(TDTable::$too_user,$userId,'`nickname`') : ""; }

	public static function getUserMap() {
		$result = array();
		$rows = TDModelDAO::queryAll(TDTable::$too_user,"", '`id`,`nickname`');
		foreach ($rows as $row) {
			$result[$row['id']] = $row['nickname'];
		}
		return $result;
	}

	public static function executeSQL($sql, $db = null) {
		if (is_null($db)) {
			$db = TDModelDAO::getDBBySQL($sql); 
		}
		$db->createCommand($sql)->execute();
		return true;
	}

	public static function getDBTableCollection() {
		$result = array();
		$typeArray = FieldRule::getStaticArray(TDTableColumn::getColumnIdByTableAndColumnName(TDTable::$too_table_collection, 'type'));
		foreach ($typeArray as $key => $value) {
			$tbsArray = array();
			$rows = TDModelDAO::queryAll(TDTable::$too_table_collection, '`type`=' . $key, '`table`,`name`');
			foreach ($rows as $row) {
				$tbsArray[] = array('table' => $row['table'], 'name' => $row['name']);
			}
			$result[] = array(
				'type' => $key,
				'typeName' => $value,
				'tables' => $tbsArray,
			);
		}
		return $result;
	}

	public static function getDBName() {
		$str = TDModelDAO::getDB()->connectionString;
		$strArr = explode(";", $str);
		$dbname = "";
		foreach ($strArr as $item) {
			if (strpos($item, "dbname") !== false) {
				$tmp = explode("=", $item);
				$dbname = $tmp[1];
			}
		}
		return $dbname;
	}

	public static function getDBTypeArray() {
		$array = array('int', 'varchar', 'text', 'tinyint', 'double', 'decimal', 'date', 'time', 'datetime', 'bigint', 'longtext', 'bit', 'char',);
		$result = array();
		foreach ($array as $item) {
			$result[$item] = $item;
		}
		return $result;
	}

	public static function backupAllTableData($fileName) {
		$file = fopen($fileName, "w");
		$sql = "SET FOREIGN_KEY_CHECKS=0;\n";
		$dbTables = TDDataFiles::getDBTables();
		foreach ($dbTables as $tableName) {
			$sql .= self::getBackupAtableSQL($tableName);
		}
		$result = fwrite($file, $sql);
		fclose($file);
		if ($result !== false) {
			return true;
		} else {
			return false;
		}
	}

	public static function getBackupAtableSQL($tableName, $bakData = true) {
		$tabledump = "DROP TABLE IF EXISTS `$tableName`;\n";
		$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE $tableName")->query();
		foreach ($createtable as $row) {
			$tabledump .= $row['Create Table'] . ";\n\n";
		}
		$rows = TDModelDAO::queryAll($tableName);
		foreach ($rows as $row) {
			$tabledump .= "insert into `" . $tableName . "` values(";
			$columns = TDTable::getTableObj($tableName, false)->columns;
			$columns = array_keys($columns);
			$columnValues = '';
			foreach ($columns as $columnName) {
				if (!empty($columnValues)) {
					$columnValues .= ',';
				}
				$columnValues .= "'" . mysql_escape_string($row[$columnName]) . "'";
				///$columnValues .= '\''.$row->$columnName.'\'';//字符串转义单引号时会出问题
			}
			$tabledump .= $columnValues . ");\n";
		}
		$tabledump .= "\n";
		return $tabledump;
	}

	public static function runBackDataBase() {
		$start = time();
		$time = date('YmdHis');
		$fileName = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH, "common/dbbak") . TDDataDAO::getDBName() . "_sql_" . $time . ".sql";
		$zipFile = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH, "common/dbbak") . TDDataDAO::getDBName() . "_" . $time . ".zip";
		$result = TDDataDAO::backupAllTableData($fileName);
		if ($result) {
			$filezip = new TDFileZipUnZip();
			$filezip->create_zip(array($fileName), $zipFile, true);
			unlink($fileName);
			$end = time();
			echo "success run finish use Seconds = " . ($end - $start) . " =>  minute=" . round((($end - $start) / 60), 2);
		} else {
			$end = time();
			echo "fail run finish use Seconds = " . ($end - $start) . "  => minute=" . round((($end - $start) / 60), 2);
		}
	}

}
