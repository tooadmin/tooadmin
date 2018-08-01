<?php
class Fie_input extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$par = array('id'=>$columnFormData['id']);
			if($columnFormData["input_expand_type"] == 1) { $par["class"] = "color"; }
			return CHtml::textField($columnFormData['name'],$columnFormData['value'],$par);
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = $columnData['value'];
			if($columnData["input_expand_type"] == 1) { 
				$result='"<span style=\"color:#'.$result.'\">'.$result.'</span>"'; 
			} else {
				if(strpos($result,TDStaticDefined::$foreignKey_tableName) === false && TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"intercept_toolong") == 1) {
					$result = 'mb_strlen('.$columnData['value'].',"UTF-8") > 20 && strip_tags('.$columnData['value'].') == '.$columnData['value']
					.' ? "<span title=\'fff'.$columnData['value'].'\'>".mb_substr('.$columnData['value'].',0,20,"UTF-8")."...</span>" : '.$columnData['value'];	
				}
			}
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$value = $columnData['value'];
			if($columnData["input_expand_type"] == 1) { 
				$value="<span style=\"color:#".$value."\">".$value."</span>"; 
			}	
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $value,
			);
			return $result;	
		}
		public function viewHtml($params) {
			$value = $params['value'];
			if(TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"input_expand_type") == 1) { 
				$value="<span style=\"color:#".$value."\">".$value."</span>"; 
			}
			return $value;
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
