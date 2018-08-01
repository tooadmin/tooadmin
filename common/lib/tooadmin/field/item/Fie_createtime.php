<?php
class Fie_createtime extends TDField {

		public function editForm($params) {

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
			if($params['model']->isNewRecord || (!empty($params['appendModel']) && $params['appendModel']->isNewRecord)) {
				if(strpos($params['dbType'],'int') !== false)
					TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],time());
				else 
					TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr'],date("Y-m-d H:i:s"));
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
		}
}
