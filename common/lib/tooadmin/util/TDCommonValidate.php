<?php
class TDCommonValidate {
	public static function mobile($mobile) {
		$preg = "/1[0123456789]{1}\d{9}$/";	
		if(!empty($mobile) && preg_match($preg,$mobile)) {
			return true;
		}
		return false;
	}

	public static function email($email) {
		//$preg = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";	
		$preg = "/^([0-9A-Za-z\\-_\\.]+)@([0-9A-Za-z\\-_]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";	
		if(preg_match($preg,$email)) {
			return true;
		}
		return false;
	}

	public static function IDNumber($IDNumber) {
		$preg = "/^[1-9]{1}\d{14}$|^[1-9]{1}\d{13}(\d|X|x)$|^[1-9]{1}\d{17}$|^[1-9]{1}\d{16}(\d|X|x)$/";
		if(preg_match($preg,$IDNumber)) {
			return true;
		}
		return false;
	}

	public static function bankNumber($bankNumber) {
		$preg = "/[0-9]{11,15}$/";
		if(preg_match($preg,$bankNumber)) {
			return true;
		}
		return false;	
	}
	
}
