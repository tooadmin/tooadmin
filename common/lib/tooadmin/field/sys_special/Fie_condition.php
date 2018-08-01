<?php
class Fie_condition extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$pkId = $params['model']->primaryKey;
			if(empty($pkId)) { $pkId = 0; }
			$html = CHtml::textField($columnFormData['name'],$columnFormData['value'],
			array('id'=>$columnFormData['id'],'readonly'=>'readonly'));
			$tableId = $params['model']->table_id; 
			$html .= '&nbsp;<button class="btn" type="button" '
			.' onclick="popupWindow(\''.TDLanguage::$UnitActionController_PopupConditionEdit.'\',\''
			.TDPathUrl::createUrl(tDUnitAction/popupConditionEdit).'/condition_table_id/'.$tableId.'/condition_pk_id/'.$pkId.'\',1100,400)">'
			. '<i class="icon-edit"></i>'.TDLanguage::$condition_button_edit.'</button>';
			$analyzeId = TDField::createFieldIdOrName(TDTableColumn::getColumnIdByTableAndColumnName(TDTableColumn::getColumnTableDBName($params['tableColumnId'])
			,"analyze_data"),$params['belongOrderColumnIds']);
			$html .= '
			<script>
    			function condtionSetData(sql,jsonstr) {
				$("#'.$columnFormData['id'].'").val(sql);
				$("#'.$analyzeId.'").val(jsonstr);;
    			} 	
			</script>';
			return $html;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = $columnData['value'];
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $columnData['value'],
			);
			return $result;	
		}
		public function viewHtml($params) {
			return $params['value'];
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}	
		}

		public function search($params) {
				
		}

		public function editTableColumn($params) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:50px;margin-bottom: 0px;\" timeajax=\"1\" />";
			if(strpos($params['dbType'],"varchar") !== false || strpos($params['dbType'],"text") !== false) {
				$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
				."\" style=\"width:160px;margin-bottom: 0px;\" timeajax=\"1\" />";
			}
			return $result;
		}


		public static function getColumnConditionInputType($tableColumnId,$inputValue='',$baseLinkUrl='') {
			$inputTypeClassName = TDTableColumn::getInputTypeByColumnId($tableColumnId);	
			$inputType = TDTableColumn::getInputTypeByColumnId($tableColumnId,false);	
			if($inputType != 'selectdb' && TDTableColumn::checkColumnIsForeignkey($tableColumnId)) {
				$inputTypeClassName = "Fie_foreignkey";	
				$inputType = str_replace("Fie_","",$inputTypeClassName);
			}
			$rowModel = null;
			if(!empty($baseLinkUrl)) {
				//title="/tDCommon/admin/moduleId/433/mnInd/0/topmnInd/0/pageLayoutType/inner/MODULE_FORM_MODULE_ID/68/MODULE_ROW_PKID/152/MODULE_READONLY/0"
				if(strpos($baseLinkUrl,TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID) !== false) {
					$moduleFormModuleId = explode(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID,$baseLinkUrl);
					$moduleFormModuleId = $moduleFormModuleId[1];	
					$moduleFormModuleId = explode('/',$moduleFormModuleId);
					$moduleFormModuleId = $moduleFormModuleId[1];	
					if(!empty($moduleFormModuleId) && is_numeric($moduleFormModuleId)) {
						$rowModel = TDModelDAO::getModel(TDModule::getModuleTableName(TDModelDAO::getFieldById(TDTable::$too_module_formmodule,$moduleFormModuleId,"ntable_module_id")));
						//MODULE_ROW_PKID
						$moduleRowPkId = explode(TDStaticDefined::$PARAM_MODULE_ROW_PKID,$baseLinkUrl);
						$moduleRowPkId = $moduleRowPkId[1];	
						$moduleRowPkId = explode('/',$moduleRowPkId);
						$moduleRowPkId = $moduleRowPkId[1];	
						if(!empty($moduleRowPkId) && is_numeric($moduleRowPkId)) {
							$moduleFormModule = TDModelDAO::getModel(TDTable::$too_module_formmodule,$moduleFormModuleId);
							$baseFormRow = TDModelDAO::getModel(TDTableColumn::getTableDBNameByModuleId($moduleFormModule->form_module_id),$moduleRowPkId);
							if(!empty($moduleFormModule->default_relation_column)) {
								$col = TDTableColumn::getColumnDBName($moduleFormModule->default_relation_column);
								$rowModel->$col = $baseFormRow->primaryKey; 
							}
						}
					}
				}
			}
			
			$conditionTypes = TDSearch::getConditionTypeArray(TDTableColumn::getColumnDBType($tableColumnId),$inputType);	
			$options = array();
			foreach($conditionTypes as $key => $value) { 
				$options[] = array('value' => $key,'label' => $value,);
			}	
			$fieldName = 'advSearch_fieldValue[]';
			$fieldId =  '';
			$inputHtml = '<input type="text" id="'.$fieldId.'" name="'.$fieldName.'" value="'
			.$inputValue.'" style="'.TDCommonCss::$search_input_style.'"  />';	
			$params = array(
				'fieldName' => $fieldName,
				'fieldId' => $fieldId,
				'tableColumnId' => $tableColumnId,
				'value' => $inputValue,
				'rowModel' => $rowModel
			);
			if(method_exists($inputTypeClassName,"search")) {
				$fie = new $inputTypeClassName();
				$result = $fie->search($params);
				if(!empty($result)) {
					$inputHtml = $result;
				}
			}
			return array(
				'options' =>$options,
				'inputHtml' => $inputHtml,
			);
		}
		public static function getConditionColumns($tableCollectionId,$belongStr,$onlyForPageSearch=false) {
			$tableName = TDTableColumn::getTableDBName($tableCollectionId);
			///$model = TDModelDAO::getModel(TDTableColumn::getTableDBName($tableCollectionId));
			$result = array();
			$result[] = array(
				'value' => '',
				'belongstr' => '',
				'foreigntableid' => '',
				'label' => TDLanguage::$please_choose,
			);
			$allowSearchColumns = TDSearch::getAllowToSearchColumns($tableName);
			$table = TDTable::getTableObj($tableName,false);
			foreach($allowSearchColumns as $column) {
				$foreigntableid = '';
				$newBelongStr = $belongStr;
				$columnId = TDTableColumn::getColumnIdByTableAndColumnName($tableName,$column->name);
				$foreign_table_column_id = TDTableColumn::getColumnForeignColumnId($columnId);
				if($column->isForeignKey && isset($table->foreignKeys[$column->name])) {
					$foreigntableid = TDTableColumn::getTableCollectionID($table->foreignKeys[$column->name][0]); 
					$newBelongStr .= "_".$columnId."_".$foreigntableid;
				} else if(!empty($foreign_table_column_id)) {
					if($onlyForPageSearch && TDTableColumn::getInputTypeByColumnId($columnId) == "Fie_selectdb") {
						
					} else{
						$foreignTableName = TDTableColumn::getColumnTableDBName($foreign_table_column_id);
						$foreigntableid = TDTableColumn::getTableCollectionID($foreignTableName);
						$newBelongStr .= "_".$columnId."_".$foreigntableid;		
					}
				} else if(TDTableColumn::getInputTypeByColumnId($columnId) == "Fie_foreignkey") {
					$foreigntableid = TDTableColumn::getMapTableCollectionId($columnId);
					$newBelongStr .= "_".$columnId."_".$foreigntableid;
				}
				$result[] = array(
					'value' => $columnId,
					'belongstr' => $newBelongStr,
					'foreigntableid' => $foreigntableid,
					'label' => TDTableColumn::getColumnLabelName($columnId),
				);
			}
			return $result;
		}
		public static function createColumnsSelect($columns,$belongStr,$selectedValue='',$markMuduleIdStr='') {
			$loadTbColumns = "";
			$loadTbColumns .= "<input type=\"hidden\" name=\"advSearch_belongStr[]\" value=\"".$belongStr."\" >";
			$loadTbColumns .= "<select name=\"advSearch_columnId[]\" onchange=\"loadSearchCon(this,'".$markMuduleIdStr."')\" style=\"width:auto !important;\">";
			for($i=0; $i<count($columns); $i++) {
				$selected = "";
				if($selectedValue !== '' && $selectedValue == $columns[$i]['value']) {
					$selected = " selected=\"selected\" ";
				}
				$loadTbColumns .= "<option value=\"".$columns[$i]['value']."\" belongstr=\"".$columns[$i]['belongstr']
				."\"  foreigntableid=\"".$columns[$i]['foreigntableid']."\" ".$selected." >".$columns[$i]['label']."</option>";	
			}	
			$loadTbColumns .= "</select>";		
			return $loadTbColumns;
		}
		public static function createConditionTypesSelect($options,$selectedValue='') {
			$html = "<select name=\"advSearch_conditionType[]\" style=\"".TDCommonCss::$search_condition_type_style."\" >";
			for($i=0; $i<count($options); $i++) {
				$selected = $options[$i]['value'] == $selectedValue ? " selected=\"selected\" " : "";
				$html .= "<option value=\"".$options[$i]['value']."\" ".$selected." >".$options[$i]['label']."</option>";
			}
			$html .= "</select>";
			return $html;
		}
		public static function getTableStartHtml() {
			return  "<table ><tbody>";
		}
		public static function getTableEndHtml() {
			return 	"</tbody></table>";
		}
		public static function createConditionRow($loadTbColumns,$isChirld,$chooseConditionHtml,$inputHtml,$combNum,$childAnalyzeHtml='',$isLastRow=false,$markMuduleIdStr='',$isUseComp=false) {
			$searchButton = TDLanguage::$advanced_search_create_condition ; 
			$tableHtml = ""
			."<tr>"
			."<td class='labName'><input type='text' class='choNum' name='advSearch_combinationNum[]' style='display:none;' value='".$combNum."'/>"
			."<label class='control-label md_search_lable'>".(empty($combNum) ? TDLanguage::$choose_condition_column : 
			TDLanguage::$choose_condition_combination."c".$combNum."b")."</label></td>"
			."<td class='choColumns'>".$loadTbColumns."</td>"
			."<td class='choCondition'>".$chooseConditionHtml."</td>"
			."<td class='choInput'>".$inputHtml.$childAnalyzeHtml."</td>"
			."<td class='opeButton' style='display:".($isLastRow ? "block;" : "none;")."'>";
			if(!$isChirld) {
				$tableHtml .= "<button type='buttom' id='subbut".time()."' onclick=\"combinationFormulaSearch('".$markMuduleIdStr."')\" class='btn btn-primary'><i class='icon icon-white icon-search'></i>".$searchButton."</button>"; 
			}
			$tableHtml .= "<button type='button' class='btn' onclick='addRow(this)'><i class='icon icon-blue icon-plus'></i></button>"
			.($isUseComp ? "<button type='button' class='btn' onclick=\"combinationCond(this,'".$markMuduleIdStr."')\">"
			."<i class='icon icon-blue icon-pin'></i></button>" : "")."</td>";
			$tableHtml .= 
			"<td class='opeDelete' style='display:".($isLastRow ? "none;" : "block;")."'>"
			."<button type='button' class='btn' onclick='$(this).parent().parent().remove();'><i class='".TDThemeDifPart::removeIcon()."'></i></button>"
			."</td>";
			$tableHtml .= "</tr>";
			return $tableHtml;
		}
		public static function getHasChildIndexArray($parentIndex,$belongStrs,$columnIds,$fieldValues) {
			$array = array();
			for($i=$parentIndex+1; $i<count($fieldValues); $i++) {
				if(strpos($belongStrs[$i],"_".$columnIds[$parentIndex]."_") !== false) {
					$array[] = $i;
				} 
			}	
			return $array;
		}
		public static function getChildAnalyzeHtml($parentIndex,$belongStrs,$columnIds,$conditionTypes,$fieldValues,$combinationNum,$markMuduleIdStr) {
			$analyzeHtml = '';
			$getHasChildIndexArray = self::getHasChildIndexArray($parentIndex, $belongStrs, $columnIds,$fieldValues);
			if(count($getHasChildIndexArray) > 0) {
				$analyzeHtml .= self::getTableStartHtml();
			}
			for($ci=0; $ci<count($getHasChildIndexArray); $ci++) {
				$hasChildIndex = $getHasChildIndexArray[$ci];
				$hasChildChildIndexArray = self::getHasChildIndexArray($hasChildIndex, $belongStrs, $columnIds,$fieldValues);	
				$childChildAnalyzeHtml = "";
				for($cci=0; $cci<count($hasChildChildIndexArray); $cci++) {
					$hasChildChildIndex = $hasChildChildIndexArray[$cci];
					$childChildAnalyzeHtml .= self::getChildAnalyzeHtml($hasChildChildIndex, $belongStrs, $columnIds, $conditionTypes, $fieldValues, $combinationNum,$markMuduleIdStr);
				}	
				$columnTableId = TDTableColumn::getColumnTableCollectionId($columnIds[$hasChildIndex]);
				$columns = self::getConditionColumns($columnTableId,$belongStrs[$hasChildIndex]);
				$chooseColumnsHtml = self::createColumnsSelect($columns,$belongStrs[$hasChildIndex],$columnIds[$hasChildIndex],$markMuduleIdStr); 		
				$inputTypeResult = self::getColumnConditionInputType($columnIds[$hasChildIndex],
				is_array($fieldValues[$hasChildIndex]) ? implode(",",$fieldValues[$hasChildIndex]) : $fieldValues[$hasChildIndex]);
				$chooseConditionHtml = self::createConditionTypesSelect($inputTypeResult['options'],$conditionTypes[$hasChildIndex]);
				$inputHtml = $inputTypeResult['inputHtml'];
				$combNum = isset($combinationNum[$hasChildIndex]) ? $combinationNum[$hasChildIndex] : '';
				$analyzeHtml .= self::createConditionRow($chooseColumnsHtml,true,$chooseConditionHtml
				,$inputHtml,$combNum,$childChildAnalyzeHtml,$ci == count($getHasChildIndexArray)-1,$markMuduleIdStr);		
			}
			if(count($getHasChildIndexArray) > 0) {
				$analyzeHtml .= self::getTableEndHtml();
			}
			return $analyzeHtml;
		}
		public static function getAnalyzeHtml($analyzeData,$baseTableId,$markMuduleIdStr) {
			$baseBelongStr = "tc_".$baseTableId;
			$belongStrs = $analyzeData['belongStrs'];	
			$columnIds = $analyzeData['columnIds'];
			$conditionTypes = $analyzeData['conditionTypes'];
			$fieldValues = $analyzeData['fieldValues'];
			$combinationNum = $analyzeData['combinationNum'];
			$analyzeHtml = "";
			//belong analyzeHtml
			$baseConditionCount = 0;
			for($i=0; $i<count($fieldValues); $i++) { 
				if($belongStrs[$i] == $baseBelongStr) { 
					$baseConditionCount++; 
				}
			}
			if($baseConditionCount > 0) {
				$analyzeHtml .= self::getTableStartHtml();
			}
			$runCount = 0;
			for($i=0; $i<count($fieldValues); $i++) {
				if($belongStrs[$i] != $baseBelongStr) { continue; }
				$columnTableId = TDTableColumn::getColumnTableCollectionId($columnIds[$i]);
				$columns = self::getConditionColumns($columnTableId,$belongStrs[$i]);
				$chooseColumnsHtml = self::createColumnsSelect($columns,$belongStrs[$i],$columnIds[$i],$markMuduleIdStr); 		
				$inputTypeResult = self::getColumnConditionInputType($columnIds[$i],
				is_array($fieldValues[$i]) ? implode(",",$fieldValues[$i]) : $fieldValues[$i]);
				$chooseConditionHtml = self::createConditionTypesSelect($inputTypeResult['options'],$conditionTypes[$i]);
				$inputHtml = $inputTypeResult['inputHtml'];
				$combNum = isset($combinationNum[$i]) ? $combinationNum[$i] : '';
				$childAnalyzeHtml = self::getChildAnalyzeHtml($i,$belongStrs,$columnIds,$conditionTypes,$fieldValues,$combinationNum,$markMuduleIdStr);
				$runCount++;
				$analyzeHtml .= self::createConditionRow($chooseColumnsHtml,false,$chooseConditionHtml
				,$inputHtml,$combNum,$childAnalyzeHtml,$runCount == $baseConditionCount,$markMuduleIdStr);			
			}	
			if($baseConditionCount > 0) {
				$analyzeHtml .= self::getTableEndHtml();
			}
			return $analyzeHtml;
		}
		
		public static function getForGridViewConditionSQL($conditionId) { return TDModelDAO::queryScalarByPk(TDTable::$too_condition, $conditionId, "sql_condition"); }
		
}
