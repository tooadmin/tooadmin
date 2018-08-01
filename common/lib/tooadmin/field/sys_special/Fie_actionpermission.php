<?php
class Fie_actionpermission extends TDField {

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$setPermission = explode(',',$columnFormData['value']);
			$perm = new TDPermission();
			$controllersActions = $perm->getAllControlsActions();
			$html = '<input type="hidden" name="'.$columnFormData['name'].'" value="" /><input type="checkbox" onchange="checkboxChooseUnChooseAll('
			."'".$columnFormData['name']."[]',this.checked".')" />'.TDLanguage::$checkBox_ChooseAll.'<br/>';
			$html .= '<table style="width:800px;" border="2px;">';
			foreach($controllersActions as $item) {
				$html .= '<tr><td>'.$item['remark'].'</td><td>';
				$index = 0;
				foreach($item['actions'] as $action => $acRemak) {
					$value = $item['controller'].'-'.$action;
					$html .= '<input type="checkbox" '.( in_array($value,$setPermission) ? 'checked="checked"' : "" )
					.' name="'.$columnFormData['name'].'[]" value="'.$value.'">'
					.$acRemak.'&nbsp;&nbsp;&nbsp;';
					if($index == 4) {
						$html .= '<br/>';
						$index = 0;
					}
					$index++;
				}
				$html .= '</td></tr>';
			}
			$html .= '</table>';
			return $html;
		}

		public function gridView($params) {
			return null;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$setPermission = explode(',',$columnData['value']);
			$perm = new TDPermission();
			$controllersActions = $perm->getAllControlsActions();
			$html = '';
			foreach($controllersActions as $item) {
				$html .= $item['remark']."【";
				foreach($item['actions'] as $action => $acRemak) {
					$value = $item['controller'].'-'.$action;
					if(in_array($value,$setPermission)) {
						$html .= $acRemak.'&nbsp;&nbsp;';
					}
				}
				$html .= '】<br/>';
			}
			$html .= '';
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $html,
			);
			return $result;	
		}
		public function viewHtml($params) {
			return $params['value'];
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],is_array($value) ? implode(',',$value) : $value);
			}	
		}

		public function search($params) {
				
		}

		public function editTableColumn($params) {
			return NULL;
		}
}
