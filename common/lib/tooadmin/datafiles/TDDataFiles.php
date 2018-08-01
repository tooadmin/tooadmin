<?php
class TDDataFiles {

	//public static function getEditFileRenderPath($tableName,$columnName,$pkId) { $file = self::bp(self::$editfile.$tableName.'_'.$pkId.'_'.$columnName.".php"); if(is_file($file)) { return substr($file,0,  strlen($file)-4);} return ''; }
	public static function bp($file="") { return "./assets/program/".$file; }
	public function getFilePath($file) { $path = self::bp($file); TDCommon::mkdir($path); return $path; }
	public static function getDBTables() { $result = array(); $rows = TDModelDAO::queryAll(TDTable::$too_table_collection,"","`table`"); foreach($rows as $row) { $result[] = $row["table"];	} return $result; }
	public static function createOrDeleteFile($path,$content="") { if(!empty($content)) { $fp = fopen($path,"w"); fwrite($fp,$content); fclose($fp); } else { if(is_file($path)) { unlink($path); } }	 }
	public static function getModelClass($tableName) { 
		$classPath = 'modclass/';
		$df = new TDDataFiles();
		if(!is_file($df->getFilePath($classPath."md_".$tableName.".php"))) { 
			$extend = "TDCommonModel";
			if(strpos($tableName,"too_") === 0) {
				$extend = "TDTooModel";	
			}
			$str = ' class md_'.$tableName.' extends '.$extend.' { public function __construct($scenario="insert") { $this->tableName = "'.$tableName
			.'"; parent::__construct($scenario); } public static function getClassName() { return __CLASS__; } }';	
			$content = '<?php '.$str.' ?>';
			self::createOrDeleteFile($df->getFilePath($classPath."md_".$tableName.".php"), $content);	
		} 
		$mdstr = "md_".$tableName; $obj = new $mdstr(); return $obj;
	}

	public static function getCompressFile($filesArray,$newFileName) {
		$df = new TDDataFiles();
		$compFile = $df->getFilePath('compress/'.$newFileName);
		if(!is_file($compFile)) { 
			$content = "";
			foreach($filesArray as $file) {
				if(is_file($file)) {
					$content .= file_get_contents($file);	
					$content .= "\n";
				}
			}
			self::createOrDeleteFile($compFile, $content);
			$target = $df->getFilePath('img');
			$source = './common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/img';
			TDDataFiles::copy_dir($source,$target);
		}
		return Yii::app()->baseUrl.'/assets/program/compress/'.$newFileName;
	}

	public static function copy_dir($src,$dst) {
  		$dir = opendir($src);
  		while(false !== ( $file = readdir($dir)) ) {
    			if (( $file != '.' ) && ( $file != '..' )) {
      				if ( is_dir($src . '/' . $file) ) {
        				TDDataFiles::copy_dir($src . '/' . $file,$dst . '/' . $file);
        				continue;
      				} else {
					TDCommon::mkdir($dst.'/'.$file);
        				copy($src . '/' . $file,$dst . '/' . $file);
      				}
    			}
  		}
  		closedir($dir);
	}
}
