<?php

class Fie_checkbox extends TDField {
		
		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$result = '<input type="hidden" name="'.$columnFormData['name'].'" value="" />';
			$dataArray = FieldRule::getStaticArray($params['tableColumnId'],$params['model'],true); 
			$setArray = !empty($columnFormData['value']) ? explode(',',$columnFormData['value']) : array();
			foreach($dataArray as $key => $value) {
				$result .= '<label style="margin-top:5px;"><input type="checkbox" '.(in_array($key,$setArray) ? "checked='checked'" : "").' 
				value="'.$key.'" id="'.$columnFormData['id'].$key.'" name="'.$columnFormData['name'].'[]">'.$value.'</label>';	
			}
			return $result;	
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result ='FieldRule::getValuesFromStaticArray("'.$params['tableColumnId'].'",'.$columnData['value'].',$data)';
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
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],is_array($value) ? implode(',',$value) : $value);
			}
		}

		public function search($params) {
			$dataArray = FieldRule::getStaticArray($params['tableColumnId']); 
			$checkBoxName = TDSearch::$checkbox_name_key.time(); 
			$result = '<input type="hidden" name="'.$params['fieldName'].'" value="'.$checkBoxName.'" />
			<input type="hidden" value=""  name="'.$checkBoxName.'[]" >';
			$checkedValues = explode(",",$params['value']);
			foreach($dataArray as $key => $value) {
				$result .= '
				<label><input type="checkbox"
				value="'.$key.'" name="'.$checkBoxName.'[]" '.(in_array($key,$checkedValues) ? ' checked="checked" ' : '')
				.'>'.$value.'</label>';	
			}
			return $result;
		}
		
		public function editTableColumn($params) {
		}
}
