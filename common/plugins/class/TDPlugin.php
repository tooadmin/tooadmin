<?php
class TDPlugin {

	public static function getConfig($fileName) {
		$file = './common/plugins/config/'.$fileName.'.php';
		if(is_file($file)) { return require $file; } else { return array();}
	}
	
}
