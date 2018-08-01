<?php

class TDRequestData {
	
	public static function getGetData($getName,$default=0,$mustMumeric=false) {
		$result = $default;
		if(isset($_GET[$getName])) {
			$result = $_GET[$getName];
			$result = trim(str_replace(".html", "",$result));	
		}
		if($mustMumeric && !is_numeric($result)) {
			$result = $default;
		}
		return $result;
	}

	public static function setGetModuleId($moduleId) { $_GET['moduleId'] = $moduleId; }
	public static function getGetModuleId() { return self::getGetData('moduleId'); }
}
