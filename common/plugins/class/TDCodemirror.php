<?php

class TDCodemirror {
	public static function jscssCode() {
		$mirUrl = Yii::app()->baseUrl."/common/plugins/items/codemirror";
		$html ='<link rel="stylesheet" href="'.$mirUrl.'/lib/codemirror.css">
		<script src="'.$mirUrl.'/lib/codemirror.js"></script>
		<script src="'.$mirUrl.'/mode/css/css.js"></script>';
		echo $html;
	}
}
