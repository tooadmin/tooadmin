<?php
class TDDate {
	
	private static function normDatetime() {
		$url = "https://www.gopay.com.cn/PGServer/time";
		$context = array(
    			"http" => array(
				"method" => "GET",
				"header" => "Content-type:application/x-www-form-urlencoded",
				"content" => "",//http_build_query($params),
			)
		);
		//$YMDHI = file_get_contents($url,false,stream_context_create($context));
		$YMDHI = file_get_contents($url);
		return strtotime($YMDHI);
	}
	
	public static function time() {
		return self::normDatetime();	
	}
}
