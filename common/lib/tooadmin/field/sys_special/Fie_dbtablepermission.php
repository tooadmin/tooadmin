<?php
/*
一个数据表里必须要有dbp_query,dbp_add,dbp_update,dbp_delete,
分别用于存储查询（字段）、添加（字段）、修改（字段）、删除（数据表）的权限，
而输入类型则只需其中一个字段设置为Fie_dbtablepermission即可
*/
class Fie_dbtablepermission extends TDField {

		public static function getPermissionDetail($dbp_query_str,$dbp_add_str,$dbp_update_str,$dbp_delete_str) {
			$dbp_query = explode(",",$dbp_query_str);	
			$dbp_add = explode(",",$dbp_add_str);	
			$dbp_update = explode(",",$dbp_update_str);	
			$dbp_delete = explode(",",$dbp_delete_str);	
			$perArray = array();
			$tableRows = TDModelDAO::queryAll(TDTable::$too_table_collection, '`type` > 1 order by `type`', '`id`,`table`,`name`');
			foreach($tableRows as $tableRow) {
				$tableId = $tableRow["id"]; 
				$queryPermission = array();
				$addPermission = array();
				$updatePermission = array();
				$tmpColumns = TDModelDAO::queryAll(TDTable::$too_table_column,'`column_type`=0 and `table_collection_id`='.$tableId.'','`id`,`name`,`label`'); 
				foreach($tmpColumns as $colrow) {
					if(in_array($colrow["id"],$dbp_query)) {
						$queryPermission[] = $colrow["name"]."【".$colrow["label"]."】"; 
					}
					if(in_array($colrow["id"],$dbp_add)) {
						$addPermission[] = $colrow["name"]."【".$colrow["label"]."】"; 
					}
					if(in_array($colrow["id"],$dbp_update)) {
						$updatePermission[] = $colrow["name"]."【".$colrow["label"]."】"; 
					}
				}
				$perArray[] = array(
				    'table' => $tableRow["table"]."【".$tableRow["name"]."】",
				    'query_permission' => $queryPermission, 
				    'add_permission' => $addPermission, 
				    'update_permission' => $updatePermission, 
				    'delete_permission' => in_array($tableId,$dbp_delete) ? 1 : 0, 
				);
			}
			return $perArray;
		}

		public function editForm($params) {
			if($params['model']->use_db_permission == 0) { return ''; }
			$columnFormData = $params['columnFormData'];
			$dbp_query = explode(",",$params['model']->dbp_query);	
			$dbp_add = explode(",",$params['model']->dbp_add);	
			$dbp_update = explode(",",$params['model']->dbp_update);	
			$dbp_delete = explode(",",$params['model']->dbp_delete);	
			$html = '<input type="hidden" name="'.$columnFormData['name'].'" value="" /><input type="checkbox" onchange="dbpChooseCkb(this.checked)" />'.TDLanguage::$checkBox_ChooseAll;
			$html .= '<script type="text/javascript">'
				. 'function dbpChooseCkb(isChecked){ '
					. 'checkboxChooseUnChooseAll('."'dbp_query[]'".',isChecked);'
					. 'checkboxChooseUnChooseAll('."'dbp_add[]'".',isChecked);'
					. 'checkboxChooseUnChooseAll('."'dbp_update[]'".',isChecked);'
					. 'checkboxChooseUnChooseAll('."'dbp_delete[]'".',isChecked);'
				. '}'
				. '</script>';
			$html .= '<table border="1px;" width="600px;">';
			$tableRows = TDModelDAO::queryAll(TDTable::$too_table_collection,'`type` > 1 order by `type`','`id`,`name`');
			foreach($tableRows as $tableRow) {
				$tableId = $tableRow["id"]; 
				$html .= '<tr>';
					$html .= '<td width="156px;">'.$tableRow["name"].'</td>';
					$html .= '<td>'; 
						$html .= '<table class="table table-bordered table-striped table-condensed">';
							$dbpQueryHtml = "";
							$dbpAddHtml = "";
							$dbpUpdateHtml = "";
							$tmpColumns = TDModelDAO::queryAll(TDTable::$too_table_column, '`column_type`=0 and `table_collection_id`='.$tableId.'','id,label'); 
							foreach($tmpColumns as $colrow) {
								$dbpQueryHtml .= '<input type="checkbox" '.(in_array($colrow["id"],$dbp_query) ? 'checked="checked"' : '')
								.' value="'.$colrow["id"].'" name="dbp_query[]" />'.$colrow["label"].'&nbsp;&nbsp;';
								$dbpAddHtml .= '<input type="checkbox" '.(in_array($colrow["id"],$dbp_add) ? 'checked="checked"' : '')
								.' value="'.$colrow["id"].'" name="dbp_add[]" />'.$colrow["label"].'&nbsp;&nbsp;';
								$dbpUpdateHtml .= '<input type="checkbox" '.(in_array($colrow["id"],$dbp_update) ? 'checked="checked"' : '')
								.' value="'.$colrow["id"].'" name="dbp_update[]" />'.$colrow["label"].'&nbsp;&nbsp;';
							}
							$dbpDeleteHtml = '<input type="checkbox" '.(in_array($tableId,$dbp_delete) ? 'checked="checked"' : '')
								.' value="'.$tableId.'" name="dbp_delete[]" />'.TDLanguage::$sys_permission_delete_data.'&nbsp;&nbsp;';	
							$html .= '<tr><td>Query</td><td>'.$dbpQueryHtml.'</td></tr>';	
							$html .= '<tr><td>Add</td><td>'.$dbpAddHtml.'</td></tr>';	
							$html .= '<tr><td>Update</td><td>'.$dbpUpdateHtml.'</td></tr>';	
							$html .= '<tr><td>Delete</td><td>'.$dbpDeleteHtml.'</td></tr>';	
						$html .= '</table>';	
					$html .= '</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			return $html;
		}

		public function gridView($params) { return null; }
		public function viewHtml($params) { return $params['value']; }

		public function viewData($params) { return null; }

		public function saveData($params) {
			TDFormat::setModelAppendColumnValue($params['model'],"dbp_query",implode(",",isset($_POST["dbp_query"]) ? $_POST["dbp_query"] : array()));	
			TDFormat::setModelAppendColumnValue($params['model'],"dbp_add",implode(",",isset($_POST["dbp_add"]) ? $_POST["dbp_add"] : array()));	
			TDFormat::setModelAppendColumnValue($params['model'],"dbp_update",implode(",",isset($_POST["dbp_update"]) ? $_POST["dbp_update"] : array()));	
			TDFormat::setModelAppendColumnValue($params['model'],"dbp_delete",implode(",",isset($_POST["dbp_delete"]) ? $_POST["dbp_delete"] : array()));	
		}

		public function search($params) { }

		public function editTableColumn($params) { return NULL; }
}
