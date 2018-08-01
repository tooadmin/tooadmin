<?php

class TDSiteController extends TDController {

	public function actionsRemark() {
		return array();
	}

	public function accessRules() {
		return array();
	}

	public function actions() {
		return array(
		    'captcha' => array(
			'class' => 'CCaptchaAction',
			'backColor' => 0xFFFFFF, //背景颜色  
			'minLength' => 4, //最短为4位  
			'maxLength' => 4, //是长为4位  
			'transparent' => true, //显示为透明  
			'testLimit' => 1, //相同验证码显示的次数
		    ),
		    'page' => array('class' => 'CViewAction',),
		);
	}

	public function actionLogin() {
		$checkuid = TDSessionData::getUserId();
		if (!TDSessionData::currentUserIsManager() && !empty($checkuid)) {
			TDCommon::tipMessage("请先退出前台的登陆，再刷新当前页面登陆后台。");
		}
		$this->layout = TDLayout::getSinglePage();
		$model = new TDLoginForm;
		$eror_msg = "";
		$toValidateLogin = false;
		$useQrcodeLogin = false;
		if (isset($_POST['TDLoginForm'])) {
			$model->attributes = $_POST['TDLoginForm'];
			$toValidateLogin = true;
		} else if(isset($_POST['qrLogin'])) {
			$useQrcodeLogin = true;
			if(Yii::app()->session['qr_tm'] > time()-180) {
				$str = Yii::app()->session['qr_tm'].Yii::app()->session['qr_ip'].Yii::app()->session['qr_num']; 
				$str = md5($str);
				$row = TDModelDAO::getModel(TDTable::$too_user)->find("nt_pwd='".$str."'");
				if(!empty($row)) {
					$row->nt_pwd = "";
					$row->save();	
					$model->init(true,$row->id);
					$toValidateLogin = true;	
				}
			}
		}
		if($toValidateLogin) {
			if (empty($eror_msg) && $model->validate() && $model->login()) {
				$user = TDModelDAO::getModel("too_user", TDSessionData::getUserId());
				$loginRow = TDModelDAO::getModel(TDTable::$too_login_log);
				$loginRow->too_uid = TDSessionData::getUserId();
				$loginRow->from_ip = TDCommon::getClientIp();
				$loginRow->login_time = time();
				//$loginRow->login_area = TDOuterTool::getIPLocationArea($loginRow->from_ip);//会很慢
				$loginRow->save();
				TDSessionData::setClientWidth($_POST['clientWidth'] - 80);
				if(!$useQrcodeLogin) {
					$this->redirect(TDPathUrl::createUrl('tDSite/admin'));
				} else {
					echo "success";exit;
				}
			}
		}
		if($useQrcodeLogin) {
			echo $eror_msg;exit;
		}
		$this->render('login', array('model' => $model, 'eror_msg' => $eror_msg,));
	}
	
	public function actionInstall() {
		$this->layout = TDLayout::getSinglePage();
		$this->render('install');
	}

	

	public function actionTmpUnpack() {
		$dateTime = $_GET['dateTime']; //"2016-04-14 15:00:00"
		$upgrade = new TDUpgrade();
		$upgrade->createUpgreadePackage($dateTime);
		echo "finish  " . date("Y-m-d H:i:s");
	}

	public function actionCommon() {
		$user_id = TDSessionData::getUserId();
		if (Yii::app()->user->isGuest || empty($user_id)) {
			$this->redirect(TDPathUrl::createUrl('tDSite/logout'));
		}
		$this->render('index', array());
	}

	public function actionAdmin() {
		//*/
		//echo "pkid=".$model->getPrimaryKey()."<br/>";
		//echo "pkid2=".$model->primaryKey."<br/>";exit;
		//echo "getTableSchema()->primaryKey=".$model->getTableSchema()->primaryKey."<br/>";exit;
		$user_id = TDSessionData::getUserId();
		if (Yii::app()->user->isGuest || empty($user_id)) {
			$this->redirect(TDPathUrl::createUrl('tDSite/logout'));
		}
		$this->render('index', array());
		exit;
		$homeUrl = TDSessionData::getHomeUrl();
		if (!empty($homeUrl)) {
			$this->redirect($homeUrl);
		} else {
			$this->render('index', array());
		}
	}

	public function actionIndex() {
		$user_id = TDSessionData::getUserId();
		if (Yii::app()->user->isGuest || empty($user_id)) {
			$this->redirect(TDPathUrl::createUrl('tDSite/logout'));
		}
		$this->render('index', array());
		exit;
		$homeUrl = TDSessionData::getHomeUrl();
		if (!empty($homeUrl)) {
			$this->redirect($homeUrl);
		} else {
			$this->render('index', array());
		}
		exit;
	}

	public function actionError() {
		if (isset(Yii::app()->errorHandler->error["message"])) {
			echo Yii::app()->errorHandler->error["message"] . "<br/>";
		}
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
		}
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(TDPathUrl::createUrl('tDSite/login'));
	}

	public function runResultTip($sql, $result) {
		if ($result) {
			echo "执行成功:" . $sql . "<br/>";
		} else {
			echo "执行失败:" . $sql . "<br/>";
		}
	}
	
}
