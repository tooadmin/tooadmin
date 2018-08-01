<?php

class TDLoginForm extends CFormModel {

	public static $QRCODELOGIN = "qrcode_login_";
	
	public $username;
	public $password;
	public $verifyCode;
	public $_identity;

	public function init($userQrCodeLogin=false,$userId=0) {
		parent::init();
		if($userQrCodeLogin && !empty($userId)) {
			$this->username = self::$QRCODELOGIN;
			$this->password = $userId;
		}
	}
	
	public function rules() {
		if($this->username == self::$QRCODELOGIN) {
			return array(
				array('username', 'required', 'message' => TDLanguage::$login_msg_username_empty),
				array('password', 'required', 'message' => TDLanguage::$login_msg_pwd_empty),
				array('username', 'validateLogin'),
			);
		}
		return array(
			array('username', 'required', 'message' => TDLanguage::$login_msg_username_empty),
			array('password', 'required', 'message' => TDLanguage::$login_msg_pwd_empty),
			array('verifyCode', 'required', 'message' => TDLanguage::$logon_msg_verify_code_empty),
			array('verifyCode', 'captcha', 'allowEmpty' => false, 'message' => TDLanguage::$logon_msg_verify_code_error),
			array('username', 'validateLogin'),
		);
	}

	public function validateLogin($attribute, $params) {
		$this->_identity = new TDUserIdentity($this->username, $this->password);
		if (!$this->_identity->authenticate()) {
			if ($this->_identity->errorCode == "NTKeyEror") {
				$this->addError('username', TDLanguage::$login_msg_usbkey_error);
			} else if ($this->_identity->errorCode == TDUserIdentity::ERROR_PASSWORD_INVALID) {
				$this->addError('password', TDLanguage::$login_msg_pwd_error);
			} else {
				$this->addError('username', TDLanguage::$login_msg_name_error);
			}
			if (!isset(Yii::app()->session['login_erro_times'])) {
				Yii::app()->session['login_erro_times'] = 1;
			} else {
				Yii::app()->session['login_erro_times'] = Yii::app()->session['login_erro_times'] + 1;
			}
		}
	}

	public function login() {
		if ($this->_identity === null) {
			$this->_identity = new TDUserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === TDUserIdentity::ERROR_NONE) {
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			$duration = 21600; // 6 h 
			Yii::app()->user->login($this->_identity, $duration);
			TDSessionData::setUserId($this->_identity->getId());
			TDSessionData::setUserName($this->_identity->getUsername());
			TDSessionData::seNickName($this->_identity->getTruename());
			TDSessionData::setRoles($this->_identity->getRoles());
			TDSessionData::setIsManager();
			TDSessionData::afterLoginInit();
			return true;
		} else
			return false;
	}
}
