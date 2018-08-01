<?php
class TDFieldColumn {

	public $tableColumnId;
	public $belongOrderColumnIds; //not include tableColumnId
	public $baseFieldName;
	public $baseFieldId; 

	public static function createBuyFieldName($fieldName) {
		$belongOrderStr = null;
		$baseFieldName = $fieldName;
		$baseFieldId = str_replace(TDStaticDefined::$formFieldName,TDStaticDefined::$formFieldID,$baseFieldName);
		$fieldName = str_replace(TDStaticDefined::$formFieldName,"",$fieldName);
		$tmpStr = explode(TDStaticDefined::$formFieldColumnBelongToOrder,$fieldName);
		for($i=0; $i<count($tmpStr)-1; $i++) {
			if(!empty($belongOrderStr)) { 
				$belongOrderStr .= ',';
			}
			$belongOrderStr .= $tmpStr[$i];
		}
		$fieColumn = new TDFieldColumn();
		$fieColumn->tableColumnId = $tmpStr[count($tmpStr)-1];
		$fieColumn->belongOrderColumnIds = $belongOrderStr;
		$fieColumn->baseFieldName = $baseFieldName;
		$fieColumn->baseFieldId = $baseFieldId;
		return $fieColumn;
	}
	
}
