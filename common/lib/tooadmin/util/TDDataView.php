<?php

class TDDataView {
	//多表中的某个字段内容显示
	public static function getViewColumnDatas($foreginColumnId,$foreginParamKey,$viewColumnId,$expandHtml="<br/>") {
		$tableName = TDTableColumn::getColumnTableDBName($foreginColumnId);	
		$foreginColumnStr = TDTableColumn::getColumnDBName($foreginColumnId);
		$rows = TDModelDAO::getModel($tableName)->findAll("`".$foreginColumnStr."`='".$foreginParamKey."'");
		$html = '';
		foreach($rows as $row) {
			$html .= !empty($html) ? $expandHtml : ""; 
			$html .= TDField::gettValueByFormatView($row,$viewColumnId);
		}
		return $html;	
	}
}
