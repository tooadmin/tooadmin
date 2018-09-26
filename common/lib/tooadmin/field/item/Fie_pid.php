<?php

class Fie_pid extends TDField {

	public static function getInputTypeId() {
		return 15;
	}

	public static function getInputTypeStr() {
		return 'pid';
	}

	public function editForm($params) {
		$columnFormData = $params['columnFormData'];
		$array = FieldRule::getPidParam($params['tableColumnId']);
		$code = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$params['tableColumnId'],"map_condition");
		$map_condition = '';
		if (!empty($code) && !empty($params['model'])) {
			$map_condition = Fie_formula::getValue($params['model'],$code);
		}
		$id = $array['id'];
		$result = TDStaticDefined::getTmpController()->widget('system.web.widgets.CTreeView'
				, array('data' => TDDataDAO::getPidTreeArray($columnFormData['name'], intval($params['model']->$id)
					, $columnFormData['value'], $params['model']->tableName, $columnFormData['columnName'], $array['id'], $array['name'], 0, $map_condition)), true);
		return $result;
	}

	public function gridView($params) {
		$result = '';
		if (empty($params['belongOrderColumnIds'])) {
			$inputInfo = FieldRule::getPidParam($params['tableColumnId']);
			$result = 'TDFormat::getPidMarginSpanInTable("' . $params['tableColumnId']
					. '","$data->' . $inputInfo['id'] . '","' . $params['moduleId'] . '").$data->' . $inputInfo['name'];
		}
		return $result;
	}
	public function viewHtml($params) {
		return $params['value'];
	}

	public function viewData($params) {
		
	}

	public function saveData($params) {
		$value = !is_null($params['fixedValue']) ? $params['fixedValue'] : self::getFormPostData($params['fieldName']);
		if (!is_null($value)) {
			TDFormat::setModelAppendColumnValue($params['model'], $params['columnAppStr'], $value);
		}
	}

	public function search($params) {
		
	}

	public function editTableColumn($params) {
		
	}

	public static function getPidColumnIdByTableId($tableCollectionId) {
		$cacheValue = TDSessionData::getCache("getPidColumnIdByTableId_" . $tableCollectionId);
		if ($cacheValue === false) {
			$result = 0;
			$inputModel = TDModelDAO::getModel(TDTable::$too_table_column_input);
			$inputModel = $inputModel->find('`name`=\'' . Fie_pid::getInputTypeStr() . '\'');
			$inputId = $inputModel->primaryKey;
			$columnRow = TDModelDAO::getModel(TDTable::$too_table_column)->find('`table_column_input_id`='
					. $inputId . ' and `table_collection_id`=\'' . $tableCollectionId . '\'');
			if (!empty($columnRow)) {
				$result = $columnRow->primaryKey;
			}
			$cacheValue = $result;
			TDSessionData::setCache("getPidColumnIdByTableId_" . $tableCollectionId, $cacheValue);
		}
		return $cacheValue;
	}

}
