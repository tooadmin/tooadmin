<?php

class SendEmails {

	public function sendEmail($toUserEmail,$subject,$content) {
		include_once './common/plugins/items/message/email/smtp.php';
		$params = TDPlugin::getConfig("TDCEmail");	
		if($params['isUseSMTP']) {
			$smtp = new smtp($params['smtpserver'],$params['smtpserverport'],true,$params['smtpuser'],$params['smtppass']);
			//这里面的一个true是表示使用身份验证,否则不使用身份验证.
			$smtp->debug = false;//是否显示发送的调试信息
			ob_start();
			$res = $smtp->sendmail($toUserEmail,$params['smtpusermail'],$subject,$content,$params['mailtype']);	
			ob_end_clean();
			return $res;
		} else {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			// Additional headers
			//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
			//$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
			//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
			//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
			$headers .= "From:".$params['smtpusermail']."\r\n" ."CC:".$toUserEmail;
			return mail($toUserEmail,$subject,$content,$headers);
		}
	}

}
