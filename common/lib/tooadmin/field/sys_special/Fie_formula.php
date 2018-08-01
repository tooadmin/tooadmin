<?php
class Fie_formula extends TDField {

		public static function getInputTypeId() {
			return 25;
		}
		
		public static function getValue($model,$formula) {
			$formula = str_replace("\'",'"', $formula);
			$data = $model; @eval($formula);
			if(isset($VAL)) { return $VAL; } else { return null; }
		}
		
		public static $validateCodeMsg = 0;
		public static function validateFormula($formula,$dataTableId = 0,$modelTableId=0,$useVAL = true,$doEval=true) {
			self::$validateCodeMsg = 0;
			$formula = trim($formula);
			if(empty($formula)) { return self::$validateCodeMsg; }
			//允许为空,因为普通字段也可以设置公式
			///if(empty($formula)) { return TDLanguage::$custom_code_empty; }
			if($formula[strlen($formula)-1] !== ';' && $formula[strlen($formula)-1] !== '}') { 
				return TDLanguage::$custom_missing_end_code; 
			}
			if(!empty($dataTableId)) {
				$data = TDModelDAO::getModel(TDTableColumn::getTableDBName($dataTableId));
				TDFormat::parseAttributeNullToEmpty($data);
			}
			if(!empty($modelTableId)) {
				$model = TDModelDAO::getModel(TDTableColumn::getTableDBName($modelTableId));
				TDFormat::parseAttributeNullToEmpty($model);	
			}
			if($doEval) {
				/*
				//字段不存在的验证待编写
				$defineFun = "";
				if(!function_exists("customError")) {
					$defineFun = 'function customError($error_level,$error_message) { Fie_formula::$validateCodeMsg = $error_message;}';
				}
				$formula = $defineFun.'set_error_handler("customError",E_ALL);'.$formula.'$CODE_RUN_PASS="pass";';

				//为避免在验证的过程中出现数据库的更新，所以加事物回滚
				$useTran = false;
				try{ $tran = ->beginTransaction(); $useTran = true; }  catch (Exception $e) { }
				eval($formula);	
				if($useTran) { $tran->rollback(); }
				*/
				$CODE_RUN_PASS="pass";	
			} else {
				$CODE_RUN_PASS="pass";	
			}	
			if(Fie_formula::$validateCodeMsg !== 0) {
				return Fie_formula::$validateCodeMsg;
			}
			if(isset($CODE_RUN_PASS) && $CODE_RUN_PASS == "pass") {
				if($useVAL) {
					if(strpos($formula,'$VAL') === false) {
						return TDLanguage::$custom_missing_result_val;
					}
				}
				return Fie_formula::$validateCodeMsg;
			} else {
				return TDLanguage::$custom_syntax_error;
			}
		}

		//排序问题下一步再思考
		private static function createLeftJoinSQL($appendColumnName,$lastTbAsName = 't',$exceptAppendColumnNameArray = array()) {
			$resultLeftJoin = '';
			$exceptAsNameArray = array();
			foreach($exceptAppendColumnNameArray as $appItem) {
				$tmpArr= explode("->",$appItem);
				for($i=0; $i<count($tmpArr)-1; $i++) {
					$exceptAsNameArray[] = $tmpArr[$i];	
				}	
			}
			$appendArra = explode("->",$appendColumnName);
			for($i=0; $i<count($appendArra)-1; $i++) {
				if(!in_array($appendArra[$i],$exceptAsNameArray)) {
					$tmpStr = explode(TDStaticDefined::$foreignKey_tableName,$appendArra[$i]);
					$tmpTableObj = TDTable::getTableObj($tmpStr[1]);
					$resultLeftJoin .= ' left join `'.$tmpStr[1].'` as `'.$appendArra[$i]
					.'` on `'.$lastTbAsName.'`.`'.$tmpStr[0].'`=`'.$appendArra[$i].'`.`'.$tmpTableObj->primaryKey.'` ';
				}
				$lastTbAsName = $appendArra[$i];
			}	
			return $resultLeftJoin;	
		}
		public static function formatFormulaToOrderAndLeftJoinSQL($modelTableName,$appendColumnName) {
			$resultFormula = '';
			$resultLeftJoin = '';
			$exceptAppendColumnNameArray = array();
			$appendArra = explode("->",$appendColumnName);
			$columnName = $appendArra[count($appendArra)-1];
			$tableName = $modelTableName;
			if(count($appendArra) > 1) {
				$tableName = $appendArra[count($appendArra)-2];
				$tableName = explode(TDStaticDefined::$foreignKey_tableName,$tableName);
				$tableName = $tableName[1];
				$resultLeftJoin .= self::createLeftJoinSQL($appendColumnName);	
				$exceptAppendColumnNameArray[] = $appendColumnName;
			}
			$columnModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,TDTableColumn::getColumnIdByTableAndColumnName($tableName,$columnName),'formula');
			if(!empty($columnModel)) {
				$formula = $columnModel["formula"];
				$tableId = TDTableColumn::getTableCollectionID($tableName);	
				if(self::validateFormula($formula,$tableId) === 0) {
					$columnIdArray =  array();
					$columnValueArray = array(); // cidb => value
					foreach($columnIdArray as $columnId) {
						if(strpos($columnId,',') === false && count($appendArra) == 1) {
							$tmpColumnName = TDTableColumn::getColumnDBName($columnId);
							///$columnValueArray[self::getTbColumnVariable($columnId)] = '`t`.`'.$tmpColumnName.'` ';
						} else if(strpos($columnId,',') === false  && count($appendArra) > 1) { 
							$tmpColumnName = '`'.$appendArra[count($appendArra)-2].'`.`'.TDTableColumn::getColumnDBName($columnId).'`';	
							///$columnValueArray[self::$variableStartKey.$columnId.self::$variableEndKey] = $tmpColumnName;
						} else {
							$tmpColumns = explode(",",$columnId);
							$belongColumnIds = '';
							for($ci=0; $ci<count($tmpColumns)-1; $ci++) {
								if(!empty($belongColumnIds)) {
									$belongColumnIds .= ',';
								}
								$belongColumnIds .= $tmpColumns[$ci];
							}
							$tmpAppendName = TDTableColumn::getColumnAppendStr($tmpColumns[count($tmpColumns)-1],$belongColumnIds,true);
							$tmpArray = explode("->",$tmpAppendName);
							$tmpColumnName = '`'.$tmpArray[count($tmpArray)-2].'`.`'.$tmpArray[count($tmpArray)-1].'`';	
							//?????????????????????????????/该函数待修改
							///$columnValueArray[self::getTbColumnVariable($columnId)] = $tmpColumnName;
							//formula left join sql
							$resultLeftJoin .= self::createLeftJoinSQL($tmpAppendName,
							count($appendArra) > 1 ? $appendArra[count($appendArra)-2] : 't',$exceptAppendColumnNameArray);
							$exceptAppendColumnNameArray[] = $tmpAppendName;
						}
					}
					foreach($columnValueArray as $keyid => $value) {
						$formula = str_replace($keyid,$value,$formula);
					}	
					$resultFormula = $formula;
				}
			}
			return array('formula'=>$resultFormula,'leftJoin'=>$resultLeftJoin);
		}
		
		public static function computeFormula($model,$columnName) {
			$customCode = "";
			$queryTableName = TDFormat::getBaseColumnTableNameFromAppendName($columnName,$model->tableName);
			$queryColumnName = TDFormat::getBaseColumnNameFromAppendName($columnName);
			$columnModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column, TDTableColumn::getColumnIdByTableAndColumnName($queryTableName,$queryColumnName),'formula');
			if(!empty($columnModel)) { $customCode = $columnModel["formula"]; }
			if(empty($customCode)) { 
				return ''; 
			} else {
				if(strrpos($columnName,"->") !== false) {	
					$queryModeStr = substr($columnName,0,strrpos($columnName,"->"));
					$data = TDFormat::getModelAppendColumnValue($model,$queryModeStr);
				} else {
					$data = $model;
				} 
				if(empty($data)) {
					return '';
				} else {
					return self::getValue($data, $customCode);
				}
			}
		}
	
		public function editForm($params) {
			$columnFormData = $params['columnFormData'];
			$fieldParam = array('id'=>$columnFormData['id']); 
			$style = "";
			if(!empty($columnFormData["columnData"]["width"])) {
				$style .= "width:".$columnFormData["columnData"]["width"]."px;"; 
			}
			if(!empty($columnFormData["columnData"]["height"])) {
				$style .= "height:".$columnFormData["columnData"]["height"]."px;"; 
			}
			if(!empty($style)) {
				$fieldParam["style"] = $style;
			}
			$jsHtml = '<script> jQuery(function($) { var editor_'.$columnFormData['id']
			.' = CodeMirror.fromTextArea(document.getElementById("'.$columnFormData['id'].'"), { lineNumbers: true, viewportMargin: Infinity }); });</script>';
			return CHtml::textArea($columnFormData['name'],$columnFormData['value'],$fieldParam).$jsHtml;	
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			return $columnData['value'];
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$dataValueStr = TDTableColumn::getColumnAppendStr(TDTableColumn::getColumnIdByTableAndColumnName(
			TDTable::$too_table_column,'table_collection_id'),$params['belongOrderColumnIds']);
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $columnData['value'],
			);
			return $result;	
		}
		public function viewHtml($params) {
			return $params['value'];
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
				if(!empty($value)) {
					$dataTableId = 0;
					$modelTableId = 0;
					$useVAL = false;
					$tableName = TDTableColumn::getColumnTableDBName($params["tableColumnId"]); 	
					$columnName = $params["columnName"];
					if($tableName == TDTable::$too_table_column) {
						//同时验证$data,$VAL,php语法,其中$data为字段table_collection_id的值的model
						$validataArray = array(
					    	'formula', //运算公式
					    	'map_condition',//map table查询条件
					    	'display_validate', //是否显示验证
						'static_array',//静态数组
						'edit_static_array',//编辑表单时使用的数组
						'input_front_txt',
						'input_back_txt',
						);	
						$onlyValiVAL = array('save_expande_path');
						if(in_array($columnName,$validataArray)) {
							$useVAL = true;
							$dataTableId = TDField::getValueFromForm(TDTable::$too_table_column,'table_collection_id',$params["model"],$params["belongOrderColumnIds"]);
						} else if(in_array($columnName,$onlyValiVAL)) {
							$useVAL = true;
						}
					} else if($tableName == TDTable::$too_module) {
						$validataArray = array(//可验证$VAL,php语法
						    'default_order',//默认排序
						    'gridview_default_condition',//默认condition
						);		
						$validataArray2 = array( //验证$data,$VAL,php语法
			    				'update_button_view',//修改按钮显示条件
			    				'delete_button_view',//删除按钮显示条件
			    				'view_button_view',//查看按钮显示条件
			    				'expande_operate_button',//扩展操作按钮设置
						);
						
						if(in_array($columnName,$validataArray)) {
							$useVAL = true;
						} else if(in_array($columnName,$validataArray2)) {
							$useVAL = true;
							$dataTableId = TDField::getValueFromForm(TDTable::$too_module,'table_collection_id',$params["model"],$params["belongOrderColumnIds"]);
						} else if($columnName == "form_save_php_code" || $columnName == "before_form_set_code") {//验证$model、php语法 , model 可以读写
							$modelTableId = TDField::getValueFromForm(TDTable::$too_module,'table_collection_id',$params["model"],$params["belongOrderColumnIds"]);
						}  else if($columnName == "after_save_code" || $columnName == "form_after_commit") {//验证$model、php语法 , model 可读
							$modelTableId = TDField::getValueFromForm(TDTable::$too_module,'table_collection_id',$params["model"],$params["belongOrderColumnIds"]);
						 } else if($columnName == "gridview_query_group") {
							$useVAL = true; 
						 }
					}  else if($tableName == TDTable::$too_menu_items) {
						//可验证$VAL,php语法
						$validataArray = array(
						    'target_condition,target_join_sql',
						);		
						if(in_array($columnName,$validataArray)) {
							$useVAL = true;
						} 
					}  else if($tableName == TDTable::$too_module_formmodule) {
						if($columnName == "ntable_condition" || $columnName == "tab_display_condition") { //查询ntable的条件,验证$VAL 、php语法、$data , $data为所属一表的当前表单的数据对象
							$useVAL = true;
							$form_module_id = TDField::getValueFromForm(TDTable::$too_module_formmodule,'form_module_id',$params["model"],$params["belongOrderColumnIds"]);
							if(!empty($form_module_id)) {
								$dataTableId = TDModule::getModuleTableId($form_module_id);	
							}
						} else if($columnName == "ntable_set_code" || $columnName == "ntable_before_form_code") { 
						//保存表单时执行的php代码，支持php语法、$data ,$model ($data为所属一表的当前表单的数据对象,$model 为当前保存的表单数据对象并可读写)
							$form_module_id = TDField::getValueFromForm(TDTable::$too_module_formmodule,'form_module_id',$params["model"],$params["belongOrderColumnIds"]);
							if(!empty($form_module_id)) {
								$dataTableId = TDModule::getModuleTableId($form_module_id);	
							}
							$ntable_module_id = TDField::getValueFromForm(TDTable::$too_module_formmodule,'ntable_module_id',$params["model"],$params["belongOrderColumnIds"]);
							if(!empty($ntable_module_id)) {
								$modelTableId = TDModule::getModuleTableId($ntable_module_id);	
							}
						}
					}	
					$doEval = true;
					if(in_array($columnName,array('form_after_commit','after_save_code','before_delete','after_delete','delete_after_commit'))) {
						$doEval = false;
					}
					$code = self::validateFormula($value,$dataTableId,$modelTableId,$useVAL,$doEval);
					if($code !== 0) {
						$resultMsg = array();
						$resultMsg['specialValidateErrorFields'] = array(array('fieldID'=>$params['fieldId'],'msg'=> TDLanguage::$custom_validate_error.' '.$code));
						return $resultMsg;	
					}
				}
			}
		}

		public function search($params) {
			return null;	
		}
		
		public function editTableColumn($params) {
			return null;
		}
}
