<?php

class TDPermission {

	public function getControllerActions($controlPath,$controlName) {
		require_once $controlPath;
		$controlObj = new $controlName(null);
		$array = $controlObj->actionsRemark();
		if(empty($array)) {
			return null;	
		}
		if(!isset($array['ControllerRemark'])) {
			return null;	
		}
		foreach($array as $action => $remark) {
			if($action != 'ControllerRemark') {
				if(!method_exists($controlName,$action)) {
					throw new Exception($controlPath." ".$action." is not exists");
				}
			}
		}
		return $array;
	}	
	
	public function getAllControlsActions() {
		//暂时保留
		$upg = new TDUpgrade();
		$controllerPaths = array('common/lib/tooadmin/admin/controllers');
		$expandAdminPath ='custome/p2p/admin'; //TDSysConfig::getExpandAdminPath();
		if(!empty($expandAdminPath) && is_dir($expandAdminPath."/controllers")) {
			$controllerPaths[] = $expandAdminPath."/controllers"; 	
		}
		$result = array();
		foreach($controllerPaths as $basePath) {
			$controlFiles = $upg->getDirFiles($basePath);
			foreach($controlFiles as $file) {
				$controlName = str_replace($basePath.'/','',$file);
				$controlName = str_replace('.php','',$controlName);
				$actions = $this->getControllerActions($file,$controlName);
				if(is_null($actions)) {
					continue;
				}
				$controllerRemark = $actions['ControllerRemark']; 
				unset($actions['ControllerRemark']);
				$newActions = array();
				foreach($actions as $ac => $acremark) {
					$newActions[strtolower(str_replace("action","",$ac))] = $acremark;
				}
				$controlName = strtolower(str_replace("Controller","",$controlName));
				$result[] = array(
					'controller' => $controlName,
					'remark' => $controllerRemark,
					'actions' => $newActions,
				);
			}
		}
		return $result;
	}

	public static function checkActionPermission($controllerName,$actionName) {
		return true;//暂时不对action做权限控制
		if(TDSessionData::currentUserIsAdmin()) { return true; }
		$array = TDSessionData::getActionPermission();
		if(!is_null($array) && in_array(strtolower($controllerName).'-'.strtolower($actionName),$array)) {
			return true;
		} else {
			return false;
		}
	}

	public static function checkQueryPermission($columnId) { if(!TDSessionData::getIsUseDBPermission() || TDSessionData::currentUserIsAdmin()) { return true; } 
	return in_array($columnId,TDSessionData::getDBQueryPermission()); } 
	public static function checkAddPermission($columnId) { if(!TDSessionData::getIsUseDBPermission() || TDSessionData::currentUserIsAdmin()) { return true; }  
	return in_array($columnId,TDSessionData::getDBAddPermission()); } 
	public static function checkUpdatePermission($columnId) { if(!TDSessionData::getIsUseDBPermission() || TDSessionData::currentUserIsAdmin()) { return true; } 
	return in_array($columnId,TDSessionData::getDBUpdatePermission()); }
	public static function checkDeletePermission($tableId) { if(!TDSessionData::getIsUseDBPermission() || TDSessionData::currentUserIsAdmin()) { return true; } 
	return in_array($tableId,TDSessionData::getDBDeletePermission()); }
}