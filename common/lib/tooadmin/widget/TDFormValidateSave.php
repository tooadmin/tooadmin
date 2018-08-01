<?php

class TDFormValidateSave {

	private $moduleId;
	private $model;
	private $fixedAttributes;
	private $formFieldColumns;
	private $formFieldIds;
	private $appendModelArray;
	public $validateErrorFields = array();
	public $validatePassFields = array();
	public $outside_validate_errors = array();
	//for result get data 
	public $validateUnPass = array();
	public $validatePass = array();
	public $validateOtherErrors = array();
	public $lessToRunMoreTimesSaveColumnId_ForeignIds = array();

	public function __construct($model, $fixedAttributes = array(), $outside_validate_errors = array(), $moduleId = 0) {
		$this->model = $model;
		$this->moduleId = $moduleId;
		$this->fixedAttributes = $fixedAttributes;
		$this->outside_validate_errors = $outside_validate_errors;
		$fieldNames = isset($_POST[TDStaticDefined::$formModelName]) ? $_POST[TDStaticDefined::$formModelName] : array();
		$fieldNames = array_keys($fieldNames);
		if (!empty($fixedAttributes)) {
			$fixedKeyArray = array_keys($fixedAttributes);
			$fieldNames = array_merge($fieldNames, $fixedKeyArray);
		}
		$this->formFieldColumns = array();
		foreach ($fieldNames as $fieldName) {
			$fieldCo = TDFieldColumn::createBuyFieldName($fieldName);
			if (TDTableColumn::checkIsCustomColumn($fieldCo->tableColumnId)) {
				continue;
			}
			$this->formFieldColumns[] = $fieldCo;
			$this->formFieldIds[] = str_replace(TDStaticDefined::$formFieldName, TDStaticDefined::$formFieldID, $fieldName);
		}
	}

	private function logErrorMsg($fieldId,$err) {
		if (in_array($fieldId, $this->formFieldIds)) {
			$this->validateErrorFields[] = array('fieldID' => $fieldId, 'msg' => $err);
		} else {
			$this->validateOtherErrors[] = array('fieldID' => $fieldId, 'msg' => $err);
		}	
	}

	private function filterErrorField() {
		foreach($this->validateErrorFields as $errorField) {	
			foreach($this->validatePassFields as $index => $item) {
				if($item["fieldID"] == $errorField["fieldID"]) {
					unset($this->validatePassFields[$index]);	
					break;
				}
			}
		}
		foreach($this->validateOtherErrors as $errorField) {	
			foreach($this->validatePassFields as $index => $item) {
				if($item["fieldID"] == $errorField["fieldID"]) {
					unset($this->validatePassFields[$index]);	
					break;
				}
			}
		}
	}

	public function validateSave($isToSave = false, $isToValidate = false) {
		if (!$isToSave && !$isToValidate)
			return;
		$appendModelArray = $this->appendModelArray;
		$appendModelArray['baseModel'] = $this->model;
		$saveEvenErrorMsgStr = '';
		foreach ($appendModelArray as $appendModelStr => $appendModel) {
		if (!$isToSave && !$isToValidate)
			$this->pidUpdateEvens($appendModel); //must be at first to run
			$erorrFieldIds = array();
			if (empty($saveEvenErrorMsgStr) && $appendModel->validate(null, false)) {// && $isToSave && $appendModel->save() 
				if ($isToSave) {
					$resErrorMsgStr = TDEvents::saveEven($appendModel, false);
					if (!empty($resErrorMsgStr)) {
						if (!empty($saveEvenErrorMsgStr)) {
							$saveEvenErrorMsgStr .= ',';
						}
						$saveEvenErrorMsgStr .= $resErrorMsgStr;
					}
					if ($appendModelStr != 'baseModel') {
						$tmpModelStr = explode("->", $appendModelStr);
						$tmpStr = explode(TDStaticDefined::$foreignKey_tableName, $tmpModelStr[count($tmpModelStr) - 1]);
						if (count($tmpModelStr) > 1) {
							$tmpParentStr = '';
							for ($i = 0; $i < count($tmpModelStr) - 1; $i++) {
								if (!empty($tmpParentStr)) {
									$tmpParentStr .= '->';
								}
								$tmpParentStr .= $tmpModelStr[$i];
							}
							TDFormat::setModelAppendColumnValue($this->model, $tmpParentStr . "->" . $tmpStr[0], $appendModel->primaryKey);
						} else {
							$tmpstrvalue = $tmpStr[0];
							$this->model->$tmpstrvalue = $appendModel->primaryKey;
						}
					}
				}
			} else {
				foreach ($appendModel->errors as $col => $errors) {
					$err = '';
					foreach ($errors as $error) {
						$err = empty($err) ? (is_array($error) ? $error[0] : $error) : $err . "," . $error;
					}
					$columnId = TDTableColumn::getColumnIdByTableAndColumnName($appendModel->tableName, $col);
					if (empty($columnId)) {
						$fieldId = $col;
					} else {
						if ($appendModelStr == 'baseModel') {
							$fieldId = TDField::createFieldIdOrName($columnId);
						} else {
							$fieldId = TDField::createFieldIdOrName($columnId, TDTableColumn::getBelongOrderColumnIds($this->model, $appendModelStr));
						}
					}
					$erorrFieldIds[] = $fieldId;
					$this->logErrorMsg($fieldId, $err);
				}
			}
			foreach ($appendModel->attributes as $col => $value) {
				if(strrpos($col,TDStaticDefined::$foreignKey_tableName) !== false) { 
					continue;
				}
				$columnId = TDTableColumn::getColumnIdByTableAndColumnName($appendModel->tableName, $col);
				if (empty($columnId)) {
					$fieldId = $col;
				} else {
					if ($appendModelStr == 'baseModel') {
						$fieldId = TDField::createFieldIdOrName($columnId);
					} else {
						$fieldId = TDField::createFieldIdOrName($columnId, TDTableColumn::getBelongOrderColumnIds($this->model, $appendModelStr));
					}
				}
				if (!in_array($fieldId, $erorrFieldIds)) {
					$this->validatePassFields[] = array('fieldID' => $fieldId);
				}
			}
		}
		if (!empty($saveEvenErrorMsgStr)) {
			$this->validateOtherErrors[] = array('fieldID' => '', 'msg' => $saveEvenErrorMsgStr);
		}
		if ($isToSave && empty($this->validateErrorFields) && empty($this->validateOtherErrors)) {
			foreach ($appendModelArray as $appendModelStr => $appendModel) {
				$appendModel->save();
			}
		}
	}

	public function validateUniqueAndExpandRules() {
		$errorMsg = array();
		foreach ($this->formFieldColumns as $fieldColumn) {
			$columnModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$fieldColumn->tableColumnId);
			if (empty($columnModel)) {
				continue;
			}
			$ladderColumnIds = empty($fieldColumn->belongOrderColumnIds) ? $fieldColumn->tableColumnId : $fieldColumn->belongOrderColumnIds . ',' . $fieldColumn->tableColumnId;
			$fileValue = TDFormat::getModelAppendColumnValue($this->model, TDTableColumn::getLadderColumnAppendStr($ladderColumnIds));
			$tableName = TDTableColumn::getTableDBName($columnModel["table_collection_id"]);
			$columnName = $columnModel["name"];
			$querySQL = '';
			$columnLabelStr = '';
			$uniqueCheckSQL = !empty($columnModel["unique_check_condtion"]) ? Fie_formula::getValue(null,$columnModel["unique_check_condtion"]) : '';
			if ($columnModel["is_unique"] == 1 || !empty($columnModel["unique1_laddercolumn"]) || !empty($columnModel["unique2_laddercolumn"]) || !empty($uniqueCheckSQL)) {
				$querySQL .= '`' . $columnName . '`=\'' . $fileValue . '\'';
				if(!empty($uniqueCheckSQL)) {
					$querySQL .= ' and '.$uniqueCheckSQL; 
				}
				$columnLabelStr .= TDTableColumn::getColumnLabelName($fieldColumn->tableColumnId);
			}
			if (!empty($columnModel["unique1_laddercolumn"])) {
				$ladderColumnIds_uni1 = empty($fieldColumn->belongOrderColumnIds) ? $columnModel["unique1_laddercolumn"] : $fieldColumn->belongOrderColumnIds . ',' . $columnModel["unique1_laddercolumn"];
				if (!empty($querySQL)) {
					$querySQL .= ' and ';
					$columnLabelStr .= ',';
				}
				$columnLabelStr .= TDTableColumn::getColumnLabelName($columnModel["unique1_laddercolumn"]);
				$querySQL .= '`' . TDTableColumn::getColumnDBName($columnModel["unique1_laddercolumn"]) . '`=\'' .
						TDFormat::getModelAppendColumnValue($this->model, TDTableColumn::getLadderColumnAppendStr($ladderColumnIds_uni1)) . '\' ';
			}
			if (!empty($columnModel["unique2_laddercolumn"])) {
				$ladderColumnIds_uni2 = empty($fieldColumn->belongOrderColumnIds) ? $columnModel["unique2_laddercolumn"] : $fieldColumn->belongOrderColumnIds . ',' . $columnModel["unique2_laddercolumn"];
				if (!empty($querySQL)) {
					$querySQL .= ' and ';
					$columnLabelStr .= ',';
				}
				$columnLabelStr .= TDTableColumn::getColumnLabelName($columnModel["unique2_laddercolumn"]);
				$querySQL .= '`' . TDTableColumn::getColumnDBName($columnModel["unique2_laddercolumn"]) . '`=\'' .
						TDFormat::getModelAppendColumnValue($this->model, TDTableColumn::getLadderColumnAppendStr($ladderColumnIds_uni2)) . '\' ';
			}
			if (!empty($querySQL)) {
				if(!empty($this->model->id)) {
					$querySQL .= ' and id <> '.$this->model->id; 
				}
				$checkRow = TDModelDAO::getModel($tableName)->find($querySQL);
				if (!empty($checkRow)) {
					$pkColumnName = $checkRow->getTableSchema()->primaryKey;
					$pkIdColumnId = TDTableColumn::getColumnIdByTableAndColumnName($tableName, $pkColumnName);
					$pkIdLadderColumnStr = empty($fieldColumn->belongOrderColumnIds) ? $pkIdColumnId :
							$fieldColumn->belongOrderColumnIds . ',' . $pkIdColumnId;
					$formPkId = TDFormat::getModelAppendColumnValue($this->model, TDTableColumn::getLadderColumnAppendStr($pkIdLadderColumnStr));
					if (empty($formPkId)) { //post is new row
						$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_msg_not_unique);
					} else {
						$checkSecondRow = TDModelDAO::getModel($tableName)->find($querySQL . ' and `' . $pkColumnName . '` <>  \'' . $formPkId . '\'');
						if (!empty($checkSecondRow)) {
							$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_msg_not_unique);
						}
					}
				}
			}
			if (is_numeric($fileValue) || !empty($fileValue)) {
				if (!empty($columnModel["grep_text"])) {
					$grep = $columnModel["grep_text"];
					if (strrpos($grep, "\\\\") !== false) {
						$grep = stripcslashes($grep);
					}
					if (!preg_match($grep, $fileValue)) {
						$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId,
							'msg' => $columnLabelStr . ' ' . (!empty($columnModel["grep_tip_msg"]) ? $columnModel["grep_tip_msg"] : TDLanguage::$validate_unpass));
					}
				}
				if (!empty($columnModel["min_value"]) && $fileValue < $columnModel["min_value"]) {//common model rule hase validated 
					$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_not_less_than . $columnModel["min_value"]);
				} else if (!empty($columnModel["max_value"]) && $fileValue > $columnModel["max_value"]) {//common model rule hase validated 
					$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_not_more_than . $columnModel["max_value"]);
				} else if (!empty($columnModel["not_eq"]) && $columnModel["not_eq"] === $fileValue) {
					$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_not_equal . $fileValue);
				} else if (!empty($columnModel["in_array"]) && !in_array($fileValue, explode(",", $columnModel["in_array"]))) {
					$errorMsg[] = array('fieldID' => $fieldColumn->baseFieldId, 'msg' => $columnLabelStr . ' ' . TDLanguage::$validate_not_in_array . " [" . $columnModel["in_array"]. "]");
				}
			}
		}
		return $errorMsg;
	}

	public function pidUpdateEvens($model) {
		if ($model->isNewRecord) {
			return;
		}
		$tableCollectionId = TDTableColumn::getTableCollectionID($model->tableName);
		$orderColumnId = Fie_order::getOrderInputTypeColumnId($tableCollectionId);
		$pidColumnId = Fie_pid::getPidColumnIdByTableId($tableCollectionId);
		if (empty($pidColumnId) || empty($orderColumnId)) {
			return;
		}
		$pidColumnName = TDTableColumn::getColumnDBName($pidColumnId);
		$newPid = $model->$pidColumnName;
		$pkId = $model->primaryKey;
		$basePidModel = TDModelDAO::queryRowByPk($model->tableName,$pkId,$pidColumnName);
		$basePid = $basePidModel->$pidColumnName;
		if ($basePid != $newPid) {
			TDFormat::setModelAppendColumnValue($model, TDTableColumn::getColumnDBName($orderColumnId), Fie_order::getNextOrderNum($orderColumnId, $model));
		}
	}

	public function setModelFormData() {
		$resultDataArray = array();
		$foreignField = array();
		$commonField = array();
		$belongArray = array();
		$belongDefault = 'unuse';
		foreach ($this->formFieldColumns as $fieldColumn) {
			$belong = $belongDefault;
			if (!empty($fieldColumn->belongOrderColumnIds)) {
				$belong = $fieldColumn->belongOrderColumnIds;
			}
			if (TDTableColumn::checkColumnIsForeignkey($fieldColumn->tableColumnId)) {
				$foreignField[$belong][] = $fieldColumn;
			} else {
				$commonField[$belong][] = $fieldColumn;
			}
			if (!in_array($belong, $belongArray)) {
				$belongArray[] = $belong;
			}
		}
		$unuseIndex = 0;
		for ($i = 0; $i < count($belongArray); $i++) {
			if ($belongArray[$i] == $belongDefault) {
				$unuseIndex = $i;
				break;
			}
		}
		if ($unuseIndex != 0) {
			$tmps = $belongArray[0];
			$belongArray[0] = $belongDefault;
			$belongArray[$unuseIndex] = $tmps;
		}
		for ($i = 1; $i < count($belongArray); $i++) {
			for ($j = $i + 1; $j < count($belongArray); $j++) {
				$beStrI = $belongArray[$i];
				$beStrIar = explode(",", $beStrI);
				$beStrJ = $belongArray[$j];
				$beStrJar = explode(",", $beStrJ);
				if (count($beStrJar) < count($beStrIar)) {
					$belongArray[$i] = $beStrJ;
					$belongArray[$j] = $beStrI;
				}
			}
		}
		for ($tmi = 0; $tmi < 2; $tmi++) {
			for ($i = 0; $i < count($belongArray); $i++) {
				$fieldColumnArray = null;
				if ($tmi == 0) {
					if (isset($foreignField[$belongArray[$i]]))
						$fieldColumnArray = $foreignField[$belongArray[$i]];
				} else {
					if (isset($commonField[$belongArray[$i]]))
						$fieldColumnArray = $commonField[$belongArray[$i]];
				}
				if (empty($fieldColumnArray)) {
					continue;
				}
				foreach ($fieldColumnArray as $fieldColumn) {
					$appendModel = null;
					$appendModelStr = null;
					if (!empty($fieldColumn->belongOrderColumnIds)) {
						$appendModelStr = TDTableColumn::getColumnAppendStr($fieldColumn->tableColumnId, $fieldColumn->belongOrderColumnIds, false);
						$appendModel = TDFormat::getModelAppendColumnValue($this->model, $appendModelStr);
						if (empty($appendModel)) {
							$tmpModelStr = explode("->", $appendModelStr);
							$tmpStr = explode(TDStaticDefined::$foreignKey_tableName, $tmpModelStr[count($tmpModelStr) - 1]);
							TDFormat::setModelAppendColumnValue($this->model, $appendModelStr, TDModelDAO::getModel($tmpStr[1]));
							$appendModel = TDFormat::getModelAppendColumnValue($this->model, $appendModelStr);
						}
					}
					$resultMsg = $this->setColumnDataItem($fieldColumn, $this->model,$appendModel);
					if (!empty($resultMsg)) {
						if (empty($resultDataArray)) {
							$resultDataArray['newFileArray'] = array();
							$resultDataArray['orgFileArray'] = array();
							$resultDataArray['specialValidateErrorFields'] = array();
						}
						if (isset($resultMsg['newFileArray']))
							$resultDataArray['newFileArray'] = TDCommon::array_smerge($resultDataArray['newFileArray'], $resultMsg['newFileArray']);
						if (isset($resultMsg['orgFileArray']))
							$resultDataArray['orgFileArray'] = TDCommon::array_smerge($resultDataArray['orgFileArray'], $resultMsg['orgFileArray']);
						if (isset($resultMsg['specialValidateErrorFields']))
							$resultDataArray['specialValidateErrorFields'] = TDCommon::array_smerge($resultDataArray['specialValidateErrorFields'], $resultMsg['specialValidateErrorFields']);
					}
					if (!empty($appendModel)) {
						if (!isset($this->appendModelArray[$appendModelStr])) {
							$this->appendModelArray[$appendModelStr] = $appendModel;
						}
					}
				}
			}
		}
		$this->autoSetValueAndDefaultNull();
		return $resultDataArray;
	}

	//formula 输入类型用于在设置其他值之后再验证设置，因为有些字段值是在 runModuleFormSavePHPCode , runModuleFormModulePHPCode 中设置
	private $setColumnData_Formula_Itests = array();

	public function run_setColumnData_Formula_Items($resultDataArray = array()) {
		foreach ($this->setColumnData_Formula_Itests as $item) {
			$resultMsg = $this->setColumnDataItem($item["fieldColumn"], $item["model"], $item["appendModel"], true);
			if (!empty($resultMsg)) {
				if (empty($resultDataArray)) {
					$resultDataArray['newFileArray'] = array();
					$resultDataArray['orgFileArray'] = array();
					$resultDataArray['specialValidateErrorFields'] = array();
				}
				if (isset($resultMsg['newFileArray']))
					$resultDataArray['newFileArray'] = TDCommon::array_smerge($resultDataArray['newFileArray'], $resultMsg['newFileArray']);
				if (isset($resultMsg['orgFileArray']))
					$resultDataArray['orgFileArray'] = TDCommon::array_smerge($resultDataArray['orgFileArray'], $resultMsg['orgFileArray']);
				if (isset($resultMsg['specialValidateErrorFields']))
					$resultDataArray['specialValidateErrorFields'] = TDCommon::array_smerge($resultDataArray['specialValidateErrorFields'], $resultMsg['specialValidateErrorFields']);
			}
		}
		return $resultDataArray;
	}

	private function setColumnDataItem($fieldColumn, $model,$appendModel = null, $runFormulaItem = false) {
		$resultMsg = null;
		//the $fieldColumn must from $model tableName sys column
		//$tmptbid = TDTableColumn::getTableCollectionID($model->tableName);
		//if (!TDTableColumn::checkColumnIdIsFromTable($fieldColumn->tableColumnId, $tmptbid)) { return $resultMsg; }
		$inputType = TDTableColumn::getInputTypeByColumnId($fieldColumn->tableColumnId);
		if (!$runFormulaItem && $inputType == "Fie_formula") {
			$this->setColumnData_Formula_Itests[] = array(
				'fieldColumn' => $fieldColumn,
				'model' => $model,
				'appendModel' => $appendModel
			);
			return $resultMsg;
		}
		if (method_exists($inputType, 'saveData')) {
			//foreignKey 输入框,为多选的情况处理
			if ($inputType == "Fie_foreignkey") {
				if (isset($this->fixedAttributes[$fieldColumn->baseFieldName]) && !is_null($this->fixedAttributes[$fieldColumn->baseFieldName])) {
					$idValues = $this->fixedAttributes[$fieldColumn->baseFieldName];
					if (strpos($idValues, ',') !== false) {
						$idValues = explode(',', $idValues);
						$this->fixedAttributes[$fieldColumn->baseFieldName] = $idValues[0];
						$lessToAdd = array();
						foreach ($idValues as $idIndex => $idite) {
							if ($idIndex > 0 && !empty($idite)) {
								$lessToAdd[] = $idite;
							}
						}
						if (count($lessToAdd) > 0) {
							$this->lessToRunMoreTimesSaveColumnId_ForeignIds[$fieldColumn->tableColumnId] = $lessToAdd;
						}
					}
				} else if (TDField::getFormPostData($fieldColumn->baseFieldName) !== null) {
					$idValues = TDField::getFormPostData($fieldColumn->baseFieldName);
					if (strpos($idValues, ',') !== false) {
						$idValues = explode(',', $idValues);
						$_POST[TDStaticDefined::$formModelName][$fieldColumn->baseFieldName] = $idValues[0];
						$lessToAdd = array();
						foreach ($idValues as $idIndex => $idite) {
							if ($idIndex > 0 && !empty($idite)) {
								$lessToAdd[] = $idite;
							}
						}
						if (count($lessToAdd) > 0) {
							$this->lessToRunMoreTimesSaveColumnId_ForeignIds[$fieldColumn->tableColumnId] = $lessToAdd;
						}
					}
				}
			}
			$params = array(
				'model' => $model,
				'appendModel' => $appendModel,
				'columnAppStr' => TDTableColumn::getColumnAppendStr($fieldColumn->tableColumnId, $fieldColumn->belongOrderColumnIds),
				'columnName' => TDTableColumn::getColumnDBName($fieldColumn->tableColumnId),
				'dbType' => TDTableColumn::getColumnDBType($fieldColumn->tableColumnId),
				'belongOrderColumnIds' => $fieldColumn->belongOrderColumnIds,
				'tableColumnId' => $fieldColumn->tableColumnId,
				'fieldName' => $fieldColumn->baseFieldName,
				'fieldId' => $fieldColumn->baseFieldId,
				'fixedValue' => isset($this->fixedAttributes[$fieldColumn->baseFieldName]) ? $this->fixedAttributes[$fieldColumn->baseFieldName] : null,
			);
			$fie = new $inputType();
			$resultMsg = $fie->saveData($params);
		}
		return $resultMsg;
	}

	private function autoSetValueAndDefaultNull() {
		$inputRows = TDModelDAO::queryAll(TDTable::$too_table_column_input,'`is_auto_set_value`=1',"id");
		$inputIds = '';
		foreach ($inputRows as $row) {
			if (!empty($inputIds)) {
				$inputIds .= ',';
			}
			$inputIds .= $row["id"];
		}
		if (empty($inputIds)) {
			$inputIds = '-1';
		}
		$appendModelArray = $this->appendModelArray;
		$appendModelArray['baseModel'] = $this->model;
		foreach ($appendModelArray as $tmpIndex => $tmpModel) {
			$tableName = $tmpModel->tableName;
			$columnRows = TDModelDAO::queryAll(TDTable::$too_table_column,'`table_collection_id`=\'' . TDTableColumn::getTableCollectionID($tableName)
					. '\' and `table_column_input_id` in (' . $inputIds . ') ',"id");
			foreach ($columnRows as $row) {
				$fieldName = TDField::createFieldIdOrName($row["id"], null, true);
				$fieldColumn = TDFieldColumn::createBuyFieldName($fieldName);
				$this->setColumnDataItem($fieldColumn, $tmpModel);
			}
			foreach (TDTable::getTableObj($tableName, false)->columns as $col => $tmpColumn) {
				$value = $tmpModel->getAttribute($col);
				if (empty($value) && !is_numeric($value)) {
					$tmpModel->setAttribute($col, TDCreateSQL::formatDefault($tmpColumn->defaultValue));
				}
			}
		}
	}

	private function modelRunAutoSetCode($model) {
		$tableName = $model->tableName;
		$columnRows = TDModelDAO::queryAll(TDTable::$too_table_column,'`table_collection_id`=\''.TDTableColumn::getTableCollectionID($tableName)
				. '\' and `auto_set_code` !="" and `auto_set_code` is not null','`name`,`auto_set_code`');
		foreach ($columnRows as $row) {
			$columnName = $row["name"];
			$model->setAttribute($columnName,Fie_formula::getValue($model,$row["auto_set_code"]));
		}
	}

	private function runAutoSetColumnsSetValue() {
		$appendModelArray = $this->appendModelArray;
		$appendModelArray['baseModel'] = $this->model;
		foreach ($appendModelArray as $tmpIndex => $tmpModel) {
			$this->modelRunAutoSetCode($tmpModel);
		}
	}

	
	private function modelRunColumnValidate($model,$appendModelStr) {
		$tableName = $model->tableName;
		$columnRows = TDModelDAO::queryAll(TDTable::$too_table_column,'`table_collection_id`=\''.TDTableColumn::getTableCollectionID($tableName).'\' and `form_save_validate` !="" and `form_save_validate` is not null','`id`,`form_save_validate`');
		foreach ($columnRows as $row) {
			$errorMsg = Fie_formula::getValue($model,$row["form_save_validate"]);
			if(!empty($errorMsg)) {
				$columnId = $row["id"];
				if ($appendModelStr == 'baseModel') {
					$fieldId = TDField::createFieldIdOrName($columnId);
				} else {
					$fieldId = TDField::createFieldIdOrName($columnId, TDTableColumn::getBelongOrderColumnIds($this->model,$appendModelStr));
				}
				$this->logErrorMsg($fieldId,$errorMsg);
			}
		}
	}

	private function runColumnSaveValidate() {
		$appendModelArray = $this->appendModelArray;
		$appendModelArray['baseModel'] = $this->model;
		foreach ($appendModelArray as $appendModelStr => $tmpModel) {
			$this->modelRunColumnValidate($tmpModel,$appendModelStr);
		}	
	}

	public function getErrorStr() {
		$str = '';
		foreach ($this->validateErrorFields as $errorItem) {
			if (!empty($str))
				$str .= ';';
			$str .= $errorItem['msg'];
		}
		foreach ($this->validateOtherErrors as $errorItem) {
			if (!empty($str))
				$str .= ';';
			$str .= $errorItem['msg'];
		}
		return $str;
	}

	private function runModuleFormSavePHPCode() { if (!empty($this->moduleId)) { $code = TDModelDAO::queryScalarByPk(TDTable::$too_module,$this->moduleId,"form_save_php_code"); if (!empty($code)) { $model = $this->model; eval($code); } } }
	private function runModuleAfterSavePHPCode() { if (!empty($this->moduleId)) { $code = TDModelDAO::queryScalarByPk(TDTable::$too_module,$this->moduleId,"after_save_code"); if (!empty($code)) { $model = $this->model; eval($code); } } }
	private function runModuleAfterCommitPHPCode() { if (!empty($this->moduleId)) { $code = TDModelDAO::queryScalarByPk(TDTable::$too_module,$this->moduleId,"form_after_commit"); if (!empty($code)) { $model = $this->model; eval($code); } } }
	private function runModuleFormModuleDefaultRelation() {
		$moduleFormModulePkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
		$rowPkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_ROW_PKID);
		if (!empty($moduleFormModulePkId) && !empty($rowPkId)) {
			$moduleFormModule = TDModelDAO::queryRowByPk(TDTable::$too_module_formmodule,$moduleFormModulePkId);
			if (!empty($moduleFormModule["default_relation_column"])) {
				$data = TDModelDAO::getModel(TDModule::getModuleTableName($moduleFormModule["form_module_id"]), $rowPkId);
				TDFormat::setModelAppendColumnValue($this->model, Fie_laddercolumn::getColumnNameStr($moduleFormModule["default_relation_column"]), $data->primaryKey);
			}
		}
	}
	private function runModuleFormModulePHPCode() {
		$moduleFormModulePkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
		$rowPkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_ROW_PKID);
		if (!empty($moduleFormModulePkId) && !empty($rowPkId)) {
			$moduleFormModule = TDModelDAO::queryRowByPk(TDTable::$too_module_formmodule,$moduleFormModulePkId);
			if (!empty($moduleFormModule["ntable_set_code"])) {
				$model = $this->model;
				if (!empty($moduleFormModule["form_module_id"])) {
					$data = TDModelDAO::getModel(TDModule::getModuleTableName($moduleFormModule["form_module_id"]),$rowPkId);
					eval($moduleFormModule["ntable_set_code"]);
				}
			}
		}
	}

	public function runOtherMoreRows() {
		$appendModel = $this->model;
		foreach ($this->lessToRunMoreTimesSaveColumnId_ForeignIds as $lessColumnId => $lessAddIds) {
			$lessToTabName = TDTableColumn::getColumnTableDBName($lessColumnId);
			if ($appendModel->tableName == $lessToTabName) {
				$lessToColumnName = TDTableColumn::getColumnDBName($lessColumnId);
				foreach ($lessAddIds as $idItem) {
					$addLessModel = TDModelDAO::getModel($lessToTabName);
					foreach ($appendModel->attributes as $col => $val) {
						if ($col != 'id') {
							$addLessModel->$col = $val;
						}
					}
					$addLessModel->$lessToColumnName = $idItem;
					$tmpObj = new TDFormValidateSave($addLessModel, array(), array(), $this->moduleId);
					$tmpObj->runSaveFlow(TDCommon::$outputErrorType_alert, false, false);
					
				}
			}
		}
	}

	public function runSaveFlow($monitorExceptionErrorType, $useException = true, $useTransaction = true) {
		if ($useTransaction)
			$trans = TDModelDAO::getDB($this->model->tableName)->beginTransaction();
		if ($useException) {
			//在操作升级时要关闭掉下面代码
			//TDCommon::monitorExceptionError($monitorExceptionErrorType);
		}
		$this->runModuleFormModuleDefaultRelation();
		$result = $this->setModelFormData();
		$result = $this->run_setColumnData_Formula_Items($result);
		$validate = array();
		$orgFileArray = isset($result['orgFileArray']) ? $result['orgFileArray'] : array();
		$newFileArray = isset($result['newFileArray']) ? $result['newFileArray'] : array();
		$specialValidateErrorFields = isset($result['specialValidateErrorFields']) ? $result['specialValidateErrorFields'] : array();
		$validate = TDCommon::array_smerge($validate, $specialValidateErrorFields);
		if (empty($validate)) {
			$this->runModuleFormModulePHPCode();
			//运行自动赋值的字段的代码
			$this->runAutoSetColumnsSetValue();
			//单个字段执行验证代码
			$this->runColumnSaveValidate();
			$this->runModuleFormSavePHPCode();
		}
		$validateUniqueAndExpandRules = $this->validateUniqueAndExpandRules();
		$validate = TDCommon::array_smerge($validate, $validateUniqueAndExpandRules);
		$validate = TDCommon::array_smerge($this->outside_validate_errors, $validate);
		
		if(TDModelDAO::queryScalarByPk(TDTable::$too_module,$this->moduleId,"is_simulate_form") == 1) { return; }

		$this->validateSave(empty($validate), true);

		$validate = TDCommon::array_smerge($validate, $this->validateErrorFields);
		$this->filterErrorField();
		$normal = $this->validatePassFields;
		foreach ($normal as $norIndex => $passfidItem) {
			foreach ($specialValidateErrorFields as $erfidItem) {
				if ($erfidItem['fieldID'] == $passfidItem['fieldID']) {
					unset($normal[$norIndex]);
				}
			}
			foreach ($validateUniqueAndExpandRules as $erfidItem) {
				if ($erfidItem['fieldID'] == $passfidItem['fieldID']) {
					unset($normal[$norIndex]);
				}
			}
		}
		$normal = array_merge($normal);
		if (empty($validate)) {
			foreach ($orgFileArray as $key => $value) {
				if (is_file($value))
					unlink($value);
			}
		} else {
			foreach ($newFileArray as $key => $value) {
				if (is_file($value))
					unlink($value);
			}
		}
		$this->validatePass = $normal;
		$this->validateUnPass = $validate;
		if (empty($validate) && empty($this->validateErrorFields) && empty($this->validateOtherErrors)) {
			$this->runModuleAfterSavePHPCode();
			unset($_POST);
			$this->runOtherMoreRows();
			if ($useTransaction)
				$trans->commit();
			$this->runModuleAfterCommitPHPCode();

		} else {
			if ($useTransaction)
				$trans->rollback();
		}
	}

}
