<?php
class Fie_text extends TDField {

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
			return CHtml::textArea($columnFormData['name'],$columnFormData['value'],$fieldParam);	
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = $columnData['value'];
			if(strpos($result,TDStaticDefined::$foreignKey_tableName) === false && TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"intercept_toolong") == 1) {
				$result = 'mb_strlen('.$columnData['value'].',"UTF-8") > 20 && strip_tags('.$columnData['value'].') == '.$columnData['value']
				.' ? "<span title=\'fff'.$columnData['value'].'\'>".mb_substr('.$columnData['value'].',0,20,"UTF-8")."...</span>" : '.$columnData['value'];	
			}
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
				."\" style=\"width:160px;margin-bottom: 0px;\" timeajax=\"1\" />";	
			return $result;
		}
}
