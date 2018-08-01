<?php

class TDMenuWidget {
	
	//$array = array('group label' => array('key'=>'value',.....) .... );
	public static function createSelectGroupSearch($array,$htmlOptions=array()) {
		$opStr = ' ';
		foreach($htmlOptions as $key => $pro) {
			if($key == 'value') continue;
			$opStr .= $key.'="'.$pro.'" ';
		}
		$html = '
		<select data-placeholder="'.TDLanguage::$please_choose_edit_form.'" '.$opStr.' data-rel="chosen" >
		<option value="">'.TDLanguage::$please_choose.'</option>';
		foreach($array as $groupData) {
			$html .= '<optgroup label="'.$groupData['optgroup_value'].'">';	
			foreach($groupData['optgroup_items'] as $key => $value) {
				$selected = '';
				if(isset($htmlOptions['value']) && $htmlOptions['value'] == $key) {
					$selected = ' selected="selected" ';
				}
				$html .= '<option value="'.$key.'" '.$selected.' >'.$value.'</option>';
			}	
			$html .= '</optgroup>';
		}
		$html .= '</select>'; 
		return $html;	
	}

	public static function createDBTableSelectGroupSearch($htmlOptions) {
		$tableCollection = TDDataDAO::getDBTableCollection();
		$array =  array();
		foreach($tableCollection as $tbData) {
			$tmpArray = array();
			foreach($tbData['tables'] as $tbs) {
				$tmpArray[$tbs['table']] = $tbs['name'];//."  ".$tbs['table'];	
			}
			$array[] = array(
				'optgroup_key' => $tbData['type'],
				'optgroup_value' => $tbData['typeName'],
				'optgroup_items' => $tmpArray
			);
		}
		return self::createSelectGroupSearch($array,$htmlOptions);
	}

	public static $defaultChooseedTableId = 0;
	public static function createTableChoose($htmlOptions=array()) {
		/*普通的select不利于快速查找
		$result = '';
		foreach($htmlOptions as $name => $value) {
			if($name == 'value') continue;
			$result .= ' '.$name.'='.$value;
		}
		$result = '<select '.$result.' >';
		$condition = "";
		if(!TDSessionData::currentUserIsTooAdmin()) {
			$condition = "`t`.`id` not in(".TDTable::$sys_table_ids.")";
		}
		$tableRows = TDModelDAO::getModel(TDTable::$too_table_collection)->findAll($condition);
		if(empty(self::$defaultChooseedTableId) && count($tableRows) > 0) {
			self::$defaultChooseedTableId = $tableRows[0]->primaryKey;
		}
		if(!isset($htmlOptions['value']) || empty($htmlOptions['value'])) {
			$htmlOptions['value'] = self::$defaultChooseedTableId;
		}
		foreach($tableRows as $row) {
			$result .= '<option value="'.$row->primaryKey.'" '.($htmlOptions['value'] == $row->primaryKey ? 'selected="selected"' : "").' >'.$row->table.'</option>';		
		}
		$result .= '</select>';
		return $result;
		*/

		//$result = CHtml::dropDownList($htmlOptions['name'],$htmlOptions["value"]
		//,FieldRule::getStaticArray(707,null,false),$htmlOptions);

		$optDBArray = FieldRule::getOPTGroupDBArray(707,null);
		$result = TDMenuWidget::createSelectGroupSearch($optDBArray,$htmlOptions);

		return $result;

		$result = '';
		foreach($htmlOptions as $name => $value) {
			if($name == 'value') continue;
			$result .= ' '.$name.'='.$value;
		}
		$result = '<select '.$result.' class="chzn-done" data-placeholder="-----请选择----" empty="---请选择---" data-rel="chosen" >';
		$result .= '<optgroup label="数据表">'; 
		$condition = "";
		if(!TDSessionData::currentUserIsTooAdmin()) {
			$condition = "`t`.`id` not in(".TDTable::$sys_table_ids.")";
		}
		$tableRows = TDModelDAO::queryAll(TDTable::$too_table_collection,$condition);
		if(empty(self::$defaultChooseedTableId) && count($tableRows) > 0) {
			self::$defaultChooseedTableId = $tableRows[0]["id"];
		}
		if(!isset($htmlOptions['value']) || empty($htmlOptions['value'])) {
			$htmlOptions['value'] = self::$defaultChooseedTableId;
		}
		foreach($tableRows as $row) {
			$result .= '<option value="'.$row["id"].'" '.($htmlOptions['value'] == $row["id"] ? 'selected="selected"' : "").' >'.$row["table"].'</option>';		
		}
		$result .= '</optgroup></select>';
		return $result;
	} 
}
