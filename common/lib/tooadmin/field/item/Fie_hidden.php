<?php
class Fie_hidden extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			return CHtml::textField($columnFormData['name'],$columnFormData['value'],array('id'=>$columnFormData['id'],'style'=>'display:none;'));
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
				
		}

		public function editTableColumn($params) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:50px;margin-bottom: 0px;\" timeajax=\"1\" />";
			if(strpos($params['dbType'],"varchar") !== false || strpos($params['dbType'],"text") !== false) {
				$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
				."\" style=\"width:160px;margin-bottom: 0px;\" timeajax=\"1\" />";
			}
			return $result;
		}
}
