<?php

class Fie_selectdb extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$htmlOptins = array('empty'=> TDLanguage::$please_choose,'name'=>$columnFormData['name']
			,'id'=>$columnFormData['id'],'value'=>$columnFormData['value']);
			$changeEven = FieldRule::getChangeEvent($params['tableColumnId']);
			if(!empty($changeEven)) {
				$htmlOptins['onchange'] = $changeEven;
			}
			$editModel = $params['model']; 
			$optDBArray = FieldRule::getOPTGroupDBArray($params['tableColumnId'],$editModel);
			$adminBtnStyle = '';
			if(!empty($optDBArray)) {
				$result = TDMenuWidget::createSelectGroupSearch($optDBArray,$htmlOptins);
				$adminBtnStyle = 'style="margin-top:-16px;"';
			} else {
				$result = CHtml::dropDownList($columnFormData['name'],$columnFormData['value']
				,FieldRule::getDBArray($params['tableColumnId'],true,'',$editModel),$htmlOptins);	
			}
			if(!empty($columnFormData["columnData"]["module_id"])) {
				$result .= '<button type="button" '.$adminBtnStyle.' class="btn" onclick="popupSearch(\''.TDLanguage::$form_button_text_admin.'\',\''
				.TDStaticDefined::$popupSearchColumnIdStr.'\',\''.$params['tableColumnId']
				.'\',\''.TDStaticDefined::$popupSearchForeignFieldId.'\',\''.$columnFormData['id'].'\',\''.TDStaticDefined::$OPERATE_TYPE_KEY.'\',
				\''.TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH.'\',\''.$editModel->primaryKey.'\',\''
				.TDField::getFormModuleExpParamsForPopSerach().'\')" ><li class="icon icon-darkgray icon-gear"></li>'.TDLanguage::$form_button_text_admin.'</button>';
			}
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = '!empty('.$columnData['value'].') ? Fie_foreignkey::getFieldText("'.$params['tableColumnId'].'",'.$columnData['value'].') : ""';
			return $result;
		}
		
		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => !empty($columnData['value']) ? Fie_foreignkey::getFieldText($params['tableColumnId'],$columnData['value']) : "",
			);
			return $result;
		}
		public function viewHtml($params) {
			return !empty($params['value']) ? Fie_foreignkey::getFieldText($params['tableColumnId'],$params['value']) : "";
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}
		}

		public function search($params) {
			if(isset($params['rowModel']) && !empty($params['rowModel'])) {
				$array = FieldRule::getDBArray($params['tableColumnId'],true,'',$params['rowModel']); 
			} else {
				$tbName = TDTableColumn::getColumnTableDBName($params['tableColumnId']);
				$array = FieldRule::getDBArray($params['tableColumnId'],true,'',TDModelDAO::getModel($tbName,TDSessionData::getLastTablePkId($tbName)));
			}
			$result = CHtml::dropDownList($params['fieldName'],$params['value'],TDCommon::array_smerge(array(TDSearch::$field_value_is_null=>TDLanguage::$advanced_search_select_null),$array),
			array('empty'=>  TDLanguage::$please_choose,'style'=>TDCommonCss::$search_select_style));
			return $result;	
		}

		public function editTableColumn($params) {
			if(isset($params['rowModel']) && !empty($params['rowModel'])) {
				$array = FieldRule::getDBArray($params['tableColumnId'],true,'',$params['rowModel']); 
			} else {
				$array = FieldRule::getDBArray($params['tableColumnId']); 
			}
			$result = CHtml::dropDownList("tmpSelectdb".TDCommon::getIncreaseNum(),TDFormat::getArrayKeyFormValue($array,$params['value']),$array,
			array('empty'=>  TDLanguage::$please_choose,
			'style'=> 'width:auto !important;margin-bottom: 0px;',
			'urlstr' => $params['urlstr'],
			'timeajax' => '1',)); 
			return $result;	
		}
}
