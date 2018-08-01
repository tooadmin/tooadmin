<?php

class Fie_order extends TDField {

	public static function getInputTypeStr() {
		return "order";
	}
	public static function getInputTypeId() {
		return 13;
	}
		
	public function editForm($params) {
		return null;
	}

	public function gridView($params) {
		$columnData = $params["columnData"];
		return $columnData["value"];
		/* 双击切换排序，先修改为直接使用输入的方式	
		$columnData = $params["columnData"];
		$result = '';
		if(empty($params['belongOrderColumnIds'])) {
			$orderpid= "";	
			$pidColumnName = FieldRule::getOrderPidColumnName($params['tableColumnId']);
			if(!empty($pidColumnName)) { $orderpid .= '$data->'.$pidColumnName; }
			$result = '"<a href=\'javascript:void(0);<##>\' name=\'orderItem\' ordernum=\''.$columnData["value"]
			.'\' orderid=\'$data->primaryKey\' orderpid=\''.$orderpid.'\' ondblclick=\'chooseOrderItem('.$params['tableColumnId']
			.',$data->primaryKey)\'>'
			. '<i class=\'icon icon-blue icon-arrow-nesw\'></i></a>"';
		} 
		*/
		return $result;
	}

	public function viewData($params) {
		$columnData = self::getColumnFormData($params['tableColumnId'],$params['belongOrderColumnIds'],$params['model']);
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
		if($params['model']->isNewRecord || (!empty($params['appendModel']) && $params['appendModel']->isNewRecord)) {
			TDFormat::setModelAppendColumnValue($params['model'],$params['columnAppStr']
			,Fie_order::getNextOrderNum($params["tableColumnId"],!empty($params['appendModel']) ? $params['appendModel'] : $params['model']));
		}
	}

	public function search($params) { }

	public function editTableColumn($params) { 
		$result = "<input type=\"text\" urlstr=\"".$params['urlstr']."\" value=\"".$params['value']."\" style=\"width:50px;margin-bottom: 0px;\" timeajax=\"1\" />";
		return $result;
	}

	public static function getOrderInputTypeColumnId($tableCollectionId) { $inputId = TDModelDAO::queryScalar(TDTable::$too_table_column_input, '`name`=\''.Fie_order::getInputTypeStr().'\'','id');
	return TDModelDAO::queryScalar(TDTable::$too_table_column,'`table_column_input_id`='.$inputId.' and `table_collection_id`=\''.$tableCollectionId.'\'','id'); }
	
	public static function getNextOrderNum($tableColumnId,$model) {
		$nextNum = 1;
		$orderColumn = TDTableColumn::getColumnDBName($tableColumnId);
		$tableName = TDTableColumn::getColumnTableDBName($tableColumnId);
		if(!empty($model)) {
			$appendCondition = '';
			$orderGroup = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$tableColumnId,"order_group_key1,order_group_key2,order_group_key3");
			if(!empty($orderGroup["order_group_key1"])) {
				$appendCondition .= empty($appendCondition) ? "" : " and ";
				$gcolumnName = TDTableColumn::getColumnDBName($orderGroup["order_group_key1"]);
				$appendCondition .= '`'.$gcolumnName.'`=\''.$model->$gcolumnName.'\' ';
			}
			if(!empty($orderGroup["order_group_key2"])) {
				$appendCondition .= empty($appendCondition) ? "" : " and ";
				$gcolumnName = TDTableColumn::getColumnDBName($orderGroup["order_group_key2"]);
				$appendCondition .= '`'.$gcolumnName.'`=\''.$model->$gcolumnName.'\' ';
			}
			if(!empty($orderGroup["order_group_key3"])) {
				$appendCondition .= empty($appendCondition) ? "" : " and ";
				$gcolumnName = TDTableColumn::getColumnDBName($orderGroup["order_group_key3"]);
				$appendCondition .= '`'.$gcolumnName.'`=\''.$model->$gcolumnName.'\' ';
			}
			$pidColumnName = FieldRule::getOrderPidColumnName($tableColumnId);
			if(!empty($pidColumnName)) {
				$appendCondition .= empty($appendCondition) ? "" : " and ";
				$appendCondition .= '`'.$pidColumnName.'`=\''.$model->$pidColumnName.'\' ';
			}
			if(!empty($appendCondition)) {
				$appendCondition = ' where '.$appendCondition;
			}
			$resultSet = TDModelDAO::getDB($tableName)->createCommand("select max(`".$orderColumn."`) as maxNum from `".$tableName."` ".$appendCondition)->query();
			foreach ($resultSet as $row)
				$nextNum = $row['maxNum'] + 10;
		}		
		return $nextNum;
	}
	
	public static function reOrderTable($tableCollectionId) {
		$inputId = TDModelDAO::queryScalar(TDTable::$too_table_column_input, '`name`=\''.Fie_order::getInputTypeStr().'\'',"id");
		$columnRows = TDModelDAO::queryAll(TDTable::$too_table_column,'`table_column_input_id`='.$inputId.(!empty($tableCollectionId) ? ' and `table_collection_id`=\''.$tableCollectionId.'\'' : ''),
		"id,table_collection_id,order_group_key1,order_group_key2,order_group_key3");
		foreach($columnRows as $columnRow) {
			$orderName = TDTableColumn::getColumnDBName($columnRow["id"]);
			$tableName = TDTableColumn::getTableDBName($columnRow["table_collection_id"]);
			$pidColumnName = FieldRule::getOrderPidColumnName($columnRow["id"]);
			$order = TDTable::geteOrderStr($tableName);

			$orderGroupColumnArray = array();
			if(!empty($columnRow["order_group_key1"])) { $tmp = TDTableColumn::getColumnDBName($columnRow["order_group_key1"]); if(!empty($tmp) && $tmp != $pidColumnName) { $orderGroupColumnArray[] = $tmp; } }
			if(!empty($columnRow["order_group_key2"])) { $tmp = TDTableColumn::getColumnDBName($columnRow["order_group_key2"]); if(!empty($tmp) && $tmp != $pidColumnName) { $orderGroupColumnArray[] = $tmp; } }
			if(!empty($columnRow["order_group_key3"])) { $tmp = TDTableColumn::getColumnDBName($columnRow["order_group_key3"]); if(!empty($tmp) && $tmp != $pidColumnName) { $orderGroupColumnArray[] = $tmp; } }
			if(!empty($pidColumnName)) { $orderGroupColumnArray[] = $pidColumnName; }
			$groupByStrs = "";
			foreach($orderGroupColumnArray as $orderGroup) {
				$groupByStrs .= empty($groupByStrs) ? "" : ",";
				$groupByStrs .= '`'.$orderGroup.'`';
			}
			if(!empty($groupByStrs)) {
				$groupRows = TDModelDAO::queryAll($tableName,"1 group by ".$groupByStrs."","".$groupByStrs."");
				foreach($groupRows as $gRow) {
					$condition = "";
					foreach($orderGroupColumnArray as $orderGroup) {
						$condition .= empty($condition) ? "" : " and ";
						$condition .=  "`".$orderGroup."`='".$gRow[$orderGroup]."'";
					}
					$groupArray[] = array(
						'condition' => $condition,
						'order' => $order,
					);
					$rows = TDModelDAO::queryAll($tableName,$condition." order by ".$order,"`id`"); $index = 1;
					foreach($rows as $row) { TDModelDAO::getDB($tableName)->createCommand("update `".$tableName."` set `".$orderName."`=".($index * 10)." where `id`=".$row["id"])->execute(); $index++; }
				}		
			} else {
				$rows = TDModelDAO::queryAll($tableName," 1 order by ".$order." limit 1000","`id`"); $index = 1;
				foreach($rows as $row) { TDModelDAO::getDB($tableName)->createCommand("update `".$tableName."` set `".$orderName."`=".($index*10)." where `id`=".$row["id"])->execute(); $index++; }	
			}
		}
	}
	
}
