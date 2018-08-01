<?php

class TDTooModel extends TDTooActiveRecord {

	public static function getClassName() {
		return __CLASS__;
	}

	public function tableName() {
		return $this->tableName;
	}

	public function rules() {
		$rules = TDSessionData::getCache('rules_' . $this->tableName);
		if ($rules === false) {
			$rules = array();
			$table = TDTable::getTableObj($this->tableName, false, true);
			$required = array();
			$integers = array();
			$numerical = array();
			$length = array();
			$safe = array();
			foreach ($table->columns as $column) {
				if ($column->autoIncrement)
					continue;
				$defaultValue = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,TDTableColumn::getColumnIdByTableAndColumnName($this->tableName, $column->name, true),"default_value");
				$r = (!$column->allowNull && $column->defaultValue === null) && $defaultValue != 'Empty String';
				if ($r)
					$required[] = $column->name;
				if ($column->type === 'integer' || $column->type === 'mediumint')
					$integers[] = $column->name;
				elseif ($column->type === 'float' || $column->type === 'double' || strpos($column->dbType, "decimal") !== false)
					$numerical[] = $column->name;
				elseif ($column->type === 'string' && $column->size > 0)
					$length[$column->size][] = $column->name;
				elseif (!$column->isPrimaryKey && !$r)
					$safe[] = $column->name;
			}
			if ($required !== array())
				$rules[] = array(implode(', ', $required), 'required');
			if ($integers !== array())
				$rules[] = array(implode(', ', $integers), 'numerical', 'integerOnly' => true);
			if ($numerical !== array())
				$rules[] = array(implode(', ', $numerical), 'numerical');
			if ($length !== array()) {
				foreach ($length as $len => $cols)
					$rules[] = array(implode(', ', $cols), 'length', 'max' => $len);
			}
			if ($safe !== array())
				$rules[] = array(implode(', ', $safe), 'safe');

			foreach ($table->columns as $column) {
				$expRule = FieldRule::getColumnRule(TDTableColumn::getColumnIdByTableAndColumnName($this->tableName, $column->name, true));
				if (!empty($expRule)) {
					$rules = array_merge($rules, $expRule);
				}
			}
			TDSessionData::setCache('rules_' . $this->tableName, $rules);
		}
		return $rules;
	}

	public function relations() {
		return array();
		$result = TDSessionData::getCache('relations_' . $this->tableName);
		if ($result === false) {
			$result = array();
			$table = TDTable::getTableObj($this->tableName, true, true);
			$foreignKeys = $table->foreignKeys;
			foreach ($foreignKeys as $columnName => $foreigData) {
				$forKey = $columnName;
				if (isset($result[$foreigData[0]])) {
					$baseForkey = is_array($result[$foreigData[0]][2]) ? $result[$foreigData[0]][2] : array($result[$foreigData[0]][2]);
					$forKey = TDCommon::array_smerge($baseForkey, array($forKey));
				}
				$result = TDCommon::array_smerge($result, array($columnName . TDSearch::$foreignKey_tableName . $foreigData[0] => array("CBelongsToRelation", "md_" . $this->tableName
								, $forKey, 'tableName' => $foreigData[0])));
			}
			TDSessionData::setCache('relations_' . $this->tableName, $result);
		}
		return $result;
	}

	public function attributeLabels() {
		$labels = TDSessionData::getCache('generateLabels_' . $this->tableName);
		if($labels === false) {
			$labels = array();
			$table = TDTable::getTableObj($this->tableName, true, true);
			foreach ($table->columns as $column) {
				$labels[$column->name] = TDTableColumn::getColumnLabelName(TDTableColumn::getColumnIdByTableAndColumnName($table->name, $column->name, true), true);
			}
			TDSessionData::setCache('generateLabels_' . $this->tableName, $labels);
		}
		return $labels;
	}

	public function search() {
		$criteria = new CDbCriteria;
		return new CActiveDataProvider($this, array('criteria' => $criteria,));
	}

}
