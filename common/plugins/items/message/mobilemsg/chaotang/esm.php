<?php


//include_once C ( 'APP_ROOT' ) . 'Lib/Util/sms/include/lib/nusoap.php';
include_once 'lib/nusoap.php';

class ESM{
	
	//服务地址
	var $url;
	//企业代码
	var $comCode;
	//用户名
	var $loginName;
	//密码
	var $pswd;
	
	//webservice客户端
	var $soap;
	
	//* @param string $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
	//* @param string $proxyport		可选，代理服务器端口，默认为 false
	//* @param string $proxyusername	可选，代理服务器用户名，默认为 false
	//* @param string $proxypassword	可选，代理服务器密码，默认为 false
	//* @param string $timeout		连接超时时间，默认0，为不超时
	//* @param string $response_timeout		信息返回超时时间，默认30
	
	//** 连接超时时间，单位为秒
	var $connectTimeOut ;
	//** 远程信息读取超时时间，单位为秒
	var $readTimeOut ;
	
	
	/**
	 * 往外发送的内容的编码,默认为 GBK
	 */
	var $outgoingEncoding = "GBK";
	
	/**
	 * 往外发送的内容的编码,默认为 GBK
	 */
	var $incomingEncoding = 'GBK';



	
	
	function ESM($url,$comCode,$loginName,$pswd,$proxyhost = false,$proxyport = false,$proxyusername = false, $proxypassword = false, $timeout = 2, $response_timeout = 30)
	{
		$this->url=$url;
		$this->comCode=$comCode;
		$this->loginName=$loginName;
		$this->pswd=$pswd;
			
		$this->soap = new nusoap_client($url, 'wsdl',$proxyhost,$proxyport,$proxyusername,$proxypassword,$timeout,$response_timeout); 
		$err = $this->soap->getError();
		if ($err) {
			echo '<h2>连接错误:</h2><pre>' . $err . '</pre>';
		}
		

	}


	/**
	 * 查询短信剩余数
	 * @return int
	 */
	function GetCompanyESMCount()
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd);
		$result = $this->soap->call("GetCompanyESMCount",$params);
		return $result[GetCompanyESMCountResult];		
	}


	/**
	 * 短信发送  (单条发送)
	 * $smob 手机号码 
	 * $smsg 短息内容
	 */
	function SendMsg($smob,$smsg)
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd , 'mob' => $smob , 'msg'=> $smsg);
		$result = $this->soap->call("SendMsg",$params);
		///print_r($params); print_r($result); exit;
		return isset($result["SendMsgResult"]) ?  true : false;		
	}
	
	/**
	 * 定时短信发送  (单条发送)
	 * $smob 手机号码 
	 * $smsg 短息内容
	 * $sdt 发送时间 
	 */
	function SendPlanMsg($smob,$smsg,$sdt)
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd , 'mob' => $smob , 'msg'=> $smsg , 'dt'=>$sdt);
		$result = $this->soap->call("SendPlanMsg",$params);
		//print_r($result);
		//exit;
		return $result[SendPlanMsgResult];
	}
	
	/**
	 * 批量即时短信
	 * $smobs 手机号码 
	 * $smsg 短息内容
	 */
	function BatchSendMsg($smobs,$smsg)
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd , 'mobs' => $smobs , 'msg'=> $smsg);
		$result = $this->soap->call("BatchSendMsg",$params);
		//print_r($result);
		//exit;
		return $result[BatchSendMsgResult];
	}

	/**
	 * 得到回复

	 */
	function GetReply()
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd );
		$result = $this->soap->call("GetReply",$params);
		//print_r($result);
		//exit;
		return $result[BatchSendMsgResult];
	}	
	
	/**
	 * 批量即时短信
	 * $smobs 手机号码 
	 * $smsg 短息内容
	 */
	function BatchSendPlanMsg($smobs,$smsg,$dt)
	{
		$params=array('comCode' => $this->comCode, 'loginName' => $this->loginName, 'pswd' => $this->pswd , 'mobs' => $smobs , 'msg'=> $smsg);
		$result = $this->soap->call("BatchSendPlanMsg",$params);
		//print_r($result);
		//exit;
		return $result[BatchSendPlanMsgResult];
	}
	
	
	function getError()
	{		
		return $this->soap->getError();
	}

	
}
