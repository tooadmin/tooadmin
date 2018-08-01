<?php

class TDApiRequestData {

	public $use_simulation;//是否使用模拟IP
	public $analog_ip;//模拟IP,只有设置了该IP启动模拟IP开发模式，才能生效.对应的加密码即为模拟IP的加密码
	public $secret_key;//加密码
	public $function;//执行的函数 
	public $table;//操作的数据表
	public $pkId;//主键id数值
	public $select="*";//查询的字段
	public $condition;//查询条件
	public $columns;//字段，多个字段使用“,”隔开

	private function validateQuery($dbTablePermissionModel) {
		if(!$dbTablePermissionModel->is_active_dbpermission)return;
		$rows = TDModelDAO::queryAll(TDTable::$too_table_column,"`column_type`=0 and `table_collection_id`=".TDTableColumn::getTableCollectionID($this->table), "`id`,`name`"); 
		$tbColArray = array();
		foreach($rows as $row) { $tbColArray[$row["id"]] = $row['name']; }
		$queryColIds = array_intersect(array_keys($tbColArray),explode(",",$dbTablePermissionModel->dbp_query));	
		if(empty($queryColIds)) { throw new Exception("table ".$this->table." not query permission"); }	
		if($this->select == "*") { $this->select = "";	}
		$checkSelectArray = array();
		$asToColumn = array();
		if(!empty($this->select)) { 
			if(strpos($this->select," as ") !== false) {
				$sqlfunArray = array("avg","sum","count");
				$checkSelectArray = explode(",",str_replace("`","",trim($this->select))); 
				foreach($checkSelectArray as $cIndex => $item) {
					$checkSelectArray[$cIndex] = substr($item,0,strpos($item," as "));	
					foreach($sqlfunArray as $fun) {
						$checkSelectArray[$cIndex] = str_replace($fun."(","",$checkSelectArray[$cIndex]);	
						$checkSelectArray[$cIndex] = str_replace(strtoupper($fun)."(","",$checkSelectArray[$cIndex]);	
						$checkSelectArray[$cIndex] = str_replace(")","",$checkSelectArray[$cIndex]);	
					}
					$asToColumn[$checkSelectArray[$cIndex]] = $item;
				}	
			} else {
				$checkSelectArray = explode(",",str_replace("`","",trim($this->select))); $this->select = ""; 
			}
		}
		foreach($queryColIds as $colId) { 
			if(!empty($checkSelectArray)) { if(!in_array($tbColArray[$colId],$checkSelectArray)) { continue; } }
			if(!empty($this->select)) { $this->select .= ","; }
			if(!empty($asToColumn)) {
				$this->select .= isset($asToColumn[$tbColArray[$colId]]) ?  $asToColumn[$tbColArray[$colId]] : "`".$tbColArray[$colId]."`";
			} else {
				$this->select .=  "`".$tbColArray[$colId]."`";
			}
		}
	}

	private function validateAdd($dbTablePermissionModel) {
		if(!$dbTablePermissionModel->is_active_dbpermission)return;
		$rows = TDModelDAO::queryAll(TDTable::$too_table_column, "`column_type`=0 and `table_collection_id`=".TDTableColumn::getTableCollectionID($this->table), "`id`,`name`");
		$tbColArray = array();
		foreach($rows as $row) { $tbColArray[$row["id"]] = $row['name']; }
		$addColIds = array_intersect(array_keys($tbColArray),explode(",",$dbTablePermissionModel->dbp_add));	
		if(empty($addColIds)) { throw new Exception("table ".$this->table." not add permission"); }	
		///if(empty($this->columns)) { throw new Exception("table ".$this->table." add function not set data"); }, when upload files 
		 $checkAddArray = explode(",",str_replace("`","",$this->columns)); $this->columns = ""; 
		foreach($addColIds as $colId) { 
			if(!in_array($tbColArray[$colId],$checkAddArray)) { continue; } 
			$this->columns .= empty($this->columns) ? $tbColArray[$colId] : ",".$tbColArray[$colId];
		}				
	}

	private function validateUpdate($dbTablePermissionModel) {
		if(!$dbTablePermissionModel->is_active_dbpermission)return;
		$rows = TDModelDAO::queryAll(TDTable::$too_table_column, "`column_type`=0 and `table_collection_id`=".TDTableColumn::getTableCollectionID($this->table), "`id`,`name`");
		$tbColArray = array();
		foreach($rows as $row) { $tbColArray[$row["id"]] = $row['name']; }
		$updateColIds = array_intersect(array_keys($tbColArray),explode(",",$dbTablePermissionModel->dbp_update));	
		if(empty($updateColIds)) { throw new Exception("table ".$this->table." not update permission"); }	
		//if(empty($this->columns)) { throw new Exception("table ".$this->table." update function not set data"); } when upload files
		 $checkUpdateArray = explode(",",str_replace("`","",$this->columns)); $this->columns = ""; 
		foreach($updateColIds as $colId) { 
			if(!in_array($tbColArray[$colId],$checkUpdateArray)) { continue; } 
			$this->columns .= empty($this->columns) ? $tbColArray[$colId] : ",".$tbColArray[$colId];
		}				
	}

	private function validateDelete($dbTablePermissionModel) {
		if(!$dbTablePermissionModel->is_active_dbpermission)return;
		$tableId = TDTableColumn::getTableCollectionID($this->table);
		if(!in_array($tableId,  explode(",",$dbTablePermissionModel->dbp_delete))) {
			 throw new Exception("table ".$this->table." not delete permission");
		}
	}
	
	private function init($dbTablePermissionModel=null) {
		$this->use_simulation = strtolower($_POST['USE_SIMULATION']) == "true" ? true : false; 
		$this->analog_ip = $_POST['ANALOG_IP'];
		$this->secret_key = $_POST['SECRET_KEY']; 		
		$this->function = $_POST['FUNCTION'];
		if(!is_null($dbTablePermissionModel)) {
			switch ($this->function) {
				case "findByPk": $this->table = trim($_POST['TABLE']);	$this->pkId = trim($_POST['PKID']); $this->validateQuery($dbTablePermissionModel); break;
				case "find": $this->table = trim($_POST['TABLE']); $this->select = trim($_POST['SELECT']); 
					while(strpos($this->select," ,") !== false) { $this->select = str_replace(" ,",",",$this->select); }
 					while(strpos($this->select,", ") !== false) { $this->select = str_replace(", ",",",$this->select); }	
					$this->condition = trim($_POST['CONDITION']); $this->validateQuery($dbTablePermissionModel); break;
				case "findAll": $this->table = trim($_POST['TABLE']); $this->select = trim($_POST['SELECT']); 
					while(strpos($this->select," ,") !== false) { $this->select = str_replace(" ,",",",$this->select); }
 					while(strpos($this->select,", ") !== false) { $this->select = str_replace(", ",",",$this->select); }
					$this->condition = trim($_POST['CONDITION']); $this->validateQuery($dbTablePermissionModel); break; 	
				case "addRow": $this->table = trim($_POST['TABLE']); $this->columns = trim($_POST['COLUMNS']); $this->validateAdd($dbTablePermissionModel); break;
				case "updateRow": $this->table = trim($_POST['TABLE']); $this->columns = trim($_POST['COLUMNS']); $this->pkId = trim($_POST['PKID']); $this->validateUpdate($dbTablePermissionModel); break;	
				case "deleteByPk": $this->table = trim($_POST['TABLE']); $this->pkId = trim($_POST['PKID']); $this->validateDelete($dbTablePermissionModel); break; 
				default : throw new Exception("undefind function ".$this->function);
			}
		}
	}
	
	public static function getApiRequestData($dbTablePermissionModel=null) {
		$data = new TDApiRequestData();
		$data->init($dbTablePermissionModel);
		return $data;
	}
}
