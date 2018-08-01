<?php

class TDCommon {

	public static function tipPopupFormMsgToOpen($htmlMsg,$width=0,$height=0) {
		TDSessionData::setPopupTipMsg($htmlMsg);
		$html = '<meta charset="utf-8">';
		$html .= '<script type="text/javascript">';
		$html .= 'parent.window.close();parent.parent.closeWindow();';
		$html .= 'parent.parent.popupWindow("提示","'.TDPathUrl::createUrl("tDTool/tipMsg").'"'.($width>0 && $height > 0 ? ",".$width.','.$height : "").');';
		$html .= '</script>';
		echo $html;
		exit;
	}

	public static function tipPopupFormMsg($msg) {
		$html = '<meta charset="utf-8">';
		$html .= '<script type="text/javascript">';
		if (!empty($msg)) {
			$html .= 'alert("' . $msg . '");';
		}
		$html .= 'parent.window.close();parent.parent.closeWindow();';
		$html .= '</script>';
		echo $html;
		exit;
	}
	
	public static function tipMessage($msg = '', $url = '', $isExit = true) {
		$html = '<meta charset="utf-8">';
		$html .= '<script type="text/javascript">';
		if (!empty($msg)) {
			$html .= 'alert("' . $msg . '");';
		}
		if (!empty($url)) {
			$html .= 'window.location.href="' . $url . '";';
		}
		$html .= '</script>';
		echo $html;
		if ($isExit) {
			exit;
		}
	}

	public static function tipMessageAndReBack($msg = '') {
		$html = '<meta charset="utf-8">';
		$html .= '<script type="text/javascript">';
		if (!empty($msg)) {
			$html .= 'alert("' . $msg . '");';
		}
		$html .= 'window.location.href="' . Yii::app()->request->urlReferrer . '";';
		///$html .= 'window.history.go(-1);';
		$html .= '</script>';
		echo $html;
		exit;
	}

	public static function tipActionNoPermission($controllerName, $actionName) {
		Yii::app()->user->logout();
		echo "the controller = " . $controllerName . "   action = " . $actionName . " no permission ! 请重新登录";
		exit;
	}

	public static function closeWindow($msg = '', $refresh = array(), $reloadParent = false) {
		header('Content-Type:text/html;charset=UTF-8');
		$html = '<script type="text/javascript">';
		if (!empty($msg)) {
			$html .= 'alert("' . $msg . '");';
		}
		if (!empty($refresh)) {
			if (isset($refresh['GridView'])) {
				$tmpRow = $refresh['GridView'];
				for ($i = 0; $i < count($tmpRow); $i++) {
					$html .= 'parent.refashGridView("' . $tmpRow[$i] . '");';
				}
			}
			if (isset($refresh['ListView'])) {
				$tmpRow = $refresh['ListView'];
				for ($i = 0; $i < count($tmpRow); $i++) {
					$html .= 'parent.refashListView("' . $tmpRow[$i] . '");';
				}
			}
		}
		$html .= 'window.close();';
		$html .= 'parent.closeWindow();';
		if ($reloadParent) {
			$html .= 'parent.document.location.reload();';
		}
		$html .= '</script>';
		echo $html;
		exit;
	}

	public static $tmpIncreaseNum = 0;

	public static function getIncreaseNum() {
		if (TDCommon::$tmpIncreaseNum > 100000000) {
			TDCommon::$tmpIncreaseNum = 0;
		}
		return TDCommon::$tmpIncreaseNum++;
	}

	public static function array_smerge($array1, $array2) {
		$newArray = array();
		foreach ($array1 as $key => $value) {
			$newArray[$key] = $value;
		}
		foreach ($array2 as $key => $value) {
			$newArray[$key] = $value;
		}
		return $newArray;
	}

	public static function array_to_str($array, $appendStr = '') {
		$result = '';
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result .= ' ' . self::array_to_str($value, (empty($appendStr) ? $key : $appendStr . "[" . $key . "]"));
			} else {
				$result .= ' ' . (empty($appendStr) ? $key : $appendStr . '[]') . "=" . $value;
			}
		}
		return $result;
	}

	public static function getArrayValuesToString($array, $keyStr = ",") {
		$str = "";
		if (!is_array($array)) {
			$str = $array;
			return $str;
		}
		foreach ($array as $key => $value) {
			if (!empty($str))
				$str .= $keyStr;
			if (is_array($value)) {
				$str .= self::getArrayValuesToString($value, $keyStr);
			} else {
				$str .= $value;
			}
		}
		return $str;
	}

	public static function getStringPart($str, $length, $append = "") {
		$maxLength = mb_strlen($str, "utf-8");
		if ($length >= $maxLength) {
			return $str;
		} else {
			return mb_substr($str, 0, $length - mb_strlen($append, "utf-8"), "utf-8") . $append;
		}
	}

	public static function trimArray($array) {
		foreach ($array as $key => $value) {
			$array[$key] = trim($value);
			if (!is_numeric($array[$key]) && empty($array[$key])) {
				unset($array[$key]);
			}
		}
		$array = array_merge($array);
		return $array;
	}

	public static function afterActionTmpForm($tipMsg, $isFresh = true) {
		echo "<script>parent.AfterActionTmpFormTip('" . $tipMsg . "','" . $isFresh . "')</script>";
	}

	public static function hex2rgb($hexColor) {
		$color = str_replace('#', '', $hexColor);
		if (strlen($color) > 3) {
			$rgb = array('r' => hexdec(substr($color, 0, 2)), 'g' => hexdec(substr($color, 2, 2)), 'b' => hexdec(substr($color, 4, 2)));
		} else {
			$color = str_replace('#', '', $hexColor);
			$r = substr($color, 0, 1) . substr($color, 0, 1);
			$g = substr($color, 1, 1) . substr($color, 1, 1);
			$b = substr($color, 2, 1) . substr($color, 2, 1);
			$rgb = array('r' => hexdec($r), 'g' => hexdec($g), 'b' => hexdec($b));
		}
		return $rgb;
	}

	public static function getClientIp() {
		$ip = "unknown";
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		if (strlen($ip) <= 5) {
			$ip = "127.0.0.1";
		}
		return $ip;
	}

	public static $amountFormat_douhao = "1";

	public static function amountFormat($amount, $type) {
		$amount = round($amount, 2);
		$baseStr = "";
		if ($amount < 0) {
			$baseStr = "-";
			$amount = abs($amount);
		}
		$amount = $amount . "";
		$newAmount = "";
		if ($type == self::$amountFormat_douhao) {
			for ($i = strlen($amount) - 1; $i >= 0; $i--) {
				if (strpos($newAmount, ",") !== false) {
					$tmp = substr($newAmount, 0, strpos($newAmount, ","));
					if (strlen($tmp) == 3) {
						$newAmount = "," . $newAmount;
					}
				} else if (strpos($newAmount, ".") !== false) {
					$tmp = substr($newAmount, 0, strpos($newAmount, "."));
					if (strlen($tmp) == 3) {
						$newAmount = "," . $newAmount;
					}
				} else if (strlen($newAmount) == 3) {
					$newAmount = "," . $newAmount;
				}
				$newAmount = $amount[$i] . $newAmount;
			}
		}
		return $baseStr . $newAmount;
	}

	public static $outputErrorType_alert = 0;
	public static $outputErrorType_jsonError = 1;

	public static function monitorExceptionError($outputErrorType) {
		if ($outputErrorType == self::$outputErrorType_alert) {

			function runError($error_level, $error_message) {
				TDCommon::tipMessage(mysql_escape_string($error_message));
			}

			function runException($e) {
				TDCommon::tipMessage(mysql_escape_string($e->getMessage()));
			}

			set_error_handler("runError", E_ALL);
			set_exception_handler("runException");
		} else if ($outputErrorType == self::$outputErrorType_jsonError) {

			function runError($error_level, $error_message) {
				echo json_encode(array('ERROR_MSG' => $error_message));
				exit;
			}

			function runException($e) {
				echo json_encode(array('ERROR_MSG' => $e->getMessage()));
				exit;
			}

			set_error_handler("runError", E_ALL);
			set_exception_handler("runException");
		}
	}

	public static function getValueBySQL($sql, $emptyDefault = 0) {
		$tbName = "";
		if(strpos($sql,"from") !== false) {
			$tbName = explode(" ",trim(explode("from ",$sql)[1]))[0];
		} else if(strpos($sql,"FROM") !== false) {
			$tbName = explode(" ",trim(explode("FROM ",$sql)[1]))[0];
		}
		$res = TDModelDAO::getDB($tbName)->createCommand($sql)->queryColumn();
		if (isset($res[0]) || !empty($res[0])) {
			return $res[0];
		} else {
			return $emptyDefault;
		}
	}

	public static function formatMoneyStr($amount) {
		if (empty($amount)) {
			$amount = 0;
		}
		$amount = round($amount, 2);
		$new = TDCommon::amountFormat($amount, TDCommon::$amountFormat_douhao);
		if (strpos($new, ".") === false) {
			$new .=".00";
		} else {
			$tmp = substr($amount . "", strrpos($amount . "", ".") + 1);
			if (strlen($tmp) == 1) {
				$new .= "0";
			}
		}
		return $new;
	}

	public static function formatMunTwoPoint($amount) {
		if (empty($amount)) {
			$amount = 0;
		}
		$new = round($amount, 2);
		if (strpos($new, ".") === false) {
			$new .=".00";
		} else {
			$tmp = substr($amount . "", strrpos($amount . "", ".") + 1);
			if (strlen($tmp) == 1) {
				$new .= "0";
			}
		}
		return $new;
	}

	public static function formatNumerals($num) {
		$d = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
		$e = array('元', '拾', '佰', '仟', '万', '拾万', '佰万', '仟万', '亿', '拾亿', '佰亿', '仟亿', '万亿');
		$p = array('分', '角');
		$zheng = '整'; //追加"整"字
		$final = array(); //结果
		$inwan = 0; //是否有万
		$inyi = 0; //是否有亿
		$len_pointdigit = 0; //小数点后长度
		$y = 0;
		if ($c = strpos($num, '.')) { //有小数点,$c为小数点前有几位数
			$len_pointdigit = strlen($num) - strpos($num, '.') - 1; // 判断小数点后有几位数
			if ($c > 13) { //简单的错误处理
				echo "数额太大,已经超出万亿.";
				die();
			} elseif ($len_pointdigit > 2) { //$len_pointdigit小数点后有几位
				echo "小数点后只支持2位.";
				die();
			}
		} else { //无小数点
			$c = strlen($num);
			$zheng = '整';
		}
		for ($i = 0; $i < $c; $i++) { //处理整数部分
			$bit_num = substr($num, $i, 1); //逐字读取 左->右
			if ($bit_num != 0 || substr($num, $i + 1, 1) != 0) //当前是零 下一位还是零的话 就不显示
				@$low2chinses = $low2chinses . $d[$bit_num];
			if ($bit_num || $i == $c - 1)
				@$low2chinses = $low2chinses . $e[$c - $i - 1];
		}
		for ($j = $len_pointdigit; $j >= 1; $j--) { //处理小数部分
			$point_num = substr($num, strlen($num) - $j, 1); //逐字读取 左->右
			if ($point_num != 0)
				@$low2chinses = $low2chinses . $d[$point_num] . $p[$j - 1];
			//if(substr($num, strlen($num)-2, 1)==0 && substr($num, strlen($num)-1, 1)==0) //小数点后两位都是0
		}
		$chinses = str_split($low2chinses, 2); //字符串转换成数组
		//print_r($chinses);
		for ($x = sizeof($chinses) - 1; $x >= 0; $x--) { //过滤无效的信息
			if ($inwan == 0 && $chinses[$x] == $e[4]) { //过滤重复的"万"
				$final[$y++] = $chinses[$x];
				$inwan = 1;
			}
			if ($inyi == 0 && $chinses[$x] == $e[8]) { //过滤重复的"亿"
				$final[$y++] = $chinses[$x];
				$inyi = 1;
				$inwan = 0;
			}
			if ($chinses[$x] != $e[4] && $chinses[$x] != $e[8]) //进行整理,将最后的值赋予$final数组
				$final[$y++] = $chinses[$x];
		}
		$newstring = (array_reverse($final)); //$final为倒数组，$newstring为正常可以使用的数组
		$nstring = join($newstring); //数组变成字符串
		if (substr($num, -2, 1) == 0 && substr($num, -1) <> 0) { //判断原金额角位为0 ? 分位不为0 ?
			$nstring = substr($nstring, 0, (strlen($nstring) - 4)) . "零" . substr($nstring, -4, 4); //这样加一个零字
		}
		$fen = "分";
		$fj = substr_count($nstring, $fen); //如果没有查到分这个字
		return $nstring = ($fj == 0) ? $nstring . "圆" . $zheng : $nstring . "圆"; //就将"整"加到后面
	}

	public static function formatNumWang($num) {
		if ($num < 100000) {
			return $num;
		}
		$baseNUm = $num;
		$str = "";
		if ($num > 100000000) {
			$enum = intval($num / 100000000);
			$num -= $enum * 100000000;
			$str .= $enum . "亿";
		}
		/*
		  if($num > 10000000) {
		  $enum = intval($num/10000000);
		  $num -= $enum * 10000000;
		  $str .= $enum."仟";
		  }
		  if($num > 1000000) {
		  $enum = intval($num/1000000);
		  $num -= $enum * 1000000;
		  $str .= $enum."佰";
		  }
		 */
		if ($num > 10000) {
			$enum = intval($num / 10000);
			$num -= $enum * 10000;
			$str .= $enum . "万";
		}
		return $str;
	}

	public static function isClassics() {
		return self::getThemeName() == "classics" ? true : false;	
	}
	public static function getThemeName() {
		return "classics";
		return isset(Yii::app()->session["themeName"]) && !empty(Yii::app()->session["themeName"]) ? Yii::app()->session["themeName"] : "default";	
	}
	public static function getRender($view, $controller = null) {
		if (is_file($view . ".php") && strpos($view, "/") !== false) {
			return $view;
		}
		$themeDefaultName = 'default';
		if (strpos($view, "//") !== false) {
			return $view;
		}
		$themeName = self::getThemeName();
		if (empty($themeName)) {
			$themeName = $themeDefaultName;
		}
		$result = '//themes/' . $themeName . '/';
		if (!is_null($controller) && strpos($view, '/') == false) {
			$className = get_class($controller);
			$className = substr($className, 0, strrpos($className, "Controller"));
			$className = strtolower($className);
			$result .= strtolower($className) . '/' . $view;
			if ($themeName != $themeDefaultName && !is_file('common/lib/tooadmin/admin/views/themes/' . $themeName . '/' . strtolower($className) . '/' . $view . '.php')) {
				$result = '//themes/' . $themeDefaultName . '/' . strtolower($className) . '/' . $view;
			}
		} else {
			$result .= $view;
			if ($themeName != $themeDefaultName && !is_file('common/lib/tooadmin/admin/views/themes/' . $themeName . '/' . $view . '.php')) {
				$result = '//themes/' . $themeDefaultName . '/' . $view;
			}
		}
		return $result;
	}

	public static function cnsubstr($str, $length, $start = 0, $charset = "utf-8", $suffix = true) {
		$str = strip_tags($str);
		if (function_exists("mb_substr")) {
			if (mb_strlen($str, $charset) <= $length)
				return $str;
			$slice = mb_substr($str, $start, $length, $charset);
		} else {
			$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re [$charset], $str, $match);
			if (count($match [0]) <= $length)
				return $str;
			$slice = join("", array_slice($match [0], $start, $length));
		}
		if ($suffix)
			return $slice . "…";
		return $slice;
	}

	//输出纯文本
	public static function text($text, $parseBr = false, $nr = false) {
		$text = htmlspecialchars_decode($text);
		$text = TDCommon::safe($text, 'text');
		if (!$parseBr && $nr) {
			$text = str_ireplace(array("\r", "\n", "\t", "&nbsp;"), '', $text);
			$text = htmlspecialchars($text, ENT_QUOTES);
		} elseif (!$nr) {
			$text = htmlspecialchars($text, ENT_QUOTES);
		} else {
			$text = htmlspecialchars($text, ENT_QUOTES);
			$text = nl2br($text);
		}
		$text = trim($text);
		return $text;
	}

	public static function safe($text, $type = 'html', $tagsMethod = true, $attrMethod = true, $xssAuto = 1, $tags = array(), $attr = array(), $tagsBlack = array(), $attrBlack = array()) {
		//无标签格式
		$text_tags = '';
		//只存在字体样式
		$font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
		//标题摘要基本格式
		$base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
		//兼容Form格式
		$form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
		//内容等允许HTML的格式
		$html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed>';
		//专题等全HTML格式
		$all_tags = $form_tags . $html_tags . '<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
		//过滤标签
		$text = strip_tags($text, ${$type . '_tags'});
		//过滤攻击代码
		if ($type != 'all') {
			//过滤危险的属性，如：过滤on事件lang js
			while (preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
				$text = str_ireplace($mat [0], $mat [1] . $mat [3], $text);
			}
			while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
				$text = str_ireplace($mat [0], $mat [1] . $mat [3], $text);
			}
		}
		return $text;
	}

	public static function getDateStr($format, $time) {
		if (empty($time)) {
			return "";
		} else {
			return date($format, $time);
		}
	}

	public static function hidecard($cardnum, $type = 1, $default = "") {
		if (empty($cardnum))
			return $default;
		$len = 1;
		if ($type == 1)
			$cardnum = substr($cardnum, 0, 3) . str_repeat("*", 12) . substr($cardnum, strlen($cardnum) - 4); //身份证
		elseif ($type == 2)
			$cardnum = substr($cardnum, 0, 3) . str_repeat("*", 5) . substr($cardnum, strlen($cardnum) - 4); //手机号
		elseif ($type == 3)
			$cardnum = str_repeat("*", strlen($cardnum) - 4) . substr($cardnum, strlen($cardnum) - 4); //银行卡
		elseif ($type == 4) {
			$cardnum = TDCommon::cnsubstr($cardnum, $len, 0, 'utf-8', false) . str_repeat("*", strlen($cardnum) - 3); //用户名
		} elseif ($type == 8) {
			$pos = strpos($cardnum, "@qq_");
			$pos1 = strpos($cardnum, "@sina_");
			if ($pos === 0) {
				$cardnum = "@qq_" . TDCommon::cnsubstr($cardnum, $len, 4, 'utf-8', false) . str_repeat("*", 3);
			} else if ($pos1 === 0) {
				$cardnum = "@sina_" . TDCommon::cnsubstr($cardnum, $len, 6, 'utf-8', false) . str_repeat("*", 3);
			} else {
				$cardnum = TDCommon::cnsubstr($cardnum, $len, 0, 'utf-8', false) . str_repeat("*", 3); //用户名
			}
		}
		return $cardnum;
	}

	// 自动转换字符集 支持数组转换
	public static function auto_charset($fContents, $from = 'gbk', $to = 'utf-8') {
		$from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
		$to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
		if (($to == 'utf-8' && TDCommon::is_utf8($fContents)) || strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
			//如果编码相同或者非字符串标量则不转换
			return $fContents;
		}
		if (is_string($fContents)) {
			if (function_exists('mb_convert_encoding')) {
				return mb_convert_encoding($fContents, $to, $from);
			} elseif (function_exists('iconv')) {
				return iconv($from, $to, $fContents);
			} else {
				return $fContents;
			}
		} elseif (is_array($fContents)) {
			foreach ($fContents as $key => $val) {
				$_key = TDCommon::auto_charset($key, $from, $to);
				$fContents [$_key] = TDCommon::auto_charset($val, $from, $to);
				if ($key != $_key)
					unset($fContents [$key]);
			}
			return $fContents;
		} else {
			return $fContents;
		}
	}

	//判断是否utf8
	public static function is_utf8($string) {
		return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   	| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   	|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   	| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   	|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   	|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   	| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   	|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   		)*$%xs', $string);
	}

	//防止SQL语句注入处理(暂时前台网站用)
	public static function requestSafeSQL() {
		if (isset($_GET)) {
			foreach ($_GET as $k => $v) {
				if (!is_array($v)) {
					$_GET[addslashes($k)] = addslashes($v);
				}
			}
		}
		if (isset($_POST)) {
			foreach ($_POST as $k => $v) {
				if (!is_array($v)) {
					$_POST[addslashes($k)] = addslashes($v);
				}
			}
		}
	}

	public static function formatBstr() {
		if (isset($_GET["Bstr"]) && isset($_GET["Btype"])) {
			if ($_GET["Btype"] == "img") {
				echo '<img src="' . $_GET["Bstr"] . '">';
			}
		}
	}

	public static function unUseKey() {
		return true;
	}

	public static function mkdir($path, $runNum = 0) {
		if ($runNum >= 10) {//避免死循环
			return;
		}
		$dir = substr($path, 0, strrpos($path, "/"));
		if (!is_dir($dir) && !empty($dir)) {
			$dirParent = substr($dir, 0, strrpos($dir, "/"));
			if (is_dir($dirParent)) {
				mkdir($dir);
			} else {
				$runNum++;
				self::mkdir($dir, $runNum);
				mkdir($dir);
			}
		}
	}

	/** Json数据格式化 
	 * @param  Mixed  $data   数据 
	 * @param  String $indent 缩进字符，默认4个空格 
	 * @return JSON 
	 */
	public static function jsonFormat($data, $indent = null) {

		//return $data."=====";
		//$data = json_decode($data);

		//return json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
		
		// 对数组中每个元素递归进行urlencode操作，保护中文字符  
		//array_walk_recursive($data, 'jsonFormatProtect');

		// json encode  
		//$data = json_encode($data);

		// 将urlencode的内容进行urldecode  
		//$data = urldecode($data);

		// 缩进处理  
		$ret = '';
		$pos = 0;
		$length = strlen($data);
		$indent = isset($indent) ? $indent : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$newline = "<br/>";
		$prevchar = '';
		$outofquotes = true;

		for ($i = 0; $i <= $length; $i++) {

			$char = substr($data, $i, 1);

			if ($char == '"' && $prevchar != '\\') {
				$outofquotes = !$outofquotes;
			} elseif (($char == '}' || $char == ']') && $outofquotes) {
				$ret .= $newline;
				$pos --;
				for ($j = 0; $j < $pos; $j++) {
					$ret .= $indent;
				}
			}

			$ret .= $char;

			if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
				$ret .= $newline;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$ret .= $indent;
				}
			}

			$prevchar = $char;
		}

		return $ret;
	}

	 /**
     *
     * CURL POST 数据
     * @param string $url
     * @param array $data
     * @param array $timeOut 超时时间 (秒)
     * @return array
     */
    public static function curlPost($url, $data, $timeOut = false) {
        $postData = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $_k => $_v) {
                    $postData [] = $k . '[' . $_k . ']=' . $_v;
                }
            } else {
                $postData [] = $k . '=' . $v;
            }
        }
        $postData = implode('&', $postData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($timeOut !== false) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        }
        //指定post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        //添加变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        if ($result === false) { //出错则显示错误信息
            throw new Exception('curl 返回错误：' . curl_error($ch)); //输出错误 $msg
        }
        curl_close($ch);

        return $result;
    }

	public static function toRunEval($phpCode) {
	 		eval($phpCode);
	}
}
