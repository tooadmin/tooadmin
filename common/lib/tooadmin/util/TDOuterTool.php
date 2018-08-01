<?php
class TDOuterTool {

	public static function getIPLocationArea($ip) {
		///header("Content-Type: text/html;charset=gb2312");//一句句分析的时候用
		$str = @file_get_contents("http://www.ip138.com/ips1388.asp?ip=".$ip."&action=2");
		$a = @strpos($str,$ip);
		$b = @strrpos($str,$ip); 
		$str = @substr($str,$a,$b-$a);
		$a = @strpos($str,"<li>");
		$b = @strpos($str,"</li>");
		$str = @substr($str,$a+4,$b-$a);
		$str = @substr($str,12);
		$str = @TDCommon::auto_charset($str);
		return $str;
	}
}
