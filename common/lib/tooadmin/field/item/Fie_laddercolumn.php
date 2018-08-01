<?php
class Fie_laddercolumn extends TDField{

	public static function getColumnLabelStr($ladderColumn) {
		if(empty($ladderColumn)) { return ''; }
		$result = '';
		$tmpArray = explode(",",$ladderColumn);	
		foreach($tmpArray as $item) {
			if(!empty($result)) {
				$result .= '->';
			}
			$result .= TDTableColumn::getColumnLabelName($item);
		}
		return $result;
	}

	public static function getColumnNameStr($ladderColumn) {
		if(empty($ladderColumn)) { return ''; }
		$result = '';
		$tmpArray = explode(",",$ladderColumn);	
		foreach($tmpArray as $item) {
			if(!empty($result)) {
				$result .= TDStaticDefined::$foreignKey_tableName.TDTableColumn::getColumnTableDBName($item);
				$result .= '->';
			}
			$result .= TDTableColumn::getColumnDBName($item);
		}
		return $result;
	}

	public static function getLadderColumnLastColumnId($ladderColumn) {
		if(empty($ladderColumn)) { return 0; }
		$idsArray = explode(",",$ladderColumn);
		$idsArray = TDCommon::trimArray($idsArray);
		return $idsArray[count($idsArray)-1];
	}
	
	public static function getConditionSQLByForeignPrimaryKey($ladderColumn,$foreignPrimaryKey) {
		$result = "";
		$items = explode(",",$ladderColumn);
		if(count($items) == 1) {
			$result = "`t`.`".TDTableColumn::getColumnDBName($ladderColumn)."`=".$foreignPrimaryKey;	
		} else {
			$ids = array();
			if(!empty($foreignPrimaryKey)) {
				for($i=  count($items)-1; $i>0; $i--) {
					$itemColName = TDTableColumn::getColumnDBName($items[$i]); 
					$itemTBName = TDTableColumn::getColumnTableDBName($items[$i]);
					$primaryKey = TDTable::getTableObj($itemTBName)->primaryKey;
					if($i == count($items) - 1) {
						$rows = TDModelDAO::queryAll($itemTBName,"`".$itemColName."`=".$foreignPrimaryKey, "`".$primaryKey."`");
					} else {
						$rows = TDModelDAO::queryAll($itemTBName,"`".$itemColName."` in (".(empty($ids) ? "-1" : implode(",",$ids)).")","`".$primaryKey."`");
					}
					$ids = array();
					foreach($rows as $row) {
						$ids[] = $row[$primaryKey];
					}
				}	
			}
			$result = "`t`.`".TDTableColumn::getColumnDBName($items[0])."` in (".(empty($ids) ? "-1" : implode(",",$ids)).")";	
		}
		return $result;	
	}
	
	public function editForm($params) {
		$columnFormData = $params['columnFormData'];
		$textFieldId = $columnFormData['id']."_ladderText";
		$result = CHtml::textField($columnFormData['name'],$columnFormData['value'],array('id'=>$columnFormData['id'],'style'=>'display:none;'));
		$result .= '<input type="text" id="'.$textFieldId.'" value="'
		.Fie_laddercolumn::getColumnLabelStr($columnFormData['value']).'" readonly="readonly" />';
		$defaultTBId = 0;
		if(!empty($params["model"]) && $params["model"]->tableName == TDTable::$too_table_column) {
		$defaultTBId = TDField::getValueFromForm(TDTable::$too_table_column,'table_collection_id',$params["model"],$params["belongOrderColumnIds"]);
			if(empty($defaultTBId)) { $defaultTBId = 0; }	
		}
		$result .= '<button type="button" class="btn" onclick="ladderColumnChoose(\''.$defaultTBId.'\',\''
		.TDStaticDefined::$ladderColumn_FieldColumnId.'\',\''.$columnFormData['id']
		.'\',\''.TDStaticDefined::$ladderColumn_FieldTextId.'\',\''.$textFieldId.'\',\''.TDStaticDefined::$OPERATE_TYPE_KEY.'\',
		\''.TDStaticDefined::$OPERATE_TYPE_POPUP_LADDER_COLUMN.'\')" ><li class="icon-search"></li>'.TDLanguage::$form_foreign_search.'</button>';
		$result .= '<button type="button" class="btn" onclick="ladderColumnChooseCancel(\''.$columnFormData['id'].'\',\''.$textFieldId.'\')">'
		.TDLanguage::$form_foreign_search_cancel.'</button>';	
		return $result;	
	}

	public function gridView($params) {
		$columnData = $params["columnData"];
		$result = '!empty('.$columnData['value'].') ? Fie_laddercolumn::getColumnLabelStr('.$columnData['value'].') : ""';
		return $result;
	}

	public function viewData($params) {
		$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
		$result = array(
			'name' => $columnData['label'],
			'type' => 'raw',
			'value' => !empty($columnData['value']) ? Fie_laddercolumn::getColumnLabelStr($columnData['value']) : "",
		);
		return $result;	
	}
	public function viewHtml($params) {
		return !empty($params['value']) ? Fie_laddercolumn::getColumnLabelStr($params['value']) : "";
	}

	public function saveData($params) {
		$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
		if(!is_null($value)) {
			TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
		}	
	}

	public function search($params) {
				
	}

	public function editTableColumn($params) {
		$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
		."\" style=\"width:50px;margin-bottom: 0px;\" timeajax=\"1\" />";
		if(strpos($params['dbType'],"varchar") !== false || strpos($params['dbType'],"text") !== false) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:160px;margin-bottom: 0px;\" timeajax=\"1\" />";
		}
		return $result;
	}
}
