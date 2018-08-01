<?php
class Fie_editfile extends TDField {

		//之前作为生成文件用,现已改为使用直接执行php代码
		public static function getInputTypeId() {
			return 34; 
		}
	
		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$fieldParam = array('id'=>$columnFormData['id']); 
			$style = "";
			if(!empty($columnFormData["columnData"]["width"])) {
				$style .= "width:".$columnFormData["columnData"]["width"]."px;"; 
			}
			if(!empty($columnFormData["columnData"]["height"])) {
				$style .= "height:".$columnFormData["columnData"]["height"]."px;"; 
			}
			if(!empty($style)) {
				$fieldParam["style"] = $style;
			}
			$jsHtml = '<script> jQuery(function($) { var editor_'.$columnFormData['id']
			.' = CodeMirror.fromTextArea(document.getElementById("'.$columnFormData['id'].'"), { lineNumbers: true, viewportMargin: Infinity }); });</script>';
			return CHtml::textArea($columnFormData['name'],$columnFormData['value'],$fieldParam).$jsHtml;	
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = $columnData['value'];
			return $result;	
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$dataValueStr = TDTableColumn::getColumnAppendStr(TDTableColumn::getColumnIdByTableAndColumnName( TDTable::$too_table_column,'table_collection_id'),$params['belongOrderColumnIds']); 
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
				."\" style=\"width:160px;margin-bottom: 0px;\" timeajax=\"1\" />";	
			return $result;
		}
}
