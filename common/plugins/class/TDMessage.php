<?php

class TDMessage {
	
	public function sendMobileMsg($msg,$mobile,$expandParams=array()) { 
		$sms_config = TDPlugin::getConfig("TDSMS");
		if($sms_config["usemodel"] == "gysoft") {
			$config = $sms_config["gysoft"];
			$url = TDPathUrl::getHttpHostString().Yii::app()->baseUrl.$config["path"]; 
			$params = array(
		    		"uid" => $config["uid"],
		    		"pwd" => $config["pwd"],
		    		"mobile"=> $mobile,
		    		"content" => $msg,
			);
			$context = array("http" => array(
				"method" => "POST","header" => "Content-type:application/x-www-form-urlencoded","content" =>http_build_query($params),));
			$dataArray = json_decode(file_get_contents($url,false,stream_context_create($context)),true);
			if($dataArray["result"]) { return true; } else { return false; }
		} else if($sms_config["usemodel"] == "yuntongxun") {
			$yun_id = isset($expandParams["yun_id"]) ? $expandParams["yun_id"] : "0";
			if(empty($yun_id)) { return false; }
			$config = $sms_config["yuntongxun"];
			$url = TDPathUrl::getHttpHostString().Yii::app()->baseUrl.$config["path"]; 
			$params = array(
		    	"mobile"=> $mobile,
		    	"content" => $msg,
			"yun_id" => $yun_id,  
			"param1" => isset($expandParams["param1"]) ? $expandParams["param1"] : "null_param",
			"param2" => isset($expandParams["param2"]) ? $expandParams["param2"] : "null_param",
			"param3" => isset($expandParams["param3"]) ? $expandParams["param3"] : "null_param",
			"param4" => isset($expandParams["param4"]) ? $expandParams["param4"] : "null_param",
			"param5" => isset($expandParams["param5"]) ? $expandParams["param5"] : "null_param",
			"accountSid" => $config["accountSid"],
			"accountToken" => $config["accountToken"], 
			"appId" => $config["appId"],  
			"serverIP" => $config["serverIP"],   	    
			"serverPort" => $config["serverPort"],   	    
			"softVersion" => $config["softVersion"],      
			);
			$context = array("http" => array(
			"method" => "POST","header" => "Content-type:application/x-www-form-urlencoded","content" =>http_build_query($params),));
			$dataArray = json_decode(file_get_contents($url,false,stream_context_create($context)),true);
			if($dataArray["result"]) { return true; } else { return false; }	
		} else if($sms_config["usemodel"] == "chaotang") {
			$config = $sms_config["chaotang"];	
			include_once $config["path"]; 
			$ssUrl = $config["ssUrl"];
			$sscomCode = $config["sscomCode"];
			$ssloginName = $config["ssloginName"];
			$sspswd = $config["sspswd"];
			$proxyhost = false;
			$proxyport = false;
			$proxyusername = false;
			$proxypassword = false;
			$connectTimeOut = 2;
			$readTimeOut = 10;
			$esm = new ESM ( $ssUrl, $sscomCode, $ssloginName, $sspswd, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut );
			$result = $esm->SendMsg ( $mobile,$msg);
			return $result;
		} else if($sms_config["usemodel"] == "chaotang") {
			$config = $sms_config["chaotang"];	
			$url = TDPathUrl::getHttpHostString().Yii::app()->baseUrl.$config["path"]; 
			$params = array(
		    		"uid" => $config["uid"],
		    		"pwd" => $config["pwd"],
		    		"mobile"=> $mobile,
		    		"content" => $msg,
			);
			///$context = array("http" => array(
			//"method" => "POST","header" => "Content-type:application/x-www-form-urlencoded","content" =>http_build_query($params),));
			//$dataArray = json_decode(file_get_contents($url,false,stream_context_create($context)),true);
			//if($dataArray["result"]) { return true; } else { return false; }
			//服务器宝不支持 file_get_contents 的情况下
			$url .= '?uid=%s&pwd=%s&mobile=%s&content=%s&time='.time();	
			$ch = curl_init();
			$timeout = 10;
			$uid = urlencode($params["uid"]); 
			$pwd = urlencode($params["pwd"]); 
			$mobile = urlencode($params["mobile"]);
			$content = $params["content"];//iconv("UTF-8","GB2312",$params["content"]);//iconv("UTF-8","GB2312","hellow".time()); //urlencode("hellow".time()); //
			$url = sprintf($url,$uid,$pwd,$mobile,$content);
			//$url .= "?uid".$params["uid"]."&pwd=".$params["pwd"]."&mobile=".$params["mobile"]."&content=".$content;
			//$url = sprintf($url, $id, $pwd, $to, $content);
      			$ch = curl_init();
			$this_header = array( "content-type: application/x-www-form-urlencoded; charset=UTF-8");
			curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
      			curl_setopt($ch, CURLOPT_POST, 1);
      			curl_setopt($ch, CURLOPT_HEADER, 0);
      			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      			$file_contents = curl_exec($ch);
      			curl_close($ch);
			$dataArray = json_decode($file_contents,true);
			//print_r($dataArray);exit;
			if($dataArray["result"]) { return true; } else { return false; }
			//*/
		}
	}
	
	public function getLastVerifyCode() { return isset(Yii::app()->session['verify_code']) ? Yii::app()->session['verify_code'] : ""; }
	public function getLastVerifyTime() { return isset(Yii::app()->session['verify_time']) ? Yii::app()->session['verify_time'] : 0; }
	public function clearVerifyCodeTime() { Yii::app()->session['verify_code'] =  ""; Yii::app()->session['verify_time'] = ""; }
	public function sendVerifyCode($mobile,$beforeMsg,$afterMsg,$typeId="") {
		$timerNum = 120;
		$verifyCode = rand("15622","98906");
		Yii::app()->session['verify_code'] =  $verifyCode;	
		Yii::app()->session['verify_time'] = time(); 	
		return $this->sendMobileMsg($beforeMsg.$verifyCode.$afterMsg,$mobile,
		array("yun_id"=>P2PCommon::getMsgYunid($typeId),"param1"=>$verifyCode,"param2"=>intval($timerNum/60)));
	}

}
