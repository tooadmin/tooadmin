<?php

class TDFileZipUnZip {
	public function unzip_file($file,$destination) { 
  		$zip = new ZipArchive() ; 
  		if ($zip->open($file) !== TRUE) { 
  			die ('upgrade file error'); 
  		} 
  		$zip->extractTo($destination); 
  		$zip->close(); 
 	} 

	public function create_zip($files = array(),$destination = '',$overwrite = false) { 
		if(file_exists($destination) && !$overwrite) { 
			return false; 
		} 
		$valid_files = array(); 
		if(is_array($files)) { 
			foreach($files as $file) { 
				if(file_exists($file)) { 
					$valid_files[] = $file; 
				} 
			} 
		} 
		if(count($valid_files)) { 
			$zip = new ZipArchive(); 
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) { 
				return false; 
			} 
			foreach($valid_files as $file) { 
				$zip->addFile($file,$file); 
			} 
			//echo $zip->numFiles  $zip->status; 
			$zip->close(); 
 			return file_exists($destination); 
		} else { 
			return false; 
		} 
	} 

}
