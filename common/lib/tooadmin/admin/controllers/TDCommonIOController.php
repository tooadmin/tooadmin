<?php
class TDCommonIOController extends TDController {

	public function accessRules() { return array(); }

	public function actionsRemark() { return array();}	
	
	public function actionBstr() {	 TDCommon::formatBstr(); }

	//登陆验证NT加密锁
	public function actionNtValidate() {
		$pwd = "";
		//判断是否属于当前网站的KEY
		$keyname = str_replace("NT199_","",$_POST["keyname"]);
		$host =  $_SERVER['HTTP_HOST'];
		if(strpos($host,$keyname) !== false || !Yii::app()->params->is_use_key || true) {
			$type = $_POST["nttype"];
			$code = $_POST["code"];
			if($type == "ntpwd" && !empty($code)) {
				$user_id = TDSessionData::getUserId();
				if (!Yii::app()->user->isGuest && !empty($user_id)) { 
					if(isset(Yii::app()->session['nt_pwd'])) {
						$pwd = Yii::app()->session['nt_pwd']; 
					} else {
						$row = TDModelDAO::getModel(TDTable::$too_user)->find(array("select"=>"nt_pwd","condition"=>"`id`=".$user_id." and nt_code='".$code."'"));
						if(!empty($row)) { $pwd = $row->nt_pwd; Yii::app()->session['nt_pwd'] = $pwd; }
					}
				} else {
					$row = TDModelDAO::getModel(TDTable::$too_user)->find(array("select"=>"nt_pwd","condition"=>"nt_code='".$code."'"));
					if(!empty($row)) { $pwd = $row->nt_pwd; }
				}
			}	
		}
		echo json_encode(array("pwd"=>$pwd));
	}
	
	public function actionCommonQrcode() {
		TDPhpqrcode::includeLib();
	   	if(isset($_REQUEST['qr_content']) && !empty($_REQUEST['qr_content'])) {
			$content = $_REQUEST['qr_content'];
			$matrixPointSize = 5;
    		if (isset($_REQUEST['qr_size']))
        			$matrixPointSize = min(max((int)$_REQUEST['qr_size'], 1), 10);
    		if (!file_exists(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH))) 
					mkdir(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH));
			$filename = 'commonQrcode.png';
			$errorCorrectionLevel = 'L';
			QRcode::png($content,TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH).$filename,
			$errorCorrectionLevel, $matrixPointSize, 2); 

  			$png = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH).$filename;
  			$QR = imagecreatefrompng($png);
			header('Content-type: image/png');
  			imagepng($QR);
  			imagedestroy($QR);
		}
	}
	//public function actionAskIP() { echo "IP=". TDCommon::getClientIp(); }

	public function actionOutside() {
		$outkey = $_GET["outkey"]; 
		$res = [];
		$baseUrl = 'http://test.cherishlovo.com/mobile.php/out/';
		if($outkey == 'wxmenu') {
			$res = file_get_contents($baseUrl.'wxmenu'); 
		}
		echo $res;
	}
}





