<?php

class TDTable {

	public static $too_menu = 'too_menu';
	public static $too_menu_items = 'too_menu_items';
	public static $too_role = 'too_role';
	public static $too_user = 'too_user';
	public static $too_table_collection = 'too_table_collection';
	public static $too_table_column = 'too_table_column';
	public static $too_table_column_class = 'too_table_column_class';
	public static $too_table_column_input = 'too_table_column_input';
	public static $too_module = 'too_module';
	public static $too_module_gridview = 'too_module_gridview';
	public static $too_module_formEdit = 'too_module_formedit';
	public static $too_module_formmodule = 'too_module_formmodule';
	public static $too_module_gridview_expbtn = 'too_module_gridview_expbtn';
	public static $sys_table_ids = "9,11,12,13,20,21,26,28,68,69,186,676,833,931"; //16, 系统用户
	public static $too_login_log = "too_login_log";
	public static $too_session= "too_session";

	public static function getTableObj($tableName, $loadCustomColumn = TRUE, $refresh = false) {
		$tableBase = null;
		$db = TDModelDAO::getDB($tableName);
		$tables = array($db->getSchema()->getTable($tableName,$db->schemaCachingDuration !== 0 || $refresh));
		if (isset($tables[0]) && !empty($tables[0])) {
			$tableBase = $tables[0];
		}

		if (is_null($tableBase)) {
			echo 'sys tableName ' . $tableName . ' is not in db';
			exit;
		}

		$table = clone $tableBase;
		//custom columns 
		if ($loadCustomColumn) {
			$duplicateColumn = null;
			foreach ($table->columns as $tmpColumn) {
				$duplicateColumn = clone $tmpColumn;
				break;
			}
			$columnNameItems = TDTableColumn::getCustomColumnNames($tableName);
			foreach ($columnNameItems as $columnName) {
				if (!is_null($duplicateColumn)) {
					$newColumn = clone $duplicateColumn;
					$newColumn->name = $columnName;
					$newColumn->rawName = '`' . $columnName . '`';
					$newColumn->allowNull = 1;
					$newColumn->dbType = 'varchar(255)';
					$newColumn->type = 'string';
					$newColumn->defaultValue = '';
					$newColumn->size = '255';
					$newColumn->precision = '255';
					$newColumn->isPrimaryKey = '';
					$newColumn->isForeignKey = '';
					$newColumn->autoIncrement = '';
					$newColumn->comment = TDTableColumn::$COLUMN_TYPE_COMMENT_CUSTOM_STR;
					$table->columns[$columnName] = $newColumn;
				}
			}
		}
		return $table;
	}

	private static function setColumnInputType($columnRow,$dbType,$comment='') {
		$label = $comment;
		$table_column_input_id  = 1;//input 
		$static_array = "";
		$comAr = explode(";",$comment);
		if(count($comAr) == 2) {
			$label = $comAr[0];
			if(!empty($comAr[1])) {
				if(strpos($comAr[1],"[") == false && strpos($comAr[1],"=") !== false && strpos($comAr[1],"]") !== false) {
					$array= explode("],",$comAr[1]);
					if(count($array)== 2) {
						$table_column_input_id = 16; //radio
					} else {
						$table_column_input_id = 17; //select	
					}
					$static_array = '$VAL="'.$comAr[1].'";';
				}
			}	
		} else if((strpos($dbType,"int") !== false || in_array($dbType,array("date","datetime"))) && ($columnRow->name == "create_at" || strpos($comment,"创建时间") !== false || strpos($comment,"添加时间") !== false)) {
			$table_column_input_id = 6;//createtime	
		} else if((strpos($dbType,"int") !== false || in_array($dbType,array("date","datetime"))) && ($columnRow->name == "update_at" || strpos($comment,"更新时间") !== false || strpos($comment,"修改时间") !== false)) {
			$table_column_input_id = 20;//updatetime	
		} else if(strpos($dbType,"int") !== false && strpos($comment,"时间") !== false) {
			$table_column_input_id = 9;//datetime	
		} else if($dbType == "date") {
			$table_column_input_id = 8;//date	
		} else if($dbType == "datetime") {
			$table_column_input_id = 9;//datetime	
		} else if($dbType == "text") {
			$table_column_input_id = 19;//text
		} else if($columnRow->name == "order" || $columnRow->name == "sort") {
			$table_column_input_id = 13;//order
		} else if(strpos($columnRow->name,"_id") !== false) {
			$tbName = Yii::app()->params["cus_table_prefix"].substr($columnRow->name,0,strrpos($columnRow->name,"_id")); 	
			$isTb = TDSessionData::checkIsTableName($tbName);
			if($isTb) {
				$table_column_input_id = 12;//foreignkey 		
				$columnRow->map_table_collection_id = TDTableColumn::getTableCollectionID($tbName);
				$columnRow->value_laddercolumn = TDTableColumn::getColumnIdByTableAndColumnName($tbName,"id");
				$columnRow->append_laddercolumn = null;
				if(empty($columnRow->map_table_collection_id)) {
					echo '$tbName = '.$tbName.'  value_laddercolumn='.$columnRow->value_laddercolumn.'    $columnRow->name='.$columnRow->name;
					exit;
				}
			}
		} else if($columnRow->name == "is_del") {
			$table_column_input_id = 36;//is_del 
		}
		$columnRow->label = empty($label) ? $columnRow->name : $label;
		$columnRow->table_column_input_id = $table_column_input_id;
		$columnRow->static_array = $static_array;	
	}

	public static function synchronizeDBWithSys($tableCollectionId = 0, $tableName = '') {
		$tdResult = new TDOperateResult();
		if (empty($tableCollectionId) && empty($tableName)) {
			$tdResult->setMsg(TDLanguage::$common_tip_msg_1);
			return $tdResult;
		}
		if (!empty($tableCollectionId)) {
			$tableName = TDCommon::getValueBySQL("select `table` from " . TDTable::$too_table_collection . " where `id`=" . $tableCollectionId);
		}
		if (empty($tableName)) {
			$tdResult->setMsg(TDLanguage::$common_tip_msg_2);
			return $tdResult;
		}
		$tbRow = TDModelDAO::getModel(TDTable::$too_table_collection)->find('`table`=\'' . $tableName . '\'');
		$isANewTable = false;
		if (empty($tbRow)) {
			$isANewTable = true;
			$tbRow = TDModelDAO::getModel(TDTable::$too_table_collection);
			$tbRow->table = $tableName;
			$tbRow->name = $tableName;
			$tbRow->type = 0;
			$tbRow->engine = TDTable::getTableEngineId($tableName);
			$tbRow->lastupdate_set = date("Y-m-d H:i:s");
			$tbRow->save();
		}
		$tableCollectionId = $tbRow->primaryKey;
		$updatedColumnArray = array();
		$tbColTbObj = TDTable::getTableObj($tableName, !$isANewTable);
		//echo "<pre>";
		$columnOrderIndex = 1;
		foreach ($tbColTbObj->columns as $columnStr => $columnObj) {
			$columnRow = TDModelDAO::getModel(TDTable::$too_table_column)->find('`table_collection_id`=\'' . $tbRow->primaryKey . '\' and `name`=\'' . $columnObj->name . '\' ');
			if (empty($columnRow)) {
				$columnRow = TDModelDAO::getModel(TDTable::$too_table_column);
				$columnRow->table_collection_id = $tbRow->primaryKey;
				$columnRow->name = $columnObj->name;
				//$tmpStr = explode(";",$columnObj->comment);
				//if(!empty($tmpStr[0])) { $columnRow->label = $tmpStr[0];	
				/*
				if (!empty($columnObj->comment)) {
					$columnRow->label = $columnObj->comment;
				} else {
					$columnRow->label = $columnObj->name;
				}
				//判断输入类型
				*/
				self::setColumnInputType($columnRow,$columnObj->dbType,$columnObj->comment);
			}
			$columnRow->allow_empty = $columnObj->allowNull ? 1 : 0;
			$columnRow->db_type = $columnObj->dbType;
			$columnRow->default_value = $columnObj->defaultValue === '' ? 'Empty String' : $columnObj->defaultValue;
			$columnRow->db_size = $columnObj->size;
			$columnRow->db_precision = $columnObj->precision;
			$columnRow->is_primary_key = $columnObj->isPrimaryKey ? 1 : 0;
			$columnRow->auto_increment = $columnObj->autoIncrement ? 1 : 0;
			$columnRow->foreign_table_column_id = null;
			$columnRow->order = ($columnOrderIndex * 10);
			$columnOrderIndex++;
			if(!$columnRow->save()) { echo "<pre> too_table_column "; print_r($columnRow->errors); exit; }
			$updatedColumnArray[] = $columnRow->primaryKey;
		}
		//mysql数据表中未存在的多余字段删除掉
		$deleteColumnIdArray = array();
		$sysColumnRows = TDModelDAO::getModel(TDTable::$too_table_column)->findAll("`table_collection_id`=" . $tbRow->primaryKey . " and `column_type`=0");
		foreach ($sysColumnRows as $sysCrow) {
			if (!in_array($sysCrow->id, $updatedColumnArray)) {
				$deleteColumnIdArray[] = $sysCrow->id;
				$sysCrow->delete();
			}
		}
		if (!empty($deleteColumnIdArray)) {
			$deleteidStr = implode(",", $deleteColumnIdArray);
			TDModelDAO::getModel(TDTable::$too_module_gridview)->deleteAll("table_column_id in (" . $deleteidStr . ") or belong_to_column_id in (" . $deleteidStr . ")");
			TDModelDAO::getModel(TDTable::$too_module_formEdit)->deleteAll("table_column_id in (" . $deleteidStr . ") or belong_to_column_id in (" . $deleteidStr . ")");
		}
		//删除模块中引用的无效字段
		$moduleRows = TDModelDAO::queryAll(TDTable::$too_module,"table_collection_id=" . $tableCollectionId,"id");
		foreach ($moduleRows as $mdRow) {
			$moduleId = $mdRow["id"];
			$griviewRows = TDModelDAO::getModel(TDTable::$too_module_gridview)->findAll("module_id=" . $moduleId);
			foreach ($griviewRows as $grRow) {
				//验证是否存在
				$isVabs = TDTableColumn::checkColumnIdIsValid($grRow->table_column_id);
				$isVabBe = $isVabs && !empty($grRow->belong_to_column_id) ? TDTableColumn::checkColumnIdIsValid($grRow->belong_to_column_id) : true;
				if (!$isVabs || !$isVabBe) {
					$grRow->delete();
				}
			}
			$editRows = TDModelDAO::getModel(TDTable::$too_module_formEdit)->findAll("module_id=" . $moduleId);
			foreach ($editRows as $editRow) {
				//验证是否存在
				$isVabs = TDTableColumn::checkColumnIdIsValid($editRow->table_column_id);
				$isVabBe = $isVabs && !empty($editRow->belong_to_column_id) ? TDTableColumn::checkColumnIdIsValid($editRow->belong_to_column_id) : true;
				if (!$isVabs || !$isVabBe) {
					$editRow->delete();
				}
			}
		}
		//补充外键关联字段
		/*
		$table = TDTable::getTableObj($tableName, !$isANewTable);
		$foreignKeys = $table->foreignKeys;
		$columnModel = TDModelDAO::getModel(TDTable::$too_table_column);
		$columnModel->updateAll(array("foreign_table_column_id" => null), '`table_collection_id`=\'' . $tbRow->primaryKey . '\'');
		foreach ($foreignKeys as $columnName => $foreigData) {
			$fortbId = TDTableColumn::getTableCollectionID($foreigData[0]);
			$foreigkeyRow = $columnModel->find(array('select' => '`id`', 'condition' => '`table_collection_id`=\'' . $fortbId . '\' and `name`=\'' . $foreigData[1] . '\' '));
			if (!empty($foreigkeyRow)) {
				$foreignColumnId = $foreigkeyRow->primaryKey;
				$baseColumnRow = $columnModel->find('`table_collection_id`=\'' . $tbRow->primaryKey . '\' and `name`=\'' . $columnName . '\'');
				if (!empty($baseColumnRow)) {
					$baseColumnRow->foreign_table_column_id = $foreignColumnId;
					$baseColumnRow->save();
				}
			}
		}
		*/
		//表数据刷新排序
		Fie_order::reOrderTable($tableCollectionId);
		/*2018-05-05去掉自动生成模块，因为创建菜单项的时候会自动生成模块
		$tbItem = $tableName;
		$moduleId = TDModule::getModuleIdByTableName($tbItem);
		if (empty($moduleId)) { //自动生成管理模块功能暂时不使用 && false
			//自动生成模块管理
			$tableId = TDTableColumn::getTableCollectionID($tbItem);
			$moduleModule = TDModelDAO::getModel(TDTable::$too_module);
			$moduleModule->name = $tbItem;
			$moduleModule->table_collection_id = $tableId;
			$moduleModule->allow_actions = "add,update,delete,view";
			$moduleModule->gridview_default_condition = self::getModuleDefaultCondition($tableName);	
			$moduleModule->form_save_php_code = "";
			if(!$moduleModule->save()) {
				echo "<pre> too_module "; print_r($moduleModule->errors); exit;	
			}
			$moduleId = $moduleModule->primaryKey;
			$columnRows = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=" . $tableId,"`id`,`name`,`auto_increment`");
			$rowIndex = 2;
			foreach ($columnRows as $crow) {
				$clumnId = $crow["id"];
				//自动生成gridview模块
				$gridview = TDModelDAO::getModel(TDTable::$too_module_gridview);
				$gridview->module_id = $moduleId;
				$gridview->table_column_id = $clumnId;
				$gridview->order = $rowIndex * 10;
				if(!$gridview->save()) {
					echo "<pre> too_module_gridview "; print_r($gridview->errors); exit;
				}
				if ($crow["name"] == "id" || $crow["auto_increment"] == 1) {
					continue;
				}
				//自动生成editform模块
				$editform = TDModelDAO::getModel(TDTable::$too_module_formEdit);
				$editform->module_id = $moduleId;
				$editform->table_column_id = $clumnId;
				$editform->order = $rowIndex * 10;
				$rowIndex++;
				if(!$editform->save()) {
					echo "<pre> too_module_formEdit "; print_r($editform->errors); exit;	
				}
			}
		}
		*/
		$tdResult->setResult(true);
		return $tdResult;
	}

	public static function geteOrderStr($tableName) {
		$result = TDSessionData::getCache("geteOrderStr_" . $tableName);
		if ($result === false) {
			$table = TDTable::getTableObj($tableName);
			foreach ($table->columns as $column) {
				$inputType = TDTableColumn::getInputTypeByColumnId(TDTableColumn::getColumnIdByTableAndColumnName($tableName, $column->name), false);
				if ($inputType == 'order') {
					$result = $column->rawName;
				}
			}
			$result = empty($result) ? (is_array($table->primaryKey) ? implode(',', $table->primaryKey) : $table->primaryKey) : $result;
			TDSessionData::setCache("geteOrderStr_" . $tableName, $result);
		}
		return $result;
	}

	public static function getTableLabelName($tbNameORId) {
		if (is_numeric($tbNameORId)) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_collection, $tbNameORId,  '`name`'); } else { 
		return TDModelDAO::queryScalar(TDTable::$too_table_collection, '`table`=\'' . $tbNameORId . '\'', '`name`'); }
	}

	public static function getAdminModelLabelName($tbAdminId) {
		if (isset($_GET["mitemId"]) && !empty($_GET["mitemId"])) {
			$name = TDModelDAO::queryScalarByPk(TDTable::$too_menu_items, $_GET["mitemId"],"name");
			if (!empty($name)) { return $name; }
		}
		$name = TDModelDAO::queryScalarByPk(TDTable::$too_module,$tbAdminId,"name");
		if (!empty($name)) { return $name; }
		return null;
	}

	public static function getTableDBName($tbCollid) { return TDTableColumn::getTableDBName($tbCollid); }
	public static function getTableColumnId($tableId, $columnName) { return TDTableColumn::getColumnIdByTableAndColumnName(self::getTableDBName($tableId),$columnName); }

	public static function checkHasPrimaryKey($tableName, $columnName) {
		$row = TDModelDAO::getDB($tableName)->createCommand("select * from information_schema.KEY_COLUMN_USAGE where CONSTRAINT_SCHEMA='" .
						TDDataDAO::getDBName() . "' and CONSTRAINT_NAME='PRIMARY' and TABLE_NAME='" . $tableName . "' and COLUMN_NAME='" . $columnName . "'")->queryRow();
		if (!empty($row)) {
			return true;
		} else {
			return false;
		}
	}

	public static function dropIndexKey($tableName, $columnName) {
		$sql = '';
		$row = TDModelDAO::getDB($tableName)->createCommand("select * from information_schema.STATISTICS where TABLE_SCHEMA='" .
						TDDataDAO::getDBName() . "' and TABLE_NAME='" . $tableName . "' and COLUMN_NAME='" . $columnName . "' and INDEX_NAME !='PRIMARY'")->queryRow();
		if (!empty($row)) {
			$sql .= 'ALTER TABLE `' . $row["TABLE_SCHEMA"] . '`.`' . $tableName . '` DROP INDEX `' . $row["INDEX_NAME"] . '`;';
		}
		return $sql;
	}

	public static function dropForeignKey($tableName, $columnName) {
		$row = TDModelDAO::getDB($tableName)->createCommand("select * from information_schema.KEY_COLUMN_USAGE where CONSTRAINT_SCHEMA='" .
						TDDataDAO::getDBName() . "' and  TABLE_NAME='" . $tableName . "' and COLUMN_NAME='" . $columnName .
						"' and CONSTRAINT_NAME !='PRIMARY' and REFERENCED_TABLE_NAME != ''")->queryRow();
		$sql = '';
		if (!empty($row)) {
			$sql .= 'ALTER TABLE `' . $row["CONSTRAINT_SCHEMA"] . '`.`' . $tableName . '` DROP FOREIGN KEY `' . $row["CONSTRAINT_NAME"] . '`;';
		}
		return $sql;
	}

	public static function getTableEngineId($tableName) {
		$engineArray = FieldRule::getStaticArray(TDTableColumn::getColumnIdByTableAndColumnName(TDTable::$too_table_collection, "engine"));
		$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE " . $tableName)->query();
		foreach ($createtable as $row) {
			$str = $row['Create Table'];
			$str = explode("ENGINE=", $str);
			$str = explode(" ", $str[1]);
			$eg = trim($str[0]);
			foreach ($engineArray as $enginId => $engineStr) {
				if (strtolower($eg) == strtolower($engineStr))
					return $enginId;
			}
		}
	}

	public static function getTableComment($tableName) {
		$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE " . $tableName)->query();
		foreach ($createtable as $row) {
			$str = $row['Create Table'];
			$str = explode("ENGINE=", $str);
			$str = explode("COMMENT=", $str[1]);
			if(count($str) == 2 && !empty($str[1])) {
				return str_replace("'","",$str[1]);
			} else {
				return "";
			}
		}
	}

	public static function getDataBaseAllTables() { $dbTables = []; $rows  = TDModelDAO::getCommDB()->createCommand("show tables")->queryAll();
	foreach($rows as $item) { foreach($item as  $key => $table) { $dbTables[] = $table; } } return $dbTables; }
	public static function getCommTableConllectionAllTables() { $result = []; $sysTables = TDModelDAO::queryAll(TDTable::$too_table_collection,'`is_systable`=0','`table`'); 
	foreach ($sysTables as $table) { $result[] = $table['table']; } return $result; }

	public static function refreshTables() {
		$dbTables = TDTable::getDataBaseAllTables();
		$sysTables = TDTable::getCommTableConllectionAllTables();
		foreach ($sysTables as $table) {
			if (!in_array($table, $dbTables)) {
				echo  "sys table 【".$table. "】 not in db<br/>";
			}
		}	
		foreach ($dbTables as $table) {
			if (!in_array($table, $sysTables)) {
				echo  "db table 【".$table. "】 not in sys<br/>";
			}
		}
	}
	
}
