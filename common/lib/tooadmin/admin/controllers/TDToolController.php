<?php

class TDToolController extends TDController {
	public function actionsRemark() { return array(); }
	public function accessRules() { return array(); }

	public function actionImgView() {
		$imgUrl = isset($_GET["imgUrl"]) ? $_GET["imgUrl"] : "";
		$maxWidth = isset($_GET["maxWidth"]) ? intval($_GET["maxWidth"]) - 25 : "100";
		$maxHeight = isset($_GET["maxHeight"]) ? intval($_GET["maxHeight"]) - 25 : "100";
		echo '<img src="'.$imgUrl.'" style="margin-left:5px;margin-top:5px;max-width:'.$maxWidth.'px;max-height:'.$maxHeight.'px;" >';
	}

	public function actionTipMsg() {
		echo TDSessionData::getPopupTipMsg();
	}
}
