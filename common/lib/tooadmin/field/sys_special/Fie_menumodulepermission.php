<?php
class Fie_menumodulepermission extends TDField {

		public function getOrderMenus($pid,$menus,$setPermission,$inputName) {
			$html = '<table  border="1px;">';//width="550px;"
			foreach($menus as $menuRow){
				if($menuRow["pid"] == $pid) {
					$checkedStr = in_array($menuRow["id"],$setPermission) ? 'checked="checked"' : '';
					$html .= '<tr>';
						$html .= '<td width="116px;"><input type="checkbox" '.$checkedStr.' value="'.$menuRow["id"].'" name="'.$inputName.'[]">'.$menuRow["name"].'</td>';
						$html .= '<td>';
						$html .= $this->getOrderMenus($menuRow["id"],$menus,$setPermission,$inputName);

						$childITems = TDModelDAO::queryAll(TDTable::$too_menu_items,"menu_id=".$menuRow["id"]." order by `order`","id,name"); 
						if(count($childITems) > 0) {
							$html .= '<table border="0px"><tr>';
							foreach($childITems as $childItem) {
								$html .= '<td>';
								$checkedStr = in_array("i".$childItem["id"],$setPermission) ? 'checked="checked"' : '';
								$html .= '<input type="checkbox" '.$checkedStr.' value="'.'i'.$childItem["id"].'" name="'.
								$inputName.'[]">'.$childItem["name"].'&nbsp;&nbsp;';
								$html .= '</td>';
							}
							$html .= '</tr></table>';
						}
						
						$html .= '</td>';
					$html .= '</tr>';
				}
			}	
			$html .= '</table>';
			return $html;
		}

		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$setPermission = explode(',',$columnFormData['value']);
			$menuRows = TDModelDAO::queryAll(TDTable::$too_menu,'is_show=1 order by `pid`,`order`,`id`', '`id`,`pid`,`name`');
			$html = '<input type="hidden" name="'.$columnFormData['name'].'" value="" /><input type="checkbox" onchange="checkboxChooseUnChooseAll('
			."'".$columnFormData['name']."[]',this.checked".')" />'.TDLanguage::$checkBox_ChooseAll.'<br/>';
			$html .= $this->getOrderMenus(0,$menuRows,$setPermission,$columnFormData['name']);
			return $html;
		}

		public function gridView($params) {
			return null;
		}

		public function viewData($params) {
			return null;
		}
		public function viewHtml($params) {
			return null;
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				$result = '';
				if(is_array($value)) {
					$result = implode(",",$value);
				}
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$result);
			}	
		}

		public function search($params) {
				
		}

		public function editTableColumn($params) {
			return NULL;
		}
}
