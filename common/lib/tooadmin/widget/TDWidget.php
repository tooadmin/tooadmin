<?php
class TDWidget {
	
	public $tableName;
	public $pageObj;
	public $model;
	public $moduleId;
	public $markMuduleIdStr;
	public $pkId;

	public $allow_actions=array();
	public $allow_pagination = true;
	public $page_item_count = 10;
	public $search_view_current = 1;
	public $use_id_checkbox = false;
	public $use_id_redio = false;
	public $tree_table_column_id;
	public $gridviewWidth = 0;
	
	public $forModuleId=0;
	public $toolModuleId=0;
	public $forJsMethodName='';
	public $forJsMethodTableId=0;
	public $forJsMethodUseOrderColumns=array();

	public $gridviewModuleId = 0;
	public $addModuleId = 0;
	public $updateModuleId = 0;
	public $viewModuleId = 0;
	public $formModuleId = 0;
	public $gridview_default_order = "";
	public $gridview_default_condition = "";
	public $gridview_join_sql = "";
	public $gridview_button_appendParam = "";
	public $gridview_only_view = false;
	public $gridview_query_group = "";
	public $expande_select_sql = "";
	public $having_sql = "";
	public $queryNotSplitePage = false; 

	public $add_form_width = 0; 
	public $add_form_height = 0;
	public $edit_form_width = 0;
	public $edit_form_height = 0;
	public $view_form_width = 0;
	public $view_form_height = 0;
	
	public function __construct($pageObj,$moduleId,$pkId =null,$layout=null,$gridview_button_appendParam='',$gridview_only_view=false) {
		$this->moduleId = $moduleId;
		$moduleFormModuleId = isset($_GET[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $_GET[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '0'; 
		$this->markMuduleIdStr = $this->moduleId.'_'.$moduleFormModuleId.(isset($_GET["mitemId"]) ? "_".$_GET["mitemId"] : ""); 
		
		$this->pageObj = $pageObj;
		$this->gridview_button_appendParam = $gridview_button_appendParam.TDField::getFormModuleExpParamsForPopSerach();
		$this->gridview_only_view = $gridview_only_view;
		if(isset($_GET[TDStaticDefined::$PARAM_MODULE_READONLY]) && $_GET[TDStaticDefined::$PARAM_MODULE_READONLY] == 1) {
			$this->gridview_only_view = true;	
		}
		$this->queryNotSplitePage = isset($_REQUEST["condition_splite_page"]) && $_REQUEST["condition_splite_page"] == "0" ? true : false;
		if(is_null($layout)) {
			$this->pageObj->layout = TDLayout::getLayout($this);
		} else {
			$this->pageObj->layout = $layout;
		}
		$this->pkId = $pkId;
		$this->init();		
	}

	private function init() {
		$tableModel = TDModelDAO::queryRowByPk(TDTable::$too_module,$this->moduleId);
		$this->gridviewModuleId = $this->moduleId;
		$this->addModuleId = $this->moduleId;  
		$this->updateModuleId = $this->moduleId;  
		$this->gridviewWidth = $tableModel["gridview_width"];
		$this->gridview_query_group = $tableModel["gridview_query_group"];
		$this->expande_select_sql = $tableModel["expande_select_sql"];
		$this->having_sql = $tableModel["having_sql"];

		$this->add_form_width = intval($tableModel["add_form_width"]); 
		$this->add_form_height = intval($tableModel["add_form_height"]); 
		$this->edit_form_width = intval($tableModel["edit_form_width"]); 
		$this->edit_form_height = intval($tableModel["edit_form_height"]); 
		$this->view_form_width = intval($tableModel["view_form_width"]); 
		$this->view_form_height = intval($tableModel["view_form_height"]);
		
		if(!empty($tableModel["default_order"])) {
			$this->gridview_default_order = Fie_formula::getValue(null,$tableModel["default_order"]);
		}
		if(empty($this->pkId)) {
			$this->formModuleId = $this->addModuleId;
		} else {
			$this->formModuleId = $this->updateModuleId;
		}
		$this->viewModuleId = $this->moduleId; 
		if($this->moduleId == TDStaticDefined::$mysqlCommonModuleId && isset($_GET[TDStaticDefined::$mysqlCommonMudelTabId])) {
			$this->tableName = TDTable::getTableDBName($_GET[TDStaticDefined::$mysqlCommonMudelTabId]); 
			if(!isset($_GET[TDStaticDefined::$mysqlDataDispalyType]) || $_GET[TDStaticDefined::$mysqlDataDispalyType] != TDStaticDefined::$mysqlDataDispalyType_org) {
				$this->allow_actions = explode(',','add,update,delete,view');
				$this->use_id_redio = true;
			}
		} else {
			$this->tableName = TDTableColumn::getTableDBNameByModuleId($this->moduleId);
			$this->allow_actions = explode(',',$tableModel["allow_actions"]);
		}
		if($this instanceof TDGridView) {
			$this->model = TDModelDAO::getModel($this->tableName);   
			$this->model->unsetAttributes();
			$ntbModuleFormModulePkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
			$ntbBaseFromRowPkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_ROW_PKID);
			if(!empty($ntbModuleFormModulePkId) && !empty($ntbBaseFromRowPkId)) {
				$moduleFormModule = TDModelDAO::queryRowByPk(TDTable::$too_module_formmodule,$ntbModuleFormModulePkId);
				$baseFormRow = TDModelDAO::getModel(TDTableColumn::getTableDBNameByModuleId($moduleFormModule["form_module_id"]),$ntbBaseFromRowPkId);
				if(!empty($moduleFormModule["default_relation_column"])) {
					$this->model->getDbCriteria()->addCondition(Fie_laddercolumn::getConditionSQLByForeignPrimaryKey($moduleFormModule["default_relation_column"],$baseFormRow->primaryKey));
				}
				if(!empty($moduleFormModule["ntable_condition"])) {
					$condition = Fie_formula::getValue($baseFormRow,$moduleFormModule["ntable_condition"]);
					if(!empty($condition)) {
						$this->model->getDbCriteria()->addCondition($condition); 
					}
				}
				if(!empty($moduleFormModule["join_sql"])) {
					$condition = Fie_formula::getValue($baseFormRow,$moduleFormModule["join_sql"]);
					if(strpos($this->model->getDbCriteria()->condition,$condition) === false) {
						$this->model->getDbCriteria()->join = $condition;
					}
				}		
			}
			if(isset($_GET[TDStaticDefined::$viewChildTableDatasFromTbId]) && !empty($_GET[TDStaticDefined::$viewChildTableDatasFromTbId])) {
				$useForTabColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".
				TDTableColumn::getTableCollectionID($this->tableName)." and map_table_collection_id=".intval($_GET[TDStaticDefined::$viewChildTableDatasFromTbId]),"`name`");
				$viewConSQl = '';
				foreach($useForTabColumns as $tmpcol) {
					$tmpColName = $tmpcol["name"];	
					$viewConSQl = !empty($viewConSQl) ? ' and ' : '';
					$viewConSQl .= "`t`.`".$tmpColName."`='".trim($_GET[TDStaticDefined::$viewChildTableDatasFromPkId])."'";
				}
				if(!empty($viewConSQl)) {
					$this->model->getDbCriteria()->addCondition($viewConSQl);
				}
			}
		} else if($this instanceof TDEditForm) {
			$this->model = TDModelDAO::getModel($this->tableName,$this->pkId);   
			if(empty($this->model)){ throw new Exception("model is empty tableName=".$this->tableName." this->pkId=".$this->pkId); }
			if(isset($_GET[TDStaticDefined::$viewChildTableDatasFromTbId]) && !empty($_GET[TDStaticDefined::$viewChildTableDatasFromTbId])) {
				$useForTabColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".
				TDTableColumn::getTableCollectionID($this->tableName)." and map_table_collection_id=".intval($_GET[TDStaticDefined::$viewChildTableDatasFromTbId]),"`name`");
				foreach($useForTabColumns as $tmpcol) {
					$tmpColName = $tmpcol["name"];	
					$this->model->$tmpColName = trim($_GET[TDStaticDefined::$viewChildTableDatasFromPkId]);
				}
			}	
		} else if($this instanceof TDView) {
			$this->model = TDModelDAO::getModel($this->tableName,$this->pkId);   
			if(empty($this->model)){ throw new Exception("model is empty tableName=".$this->tableName); }
		}
		$this->allow_pagination = $tableModel["is_pagination"];
		if(!empty($tableModel["page_item_count"])) {
			$this->page_item_count = $tableModel["page_item_count"];
		}
		$this->search_view_current = $tableModel["search_view"];
		$this->use_id_checkbox = $tableModel["use_id_checkbox"] == 1 ? true : false;
		$this->tree_table_column_id = $tableModel["tree_table_column_id"];
		if(!empty($tableModel["gridview_default_condition"])) {
			$this->gridview_default_condition = Fie_formula::getValue(null,$tableModel["gridview_default_condition"]);	
		}
		if(!empty($tableModel["join_sql"])) {
			$this->gridview_join_sql = Fie_formula::getValue(null,$tableModel["join_sql"]);	
		}
		$this->afterInit();
	}	

	public function afterInit() { 
	}
}