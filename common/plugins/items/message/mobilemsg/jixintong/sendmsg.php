<?php
header("Content-Type: text/html;charset=utf-8");

$uid = $_REQUEST["uid"];		//用户账号
$pwd = $_REQUEST["pwd"];		//密码
$mobile	 = $_REQUEST["mobile"];	//号码
$content = $_REQUEST["content"];  //内容
/*
$uid = $_POST["uid"];		//用户账号
$pwd = $_POST["pwd"];		//密码
$mobile	 = $_POST["mobile"];	//号码
$content = $_POST["content"];  //内容
 * 
 */
//即时发送
$res = sendSMS($uid,$pwd,$mobile,$content);
echo json_encode($res);
function sendSMS($uid,$pwd,$mobile,$content)
{
	$result = false;
	 //$mobile 发送号码用逗号分隔
	if(PATH_SEPARATOR==':'){//如果是Linux系统，则执行linux短息接口
      		//$url="http://service.winic.org:8009/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=";
      		$url="http://service.winic.org/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=";
      		$id = urlencode($uid);
      		$pwd = urlencode($pwd);
      		$to = urlencode($mobile);    
      		$content = iconv("UTF-8","GB2312",$content); 
		$rurl = sprintf($url, $id, $pwd, $to, $content);
      		$ch = curl_init();
      		curl_setopt($ch, CURLOPT_POST, 1);
      		curl_setopt($ch, CURLOPT_HEADER, 0);
      		curl_setopt($ch, CURLOPT_URL,$rurl);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      		$result = curl_exec($ch);
      		curl_close($ch);
      		$status = substr($result, 0,3);
      		if($status==="000"){ $result = true; }
      } else {
      		$content=urlencode(auto_charset($content,"utf-8",'gbk'));  //短信内容
      		//$sendurl="http://service.winic.org:8009/sys_port/gateway/?";
      		$sendurl="http://service.winic.org/sys_port/gateway/?";
      		$sdata="id=".$uid."&pwd=".$pwd."&to=".$mobile."&content=".$content."&time=";
      		$xhr=new COM("MSXML2.XMLHTTP");   
      		$xhr->open("POST",$sendurl,false);
      		$xhr->setRequestHeader ("Content-type:","text/xml;charset=GB2312");
      		$xhr->setRequestHeader ("Content-Type","application/x-www-form-urlencoded");
      		$xhr->send($sdata);   
      		$data = explode("/",$xhr->responseText);
		if($data[0]=="000") { $result = true; }
	}
	//$teset = 'uid='.$uid.' pwd='.$pwd.' mobile='.$mobile.'  conetent='.$content;
	if($result){
      		return array("result"=>true,"msg"=>"发送成功!");
      	} else {
      		return array("result"=>false,"msg"=>"发送失败!");
      	}
}
?>