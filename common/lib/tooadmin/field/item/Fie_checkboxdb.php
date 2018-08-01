<?php

class Fie_checkboxdb extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$result = '<input type="hidden" name="'.$columnFormData['name'].'" value="" />';
			$dataArray = FieldRule::getDBArray($params['tableColumnId'],true,'',$params['model']);
			$setArray = !empty($columnFormData['value']) ? explode(',',$columnFormData['value']) : array();
			$result .= '<table><tr>';
			$index = 0;
			foreach($dataArray as $key => $value) {
				if($index > 0 && $index % 3 == 0) {
					$result .= '</tr><tr>';
				}	
				$result .= '<td>';
				$result .= '<label><input type="checkbox" '.(in_array($key,$setArray) ? "checked='checked'" : "").' value="'.$key.'" id="'.
				$columnFormData['id'].$key.'" name="'.$columnFormData['name'].'[]">'.$value.'</label> ';	
				$result .= '</td>';
				$index++;
			}
			$result .= '</tr></table>';
			if(count($dataArray) > 3) {
				$result .= '<label><input type="checkbox" onclick="checkboxChooseUnChooseAll(\''.$columnFormData['name'].'[]\',this.checked)"><span style="color:blue;">全/反选<span></label>';
			}	
			return $result; 
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = '!empty('.$columnData['value'].') ? TDFormat::tableStrFormat(Fie_foreignkey::getFieldText("'.$params['tableColumnId'].'",'.$columnData['value'].')) : ""';
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
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],is_array($value) ? implode(',',$value) : $value);
			}
		}

		public function search($params) {
			$dataArray = FieldRule::getDBArray($params['tableColumnId'],false);
			$checkBoxName = TDSearch::$checkbox_name_key.time(); 
			$result = '<input type="hidden" name="'.$params['fieldName'].'" value="'.$checkBoxName.'" />
			<input type="hidden" value=""  name="'.$checkBoxName.'[]" >';
			$checkedValues = explode(",",$params['value']);
			foreach($dataArray as $key => $value) {
				$result .= '<label><input type="checkbox"
				value="'.$key.'" name="'.$checkBoxName.'[]" '.(in_array($key,$checkedValues) ? ' checked="checked" ' : '')
				.'>'.$value.'</label>';	
			}
			return $result;
		}

		public function editTableColumn($params) {
		}
}
