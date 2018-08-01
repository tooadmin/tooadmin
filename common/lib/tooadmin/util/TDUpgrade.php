<?php
class TDUpgrade {

	public $upgradePath = "http://www.tooadmin.com/upgrade.php";
	public $TYPE_EDIT = "edit";
	public $TYPE_DELETE = "delete";
	public $TYPE_CODE = "code";
	public function doUpgradeFiles() {
		if(Yii::app()->params->is_auto_upgrade) {
			$datas = json_decode(file_get_contents($this->upgradePath.'?version='.Yii::app()->params->sys_version),true);
			$files = $datas["files"];
			foreach($files as $item) {
				$filePath = $item["file"];
				$filefolder = substr($filePath,0,strrpos($filePath,"/"));
				if($item["type"] == $this->TYPE_EDIT) {
					if(!is_dir($filefolder)) {
						mkdir($filefolder,0777,true); 
					}	
					if(is_file($filePath) && !is_writable($filePath)) {
						unlink($filePath);
					}
					$text = file_get_contents($this->upgradePath.'?getFile='.$filePath);
					$file = fopen($filePath,"w");
					fwrite($file,$text);
					fclose($file);
				} else if($item["type"] == $this->TYPE_DELETE) {	
					if(is_file($filePath)) {
						unlink($filePath);
					}	
				} else if($item["type"] == $this->TYPE_CODE) {
					$text = file_get_contents($this->upgradePath.'?getFile='.$filePath);	
					eval($text);
				}
			}
			if(!empty($files)) {
				///TDSysConfig::setSysVersion($datas["newVersion"]);
			}
		}		
	}
	
	private $upgradeFilePath = 'upgradeFiles'; 
	
	private function checkFileType($filePath,$name) {
		$unPassTypes = array('.','..','.svn');
		if(in_array($name,$unPassTypes)) {
			return false;
		}
		$typestr = substr($filePath,strripos($filePath,"."));
		$baseName = str_replace($typestr,"",$name);
		if(empty($baseName)) {
			return false;
		}
		return true;
	}

	public function getDirFiles($dir) {
		$files = array();
		$d = opendir($dir);	
		while($file = readdir($d)) {
			$readFile = $dir.'/'.$file;
			if(!$this->checkFileType($readFile,$file)) {
				continue;
			}
			if(is_dir($readFile)) {
				$files = array_merge($files,$this->getDirFiles($readFile));
			}	
			if(is_file($readFile)) {
				$files[] = $readFile;
			}
		}
		return $files;
	}
		
	private function getLastUpdateFiles($dir,$dateTime) {
		$files = array();
		$d = opendir($dir);	
		while($file = readdir($d)) {
			$readFile = $dir.'/'.$file;
			if(strpos($file,"FactoryDB") !== false) {
				continue;
			}
			if(!$this->checkFileType($readFile,$file)) {
				continue;
			}
			if(is_dir($readFile)) {
				$files = array_merge($files,$this->getLastUpdateFiles($readFile,$dateTime));
			}	
			if(is_file($readFile)) {
				$mtime = filemtime($readFile);
				if($mtime >= strtotime($dateTime)) {
					$files[] = $readFile;
				}
			}
		}
		return $files;
	}

	public function addFileToZip($path,$zip,$lasttime){
    	$handler=opendir($path); //打开当前文件夹由$path指定。
    	while(($filename=readdir($handler))!==false){
        	if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
            	if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
					$this->addFileToZip($path."/".$filename,$zip,$lasttime);
            	}else{ //将文件加入zip对象
					if(is_file($path."/".$filename)) {
						$mtime = filemtime($path."/".$filename);
						if($mtime >= $lasttime) {
                			$zip->addFile($path."/".$filename);
						}
					}
                	//$zip->addFile($path."/".$filename);
            	}
        	}
    	}
    	@closedir($path);
	}

	private function createOrUpdateByCopyFiles($files,$sourceBasePath,$destinationBasePath) {
		foreach($files as $file) {
			$fileDir = str_replace($sourceBasePath,"",$file);
			$desFile = $destinationBasePath.$fileDir;
			$desDir = str_replace(substr($desFile,strripos($desFile,"/")),"",$desFile); 
			if(!is_dir($desDir)) {
				mkdir($desDir,0777,true);
			}
			copy($file,$desFile);
		}	
	}

	public function upgradeFiles($logId) {
		$filezip = new TDFileZipUnZip();
		//解压
		$filezip->unzip_file($this->upgradeFilePath.'/'.$logModel->version.'.zip',$this->upgradeFilePath);
		//升级文件
		$this->createOrUpdateByCopyFiles($this->getDirFiles($this->upgradeFilePath.'/'.$logModel->version),$this->upgradeFilePath.'/'.$logModel->version.'/','');	
	}
}