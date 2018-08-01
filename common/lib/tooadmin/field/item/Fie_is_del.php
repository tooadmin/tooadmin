<?php

class Fie_is_del extends TDField {
		function getInputRule($param) { return null; }

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$dataArray = array(0=>  TDLanguage::$false,1=>  TDLanguage::$true);
			$result = '';
			foreach ($dataArray as $key => $value) {
				$result .= '
				<label class="radio input_readio">
				<div class="radio">
				<span class=""><input type="radio" '.($key == $columnFormData['value'] ? "checked='checked'" : '').' value="'.$key.'" 
				id="'.$columnFormData['id'].$key.'" name="'.$columnFormData['name'].'" style="opacity: 0;"></span>
				</div>
				'.$value.'
				</label>';
			}
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result =  $columnData['value'].'== 1 ? TDLanguage::$true : TDLanguage::$false';
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $columnData['value'] == 1 ? TDLanguage::$true : TDLanguage::$false,
			);
			return $result;
		}
		//$params = array("tableColumnId"=>"","value"=>"","model"=>null);
		public function viewHtml($params) {
			return $params['value'] == 1 ? TDLanguage::$true : TDLanguage::$false;	
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}
		}

		public function search($params) {
			$result = CHtml::dropDownList($params['fieldName'],$params['value'],
			array(TDSearch::$field_value_is_null=>TDLanguage::$advanced_search_select_null,
			0=>TDLanguage::$false,1=>TDLanguage::$true),array('empty'=>TDLanguage::$please_choose,
			'style'=> TDCommonCss::$search_select_style));
			return $result;	
		}

		public function editTableColumn($params) {

		}

	

}
