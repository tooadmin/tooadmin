<?php

class TDUserIdentity extends CUserIdentity {

	private $_userid;
	private $_username;
	private $_nickname;
	private $_roles;

	public function authenticate() {
		$model = TDModelDAO::getModel(TDTable::$too_user);
		if($this->username==TDLoginForm::$QRCODELOGIN) {
			$user = $model->find("id='".$this->password."'");
		} else {
			$user = $model->find("username='".$this->username."' and password='".md5($this->password)."'");
		}
		if(!Yii::app()->params->is_use_key) {
			$isPass  = true;
                	$usbkeyPass = true;
			if(empty($user)) { $isPass = false; }
		} else {
                	$isPass  = false;
			$usbkeyPass = false;
			if(!empty($user)) {
				$isPass = true;
				//加密锁验证
				if($user->nt_code == $_POST["login_nt_code"]) { $usbkeyPass = true; }
			}
		}
		if(!$usbkeyPass) {
                	$this->errorCode="NTKeyEror";
		} else if(!$isPass) {
                	$checkUseName = $model->find("username='".$this->username."'");
			if(!empty($checkUseName)) {
                		$this->errorCode=self::ERROR_PASSWORD_INVALID;
			} else {
                		$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
            	} else {
                	$this->_userid    = $user->primaryKey;
                	$this->_username  = $user->username;
                	$this->_nickname  = $user->nickname;
			$this->_roles = $user->roles;
                	$this->errorCode=self::ERROR_NONE;
            	}
		return !$this->errorCode;
	}
        
        public function getId() { return $this->_userid; }
        
        public function getUsername() { return $this->_username; }
        
	public function getTruename() { return $this->_nickname; }

	public function getRoles() { return $this->_roles; } 
}