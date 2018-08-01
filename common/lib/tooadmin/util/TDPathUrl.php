<?php

class TDPathUrl {

	public static $TYPE_PATH = 0;
	public static $TYPE_URL = 1;

	public static function parsePath($pathStr) {//去头的/和去尾/
		$pathStr = str_replace('\\', "", $pathStr);
		if (!empty($pathStr)) {
			if ($pathStr[0] == '/') {
				$pathStr = substr($pathStr, 1);
			}
			if (!empty($pathStr)) {
				if ($pathStr[strlen($pathStr) - 1] == "/") {
					$pathStr = substr($pathStr, 0, strlen($pathStr) - 1);
				}
			}
		}
		return $pathStr;
	}

	public static function getPathUrl($type = 1,$pathStr = 'assets') { //$pathStr = 'assets/uploadfiles/temp') {
		$pathStr = self::parsePath($pathStr);
		if ($type == self::$TYPE_PATH) {
			return __MAIN_PATH__ . "/" . $pathStr . "/";
		} else {
			if (empty($pathStr)) {
				return $pathStr . "/";
			} else {
				return Yii::app()->baseUrl . "/" . $pathStr . "/";
			}
		}
	}

	public static function getPathUrlByColumnId($columnId, $type = 1) {
		$res = "";
		$filePath = TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $columnId, 'file_path');
		if (!empty($filePath)) {
			$filePath = Fie_formula::getValue(null,$filePath);
			$res = TDPathUrl::getPathUrl($type, $filePath);
		} else {
			$res = TDPathUrl::getPathUrl($type);
		}
		return $res;
	}

	public static function getUpgradeFilePathUrl($type = 1) {
		if ($type == self::$TYPE_PATH) {
			return __MAIN_PATH__ . "/upgradeFiles/";
		} else {
			return Yii::app()->baseUrl . "/upgradeFiles/";
		}
	}

	public static function getTableObjFilePathUrl($type = 1) {
		if ($type == self::$TYPE_PATH) {
			return __MAIN_PATH__ . "/common/lib/tooadmin/table_objs/";
		} else {
			return Yii::app()->baseUrl . "/common/lib/tooadmin/table_objs/";
		}
	}

	public static function getEditRender($tableName) {
		$baseName = $tableName . '_form';
		if (is_file(__MAIN_PATH__ . "/admin/views/sys/" . $baseName . '.php')) {
			return '//sys/' . $baseName;
		} else {
			return 'form';
		}
	}

	public static function getViewRender($tableName) {
		$baseName = $tableName . '_view';
		if (is_file(__MAIN_PATH__ . "/admin/views/sys/" . $baseName . '.php')) {
			return '//sys/' . $baseName;
		} else {
			return 'view';
		}
	}

	public static function createUrl($route, $params = array()) {
		$char = substr($route, 0, 1);
		if ($char == '/') {
			$route = substr($route, 1);
		}
		$url = Yii::app()->urlManager->createUrl($route, $params);
		return $url;
	}

	public static $createGridviewOpUrl_TYPE_ADD = 0;
	public static $createGridviewOpUrl_TYPE_UPDATE = 1;
	public static $createGridviewOpUrl_TYPE_DELETE = 2;
	public static $createGridviewOpUrl_TYPE_VIEW = 3;

	public static function createGridviewOpUrl($moduleId, $type, $pkId = 0, $appendParam = "") {
		$actionName = "";
		$pkType = "";
		if ($type == self::$createGridviewOpUrl_TYPE_ADD) {
			$actionName = "edit";
			$pkType = TDPrimaryKey::$PRIMARY_KEY_OPERATE_EMPTY_URLSTR;
		} else if ($type == self::$createGridviewOpUrl_TYPE_UPDATE) {
			$actionName = "edit";
			$pkType = TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN;
		} else if ($type == self::$createGridviewOpUrl_TYPE_DELETE) {
			$actionName = "delete";
			$pkType = TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN;
		} else if ($type == self::$createGridviewOpUrl_TYPE_VIEW) {
			$actionName = "view";
			$pkType = TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN;
		}
		$pkidstr = TDTable::getTableObj(TDModule::getModuleTableName($moduleId))->primaryKey;
		$appParemStr = '';
		if ($moduleId == TDStaticDefined::$mysqlCommonModuleId && isset($_GET[TDStaticDefined::$mysqlCommonMudelTabId])) {
			$appParemStr .= '/' . TDStaticDefined::$mysqlCommonMudelTabId . '/' . $_GET[TDStaticDefined::$mysqlCommonMudelTabId];
		}
		if (isset($_GET[TDStaticDefined::$viewChildTableDatasFromTbId]) && !empty($_GET[TDStaticDefined::$viewChildTableDatasFromTbId])) {
			$appParemStr .= '/' . TDStaticDefined::$viewChildTableDatasFromTbId . '/' . $_GET[TDStaticDefined::$viewChildTableDatasFromTbId];
			$appParemStr .= '/' . TDStaticDefined::$viewChildTableDatasFromPkId . '/' . $_GET[TDStaticDefined::$viewChildTableDatasFromPkId];
		}
		if (!is_string($pkidstr)) {
			$pkidstr = "id";
		}
		return TDPathUrl::createUrl('tDCommon/' . $actionName . '/moduleId/' . $moduleId . $appParemStr . '/' . $pkidstr . '/' . $pkId . $appendParam);
	}

	public static function getGETParam($name, $default = '') {
		$result = $default;
		if (isset($_GET[$name])) {
			if (!empty(Yii::app()->urlManager->urlSuffix))
				$result = str_replace(Yii::app()->urlManager->urlSuffix, "", $_GET[$name]);
			else
				$result = $_GET[$name];
		}
		return $result;
	}

	public static function getPostParam($name, $default = '') {
		$result = $default;
		if (isset($_POST[$name])) {
			if (!empty(Yii::app()->urlManager->urlSuffix))
				$result = str_replace(Yii::app()->urlManager->urlSuffix, "", $_POST[$name]);
			else
				$result = $_POST[$name];
		}
		return $result;
	}

	public static function getHttpHostString() {
		return 'http://' . $_SERVER['HTTP_HOST'];
	}

	public static function getEmptyImgUrl() {
		$bimUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/www/too_admin/';
		return $bimUrl . "image/wutu.jpg";
	}

	public static $menuForType_url = 0;
	public static $menuForType_commonPage = 1;
	public static $menuForType_menulink = 2;
	public static $menuForType_topMenulink = 3;

	public static function getMenuItemLink($minInd=0,$topMinId,$mitemId=0,$forType=0,$menuRowsIndex=false,$menuRowsCount=0) {
		$url = "";
		if ($forType == self::$menuForType_commonPage) {
			$devMD = "";
			if(TDSessionData::getCurIsDevModel()){
				$devMD = '<div class="btn-group pull-right" style="position: absolute;margin-left: 90px;margin-top: -28px;">
				<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-orange icon-wrench"></i></a>
				<ul class="dropdown-menu qkmenu">
				<li onclick="quickEditMenu('.$minInd.')"><span class="icon icon-color icon-edit"></span>'.TDLanguage::$quikEditMenu_edit.'</li>
				'.($menuRowsIndex != 0 ? '<li onclick="quickReorderMenu('.$minInd.',0)"><span class="icon icon-orange icon-arrowthick-n"></span>'.TDLanguage::$quikEditMenu_toUp.'</li>' : '').'
				'.($menuRowsIndex < $menuRowsCount-1 ? '<li onclick="quickReorderMenu('.$minInd.',1)"><span class="icon icon-orange icon-arrowthick-s"></span>'.TDLanguage::$quikEditMenu_toDown.'</li>' : '').'
				<li onclick="quickDeleteMenu('.$minInd.')"><span class="icon icon-color icon-close"></span>'.TDLanguage::$quikEditMenu_delete.'</li>
				</ul>
				</div>';
			}	
			if (!empty($minInd)) {
				$menu = TDModelDAO::queryRowByPk(TDTable::$too_menu, $minInd);
			} else {
				$menu = TDModelDAO::queryRowByCondtion(TDTable::$too_menu, "pid=" .$topMinId . " and `is_show`=1 order by `order`");
				$minInd = $menu["id"];
			}
			$url = TDPathUrl::createUrl('/tDCommon/menuItems/mnInd/' . $minInd . '/mitemId/0/topmnInd/'.$topMinId);
			//if(!empty($url) && !isset($_GET['mnInd'])) { header("Location:".$url); exit; }
			$url = '<li '.(isset($_GET['mnInd']) && TDPathUrl::getGETParam('mnInd') == $minInd ? ' class="active" ' : "")
			.' liname="menuli' . $menu['pid'] . '" style="margin-left: -2px;"><a  href="' . $url . '"><span class="hidden-tablet">' . $menu['name'] . '</span></a>'.$devMD.'</li>';
		} else if ($forType == self::$menuForType_menulink) {
			if (!empty($mitemId)) {
				$menuItem = TDModelDAO::queryRowByCondtion(TDTable::$too_menu_items, "id=" . $mitemId . " and `is_show`=1 order by `order`");
			} else if(!empty($minInd)) {
				$menuItem = TDModelDAO::queryRowByCondtion(TDTable::$too_menu_items, "menu_id=" . $minInd . " and `is_show`=1 order by `order`");	
			} else {
				$minInd = TDModelDAO::queryRowByCondtion(TDTable::$too_menu, "pid=" .$topMinId . " and `is_show`=1 order by `order`","id");
				$menuItem = TDModelDAO::queryRowByCondtion(TDTable::$too_menu_items, "menu_id=" . $minInd . " and `is_show`=1 order by `order`");	
			}
			$url = '';
			$layoutStr = TDStaticDefined::$pageLayoutType_inner; 

			//判读是否存在从属当前布局组合
			if(!isset($_GET["isCompos"]) && TDModelDAO::queryScalar("too_menu_items","layout_menu_items_pid=".intval($menuItem["id"]),"count(*)") > 0) {
				$url .= 'tDCommon/layoutCompos/isCompos/yes';
			} else {
				if ($menuItem['link_page_type'] == 1) {
					$url .= '/tDCommon/custome';
					$layoutStr = TDStaticDefined::$pageLayoutType_alone; 
				} else if ($menuItem['link_page_type'] == 2) {
					$url .= '/tDCommon/query';
				} else if (!empty($menuItem['module_id'])) {
					$url .= '/tDCommon/admin/moduleId/' . $menuItem['module_id'];
				} else if ($menuItem['link_page_type'] == 2) {
				}
				if (!empty($menuItem["action_url"])) {
					if (substr($menuItem["action_url"], 0, 1) !== "/") {
						$url .= "/";
					}
					$url .= $menuItem["action_url"];
				}
			}
			if (empty($url)) {
				$url = "#";
			} else {
				$url = TDPathUrl::createUrl($url . '/mnInd/' . $minInd . '/mitemId/' . $menuItem['id'] . '/topmnInd/' . $topMinId);
			}
			$url .= '/' . TDStaticDefined::$pageLayoutType . '/' .$layoutStr;
		}  else if($forType == self::$menuForType_topMenulink) {
			if (!empty($minInd)) {
				$menu = TDModelDAO::queryRowByPk(TDTable::$too_menu, $minInd);
			} else {
				$menu = TDModelDAO::queryRowByCondtion(TDTable::$too_menu, "pid=" .$topMinId . " and `is_show`=1 order by `order`");
				$minInd = $menu["id"];
			}
			$url = TDPathUrl::createUrl('/tDCommon/menuItems/mnInd/' . $minInd . '/mitemId/0/topmnInd/'.$topMinId);
		}
		return $url;
	}

}