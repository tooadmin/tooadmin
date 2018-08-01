<?php

class TDLayout {
	
	public static function getComonPage() {
		return TDCommon::getRender('layouts/common_page');
	}
	public static function getSinglePage() {
		return TDCommon::getRender('layouts/single_page');
	}
	public static function getAlonePage() {
		return TDCommon::getRender('layouts/alone_page');
	}
	public static function getInnerPage() {
		return TDCommon::getRender('layouts/inner_page');
	}
	
	public static function getLayout($swidget="") {
		$layout = self::getComonPage();
		if($swidget instanceof TDGridView) {
			$layout = self::getComonPage();
		} else if($swidget instanceof TDEditForm) {
			$layout = self::getSinglePage();
		} else if($swidget instanceof TDView) {
			$layout = self::getSinglePage();
		}
		if(isset($_GET[TDStaticDefined::$pageLayoutType])) {
			if($_GET[TDStaticDefined::$pageLayoutType] == TDStaticDefined::$pageLayoutType_single) {
				$layout = self::getSinglePage();
			} else if($_GET[TDStaticDefined::$pageLayoutType] == TDStaticDefined::$pageLayoutType_alone) {
				$layout = self::getAlonePage();
			} else if($_GET[TDStaticDefined::$pageLayoutType] == TDStaticDefined::$pageLayoutType_inner) {
				$layout = self::getInnerPage();
			} else if($_GET[TDStaticDefined::$pageLayoutType] == TDStaticDefined::$pageLayoutType_common) {
				$layout = self::getComonPage();
			}
		}
		return $layout;
	}
}