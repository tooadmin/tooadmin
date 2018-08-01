<?php

class Fie_foreignkey extends TDField {

	public static function getInputTypeId() {
		return 12;
	}

	public static function getInputTypeStr() {
		return 'foreignkey';
	}

	public static function createTextId($fieldId) {
		return $fieldId . "_foreigntext";
	}

	public static function getFieldText($tableColumnId, $fieldValue) {
		/*
		  $textValue = "";
		  if(!empty($fieldValue)) {
		  $fieldValue = explode(",",$fieldValue);
		  foreach($fieldValue as $item) {
		  $columnModel = TDModelDAO::getModel(TDTable::$too_table_column,$tableColumnId);
		  $columnAppName = Fie_laddercolumn::getColumnNameStr($columnModel->value_laddercolumn);
		  $mapTableName = TDTableColumn::getTableDBName($columnModel->map_table_collection_id);
		  if(!empty($mapTableName)) {
		  $forMd = TDModelDAO::getModel($mapTableName,$item);
		  if(!empty($textValue)) {
		  $textValue .= "  ";
		  }
		  //$textValue .= TDFormat::getModelAppendColumnValue($forMd,$columnAppName);
		  $colArr = explode(",",$columnModel->value_laddercolumn);
		  $textValue .= TDField::gettValueByFormatView($forMd,$colArr[count($colArr)-1]);
		  $appendValueColumnAppStr = !empty($columnModel->append_laddercolumn) ? TDTableColumn::getLadderColumnAppendStr($columnModel->append_laddercolumn) : "";
		  if(!empty($appendValueColumnAppStr)) {
		  //$appValue = TDFormat::getModelAppendColumnValue($forMd,$appendValueColumnAppStr);
		  $appColumnId = Fie_laddercolumn::getLadderColumnLastColumnId($columnModel->append_laddercolumn);
		  $appColumnModel = TDFormat::getBaseColumnLastModelFromAppendName($forMd,$appendValueColumnAppStr);
		  $appValue = TDField::gettValueByFormatView($appColumnModel,$appColumnId);
		  $textValue .= " 【".$appValue."】";
		  }
		  }
		  }
		  }
		  return $textValue;
		 */
		$textValue = "";
		if (!empty($fieldValue)) {
			$fieldValue = explode(",", $fieldValue);
			foreach ($fieldValue as $item) {
				$columnModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column, $tableColumnId,"value_laddercolumn,map_table_collection_id,append_laddercolumn");
				$columnAppName = Fie_laddercolumn::getColumnNameStr($columnModel["value_laddercolumn"]);
				$mapTableName = TDTableColumn::getTableDBName($columnModel["map_table_collection_id"]);
				if (!empty($mapTableName) && !empty($columnAppName)) {
					$forMd = TDModelDAO::getModel($mapTableName, $item,true);
					$valueColumnAppStr = !empty($columnModel["value_laddercolumn"]) ? TDTableColumn::getLadderColumnAppendStr($columnModel["value_laddercolumn"]) : "";
					$appColumnId = Fie_laddercolumn::getLadderColumnLastColumnId($columnModel["value_laddercolumn"]);
					$appColumnModel = TDFormat::getBaseColumnLastModelFromAppendName($forMd, $valueColumnAppStr);
					//$textValue .= TDField::gettValueByFormatView($appColumnModel, $appColumnId);
					$textValue .= TDField::getFormatPid($appColumnModel, $appColumnId);
					$appendValueColumnAppStr = !empty($columnModel["append_laddercolumn"]) ? TDTableColumn::getLadderColumnAppendStr($columnModel["append_laddercolumn"]) : "";
					if (!empty($appendValueColumnAppStr)) {
						//$appValue = TDFormat::getModelAppendColumnValue($forMd,$appendValueColumnAppStr); 
						$appColumnId = Fie_laddercolumn::getLadderColumnLastColumnId($columnModel["append_laddercolumn"]);
						$appColumnModel = TDFormat::getBaseColumnLastModelFromAppendName($forMd, $appendValueColumnAppStr);
						//$appValue = TDField::gettValueByFormatView($appColumnModel, $appColumnId);
						$appValue = TDField::getFormatPid($appColumnModel, $appColumnId);
						if(!empty($appValue)) {
							$textValue .= " 【" . $appValue . "】";
						}
					}
				}
			}
		}
		return $textValue;
	}

	public function editForm($params) {
		$tmpModuleId = FieldRule::getModuleId($params['tableColumnId']);
		$popupTableName = "";
		if (!empty($tmpModuleId)) {
			$popupTableName = TDModule::getModuleTableName($tmpModuleId);
		}
		if ($popupTableName == "erp_employee") {
			$columnFormData = $params['columnFormData'];
			$textFieldId = Fie_foreignkey::createTextId($columnFormData['id']);
			$result = CHtml::textField($columnFormData['name'], $columnFormData['value'], array('id' => $columnFormData['id'], 'style' => 'display:none;'));
			$textValue = self::getFieldText($params['tableColumnId'], $columnFormData['value']);
			$baseCID = $columnFormData['id'];
			$result .= '<script> 
					var timer_' . $baseCID . ' = null;
					function readID_' . $baseCID . '() { 
						$("#' . $baseCID . '_carid").val("");
						$("#' . $baseCID . 'btt").html("请刷卡.....");
			 			$("#' . $baseCID . '_carid").focus(); 
						timer_' . $baseCID . ' = setInterval("readID_' . $baseCID . '_finish(true)",1500);
					} 
					function readID_' . $baseCID . '_finish(finishTimeCheck) { 
						if(finishTimeCheck) {
							if($("#' . $baseCID . '_carid").val() == "") return;
							$.ajax({ type:"POST", dataType:"json",
								url:homeUrl+"/tDAjax/getPopupData/id/0",  
								data:"popupSearchColumnId=' . $params['tableColumnId'] . '&findCondition=`id_code`=\'"+$("#' . $baseCID . '_carid").val()+"\'",
        							success:function(data){  
									if(data.foreignId == "0") {
										alert("未找到对应数据!");
									} else {		
										//检测权限
										$.ajax({ type:"POST", dataType:"json",
											url:homeUrl+"/eRP/checkReadidPermission",  
											data:"readIDUserId="+data.foreignId,
        										success:function(ckdata){  
												if(ckdata.result == "fail") {
													alert("权限不够!");
												} else {
													$("#' . $columnFormData['id'] . '").val(data.foreignId); 
													if($("#' . $textFieldId . '").length > 0) {
														$("#' . $textFieldId . '").val(data.fieldText); 
													}
												}
        										}  
    										}); 	
									}
        							}  
    							}); 
						} else {
							if(timer_' . $baseCID . ' != null) alert("读卡失败!");
						}
						$("#' . $baseCID . '_carid").val("");
						clearInterval(timer_' . $baseCID . ');
						timer_' . $baseCID . ' = null;
						$("#' . $baseCID . 'btt").html("' . TDLanguage::$form_foreign_search_byReadCardID . '");
					}
					</script>
				';
			$result .= '<input type="text" style="width:1px;height:2px;margin-left:10px;'
					. 'margin-top:5px;;position: absolute;z-index:-1;" id="' . $baseCID . '_carid" onblur="readID_' . $baseCID . '_finish(false)" /><input type="text" id="'
					. $textFieldId . '" readonly="readonly" value="' . $textValue . '" />';
			$result .= '<button type="button" class="btn" onclick="readID_' . $baseCID . '()" ><li class="icon-search"></li><span id="'
					. $baseCID . 'btt">' . TDLanguage::$form_foreign_search_byReadCardID . '<span></button>';
			return $result;
		} else {
			$columnFormData = $params['columnFormData'];
			$textFieldId = Fie_foreignkey::createTextId($columnFormData['id']);
			$result = CHtml::textField($columnFormData['name'], $columnFormData['value'], array('id' => $columnFormData['id'], 'style' => 'display:none;'));
			$textValue = self::getFieldText($params['tableColumnId'], $columnFormData['value']);
			if($textValue != strip_tags($textValue)) { //是否包含html标签,包含则说明直接显示如图片输入框
				$result .= '<div id="' . $textFieldId . '">'.$textValue.'</div>';
			} else {
				$result .= '<input type="text" id="' . $textFieldId . '" readonly="readonly" value="' . $textValue . '" />';
			}
			$popupButton = '';
			if(!empty($columnFormData["columnData"]["module_id"])) {
				/*
				$popupButton = '<button type="button" class="btn" onclick="popupSearch(\'' . TDLanguage::$form_popup_saerch_title . '\',\''
					. TDStaticDefined::$popupSearchColumnIdStr . '\',\'' . $params['tableColumnId']
					. '\',\'' . TDStaticDefined::$popupSearchForeignFieldId . '\',\'' . $columnFormData['id'] . '\',\'' . TDStaticDefined::$OPERATE_TYPE_KEY . '\',
				\'' . TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH . '\',\'' . $params['model']->primaryKey . '\',\''
					. TDField::getFormModuleExpParamsForPopSerach() . '\')" ><li class="icon-search"></li>' . TDLanguage::$form_foreign_search . '</button>';
				*/
				if(!empty($params['tableColumnId'])) {
					$map_conditionCode = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params['tableColumnId'],'map_condition');
					if(!empty($map_conditionCode)) {
						$map_condition  = Fie_formula::getValue($params['model'],$map_conditionCode);
						Yii::app()->session["popup_condition_".$params['tableColumnId']."_".$params['model']->primaryKey] = $map_condition;
					}
				}
				$popupButton = '<button type="button" class="btn" onclick="popupSearch(\'' . TDLanguage::$form_popup_saerch_title . '\',\''
					. TDStaticDefined::$popupSearchColumnIdStr . '\',\'' . $params['tableColumnId']
					. '\',\'' . TDStaticDefined::$popupSearchForeignFieldId . '\',\'' . $columnFormData['id'] . '\',\'' . TDStaticDefined::$OPERATE_TYPE_KEY . '\',
				\'' . TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH . '\',\'' . $params['model']->primaryKey . '\',\''
					. '' . '\')" ><li class="icon-search"></li>' . TDLanguage::$form_foreign_search . 
				'</button>&nbsp;<button type="button" class="btn" onclick="inputFieldChooseCancel(\''.$columnFormData['id'].'\',\''.$textFieldId.'\')">'
					.TDLanguage::$form_foreign_search_cancel.'</button>';
				//检查是否存在组合唯一约束
				$uniqueColumnIds = '';
				$uniqueColumnFieldIds = '';
				$uniqueColumnDefaultValues = '';
				$uniqueColumnLabels = '';
				$uniqueRow = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$params['tableColumnId'],"unique1_laddercolumn,unique2_laddercolumn");
				if(!empty($uniqueRow["unique1_laddercolumn"])) {
					$uniqueColumnIds .= $uniqueRow["unique1_laddercolumn"];	
					$uniqueColumnFieldIds .= TDField::createFieldIdOrName($uniqueRow["unique1_laddercolumn"]);	
					//TDModelDAO::getFieldById(TDTableColumn::getColumnTableDBName($uniqueRow->unique1_laddercolumn),$uniqueRow->unique1_laddercolumn,'default_value','');
					$tmpColumnName = TDTableColumn::getColumnDBName($uniqueRow["unique1_laddercolumn"]);
					$uniqueColumnDefaultValues .= $params['model']->$tmpColumnName;
					$uniqueColumnLabels .= TDTableColumn::getColumnLabelName($uniqueRow["unique1_laddercolumn"]);
				}	
				if(!empty($uniqueRow["unique2_laddercolumn"])) {
					if(!empty($uniqueColumnIds)) {
						$uniqueColumnIds .= '---';
						$uniqueColumnFieldIds .= '---';
						$uniqueColumnDefaultValues .= '---';	
						$uniqueColumnLabels .= '---';	
					}
					$uniqueColumnIds .= $uniqueRow["unique2_laddercolumn"];	
					$uniqueColumnFieldIds .= TDField::createFieldIdOrName($uniqueRow["unique2_laddercolumn"]);	
					$tmpColumnName = TDTableColumn::getColumnDBName($uniqueRow["unique2_laddercolumn"]);
					$uniqueColumnDefaultValues .= $params['model']->$tmpColumnName;
					$uniqueColumnLabels .= TDTableColumn::getColumnLabelName($uniqueRow["unique2_laddercolumn"]);
				}
				if(!empty($uniqueColumnIds)) {
					/*
					$popupButton = '<button type="button" class="btn" onclick="popupSearchForUnique(\'' . TDLanguage::$form_popup_saerch_title . '\',\''
					. TDStaticDefined::$popupSearchColumnIdStr . '\',\'' . $params['tableColumnId']
					. '\',\'' . TDStaticDefined::$popupSearchForeignFieldId . '\',\'' . $columnFormData['id'] . '\',\'' . TDStaticDefined::$OPERATE_TYPE_KEY . '\',
					\'' . TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH . '\',\'' . $params['model']->primaryKey . '\',\''
					. TDField::getFormModuleExpParamsForPopSerach() . '\',\''.$uniqueColumnIds.'\',\''.$uniqueColumnFieldIds.'\',\''.
					$uniqueColumnDefaultValues.'\',\''.$uniqueColumnLabels.'\')" ><li class="icon-search"></li>' . TDLanguage::$form_foreign_search . '</button>';
					*/
					//TDField::getFormModuleExpParamsForPopSerach();
					$popupButton = '<button type="button" class="btn" onclick="popupSearchForUnique(\'' . TDLanguage::$form_popup_saerch_title . '\',\''
					. TDStaticDefined::$popupSearchColumnIdStr . '\',\'' . $params['tableColumnId']
					. '\',\'' . TDStaticDefined::$popupSearchForeignFieldId . '\',\'' . $columnFormData['id'] . '\',\'' . TDStaticDefined::$OPERATE_TYPE_KEY . '\',
					\'' . TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH . '\',\'' . $params['model']->primaryKey . '\',\''
					. '' . '\',\''.$uniqueColumnIds.'\',\''.$uniqueColumnFieldIds.'\',\''.
					$uniqueColumnDefaultValues.'\',\''.$uniqueColumnLabels.'\')" ><li class="icon-search"></li>' . TDLanguage::$form_foreign_search . 
					'</button>&nbsp;<button type="button" class="btn" onclick="inputFieldChooseCancel(\''.$columnFormData['id'].'\',\''.$textFieldId.'\')">'
					.TDLanguage::$form_foreign_search_cancel.'</button>';
				}	
			}
			$result .= $popupButton;
			return $result;
		}
	}

	public function gridView($params) {
		$columnData = $params["columnData"];
		$result = 'Fie_foreignkey::getFieldText(' . $params["tableColumnId"] . ',' . $columnData['value'] . ')';
		return $result;
	}

	public function viewData($params) {
		$columnData = self::getColumnFormData($params['tableColumnId'], $params['belongOrderColumnIds'], $params['model']);
		$result = array(
			'name' => $columnData['label'],
			'type' => 'raw',
			'value' => Fie_foreignkey::getFieldText($params["tableColumnId"], $columnData['value']),
		);
		return $result;
	}
	public function viewHtml($params) {
		return Fie_foreignkey::getFieldText($params["tableColumnId"],$params['value']);
	}

	public function saveData($params) {
		$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
		if (!is_null($value)) {
			TDFormat::setModelAppendColumnValue($params['model'], $params['columnAppStr'], $value);
		}
	}

	public function search($params) {
		return '<input type="hidden" value="' . $params['value'] . '" name="' . $params['fieldName'] . '" >';
	}

	public function editTableColumn($params) {
		
	}

}
