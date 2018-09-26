<?php
abstract class TDField {
	abstract function editForm($params); 
	abstract function gridView($params);
	abstract function viewData($params);
	abstract function saveData($params);
	abstract function search($params);
	abstract function editTableColumn($params);
	abstract function viewHtml($params);

	public static function getInputId_foreignKey() { return TDModelDAO::queryScalar(TDTable::$too_table_column_input,'`name`=\''.  Fie_foreignkey::getInputTypeStr().'\'',"id"); }
	
	//gridview
	public static function getGridViewColumns($widgetObj,$moduleId,$gridviewIsOnlyView = false) {
		$columns = array();
		if($moduleId == TDStaticDefined::$mysqlCommonModuleId) {
			$useColumns = [];
			$allOrderColums = [];
			$emptyGridviewArra = TDModelDAO::getModel(TDTable::$too_module_gridview)->attributes;
			if(isset($_GET[TDStaticDefined::$mysqlTableColumnsStr]) && !empty($_GET[TDStaticDefined::$mysqlTableColumnsStr])) {
				$columnIds = $_GET[TDStaticDefined::$mysqlTableColumnsStr];	
				$columnItems = explode(TDSearch::$expand_tree_column_key_column,$columnIds);
				foreach($columnItems as $item) {
					$tmpStr = explode(TDSearch::$expand_tree_str_key_str,$item);
					$belogOrderColumnIds = $tmpStr[0];
					$tmpArr = explode(",",$belogOrderColumnIds);
					$belogToColumnId = $tmpArr[count($tmpArr)-1];
					$columnId = $tmpStr[1];
					$tmpRow = $emptyGridviewArra;
					$tmpRow["module_id"] = $moduleId;
					$tmpRow["table_column_id"] = $columnId;
					$tmpRow["belong_to_column_id"] = empty($belogToColumnId) ? null : $belogToColumnId;
					$tmpRow["belong_order_column_ids"] = empty($belogOrderColumnIds) ? null : $belogOrderColumnIds;
					$tmpRow["allow_order"] = 1;
					$useColumns[] = $tmpRow;
					if(!TDTableColumn::checkIsCustomColumn($tmpRow["table_column_id"])) {
						$allOrderColums[] = TDTableColumn::getColumnAppendStr($tmpRow["table_column_id"],$tmpRow["belong_order_column_ids"]);	
					}
				}
			} else {
				$baseColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".TDTableColumn::getTableCollectionID($widgetObj->tableName)." order by `order`","id");
				foreach($baseColumns as $tmpCols) {
					$tmpRow = $emptyGridviewArra;
					$tmpRow["module_id"] = $moduleId;
					$tmpRow["table_column_id"] = $tmpCols["id"];
					$tmpRow["allow_order"] = 1;
					$useColumns[] = $tmpRow;
					if(!TDTableColumn::checkIsCustomColumn($tmpRow["table_column_id"])) {
						$allOrderColums[] = TDTableColumn::getColumnAppendStr($tmpRow["table_column_id"],$tmpRow["belong_order_column_ids"]);	
					}
				}
			}
			$widgetObj->forJsMethodUseOrderColumns = $allOrderColums;	
			if(isset($_GET[TDStaticDefined::$mysqlDataDispalyType]) && $_GET[TDStaticDefined::$mysqlDataDispalyType] == TDStaticDefined::$mysqlDataDispalyType_org) {
				$useColumns = [];	
			}
		} else {
			$useColumns = TDModelDAO::queryAll(TDTable::$too_module_gridview,'`module_id`=\''.$moduleId.'\' order by `order`');
		}
		foreach($useColumns as $row) {
			if(!TDPermission::checkQueryPermission($row["table_column_id"])) {
				continue;	
			}
			$params = array(
				'tableColumnId'=>$row["table_column_id"],
				'belongOrderColumnIds' => $row["belong_order_column_ids"],
				'moduleId' => $moduleId,
			    'columnData' =>  self::getColumnGridViewData($row["table_column_id"],$row["belong_order_column_ids"]),
			);
			$columnData = $params["columnData"];
			if($columnData["column_type"] == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN || !empty($columnData["formula"])) { 
				$tmpArray = array(
					'name' => $columnData['name'],
					'header' => $columnData['header'],
					'type' => $columnData['type'],
					'value' => 'Fie_formula::computeFormula($data,"'.$columnData['name'].'")',
				);
				$headerHtmlStr = '';
				if(!empty($row["width"])) { 
						$headerHtmlStr .= 'width:'.$row["width"].'%;'; 
				} else {
						if(strpos($tmpArray["header"],"时间") !== false) {
							$px = 106;
						} else {
							$len = strlen(strip_tags($tmpArray["header"]));
							$count = intval($len/3);
							if($len%3 > 0) { $count++; }
							$px = $count * 18 + 10;
						}
						$headerHtmlStr .= "min-width:".$px."px;"; 	
				}
				if($row["is_hidden"]) { $headerHtmlStr .= 'display:none;';$tmpArray["htmlOptions"] = array("style"=>"display:none;");  }
				if(!empty($headerHtmlStr)) { 
					$tmpArray["headerHtmlOptions"] = array("columnid"=>$row["table_column_id"]);
					$tmpArray["headerHtmlOptions"]["style"] = $headerHtmlStr;
				}
				if($row["allow_sum"] || $row["allow_avg"]) {
					$dataRows = $widgetObj->getDataProvider()->getData();
					$sumValue = 0;
					$rowCount = count($dataRows);
					foreach($dataRows as $dataRow) {
						$sumValue += Fie_formula::computeFormula($dataRow,$columnData['name']);	
					}
					$tmpArray['footerHtmlOptions'] = array("style"=>"font-weight:bold;");
					if($row["allow_sum"]) {
						$tmpArray['footer'] = TDLanguage::$common_sum.$sumValue; 
					}
					if($row["allow_avg"]) {
						$avgValue = $sumValue > 0 ? round($sumValue/$rowCount,2) : 0;	
						$tmpArray['footer'] = isset($tmpArray['footer']) ?  $tmpArray['footer']."<br/>"
						.TDLanguage::$common_avg.$avgValue : TDLanguage::$common_avg.$avgValue;
					}
				}
				if($row["is_hidden"]) { $tmpArray["footerHtmlOptions"] = array("style"=>"display:none;"); }
				$columns[] = $tmpArray;
				continue; 
			}
			$inputType = TDTableColumn::getInputTypeByInputId($columnData['columnInputId']);	
			if(method_exists($inputType,'gridView')) {
				$fie = new $inputType();
				$value = $fie->gridView($params);	
				if(!is_null($value)) {
					$gvColumnName = $columnData['name'];
					$tmpArray = array(
						'name' => $gvColumnName,
						'header' => $columnData['header'],
						'type' => $columnData['type'],
						//'value' => 'isset('.$columnData['value'].') ? '.$value.' : ""',
						'value' => 'true ? '.
						($columnData["columnInputId"] == Fie_formula::getInputTypeId() ? 'Fie_formula::getValue(null,'.$value.')' : $value).' : ""',

						//'value' => 'isset('.$columnData['value'].') ? '.
						//($columnData["columnInputId"] == Fie_formula::getInputTypeId() ? 'Fie_formula::getValue(null,'.$value.')' : $value).' : ""',

					   	//注意区分，最上面的 formula 处理 'value' => 'Fie_formula::computeFormula($data,"'.$columnData['name'].'")',  是用于处理
					    	//字段为 自定义 或 formula 内容 不为空的 字段内容 进行formula处理，
					    	//当前这里的formula处理是，当字段内容本身为formula内容时的，处理后显示
					);
					$headerHtmlStr = '';
					if(!empty($row["width"])) { 
						$headerHtmlStr .= 'width:'.$row["width"].'%;'; 
					} else {
						if(strpos($inputType,"datetime") !== false) {
							$px = 106;
						} else {
							$len = strlen(strip_tags($tmpArray["header"]));
							$count = intval($len/3);
							if($len%3 > 0) { $count++; }
							$px = $count * 18 + 10;
						}	
						$headerHtmlStr .= "min-width:".$px."px;"; 	
					}
					if($row["is_hidden"]) { $headerHtmlStr .='display:none;'; $tmpArray["htmlOptions"] = array("style"=>"display:none;"); }	
					if(!empty($headerHtmlStr)) { 
						$tmpArray["headerHtmlOptions"] = array("columnid"=>$row["table_column_id"]); 
						$tmpArray["headerHtmlOptions"]["style"] = $headerHtmlStr;
					}
					if(($row["allow_edit"] || $inputType == "Fie_order") && $columnData['column_type'] == TDTableColumn::$COLUMN_TYPE_DB_COLUMN && !$gridviewIsOnlyView) {
						$tmpArray['type'] = 'raw';
						$tmpArray['value'] = 'TDField::gridViewEditColumn("'.$params['tableColumnId'].'","'.$inputType
						.'","'.$columnData['dbType'].'","'.TDPathUrl::createUrl("tDAjax/updateARow").'/gridViewId/'.$row["id"]
						.TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN,$columnData['tableName'])
						.'/newValue/",'.$value.',$data)';
					}
					if($row["allow_sum"] || $row["allow_avg"]) {
						$dataRows = $widgetObj->getDataProvider()->getData();
						$sumValue = 0;
						$rowCount = count($dataRows);
						foreach($dataRows as $dataRow) {
							$sumValue += TDFormat::getModelAppendColumnValue($dataRow, $gvColumnName);// $dataRow->$gvColumnName;	
						}
						$tmpArray['footerHtmlOptions'] = array("style"=>"font-weight:bold;");
						if($row["allow_sum"]) {
							$tmpArray['footer'] = TDLanguage::$common_sum.$sumValue; 
						}
						if($row["allow_avg"]) {
							$avgValue = $sumValue > 0 ? round($sumValue/$rowCount,2) : 0;	
							$tmpArray['footer'] = isset($tmpArray['footer']) ?  $tmpArray['footer']."<br/>"
							.TDLanguage::$common_avg.$avgValue : TDLanguage::$common_avg.$avgValue;
						}
					}
					if($row["is_hidden"]) { $tmpArray["footerHtmlOptions"] = array("style"=>"display:none;"); }	
					$columns[] = $tmpArray;
				}
			}
		}
		return $columns;	
	}
	//gridview
	public static function getColumnGridViewData($tableColumnId,$belongOrderColumnIds=null) {
		$columnData = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId);
		$appendStr = TDTableColumn::getColumnAppendStr($tableColumnId,$belongOrderColumnIds);
		$header = $columnData["label"]; 
		$tableName = '';
		if(!empty($belongOrderColumnIds)) {
			$tmpId = explode(',',$belongOrderColumnIds);
			$tmpId = $tmpId[0];
			$tableName = TDTableColumn::getColumnTableDBName($tmpId);
			///$header = "【".TDTableColumn::getColumnLabelName($tmpId)."】".$header; 
		} else {
			$tableName = TDTable::getTableDBName($columnData["table_collection_id"]);
		}
		return array(
			'name' => $appendStr,
			'header' => $header,
			'type' => 'raw',
			'value' =>  '$data->'.$appendStr,
			'tableName' => $tableName,
			'dbType' => $columnData["db_type"],
			'columnInputId' => $columnData["table_column_input_id"],
			'column_type' => $columnData["column_type"],
		    'formula' => $columnData["formula"],
		    'input_expand_type' => $columnData["input_expand_type"],
		);	
	}

	public static function gridViewEditColumn($tableColumnId,$inputType,$dbType,$url,$value,$rowModel=null) {
		$result = $value; 
		if(TDPermission::checkUpdatePermission($tableColumnId) && method_exists($inputType,"editTableColumn")) {
			$fie = new $inputType();
			$params = array(
				'value' => $value,	
				'urlstr' => $url,
				'dbType' => $dbType,
				'tableColumnId'=>$tableColumnId,
				'rowModel'=>$rowModel
			);
			$input = $fie->editTableColumn($params);
			if(!empty($input)) {
				$result = $input;
			}
		}
		return $result;
	}

	public static function checkHasModuleFormModuleForEdit($moduleId) { 
		$count = 0;
		$model = TDModelDAO::getModel(TDModule::getModuleTableName($moduleId),$moduleId);
		$rows = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId
		.' and is_foredit=1 and is_activate=1 and gridview_expbtn_id=0',"tab_display_condition");
		foreach($rows as $row) {
			$count = Fie_formula::getValue($model,$row['tab_display_condition']) ? $count+1 : $count;
		}
		return $count > 0;
	}

	public static function setModelBeforFormLoad(&$model,$firstEditFormModuleId=0) {
		$moduleFormModulePkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
		$rowPkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_ROW_PKID);
		if(!empty($firstEditFormModuleId) || !empty($rowPkId)) {
			$firstEditFormModuleId = !empty($firstEditFormModuleId) ? $firstEditFormModuleId : $rowPkId;
			$code = TDModelDAO::queryScalarByPk(TDTable::$too_module,$firstEditFormModuleId,"before_form_set_code");
			if(!empty($code)) { eval($code); }
		}
		if(!empty($moduleFormModulePkId) && !empty($rowPkId)) {
			$moduleFormModule = TDModelDAO::queryRowByPk(TDTable::$too_module_formmodule,$moduleFormModulePkId,"default_relation_column,ntable_before_form_code,form_module_id");
			if(!empty($moduleFormModule["default_relation_column"])) {
				$items = explode(",",$moduleFormModule["default_relation_column"]);
				if(count($items) == 1) {
					$forc = TDTableColumn::getColumnDBName($moduleFormModule["default_relation_column"]);
					$model->$forc = $rowPkId;
				}
			}
			if(!empty($moduleFormModule["ntable_before_form_code"])) {
				if(!empty($moduleFormModule["form_module_id"])) {
					$data = TDModelDAO::getModel(TDModule::getModuleTableName($moduleFormModule["form_module_id"]),$rowPkId);
					eval($moduleFormModule["ntable_before_form_code"]);
				}
			}
		}
	}

	public static function getFormModuleExpParamsForPopSerach() {
		$str = "";
		$moduleFormModulePkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
		$rowPkId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_ROW_PKID);
		if(!empty($moduleFormModulePkId) && !empty($rowPkId)) {
			$str = "/".TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID."/".$moduleFormModulePkId."/".TDStaticDefined::$PARAM_MODULE_ROW_PKID."/".$rowPkId;
		}	
		return $str;
	}

	public static function getAppendUrl($model,$arrayCodeVAL="") {
		$appendUrl = "";	
		if(!empty($arrayCodeVAL)) {
			$getArray = Fie_formula::getValue($model,$arrayCodeVAL);
			if(!empty($getArray) && is_array($getArray)) {
				foreach($getArray as $key => $value) {
					$appendUrl .= "/".$key."/".$value;
				}
			}
		}	
		return $appendUrl;
	}
	
	//form
	public static function createFormEditField($moduleId,$model,$useForGridviewEditRow=false) {
		$strAddEdit = '';
		if($model->isNewRecord) {
			$strAddEdit = ' and `use_add`=1 ';
		} else {
			$strAddEdit = ' and `use_update`=1 ';
		}
		$querySQL = '`module_id`=\''.$moduleId.'\' '.$strAddEdit.' order by `order`';
		$fieldHtmlArray = array();
		$useColumns = array();
		$editOnlyCondi = "";
		if(isset($_GET["expbtnid"])) {
			$editOnlyCondi = " and gridview_expbtn_id=".  intval($_GET["expbtnid"]);	
		} else {
			$editOnlyCondi = $model->isNewRecord ? " and is_foradd=1 and gridview_expbtn_id=0 " : " and is_foredit=1 and gridview_expbtn_id=0 ";
			$emptyFormEditArr = TDModelDAO::getModel(TDTable::$too_module_formEdit)->attributes;	
			if($moduleId == TDStaticDefined::$mysqlCommonModuleId) {
				$baseColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".TDTableColumn::getTableCollectionID($model->tableName)." order by `order`","id,is_primary_key,auto_increment");
				foreach($baseColumns as $tmpCols) {
					if($tmpCols["is_primary_key"] != 1 && !TDTableColumn::checkIsCustomColumn($tmpCols["id"])) {
						$tmpRow = $emptyFormEditArr;
						$tmpRow["module_id"] = $moduleId;
						$tmpRow["table_column_id"] = $tmpCols["id"];
						$useColumns[] = $tmpRow;		
					}
				}
			} else {
				$useColumns = TDModelDAO::queryScalarByPk(TDTable::$too_module,intval($moduleId),"notuse_sys_form") == 0 ? TDModelDAO::queryAll(TDTable::$too_module_formEdit,$querySQL) : array();
			}
		}
		
		$modelUseGroup = TDModelDAO::queryRowByPk(TDTable::$too_module,$moduleId,"form_use_group");
		$useGroup = !empty($modelUseGroup) && $modelUseGroup["form_use_group"] == 1 ? true : false;
		foreach($useColumns as $row) {
			if($model->isNewRecord) {
				if(!TDPermission::checkAddPermission($row["table_column_id"])) {
					continue;	
				}
			} else {
				if(!TDPermission::checkUpdatePermission($row["table_column_id"])) {
					continue;	
				}	
			}
			$params = array(
				'tableColumnId'=>$row["table_column_id"],
				'belongOrderColumnIds' => $row["belong_order_column_ids"],
				'model' => $model,
			    'columnFormData' => self::getColumnFormData($row["table_column_id"],$row["belong_order_column_ids"],$model),
			);
			$columnFormData = $params['columnFormData'];
			if(!$columnFormData["displayValidate"]) { continue; }
			$inputType = TDTableColumn::getInputTypeByInputId($columnFormData['columnInputId']);	
			if(method_exists($inputType,'editForm')) {
				$fie = new $inputType();
				if($row["readonly"] == 1 || TDTableColumn::checkIsCustomColumn($row["table_column_id"])) {
					$fieldHtml = $fie->viewData($params);	
					$fieldHtml = $fieldHtml["value"];	
				} else {
					$fieldHtml = $fie->editForm($params);	
				}
				$formCusCode = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"edit_form_code");
				if(!empty($formCusCode)) { $fieldHtml = Fie_formula::getValue($model,$formCusCode); }
				if($useForGridviewEditRow) {
					$fieldHtmlArray[] = array(
						'columnId' => $params["tableColumnId"],
						'fieldHtml' => $fieldHtml,
					);
				} else if(!empty($fieldHtml) || $row["readonly"] == 1) {
					$lableTxt = empty($columnFormData["input_front_txt"]) ? $columnFormData['label'] : $columnFormData["input_front_txt"]; 
					$columnHeader = $columnFormData['allowEmpty'] && !$columnFormData['in_form_notnull'] ? $lableTxt
					: ($lableTxt."<span style='color:red;'>*</span>");
					$fieldHtml = FieldFactory::getInputBeforeHTML($inputType,$columnHeader,$row["readonly"] == 1).$fieldHtml;
					$fieldHtml .= FieldFactory::getInputAfterHTML($columnFormData['id'],"",$columnFormData["formula_remark"],$columnFormData["input_back_txt"]);
					$tmpModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$row["table_column_id"],"group_id");
					$groupId = $useGroup && !empty($tmpModel["group_id"]) ? $tmpModel["group_id"] : 0; 
					if(!isset($fieldHtmlArray[$groupId])) {
						$fieldHtmlArray[$groupId] = array(); 	
					}
					$fieldHtmlArray[$groupId][] = $fieldHtml; 
				}
			}
		}
		if($useForGridviewEditRow) {
			return $fieldHtmlArray;	
		}
		// reorder 
		if(count($fieldHtmlArray) > 1) {
			$tmpRows = TDModelDAO::queryAll(TDTable::$too_table_column_class,'`table_id`='.TDTableColumn::getTableCollectionID($model->tableName()).' order by `order`',"id");
			$keysClassId = array_keys($fieldHtmlArray);
			$newFieldHtmlArray = array();
			foreach($tmpRows as $tmp) { 
				if(in_array($tmp["id"],$keysClassId)) {
					$newFieldHtmlArray[$tmp["id"]] = $fieldHtmlArray[$tmp["id"]];
				}
			}
			$newKeys = array_keys($newFieldHtmlArray);
			foreach($keysClassId as $tmpId) { 
				if(!in_array($tmpId,$newKeys)) {
					$newFieldHtmlArray[$tmpId] = $fieldHtmlArray[$tmpId];
				}
			}	
			$fieldHtmlArray = $newFieldHtmlArray;
		}
		
		// 加入嵌入表单的 module_formmodule
		$moduleFormModules = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId.
		' and `is_activate`=1 and order_type=1 '.$editOnlyCondi.' order by `order`,id','id,tab_display_condition,formtab_title,ntable_module_id,page_type,page_url_get_array');
		$startEnterNum = TDStaticDefined::$formInnerGridviewIndexId;
		foreach($moduleFormModules as $item) {
			if(!empty($item["tab_display_condition"])) {
				$dispaly = Fie_formula::getValue($model,$item["tab_display_condition"]);
				if(!$dispaly) { continue; }
			}
			foreach($fieldHtmlArray as $fkey => $fitem) {
				$fieldHtmlArray[$fkey][$startEnterNum] = array(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID=>$item["id"],
				TDStaticDefined::$PARAM_MODULE_ROW_PKID=>  intval($model->primaryKey),'ntableModuleId'=>$item["ntable_module_id"],"pageType"=>$item["page_type"],"appendUrl"=>self::getAppendUrl($model,$item["page_url_get_array"])); 
				$startEnterNum++;
				break;
			}
		}
		// 加入tab项的 module_formmodule 
		$moduleFormModules = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId.
		' and `is_activate`=1 and order_type=0 '.$editOnlyCondi.' order by `order`,id','id,tab_display_condition,formtab_title,ntable_module_id,page_type,page_url_get_array');
		foreach($moduleFormModules as $item) {
			if(!empty($item["tab_display_condition"])) {
				$dispaly = Fie_formula::getValue($model,$item["tab_display_condition"]);
				if(!$dispaly) { continue; }
			}
			$fieldHtmlArray[$item["formtab_title"]] = array(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID=>$item["id"],
			TDStaticDefined::$PARAM_MODULE_ROW_PKID=>  intval($model->primaryKey),'ntableModuleId'=>$item["ntable_module_id"],"pageType"=>$item["page_type"],"appendUrl"=>self::getAppendUrl($model,$item["page_url_get_array"])); 
		}	
		
		$useTab = count($fieldHtmlArray) > 0;
		if($useTab) {
			$jsAutoRunFun ='';
			echo '
			<div class="tabbable">
				<ul class="nav nav-tabs">'; 
			$rowIndex = 1;
			foreach($fieldHtmlArray as $classId => $fieldHtmls) { 
				$tabTitle = "";
				$onclick = "";
				$curClickFun = '';
				if(is_numeric($classId)) {
					//过滤从属子分类
					if($classId > 0 && TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class,$classId,"min(pid)") > 0) {
						continue;
					}
					$tmp = TDTableColumn::getTableColumnClassGroupName($classId);
					$tabTitle = !empty($tmp) ? $tmp : TDLanguage::$table_column_class_other;	
				} else {
					$tabTitle = $classId; 
					$classId = "gridview".$rowIndex;
					$curClickFun = "";
					if($fieldHtmls["pageType"] == 1) {
						$curClickFun = 'formLoadModuleFormCustomPage(\'fieldtab_'.$classId.'_'.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].'\','
						.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].','.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_ROW_PKID].',\''.$fieldHtmls["appendUrl"].'\')'; 
					} else {
						$curClickFun = 'formLoadModuleFormModule(\'fieldtab_'.$classId.'_'.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].'\','
						.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].','.$fieldHtmls[TDStaticDefined::$PARAM_MODULE_ROW_PKID].','.$fieldHtmls['ntableModuleId'].',\'0'.$fieldHtmls["appendUrl"].'\')'; 
					}
					$onclick = ' onclick="'.$curClickFun.'" ';
				}
				$isactive = isset($_GET["vlasttb"]) ? $rowIndex == count($fieldHtmlArray) : $rowIndex == 1;
				if($isactive) { $jsAutoRunFun = $curClickFun.';'; }	
				echo '<li '.($isactive ? 'class="active"' : '').'><a href="#fieldtab_'.$classId.'_'.
				(is_array($fieldHtmls) && isset($fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '').'" '.$onclick.' data-toggle="tab">'.$tabTitle.'</a></li>'; 
				$rowIndex++;
			}
		}

		if($useTab) {
			echo '
			</ul>
				<div class="tab-content">'; 
					$rowIndex = 1;
		}
		$saveButonIsSet = false;
		foreach($fieldHtmlArray as $classId => $fieldHtmls) { 
			if(!is_numeric($classId)) { $classId = "gridview".$rowIndex; }
			//过滤从属子分类
			if(is_numeric($classId) && $classId > 0 && TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class,$classId,"min(pid)") > 0) {
				continue;
			}
			if($useTab) {
				$isactive = isset($_GET["vlasttb"]) ? $rowIndex == count($fieldHtmlArray) : $rowIndex == 1;
				echo '<div class="tab-pane '.($isactive ? "active" : '').'" id="fieldtab_'.$classId.'_'.(is_array($fieldHtmls) && 
				isset($fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $fieldHtmls[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '').'">';
			}
			if(is_numeric($classId)) {
				echo '<div class="box"><div class="box-header well"><h2>编辑</h2>'.($saveButonIsSet ? '' : '&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-success" onclick="loadingStart()">'.
				TDLanguage::$common_button_save.'</button>').'<div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div></div><div class="box-content row-fluid sortable">';

				self::getFieldHtmls($fieldHtmls,self::$fieldHtmlTypes_BaseEditField,array("classId"=>$classId));
				//加载子分类
				self::getFieldHtmls($fieldHtmlArray,self::$fieldHtmlTypes_ChildClassEditField,array("classId"=>$classId));
				echo '</div></div>';

				self::getFieldHtmls($fieldHtmls,self::$fieldHtmlTypes_FormInnerGridvew,array("readonly"=>0,"classId"=>$classId));
				$saveButonIsSet = true;
			}
			if($useTab) {		
				echo '</div>'; 
				$rowIndex++;
			}
		}
		if($useTab) {
			echo '
				</div>
			</div>';
			echo '<script> 
				var timerObj = setTimeout("reSetTableWH()",500);
				function reSetTableWH() {
				clearTimeout(timerObj);	'.$jsAutoRunFun.'	
				var tabpanes = $(".tab-pane"); var lastMaxPanWidth=400; lastMaxPanHeight=300;
				for(var i=0; i<tabpanes.length; i++){ 
					var checkHeight = tabpanes.filter(":eq("+i+")").height(); 
					if(tabpanes.filter(":eq("+i+")").find(".grid-view").length > 0) {
						checkHeight -= 120;		
					} 
					if(checkHeight > lastMaxPanHeight) { 
						lastMaxPanHeight = checkHeight; 
					}
					var checkWidth = tabpanes.filter(":eq("+i+")").width(); 
					if(checkWidth > lastMaxPanWidth) { 
						lastMaxPanWidth = checkWidth;
					} 
				}
				for(var i=0; i<tabpanes.length; i++){ 
					
					tabpanes.filter(":eq("+i+")").css("min-height",lastMaxPanHeight); 
				} 
			}
			</script>';
		}
	}	

	public static $fieldHtmlTypes_BaseEditField = 1; 
	public static $fieldHtmlTypes_ChildClassEditField = 2; 
	public static $fieldHtmlTypes_FormInnerGridvew = 3; 
	public static function getFieldHtmls($fieldHtmls_or_fieldHtmlArray,$type,$params=array()) {
		if($type == self::$fieldHtmlTypes_BaseEditField) {
			$classId  = $params["classId"];
			$classRow = is_numeric($classId) && $classId > 0 ? TDModelDAO::queryRowByPk(TDTable::$too_table_column_class,$classId) : array();
			echo '<div class="box-content span'.(!empty($classRow) ? $classRow["span_num"] : 12).'">';	
			foreach($fieldHtmls_or_fieldHtmlArray as $htIndex => $html) { 
				if(is_numeric($htIndex) && $htIndex >= TDStaticDefined::$formInnerGridviewIndexId) {
					continue;
				} else { 
					echo $html;
				} 
			}
			echo '</div>';
		} else if($type == self::$fieldHtmlTypes_ChildClassEditField) {
			$classId  = $params["classId"];
			foreach($fieldHtmls_or_fieldHtmlArray as $childClassId => $childfieldHtmls) { 
				if(is_numeric($childClassId) && $childClassId > 0) {
					$chilClassdRow = TDModelDAO::queryRowByPk(TDTable::$too_table_column_class,$childClassId);
					if(!empty($chilClassdRow) && $chilClassdRow["pid"] == $classId) {
						echo '<div class="box-content span'.$chilClassdRow["span_num"].'">'; 
						foreach($childfieldHtmls as $childhtIndex => $childhtml) { 
							if(is_numeric($childhtIndex) && $childhtIndex >= TDStaticDefined::$formInnerGridviewIndexId) {
								continue;
							} else { 
								echo $childhtml;
							} 
						}
						echo '</div>';
					}
				}
			}	
		} else if($type == self::$fieldHtmlTypes_FormInnerGridvew) {
			$classId  = $params["classId"]; 
			foreach($fieldHtmls_or_fieldHtmlArray as $htIndex => $html) { 
				if(is_numeric($htIndex) && $htIndex >= TDStaticDefined::$formInnerGridviewIndexId) {
						unset($_REQUEST);
						// 搜索 js 函数 formLoadModuleFormModule 参考
						$_GET[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] = $html[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID];
						$_GET[TDStaticDefined::$PARAM_MODULE_ROW_PKID] = $html[TDStaticDefined::$PARAM_MODULE_ROW_PKID];
						$_GET['moduleId'] = $html['ntableModuleId']; 
						$_GET['mnInd'] = 0;	
						$_GET['topmnInd'] = 0;
						$_GET[TDStaticDefined::$pageLayoutType] = TDStaticDefined::$pageLayoutType_inner; 
						$_GET[TDStaticDefined::$PARAM_MODULE_READONLY] = isset($params["readonly"]) ? $params["readonly"] : 1;
						$tmpgr = new TDGridView(new CController($classId."_".$htIndex),$html["ntableModuleId"]); 
						echo '<div class="box"><div class="box-header well"><h2>'.TDModelDAO::queryScalarByPk(TDTable::$too_module_formmodule,$html[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID],"formtab_title")
						.'</h2><div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div></div><div class="box-content row-fluid sortable">';
						echo $tmpgr->createGridView();
						echo '</div></div>';
				} 
			}	
		} 
	}

	public static function createFieldIdOrName($tableColumnId,$belongOrderColumnIds=null,$isName = false) {
		$id = TDStaticDefined::$formFieldID.$tableColumnId;
		$name = TDStaticDefined::$formFieldName.$tableColumnId;
		if(!empty($belongOrderColumnIds)) {
			$id = TDStaticDefined::$formFieldID.str_replace(",",TDStaticDefined::$formFieldColumnBelongToOrder,$belongOrderColumnIds)
			.TDStaticDefined::$formFieldColumnBelongToOrder.$tableColumnId;
			$name = TDStaticDefined::$formFieldName.str_replace(",",TDStaticDefined::$formFieldColumnBelongToOrder,$belongOrderColumnIds)
			.TDStaticDefined::$formFieldColumnBelongToOrder.$tableColumnId; 
		}
		return $isName ? $name : $id;
	}
	public static function createFieldIdOrNameByLadderColumn($ladderColumn,$isName = false) {
		$id = TDStaticDefined::$formFieldID.str_replace(",",TDStaticDefined::$formFieldColumnBelongToOrder,$ladderColumn);
		$name = TDStaticDefined::$formFieldName.str_replace(",",TDStaticDefined::$formFieldColumnBelongToOrder,$ladderColumn); 
		return $isName ? $name : $id;
	}
	public static function getValueFromForm($tableName,$columnName,$model,$belongOrderColumnIds=null) {
		$tbcolumnId = TDTableColumn::getColumnIdByTableAndColumnName($tableName,$columnName);
		$value = TDField::getFormPostData(TDField::createFieldIdOrName($tbcolumnId,$belongOrderColumnIds,TRUE));
		if(empty($value)) {
			$value = TDFormat::getModelAppendColumnValue($model,TDTableColumn::getColumnAppendStr($tbcolumnId,$belongOrderColumnIds)); 
		}	
		return $value;
	}
	
	//form	
	public static function getColumnFormData($tableColumnId,$belongOrderColumnIds=null,$model=null) {
		$columnData = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId);
		$id = self::createFieldIdOrName($tableColumnId, $belongOrderColumnIds);
		$name = self::createFieldIdOrName($tableColumnId, $belongOrderColumnIds,true);
		$dataValueStr = TDTableColumn::getColumnAppendStr($tableColumnId,$belongOrderColumnIds);
		$value = '';
		if(TDTableColumn::checkIsCustomColumn($tableColumnId)) {
			if(isset($_POST[TDStaticDefined::$formModelName]) && isset($_POST[TDStaticDefined::$formModelName][$name])) {
				$value = $_POST[TDStaticDefined::$formModelName][$name]; 
			} else if(!empty($columnData["formula"])) { 
				$value = Fie_formula::computeFormula($model,$columnData["name"]);
			}
		} else {
			$value = $model instanceof CActiveRecord ? TDFormat::getModelAppendColumnValue($model,$dataValueStr) : $model;
		}
		$displayValidate = true; if(!empty($columnData["display_validate"])) { $displayValidate = Fie_formula::getValue($model,$columnData["display_validate"]); }
		return array(
			'id' => $id,
			'name' => TDStaticDefined::$formModelName.'['.$name.']',
			'baseName' => $name,
			'value' => $value,
			'label' => $columnData["label"],
			'columnName' => $columnData["name"],
			'allowEmpty' => $columnData["allow_empty"] == 1 || $columnData["default_value"] == 'Empty String' ? true : false,
			'tableName' => TDTableColumn::getTableDBName($columnData["table_collection_id"]),
			'dbType' => $columnData["db_type"],
			'columnInputId' => $columnData["table_column_input_id"],
		    	'displayValidate' => $displayValidate,
		    	'columnData' => $columnData,
		    	'in_form_notnull' => $columnData["in_form_notnull"],
		    	'input_expand_type' => $columnData["input_expand_type"],
		    	'formula_remark' => $columnData["formula_remark"],
		    	'input_front_txt' => empty($columnData["input_front_txt"]) ? "" : Fie_formula::getValue($model,$columnData["input_front_txt"]), 
		    	'input_back_txt' => empty($columnData["input_back_txt"]) ? "" : Fie_formula::getValue($model,$columnData["input_back_txt"]),
		);	
	}
	//saveData
	public static function getFormPostData($fieldName) {
		$value = null;
		if(isset($_POST[TDStaticDefined::$formModelName][$fieldName])) {
			$value = $_POST[TDStaticDefined::$formModelName][$fieldName];
			if(is_string($value)) {
				$value = trim($value);
			}
		}	
		return $value;
	}
	
	public static function getOnbeChangeFormData($ladderColumn) {
		$value = null;
		$array = isset($_POST[TDStaticDefined::$formModelName]) ? $_POST[TDStaticDefined::$formModelName] : array();
		$array = array_keys($array);
		$ladderFieldName = TDField::createFieldIdOrNameByLadderColumn($ladderColumn,true);
		if(in_array($ladderFieldName,$array)) {
			$value = self::getFormPostData($ladderFieldName);
		}
		return $value;	
	}

	public static function getFormatPid($appColumnModel,$appColumnId) {
		$result = "";
		$pidrow = TDModelDAO::queryRowByCondtion(TDTable::$too_table_column,"table_collection_id=".TDTableColumn::getColumnTableCollectionId($appColumnId).
		" and table_column_input_id=".Fie_pid::getInputTypeId()." and pid_view_columnid=".$appColumnId,"id,name");
		if(!empty($pidrow)) {
			$pid = $pidrow["name"];
			$pidNum = $appColumnModel->$pid; 
			while(!empty($pidNum)) {
				$strColumn = TDTableColumn::getColumnDBName($appColumnId);
				$parentRow = TDModelDAO::queryRowByPk($appColumnModel->tableName,$pidNum,$strColumn.','.$pid);
				if(!empty($parentRow)) {
					$result = $parentRow[$strColumn]."=>".$result;
					$pidNum = $parentRow[$pid];
				} else {
					break;
				}
			}
		}
		$lastStr = TDField::gettValueByFormatView($appColumnModel,$appColumnId);
		$result = !empty($result) ? "<span title='".$result.$lastStr."'>".$lastStr."</span>" : $lastStr; 
		return $result;
	}
	
	//like view get value
	public static function gettValueByFormatView($model,$columnId) {
		$params = array(
			'tableColumnId'=>$columnId,
			'belongOrderColumnIds' =>"",
			'model' => $model,
		);
		$inputType = TDTableColumn::getInputTypeByColumnId($columnId);		
		if(method_exists($inputType,'viewData')) {
			$fie = new $inputType();
			$viewColumn = $fie->viewData($params);	
			if(!empty($viewColumn)) {
				$tmpModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$columnId,"group_id,formula,name,column_type");
				$groupId = !empty($tmpModel["group_id"]) ? $tmpModel["group_id"] : 0; 
				if($tmpModel["column_type"] == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN || !empty($tmpModel["formula"])) { 
					$viewColumn["value"] = Fie_formula::computeFormula($model,$tmpModel["name"]);
				}
				return $viewColumn["value"]; 
			}
		}	
	}
	//view
	public static function getViewGroupColumns($model,$moduleId) {
		$emptyFormEditArr = TDModelDAO::getModel(TDTable::$too_module_formEdit)->attributes; 
		$useColumns = array();
		if($moduleId == TDStaticDefined::$mysqlCommonModuleId) {
			$baseColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".TDTableColumn::getTableCollectionID($model->tableName)." order by `order`","id");
			foreach($baseColumns as $tmpCols) {
				if(!TDTableColumn::checkIsCustomColumn($tmpCols["id"])) {
					$tmpRow = $emptyFormEditArr;
					$tmpRow["module_id"] = $moduleId;
					$tmpRow["table_column_id"] = $tmpCols["id"];
					$useColumns[] = $tmpRow;		
				}
			}
		} else {
			$useColumns = TDModelDAO::queryScalarByPk(TDTable::$too_module,intval($moduleId),"notuse_sys_form") == 0 ? TDModelDAO::queryAll(TDTable::$too_module_formEdit,'`module_id`=\''.$moduleId.'\' and `use_view`=1 order by `order`') : array();
		}
		$goupColumns = array();
		$modelUseGroup = TDModelDAO::queryRowByPk(TDTable::$too_module,$moduleId,"form_use_group");
		$useGroup = !empty($modelUseGroup) && $modelUseGroup["form_use_group"] == 1 ? true : false;
		foreach($useColumns as $row) {
			if(!TDPermission::checkQueryPermission($row["table_column_id"])) {
				continue;	
			}
			$params = array(
				'tableColumnId'=>$row["table_column_id"],
				'belongOrderColumnIds' => $row["belong_order_column_ids"],
				'model' => $model,
			);
			$inputType = TDTableColumn::getInputTypeByColumnId($params['tableColumnId']);		
			if(method_exists($inputType,'viewData')) {
				$fie = new $inputType();
				$viewColumn = $fie->viewData($params);	
				if(!empty($viewColumn)) {
					$tmpModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$row["table_column_id"],'`group_id`,`column_type`,`formula`,`name`');
					$groupId = $useGroup && !empty($tmpModel["group_id"]) ? $tmpModel["group_id"] : 0; 
					if(!isset($goupColumns[$groupId])) {
						$goupColumns[$groupId] = array(); 	
					}
					if($tmpModel["column_type"] == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN || !empty($tmpModel["formula"])) { 
						$viewColumn["value"] = Fie_formula::computeFormula($model,TDTableColumn::getColumnAppendStr($row["table_column_id"],$row["belong_order_column_ids"]));
					}
					$goupColumns[$groupId][] = $viewColumn; 
				}
			}	
		}
		// reorder 
		if(count($goupColumns) > 1) {
			$tmpRows = TDModelDAO::queryAll(TDTable::$too_table_column_class,'`table_id`='.TDTableColumn::getTableCollectionID($model->tableName()).' order by `order`','id');
			$keysClassId = array_keys($goupColumns);
			$newGoupColumns = array();
			foreach($tmpRows as $tmp) { 
				if(in_array($tmp["id"],$keysClassId)) {
					$newGoupColumns[$tmp["id"]] = $goupColumns[$tmp["id"]];
				}
			}
			$newKeys=array_keys($newGoupColumns);
			foreach($keysClassId as $tmpId) { 
				if(!in_array($tmpId,$newKeys)) {
					$newGoupColumns[$tmpId] = $goupColumns[$tmpId];
				}
			}	
			$goupColumns = $newGoupColumns;
		}
		// 加入tab项的 module_formmodule 
		$moduleFormModules = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId.
		' and `is_activate`=1 and `is_forread`=1 and order_type=0 order by `order`,id','id,tab_display_condition,formtab_title,ntable_module_id,page_type,page_url_get_array');
		foreach($moduleFormModules as $item) {
			if(!empty($item["tab_display_condition"])) {
				$dispaly = Fie_formula::getValue($model,$item["tab_display_condition"]);
				if(!$dispaly) { continue; }
			}
			$goupColumns[$item["formtab_title"]] = array(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID=>$item["id"],
				TDStaticDefined::$PARAM_MODULE_ROW_PKID=>$model->primaryKey,'ntableModuleId'=>$item["ntable_module_id"],TDStaticDefined::$PARAM_MODULE_READONLY=>1,
				'pageType'=>$item["page_type"],"appendUrl"=>self::getAppendUrl($model,$item["page_url_get_array"])); 
		}	
		// 加入嵌入表单的 module_formmodule
		$moduleFormModules = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId.
		' and `is_activate`=1 and `is_forread`=1 and order_type=1 order by `order`,id','id,tab_display_condition,formtab_title,ntable_module_id,page_type,page_url_get_array');
		$startEnterNum = TDStaticDefined::$formInnerGridviewIndexId;
		foreach($moduleFormModules as $item) {
			if(!empty($item["tab_display_condition"])) {
				$dispaly = Fie_formula::getValue($model,$item["tab_display_condition"]);
				if(!$dispaly) { continue; }
			}
			$goupColumns[$startEnterNum] = array(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID=>$item["id"],
			TDStaticDefined::$PARAM_MODULE_ROW_PKID=>$model->primaryKey,'ntableModuleId'=>$item["ntable_module_id"],
			'pageType'=>$item["page_type"],"appendUrl"=>self::getAppendUrl($model,$item["page_url_get_array"])); 
			$startEnterNum++;
		}
		return $goupColumns;
	}
	
}
