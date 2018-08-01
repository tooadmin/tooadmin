<?php

class TDFormat {
		
		public static function getModelAppendColumnValue($model,$columnName,$formatToInputTypeData=false) {
				if(empty($model)) {
					return null;
				}		
				if(strrpos($columnName,TDStaticDefined::$foreignKey_tableName) !== false) {
					$nameArray = explode('->',$columnName);
					if(count($nameArray) == 2) {
						$namestr0 = $nameArray[0];
						$namestr1 = $nameArray[1];
						$result = $model->$namestr0->$namestr1;
						return $result; 
					}
				}
				$result = null;
				$lastModelObj = null;
				$lastColumnName = null;
				$nameArray = explode('->',$columnName);
				if(count($nameArray) == 1) {
					$namestr = 	$nameArray[0];
					$result = isset($model->$namestr) || is_object($model->$namestr) ? $model->$namestr : null;
					if(!is_null($result)) {
						$lastModelObj = $model;
						$lastColumnName = $columnName;		
					}
				} elseif(count($nameArray) == 2) {
					$namestr0 = $nameArray[0];
					$namestr1 = $nameArray[1];
					$result = isset($model->$namestr0->$namestr1) || is_object($model->$namestr0->$namestr1) ? $model->$namestr0->$namestr1 : null;
					if(!is_null($result)) {
						$lastModelObj = $model->$namestr0;
						$lastColumnName = $namestr1;		
					}
				} elseif(count($nameArray) == 3) {
					$namestr0 = $nameArray[0];
					$namestr1 = $nameArray[1];
					$namestr2 = $nameArray[2];
					$result = isset($model->$namestr0->$namestr1->$namestr2)
					|| is_object($model->$namestr0->$namestr1->$namestr2) ? $model->$namestr0->$namestr1->$namestr2 : null;
					if(!is_null($result)) {
						$lastModelObj = $model->$namestr0->$namestr1;
						$lastColumnName = $namestr2;		
					}
				} elseif(count($nameArray) == 4) {
					$namestr0 = $nameArray[0];
					$namestr1 = $nameArray[1];
					$namestr2 = $nameArray[2];	
					$namestr3 = $nameArray[3];	
					$result = isset($model->$namestr0->$namestr1->$namestr2->$namestr3)
					|| is_object($model->$namestr0->$namestr1->$namestr2->$namestr3) ? $model->$namestr0->$namestr1->$namestr2->$namestr3 : null;
					if(!is_null($result)) {
						$lastModelObj = $model->$namestr0->$namestr1->$namestr2;
						$lastColumnName = $namestr3;		
					}
				} elseif(count($nameArray) == 5) {
					$namestr0 = $nameArray[0];
					$namestr1 = $nameArray[1];
					$namestr2 = $nameArray[2];	
					$namestr3 = $nameArray[3];	
					$namestr4 = $nameArray[4];	
					$result = isset($model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4)
					|| is_object($model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4) ? $model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4 : null;
					if(!is_null($result)) {
						$lastModelObj = $model->$namestr0->$namestr1->$namestr2->$namestr3;
						$lastColumnName = $namestr4;		
					}
				} elseif(count($nameArray) == 6) {
					$namestr0 = $nameArray[0];
					$namestr1 = $nameArray[1];
					$namestr2 = $nameArray[2];	
					$namestr3 = $nameArray[3];	
					$namestr4 = $nameArray[4];	
					$namestr5 = $nameArray[5];	
					$result = isset($model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4->$namestr5)
					|| is_object($model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4->$namestr5) ? $model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4->$namestr5 : null;
					if(!is_null($result)) {
						$lastModelObj = $model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4;
						$lastColumnName = $namestr5;		
					}
				}
				if($formatToInputTypeData && !is_null($result) && !is_null($lastModelObj)) {
					$columnId = TDTableColumn::getColumnIdByTableAndColumnName($lastModelObj->tableName,$lastColumnName);
					$inputType =  TDTableColumn::getInputTypeByColumnId($columnId);
					if($inputType == "Fie_selectdb") {
						return Fie_foreignkey::getFieldText($columnId,$result);
					}
				}
				return $result;
		}

		public static function setModelAppendColumnValue($model,$columnAppStr,$value) {
			$nameArray = explode('->',$columnAppStr);
			if(count($nameArray) == 1) {
				$namestr0 = $nameArray[0];
				$model->$namestr0 = $value;
			} elseif(count($nameArray) == 2) {
				$namestr0 = $nameArray[0];
				$namestr1 = $nameArray[1];
				$model->$namestr0->$namestr1 = $value;
			} elseif(count($nameArray) == 3) {
				$namestr0 = $nameArray[0];
				$namestr1 = $nameArray[1];
				$namestr2 = $nameArray[2];
				$model->$namestr0->$namestr1->$namestr2 = $value;
			} elseif(count($nameArray) == 4) {
				$namestr0 = $nameArray[0];
				$namestr1 = $nameArray[1];
				$namestr2 = $nameArray[2];
				$namestr3 = $nameArray[3];
				$model->$namestr0->$namestr1->$namestr2->$namestr3 = $value;
			} elseif(count($nameArray) == 5) {
				$namestr0 = $nameArray[0];
				$namestr1 = $nameArray[1];
				$namestr2 = $nameArray[2];
				$namestr3 = $nameArray[3];
				$namestr4 = $nameArray[4];
				$model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4 = $value;
			} elseif(count($nameArray) == 6) {
				$namestr0 = $nameArray[0];
				$namestr1 = $nameArray[1];
				$namestr2 = $nameArray[2];
				$namestr3 = $nameArray[3];
				$namestr4 = $nameArray[4];
				$namestr5 = $nameArray[5];
				$model->$namestr0->$namestr1->$namestr2->$namestr3->$namestr4->$namestr5 = $value;
			}
		}

		public static function getBaseColumnNameFromAppendName($columnName) {
			$nameArray = explode('->',$columnName);
			return $nameArray[count($nameArray) - 1];	
		}

		public static function getBaseColumnTableNameFromAppendName($columnName,$orgTableName) {
			$nameArray = explode('->',$columnName);
			if(count($nameArray) > 1) {
			 	$tmpStr =  $nameArray[count($nameArray) - 2];	
				$tmpStr = explode(TDStaticDefined::$foreignKey_tableName,$tmpStr);
				$tmpStr = $tmpStr[1];
				return $tmpStr;
			} else {
				return $orgTableName;
			}
		}

		public static function getBaseColumnLastModelFromAppendName($model,$appColumnNameStr) {
			$nameArray = explode('->',$appColumnNameStr);
			if(count($nameArray) == 1) {
				return $model;
			} else if(count($nameArray) > 1) {
				$newStr = "";
				for($i=0; $i < count($nameArray) -1; $i++) {
					if(!empty($newStr)) { $newStr .= "->"; }
					$newStr .= $nameArray[$i];
				}
				return self::getModelAppendColumnValue($model,$newStr);
			}
		}

		public static function getUrlExpandParamStr() {
			$str = '';
			if(isset($_GET[TDStaticDefined::$OPERATE_TYPE_KEY])) {
				$str .= '&'.TDStaticDefined::$OPERATE_TYPE_KEY.'='.$_GET[TDStaticDefined::$OPERATE_TYPE_KEY];	
			}	
			if(isset($_GET[TDStaticDefined::$popupSearchColumnIdStr])) {
				$str .= '&'.TDStaticDefined::$popupSearchColumnIdStr.'='.$_GET[TDStaticDefined::$popupSearchColumnIdStr];	
			}
			if(isset($_GET[TDStaticDefined::$popupSearchForeignFieldId])) {
				$str .= '&'.TDStaticDefined::$popupSearchForeignFieldId.'='.$_GET[TDStaticDefined::$popupSearchForeignFieldId];	
			}
			if(isset($_GET[TDStaticDefined::$ladderColumn_FieldColumnId])) {
				$str .= '&'.TDStaticDefined::$ladderColumn_FieldColumnId.'='.$_GET[TDStaticDefined::$ladderColumn_FieldColumnId];	
			}
			if(isset($_GET[TDStaticDefined::$ladderColumn_FieldTextId])) {
				$str .= '&'.TDStaticDefined::$ladderColumn_FieldTextId.'='.$_GET[TDStaticDefined::$ladderColumn_FieldTextId];	
			}
			if(isset($_GET['onclick'])) {
				$str .= '&onclick='.  str_replace("'","\'",$_GET['onclick']);
			}
			return $str;
		}

		public static function getPidMarginSpanInTable($tableColumnId,$pkIdValue,$moduleId) {
			$tableName = TDTableColumn::getColumnTableDBName($tableColumnId);
			$columnName = TDTableColumn::getColumnDBName($tableColumnId);
			$baseCondition = "";
			$keyStr = "'{{{".$columnName."}}}'";
			$cacheValue = TDSessionData::getCache('getPidMarginSpanInTable_'.$moduleId.'_'.$pkIdValue);
			if($cacheValue && $moduleId !== TDStaticDefined::$mysqlCommonModuleId) {
				$baseCondition = $cacheValue;
			} else {
				$_GET["is_from_expand_tree"] = "1";
				$gridview = new TDGridView(TDStaticDefined::getTmpController(),$moduleId);
				$gridview->getDataProvider();
				$baseCondition = $gridview->model->getDbCriteria()->condition;
				$_GET["is_from_expand_tree"] = "0";
				if(!empty($baseCondition)) { $baseCondition .= " and  "; 
				$baseCondition = str_replace(" t."," ",$baseCondition);
				$baseCondition = str_replace("(t.","(",$baseCondition);
				$baseCondition = str_replace("`t`.","",$baseCondition);
				if(substr($baseCondition,0,2) == "t.") {
					$baseCondition = substr($baseCondition,2);
				}
				}
				$checkStrAr = array(
					"`".$columnName."` = ",	
					"`".$columnName."` =",	
					"`".$columnName."`=",	
					
					"(".$columnName." = ",	
					"(".$columnName." =",	
					"(".$columnName."=",

					" ".$columnName." = ",	
					" ".$columnName." =",	
					" ".$columnName."=",
				);
				foreach($checkStrAr as $checkStr) {
					if(strrpos($baseCondition,$checkStr) !== false) {
						$strAr = explode($checkStr,$baseCondition);	
						$baseCondition = $strAr[0].$checkStr.$keyStr;
						if(isset($strAr[1])) {
							$str2 = trim($strAr[1]);
							if(strpos($str2," ") !== false && strpos($str2,")") !== false && strpos($str2,")") < strpos($str2," ")) {
								$baseCondition .= substr($str2,strpos($str2, ")"));
							} else if(strpos($str2," ") !== false) {
								$baseCondition .= substr($str2,strpos($str2, " "));
							} else if(strpos($str2,")") !== false) {
								$baseCondition .= substr($str2,strpos($str2, ")"));
							}
						}
						break;
					} 
				}
				TDSessionData::setCache('getPidMarginSpanInTable_'.$moduleId.'_'.$pkIdValue,$baseCondition);
			}
			$baseCondition = str_replace("{{{".$columnName."}}}",$pkIdValue,$baseCondition);
			$childCount = TDModelDAO::queryScalar($tableName, $baseCondition.' `'.$columnName.'`=\''.$pkIdValue.'\'', 'count(*)');
			$rdNum = time().'_'.$tableColumnId.'_'.$pkIdValue;
			$idNum = "oca".$rdNum;
			$result = '';
			$apendParams = TDFormat::getUrlExpandParamStr();
			if($childCount > 0) {
				$result = '<a id="'.$idNum.'" href="javascript:expandTableTreeData('.$moduleId.',\''.$tableColumnId.'\',\''.$pkIdValue.'\',\''
				.$pkIdValue.'\',\''.$rdNum.'\',\''.$apendParams.'\');void(0);">'.TDCommonCss::$tree_closeed_icon.'</a>';
			}	
			$result .= '<input type="hidden" id="belongIds'.$rdNum.'" value="" expand="belongid" />';
			return $result;
		}

		public static function tableStrFormat($str) {
			$newStr = ''; 
			if(strpos($str,"</span>") !== false) {
				$arStr = explode("</span>",$str);
				for($i=0; $i<count($arStr); $i++) {
					if($i > 0 && $i % 3 == 0) {
						$newStr .= "<br/>";
					}
					$newStr .= $arStr[$i]."</span>&nbsp;&nbsp;&nbsp;";	
				}
				return $newStr;
			}
			$maxLeng = 18;
			$canSplite = true;
			$newStr = '';
			$start = 0;
			while($canSplite) {
				$tmp = mb_substr($str,$start,$maxLeng,"UTF-8");	
				if(!empty($tmp)) {
					$newStr .= $tmp .'<br/>';	
					$start += $maxLeng; 
				} else {
					$canSplite = false;
				}
			}
			return $newStr;
		}

		public static function getArrayKeyFormValue($array,$value) {
			foreach($array as $key => $keyValue) {
				if($keyValue == $value) {
					return $key;
				}
			}
			return '';
		}
		
		public static function parseAttributeNullToEmpty(&$model) {
			foreach($model->attributes as $col => $val) {
				if(is_null($val)) {
					$model->$col = "";
				}
			}	
		}

	public static function parseArrayToFileStr($baseArray) {
		$str = 'array(';
		foreach($baseArray as $key => $value) {
			$str .= '"'.$key.'"=>';
			$str .= is_array($value) ? self::parseArrayToFileStr($value) : '"'.$value.'"'; 
			$str .= ",";
		}
		$str .= ")";
		return $str;
	}
}