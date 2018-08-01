<?php
class FieldFactory {

	public static function getInputBeforeHTML($fieldClassName,$columnHeader='',$readOnly=false) {
		$style = '';
		if($fieldClassName == FieldCollection::$hiddenFieldClass) {
			$style = ' style="display:none;" ';
		}
		$result = '
		<div class="control-group" '.$style.' >
		<label class="control-label" for="appendedInput">'.$columnHeader.'&nbsp;&nbsp;</label>
		<div class="controls"><div class="input-append" '.($readOnly ? ' style="margin-top:5px;" ' : '').'>	';
		//<div class="input-append"> 在控制提示错误信息的时候避免图标向右偏离
		return $result;			
	}

	public static function getInputAfterHTML($fieldID,$errorMsg='',$inputRemark="",$inputBackTxt="") {
		$errorMsg = str_replace('"','',$errorMsg);
		$result = empty($inputBackTxt) ? "" : " ".$inputBackTxt;	
		$result .= '<a data-rel="tooltip" id="'.$fieldID.'_tipmsg" 
		style="display:'.( empty($inputRemark) ? "none" : "block" ).';float:right;" href="#" 
		data-original-title="'.$inputRemark.'"><i class="icon icon-darkgray icon-comment-text"></i></a>'
		.'<a data-rel="tooltip" id="'.$fieldID.'_error" 
		style="display:'.(empty($errorMsg) ? "none" : "block" ).';float:right;" href="#" 
		data-original-title="'.$errorMsg.'"><i class="icon icon-red icon-alert"></i></a>';
		$result .= ' </div></div></div>	';
		return $result;
	}

	public static function getErrorsHTML($errors) {
		$errorsStr = '';
		foreach($errors as $col => $error) {
			if(!empty($errorsStr)) {
				$errorsStr .= "; ";
			}
			$errorsStr .= isset($error['msg']) ? $error['msg'] : '';	
		}
		return $errorsStr;
	}

}
