<?php return array(
    	'usemodel' => 'yuntongxun',//选用哪个短信接口 
    	'gysoft' => array(
	    	"path" => '/common/plugins/items/message/mobilemsg/gysoft/sendmsg.php',
		"uid" => '',
		"pwd"  => '',),
	'yuntongxun' => array(
	    	"path" => '/common/plugins/items/message/mobilemsg/yuntongxun/sendmsg.php',
	    	"accountSid" => "",
		"accountToken" => "", 
		"appId" => "", 
		"serverIP" => "",   	    
		"serverPort" => "",   	    
		"softVersion" => "", ),    		
	'chaotang' => array(
	    	"path" => "/common/plugins/items/message/mobilemsg/chaotang/esm.php",
    		"ssUrl" => "http://esm.chaotang.com/webservice/ESMSendService.asmx?WSDL",
		"sscomCode" => "",
		"ssloginName" => "",
		"sspswd" => "",),
    	'jixintong' => array(
	    	"path" => "/common/plugins/items/message/mobilemsg/jixintong/sendmsg.php",
	    	"uid" => "",
	    	"pwd" => "",
	),
); 