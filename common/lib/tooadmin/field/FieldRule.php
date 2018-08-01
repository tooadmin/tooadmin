<?php

class FieldRule {

	public static function formatParamStrToArray($paramStr) {
		$result = array();	
		if(!empty($paramStr)) {
			$items = explode("],[",$paramStr);
			for($i=0; $i<count($items); $i++) {
				$tmpStr = $items[$i];
				if($i == 0 || $i == count($items) - 1) {
					$tmpStr = str_replace("[","", $tmpStr);
					$tmpStr = str_replace("]","", $tmpStr);
				}
				$tmpArray = explode("=",$tmpStr);
				if(isset($tmpArray[1]) && (is_numeric($tmpArray[0]) || !empty($tmpArray[0]))) {
					$result[$tmpArray[0]] = $tmpArray[1];	
				}
			}
		}
		return $result;
	}
	
	private static function getValuesFromArray($array,$currentValue) {
		$result = '';
		if(empty($array)) {
			$result = $currentValue;	
		} else {
			if(!is_numeric($currentValue) && empty($currentValue)) {
				return '';
			} else {
				$itmes = explode(",",$currentValue);
				foreach($itmes as $item) {
					if(isset($array[$item])) {
						if(!empty($result)) $result .= ' ';	
						$result .= $array[$item];
					}
				}
			}
		}
		return $result;	
	}
	
	public static function getStaticArray($tableColumnId,$model=null,$isForEdit=false) {
		$columnModel = TDTableColumn::getColumnStaticData($tableColumnId);
		$static_array = array();
		if(empty($columnModel["static_array"])) {
			if($columnModel["table_column_input_id"] == Fie_pid::getInputTypeId() && !empty($columnModel["pid_view_columnid"])) {
				$prows = TDModelDAO::getModel(TDTableColumn::getColumnTableDBName($tableColumnId))->findAll('`'.$columnModel["name"].'`=0');
				foreach($prows as $prow) {
					$static_array[$prow->primaryKey] = TDFormat::getModelAppendColumnValue($prow,Fie_laddercolumn::getColumnNameStr($columnModel["pid_view_columnid"]));
				}
			}
		} else {
			//print_r($columnModel); exit;
			$staticStr = $isForEdit && isset($columnModel["edit_static_array"]) && !empty($columnModel["edit_static_array"]) ? $columnModel["edit_static_array"] : $columnModel["static_array"]; 
			$static_array = self::formatParamStrToArray(Fie_formula::getValue($model,$staticStr));
		}
		return $static_array;
	}

	public static function getValuesFromStaticArray($tableColumnId,$currentValue,$model=null) {
		$staticArray = self::getStaticArray($tableColumnId,$model);
		return self::getValuesFromArray($staticArray,$currentValue);
	}

	public static function getDBArray($tableColumnId,$useCondition = true,$groupKeyEquValue = '',$editModel=null) {
		$result = array();
		$columnModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId);
		if(!empty($columnModel["map_table_collection_id"])) { 
			$conditionStr = '';
			$condCriteria = null;
			if($useCondition) {
				$map_condition = '';
				if(!empty($columnModel["map_condition"]) && !empty($editModel)) { $map_condition  = Fie_formula::getValue($editModel,$columnModel["map_condition"]); }
				if((is_numeric($groupKeyEquValue) || !empty($groupKeyEquValue)) && !empty($columnModel["optgroup_laddercolumn"])) {
					$condiArray = explode(",",$columnModel["optgroup_laddercolumn"]);
					$condiArray = TDCommon::trimArray($condiArray);
					$conColumn = TDTableColumn::getColumnDBName($condiArray[count($condiArray)-1]);
					if(count($condiArray) > 1) {
						$joinStr = '';
						$lastAsTb = 't';
						for($i=0; $i<count($condiArray)-1; $i++) {
							$tmpCol = $condiArray[$i];
							$tcol = TDTableColumn::getColumnDBName($tmpCol);
							$tTB = TDTableColumn::getColumnForeignTableName($tmpCol);
							$tmpColId = TDTableColumn::getColumnForeignColumnId($tmpCol);	
							$foreignCol = TDTableColumn::getColumnDBName($tmpColId);
							$asName = $tcol.TDStaticDefined::$foreignKey_tableName.$tTB; 
							$joinStr .= ' inner join `'.$tTB.'` as `'.$asName.'` on `'.$lastAsTb.'`.`'.$tcol.'` = `'.$asName.'`.`'.$foreignCol.'` ';
							$lastAsTb = $asName;
						}
						$joinStr .= ' and `'.$lastAsTb.'`.`'.$conColumn.'`=\''.$groupKeyEquValue.'\' ';
						$condCriteria = new CDbCriteria;	
						$condCriteria->join = $joinStr;
						if(!empty($map_condition)) { $condCriteria->addCondition($map_condition); }
					} else {
						$conditionStr .= '`t`.`'.$conColumn.'`=\''.$groupKeyEquValue.'\' ';
						if(!empty($map_condition)) { $conditionStr .= ' and '.$map_condition;}
					}
				} else {
					$conditionStr = $map_condition;	
				}
			}
			$valueColumnAppStr = TDTableColumn::getLadderColumnAppendStr($columnModel["value_laddercolumn"]);
			$appendValueColumnAppStr = !empty($columnModel["append_laddercolumn"]) ? TDTableColumn::getLadderColumnAppendStr($columnModel["append_laddercolumn"]) : "";
			$rows = TDModelDAO::getModel(TDTableColumn::getTableDBName($columnModel["map_table_collection_id"]))->findAll(is_null($condCriteria) ? $conditionStr : $condCriteria);
			foreach($rows as $row) {
				$mapValue = TDFormat::getModelAppendColumnValue($row,$valueColumnAppStr,true);
				if(!empty($appendValueColumnAppStr)) {
					$mapValue .= " ".TDFormat::getModelAppendColumnValue($row,$appendValueColumnAppStr,true);
				}
				$result[$row->primaryKey] = $mapValue; 
			}
		}
		return $result;
	}

	public static function getOPTGroupDBArray($tableColumnId,$model) {
		$result = array();
		$groupColumnId = Fie_laddercolumn::getLadderColumnLastColumnId(TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $tableColumnId, "optgroup_laddercolumn"));
		if(!empty($groupColumnId)) {
			$staticArray = self::getStaticArray($groupColumnId);
			foreach($staticArray as $seKey => $seValue) {
				$result[] = array(
					'optgroup_key' => $seKey,
					'optgroup_value' => $seValue,
					'optgroup_items' => self::getDBArray($tableColumnId,true,$seKey,$model) 
				);
			}
		}
		return $result;
	}

	public static function getPidParam($tableColumnId) {
		$result = array(
			'id' => 'id',
			'name' => 'name',
		);
		$pid_view_columnid = TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $tableColumnId,"pid_view_columnid");
		if(!empty($pid_view_columnid)) {
			$result['id'] = TDTable::getTableObj(TDTableColumn::getColumnTableDBName($tableColumnId))->primaryKey;
			$result['name'] = TDTableColumn::getColumnDBName(Fie_laddercolumn::getLadderColumnLastColumnId($pid_view_columnid));
		}
		return $result;
	}

	public static function getChangeEvent($tableColumnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$tableColumnId,'`change`'); }
	public static function getEncrypt($tableColumnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$tableColumnId,'`encrypt`'); } 
	public static function getEditorParam($tableColumnId) { $model = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId,'`width`,`height`'); return array('width'=>$model["width"],'height'=>$model["height"]); }
	
	public static function getColumnRule($tableColumnId) {
		$result = array();
		$rule = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId);
		if(empty($rule)) {
			return $result;
		}
		$result[] = $rule["name"];
		if(strpos($rule["db_type"],"int") !== false 
		|| strpos($rule["db_type"],"float") !== false 
		|| strpos($rule["db_type"],"double") !== false 
		|| strpos($rule["db_type"],"decimal") !== false) {
			$result[] = 'numerical';	
		}
		if(!empty($rule["min_value"])) {
			$result['min'] = $rule["min_value"]; 	
		}
		if(!empty($rule["max_value"])) {
			$result['max'] = $rule["max_value"]; 	
		}

		if(strpos($rule["db_type"],"varchar") !== false || strpos($rule["db_type"],"text") !== false) {
			if(!empty($rule["file_types"])) {
				$result[] = 'file';
				$result['allowEmpty'] = true;
				$result['types'] = $rule["file_types"];
			}
			if(!empty($rule["file_max_size"])) {
				$result['maxSize'] = 1024 * $rule["file_max_size"];//kB
			}
			if(!empty($rule["file_too_large"])) {
				$result['tooLarge'] = $rule["file_too_large"];
			}
		}
		//if($rule->in_form_notnull && $rule->allow_empty == 1) {
		if(($rule["in_form_notnull"] || $rule["allow_empty"] == 0) && $rule["default_value"] != "Empty String") {
			if(is_null(TDField::getFormPostData(TDField::createFieldIdOrName($rule["id"],null,true)))) {
				if(isset($_POST[TDStaticDefined::$formModelName]) && is_array($_POST[TDStaticDefined::$formModelName])) {
					$keys = array_keys($_POST[TDStaticDefined::$formModelName]);
					if(!empty($keys) && is_array($keys)) {
						foreach($keys as $k) {
							if(preg_match("/\w".TDStaticDefined::$formFieldColumnBelongToOrder.$rule["id"]."$/",$k)) {
								$result = array();
								$result[] = $rule["name"];
								$result[]= 'required';
								break;
							}
						}
					}
				}	
			} else {
				$result = array();
				$result[] = $rule["name"];
				$result[]= 'required';
			}
		}
		if(count($result) > 1) {
			$result = array($result);
		} else {
			$result = array();	
		}
		return $result;

	}	

	//foreignkey
	public static function getModuleId($tableColumnId) { return TDModelDAO::queryScalarByPk(TDTable::$too_table_column, $tableColumnId, '`module_id`'); }

	public static function getOrderPidColumnName($tableColumnId) { $result = ''; $code = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$tableColumnId,"order_group_laddercolumn"); 
	if(!empty($code)) { $result = Fie_laddercolumn::getColumnNameStr($code); } return $result; }
	
}
