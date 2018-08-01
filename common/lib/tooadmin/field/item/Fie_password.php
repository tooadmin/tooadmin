<?php

class Fie_password extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			return CHtml::passwordField($columnFormData['name'],'',array('id'=>$columnFormData['id']));
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			return $columnData['value'];
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
			if(!empty($value)) {
				$encrypt = FieldRule::getEncrypt($params['tableColumnId']);
				if(!empty($encrypt)) {
					$value = $encrypt($value);
				}
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}
		}

		public function search($params) {
				
		}
		
		public function editTableColumn($params) {

		}

}
