<?php

class Fie_updateuser extends TDField {

		public function editForm($params) {
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = '!empty('.$columnData['value'].') ? TDDataDAO::getUserInfoStr('.$columnData['value'].') : ""';
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => !empty($columnData['value']) ? TDDataDAO::getUserInfoStr($columnData['value']) : "",
			);
			return $result;
		}
		public function viewHtml($params) {
			return !empty($params['value']) ? TDDataDAO::getUserInfoStr($params['value']) : "";
		}

		public function saveData($params) {
			TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],TDSessionData::getUserId());
		}

		public function search($params) {
			$result = CHtml::dropDownList($params['fieldName'],$params['value'],
			TDCommon::array_smerge(array(TDSearch::$field_value_is_null=>TDLanguage::$advanced_search_select_null),
			TDDataDAO::getUserMap()),array('empty'=>  TDLanguage::$please_choose,
			'style'=>  TDCommonCss::$search_select_style));
			return $result;			
		}

		public function editTableColumn($params) {
		}
}