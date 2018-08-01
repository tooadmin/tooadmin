<?php
class Fie_select extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$htmlOptions = array('id'=>$columnFormData['id'],'empty'=>  TDLanguage::$please_choose);
			$changeEven = FieldRule::getChangeEvent($params['tableColumnId']);
			if(!empty($changeEven)) {
				$htmlOptions['onchange'] = $changeEven;
			}
			$result = CHtml::dropDownList($columnFormData['name'],$columnFormData['value']
			,FieldRule::getStaticArray($params['tableColumnId'],$params['model'],true),$htmlOptions);
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = 'FieldRule::getValuesFromStaticArray("'.$params['tableColumnId'].'",'.$columnData['value'].',$data)';
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => FieldRule::getValuesFromStaticArray($params['tableColumnId'],$columnData['value'],$params['model']),
			);
			return $result;	
		}
		public function viewHtml($params) {
			return FieldRule::getValuesFromStaticArray($params['tableColumnId'],$params['value'],$params['model']);
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}
		}

		public function search($params) {
			$result = CHtml::dropDownList($params['fieldName'],$params['value'],
			TDCommon::array_smerge(array(TDSearch::$field_value_is_null=>TDLanguage::$advanced_search_select_null),
			FieldRule::getStaticArray($params['tableColumnId'])),array('empty'=>  TDLanguage::$please_choose,
			'style'=>  TDCommonCss::$search_select_style));
			return $result;
		}

		public function editTableColumn($params) {
			$array = FieldRule::getStaticArray($params['tableColumnId']); 
			$result = CHtml::dropDownList('tmpSelect'.TDCommon::getIncreaseNum(),TDFormat::getArrayKeyFormValue($array,$params['value']),$array,
			array('empty'=>  TDLanguage::$please_choose,
			'style'=> 'width:auto !important;margin-bottom: 0px;',
			'urlstr' => $params['urlstr'],
			'timeajax' => '1',));
			return $result;
		}
}
