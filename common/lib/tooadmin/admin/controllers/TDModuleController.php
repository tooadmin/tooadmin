<?php

class TDModuleController extends TDController
{

	public function actionsRemark() {
		return array(
			'ControllerRemark' => TDLanguage::$ModuleController_Remark, 
			'actionChooseColumns' => TDLanguage::$ModuleController_ChooseColumns,	
			'actionCommonChooseColumns' => TDLanguage::$ModuleController_CommonChooseColumns,	
			'actionModuleColumns' => TDLanguage::$ModuleController_ModuleColumns,	
			'actionColumnsForModule' => TDLanguage::$ModuleController_ColumnsForModule,	
			'actionMysqlChooseColumns' => 'actionMysqlChooseColumns',
		);	
	}

	public function commonChooseMoreColumns($toolModuleId,$forModuleId,$tableCollectionId,$forJsMethodName) {
		$gridView = new TDGridView($this,TDStaticDefined::$choose_columns_moduleId);
		$gridView->model->getDbCriteria()->addCondition('`table_collection_id`='.$tableCollectionId);
		$gridView->toolModuleId = $toolModuleId;
		$gridView->forModuleId = $forModuleId;
		$gridView->forJsMethodName = $forJsMethodName;
		$gridView->forJsMethodTableId = $tableCollectionId;
		$this->layout = TDLayout::getSinglePage();	
		$this->render('tdcommon/admin',array('gridView'=>$gridView));
		//待处理
		//在table里如果有单选按钮,在点击下一页，或者ajax其他数据待单选按钮的数据加入进来时，单选按钮的样式会自定会变回原来的普通样式
		//所以改为统一使用原来的普通样式
	}
	
	public function actionMysqlChooseColumns() {
		$toolModuleId = $_GET['toolModuleId'];
		$tableCollectionId = $_GET['mysqlTableId'];
		$forModuleId = 0;
		$forJsMethodName = "mysqlReloadTableData";
		$this->commonChooseMoreColumns($toolModuleId, $forModuleId, $tableCollectionId, $forJsMethodName);
	}
	
	public function actionChooseColumns() {
		$forModuleId = $_GET['forModuleId'];
		$toolModuleId = $_GET['toolModuleId'];
		$moduleModel = TDModelDAO::getModel(TDTable::$too_module,$forModuleId);
		$tableCollectionId = $moduleModel->table_collection_id;
		$forJsMethodName = "columnIdsForModule";
		$this->commonChooseMoreColumns($toolModuleId, $forModuleId, $tableCollectionId, $forJsMethodName);
	}

	//通用选择列
	public function actionCommonChooseColumns() {
		$getName = 'defaultTableId';
		$defaultTableId = isset($_GET[$getName]) ? $_GET[$getName] : 0;
		if(isset($_GET[$getName.'r'])) {
			$defaultTableId = $_GET[$getName.'r'];	
		}
		$htmlOptions = array('name'=>$getName,'value'=>$defaultTableId);
		//$htmlOptions['onchange'] = 'window.location.href="?'.$getName.'r="+this.value';
		$htmlOptions['onchange'] = 'window.location.href=\'?'.$getName.'r=\'+this.value';
		$expandHtml = TDMenuWidget::createTableChoose($htmlOptions);
		if(empty($defaultTableId)) {
			$defaultTableId = TDMenuWidget::$defaultChooseedTableId;
		}
		$gridView = new TDGridView($this,TDStaticDefined::$choose_columns_moduleId);
		if(!empty($defaultTableId)) {
			$gridView->model->getDbCriteria()->addCondition('`table_collection_id`=\''.$defaultTableId.'\'');
		}	
		$this->layout = TDLayout::getSinglePage();	
		$this->render('tdcommon/admin',array('gridView'=>$gridView,'expandHtml'=>$expandHtml));
		//待处理
		//在table里如果有单选按钮,在点击下一页，或者ajax其他数据待单选按钮的数据加入进来时，单选按钮的样式会自定会变回原来的普通样式
		//所以改为统一使用原来的普通样式
	}
	
	public function actionModuleColumns()
	{
		$forModuleId = $_GET['forModuleId'];
		$toolModuleId = $_GET['toolModuleId'];
		$gridView = new TDGridView($this,$toolModuleId);
		$gridView->model->getDbCriteria()->addCondition('`module_id`='.$forModuleId);
		$gridView->forModuleId = $forModuleId;
		$gridView->button_add_url = TDPathUrl::createUrl('tDModule/chooseColumns/forModuleId/'
		.$gridView->forModuleId.'/toolModuleId/'.$toolModuleId);
		$gridView->forJsMethodName = "columnIdsForModule";
		$this->layout = TDLayout::getSinglePage();	

		$gridview_top_file = TDModelDAO::queryScalarByPk(TDTable::$too_module, $toolModuleId,"gridview_top_file");
		if(!empty($gridview_top_file)) {
			$value = Fie_formula::getValue(null,$gridview_top_file);
			if(!empty($value)) { Too::daoFile($value); }
		}
		if(isset($_REQUEST["condition_expert_excel"]) && $_REQUEST["condition_expert_excel"] == "1") {
			$excel = new TDToolExcel();
			$excel->expertByTDGRidView($gridView);
			exit;
		}
		$gridview_foot_file = TDModelDAO::queryScalarByPk(TDTable::$too_module,$toolModuleId,"gridview_rewrite_file"); 
		if(!empty($gridview_foot_file)) { 
			$value = Fie_formula::getValue(null,$gridview_foot_file);
			if(!empty($value)) { $this->render("custome_transfer",array("view_file"=>$value));  } 
		} else {
			$this->render('tdcommon/admin',array('gridView'=>$gridView));
		}

	}

	public function actionColumnsForModule() {
		$columnIds = $_POST['columnIds'];	
		$moduleId = $_POST['moduleId'];	
		$toolModuleId = $_POST['toolModuleId'];
		$columnItems = explode(TDSearch::$expand_tree_column_key_column,$columnIds);
		foreach($columnItems as $item) {
			$tmpStr = explode(TDSearch::$expand_tree_str_key_str,$item);
			$belogOrderColumnIds = $tmpStr[0];
			$tmpArr = explode(",",$belogOrderColumnIds);
			$belogToColumnId = $tmpArr[count($tmpArr)-1];
			$columnId = $tmpStr[1];
			$toolModule = TDModelDAO::getModel(TDTable::$too_module,$toolModuleId);
			$moduleTableName = TDTableColumn::getTableDBName($toolModule->table_collection_id);
			$model = TDModelDAO::getModel($moduleTableName);
			$model->module_id = $moduleId;
			$model->table_column_id = $columnId;
			$model->belong_to_column_id = empty($belogToColumnId) ? null : $belogToColumnId;
			$model->belong_order_column_ids = empty($belogOrderColumnIds) ? null : $belogOrderColumnIds;
			$check = '`module_id`=\''.$model->module_id.'\' and `table_column_id`=\''
			.$model->table_column_id.'\' and `belong_to_column_id` '.(empty($model->belong_to_column_id) ? 'is null' : '='.$model->belong_to_column_id).' ';
			if($model->count($check) == 0) {
				$model->order = Fie_order::getNextOrderNum(TDTableColumn::getColumnIdByTableAndColumnName($moduleTableName,'order'),$model);
				$model->save();
			}
		}
	}
}
