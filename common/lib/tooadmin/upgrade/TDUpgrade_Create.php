<?php

class TDUpgrade_Create {

	public $path_table_sql = "upgradebag/table_sql_";	
	public $path_column_sql = "upgradebag/column_sql_";	
	public $path_column_class_sql = "upgradebag/column_class_sql_";	
	public $path_module_sql = "upgradebag/module_sql_";	
	public $path_module_columns_sql = "upgradebag/module_columns_sql_";	
	public $path_menu_sql = "upgradebag/menu_sql_";
	public $replace_tableidstr = "rep___table_id";
	public $replace_moduleidstr = "rep___module_id";
	public $columnid_decode = "columnid_decode";
	public $columnid_decode_begin = "col_decode_begin___";
	public $columnid_decode_tbcol_split = "---";
	public $columnid_decode_end = "___col_decode_end";
	public $moduleid_decode = "moduleid_decode";
	public $moduleid_decode_begin = "mod_decode_begin___";
	public $moduleid_decode_tbcol_split = "---";
	public $moduleid_decode_end = "___mod_decode_end";

	public function createUpgradeBag($tableName) {
		$this->bakTableSQL($tableName);	
		$this->bakColumnSQL($tableName);
		$this->bakColumnClassSQL($tableName);
		$this->bakModuleSQL($tableName);
		$this->bakModuleColumnsSQL($tableName);
		$this->bakMenu($tableName);
	} 
	
	private function getTableRowsSQL($rows,$dbcolumns,$expColumns=array("id"),$replaceColumns=array()) {
		$sql = "";
		foreach($rows as $row) {
			$sql .= "insert into `".$row->tableName()."`(";
			$columnNameStrs = "";
			$columnValueStrs = "";
			foreach($dbcolumns as $columnStr => $columnObj) {
				$tmpStr = $columnObj->name; 
				if(in_array("id",$expColumns)) {
					continue;	
				}
				if(!empty($columnNameStrs)) { 
					$columnNameStrs .= ","; 
					$columnValueStrs .= ",";
				}
				$columnNameStrs .= "`".$tmpStr."`";
				if(isset($replaceColumns[$tmpStr])) {
					if($replaceColumns[$tmpStr] == $this->columnid_decode) {
						$tmpValue = $row->$tmpStr;	
						$tmpValueArry = split(",",$tmpValue);
						$insertNewValue = "";
						foreach($tmpValueArry as $tmpitem) {
							if(!empty($insertNewValue)) { $insertNewValue .= ","; }
							$insertNewValue .= $this->columnid_decode_begin.TDTableColumn::getColumnTableDBName($tmpitem);
							$insertNewValue .= $this->columnid_decode_tbcol_split.TDTableColumn::getColumnDBName($tmpitem);
							$insertNewValue .= $this->columnid_decode_end;
						}
						$columnValueStrs .= "'".$insertNewValue."'";
					} else if($replaceColumns[$tmpStr] == $this->moduleid_decode) {
						$tmpValue = $row->$tmpStr;	
						$tmpValueArry = split(",",$tmpValue);
						$insertNewValue = "";
						foreach($tmpValueArry as $tmpitem) {
							if(!empty($insertNewValue)) { $insertNewValue .= ","; }
							$insertNewValue .= $this->moduleid_decode_begin.TDModule::getModuleTableName($tmpitem);
							$tmpIndexNum = 1;
							$resultIndexNum = 1;
							$tmpModuleRows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id=".
							TDModule::getModuleTableId($tmpitem));
							foreach($tmpModuleRows as $tmpmdr) {
								if($tmpmdr->id == $tmpitem) { $resultIndexNum = $tmpIndexNum; break; }	
								$tmpIndexNum++;
							}
							$insertNewValue .= $this->moduleid_decode_tbcol_split.$resultIndexNum;
							$insertNewValue .= $this->moduleid_decode_end;
						}
						$columnValueStrs .= "'".$insertNewValue."'";
					} else {
						$columnValueStrs .= "'".$replaceColumns[$tmpStr]."'";
					}
				} else {
					$columnValueStrs .= "'".mysql_escape_string($row->$tmpStr)."'";
				}
			}	
			$sql .= $columnNameStrs.") values(".$columnValueStrs.");\n"; 
		}
		return $sql;
	}

	//替换column id
	private function replaceColumnId($sql) {
		$tmpSQL = $sql;
		$tmpSQLArray = split($this->columnid_decode_begin,$tmpSQL);
		$index = 1;
		foreach($tmpSQLArray as $item) {
			if($index == 1) { continue; }
			$tbcolstr = split($this->columnid_decode_end,$item);
			$tbcolstr = split($this->columnid_decode_tbcol_split,$tbcolstr[0]);
			$newcolumnId = TDTableColumn::getColumnIdByTableAndColumnName($tbcolstr[0],$tbcolstr[1]);	
			$sql = str_replace($this->columnid_decode_begin.$tbcolstr[0].$this->columnid_decode_tbcol_split.
			$tbcolstr[1].$this->columnid_decode_end,$newcolumnId,$sql);	
			$index++;
		}	
		return $sql;
	}

	//替换module id
	private function replaceModuleId($sql) {
		$tmpSQL = $sql;
		$tmpSQLArray = split($this->moduleid_decode_begin,$tmpSQL);
		$index = 1;
		foreach($tmpSQLArray as $item) {
			if($index == 1) { continue; }
			$tbIndstr = split($this->moduleid_decode_end,$item);
			$tbIndstr = split($this->moduleid_decode_tbcol_split,$tbIndstr[0]);
			$mdindex = 1;
			$resultmdid = 0;
			$mdrows = TDModelDAO::getModel(TDTable::$too_module)->findAll("table_collection_id=".TDTableColumn::getTableCollectionID($tbIndstr[0]));
			foreach($mdrows as $mdrow) {
				if($mdindex == $tbIndstr[1]) {
					$resultmdid = $mdrow->id;
					break;
				}
				$mdindex++;
			}
			$sql = str_replace($this->moduleid_decode_begin.$tbIndstr[0].$this->moduleid_decode_tbcol_split.
			$tbIndstr[1].$this->moduleid_decode_end,$resultmdid,$sql);	
			$index++;
		}	
		return $sql;
	}
	
	//备份数据表结构和数据内容
	private function bakTableSQL($tableName) {
		$tabledump = TDDataDAO::getBackupAtableSQL($tableName);
		$fileTxt = $tabledump;	
		$row = TDModelDAO::getModel(TDTable::$too_table_collection)->find("`table`='".$tableName."'");
		$sql = "insert into `".TDTable::$too_table_collection."`(`table`,`name`,`type`) ".
		"values('".$row->table."','".$row->name."','".$row->type."');"; 
		$fileTxt .= $sql;
		$dtf = new TDDataFiles();
		$fp = fopen($dtf->getFilePath($this->path_table_sql.$tableName.".too"), "w"); 
		fwrite($fp,$fileTxt); 
		fclose($fp); 
	}

	private function bakColumnSQL($tableName) {
		$rows = TDModelDAO::getModel(TDTable::$too_table_column)->findAll("`table_collection_id`=".TDTableColumn::getTableCollectionID($tableName));
		$dbcolumns = TDTable::getTableObj(TDTable::$too_table_column,false)->columns;
		$expColumns = array("id");
		$replaceColumns = array("table_collection_id"=>$this->replace_tableidstr);	
		$sql = $this->getTableRowsSQL($rows, $dbcolumns, $expColumns, $replaceColumns);
		$dtf = new TDDataFiles();
		$fp = fopen($dtf->getFilePath($this->path_column_sql.$tableName.".too"), "w"); 
		fwrite($fp,$sql); 
		fclose($fp); 
	}

	private function bakColumnClassSQL($tableName) {
		$rows = TDModelDAO::getModel(TDTable::$too_table_column_class)->findAll("`table_id`=".TDTableColumn::getTableCollectionID($tableName));
		$dbcolumns = TDTable::getTableObj(TDTable::$too_table_column_class,false)->columns;
		$expColumns = array("id");
		$replaceColumns = array("table_id"=>$this->replace_tableidstr);	
		$sql = $this->getTableRowsSQL($rows, $dbcolumns, $expColumns,$replaceColumns);
		$dtf = new TDDataFiles();
		$fp = fopen($dtf->getFilePath($this->path_column_class_sql.$tableName.".too"), "w"); 
		fwrite($fp,$sql); 
		fclose($fp); 
	}
	
	private function bakModuleSQL($tableName) {
		$rows = TDModelDAO::getModel(TDTable::$too_module)->findAll("`table_collection_id`=".TDTableColumn::getTableCollectionID($tableName));
		$dbcolumns = TDTable::getTableObj(TDTable::$too_module,false)->columns;
		$expColumns = array("id");
		$replaceColumns = array("table_collection_id"=>$this->replace_tableidstr);	
		$sql = $this->getTableRowsSQL($rows,$dbcolumns,$expColumns,$replaceColumns);
		$dtf = new TDDataFiles();
		$fp = fopen($dtf->getFilePath($this->path_module_sql.$tableName.".too"), "w"); 
		fwrite($fp,$sql); 
		fclose($fp); 	
	}

	private function bakModuleColumnsSQL($tableName) {
		$dtf = new TDDataFiles();
		$modrows = TDModelDAO::getModel(TDTable::$too_module)->findAll("`table_collection_id`=".TDTableColumn::getTableCollectionID($tableName));
		$index = 1;
		$sql = "";
		foreach($modrows as $modrow) {
			$rows = TDModelDAO::getModel(TDTable::$too_module_formEdit)->findAll("`module_id`=".$modrow->id);
			$dbcolumns = TDTable::getTableObj(TDTable::$too_module_formEdit,false)->columns;
			$expColumns = array("id");
			$replaceColumns = array("module_id"=>$this->replace_moduleidstr."_".$index."_",
			"table_column_id"=>$this->columnid_decode,"belong_to_column_id"=>$this->columnid_decode,
			"belong_order_column_ids"=>$this->columnid_decode);	
			$sql .= $this->getTableRowsSQL($rows,$dbcolumns,$expColumns,$replaceColumns);	
		
			$rows = TDModelDAO::getModel(TDTable::$too_module_formmodule)->findAll("`form_module_id`=".$modrow->id);
			$dbcolumns = TDTable::getTableObj(TDTable::$too_module_formmodule,false)->columns;
			$expColumns = array("id");
			$replaceColumns = array("form_module_id"=>$this->replace_moduleidstr."_".$index."_","ntable_module_id"=> $this->moduleid_decode);
			$sql .= $this->getTableRowsSQL($rows,$dbcolumns,$expColumns,$replaceColumns);	
			
			$rows = TDModelDAO::getModel(TDTable::$too_module_gridview)->findAll("`module_id`=".$modrow->id);
			$dbcolumns = TDTable::getTableObj(TDTable::$too_module_gridview,false)->columns;
			$expColumns = array("id");
			$replaceColumns = array("module_id"=>$this->replace_moduleidstr."_".$index."_",
			"table_column_id"=>$this->columnid_decode,"belong_to_column_id"=>$this->columnid_decode,
			"belong_order_column_ids"=>$this->columnid_decode);	
			$sql .= $this->getTableRowsSQL($rows,$dbcolumns,$expColumns,$replaceColumns);

			$index++;
		}
		$fp = fopen($dtf->getFilePath($this->path_module_columns_sql.$tableName.".too"), "w"); 
		fwrite($fp,$sql); 
		fclose($fp);	
	}

	private function bakMenu($tableName) {
		$moduleIdArray = array();
		$moduleRows = TDModelDAO::getModel(TDTable::$too_module)->findAll("`table_collection_id`=".TDTableColumn::getTableCollectionID($tableName));
		foreach($moduleRows as $mdrow) {
			$moduleIdArray[] = $mdrow->id;	
		}
		$rows = TDModelDAO::getModel(TDTable::$too_menu)->findAll("`module_id` in (".(empty($moduleIdArray) ? "-1" : implode(",",$moduleIdArray)).")");
		$dbcolumns = TDTable::getTableObj(TDTable::$too_menu,false)->columns;
		$expColumns = array("id");
		$replaceColumns = array("module_id"=>$this->moduleid_decode);	
		$sql = $this->getTableRowsSQL($rows,$dbcolumns,$expColumns,$replaceColumns);
		$dtf = new TDDataFiles();
		$fp = fopen($dtf->getFilePath($this->path_menu_sql.$tableName.".too"), "w"); 
		fwrite($fp,$sql); 
		fclose($fp); 	
	}
	
	public function excuteUpgradeBag($tableName) {
		$dtf = new TDDataFiles();
		//判断是否允许升级
		$checkQuery = TDModelDAO::getModel(TDTable::$too_table_collection)->count("`table`='".$tableName."'");
		if($checkQuery > 0) { echo '升级中断...'.$tableName . '已经在tooadmin系统表里存在';exit; }
		if(TDSessionData::checkIsTableName($tableName)) { echo '升级中断...'.$tableName . '已经在数据库里存在';exit; }
		//执行创建表结构
		$sql = file_get_contents($dtf->getFilePath($this->path_table_sql.$tableName.".too"));
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();
		$tableRow = TDModelDAO::getModel(TDTable::$too_table_collection)->find("`table`='".$tableName."'");
		$newTableId = $tableRow->id;
		if(empty($newTableId)) { echo "升级中断...new table id 为空";exit; }
		//执行字段分类 
		$sql = file_get_contents($dtf->getFilePath($this->path_column_class_sql.$tableName.".too"));
		$sql = str_replace($this->replace_tableidstr,$newTableId,$sql);
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();		
		//执行创建字段数据	
		$sql = file_get_contents($dtf->getFilePath($this->path_column_sql.$tableName.".too"));
		$sql = str_replace($this->replace_tableidstr,$newTableId,$sql);
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();	
		//执行创建模块	
		$sql = file_get_contents($dtf->getFilePath($this->path_module_sql.$tableName.".too"));
		$sql = str_replace($this->replace_tableidstr,$newTableId,$sql);
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();	
		//执行模块引用的数据
		$moduleRows = TDModelDAO::getModel(TDTable::$too_module)->findAll("`table_collection_id`=".$newTableId);
		$sql = file_get_contents($dtf->getFilePath($this->path_module_columns_sql.$tableName.".too"));
		$index = 1;
		foreach($moduleRows as $mdrow) {
			$sql = str_replace($this->replace_moduleidstr."_".$index."_",$mdrow->id,$sql);	
			$index++;
		}	
		$sql = $this->replaceColumnId($sql);
		$sql = $this->replaceModuleId($sql);
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();	
		//执行菜单创建
		$sql = file_get_contents($dtf->getFilePath($this->path_menu_sql.$tableName.".too"));
		$sql = $this->replaceModuleId($sql);
		if(!empty($sql))
			TDModelDAO::getDB($tableName)->createCommand($sql)->execute();
		
		
		
	}
}
