<?php
class Fie_radio extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$dataArray = FieldRule::getStaticArray($params['tableColumnId'],$params['model'],true);
			$changeEven = FieldRule::getChangeEvent($params['tableColumnId']);
			if(!empty($changeEven)) {
				$changeEven = 'onchange="'.$changeEven.'"';
			}
			$result = '';
			foreach ($dataArray as $key => $value) {
				$result .= '<label style="margin-top:5px;" class="pull-left"><input '.$changeEven.' type="radio" '.($key == $columnFormData['value'] ? "checked='checked'" : '').' value="'.$key.'" id="'.
				$columnFormData['id'].$key.'" name="'.$columnFormData['name'].'">'.$value.'</label>';
				/*
				$result .= '
				<label class="radio input_readio">
				<div class="radio">
				<span class=""><input '.$changeEven.' type="radio" '.($key == $columnFormData['value'] ? "checked='checked'" : '').' value="'.$key.
				'" id="'.$columnFormData['id'].$key.'" name="'.$columnFormData['name'].'" style="opacity: 0;"></span>
				</div>'.$value.'
				</label>';
				 */
			}
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
			FieldRule::getStaticArray($params['tableColumnId']))
			,array('empty'=>  TDLanguage::$please_choose,'style'=>  TDCommonCss::$search_select_style));
			return $result;
		}
		
		public function editTableColumn($params) {
			$array = FieldRule::getStaticArray($params['tableColumnId']); 
			$result = CHtml::dropDownList('tmpRadio'.TDCommon::getIncreaseNum(),TDFormat::getArrayKeyFormValue($array,$params['value']),$array,
			array('empty'=>  TDLanguage::$please_choose,
			'style'=> 'width:auto !important;margin-bottom: 0px;',
			'urlstr' => $params['urlstr'],
			'timeajax' => '1',)); 
			return $result;
		}
}
