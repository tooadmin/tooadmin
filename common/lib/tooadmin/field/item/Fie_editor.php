<?php
class Fie_editor extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$editor = new KindEditor();
			$whArray = FieldRule::getEditorParam($params['tableColumnId']); 
			$editor = $editor->create_editor($columnFormData['baseName'],isset($whArray['width']) && !empty($whArray['width']) ? 
			$whArray['width'] : "600",
			isset($whArray['height']) && !empty($whArray['height']) ? $whArray['height'] : "400");
			$result = '<input type="hidden" name="'.$columnFormData['name'].'" /><textarea name="'
			.$columnFormData['baseName'].'">'.$columnFormData['value'].'</textarea>'.$editor;	
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
			if(isset($_POST[$params['fieldName']]))
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$_POST[$params['fieldName']]);
		}

		public function search($params) {
				
		}

		public function editTableColumn($params) {
				
		}
}
