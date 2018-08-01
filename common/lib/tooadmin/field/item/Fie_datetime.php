<?php
class Fie_datetime extends TDField{

		public function editForm($params) {
			/*
			$columnFormData = $params['columnFormData'];
			$result = TDStaticDefined::getTmpController()->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd 00:00',),
			'htmlOptions' => array('name' => $columnFormData['name'],'id'=>$columnFormData['id']),
			'value' => (strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value']) ? 
			date("Y-m-d H:i",$columnFormData['value']) : "")
			: (!empty($columnFormData['value']) ? date("Y-m-d H:i",strtotime($columnFormData['value'])) : "")),
			),true);
			*/
			$columnFormData = $params['columnFormData'];
			$result = '<input type="text" style="width:120px;" class="Wdate" readonly="readonly" 
			value="'.(strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value']) ? 
			date("Y-m-d H:i",$columnFormData['value']) : "") : 
			(!empty($columnFormData['value']) ? date("Y-m-d H:i",strtotime($columnFormData['value'])) : "")).
			'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd H:mm\'})" id="'.$columnFormData['id'].'" name="'.$columnFormData['name'].'" />';
			/*	
			TDStaticDefined::getTmpController()->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd 00:00',),
			'htmlOptions' => array('name' => $columnFormData['name'],'id'=>$columnFormData['id']),
			'value' => (strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value']) ? 
			date("Y-m-d H:i",$columnFormData['value']) : "")
			: (!empty($columnFormData['value']) ? date("Y-m-d H:i",strtotime($columnFormData['value'])) : "")),
			),true);
			*/
			/*
			$result = CHtml::textField($columnFormData['name'],(strpos($columnFormData['dbType'],'int') !== false ? (!empty($columnFormData['value']) ? 
			date("Y-m-d H:i",$columnFormData['value']) : "") : (!empty($columnFormData['value']) ? date("Y-m-d H:i",strtotime($columnFormData['value'])) : "")),array('id'=>$columnFormData['id'],'class'=>'Wdate','onclick'=>'WdatePicker()'));
			*/
			return $result;
		}

		public function gridView($params) {
			$columnData = $params["columnData"];
			$result = '(strpos("'.$columnData['dbType'].'","int") !== false ? (!empty('.$columnData['value'].') 
			? date("Y-m-d H:i",'.$columnData['value'].') : "")
			: (!empty('.$columnData['value'].') ? date("Y-m-d H:i",strtotime('.$columnData['value'].')) : ""))';
			return $result;
		}
		
		public function viewData($params) {
			$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
			$value = strpos($columnData['dbType'],'int') !== false ? (!empty($columnData['value']) 
			? date("Y-m-d H:i",$columnData['value']) : "")
			: (!empty($columnData['value']) ? date("Y-m-d H:i",strtotime($columnData['value'])) : "");
			$result = array(
				'name' => $columnData['label'],
				'type' => 'raw',
				'value' => $value,
			);
			return $result;
		}
		public function viewHtml($params) {
			return strpos(TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params["tableColumnId"],"db_type"),'int') !== false ? (!empty($params['value']) 
			? date("Y-m-d H:i",$params['value']) : "") : (!empty($params['value']) ? date("Y-m-d H:i",strtotime($params['value'])) : "");
		}

		public function saveData($params) {
			$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
			if(!is_null($value)) {
				$validate = true;
				if(!empty($value)) {
					if(!TDValidateCollection::isDatetime($value)) {
						$params['model']->addError($params['fieldId'],array(TDLanguage::$validate_msg_datetime_error));
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
			$fieldID = 'searchID'.time();
			$result = '<input type="text" style="width:120px;" class="Wdate" readonly="readonly" 
			value="'.$params['value'].'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd H:mm\'})" id="'.$fieldID.'" name="'.$params['fieldName'].'" />';
			/*	
			$obj = new CController('');
			$result = $obj->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd 00:00',),
			'htmlOptions' => array('id'=>$fieldID,'name'=>$params['fieldName'],'style'=>TDCommonCss::$search_input_style),
			'value' => $params['value']),true);
			$result .= '<script>jQuery("#'.$fieldID.'").datepicker(jQuery.extend({showMonthAfterYear:false},
			jQuery.datepicker.regional["zh_cn"],{"dateFormat":"yy-mm-dd 00:00"}));</script>';
			*/
			return $result;
		}

		public function editTableColumn($params) {
			$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']
			."\" style=\"width:105px;margin-bottom: 0px;\" timeajax=\"1\" />";
			return $result;//使用普通的input

			/*
			$fieldID = 'tmpDatetimeID'.TDCommon::getIncreaseNum();
			$result = TDStaticDefined::getTmpController()->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => Yii::app()->params->date_language,
			'options' => array('dateFormat' => 'yy-mm-dd 00:00',),
			'htmlOptions' => array('style'=>'width:106px;margin-bottom: 0px;','name' =>$fieldID,
			'urlstr'=>$params['urlstr'],'expand'=>'datetime','timeajax'=>'1'),
			'value' => $params['value']),true);
			return $result;
			*/
		}
		
}
