<?php

class TDApiDAO {
	
	public $dbp_model = null;
	
	public function getApiSecretKey($api) {
		$api = str_replace(":","",$api);
		$apiArr = explode(".",$api);
		$num = 1;
		foreach($apiArr as $ival) {
			$num *= $ival/(strlen($ival)+1);	
		}	
		$num = round($num/strlen($num),2);	
		$api .= "_".$num;
		$str = md5($api); 
		$secretKey = "";
		$secretKey .= $str[0];
		$secretKey .= $str[5];
		$secretKey .= $str[12];
		$secretKey .= $str[19];
		$secretKey .= $str[23];
		$secretKey .= $str[27];
		$secretKey .= $str[30];
		$secretKey .= $str[31];
		return $secretKey;
	}

	private function validateSecretKey($api,$secretKey) { if($this->getApiSecretKey($api) === $secretKey) { return true; } else { return false; }	}

	public function validateIPSecretKey() {
		$requestData = TDApiRequestData::getApiRequestData();
		$errorMsg = "";
		if(empty($requestData->secret_key)) { 
			$errorMsg = " secret key is empty"; 
		} else if($requestData->use_simulation) {
			if(empty($requestData->analog_ip)) {
				$errorMsg = "analog ip is empty"; 
			}
		} else if(!$this->validateSecretKey(TDCommon::getClientIp(),$requestData->secret_key)) {
			$errorMsg = " secret key validate fail ! "; 
		}
		if(!empty($errorMsg)) { throw new Exception($errorMsg); }
	}

	public function excuteDao() {
		$data = array();
		$ERROR_MSG = "";
		$requestData = TDApiRequestData::getApiRequestData();
		$function = $requestData->function;
		$dataDao = new TDApiDataDAO($this->dbp_model);
		if(method_exists($dataDao,$function)) {
			$res = $dataDao->$function();
			if(is_array($res)) { $data = $res; } 
			else if(!empty($res) && is_string($res)) { $ERROR_MSG = $res; }
		} else {
			$ERROR_MSG = "undefined function '".$function."' ";
		}
		if(!empty($ERROR_MSG)) { throw new Exception($ERROR_MSG); }
		echo json_encode($data); 
	}	
	
}
