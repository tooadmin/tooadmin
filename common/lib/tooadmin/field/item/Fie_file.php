<?php

class Fie_file extends TDField {

	public static function deleteFile($columnId, $pkId) {
		$result = new TDOperateResult();
		$model = TDModelDAO::getModel(TDTableColumn::getColumnTableDBName($columnId))->findByPk($pkId);
		if (!empty($model)) {
			$columnName = TDTableColumn::getColumnDBName($columnId);
			$filePath = TDPathUrl::getPathUrlByColumnId($columnId, TDPathUrl::$TYPE_PATH) . $model->$columnName;
			$orgValue = $model->$columnName;
			$model->$columnName = "";
			if ($model->save()) {
				if (is_file($filePath)) {
					if (unlink($filePath)) {
						$result->setResult(true);
					} else {
						$model->$columnName = $orgValue;
						$model->save();
						$result->setResult(false);
						$result->setMsg("delete file fail, file path is " . $filePath);
					}
				} else {
					$result->setResult(true);
				}
			} else {
				$result->setResult(false);
				$result->setMsg(TDCommon::getArrayValuesToString($model->errors));
			}
		} else {
			$result->setResult(false);
			$result->setMsg("model row is empty");
		}
		return $result;
	}

	public static function getFileUrl($tableColumnId, $fieldValue, $isAppendHost = false, $default = "") {
		$url = "";
		if (strpos($fieldValue, "http://") !== false || strpos($fieldValue, "https://") !== false || substr($fieldValue, 0,2) == '//') {
			return $fieldValue;
		} else if (is_file(TDPathUrl::getPathUrlByColumnId($tableColumnId, TDPathUrl::$TYPE_PATH) . $fieldValue)) {
			$url = TDPathUrl::getPathUrlByColumnId($tableColumnId, TDPathUrl::$TYPE_URL) . $fieldValue;
			if ($isAppendHost) {
				$url = TDPathUrl::getHttpHostString() . $url;
			}
		} else if (is_file(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH, '') . $fieldValue)) {
			$url = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL, '') . $fieldValue;
			if ($isAppendHost) {
				$url = TDPathUrl::getHttpHostString() . $url;
			}
		} else {
			if (!empty($fieldValue)) {
				$file_read_base_value = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$tableColumnId,"file_read_base_value");
				$baseUrl = !empty($file_read_base_value) ? Fie_formula::getValue(null,$file_read_base_value) : "";
				if (!empty($baseUrl)) {
					$url = $baseUrl . $fieldValue;
				}
			}
		}
		if (empty($url)) {
			$url = $default;
		}
		return $url;
	}

	public static function getFileALink($tableColumnId, $value) {
		if (!empty($value)) {
			$path = Fie_file::getFileUrl($tableColumnId, $value);
			$ext = strtolower(strrchr($value,"."));
			if(empty($ext)) {
				return '';
			}
			if(strpos($ext,'?') !== false) {	
				$ext = substr($ext,0,strpos($ext,'?'));
			} else if(strpos($ext,'/') !== false) {	
				$ext = substr($ext,0,strpos($ext,'/'));
			}
			if (in_array($ext, array(".jpg",".jpeg", ".png", ".gif", ".bmp",".cn",".com",".net",".org"))) {
				$html = Fie_file::getAHtml($path);
			} else {
				$html = '<a target="_blank" href="' .$path. '" download="download" >' . TDLanguage::$view.'</a>';
			}
			return $html;
		} else {
			return "";
		}
	}

	public static function getAHtml($path) {
		$html = '<a href="javascript:popupImgView(\''.$path.'\',700,700);void(0);">' .
		'<img src="' . $path . '" style="display: block;max-width:100px;max-height:30px;"></a>';
		//$html = '<a style="background:url(' . $path . ');" href="' . $path . '" class="cboxElement">' .
		//'<img src="' . $path . '" style="display: block;max-width:100px;max-height:30px;"></a>';
		return $html;
	}

	public function editForm($params) {
		$columnFormData = $params['columnFormData'];
		$result = "<input type='hidden' name='" . $columnFormData['name'] . "' />" . CHtml::fileField($columnFormData['baseName'], '', array('id' => $columnFormData['id']));
		$fileUrl = self::getFileUrl($params["tableColumnId"], $columnFormData['value']);
		if (!empty($fileUrl)) {
			$result .= '<a target="_blank" href="' . $fileUrl . '">' . TDLanguage::$view . '</a>&nbsp;'
					. '<a href="javascript:' . 'commonOperate(' . "'" . TDOperate::createDeleteFileAppendUrlStr($params['tableColumnId'], $params['model']->primaryKey) . "'" . ',true,'
					. "'" . TDLanguage::$Operate_delete_file_confirm . "'" . ');void(0);">' . TDLanguage::$Operate_delete . '</a>';
		}
		return $result;
	}

	public function gridView($params) {
		$columnData = $params["columnData"];
		$result = 'Fie_file::getFileALink(' . $params['tableColumnId'] . ',' . $columnData['value'] . ')';
		return $result;
	}

	public function viewData($params) {
		$columnData = self::getColumnFormData($params['tableColumnId'], $params['belongOrderColumnIds'], $params['model']);
		///$fileUrl = self::getFileUrl($params["tableColumnId"], $columnData['value']);
		//!empty($fileUrl) ? "<a target='_blank' href='" . $fileUrl . "' >" . TDLanguage::$view . "</a>" : "",
		$result = array(
			'name' => $columnData['label'],
			'type' => 'raw',
			'value' => Fie_file::getFileALink($params['tableColumnId'],$columnData['value']),
		);
		return $result;
	}
	public function viewHtml($params) {
		return Fie_file::getFileALink($params['tableColumnId'],$params['value']);
	}

	public function saveData($params) {
		$result = array();
		$orgValue = TDFormat::getModelAppendColumnValue($params['model'], $params['columnAppStr']);
		$fileBasePath = TDPathUrl::getPathUrlByColumnId($params["tableColumnId"], TDPathUrl::$TYPE_PATH);
		$fileObj = CUploadedFile::getInstanceByName($params['fieldName']);
		if (is_object($fileObj)) {
			$fileExt = strtolower(strchr($fileObj, '.'));
			$rules = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$params["tableColumnId"],'`file_types`,`file_max_size`,`save_expande_path`,`file_save_base_value`');
			if (!empty($rules)) {
				if (!empty($rules["file_types"])) {
					$fileTypes = strtolower($rules["file_types"]);
					$typesArray = explode(",", $fileTypes);
					$fileExtCheck = strtolower(substr($fileExt, 1));
					if (!in_array($fileExtCheck, $typesArray)) {
						$result['specialValidateErrorFields'] = array(array('fieldID' => $params['fieldId'], 'msg' => TDLanguage::$file_validate_type_error . $fileTypes));
						return $result;
					}
					if (!empty($rules["file_max_size"])) {
						if ($fileObj->size > $rules["file_max_size"] * 1024) {
							$result['specialValidateErrorFields'] = array(array('fieldID' => $params['fieldId'], 'msg' => TDLanguage::$file_validate_size_error . $rules["file_max_size"]."KB"));
							return $result;
						}
					}
				}
			}
			$saveExpandPath = !empty($rules) && !empty($rules["save_expande_path"]) ? Fie_formula::getValue($params['model'],$rules["save_expande_path"]) : "";
			$fileSaveBaseValue = !empty($rules) && !empty($rules["file_save_base_value"]) ? Fie_formula::getValue($params['model'],$rules["file_save_base_value"]) : "";
			$saveExpandPath = !empty($saveExpandPath) ? TDPathUrl::parsePath($saveExpandPath) : "";
			$fileName = $params['columnName'] . "_" . date('YmdHis') . $fileExt;
			$isUseOrgFileName = TDModelDAO::getFieldById(TDTable::$too_table_column,$params["tableColumnId"],'use_org_filename');
			if($isUseOrgFileName == 1) {
				$fileName = $fileObj->getName();
			}
			if (empty($saveExpandPath)) {
				if (!is_dir($fileBasePath)) {
					mkdir($fileBasePath, 0777, true);
				}
			} else {
				if (!is_dir($fileBasePath . $saveExpandPath)) {
					mkdir($fileBasePath . $saveExpandPath, 0777, true);
				}
				$fileName = $saveExpandPath . "/" . $fileName;
			}
			$fileObj->saveAs($fileBasePath . $fileName);

			//------save to table----------------------------------
			$fileContent = file_get_contents($fileBasePath.$fileName);
			$base64Str = base64_encode($fileContent);
			$tmpMb = TDModelDAO::getModel("crhtmpfile");
			$tmpMb->content = $base64Str;
			$tmpMb->createtime = time();
			$tmpMb->filename = $fileName;
			if($tmpMb->save()) {
				$tbname = TDTableColumn::getColumnTableDBName($params['tableColumnId']);
				$pkColumnName = TDTableColumn::getPrimaryKeyColumnName($tbname); 
				$res = CrhToo::ossFile($tmpMb->id,$tbname ,$params['columnName'],$pkColumnName,$params['model']->getAttribute($pkColumnName));
				//if($res !== "success") { echo $res;exit; }
			} else {
				TDFormat::setModelAppendColumnValue($params['model'], $params['columnAppStr'], $fileSaveBaseValue . $fileName);
			}
			//------end save to table -----------------------------
			

			//echo $fileObj->getSize();
			//exit;
			$newFileArray = array();
			$orgFileArray = array();
			$newFileArray[$params['columnName']] = $fileBasePath . $fileName;
			$orgFileArray[$params['columnName']] = $fileBasePath . $orgValue;
			$result = array('newFileArray' => $newFileArray,
				'orgFileArray' => $orgFileArray,);
			return $result;
		}
	}

	public function search($params) {
		
	}

	public function editTableColumn($params) {
		
	}

}