<?php

class TDSearch {

	private static $dbType_unuse =  array('tinyblob','blob','mediumblob','longblob','binary','varbinary','varbinary','point'
	,'linestring','polygon','geometry','multipoint','multilinestring','multipolygon','geometrycollection');
	private static $dbType_eqonly = array('bit','char');
	private static $dbType_round = array('tinyint','smallint','mediumint','int','integer','bigint','real','double','float'
	,'decimal','numeric','date','time','year','timestamp','datetime','createtime','updatetime'); 
	private static $dbType_string =  array('varchar','tinytext','text','mediumtext','longtext','enum','set');
	
	public static $condion_type_eq = 'eq'; 
	public static $condion_type_gre = 'gre'; 
	public static $condion_type_les = 'les'; 
	public static $condion_type_greeq = 'greeq'; 
	public static $condion_type_leseq = 'leseq'; 
	public static $condion_type_noteq = 'noteq'; 

	public static $condion_type_LeftRightLike = 'lrlike'; 
	public static $condion_type_LeftLike = 'llike'; 
	public static $condion_type_RightLike = 'rlike'; 

	public static $condion_type_include = 'include'; 
	public static $condion_type_NotInclude = 'notinclude'; 

	public static $field_value_is_null = 'nullkey';

	public static $checkbox_name_key = '(_!-';
	public static $expand_tree_str_key_str = '-*-';
	public static $expand_tree_column_key_column = ';-;';
	public static $foreignKey_tableName = '___';
	
	public static function getAllowToSearchColumns($tableName) {
		$table = TDTable::getTableObj($tableName,true);
		$result = array();
		$pidChangeToColumn = null;
		$pidLableName = "";
		$unUseSearch = array();
		$tableId = TDTableColumn::getTableCollectionID($tableName);
		$unSearchColumnRows = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".$tableId
		." and (gridview_query_show=0 or (gridview_query_show=1 and column_type=1 and (for_query_join_sql is null or for_query_join_sql='') and (for_query_condition_sql is null or for_query_condition_sql='')) )","name");
		foreach($unSearchColumnRows as $unSr) {
			$unUseSearch[] = $unSr["name"];
		}
		foreach($table->columns as $column) {
			if(in_array($column->dbType,self::$dbType_unuse)) 
				continue;
			if(in_array($column->name,$unUseSearch)) 
				continue;
			$columnId = TDTableColumn::getColumnIdByTableAndColumnName($tableName,$column->name); 
			if(empty($columnId))
				continue;
			$inputType = TDTableColumn::getInputTypeByColumnId($columnId,false);
			if($inputType == "pid") {
				$pidViewColu = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$columnId,"pid_view_columnid");	
				if(empty($pidViewColu)) {
					continue;
				}
				$pidViewColuName = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$pidViewColu,"name");
				if(!isset($table->columns[$pidViewColuName])) {
					continue;
				}
				$pidChangeToColumn = $table->columns[$pidViewColuName];	
				$pidLableName = $pidViewColuName;
			}
			if(in_array($inputType,FieldCollection::$SEARCH_NOT_ALLOWED)) 
				continue;
			$result[$column->name] = $column;
		}
		return $result;
	}
			
	public static function getConditionTypeArray($dbType,$inputType='') {
		$dbType = explode('(', $dbType);	
		$dbType = $dbType[0];
		$result = array(''=>TDLanguage::$please_choose);
		if(in_array($dbType,self::$dbType_eqonly) || in_array($inputType,FieldCollection::$SEARCH_EQ_ONLY)) {
			$result = array(
				self::$condion_type_eq => TDLanguage::$condion_type_eq,
				self::$condion_type_noteq => TDLanguage::$condion_type_noteq,
			);	
		} else if(in_array($inputType,FieldCollection::$SEARCH_INCLUDE_ONLY)) {
			$result = array(
				self::$condion_type_include => TDLanguage::$condion_type_include,
				self::$condion_type_NotInclude => TDLanguage::$condion_type_NotInclude,
			);	
		} else if(in_array($dbType, self::$dbType_round) || in_array($inputType,  FieldCollection::$SEARCH_AROUND)) {
			$result = array(
				self::$condion_type_eq => TDLanguage::$condion_type_eq,
				self::$condion_type_gre => TDLanguage::$condion_type_gre,
				self::$condion_type_les => TDLanguage::$condion_type_les,
				self::$condion_type_greeq => TDLanguage::$condion_type_greeq,
				self::$condion_type_leseq => TDLanguage::$condion_type_leseq,
				self::$condion_type_noteq => TDLanguage::$condion_type_noteq,
			);	
		} else if(in_array($dbType, self::$dbType_string)) {
			$result = array(
				self::$condion_type_LeftRightLike => "关键字", //TDLanguage::$condion_type_LeftRightLike,
				self::$condion_type_RightLike => "右关键字", //TDLanguage::$condion_type_RightLike,
				self::$condion_type_LeftLike => "左关键字",//TDLanguage::$condion_type_LeftLike,
				self::$condion_type_eq => TDLanguage::$condion_type_eq,
				self::$condion_type_noteq => TDLanguage::$condion_type_noteq,
			);
		}
		return $result;
	}

	public static function buildConditionSQL($columnName,$conditionType,$fieldValue,$dbType,$inputType='') {
		$result = '';
		if(!is_array($fieldValue) && !is_numeric($fieldValue) && empty($fieldValue) && !in_array($conditionType,array(self::$condion_type_eq,self::$condion_type_noteq))) 
			return $result;
		if(in_array($inputType,array('date','datetime','createtime','updatetime')) && strpos($dbType,"int") !== false) {
			$fieldValue = strtotime($fieldValue);
		}
		switch ($conditionType) {
			case self::$condion_type_eq:
				if($fieldValue == TDSearch::$field_value_is_null) {
					$result = '`t`.`'.$columnName.'` = \'\' or `t`.`'.$columnName.'` is null ';
					
				} else {
					$result = '`t`.`'.$columnName.'` = \''.$fieldValue.'\'';
				}
				break;
			case self::$condion_type_gre:
				$result = '`t`.`'.$columnName.'` > \''.$fieldValue.'\'';
				break;	
			case self::$condion_type_les:
				$result = '`t`.`'.$columnName.'` < \''.$fieldValue.'\'';
				break;
			case self::$condion_type_greeq:
				$result = '`t`.`'.$columnName.'` >= \''.$fieldValue.'\'';
				break;
			case self::$condion_type_leseq:
				$result = '`t`.`'.$columnName.'` <= \''.$fieldValue.'\'';
				break;	
			case self::$condion_type_noteq:
				if($fieldValue == TDSearch::$field_value_is_null) {
					$result = '`t`.`'.$columnName.'` != \'\' or `t`.`'.$columnName.'` is not null ';
				} else {
					$result = '`t`.`'.$columnName.'` != \''.$fieldValue.'\'';
				}
				break;
			case self::$condion_type_LeftRightLike:
				if($fieldValue == 'empty') {
					$result = ' (`t`.`'.$columnName.'` =\'\' or `t`.`'.$columnName.'` is null) ';
				} else if($fieldValue == 'not empty') {
					$result = ' (`t`.`'.$columnName.'` !=\'\' or `t`.`'.$columnName.'` is not null) ';
				} else {
					$result = '`t`.`'.$columnName.'` like \'%'.$fieldValue.'%\'';
				}
				break;
			case self::$condion_type_LeftLike:
				if($fieldValue == 'empty') {
					$result = ' (`t`.`'.$columnName.'` =\'\' or `t`.`'.$columnName.'` is null) ';
				} else if($fieldValue == 'not empty') {
					$result = ' (`t`.`'.$columnName.'` !=\'\' or `t`.`'.$columnName.'` is not null) ';
				} else {
					$result = '`t`.`'.$columnName.'` like \'%'.$fieldValue.'\'';
				}
				break;
			case self::$condion_type_RightLike:
				if($fieldValue == 'empty') {
					$result = ' (`t`.`'.$columnName.'` =\'\' or `t`.`'.$columnName.'` is null) ';
				} else if($fieldValue == 'not empty') {
					$result = ' (`t`.`'.$columnName.'` !=\'\' or `t`.`'.$columnName.'` is not null) ';
				} else {
					$result = '`t`.`'.$columnName.'` like \''.$fieldValue.'%\'';
				}
				break;
			case self::$condion_type_include:
				if(count(explode(TDSearch::$checkbox_name_key,$fieldValue)) > 1) {
					$fieldValue = isset($_GET[$fieldValue]) ? $_GET[$fieldValue] : array();
					foreach($fieldValue as $key => $value) {
						if(!is_numeric($value) && empty($value))
							continue;
						if(!empty($result))
							$result .= ' and ';
						$result .= 'FIND_IN_SET(\''.$value.'\',`t`.`'.$columnName.'`)';
					}
				} else {
					$result = 'FIND_IN_SET(\''.$fieldValue.'\',`t`.`'.$columnName.'`)';
				}
				break;
			case self::$condion_type_NotInclude:
				if(count(explode(TDSearch::$checkbox_name_key,$fieldValue)) > 1) {
					$fieldValue = isset($_GET[$fieldValue]) ? $_GET[$fieldValue] : array();
					foreach($fieldValue as $key => $value) {
						if(!is_numeric($value) && empty($value))
							continue;
						if(!empty($result))
							$result .= ' and ';
						$result .= 'not FIND_IN_SET(\''.$value.'\',`t`.`'.$columnName.'`)';
					}
				} else {
					$result = 'not FIND_IN_SET(\''.$fieldValue.'\',`t`.`'.$columnName.'`)';
				}
				break;
			default:
		}
		return $result;
	}

	 static function getConditionRenderParams($is_use_combination,$is_create_condition,$condition_table_id,$condition_pk_id=0,$markMuduleIdStr='') {
		$combinationStyle = $is_use_combination ? "" : " style='display:none' ";
		$searchButton = TDLanguage::$advanced_search_btn; 
		$conbinationButton = TDLanguage::$advanced_search_combination_btn;  
		if($is_create_condition) {
			$searchButton = TDLanguage::$advanced_search_create_condition ; 
			$conbinationButton = TDLanguage::$advanced_search_combination_create_condition;  
		}	
		$model = TDModelDAO::getModel(TDTableColumn::getTableDBName($condition_table_id));	
		$combinationFormula = '';
		$useCombinationFormula = 0;
		$combinationMaxNum = 0;
		$analyzeData = array();
		$analyzeHtml = "";
		if(!empty($analyzeData)) {
			$analyzeHtml = Fie_condition::getAnalyzeHtml($analyzeData,$condition_table_id,$markMuduleIdStr);	
		}
		return array('model'=>$model,
		'condition_pk_id' => $condition_pk_id,

		'analyzeHtml'=>$analyzeHtml,
		'combinationFormula'=>$combinationFormula,
		'useCombinationFormula'=>$useCombinationFormula,
		'combinationMaxNum'=>$combinationMaxNum,

		'combinationStyle' => $combinationStyle,
		'searchButton' => $searchButton,	
		'conbinationButton' => $conbinationButton,	
		'markMuduleIdStr' => $markMuduleIdStr,
		);
	}

	public static function getConditionAnalyzeDataArray($jsonFormatStr) {
		$jsonStr = str_replace("###","\"",$jsonFormatStr);	
		return json_decode($jsonStr);
	}	
	public static function createCondtionAnalyzeDataJsonFormatStr() {
		$belongStrs = isset($_GET['advSearch_belongStr']) ? $_GET['advSearch_belongStr'] : array();
		$columnIds = isset($_GET['advSearch_columnId']) ? $_GET['advSearch_columnId'] : array();
		$conditionTypes = isset($_GET['advSearch_conditionType']) ? $_GET['advSearch_conditionType'] : array();
		$fieldValues = isset($_GET['advSearch_fieldValue']) ? $_GET['advSearch_fieldValue'] : array();
		$combinationNum = isset($_GET['advSearch_combinationNum']) ? $_GET['advSearch_combinationNum'] : array();
		$combinationFormula = isset($_GET['advSearch_combinationFormula']) ? $_GET['advSearch_combinationFormula'] : '';
		$useCombinationFormula = isset($_GET['advSearch_useCombinationFormula']) && $_GET['advSearch_useCombinationFormula'] == '1' ? true : false;
		$combinationMaxNum = isset($_GET['combinationMaxNum']) ? $_GET['combinationMaxNum'] : 0;
		
		$analyzeData = array();
		$analyzeData['belongStrs'] = $belongStrs;	
		$analyzeData['columnIds'] = $columnIds;
		$analyzeData['conditionTypes'] = $conditionTypes;
		$analyzeData['fieldValues'] = $fieldValues;
		$analyzeData['combinationNum'] = $combinationNum;
		$analyzeData['combinationFormula'] = $combinationFormula;
		$analyzeData['useCombinationFormula'] = $useCombinationFormula;
		$analyzeData['combinationMaxNum'] = $combinationMaxNum;
		$jsonstr = json_encode($analyzeData);
		return str_replace("\"", "###", $jsonstr);
	}

	public static function getSearchConditionSql($tableId) {
		$belongStrs = isset($_GET['advSearch_belongStr']) ? $_GET['advSearch_belongStr'] : array();
		$columnIds = isset($_GET['advSearch_columnId']) ? $_GET['advSearch_columnId'] : array();
		$conditionTypes = isset($_GET['advSearch_conditionType']) ? $_GET['advSearch_conditionType'] : array();
		$fieldValues = isset($_GET['advSearch_fieldValue']) ? $_GET['advSearch_fieldValue'] : array();
		$combinationNum = isset($_GET['advSearch_combinationNum']) ? $_GET['advSearch_combinationNum'] : array();
		$combinationFormula = isset($_GET['advSearch_combinationFormula']) ? $_GET['advSearch_combinationFormula'] : '';
		$useCombinationFormula = isset($_GET['advSearch_useCombinationFormula']) && $_GET['advSearch_useCombinationFormula'] == '1' ? true : false;
		if(empty($combinationFormula) || !$useCombinationFormula) {
			$combinationNum = array();
			$useCombinationFormula = false;
		} else {
			$combinationFormula = str_replace('C','c',$combinationFormula);
			$combinationFormula = str_replace('B','b',$combinationFormula);
		}
		$searchData = array();
		for($i=0; $i<count($fieldValues); $i++) {
			if(empty($columnIds[$i])) 
				continue;
			$searchData[$belongStrs[$i]][] = array(
				'columnId' => $columnIds[$i],
				'conditionType' => $conditionTypes[$i],
				'fieldValue' => $fieldValues[$i],
				'combinationNum' => !empty($combinationNum) && !empty($combinationNum[$i]) ? "c".$combinationNum[$i]."b" : null,
			);
		}
		$criteria = self::getDBCriteria($searchData,"tc_".$tableId,$useCombinationFormula,$combinationFormula);	
		if(!is_null($criteria)) { 
			return $criteria->condition;
		} else { 
			return '';
		}
	}

	private static function getDBCriteria($searchData,$tcId,$useCombinationFormula,&$combinationFormula) {
		$criteria = null;
		$sqlCombinationArray = array();
		$tableId = explode("_",$tcId);
		$tableId = $tableId[count($tableId)-1];
		$tableName = TDTableColumn::getTableDBName($tableId);
		$table = TDTable::getTableObj($tableName);
		$queryData = isset($searchData[$tcId]) ? $searchData[$tcId] : array();
		foreach($queryData as $data) {
			$colName = TDTableColumn::getColumnDBName($data['columnId']);
			$columnRow = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$data['columnId'],"for_query_condition_sql,for_query_join_sql,gridview_query_show,column_type");
			if($columnRow["gridview_query_show"] == 1 && !empty($columnRow["for_query_condition_sql"])) {
				$sql = Fie_formula::getValue($data['fieldValue'],$columnRow["for_query_condition_sql"]);//$columnRow["column_type"] == 1 && 普通的字段也支持自定义搜索
			} else if(isset($table->columns[$colName])) {
				$inputType = TDTableColumn::getInputTypeByColumnId(TDTableColumn::getColumnIdByTableAndColumnName($tableName,$colName),false);
				$dbType = $table->columns[$colName]->dbType;
				$foreign_table_column_id = TDTableColumn::getColumnForeignColumnId($data['columnId']);
				$inputTypeFie = TDTableColumn::getInputTypeByColumnId($data['columnId']);
				if(($table->columns[$colName]->isForeignKey && isset($table->foreignKeys[$colName])) || (!empty($foreign_table_column_id)  && $inputTypeFie != "Fie_selectdb") 
					|| $inputTypeFie == "Fie_foreignkey") {
					if($table->columns[$colName]->isForeignKey && isset($table->foreignKeys[$colName])) {
						$foreignTableName = $table->foreignKeys[$colName][0]; 
						$foreignTableKey = $table->foreignKeys[$colName][1]; 
					} else if(!empty($foreign_table_column_id) && $inputTypeFie != "Fie_selectdb") {
						$foreignTableName = TDTableColumn::getColumnTableDBName($foreign_table_column_id);
						$foreignTableKey = TDTable::getTableObj($foreignTableName,false)->primaryKey; 	
					} else {
						$foreignTableName = TDTableColumn::getTableDBName(TDTableColumn::getMapTableCollectionId($data['columnId'])); 
						$foreignTableKey = TDTable::getTableObj($foreignTableName,false)->primaryKey; 	
					}	
					$foreignCri = self::getDBCriteria($searchData,
 					$tcId."_".$data['columnId']."_".TDTableColumn::getTableCollectionID($foreignTableName)
					,$useCombinationFormula,$combinationFormula);			
					if(is_null($foreignCri)) { 
						continue;
					}
					$foreignIdsSQL = "select `t`.`".$foreignTableKey."` from `".$foreignTableName."` as `t` where ".$foreignCri->condition; 
					if($data['conditionType'] == TDSearch::$condion_type_include) {
						$sql = '`t`.`'.$colName.'` in ('.$foreignIdsSQL.')';
					} else {
						$sql ='`t`.`'.$colName.'` not in ('.$foreignIdsSQL.')';
					}
				} else {
					$sql = self::buildConditionSQL($colName,$data['conditionType'],$data['fieldValue'],$dbType,$inputType);	
				}
			}	
			if(isset($sql) && !empty($sql)) {
				if($useCombinationFormula && !empty($data['combinationNum'])) {
					$sqlCombinationArray[] = array('combinationNum' => $data['combinationNum'],'conditionSql' => $sql);	
				} else {
					if(is_null($criteria)) 
						$criteria = new CDbCriteria;	
					$criteria->addCondition($sql);
				}
			}
		}	
		if($useCombinationFormula) {
			for($i=0; $i<count($sqlCombinationArray); $i++) {
				for($j=$i+1; $j<count($sqlCombinationArray); $j++) {
					if($sqlCombinationArray[$i]['combinationNum'] == $sqlCombinationArray[$j]['combinationNum']) {
						$sqlCombinationArray[$i]['conditionSql'] .= ' and '.$sqlCombinationArray[$j]['conditionSql']; 	
						unset($sqlCombinationArray[$j]);
						$sqlCombinationArray = array_merge($sqlCombinationArray);
						$j--;
					}
				}
			}	
			$formulaItem = explode(';',$combinationFormula);	
			for($i=0; $i<count($formulaItem); $i++) {
				$item = $formulaItem[$i];		
				if(empty($item))
					continue;
				$canUse = false;
				foreach($sqlCombinationArray as $combData) {
					if(strpos($item,$combData['combinationNum']) !== false) {
						$canUse = true;	
					}
				}	
				if($canUse) {
					$condition = $item;
					foreach($sqlCombinationArray as $combData) {
						$condition = str_replace($combData['combinationNum']," ".$combData['conditionSql']." ", $condition);
					}	
					if(!empty($condition) && !empty($sqlCombinationArray)) {
						if(is_null($criteria)) 
							$criteria = new CDbCriteria;
						$criteria->addCondition($condition);
						$combinationFormula = str_replace($item.";","",$combinationFormula);
						break;
					}
				}
			}
		}
		return $criteria;
	}
}