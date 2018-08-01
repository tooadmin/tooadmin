<?php

class TDDataFiles_Website {

	public static function websitePublish($websiteId=0,$rePublishAll=false) { }

	public static function createWebSiteParams($modelType = 1) { }
	
	public static function checkLoadThemeHead($use_theme_head) {
		if(isset($_GET["tpagemodel"]) && $_GET["tpagemodel"] == "unloadtopfoot") { return false; } 
		else if($use_theme_head == 1) { return true; } return false;
	}
	public static function checkLoadThemeFoot($use_theme_foot) {
		if(isset($_GET["tpagemodel"]) && $_GET["tpagemodel"] == "unloadtopfoot") { return false; } 
		else if($use_theme_foot == 1) { return true; } return false;
	}
	
	public static function createPageFile($pageid,$modelType = 1) { } 

	public static function getPagePath($pageid=0) {
		$dtf = new TDDataFiles();
		if(empty($pageid)) {
			$websiteParams = require $dtf->getFilePath($websitePath."website_default.php");
			$pageid = $websiteParams["home_pageid"]; 
		}
		$pageParams = require $dtf->getFilePath($websitePath."pgparams/".$pageid.".php"); 
		$websiteParams = require $dtf->getFilePath($websitePath."website_".$pageParams["website_id"].".php");	
		if($websiteParams["is_active"] != 1) {
			header("Location:tDSite/admin");exit();
		}
		if($pageParams["login_view"] == 1) {
			if(Yii::app()->user->isGuest) {
				if(!empty($websiteParams["login_pageid"])) {
					$pageid = $websiteParams["login_pageid"]; 
				} else {
					TDCommon::tipMessage("未设置站点登陆PageID");
				}
			}
		}
		return $dtf->getFilePath($websitePath.$pageParams["website_id"]."/".$pageid."/page");	
	}
}
