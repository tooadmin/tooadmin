<?php

class TDIplimit
{
	public static function isChinaIp() {
		$userIp = TDCommon::getClientIp();
		$html = file_get_contents("http://ip.itlearner.com/?ip=".$userIp);
		if(strpos($html,"China") !== false) {
			return true;
		} else {
			return false;
		}	
	}
}
?>