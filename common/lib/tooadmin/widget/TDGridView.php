<?php
class TDGridView extends TDWidget{

	private static $search_view_undisplay = 0;
	private static $search_view_advanced = 1;
	private static $search_view_advanced_combination = 2;

	public $button_add_url;
	public $button_add_html;

	public function afterInit() {   }

	public function getColumns($appendGridId="") {
		$columns = TDField::getGridViewColumns($this,$this->gridviewModuleId,$this->gridview_only_view); 
		/* 暂时隐藏缓存，列的统计数值时有问题
		$cacheValue = TDSessionData::getCache('createGridView_'.$this->moduleId.'_'.$this->gridviewModuleId."_".$this->gridview_only_view);
		if($cacheValue && !in_array($this->moduleId,array(TDStaticDefined::$mysqlCommonModuleId,38))) {
			$columns = $cacheValue;
		} else {
			$columns = TDField::getGridViewColumns($this,$this->gridviewModuleId,$this->gridview_only_view); 
			TDSessionData::setCache('createGridView_'.$this->moduleId.'_'.$this->gridviewModuleId."_".$this->gridview_only_view,$columns);
		}
		*/
		$innerChildGridviewColumns = $this->getChildInnerGridviewColumns($this->moduleId);
		foreach($innerChildGridviewColumns as $childIndex => $innerChild) { $columns["innerChild".$childIndex] = $innerChild; }
		$moduleExpOpButotn = $this->getModuleExpandeOpButton();
		if(!empty($moduleExpOpButotn)) { $columns['MdExpandButton'] = $moduleExpOpButotn; }
		$expbtns = $this->getCbuttronExpand($this->moduleId,$appendGridId);	
		if(!empty($expbtns)) { 
			//$columns['ExpButtons'] = $expbtns;
			$columns = array_merge($columns,$expbtns);
		}
		$cbClu = $this->getCbuttronColumn($this->moduleId,$this->allow_actions,$appendGridId);	
		if(!empty($cbClu)) { $columns['CButton'] = $cbClu; }
		$expandCButton = $this->getExpandOperateCButton();
		if(!empty($expandCButton)) { $columns['ExpandCButton'] = $expandCButton; }
		/*
		if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE && 
		 * TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE) == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE_MORE) {
			$this->pageObj->layout = TDLayout::getSinglePage();
			$chooseIdCkb= array(
				'header'=>'<input type="checkbox" onclick="checkboxChooseUnChooseAll('."'"."checkboxid[]"."'".',this.checked)" />',
		   		'type' => "raw", 
		    	//'htmlOptions' => array("style"=>"width:20px;"),
				'value' => '"<input type=checkbox name=checkboxid[] value=$data->getPrimaryKey />"',	
			); 
			$chooseIdCkb['footer'] = '<a href="javascript:controlPopupChooseedMore(\''.TDRequestData::getGetData(TDStaticDefined::$popupControlExpandFun).'\');void(0);" title="'
			.TDLanguage::$choose_column_button_tip.'"><i class="'.TDThemeDifPart::OKIcon().'"></i></a>';	
			$columns = array_merge(array('chooseIdForAdd'=>$chooseIdCkb),$columns);	
		} else 
			*/
		if($this->use_id_checkbox || in_array('deletemore',$this->allow_actions) || (TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_KEY) == TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH)) {
			$chooseIdCkb= array(
				'header'=>'<input type="checkbox" onclick="checkboxChooseUnChooseAll('."'"."checkboxid".$this->markMuduleIdStr."[]"."'".',this.checked)" />',
		   		'type' => "raw", 
		    		//'htmlOptions' => array("style"=>"width:20px;"),
				'value' => '"<input type=checkbox name=checkboxid'.$this->markMuduleIdStr.'[] value=$data->getPrimaryKey />"',	
			); 
			if($this->use_id_checkbox) {
				$chooseIdCkb['footer'] = !empty($this->forJsMethodName) ? '<a href="javascript:getChoooseedID(\''.$this->forJsMethodName.'\','.$this->toolModuleId.','
				.$this->forModuleId.','.$this->forJsMethodTableId.',\''.$this->markMuduleIdStr.'\');void(0);" title="'
				.TDLanguage::$choose_column_button_tip.'"><i class="'.TDThemeDifPart::OKIcon().'"></i></a>' : "";	
				$columns = array_merge(array('chooseIdForAdd'=>$chooseIdCkb),$columns);
			} else if(in_array('deletemore',$this->allow_actions)) {
				$deleteAllBtn = '<a href="javascript:deleteChooseedAllRow('."'"."checkboxid".$this->markMuduleIdStr."[]"."'".','
				.TDTableColumn::getTableCollectionID($this->tableName).');void(0);" title="'
				.TDLanguage::$Operate_delete_chooseed.'"><i class="icon icon-color icon-close"></i></a>'; 
				if(isset($columns['CButton'])) { $columns['CButton']["header"] .= '&nbsp;'.$deleteAllBtn; } 
				else { $chooseIdCkb["footer"] = $deleteAllBtn; }
				$columns["checkboxid".$this->markMuduleIdStr] = $chooseIdCkb;	
			} else if(TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_KEY) == TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH) {
				$popupColumnId =  TDRequestData::getGetData(TDStaticDefined::$popupSearchColumnIdStr);
				$foreignChooseMore = TDModelDAO::getFieldById(TDTable::$too_table_column,$popupColumnId,"choose_more");
				if($foreignChooseMore == 1) {
					$chooseIdCkb['footer'] = '<a href="javascript:popupSearchChooseedMore(\''.$popupColumnId.'\',\''
					.TDRequestData::getGetData(TDStaticDefined::$popupSearchForeignFieldId).'\',\''.$this->markMuduleIdStr.'\');void(0);" title="'
					.TDLanguage::$choose_column_button_tip.'"><i class="'.TDThemeDifPart::OKIcon().'"></i></a>';	
					$columns = array_merge(array('chooseIdForAdd'=>$chooseIdCkb),$columns);	
				}
			}		 
		}
		if($this->use_id_redio) {
			$chooseIdradio= array(
				'header'=>'',
		   		'type' => "raw", 
		    	//'htmlOptions' => array("style"=>"width:20px;"),
				'value' => '"<input style=\'margin-left:none;\' type=radio name=idradio value=$data->getPrimaryKey />"',	
			);
			$columns = array_merge(array('chooseIdradio'=>$chooseIdradio),$columns);
		}
		return $columns;
	}

	private function getExpandOperateCButton() {
		$result = array();
		$operateTypeKey = TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_KEY); 	
		if(empty($operateTypeKey)) {
			if(TDRequestData::getGetData('is_single_page') == 1) {
				$this->pageObj->layout = TDLayout::getSinglePage();
			}
		} else if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE && TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE) == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE_ONE) {
			$this->pageObj->layout = TDLayout::getSinglePage();
			$result = array('header'=> TDLanguage::$form_popup_saerch_choose,'type'=>'raw'
			,'value'=>'"<button type=\'button\' class=\'btn\' onclick=\"controlPopupChooseed(\'$data->id\',\''.TDRequestData::getGetData(TDStaticDefined::$popupControlExpandFun).'\')\" >
			<li class=\'icon-check\'></li></button>"');	
		} else if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE && TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE) == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE_MORE) {
			$this->pageObj->layout = TDLayout::getSinglePage();
			$valueStr = '';
			$chooseIdCkb= array(
				'header'=>'<input type="checkbox" onclick="checkboxChooseUnChooseAll('."'"."checkboxid".$this->markMuduleIdStr."[]"."'".',this.checked)" />',
		   		'type' => "raw", 
		    	//'htmlOptions' => array("style"=>"width:20px;"),
				'value' => !empty($valueStr) ? $valueStr : '"<input type=checkbox name=checkboxid'.$this->markMuduleIdStr.'[] value=$data->getPrimaryKey />"',	
			); 
			$chooseIdCkb['footer'] = '<a href="javascript:controlPopupChooseedMore(\''.TDRequestData::getGetData(TDStaticDefined::$popupControlExpandFun).'\',\''.$this->markMuduleIdStr.'\');void(0);" title="'
			.TDLanguage::$choose_column_button_tip.'"><i class="'.TDThemeDifPart::OKIcon().'"></i></a>';	
			$result = $chooseIdCkb;
		} else if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_SEARCH) {
			$popupColumnId =  TDRequestData::getGetData(TDStaticDefined::$popupSearchColumnIdStr);
			//在使用多选框的情况下则通过上面的getColumns函数来控制,否则则使用默认的单选模式(直接按钮选择)
			$foreignChooseMore = TDModelDAO::getFieldById(TDTable::$too_table_column,$popupColumnId,"choose_more");
			if(empty($foreignChooseMore)) {	
				//选择之后,是否需要刷新重载
				$needReload = TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$popupColumnId,"onchange_reload");
				$needReload = $needReload == 0 ? "false" : "true";
				$result = array('header'=> TDLanguage::$form_popup_saerch_choose,'type'=>'raw'
				,'value'=>'"<button type=\'button\' class=\'btn\' onclick=\"popupSearchChooseed(\''.$popupColumnId.'\',\''
				.TDRequestData::getGetData(TDStaticDefined::$popupSearchForeignFieldId).'\',\''.TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN,$this->tableName).'\','.$needReload.')\" >
				<li class=\'icon-check\'></li></button>"');				
			}
			$popupFormPrimaryKey = TDRequestData::getGetData(TDStaticDefined::$popupSearchFormPrimaryKey);
			if(!empty($popupColumnId)) {
				$columnMode = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$popupColumnId,'`name`,`map_condition`,`table_collection_id`,`unique_check_condtion`');
				$pbaseColumnName = $columnMode["name"];
				$pbaseUniqCheckCOndtion = !empty($columnMode["unique_check_condtion"]) ? Fie_formula::getValue(null,$columnMode["unique_check_condtion"]) : '';
				$uniqueCheckWhereSQL = '';
				if(!empty($columnMode["map_condition"])) {
					$map_condition = "";	
					if(isset(Yii::app()->session["popup_condition_".$popupColumnId."_".$popupFormPrimaryKey])) {
						$map_condition = Yii::app()->session["popup_condition_".$popupColumnId."_".$popupFormPrimaryKey];	
					}
					if(!empty($map_condition)) {
						$this->model->getDbCriteria()->addCondition($map_condition);
					}
				}
				if(isset($_GET[TDStaticDefined::$popupSearchUniqueColumnIdsStr]) && !empty($_GET[TDStaticDefined::$popupSearchUniqueColumnIdsStr])) {
					$uniqueColumnIds = explode("---",$_GET[TDStaticDefined::$popupSearchUniqueColumnIdsStr]);
					$uniqueValues = explode("---",$_GET[TDStaticDefined::$popupSearchUniqueColumnIdsValue]); 
					foreach($uniqueColumnIds as $uIndex => $uniqueColumnId) {
						$uniqueCheckWhereSQL .= " and `".TDTableColumn::getColumnDBName($uniqueColumnId)."`='".$uniqueValues[$uIndex]."'";	
					}
				}
				if(!empty($uniqueCheckWhereSQL) || !empty($pbaseUniqCheckCOndtion)) {
					$tbname = TDTableColumn::getTableDBName($columnMode["table_collection_id"]);
					$querySql = "select `".$pbaseColumnName."` from `".$tbname."` where 1 ";
					$querySql .= $uniqueCheckWhereSQL;
					if(!empty($pbaseUniqCheckCOndtion)) {
						$querySql .= " and ".$pbaseUniqCheckCOndtion;
					}
					$uniqueCheckWhereSQL .= " limit 1000";
					$uniqueRes = TDModelDAO::getDB($tbname)->createCommand($querySql)->queryAll();
					$notInIds = '';
					foreach($uniqueRes as $urIndex => $item) {
						if(empty($item[$pbaseColumnName])) {
							continue;
						}
						$notInIds .= !empty($notInIds) ? "," : "";
						$notInIds .= $item[$pbaseColumnName];
					}
					if(!empty($notInIds)) {
						$this->model->getDbCriteria()->addCondition("t.id not in (".$notInIds.")");
					}	
				}
			}
			$this->pageObj->layout = TDLayout::getSinglePage();
		} else if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_LADDER_COLUMN) {
			$result = array('header'=> TDLanguage::$form_popup_saerch_choose,'type'=>'raw'
			,'value'=>'"<button type=\'button\' class=\'btn\' onclick=\"ladderColumnChooseed(
			\''.TDRequestData::getGetData(TDStaticDefined::$ladderColumn_FieldColumnId).'\',\''
			.TDRequestData::getGetData(TDStaticDefined::$ladderColumn_FieldTextId).'\',\''.
			TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN,$this->tableName).'\',this)\" >
			<li class=\'icon-check\'></li></button>"');				
			$this->use_id_checkbox = false;
			$this->pageObj->layout = TDLayout::getSinglePage();
		} else if($operateTypeKey == TDStaticDefined::$OPERATE_TYPE_POPUP_CHOOSE_BUTTON_ONCLICK) {
			$result = array('header'=> TDLanguage::$form_popup_saerch_choose,'type'=>'raw'
			,'value'=>'"<button type=\'button\' class=\'btn\' onclick=\"'.str_replace("'","'",str_replace("___","/",TDRequestData::getGetData('onclick'))).'\" >
			<li class=\'icon-check\'></li></button>"');				
			$this->use_id_checkbox = false;
			$this->pageObj->layout = TDLayout::getSinglePage();
		} 
		return $result;
	} 

	private function getModuleExpandeOpButton() {
		$cacheValue = TDSessionData::getCache("getModuleExpandeOpButton_".$this->moduleId);
		if($cacheValue === false) {
			$tmp = TDModelDAO::queryRowByPk(TDTable::$too_module,$this->moduleId,"expande_operate_button,expande_operate_title");
			if(empty($tmp["expande_operate_button"])) {
				$cacheValue = null;
			} else {	
				$cacheValue = array('header'=> $tmp["expande_operate_title"],'type'=>'raw',
				'value'=>'TDModule::getExpandeOperateButtonHtml($data,'.$this->moduleId.')');//'headerHtmlOptions'=>array('style'=>'min-width:80px;'),	
			}
			TDSessionData::setCache("getModuleExpandeOpButton_".$this->moduleId,$cacheValue);
		}
		return $cacheValue;
	}

	private function getCbuttronColumn($moduleId,$allowActions,$appendGridId="") {
		$moduleAlias = TDModelDAO::queryRowByPk(TDTable::$too_module,$moduleId,"btn_add_alias,btn_edit_alias,btn_delete_alias,btn_view_alias");
		$btnAddTitle = !empty($moduleAlias["btn_add_alias"]) ? $moduleAlias["btn_add_alias"] : TDLanguage::$title_add;
		$btnEditTitle = !empty($moduleAlias["btn_edit_alias"]) ? $moduleAlias["btn_edit_alias"] : TDLanguage::$common_button_update;
		$btnDeleteTitle = !empty($moduleAlias["btn_delete_alias"]) ? $moduleAlias["btn_delete_alias"] : TDLanguage::$common_button_delete;
		$btnViewTitle = !empty($moduleAlias["btn_view_alias"]) ? $moduleAlias["btn_view_alias"] : TDLanguage::$common_button_view;
		$addCheckCode = TDModelDAO::queryScalarByPk(TDTable::$too_module,$moduleId,"add_button_view");
		$addBtnIsView = !empty($addCheckCode) ?  Fie_formula::getValue(null,$addCheckCode) : true;
		if(!$addBtnIsView && in_array('add',$allowActions)) {
			$newAllowAcions = array();
			for($i=0; $i<count($allowActions); $i++){
				if($allowActions[$i] != 'add') {
					$newAllowAcions[] = $allowActions[$i];
				}
			}
			$allowActions = $newAllowAcions;
		}	
		$result = array();
		$allow_action = '';
		if(in_array('view',$allowActions)) { $allow_action .= '{view}'; }
		if(in_array('update',$allowActions) && !$this->gridview_only_view) { $allow_action .= '{update}'; }
		if(in_array('delete',$allowActions) && !$this->gridview_only_view) { $allow_action .= '{delete}'; }
		if(in_array('add',$allowActions)  || !empty($allow_action)) {	
		if(empty($this->button_add_url)) { 
			$this->button_add_url = TDPathUrl::createGridviewOpUrl($moduleId,TDPathUrl::$createGridviewOpUrl_TYPE_ADD,0,$this->gridview_button_appendParam); 
		}
		if(isset($_GET['MODULE_ROW_PKID']) && ($this->moduleId == TDStaticDefined::$editColumnsModuleId || $this->moduleId == TDStaticDefined::$gridviewColumnsModuleId)) {
			$this->button_add_url = TDPathUrl::createUrl("tDModule/chooseColumns", array("forModuleId"=>  intval($_GET['MODULE_ROW_PKID']),"toolModuleId"=>  $this->moduleId)); 
		}
		$updateButton = array();
		$editFromType = TDModelDAO::queryScalarByPk(TDTable::$too_module,$moduleId,"edit_from_type");
		$addHeader = '';
		if($editFromType == 1) {
			$addHeader = in_array('add',$allowActions) && !$this->gridview_only_view ? '<a id="'.$this->getGridviewId($appendGridId).'_add" title="'.$btnAddTitle
			.'" href="javascript:gridviewEdit_edit('.$moduleId.',\''.$this->getGridviewId($appendGridId).'\',0,\''.$this->button_add_url.'\')"><i title="'.$btnAddTitle.'" class="icon-color icon-plus"></i></a>' : '';
			$updateButton = array(
				'url'=> '"javascript:gridviewEdit_edit('.$moduleId.',\''.$this->getGridviewId($appendGridId).'\',$data->id,\'".TDPathUrl::createGridviewOpUrl('.$moduleId.','.
				TDPathUrl::$createGridviewOpUrl_TYPE_UPDATE.',$data->primaryKey,"'.$this->gridview_button_appendParam.'")."\');void(0);"',
				'visible'=>'\''.in_array('update',$allowActions).'\' && TDModule::getUpdateButtonViewBool($data,'.$moduleId.')',
				'imageUrl' => null,
				'label' => "<i class='icon-edit icon-white'></i>".$btnEditTitle,
				'options'=> array('class'=>'','editbt'=> 'tmpformEditBt_'.$moduleId),
			);
		} else {
			$addHeader = in_array('add',$allowActions) && !$this->gridview_only_view ? '<a class=\'popup\' pwidth=\''.$this->add_form_width.'\' pheight=\''.$this->add_form_height.'\' title="'.$btnAddTitle
			.'" href=\''.$this->button_add_url.'\'><i title="'.$btnAddTitle.'" class="icon-color icon-plus"></i></a>' : '';	
			$updateButton = array(
				'url'=> 'TDPathUrl::createGridviewOpUrl('.$moduleId.','.TDPathUrl::$createGridviewOpUrl_TYPE_UPDATE.',$data->primaryKey,"'.$this->gridview_button_appendParam.'")',
				'visible'=>'\''.in_array('update',$allowActions).'\' && TDModule::getUpdateButtonViewBool($data,'.$moduleId.')',
				'options'=> array('class'=>'popup','pwidth'=>$this->edit_form_width,'pheight'=>$this->edit_form_height),
			);
		}
		$result = array(
			'class' => 'CButtonColumn',
			'template'=> $allow_action,
			'headerHtmlOptions'=>array('style'=>'min-width:50px;text-align:center;'),
			'header' => $addHeader,
			'buttons'=>array(
			'view'=>array('url'=>'TDPathUrl::createGridviewOpUrl('.$moduleId.','.TDPathUrl::$createGridviewOpUrl_TYPE_VIEW.',$data->primaryKey,"'.$this->gridview_button_appendParam.'")',
			'visible'=>'\''.in_array('view',$allowActions).'\' && TDModule::getViewButtonViewBool($data,'.$moduleId.')','options'=>array('class'=>'popup','pwidth'=>$this->view_form_width,'pheight'=>$this->view_form_height)),
				'update'=>$updateButton, 
				'delete'=>array('url'=>'TDPathUrl::createGridviewOpUrl('.$moduleId.','.TDPathUrl::$createGridviewOpUrl_TYPE_DELETE.',$data->primaryKey,"'.$this->gridview_button_appendParam.'")',
				'visible'=>'\''.in_array('delete',$allowActions).'\' && TDModule::getDeleteButtonViewBool($data,'.$moduleId.')',),
			),  
			);	
		}	
		return $result;
	}

	public static function getCbuttonExpUrl($data,$expbtnId) {
		$code = TDModelDAO::queryScalarByPk(TDTable::$too_module_gridview_expbtn,$expbtnId,"set_url");
		if(!empty($code)) {
			return Fie_formula::getValue($data,$code);
		} else {
			return "";
		}
	}

	private function getCbuttronExpand($moduleId,$appendGridId="") {
		$rows = TDModelDAO::queryAll(TDTable::$too_module_gridview_expbtn,"too_module_id=".$moduleId." and is_active=1");
		$buttons = array();
		foreach($rows as $rIndex => $row) {
			$labeltext = $row["name"]; //Fie_formula::getValue($data,$row["labeltext"])
			///'.TDStaticDefined::$pageLayoutType.'/'.TDStaticDefined::$pageLayoutType_single.'
			$linkTypeHtml = '';
			if($row["link_type"] == 0) {
				$linkTypeHtml = ' class=\'btn btn-primary popup\' ';	
			} else if($row["link_type"] == 1) {
				$linkTypeHtml = ' class=\'btn btn-primary\' target=\'_blank\' ';	
			} else if($row["link_type"] == 2) {
				$linkTypeHtml = ' class=\'btn btn-primary\' ';	
			}
			$hrefHtml = '';
			if(!empty($row["set_url"])) {
				$hrefHtml = '\'".TDGridView::getCbuttonExpUrl($data,'.$row["id"].')."\'';
			} else {
				$hrefHtml = '\'".TDPathUrl::createGridviewOpUrl('.$moduleId.','.TDPathUrl::$createGridviewOpUrl_TYPE_UPDATE.',$data->primaryKey,\''.$this->gridview_button_appendParam.'/expbtnid/'.$row["id"].'\')."\'';	
			} 
			$value =  '"<a href='.$hrefHtml.' '.$linkTypeHtml.' >'.$labeltext.'</a>"';
			
			//所连接的模块是否有隐藏的判断条件控制
			$fmdDisCond = TDModelDAO::queryScalar(TDTable::$too_module_formmodule,"gridview_expbtn_id=".$row["id"],'tab_display_condition');
			if(!empty($fmdDisCond)) {
				$value = ' Fie_formula::getValue($data,TDModelDAO::queryScalar(TDTable::$too_module_formmodule,"gridview_expbtn_id='.$row["id"].'","tab_display_condition")) ? '.$value.' : ""';	
			}
			$buttons["expbtn".$rIndex] = array('header'=> $labeltext, 'type'=>'raw', 'value'=>$value);
			//'headerHtmlOptions'=>array('style'=>'min-width:80px;'),	
			/*
			$buttons["expbtn".$rIndex] = array(
				'url'=> 'TDPathUrl::createGridviewOpUrl('.$moduleId.','.TDPathUrl::$createGridviewOpUrl_TYPE_UPDATE.',$data->primaryKey,"'.$this->gridview_button_appendParam.'/expbtnid/'.$row["id"].'")',
				'visible'=>'true',
				'imageUrl' => null,
				'label' => "<i class='icon-edit icon-white'></i>".$labeltext,
				'title' => "",
				'options'=> array('class'=>'btn btn-info popup','title' => '','pwidth'=>$this->edit_form_width,'pheight'=>$this->edit_form_height),
			);
			*/
			//$row["labeltext"]
		}
		return $buttons;
	}

	private function getChildInnerGridviewColumns($moduleId) {
		$childGridviews = array();
		//来至form_module的嵌入gridview
		$moduleFormModules = TDModelDAO::queryAll(TDTable::$too_module_formmodule,'`form_module_id`='.$moduleId.' and `is_activate`=1 and `view_to_gridview`=1 order by `order`,id','ntable_module_id');
		foreach($moduleFormModules as $mditem) {	
			$childColumns = TDModelDAO::queryAll(TDTable::$too_module_gridview,'`module_id`=\''.$mditem["ntable_module_id"].'\' order by `order`');
			$haederHtml = '<ul style="list-style:none;">'; 
			//$columns = TDField::getGridViewColumns($this,$this->gridviewModuleId,$this->gridview_only_view);
			foreach($childColumns as $child) {
				$title = TDTableColumn::getColumnLabelName($child["table_column_id"]);
				$haederHtml .= "<li style='width:".(strlen($title) * 10)."px;float:left;'>".$title."&nbsp;</li>";
			}
			$haederHtml .= '</ul>';
			$childGridviews[] = array(
				'header'=> $haederHtml,
				'type'=>'raw',
				'value'=>'TDGridView::getChildGridviewValus($data,'.$mditem["ntable_module_id"].');',
			);
		}
		return $childGridviews;
	} 
	public static function getChildGridviewValus($data,$childGridviewModuleId) {
		$res = '';
		$str = TDStaticDefined::$childModels.'lottery_factor_id'.TDStaticDefined::$foreignKey_tableName.TDTableColumn::getTableDBNameByModuleId($childGridviewModuleId);
		$childColumns = TDModelDAO::queryAll(TDTable::$too_module_gridview,'`module_id`=\''.$childGridviewModuleId.'\' order by `order`');
		$childRows = $data->$str; 
		foreach($childRows as $childIndex => $childrow) {
			$res .= empty($res) ? '' : '<br/>';
			$res .= '<ul style="list-style:none;">';
			foreach($childColumns as $child) {
				$appendStr = TDTableColumn::getColumnAppendStr($child["table_column_id"],$child["belong_order_column_ids"]);
				$baseValue = TDFormat::getModelAppendColumnValue($childrow,$appendStr);
				$params = array( 'tableColumnId'=>$child["table_column_id"], 'value' => $baseValue, 'model' =>$childrow,);
				$viewCode = null;
				if(!empty($baseValue)) {
					$tmpModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$params["tableColumnId"],'`group_id`,`column_type`,`formula`,`name`');
					if($tmpModel["column_type"] == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN || !empty($tmpModel["formula"])) { 
						$viewCode = Fie_formula::computeFormula($childrow,TDTableColumn::getColumnAppendStr($params["tableColumnId"],""));
					}
				}
				if(empty($viewCode)) {
					$inputType = TDTableColumn::getInputTypeByColumnId($params['tableColumnId']);		
					$viewCode = $baseValue;
					if(method_exists($inputType,'viewHtml')) {
						$fie = new $inputType();
						$viewCode = $fie->viewHtml($params);	
					}
				}
				$title = TDTableColumn::getColumnLabelName($child["table_column_id"]);
				$res .= "<li style='width:".(strlen($title) * 10)."px;float:left;".(count($childRows) - 1 > $childIndex ? "border-bottom:1px dotted #999;" : "")."'>".$viewCode."&nbsp;</li>";
			}
			$res .= '</ul>';
		}
		return $res;
	} 
	
	
	public function getDataProvider() {
		if(!empty($this->gridview_query_group)) { $this->model->getDbCriteria()->group = Fie_formula::getValue(null,$this->gridview_query_group); }
		if(!empty($this->expande_select_sql)) { $exsq = Fie_formula::getValue(null,$this->expande_select_sql); if(!empty($exsq)) {$this->model->getDbCriteria()->select .= ",".$exsq;}  }
		if(!empty($this->having_sql)) { $this->model->getDbCriteria()->having = Fie_formula::getValue(null,$this->having_sql); }

		if(!empty($this->gridview_default_order)) {
			$this->model->getDbCriteria()->order = $this->gridview_default_order;	
		} else {
			$orderInputTypeColumn = TDTable::geteOrderStr($this->tableName);
			if(!empty($orderInputTypeColumn)) {
				$this->model->getDbCriteria()->order = $orderInputTypeColumn;
			}
		}
		if(strpos(strtolower($_SERVER["REQUEST_URI"]),"expandtabletreedata") === false) { //非Tree展开的时候 
			$searchStr  = isset($_GET["advSearch_columnId"]) ? TDCommon::getArrayValuesToString($_GET["advSearch_columnId"]) : "";
			if(empty($searchStr)) { //非搜索查询的时候
				$pidColumnId = Fie_pid::getPidColumnIdByTableId(TDTableColumn::getTableCollectionID($this->tableName));
				//存在于当前gridview显示列中
				if(!empty($pidColumnId) && TDModelDAO::queryScalar(TDTable::$too_module_gridview,"module_id=".$this->moduleId." and table_column_id=".$pidColumnId,"count(*)") > 0) {
					$this->model->getDbCriteria()->addCondition('`'.TDTableColumn::getColumnDBName($pidColumnId).'`=0');//pid=0
				}
			}
		} else {
			$this->queryNotSplitePage = true;
		}
		if(!empty($this->gridview_default_condition)) {
			if(strpos($this->model->getDbCriteria()->condition,$this->gridview_default_condition) === false) {
				$this->model->getDbCriteria()->addCondition($this->gridview_default_condition);
			}
		}
		if(!empty($this->gridview_join_sql)) {
			if(strpos($this->model->getDbCriteria()->condition,$this->gridview_join_sql) === false) {
				$this->model->getDbCriteria()->join .= ' '.$this->gridview_join_sql;
			}
		}
		//menu link condtion
		if(isset($_GET["mitemId"])) {
			$mitemId = TDPathUrl::getGETParam('mitemId');
			if(!empty($mitemId)) {
				$target_condition = TDModelDAO::queryScalarByPk(TDTable::$too_menu_items,$mitemId,"target_condition");
				$target_condition = !empty($target_condition) ? Fie_formula::getValue(null,$target_condition) : "";	
				if(!empty($target_condition)) { $this->model->getDbCriteria()->addCondition($target_condition);	}

				$target_join_sql = TDModelDAO::queryScalarByPk(TDTable::$too_menu_items,$mitemId,"target_join_sql");
				$target_join_sql = !empty($target_join_sql) ? Fie_formula::getValue(null,$target_join_sql) : "";	
				if(!empty($target_join_sql)) { $this->model->getDbCriteria()->join .= ' '.$target_join_sql; }
			}
		}
		//搜索
		$conditionSql = TDSearch::getSearchConditionSql(TDTableColumn::getTableCollectionID($this->tableName));
		if(isset($_GET["is_from_expand_tree"]) && $_GET["is_from_expand_tree"] == "1") { $conditionSql = ""; }
		if(!empty($conditionSql)) {
				$this->model->getDbCriteria()->addCondition($conditionSql);
		}
		$dataProvider = $this->model->search();
		if(!$this->allow_pagination || $this->queryNotSplitePage) {
			$dataProvider->pagination->pageSize = 5000;
		} else {
			$dataProvider->pagination->pageSize= $this->page_item_count;
		}
		//如果在mysql模块中调用时,会在读取字段的时候设置可排序字段
		if($this->moduleId == TDStaticDefined::$mysqlCommonModuleId) {
			$dataProvider->sort = array('attributes' => $this->forJsMethodUseOrderColumns);	
		} else {
			$dataProvider->sort = array('attributes' => TDModule::getOrderColumns($this->gridviewModuleId));	
		}
		if(!empty($this->tree_table_column_id)) {
			$rows = $dataProvider->getData();
			$treeColName = TDTableColumn::getColumnDBName($this->tree_table_column_id);
			$tmpIndex = 1;
			if(count($rows) > 0) {
				TDSessionData::setLastTablePkId($rows[0]->tableName,$rows[0]->primaryKey);
			}
			foreach($rows as $tmpKey => $row) {
				$value = TDFormat::getModelAppendColumnValue($row,$treeColName);
				$rdNum = time().'_'.$tmpIndex++;
				if(!empty($value)) {
					//如果有多个column 对应的 value 的搜索条件则使用 
					//FactorySearch::$expand_tree_str_key_str  分隔
					$foreignTableId = TDTableColumn::getColumnTableCollectionId($value);
					$aId = "oca".$rdNum;
					$row->name = '<a id="'.$aId.'" href="javascript:expandTableTreeData(\''
					.$this->gridviewModuleId.'\',\''
					.TDTable::getTableColumnId(TDTableColumn::getTableCollectionID($this->model->tableName),'table_collection_id')
					.'\',\''.$foreignTableId.'\',\''.$row->primaryKey.'\',\''.$rdNum.'\',\''.TDFormat::getUrlExpandParamStr().'\');void(0);">'
					.TDCommonCss::$tree_closeed_icon.'</a><input type="hidden" id="belongIds'.$rdNum.'" value="" expand="belongid" />&nbsp;'.$row->name;
				} else {
					//输入类型为foreignkey的自动判断为引用外键
					if($row->tableName == TDTable::$too_table_column
						&& $row->table_column_input_id == Fie_foreignkey::getInputTypeId() 
						&& !empty($row->map_table_collection_id)) {
						$foreignTableId = $row->map_table_collection_id;
						$aId = "oca".$rdNum;
						$row->name = '<a id="'.$aId.'" href="javascript:expandTableTreeData(\''
						.$this->gridviewModuleId.'\',\''
						.TDTable::getTableColumnId(TDTableColumn::getTableCollectionID($this->model->tableName),'table_collection_id')
						.'\',\''.$foreignTableId.'\',\''.$row->primaryKey.'\',\''.$rdNum.'\',\''.TDFormat::getUrlExpandParamStr().'\');void(0);">'
						.TDCommonCss::$tree_closeed_icon.'</a><input type="hidden" id="belongIds'.$rdNum.'" value="" expand="belongid" />&nbsp;'.$row->name;	
					} else {	
						$row->name = '<input type="hidden" id="belongIds'.$rdNum.'" value="" expand="belongid" />'.$row->name; 	
					}
				}
			}	
			$dataProvider->setData($rows);
		} else {
			//处理pid存在的情况下，点击编辑保存后，只刷新pid父级别的刷新
			/*
			$colrows = TDModelDAO::queryAll(TDTable::$too_module_gridview,"module_id=".$this->gridviewModuleId,"table_column_id");
			$pidColumnId = 0;
			foreach($colrows as $col) {
				if(TDModelDAO::queryScalarByPk(TDTable::$too_table_column,$col["table_column_id"],"table_column_input_id") == 15) {
					$pidColumnId = $col["table_column_id"]; 
					break;
				}
			}
			if(!empty($pidColumnId)) {
				$rows = $dataProvider->getData();
				$pidColumn = TDTableColumn::getColumnDBName($pidColumnId);
			}
			*/
		}
		/*
		if(in_array($this->tableName,TDXml::xmlTables())) {
			$dataProvider->pagination->pageSize=100000;
			$xrows = TDXml::loadXmlToRows($this->tableName);
			$dataProvider->setData($xrows);
			$dataProvider->setTotalItemCount(count($xrows));
		}
		*/
		return $dataProvider;
	}

	public function isDisplaySearchView() { return $this->search_view_current != self::$search_view_undisplay; }
	
	public function craeteSearchLink() {
		if($this->isDisplaySearchView()) { return CHtml::link(TDLanguage::$advanced_search,'#',array('class'=>'search-button ','id'=>'search_button'.$this->markMuduleIdStr)); }
	}
	public function createSearch() {
		if($this->isDisplaySearchView()) {
	    	return	$this->pageObj->renderPartial(TDCommon::getRender('min_items/condition'),TDSearch::getConditionRenderParams(
			$this->search_view_current == self::$search_view_advanced_combination ,false,TDTableColumn::getTableCollectionID($this->tableName),0,$this->markMuduleIdStr),true);
		}
	}

	public function getGridviewId($appendGridId="") {
		return 'common-grid'.$this->markMuduleIdStr.$appendGridId;	
	}

	public function createGridView($echoHtml=true,$appendGridId="") {
		/*
		if(!empty($this->gridviewWidth) && TDSessionData::getClientWidth() > ($this->gridviewWidth - 80)) {
			$this->gridviewWidth = TDSessionData::getClientWidth()+80;
		}
		if(!empty($this->gridviewWidth) && $this->gridviewWidth - 80 > 0) { 
			echo '<style>.grid-view table { width:'.($this->gridviewWidth - 80).'px; }</style>';//<input type="hidden" value="'.($this->gridviewWidth - 80).'" id="popwindowWidth" />
		}
		echo '<style>.grid-view table { width:'.(TDSessionData::getClientWidth() - 330).'px; }</style>';//<input type="hidden" value="'.($this->gridviewWidth - 80).'" id="popwindowWidth" />
		*/
		//是否默认展开树形
		$pidColumnId = Fie_pid::getPidColumnIdByTableId(TDTableColumn::getTableCollectionID($this->tableName));
		//存在于当前gridview显示列中
		if(!empty($pidColumnId) && TDModelDAO::queryScalar(TDTable::$too_module_gridview,"module_id=".$this->moduleId." and table_column_id=".$pidColumnId,"count(*)") > 0) {
			if(TDModelDAO::getFieldById(TDTable::$too_module,  $this->moduleId,"default_expand_all_tree") == 1) {
				$this->button_add_html .= '<script>setTimeout("expandAllTree(true)",1000)</script>';
			}
		}	
		$columns = $this->getColumns($appendGridId);
		$gridViewId = $this->getGridviewId($appendGridId);
		echo TDModule::getReSetTableRowSpanJsHtml($columns,$this->moduleId,$gridViewId);
		$dataProvider = $this->getDataProvider();
		echo $this->button_add_html;
		$this->pageObj->widget('zii.widgets.grid.CGridView',array(
			'id' => $gridViewId,
			'dataProvider' => $dataProvider,
		    	//'summaryText'=>'',
			//'filter'=>$model,
			'cssFile' => null,
			'itemsCssClass' => TDCommonCss::$CGridView_ItemsCssClass,
			'columns' => $columns, 
		),!$echoHtml);
	}

}