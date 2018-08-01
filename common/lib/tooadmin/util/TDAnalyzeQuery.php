<?php

class TDAnalyzeQuery {

	public static function getColumnsBySQL($sql) {
		if(strpos($sql,".*") !== false || strpos($sql,"(select ") !== false || strpos($sql,"( select ") !== false) {
			return array();
		}
		$tableAsArray = array();
		$tableStr = strtolower($sql);
		$tableStr = explode("from", $tableStr)[1];
		$tableStr = explode("where", $tableStr)[0];
		$tableStr = trim($tableStr);
		$tableStr = explode(" ", $tableStr);
		$defaultTable = trim($tableStr[0]);
		$newTableStr = array();
		for ($tbIndex = 0; $tbIndex < count($tableStr); $tbIndex++) {
			$tmp = trim($tableStr[$tbIndex]);
			if (empty($tmp)) {
				continue;
			}
			$newTableStr[] = $tmp;
		}
		for ($tbIndex = 1; $tbIndex < count($newTableStr); $tbIndex++) {
			if ($newTableStr[$tbIndex] == "as") {
				$tableAsArray[$newTableStr[$tbIndex + 1]] = $newTableStr[$tbIndex - 1];
			} else if (in_array($newTableStr[$tbIndex], array("left", "right", "inner"))) {
				if ($tbIndex - 2 >= 0) {
					if (TDModelDAO::queryScalar(TDTable::$too_table_collection, "`table`='" . $newTableStr[$tbIndex - 2] . "'", "count(*)") > 0) {
						$tableAsArray[$newTableStr[$tbIndex - 1]] = $newTableStr[$tbIndex - 2];
					}
				}
			} else if ($newTableStr[$tbIndex] == "join") {
				if (!in_array($newTableStr[$tbIndex - 1], array("left", "right", "inner")) && $tbIndex - 2 >= 0) {
					if (TDModelDAO::queryScalar(TDTable::$too_table_collection, "`table`='" . $newTableStr[$tbIndex - 2] . "'", "count(*)") > 0) {
						$tableAsArray[$newTableStr[$tbIndex - 1]] = $newTableStr[$tbIndex - 2];
					}
				}
				if (!in_array($newTableStr[$tbIndex + 2], array("on", "as"))) {
					$tableAsArray[$newTableStr[$tbIndex + 2]] = $newTableStr[$tbIndex + 1];
				}
			}
		}
		$columnStr = strtolower($sql);
		$columnStr = explode("from", $columnStr)[0];
		$columnStr = explode("select", $columnStr)[1];
		$columnStr = explode(",", $columnStr);
		$columns = array();
		foreach ($columnStr as $str) {
			$str = trim($str);
			if (strpos($str, " as ") !== false) {
				$str = explode(" as ", $str)[1];
				$str = str_replace("'", "", $str);
				$str = str_replace('"', '', $str);
				$columns[] = array(
					'table' => '',
					'name' => trim($str),
				);
			} else if (strpos($str, " ") !== false) {
				$str = explode(" ", $str);
				$str = $str[count($str) - 1];
				$str = str_replace("'", "", $str);
				$str = str_replace('"', '', $str);
				$columns[] = array(
					'table' => '',
					'name' => trim($str),
				);
			} else if (strpos($str, ".") !== false) {
				$str = explode(".", $str);
				$tb = trim($str[0]);
				if (isset($tableAsArray[$tb])) {
					$tb = $tableAsArray[$tb];
				} else {
					if (TDModelDAO::queryScalar(TDTable::$too_table_collection, "`table`='" . $tb . "'", "count(*)") == 0) {
						echo "sql 有误 " . $tb . "." . $str[1] . " 未找到  table =" . $tb;
						exit;
					}
				}
				$columns[] = array(
					'table' => $tb,
					'name' => trim($str[1]),
				);
			} else {
				$columns[] = array(
					'table' => $defaultTable,
					'name' => $str,
				);
			}
		}
		foreach ($columns as $index => $item) {
			if (!empty($item["table"])) {
				$columns[$index]["columnId"] = TDTableColumn::getColumnIdByTableAndColumnName($item["table"], $item["name"]);
				$columns[$index]["label"] = TDTableColumn::getColumnLabelName($columns[$index]["columnId"]);
			}
		}
	}

	public static function getData($mitemId) {
		$menuRow = TDModelDAO::queryRowByPk(TDTable::$too_menu_items,$mitemId,"query_sql,query_params");
		$sql = !empty($menuRow["query_sql"]) ? Fie_formula::getValue(null,$menuRow["query_sql"]) : array();
		$queryParams = !empty($menuRow["query_params"]) ? Fie_formula::getValue(null,$menuRow["query_params"]) : array();
		if(empty($sql)) {
			echo "查询SQL为空";exit;
		}
		if(substr($sql,strlen($sql)-1,1) == ";") {
			$sql = substr($sql,0,strlen($sql)-1);	
		}
		$baseName = 'tmpQuery';
		$queryFormCode = ' ';
		if(!empty($queryParams) && is_array($queryParams)) {
			$index = 0;
			$condition = "";
			foreach($queryParams as $param) {
				$index++;
				if($param["type"] == "datetime") {
					$_start = isset($_GET["tmpQuery"]) && isset($_GET["tmpQuery"][$param["name"]."_start"]) ? $_GET["tmpQuery"][$param["name"]."_start"] : '';
					$_end = isset($_GET["tmpQuery"]) && isset($_GET["tmpQuery"][$param["name"]."_end"]) ? $_GET["tmpQuery"][$param["name"]."_end"] : '';
					$queryFormCode .= $param["title"].'：';
					$queryFormCode .= '
					<input style="width:120px;" class="Wdate" readonly="readonly" value="'.$_start.'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd H:mm\'})" id="searchID1tmp'.$index.'" name="'.$baseName.'['.$param["name"].'_start]" type="text"> 至 
					<input style="width:120px;" class="Wdate" readonly="readonly" value="'.$_end.'" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd H:mm\'})" id="searchID2tmp'.$index.'" name="'.$baseName.'['.$param["name"].'_end]" type="text">
					&nbsp;&nbsp;&nbsp;';	
					if(!empty($_start)) {
						$condition .= ' and '.$param["name"].'>='.  strtotime($_start); 
					}
					if(!empty($_end)) {
						$condition .= ' and '.$param["name"].'<='.  strtotime($_end); 
					}
				} else if($param["type"] == "input") {
					$value = isset($_GET["tmpQuery"]) && isset($_GET["tmpQuery"][$param["name"]]) ? $_GET["tmpQuery"][$param["name"]] : '';
					$value = trim($value);
					$queryFormCode .= $param["title"].'：';
					$queryFormCode .= '<input style="width:120px;" name="'.$baseName.'['.$param["name"].']" class="input" value="'.$value.'" type="text">&nbsp;&nbsp;&nbsp;';	
					if(!empty($value)) {
						$condition .= ' and '.$param["name"].'='.intval($value); 
					}
				}
			}
			$sql = str_replace("{condition}",$condition,$sql);
			if(!empty($queryFormCode)) {
				$queryFormCode = '<form action="" method="get">'.$queryFormCode;
				$queryFormCode .= '<button type="submit" id="subbutTmpQ" class="btn btn-primary"><i class="icon icon-white icon-search"></i>确定</button></form>';
			}
		}

		$totalCount = strtolower($sql);
		$totalCount = substr($totalCount,strpos($totalCount,"from")+4);
		if(strpos($totalCount,")") !== false && strpos($totalCount,"(") && strpos($totalCount,")") < strpos($totalCount,"(") ) {
			$totalCount = substr($totalCount,strpos($totalCount,"from")+4);
		}
		$totalCountTbName = explode(" ",trim($totalCount))[0]; 
		$totalCount = "select count(*) as tmpnum from ".$totalCount;
		$totalCount = TDModelDAO::getDB($totalCountTbName)->createCommand($totalCount)->queryAll();
		if(count($totalCount) > 0 && strpos($sql,"group by") !== false) {
			$totalCount = count($totalCount);
		} else if(isset($totalCount[0])) {
			$totalCount = $totalCount[0]['tmpnum'];
		} else {
			$totalCount = 0;	
		}
		
		$columns = self::getColumnsBySQL($sql);
				
		//分页处理
		$pageSize = isset($_GET["pageSize"]) ? intval($_GET["pageSize"]) : 10;
		$currentPage = isset($_GET["currentPage"]) ? intval($_GET["currentPage"]) : 1;
		$offSet = ($currentPage > 0 ? $currentPage - 1 : 0) * $pageSize;
		$sql .= " LIMIT :offSet, :pageSize";
		$comm = TDModelDAO::getDB($totalCountTbName)->createCommand($sql);
		$comm->bindValue(':offSet', $offSet);
		$comm->bindValue(':pageSize', $pageSize);
		$rows = $comm->queryAll();
		if(empty($columns) && count($rows) > 0) {
			foreach($rows[0] as $key => $va) {
				$columns[] = array(
					'table' => '',
					'name' => $key,
				); 
			}	
		}
		return array('rows'=>$rows,'columns'=>$columns,'totalCount'=>$totalCount,'queryFormCode'=>$queryFormCode);
	}

}
