<?php

class TDAjaxController extends TDController {

	public function actionsRemark() {
		return array(
			'ControllerRemark' => TDLanguage::$AjaxController_Remark,
			'actionExpandTableTreeData' => TDLanguage::$AjaxController_ExpandTableTreeData,
			'actionColumnsByTable' => TDLanguage::$AjaxController_ColumnsByTable,
			'actionGetPopupData' => TDLanguage::$AjaxController_GetPopupData,
			'actionGetPopupLadderColumn' => TDLanguage::$AjaxController_GetPopupLadderColumn,
			'actionUpdateARow' => TDLanguage::$AjaxController_UpdateARow,
			'actionRefreshSession' => TDLanguage::$AjaxController_RefreshSession,
			'actionConditionLoadTableColumns' => TDLanguage::$AjaxController_ConditionLoadTableColumns,
			'actionConditionLoadInputType' => TDLanguage::$AjaxController_ConditionLoadInputType,
			'actionBackupDataBase' => TDLanguage::$AjaxController_BackupDataBase,
			'actionCommonOperate' => TDLanguage::$AjaxController_CommonOperate,
			'actionReorderRows' => TDLanguage::$AjaxController_ReorderRows,
			'actionGetPopupDataMore' => '多选',
			'actionGridviewEditAdd' => 'gridvewi编辑添加记录',
			'actionClearnCashDB' => '清除缓存',
		);
	}

	public function actionExpandTableTreeData() {
		$moduleId = TDRequestData::getGetData('moduleId');
		$columnIds = TDRequestData::getGetData('columnIds');
		$columnValues = TDRequestData::getGetData('columnValues');
		$columnIdArray = explode(TDSearch::$expand_tree_str_key_str, $columnIds);
		$columnValueArray = explode(TDSearch::$expand_tree_str_key_str, $columnValues);
		$_GET["condition_splite_page"] = "0";
		$_GET["is_from_expand_tree"] = "1";
		$gridView = new TDGridView($this, $moduleId);
		foreach ($columnIdArray as $index => $columnId) {
			$gridView->model->getDbCriteria()->addCondition('`' . TDTableColumn::getColumnDBName($columnId) . '`=\''
					. $columnValueArray[$index] . '\' ');
		}
		$gridView->createGridView();
	}

	public function actionColumnsByTable() {
		$table = $_POST['table'];
		$model = TDModelDAO::getModel($table);
		$columns = $model->attributeLabels();
		foreach ($columns as $col => $str) {
			echo '<option value="' . $col . '">' . $str . '</option>';
		}
	}

	public function actionConditionLoadInputType() {
		$tableColumnId = isset($_POST['tableColumnId']) ? $_POST['tableColumnId'] : 0;
		$baseLinkUrl = isset($_POST['baseLinkUrl']) ? $_POST['baseLinkUrl'] : ''; 
		$result = Fie_condition::getColumnConditionInputType($tableColumnId,'',$baseLinkUrl);
		echo json_encode(array('typesSelect' => Fie_condition::createConditionTypesSelect($result['options']), 'inputHtml' => $result['inputHtml'],));
	}

	public function actionConditionLoadTableColumns() {
		$tableCollectionId = $_POST['tableCollectionId'];
		$markMuduleIdStr = $_POST['markMuduleIdStr'];
		$isUseComp = isset($_POST['isUseCondtionComp']) && $_POST['isUseCondtionComp'] == 1 ? true : false; 
		$belongStr = $_POST['belongStr'];
		$key = 'conditionLoadTableColumns_' . $tableCollectionId . '_' . $markMuduleIdStr . '_' . $belongStr;
		$cacheValue = TDSessionData::getCache($key);
		if ($cacheValue === false) {
			$isChild = true;
			$tmpArr = explode("_", $belongStr);
			if (count($tmpArr) <= 2) {
				$isChild = false;
			}
			$rowHtml = Fie_condition::getTableStartHtml();
			$rowHtml .= Fie_condition::createConditionRow(
							Fie_condition::createColumnsSelect(
									Fie_condition::getConditionColumns($tableCollectionId, $belongStr, true), $belongStr, "", $markMuduleIdStr)
							, $isChild, "", "", "", "", true, $markMuduleIdStr,$isUseComp);
			$rowHtml .= Fie_condition::getTableEndHtml();
			$cacheValue = $rowHtml;
			TDSessionData::setCache($key, $cacheValue);
		}
		echo $cacheValue;
	}

	public function actionGetPopupData() {
		$popupSearchColumnId = $_POST['popupSearchColumnId'];
		$columnModel = TDModelDAO::getModel(TDTable::$too_table_column)->find(array('select' => 'map_table_collection_id', 'condition' => '`id`=' . $popupSearchColumnId));
		$beSearchTableName = TDTableColumn::getTableDBName($columnModel->map_table_collection_id);
		$foreignId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE, $beSearchTableName);
		if (empty($foreignId) && isset($_POST["findCondition"])) {
			$tmpModel = TDModelDAO::getModel($beSearchTableName)->find(array("select" => "`id`", "condition" => $_POST["findCondition"]));
			if (!empty($tmpModel)) {
				$foreignId = $tmpModel->primaryKey;
			}
		}
		$fieldText = Fie_foreignkey::getFieldText($popupSearchColumnId, $foreignId);
		echo json_encode(array("fieldText" => $fieldText, "foreignId" => $foreignId));
	}

	public function actionGetPopupDataMore() {
		$popupSearchColumnId = $_POST['popupSearchColumnId'];
		$columnModel = TDModelDAO::getModel(TDTable::$too_table_column)->find(array('select' => 'map_table_collection_id', 'condition' => '`id`=' . $popupSearchColumnId));
		$beSearchTableName = TDTableColumn::getTableDBName($columnModel->map_table_collection_id);
		$foreignIdsStr = $_POST['foreignIds'];
		$foreignIds = explode(',', $foreignIdsStr);
		$fieldText = '';
		foreach ($foreignIds as $item) {
			$fieldText .= empty($fieldText) ? "" : "<br/>";
			$fieldText .= Fie_foreignkey::getFieldText($popupSearchColumnId, $item);
		}
		echo json_encode(array("fieldText" => $fieldText, "foreignIds" => $foreignIdsStr));
	}

	public function actionGetPopupLadderColumn() {
		$fieldText = '';
		$foreignId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE, TDTable::$too_table_column);
		$setBelongColumn = $foreignId;
		$belongIds = $_GET['belongIds'];
		if (!empty($belongIds)) {
			$setBelongColumn = $belongIds . ',' . $setBelongColumn;
		}
		$fieldText = Fie_laddercolumn::getColumnLabelStr($setBelongColumn);
		echo json_encode(array("fieldText" => $fieldText, "belongColumn" => $setBelongColumn));
	}

	public function actionUpdateARow() {
		$gridViewModel = TDModelDAO::queryRowByPk(TDTable::$too_module_gridview, $_GET['gridViewId']);
		$tableName = TDModule::getModuleTableName($gridViewModel["module_id"]);
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE, $tableName);
		$model = TDModelDAO::getModel($tableName, $pkId);
		if (!empty($gridViewModel["belong_order_column_ids"])) {
			$columnAppModelStr = TDTableColumn::getColumnAppendStr($gridViewModel["table_column_id"], $gridViewModel["belong_order_column_ids"], false);
			$model = TDFormat::getModelAppendColumnValue($model, $columnAppModelStr);
		}
		if (!empty($model)) {
			$columnRow = TDModelDAO::queryRowByPk("too_table_column",$gridViewModel["table_column_id"],"name,table_column_input_id");
			$columnStr = $columnRow["name"];
			$newVlaue = $_GET['newValue'];
			if($columnRow["table_column_input_id"] == Fie_order::getInputTypeId()) {
				$model->$columnStr = $newVlaue;
				//echo "<pre>";
				//print_r($model->rules());
				//exit;
				$model->save();	
				echo 'success';
				exit;	
			}
			$vs = new TDFormValidateSave($model, array( TDField::createFieldIdOrName($gridViewModel["table_column_id"], null, true) => $newVlaue));
			$vs->setModelFormData();
			$vs->validateSave(true);
			$errorStr = $vs->getErrorStr();
			if (!empty($errorStr)) {
				echo $errorStr;
				exit;
			} else {
				echo 'success';
				exit;
			}
		}
	}

	public function actionGridviewEditAdd() {
		$moduleId = $_POST["moduleId"];		
		$pkId = $_POST["pkId"];
		$columns = TDField::createFormEditField($moduleId,TDModelDAO::getModel(TDModule::getModuleTableName($moduleId),$pkId,true),true);
		foreach($columns as $index => $item) {
			$fieldId = "";
			$str = explode('name="',$item["fieldHtml"]);
			if(count($str) == 2) {
				$str = $str[0].substr($str[1],strpos($str[1],'"')+1);
			} else {
				$str = "";
			}
			$columns[$index]["tmpForEdit"] = $str;
			$str = explode('id="',$item["fieldHtml"]);
			if(count($str) == 2) {
				$fieldId = substr($str[1],0,strpos($str[1],'"')); 
				$str = $str[0].' id="'.$fieldId.'_tmp" '.substr($str[1],strpos($str[1],'"')+1);
			} else {
				$str = "";
			}
			$columns[$index]["tmpForHid"] = $str;
			$columns[$index]["fieldId"] = $fieldId;
		}
		echo json_encode($columns);
	}

	public function actionRefreshSession() {
		TDSessionData::afterLoginInit();
	}

	public function actionBackupDataBase() {
		//此方法在wdzxApiController中也一样的操作，用于在linux上自动执行备份数据用
		$time = date('YmdHis');
		$fileName = "tooadmin_sql_" . $time . ".sql";
		$zipFile = "tooadmin_" . $time . ".zip";
		$result = TDDataDAO::backupAllTableData($fileName);
		if ($result) {
			$filezip = new TDFileZipUnZip();
			$filezip->create_zip(array($fileName), $zipFile, true);
			unlink($fileName);
			echo json_encode(array("result" => "success", "file" => $zipFile));
		} else {
			echo json_encode(array("result" => "error", "file" => ""));
		}
	}

	public function actionCommonOperate() {
		$result = new TDOperateResult();
		$operateType = "";
		if (isset($_GET[TDOperate::$PARAM_OPERATE_TYPE])) {
			$operateType = trim($_GET[TDOperate::$PARAM_OPERATE_TYPE]);
		}
		if (empty($operateType)) {
			$result->setMsg("empty _GET " . TDOperate::$PARAM_OPERATE_TYPE);
		} else {
			$operate = new TDOperate();
			if (method_exists($operate, $operateType)) {
				$result = $operate->$operateType();
			} else {
				$result->setMsg("undefined function " . $operateType);
			}
		}
		echo json_encode($result->getResultForAjax());
	}

	public function actionReorderRows() {
		$result = new TDOperateResult();
		$ids = isset($_GET["ids"]) ? $_GET["ids"] : "";
		$orders = isset($_GET["orders"]) ? $_GET["orders"] : "";
		$orderAId = isset($_GET["orderAId"]) ? $_GET["orderAId"] : 0;
		$orderBId = isset($_GET["orderBId"]) ? $_GET["orderBId"] : 0;
		$orderColumnId = isset($_GET["orderColumnId"]) ? $_GET["orderColumnId"] : 0;
		$changeAChildToParent = isset($_GET["changeAChildToParent"]) ? $_GET["changeAChildToParent"] : 0;
		$changeAParentToChild = isset($_GET["changeAParentToChild"]) ? $_GET["changeAParentToChild"] : 0;
		if (!empty($ids) && !empty($orders) && !empty($orderAId) && !empty($orderBId) && !empty($orderColumnId)) {
			$orderColumnName = TDTableColumn::getColumnDBName($orderColumnId);
			$amodel = TDModelDAO::getModel(TDTableColumn::getColumnTableDBName($orderColumnId), $orderAId);
			$bmodel = TDModelDAO::getModel(TDTableColumn::getColumnTableDBName($orderColumnId), $orderBId);
			$anum = $amodel->$orderColumnName;
			$bnum = $bmodel->$orderColumnName;
			if ($changeAChildToParent || $changeAParentToChild) {
				$anum = Fie_order::getNextOrderNum($orderColumnId, $bmodel);
				$pidColumnName = FieldRule::getOrderPidColumnName($orderColumnId);
				$amodel->$pidColumnName = $bmodel->$pidColumnName;
				$amodel->save();
			}
			$idArray = explode(",", $ids);
			$orderArray = explode(",", $orders);
			$baseIdOrder = array();
			for ($i = 0; $i < count($orderArray); $i++) {
				if ($idArray[$i] == $orderAId) {
					$orderArray[$i] = $anum;
				}
				$baseIdOrder[] = array("id" => $idArray[$i], "num" => $orderArray[$i]);
			}
			sort($orderArray);
			$newIdOrder = array();
			for ($i = 0; $i < count($orderArray); $i++) {
				foreach ($baseIdOrder as $item) {
					$id = $item["id"];
					$num = $item["num"];
					if ($orderArray[$i] == $num) {
						$newIdOrder[] = array("id" => $id, "num" => $num);
					}
				}
			}
			$baseNewIdOrder = $newIdOrder;
			if ($anum > $bnum) { //up
				$newIdOrder[count($newIdOrder) - 1]["num"] = $bnum;
				for ($i = 0; $i < count($newIdOrder) - 1; $i++) {
					$newIdOrder[$i]["num"] = $baseNewIdOrder[$i + 1]["num"];
				}
			} else {
				$newIdOrder[0]["num"] = $bnum;
				for ($i = 1; $i < count($newIdOrder); $i++) {
					$newIdOrder[$i]["num"] = $baseNewIdOrder[$i - 1]["num"];
				}
			}
			$tableName = TDTableColumn::getColumnTableDBName($orderColumnId);
			$columnName = TDTableColumn::getColumnDBName($orderColumnId);
			if (!empty($tableName)) {
				foreach ($newIdOrder as $item) {
					$id = $item["id"];
					$num = $item["num"];
					$model = TDModelDAO::getModel($tableName, $id);
					$model->$columnName = $num;
					$model->save();
				}
				$result->setResult(true);
			} else {
				$result->setMsg("table name is empty");
			}
		} else {
			$result->setMsg("params error");
		}
		echo json_encode($result->getResultForAjax());
	}

	public function actionClearnCashDB() {
		$redis  =  new Predis_Client(Yii::app()->cache->servers[0]);
		$redis->select(10);
		$res = $redis->flushdb();
		echo $res ? "success" : "fail";
	}


}
