<?php

class TDToolExcel {
	
	//excel asccii 和 数字 的转换还有问题待改进
	public function getExcelColumnChar($index) {
		$char = ""; //ascii 65 A -- 90 Z	
		if ($index <= 26) {
			$char = chr(65 + $index);
		} else {
			$base = $this->getExcelColumnChar(intval($index / 26));
			$char = $base . $this->getExcelColumnChar($index % 26);
		}
		return $char;
	}

	public function getExcelColumnsCountByChar($char) {
		$result = 0;
		if (strlen($char) == 1) {
			$result = ord($char) - 65 + 1;
		} else {
			$result += (strlen($char) - 1) * 26 - 1;
			for ($i = 0; $i < strlen($char); $i++) {
				$result += $this->getExcelColumnsCountByChar($char[$i]);
			}
		}
		return $result;
	}

	public function exportDatas($headers, $rows, $windowOpen = true) {
		$xls = new Excel_XML('UTF-8',false,'data');
		$rows = array_merge(array($headers),$rows);
        $xls->addArray($rows);
        $xls->generateXML("data");
			
		/*
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=datas.xls');
		header('Pragma: no-cache');
		header('Expires: 0');

		foreach ($headers as $k => $v) {
			$headers[$k] = strip_tags($v);
		}
		foreach ($rows as $k => $v) {
			foreach ($v as $vk => $vv)
				$rows[$k][$vk] = strip_tags($vv);
		}
		///echo @iconv('utf-8', 'gbk', implode("\t", $headers)), "\n";
		echo implode("\t", $headers) . "\n";
		foreach ($rows as $key => $value) {
			//echo @iconv('utf-8', 'gbk', implode("\t", $value)), "\n";
			echo implode("\t", $value) . "\n";
		}
		exit;
		*/
		/*
		  //新建
		  include_once './common/plugins/items/PHPExcel/PHPExcel.php';
		  include_once './common/plugins/items/PHPExcel/PHPExcel/Writer/Excel5.php';
		  $excel = new PHPExcel();
		  //设置参数
		  $colIndex = 0;
		  foreach($headers as $lable) {
		  $excel->getActiveSheet()->setCellValue($this->getExcelColumnChar($colIndex).'1',$lable);
		  $excel->getActiveSheet()->getStyle($this->getExcelColumnChar($colIndex).'1')->getFont()->setBold(true);
		  $colIndex++;
		  }
		  $rowIndex = 0;
		  foreach($rows as $row) {
		  $colIndex = 0;
		  foreach($row as $value) {
		  $excel->getActiveSheet()->setCellValue($this->getExcelColumnChar($colIndex).($rowIndex+2),$value);
		  $colIndex++;
		  }
		  $rowIndex++;
		  }
		  $write = new PHPExcel_Writer_Excel5($excel);
		  $write->save(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH)."export.xls");
		  if($windowOpen) {
		  echo '<script type="text/javascript">window.open("'.TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL)."export.xls".'");</script>';
		  } else {
		  echo TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL)."export.xls";
		  }
		 */
	}

	public function exportByTableName($tableName, $condition = "") {
		$headers = array();
		$dataRows = array();
		$colLables = TDModelDAO::getModel($tableName)->attributeLabels();
		foreach ($colLables as $col => $lable) {
			$headers[] = $lable;
		}
		$rows = TDModelDAO::getModel($tableName)->findAll($condition);
		$obj = TDTable::getTableObj($tableName);
		$columns = array_keys($obj->columns);
		foreach ($rows as $row) {
			$data = array();
			foreach ($columns as $column) {
				$data[] = $row->$column;
			}
			$dataRows[] = $data;
		}
		$this->exportDatas($headers, $dataRows);
	}

	public function exportByTableHtml($tableHtml) {
		$headers = array();
		$rows = array();
		$trs = explode("</tr>", $tableHtml);
		foreach ($trs as $tr) {
			if (strpos($tr, "</th>") !== false) {
				$ths = explode("</th>", $tr);
				foreach ($ths as $th) {
					if (count(explode("<", $th)) > 2) {
						$th = substr($th, 0, strrpos($th, "<"));
					}
					$str = substr($th, strrpos($th, ">") + 1);
					if ($str !== false) {
						$headers[] = $str;
					}
				}
			} else if (strpos($tr, "</td>") !== false) {
				$tds = explode("</td>", $tr);
				$row = array();
				foreach ($tds as $td) {
					$str = substr($td, strrpos($td, ">") + 1);
					if ($str !== false) {
						$row[] = $str;
					} else {
						$row[] = "";
					}
				}
				$rows[] = $row;
			}
		}
		return $this->exportDatas($headers, $rows, false);
	}

	public function expertByTDGRidView($tdgridview) {
		$columns = $tdgridview->getColumns();
		$dataProvider = $tdgridview->getDataProvider();
		$headers = array();
		$rows = array();
		$unExpertArra = ['MdExpandButton', 'CButton'];
		foreach ($columns as $key => $item) {
			$title = isset($item["header"]) ? $item["header"] : (isset($item["name"]) ? $item["name"] : "");
			if ($key !== 0 && in_array($key, $unExpertArra)) {
				continue;
			}
			$headers[] = $title;
		}
		foreach ($dataProvider->getData() as $data) {
			$row = array();
			foreach ($columns as $key => $item) {
				if ($key !== 0 && in_array($key, $unExpertArra)) {
					continue;
				}
				$value = isset($item["value"]) ? @eval('return ' . $item["value"] . ';') : "";
				$value = strip_tags($value);
				if (strlen($value) > 100) {
					$value = substr($value, 0, 100);
				}
				$row[] = $value;
			}
			$rows[] = $row;
		}
		/*
		  echo "<pre>";
		  print_r($headers);
		  print_r($rows);
		  echo '<br/> tableName = '.$tdgridview->model->tableName;
		  echo "<br/> condtion sql = ".$tdgridview->model->getDbCriteria()->condition;
		 */
		$this->exportDatas($headers, $rows, false);
	}

	public function importToDBTable() {
		include 'common/plugins/items/PHPExcel/PHPExcel.php';
		$filePath = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH) . "export.xls";
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load($filePath);
		$sheet = $objPHPExcel->getSheet(0);
		$rowCount = $sheet->getHighestRow(); //取得总行数
		$columnMaxChar = $sheet->getHighestColumn(); //取得总列数
		$rows = array();
		for ($j = 1; $j <= $rowCount; $j++) { //从第一行开始读取数据
			$row = array();
			for ($k = 'A'; $k <= $columnMaxChar; $k++) { //从A列读取数据
				$row[] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . ''; //读取单元格
			}
			$rows[] = $row;
		}
		echo "<pre>";
		print_r($rows);
		///unlink($uploadfile); //删除上传的excel文件
		//$msg = "导入成功！";		
	}

}
