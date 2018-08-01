<?php

class TDCheckDataController extends TDController {

	public function actionsRemark() {
		return array();
	}

	public function actionIndex() {
		$checkType = isset($_GET["type"]) ? $_GET["type"] : 0;
		if($checkType == 1) {
			echo "<br/>---------item_db_tables_comp_sys_tables start---------<br/>";
			$this->item_db_tables_comp_sys_tables();
			echo "<br/>---------item_db_tables_comp_sys_tables end---------<br/>";
		} else if($checkType == 2) {
			echo "<br/>---------item_sys_tables_comp_db_tables start---------<br/>";
			$this->item_sys_tables_comp_db_tables();
			echo "<br/>---------item_sys_tables_comp_db_tables end---------<br/>";
		} else if($checkType == 4) {
			echo "<br/>---------item_rows_unique_columns start---------<br/>";
			$table = isset($_GET['table']) ? $_GET['table'] : "";
			$this->item_rows_unique_columns($table);
			echo "<br/>---------item_rows_unique_columns end---------<br/>";
		} else if($checkType == 5) {
			echo "<br/>--------- org tables start---------<br/>";
			$this->displayOrgTables();
			echo "<br/>---------org tables end---------<br/>";
		} else {
			//echo ' type  error ';
			$sql = "select id,entity_id,count(*) as '订单数量',create_time from xg_order group by entity_id";
			$tableAsArray = array();
			$tableStr = strtolower($sql);
			$tableStr = explode("from",$tableStr)[1];
			$tableStr = explode("where",$tableStr)[0];
			$tableStr = trim($tableStr);
			$tableStr = explode(" ",$tableStr);
			$defaultTable = trim($tableStr[0]);
			$newTableStr = array();
			for($tbIndex = 0; $tbIndex < count($tableStr); $tbIndex++) {
				$tmp = trim($tableStr[$tbIndex]);
				if(empty($tmp)) { continue; }
				$newTableStr[] = $tmp;	
			}
			for($tbIndex = 1; $tbIndex < count($newTableStr); $tbIndex++) {
				if($newTableStr[$tbIndex] == "as") {
					$tableAsArray[$newTableStr[$tbIndex+1]] = $newTableStr[$tbIndex-1]; 
				} else if(in_array($newTableStr[$tbIndex],array("left","right","inner"))) {
					if($tbIndex-2 >= 0) {
						if(TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$newTableStr[$tbIndex-2]."'","count(*)") > 0) {
							$tableAsArray[$newTableStr[$tbIndex-1]] = $newTableStr[$tbIndex-2]; 
						}
					}
				} else if($newTableStr[$tbIndex] =="join") {
					if(!in_array($newTableStr[$tbIndex-1],array("left","right","inner")) && $tbIndex-2 >= 0) {
						if(TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$newTableStr[$tbIndex-2]."'","count(*)") > 0) {
							$tableAsArray[$newTableStr[$tbIndex-1]] = $newTableStr[$tbIndex-2]; 
						}
					}
					if(!in_array($newTableStr[$tbIndex+2],array("on","as"))) {
						$tableAsArray[$newTableStr[$tbIndex+2]] = $newTableStr[$tbIndex+1]; 
					}
				}
			}
			$columnStr = strtolower($sql);
			$columnStr = explode("from",$columnStr)[0];
			$columnStr = explode("select",$columnStr)[1];
			$columnStr = explode(",",$columnStr);
			$columns = array();
			foreach($columnStr as $str) {
				$str = trim($str);
				if(strpos($str," as ") !== false) {
					$str = explode(" as ",$str)[1];
					$str = str_replace("'","",$str);
					$str = str_replace('"','',$str);
					$columns[] = array(
					    'table' => '',
					    'name' => trim($str),
					);
				} else if(strpos($str," ") !== false) {
					$str = explode(" ",$str);
					$str = $str[count($str)-1];
					$str = str_replace("'","",$str);
					$str = str_replace('"','',$str);
					$columns[] = array(
					    'table' => '',
					    'name' => trim($str),
					);
				} else if(strpos($str,".") !== false) {
					$str = explode(".",$str);
					$tb = trim($str[0]); 
					if(isset($tableAsArray[$tb])) {
						$tb = $tableAsArray[$tb]; 
					} else {
						if(TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$tb."'","count(*)") == 0) {
							echo "sql 有误 ".$tb.".".$str[1]." 未找到  table =".$tb;exit;
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
			foreach($columns as $index => $item) {
				if(!empty($item["table"])) {
					$columns[$index]["columnId"] = TDTableColumn::getColumnIdByTableAndColumnName($item["table"],$item["name"]);
					$columns[$index]["label"] = TDTableColumn::getColumnLabelName($columns[$index]["columnId"]);
				}
			}
		
			 //分页处理
        	$pageSize = isset($_GET["pageSize"]) ? intval($_GET["pageSize"]) : 10;
        	$currentPage = isset($_GET["currentPage"]) ? intval($_GET["currentPage"]) : 1;
        	$offSet = ($currentPage > 0 ? $currentPage - 1 : 0) * $pageSize;
			$sql .= " LIMIT :offSet, :pageSize"; 
			$comm = TDModelDAO::getDBBySQL($sql)->createCommand($sql);
			$comm->bindValue(':offSet', $offSet);
        	$comm->bindValue(':pageSize',$pageSize);
			$rows = $comm->queryAll();

			$this->render('min_items/analyze_query',array('columns'=>$columns,'rows'=>$rows));
		}
	}
	
	public function item_db_tables_comp_sys_tables() {
		$result = [];
		$dbTables = []; 
		$rows  = TDModelDAO::getDB()->createCommand("show tables")->queryAll();
		foreach($rows as $item) { foreach($item as  $key => $table) { $dbTables[] = $table; } }
		foreach ($dbTables as $table) {
			$rows = TDModelDAO::queryAll(TDTable::$too_table_collection,'`table`=\'' . $table . '\' ','`id`');
			if (count($rows) == 0) {
				$result[] = "db table 【" . $table . "】 not in sys table";
			} else if (count($rows) > 1) { //check unique
				$result[] = "db table 【" . $table . "】 has " . count($rows) . " count in sys table ";
			} else {
				$row = $rows[0];
				$sysColumnsCount = TDModelDAO::queryScalar(TDTable::$too_table_column, "`table_collection_id`=" .$row["id"]." and `column_type`=0","count(*)");
				$tableObj = TDTable::getTableObj($table, false);	
				$dbColumnsCount = count($tableObj->columns);
				if($sysColumnsCount != $dbColumnsCount) {
					 $dbAddCol = "";
					 foreach($tableObj->columns as $dbcol => $colItem) {
						if(TDModelDAO::queryScalar(TDTable::$too_table_column, "`table_collection_id`=" .$row["id"]." and `column_type`=0 and `name`='".$dbcol."'","count(*)") == 0) {
							$dbAddCol .= empty($dbAddCol) ? "" : ","; 
							$dbAddCol .= $dbcol; 
						}
					 }
					$dbAddCol = !empty($dbAddCol) ? "   db add column=>".$dbAddCol : ""; 
					$result[] = "db table 【" . $table . "】 columns has " .$dbColumnsCount . " , sys table columns has ".
					$sysColumnsCount.$dbAddCol." <a href='/tDAjax/commonOperate/".TDOperate::$PARAM_OPERATE_TYPE.'/'.TDOperate::$TYPE_REFRESH_TABLE_STRUCTURE.'/'.TDOperate::$PARAM_TABLE_ID.'/'.$row["id"]."' target='_blank'>refresh</a>";
				}
			}
		}
		$this->outputResult($result);
	}

	public function item_sys_tables_comp_db_tables() {
		$result = [];
		$dbTables = TDTable::getDataBaseAllTables();
		$sysTables = TDTable::getTableConllectionAllTables();
		foreach ($sysTables as $table) {
			if (!in_array($table["table"], $dbTables)) {
				$result[] = "sys table 【".$table["table"]. "】 not in db";
			}
		}
		$this->outputResult($result);
	}

	public function item_rows_unique_columns($ptable="") {
		$condition = !empty($ptable) ? "`table`='".$ptable."'" : "";	
		$sysTables = TDModelDAO::queryAll(TDTable::$too_table_collection,$condition,"`id`,`table`");
		foreach ($sysTables as $sysTable) {
			$uniqueColumns = TDModelDAO::queryAll(TDTable::$too_table_column,"table_collection_id=".$sysTable["id"]." and ((`name` != 'id' and is_unique=1) or (unique1_laddercolumn > 0 or unique2_laddercolumn > 0))",
			"`id`,`name`,`unique1_laddercolumn`,`unique2_laddercolumn`,`unique_check_condtion`");	
			foreach($uniqueColumns as $uniqueCol) {
				$uni1 = $uniqueCol["name"];
				$uni2 = !empty($uniqueCol["unique1_laddercolumn"]) ? TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$uniqueCol["unique1_laddercolumn"],"`name`") : "";
				$uni3 = !empty($uniqueCol["unique2_laddercolumn"]) ? TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$uniqueCol["unique2_laddercolumn"],"`name`") : "";
				$checkSQL = "select base.`id`,base.`".$uni1."`".(!empty($uni2) ? ",base.`".$uni2."`" : "").(!empty($uni3) ? ",base.`".$uni3."`" : "").
				" from `".$sysTable["table"]."` as base join (select `id`,`".$uni1."`".(!empty($uni2) ? ",`".$uni2."`" : "").(!empty($uni3) ? ",`".$uni3."`" : "").",count(1) ".
				"as tt from `".$sysTable["table"]."` where 1 group by `".$uni1."`".(!empty($uni2) ? ",`".$uni2."`" : "").(!empty($uni3) ? ",`".$uni3."`" : "").
				" HAVING tt > 1) as tmptb on base.`".$uni1."`=tmptb.`".$uni1."` ".(!empty($uni2) ? " and base.`".$uni2."`=tmptb.`".$uni2."`" : "").(!empty($uni3) ? " and base.`".$uni3."`=tmptb.`".$uni3."`" : "");
				$rows = TDModelDAO::getDB($sysTable["table"])->createCommand($checkSQL)->queryAll();
				if(count($rows) == 0) {
					continue;
				}
				echo "<br/>".$sysTable["table"]."检测唯一约束==>".(count($rows) == 0 ? "正常" : "异常<br/>检测SQL：<br/>".$checkSQL."<br/>异常记录：")."<br/>";
				foreach($rows as $row) {
					echo $sysTable["table"]."\t"; foreach($row as $key => $v) {	echo $key."=".$v."\t"; } echo "<br/>"; 
				}
			}
		}
	}

	public function outputResult($result,$tableName='',$saveLog=false,$echo=true) {
		foreach ($result as $errorMsg) {
			if($echo) 
				echo $errorMsg . "<br/>";
		}
	}

	public function displayOrgTables() {
		$html = '<table>';
		$dbTables = []; 
		$rows  = TDModelDAO::getDB()->createCommand("show tables")->queryAll();
		foreach($rows as $item) { foreach($item as  $key => $table) { $dbTables[] = $table; } }
		foreach($dbTables as $index => $tableName) {
			$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE $tableName")->query();
			$tbstr = $tableName;
			foreach ($createtable as $row) {
				$tableCom = $row['Create Table'];
				$tableComAr = explode("COMMENT=",$tableCom);
				if(count($tableComAr) == 2) {
					$tbstr = $tableComAr[1];
				}
			}
			$html .= '<tr><td>'.$tableName.'    '. (($tableName != $tbstr) ? $tbstr : "" ).'</td></tr>';	
		}
		$html .= '</table>';
		echo $html;
	}

}
