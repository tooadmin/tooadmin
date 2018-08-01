<?php
/* * version 1.1 */
ini_set('max_execution_time','0');
ini_set('memory_limit', '-1');
class TDApiModel {

	private $API_URL= "http://localhost/tooadmin/admin.php/tDCommonIO/apiDataDao";//api 地址
	private $USE_SIMULATION = "true";//是否使用模拟IP
	private $ANALOG_IP = "103.19.85.147";//模拟IP,只有设置了该IP启动模拟IP开发模式，才能生效.对应的加密码即为模拟IP的加密码
	private $SECRET_KEY = "29690361"; //加密码

	private function getdataFormApi($params) {
		if(!isset($params['TABLE']) || empty($params['TABLE'])) { throw new Exception("table is empty"); }
		$params["SECRET_KEY"] = $this->SECRET_KEY;
		$params["USE_SIMULATION"] = $this->USE_SIMULATION;	
		$params["ANALOG_IP"] = $this->ANALOG_IP; 
		if(in_array($params["FUNCTION"],array("addRow","updateRow")) && isset($_FILES) && is_array($_FILES)) {
			$params["FILES_ITEMS"] = $_FILES;	
			foreach($params["FILES_ITEMS"] as $inputName => $item) {
				$params["COLUMNS"] .= empty($params["COLUMNS"]) ? $inputName : ",".$inputName;
				$params["COLUMN_".$inputName] = "";
				$fileCode= "";
				if(is_file($item["tmp_name"])) {
					$file = fopen($item["tmp_name"],"r");
					while (!feof($file)) { $fileCode .= fgets($file); }
					fclose($file); unlink($item["tmp_name"]);
				}
				$params["FILES_ITEMS"][$inputName]["file_code"] = $fileCode; 
			}
		}
		$context = array(
    			"http" => array(
				"method" => "POST",
				"header" => "Content-type:application/x-www-form-urlencoded",
				"content" =>http_build_query($params),
			)
		);
		$dataArray = json_decode(file_get_contents($this->API_URL,false,stream_context_create($context)),true);
		if(isset($dataArray['ERROR_MSG'])) { echo $dataArray['ERROR_MSG']; exit; }
		return $dataArray;
	}	

	public function findByPk($table,$pkId) {
		$params = array('TABLE' => $table,'PKID' => $pkId,'FUNCTION' => "findByPk");			
		return $this->getdataFormApi($params);
	}

	public function findAll($table,$select="*",$condition="1") {
		$params = array('TABLE' => $table,'SELECT' =>$select,'CONDITION'=>$condition,'FUNCTION' => "findAll");			
		return $this->getdataFormApi($params);
	}

	public function find($table,$select="*",$condition="1") {
		$params = array('TABLE' => $table,'SELECT' =>$select,'CONDITION'=>$condition,'FUNCTION' => "find");			
		return $this->getdataFormApi($params);
	}

	public function addRow($table,$mapData) {
		$params = array('TABLE' => $table,'FUNCTION' => "addRow");			
		$columns = "";
		foreach($mapData as $k => $v) {
			if(!empty($columns)) { $columns .= ","; }
			$columns .= $k;	
			$params["COLUMN_".$k] = $v;
		}
		$params["COLUMNS"] = $columns;
		return $this->getdataFormApi($params);
	}

	public function updateRow($table,$pkId,$mapData) {
		$params = array('TABLE' => $table,'PKID' => $pkId,'FUNCTION' => "updateRow");			
		$columns = "";
		foreach($mapData as $k => $v) {
			if(!empty($columns)) { $columns .= ","; }
			$columns .= $k;	
			$params["COLUMN_".$k] = $v;
		}
		$params["COLUMNS"] = $columns;
		return $this->getdataFormApi($params);
	}

	public function deleteByPk($table,$pkId) {
		$params = array('TABLE' => $table,'PKID' => $pkId,'FUNCTION' => "deleteByPk");			
		return $this->getdataFormApi($params);
	}

	public function getPermissionDetail() {
		$params = array('TABLE'=>'Test','FUNCTION' => "getPermissionDetail");			
		 $result = $this->getdataFormApi($params);
		echo $result["info"]; 
	}
}
