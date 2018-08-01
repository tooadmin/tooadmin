<?php

class Fie_time extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$result = '<input type="text" style="width:100px;" class="Wdate" readonly="readonly" 
			value="'.$columnFormData['value'].'" onclick="WdatePicker({dateFmt:\'HH:mm\'})" id="'.$columnFormData['id'].'" name="'.$columnFormData['name'].'" />';
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = $columnData['value'];
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$value = $columnData['value'];
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $value,
			);
			return $result;
		}
		public function viewHtml($params) {
			return $params['value'];
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				$validate = true;
				if(!empty($value)) {
					if(!TDValidateCollection::isDatetime($value)) {
						$params['model']->addError($params['fieldId'],array(TDLanguage::$validate_msg_date_error));
						$validate = false;
					}
				}
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}	
		}

		public function search($params) {
			$fieldID = 'searchID'.time();
			$result = '<input type="text" style="width:100px;" class="Wdate" readonly="readonly" 
			value="'.$params['value'].'" onclick="WdatePicker({dateFmt:\'HH:mm\'})" id="'.$fieldID.'" name="'.$params['fieldName'].'" />';
			return $result;
		}

		public function editTableColumn($params) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:80px;margin-bottom: 0px;\" timeajax=\"1\" />";
			return $result;//使用普通的input
		}
}
