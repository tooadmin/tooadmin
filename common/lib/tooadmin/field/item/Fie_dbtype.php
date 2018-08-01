<?php
class Fie_dbtype extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$result = CHtml::dropDownList($columnFormData['name'],$columnFormData['value'],TDDataDAO::getDBTypeArray()
			,array('empty'=> TDLanguage::$please_choose,'id'=>$columnFormData['id']));
			return $result;
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
			$result = CHtml::dropDownList($params['fieldName'],$params['value'],
			TDDataDAO::getDBTypeArray(),array('empty'=>  TDLanguage::$please_choose,
			'style'=>  TDCommonCss::$search_select_style));
			return $result;
		}

		public function editTableColumn($params) {
			$array = TDDataDAO::getDBTypeArray(); 
			$result = CHtml::dropDownList("tmpDbType".TDCommon::getIncreaseNum(),TDFormat::getArrayKeyFormValue($array,$params['value']),$array,
			array('empty'=>  TDLanguage::$please_choose,
			'style'=> 'width:auto !important;margin-bottom: 0px;',
			'urlstr' => $params['urlstr'],
			'timeajax' => '1',));
			return $result;
		}
}
