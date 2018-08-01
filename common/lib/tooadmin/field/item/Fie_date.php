<?php

class Fie_date extends TDField {

		public function editForm($params) {
			/*
			$columnFormData = $params['columnFormData'];
			$result = CHtml::textField($columnFormData['name'],(strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value'])
			? date("Y-m-d",$columnFormData['value']) : "") : $columnFormData['value']),array('id'=>$columnFormData['id'],'class'=>'Wdate','onclick'=>'WdatePicker()','readonly'=>'readonly'));
			return $result;
			*/

			$columnFormData = $params['columnFormData'];
			$result = '<input type="text" style="width:100px;" class="Wdate" readonly="readonly" 
			value="'.(strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value']) ? 
			date("Y-m-d",$columnFormData['value']) : "") : 
			(!empty($columnFormData['value']) ? date("Y-m-d",strtotime($columnFormData['value'])) : "")).
			'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" id="'.$columnFormData['id'].'" name="'.$columnFormData['name'].'" />';
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = '(strpos("'.$columnData['dbType'].'","int") !== false ? (!empty('.$columnData['value'].')
			? date("Y-m-d",'.$columnData['value'].') : "") : '.$columnData['value'].')';
			return $result;
		}

		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$value = strpos($columnData['dbType'],'int') !== false ? (!empty($columnData['value'])
			? date("Y-m-d",$columnData['value']) : "") : $columnData['value'];	
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $value,
			);
			return $result;
		}
		public function viewHtml($params) {
			return strpos(TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"db_type"),'int') !== false ? (!empty($params['value']) ? date("Y-m-d",$params['value']) : "") : $params['value'];
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				$validate = true;
				if(!empty($value)) {
					if(!TDValidateCollection::isDatetime($value)) {
						$params['model']->addError($params['fieldId'],array(TDLanguage::$validate_msg_date_error));
						$validate = false;
					}
				}
				if(strpos($params['dbType'],'int') !== false && $validate) {
					$value = empty($value) ? null : strtotime($value);
				}
				TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],$value);
			}	
		}

		public function search($params) {
			/*
			$fieldID = 'searchID'.time();
			$obj = new CController('');
			$result = $obj->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd',),
			'htmlOptions' => array('id'=>$fieldID,'name'=>$params['fieldName'],'style'=>TDCommonCss::$search_input_style),
			'value' => $params['value']),true);
			$result .= '<script>jQuery("#'.$fieldID.'").datepicker(jQuery.extend({showMonthAfterYear:false},
			jQuery.datepicker.regional["zh_cn"],{"dateFormat":"yy-mm-dd"}));</script>';
			return $result;
			*/
			$fieldID = 'searchID'.time();
			$result = '<input type="text" style="width:100px;" class="Wdate" readonly="readonly" 
			value="'.$params['value'].'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" id="'.$fieldID.'" name="'.$params['fieldName'].'" />';
			return $result;
		}

		public function editTableColumn($params) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:80px;margin-bottom: 0px;\" timeajax=\"1\" />";
			return $result;//使用普通的input
			
			$fieldID = 'tmpDateID'.TDCommon::getIncreaseNum();
			///*
			$result = TDStaticDefined::getTmpController()->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd',),
			'htmlOptions' => array('style'=>'width:80px;margin-bottom: 0px;','readonly'=>'readonly',
			'name' =>$fieldID,'urlstr'=>$params['urlstr'],'expand'=>'date','timeajax'=>'1'),
			'value' => $params['value']),true);
			//*/
			/*
			$result = "<input type=\"text\"  urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:80px;margin-bottom: 0px;\" timeajax=\"1\" class=\"Wdate\" onclick=\"WdatePicker()\" />";//readonly=\"readonly\" 
			*/
			return $result;	
		}
}
