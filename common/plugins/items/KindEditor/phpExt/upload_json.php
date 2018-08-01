<?php
/**
 * KindEditor PHP
 * 
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * 
 */
include_once('./JSON.php');
//include_once('../../../config/appConst.php');
//include_once('../../../../lib/tooadmin/util/Xjkut.php');

//文件保存目录路径
$save_path = "../../../../uploadfiles/kindeditor/";
//文件保存目录URL
function getHttpHost() {
//alert("上传文件失败。");//有漏洞暂时关闭上传
$_scriptUrl = "";
$scriptName=basename($_SERVER['SCRIPT_FILENAME']);
if(basename($_SERVER['SCRIPT_NAME'])===$scriptName)
	$_scriptUrl=$_SERVER['SCRIPT_NAME'];
elseif(basename($_SERVER['PHP_SELF'])===$scriptName)
	$_scriptUrl=$_SERVER['PHP_SELF'];
elseif(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME'])===$scriptName)
	$_scriptUrl=$_SERVER['ORIG_SCRIPT_NAME'];
elseif(($pos=strpos($_SERVER['PHP_SELF'],'/'.$scriptName))!==false)
	$_scriptUrl=substr($_SERVER['SCRIPT_NAME'],0,$pos).'/'.$scriptName;
elseif(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT'])===0)
	$_scriptUrl=str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));
$baseUrl = rtrim(dirname($_scriptUrl),'\\/');
$host = 'http://'.$_SERVER['HTTP_HOST'];
if(strpos($host,"localhost") !== false || strpos($host,"127.0.0.1") !== false) {
	$new = substr($baseUrl,0,strpos($baseUrl,"/",2));
	$baseUrl = $new;
} else {
	$baseUrl =  "";
}
return $host.$baseUrl;
}
$save_url = getHttpHost().'/common/uploadfiles/kindeditor/';
//
//定义允许上传的文件扩展名
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf'),
);
//最大文件大小
$max_size = 1024*1024*20;//20M

$save_path = realpath($save_path) . '/';

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("临时文件可能不是上传文件。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	$ymd = date("Ym");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	} else {
		if(in_array($file_ext, $ext_arr["image"]) && isset($_GET["textarea_name"])) {
			$context = array("http" => array("method" => "POST",
			"header" => "Content-type:application/x-www-form-urlencoded",
			"content" =>http_build_query(array("beWatermarkImgPath"=>$file_path,"textarea_name"=>$_GET["textarea_name"])),));
			$str = $_SERVER['HTTP_REFERER'];
			$postToUrl =  getHttpHost()."/admin.php/tDCommonIO/watermark";
			if(strpos($str,"admin.php") === false) {
				$postToUrl =  getHttpHost()."/tDCommonIO/watermark";
			}
			file_get_contents($postToUrl,false,stream_context_create($context));
		}
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;
	///@Xjkut::fileToOSS($file_path);	
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 0, 'url' => $file_url));
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}
?>