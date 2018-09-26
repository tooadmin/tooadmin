<?php
class TDSessionData {

	public static function setUserId($id) { Yii::app()->session->add('userid',$id); }	
	public static function getUserId() { return isset(Yii::app()->session['userid']) ? Yii::app()->session['userid'] : "0"; }	
	public static function setUserName($name) { Yii::app()->session->add('username',$name); }
	public static function setIsManager() { Yii::app()->session->add('ismanager',"yes");  }
	public static function currentUserIsManager() { return isset(Yii::app()->session['ismanager']); }
	public static function currentUserIsAdmin() { 
		if(!isset(Yii::app()->session['isAdminMark'])) {
		 	Yii::app()->session['isAdminMark'] = TDModelDAO::queryScalarByPk(TDTable::$too_user,TDSessionData::getUserId(),"is_manager") == 1 ? true : false;
		}
		return Yii::app()->session['isAdminMark'];
	}
	public static function currentUserIsTooAdmin() { 
		return self::getUserName() === 'tooadmin';
	}
	public static function userMarkStr($newMarkStr="") { 
		if(!empty($newMarkStr)) {
			Yii::app()->session['userMarkStr'] = $newMarkStr.time();	
		} else if(!isset(Yii::app()->session['userMarkStr']) || empty(Yii::app()->session['userMarkStr'])) {
		 	Yii::app()->session['userMarkStr'] = self::getUserId().time();
		}
		return Yii::app()->session['userMarkStr'];
	}

	public static function getUserName() { return isset(Yii::app()->session['username']) ? Yii::app()->session['username'] : null; }
	public static function seNickName($nickname) { Yii::app()->session->add('nickname',$nickname); }	
	public static function getNickName() { return isset(Yii::app()->session['nickname']) ? Yii::app()->session['nickname'] : null; }
	public static function setRoles($data) { Yii::app()->session['roles'] = $data; }	
	public static function getRoles() { return isset(Yii::app()->session['roles']) ? Yii::app()->session['roles'] : ""; }
	public static function setLastTablePkId($table,$pkId) { Yii::app()->session['forcolumn_tb_'.$table] = $pkId;  }
	public static function getLastTablePkId($table) { return isset(Yii::app()->session['forcolumn_tb_'.$table]) ? Yii::app()->session['forcolumn_tb_'.$table] : 0;  }


	public static function setClientWidth($data) { Yii::app()->session['clientWidth'] = $data; }	
	public static function getClientWidth() { return isset(Yii::app()->session['clientWidth']) ? Yii::app()->session['clientWidth'] : 1024; }

	public static function spec_layout_common_page_left() { return 'width:160px;'; }
	public static function spec_layout_common_page_right() { return 'min-width:'.(self::getClientWidth()-190).'px;'; }
	public static function spec_layout_common_page_right_gridview_opbtn_left() { return self::getClientWidth()-180+584; }
	


	public static function afterLoginInit() { $upgrade = new TDUpgrade();$upgrade->doUpgradeFiles(); self::initPermission(); }

	public static function initPermission() {
		$curExpMenuIds = array();
		if(TDSessionData::currentUserIsTooAdmin()) {
			Yii::app()->session['menu_permission_str'] = !empty($curExpMenuIds) ? " and id not in (".  implode(",",$curExpMenuIds).") " : "";	
			Yii::app()->session['menu_items_permission_str'] = "";
			return;
		} else if(TDSessionData::currentUserIsAdmin()) {
			Yii::app()->session['menu_permission_str'] = !empty($curExpMenuIds) ? " and id not in (".  implode(",",$curExpMenuIds).")"  : " ";	
			Yii::app()->session['menu_items_permission_str'] = "";
			return;
		}
		
		$roles = self::getRoles();
		if(empty($roles)) {
			$roles = '-1';
		}
		$action_permission = array();
		$dbp_query_permission = array();
		$dbp_add_permission = array();
		$dbp_update_permission = array();
		$dbp_delete_permission = array();
		$useDBPromission = true;
		$menuIds = array();
		$menuItemsIds = array();
		$rows = TDModelDAO::queryAll(TDTable::$too_role,'`id` in ('.$roles.')');
		foreach($rows as $row) {
			$action_permission = array_merge($action_permission,explode(',',$row["action_permission"]));
			if($row["use_db_permission"] == 1) {
				$dbp_query_permission = array_merge($dbp_query_permission,explode(",",$row["dbp_query"]));
				$dbp_add_permission = array_merge($dbp_add_permission,explode(",",$row["dbp_add"])); 
				$dbp_update_permission = array_merge($dbp_update_permission,explode(",",$row["dbp_update"]));  
				$dbp_delete_permission = array_merge($dbp_delete_permission,explode(",",$row["dbp_delete"]));  
			} else if($useDBPromission) {
				$useDBPromission = false;
			}
			$menuIdsTmp = array_merge($menuIds,explode(',',$row["menu_module_permission"]));
			foreach($menuIdsTmp as $id) {
				if(strpos($id,'i') === false) {
					$menuIds[] = $id;	
				} else {
					$menuItemsIds[] = substr($id,1);
				}
			}
		}
		Yii::app()->session['use_db_permission'] = $useDBPromission; 
		Yii::app()->session['action_permission'] = array_unique($action_permission);
		Yii::app()->session['dbp_query_permission'] = array_unique($dbp_query_permission);
		Yii::app()->session['dbp_add_permission'] = array_unique($dbp_add_permission);
		Yii::app()->session['dbp_update_permission'] = array_unique($dbp_update_permission);
		Yii::app()->session['dbp_delete_permission'] = array_unique($dbp_delete_permission);
		Yii::app()->session['menu_items_permission_str'] = empty($menuItemsIds) ? " false " : " AND id IN (".implode(",",$menuItemsIds).") ";// $menuItemsIds;
		$menuIds = array_unique($menuIds);

		//过滤处理特殊权限
		$conditionSpe = "pid=0 and is_show=1";
		if(!empty($curExpMenuIds)) {
			$conditionSpe = "(pid=0 and is_show=1) or id in (".implode(",",$curExpMenuIds).")";
		}
		$speMenus = array();
		$menus = TDModelDAO::queryAll(TDTable::$too_menu,$conditionSpe,"id");
		$ids = "";
		foreach($menus as $menu) {
			$ids .= empty($ids) ? $menu["id"] : ",".$menu["id"]; 
			$speMenus[] = $menu;
		}
		if(!empty($ids)) {
			$menus = TDModelDAO::queryAll(TDTable::$too_menu,"pid in (".$ids.") and is_show=1","id");
			$ids = "";
			foreach($menus as $menu) {
				$ids .= empty($ids) ? $menu["id"] : ",".$menu["id"]; 
				$speMenus[] = $menu;
			}
			if(!empty($ids)) {
				$menus = TDModelDAO::queryAll(TDTable::$too_menu,"pid in (".$ids.") and is_show=1","id");
				foreach($menus as $menu) {
					$speMenus[] = $menu;
				}
			}	
		}
		/*
		foreach($speMenus as $menu) {
			for($i=0; $i<count($menuIds); $i++) {
				if(isset($menuIds[$i]) && $menuIds[$i] == $menu["id"]) {
					unset($menuIds[$i]);
					break;
				}
			}
		}
		if(empty($menuIds) || !is_array($menuIds)) { 
			$menuIds = '-1'; 
		} else { 
			$menuIds[0] = isset($menuIds[0]) ? trim($menuIds[0]) : "";
			if(empty($menuIds[0])) {
				$menuIds = '-1'; 
			} else {
				$menuIds = implode(",",$menuIds);
			}
		}	
		if($menuIds[strlen($menuIds)-1] == ',') {
			$menuIds = substr($menuIds,0,strlen($menuIds)-1);
		}
		Yii::app()->session['menu_permission_str'] = ' and `id` in ('.$menuIds.') ';
		*/

		Yii::app()->session['menu_permission_str'] = ' and `id` in ('.  implode(",",$menuIds).') ';

		//echo 'Yii::app()->session[menu_items_permission_str]='.Yii::app()->session['menu_items_permission_str'].'<br/>';
		//echo 'Yii::app()->session[menu_permission_str]='.Yii::app()->session['menu_permission_str'].'<br/>';
		//echo  date("Y-m-d H:i:s"); exit;
	}

	public static function getIsUseDBPermission() { return Yii::app()->session['use_db_permission']; }
	public static function getActionPermission() { return Yii::app()->session['action_permission']; }
	public static function getDBQueryPermission() { return Yii::app()->session['dbp_query_permission']; }
	public static function getDBAddPermission() { return Yii::app()->session['dbp_add_permission']; }
	public static function getDBUpdatePermission() { return Yii::app()->session['dbp_update_permission']; }
	public static function getDBDeletePermission() { return Yii::app()->session['dbp_delete_permission']; }

	public static function checkIsTableName($str) { return TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$str."'","count(*)") > 0; }
	
	public static function getColumnNum($reAscending = false) {
		if(!isset(Yii::app()->session['column_num_ascending'])) {
			Yii::app()->session['column_num_ascending'] = 0;
		}	
		if($reAscending) {
			Yii::app()->session['column_num_ascending'] = 0;
		} else {
			Yii::app()->session['column_num_ascending'] += 1; 
		}	
		return Yii::app()->session['column_num_ascending'];
	}

	//当前是否为开发模式
	public static function currentIsDevelopment() {
		return 1;
	}

	public static function getHomeUrl() {
		if(!isset(Yii::app()->session['homeUrl'])) {
			$url = '';
			$row = TDModelDAO::queryRowByCondtion(TDTable::$too_menu,"`is_home`=1");
			if(!empty($row)) {
				$minInd = TDModelDAO::queryScalar(TDTable::$too_menu, "pid=" . $row["id"] . " and `is_show`=1 order by `order`,`id` limit 1", "id");
                if (!empty($minInd)) {
                    $minInd = TDModelDAO::queryScalar(TDTable::$too_menu, "pid=" . $minInd . " and `is_show`=1 order by `order`,`id` limit 1", "id");
              	}
				Yii::app()->session['homeUrl'] = TDPathUrl::getMenuItemLink($minInd,$row["id"],0,TDPathUrl::$menuForType_topMenulink); 
			}
		}
		return Yii::app()->session['homeUrl'];
	}

	public static function setPopupTipMsg($html) {
		Yii::app()->session['lastPopupTipMsg'] = $html;
	}
	public static function getPopupTipMsg() {
	 	return isset(Yii::app()->session['lastPopupTipMsg']) ? Yii::app()->session['lastPopupTipMsg'] : "";
	}
	//当前是否为开发模式
	public static function getCurIsDevModel() {
	 	return isset(Yii::app()->session['optModel']) && Yii::app()->session['optModel'] == "dev" ? true : false;
	}
	//开发模式
	public static function setCurToDevModel() { Yii::app()->session['optModel'] = "dev"; }
	//关闭开发模式
	public static function closeDevModel() { unset(Yii::app()->session['optModel']); }

	public static function setCache($key,$value) { 
		if(!TDSessionData::getCurIsDevModel()) { Yii::app()->cache->set($key, $value); }
	}
	public static function getCache($key) {
		return TDSessionData::getCurIsDevModel() ? false : Yii::app()->cache->get($key);
	}
}
