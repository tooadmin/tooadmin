<?php

class TDApiDataDAO {
	
	public $dbp_model = null;

	public function __construct($pdbpModel) {
		$this->dbp_model = $pdbpModel;
	}

	private function getFileColumns($tableName) {
		$array = array(); $tableId = TDTableColumn::getTableCollectionID($tableName);
		if(!empty($tableId)) { $rows = TDModelDAO::queryAll(TDTable::$too_table_column, '`table_collection_id`='.$tableId.' and `table_column_input_id`='.TDStaticDefined::$column_input_id_file, '`name`,`id`');
		foreach($rows as $row) { $array[$row["name"]] = $row["id"]; } } return $array;
	}

	private function formatFileColumnValues(&$model,$fileColumnName_IDArray=null) {
		if(is_null($fileColumnName_IDArray)) {
			$fileColumnName_IDArray = $this->getFileColumns($model->tableName()); 
		}		
		if(!empty($fileColumnName_IDArray)) {
			$fileColumnArray = array_keys($fileColumnName_IDArray);
			foreach($model->getAttributes() as $column => $value) {
				if(!empty($value) && in_array($column,$fileColumnArray)) {
					$model->$column = Fie_file::getFileUrl($fileColumnName_IDArray[$column],$value,true); 
				}
			}
		}
	}
	
	public function findByPk() {
		$reqData = TDApiRequestData::getApiRequestData($this->dbp_model);
		$row = TDModelDAO::getModel($reqData->table)->find(array('select'=> $reqData->select,'condition'=> '`id`='.intval($reqData->pkId))); 
		if(!empty($row)) { $this->formatFileColumnValues($row); return $row->attributes; } else { return "not find data !"; }
	}

	public function find() {
		$reqData = TDApiRequestData::getApiRequestData($this->dbp_model);
		$row = TDModelDAO::getModel($reqData->table)->find(array('select'=>$reqData->select,'condition'=>$reqData->condition)); 
		if(!empty($row)) { $this->formatFileColumnValues($row); return $row->attributes; }
	}

	public function findAll() {
		$reqData = TDApiRequestData::getApiRequestData($this->dbp_model);
		$rows = TDModelDAO::getModel($reqData->table)->findAll(array('select'=>$reqData->select,'condition'=>$reqData->condition)); 
		$data = array();
		$fileColumnsArray = $this->getFileColumns($reqData->table);
		foreach($rows as $row) { $this->formatFileColumnValues($row,$fileColumnsArray); $data[] = $row->attributes; }
		return $data;
	}

	private function formatEditColumns($tableName,$columnStrs) {
		if(isset($_POST["FILES_ITEMS"])) { $_FILES = $_POST["FILES_ITEMS"]; }
		$_POST['modelName'] = TDStaticDefined::$formModelName;
		$_POST[TDStaticDefined::$formModelName] = array();
		$columns = explode(",",$columnStrs);
		foreach($columns as $col) {
			$colId =  TDTableColumn::getColumnIdByTableAndColumnName($tableName,$col);
			if(!empty($colId) && isset($_POST["COLUMN_".$col])) {
				if(isset($_FILES[$col])) { $_FILES[TDField::createFieldIdOrName($colId,null,true)] = $_FILES[$col]; unset($_FILES[$col]); }
				$_POST[TDStaticDefined::$formModelName][TDField::createFieldIdOrName($colId,null,true)] =  $_POST["COLUMN_".$col]; 	
			}	
		}
	}

	private function editRow() {
		$reqData = TDApiRequestData::getApiRequestData($this->dbp_model);
		$this->formatEditColumns($reqData->table,$reqData->columns);
		$model = TDModelDAO::getModel($reqData->table,$reqData->pkId); 
		$formValidate = new TDFormValidateSave($model);
		$formValidate->runSaveFlow(TDCommon::$outputErrorType_jsonError);
		$validateError =  $formValidate->validateUnPass;
		$validateOtherErrors = $formValidate->validateOtherErrors;
		$errorMsg = "";
		if(!empty($validateError) || !empty($validateOtherErrors)) {
			$errorMsg = TDCommon::getArrayValuesToString($validateOtherErrors);
			if(!empty($errorMsg)) { $errorMsg .= ","; }
			$errorMsg .= TDCommon::getArrayValuesToString($validateError);
		}	
		$data = array();
		if(empty($errorMsg)) {
			$data["result"] = "success";
			$data["pkid"] = $model->primaryKey;
		} else {
			$data["result"] = "fail";
			$msg = TDCommon::getArrayValuesToString($model->errors);
			$data["msg"] = empty($msg) ? $errorMsg : $msg;
		}
		return $data;	
	}
	
	public function addRow() { return $this->editRow(); } 
	public function updateRow() {  return $this->editRow();}	

	public function deleteByPk() {
		$reqData = TDApiRequestData::getApiRequestData($this->dbp_model);
		$row = TDModelDAO::getModel($reqData->table,$reqData->pkId);
		$data = array();
		if(empty($row)) { $data["result"] = "fail"; $data["msg"] = "unfind row"; } 
		else { if($row->delete())  { $data["result"] = "success"; } 
		else { $data["result"] = "fail"; $data["msg"] = "delete error";	} }
		return $data;
	}

	public function getPermissionDetail() {
		$detail = Fie_dbtablepermission::getPermissionDetail($this->dbp_model->dbp_query,$this->dbp_model->dbp_add,$this->dbp_model->dbp_update,$this->dbp_model->dbp_delete);	
		$html = '<table border="2">'; 
		foreach($detail as $item) {
			$html .= '<tr>';
				$html .= '<td>'.$item["table"].'</td>';
				$html .= '<td>';
					$html .= '<table>';
						$perHtml = ''; foreach($item["query_permission"] as $pi) { $perHtml .= '&nbsp;'.$pi; }
						$html .= '<tr><td>Query: '.$perHtml.'</td></tr>';

						$perHtml = ''; foreach($item["add_permission"] as $pi) { $perHtml .= '&nbsp;'.$pi; }
						$html .= '<tr><td>Add: '.$perHtml.'</td></tr>';

						$perHtml = ''; foreach($item["update_permission"] as $pi) { $perHtml .= '&nbsp;'.$pi; }
						$html .= '<tr><td>Update: '.$perHtml.'</td></tr>';

						$html .= '<tr><td>Delete:'.($item["delete_permission"] ? 'true' : 'false').'</td></tr>';
					$html .= '</table>';
				$html .= '</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		return array("info"=>$html);
	}
}
