<?php

class TDCommonController extends TDController
{
	public function actions() {
		return array(
			'captcha' => array( 
			    	'class' => 'CCaptchaAction',
                		'backColor' => 0xFFFFFF, //背景颜色  
				'minLength' => 4, //最短为4位  
                		'maxLength' => 4, //是长为4位  
                		'transparent' =>true, //显示为透明  
                		'testLimit' => 1,//相同验证码显示的次数
            		),
        	);
	}

	public function actionsRemark() {
		return array(
			'ControllerRemark' => TDLanguage::$CommonController_Remark, 
			'actionView' => TDLanguage::$CommonController_View,	
			'actionDelete' => TDLanguage::$CommonController_Delete,	
			'actionAdmin' => TDLanguage::$CommonController_Admin,	
			'actionEdit' => TDLanguage::$CommonController_Edit,	
			'actionEditBool' => TDLanguage::$CommonController_EditBool,	
			'actionPopupSearch' => TDLanguage::$CommonController_PopupSearch,	
		    'actionCustome' => TDLanguage::$CommonController_Custome, 
		    'actionRender' => TDLanguage::$CommonController_Render,
		    'actionExpandeFunction' => TDLanguage::$CommonController_ExpandeFunction,
		    'actionQuery' => TDLanguage::$CommonController_Query,
		    'actionMenuItems' => TDLanguage::$CommonController_MenuItems,
			'actionLayoutCompos' => TDLanguage::$CommonController_LayoutCompos,
			'actionControlPopup' => 'actionControlPopup',
		);	
	}

	public function actionView($moduleId) {
		$tableName = TDTableColumn::getTableDBNameByModuleId($moduleId);
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,$tableName);
		$view = new TDView($this,$moduleId,$pkId);
		$this->render(TDPathUrl::getViewRender($tableName),array('view'=>$view,));
	}

	public function actionDelete($moduleId) {
		if($moduleId == TDStaticDefined::$mysqlCommonModuleId) {
			$tableName = TDTableColumn::getTableDBName(intval($_GET[TDStaticDefined::$mysqlCommonMudelTabId])); 
		} else {
			$tableName = TDTableColumn::getTableDBNameByModuleId($moduleId); 
		}
		if(!TDPermission::checkDeletePermission(TDTableColumn::getTableCollectionID($tableName))) {
			throw new CHttpException(400,TDLanguage::$operate_no_permission);
		}
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,$tableName);
		TDDataDAO::deleteARow($tableName,$pkId,$moduleId);
		return true;
	}

	public function actionMenuItems() {
		$minInd = TDRequestData::getGetData('mnInd',0,true);
		If(empty($minInd)) {
			$topmnInd = TDRequestData::getGetData('topmnInd',0,true);
			$minInd = intval(TDModelDAO::queryScalar(TDTable::$too_menu,"pid=" .$topmnInd." and `is_show`=1 order by `order`","id"));
			$_GET["mnInd"] = $minInd; 
		}
		$items = TDModelDAO::queryAll(TDTable::$too_menu_items,"menu_id=".$minInd." and `is_show`=1 and layout_menu_items_pid=0 ".
		Yii::app()->session['menu_items_permission_str']." order by `order`");	
		$this->layout = TDLayout::getLayout();
		$this->render('menu_items',array('items'=>$items));
	}

	public function actionLayoutCompos() {
		$this->layout = TDLayout::getInnerPage();
		$this->render('layout_compos');
	}

	public function actionAdmin() {
		$moduleId = TDRequestData::getGetData('moduleId');
		$gridview_top_file = TDModelDAO::queryScalarByPk(TDTable::$too_module, $moduleId,"gridview_top_file");
		if(!empty($gridview_top_file)) {
			$value = Fie_formula::getValue(null,$gridview_top_file);
			if(!empty($value)) { Too::daoFile($value); }
		}
		$gridview =  new TDGridView($this,$moduleId);
		if(isset($_REQUEST["condition_expert_excel"]) && $_REQUEST["condition_expert_excel"] == "1") {
			$excel = new TDToolExcel();
			$excel->expertByTDGRidView($gridview);
			exit;
		}
		$gridview_foot_file = TDModelDAO::queryScalarByPk(TDTable::$too_module,$moduleId,"gridview_rewrite_file"); 
		if(!empty($gridview_foot_file)) { 
			$value = Fie_formula::getValue(null,$gridview_foot_file);
			if(!empty($value)) { $this->render("custome_transfer",array("view_file"=>$value));  } 
		} else {
			$this->render('admin',array('gridView'=>$gridview));
		}
	}
	
	public function actionEdit($moduleId) {
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,TDTableColumn::getTableDBNameByModuleId($moduleId));
		$editForm = new TDEditForm($this,$moduleId,$pkId);
		$this->render(TDPathUrl::getEditRender($editForm->model->tableName()),array('editForm'=>$editForm,));
	}

	public function actionEditBool() {
		$bool = TDRequestData::getGetData("bool");
		$bool = ($bool == 'true' || $bool == '1') ?  1 : 0;
		$tableName = TDRequestData::getGetData('tableName');
		$boolColumn = TDRequestData::getGetData('boolColumn');
		$model = TDModelDAO::getModel($tableName,TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,$tableName));
		if(!empty($model)) {
			$model->$boolColumn = $bool;
			$formValidate = new TDFormValidateSave($model,array(),array(),$_GET["moduleId"]);
			$formValidate->runSaveFlow(TDCommon::$outputErrorType_alert);
			$validate =  $formValidate->validateUnPass;//error array
			$validateOtherErrors = $formValidate->validateOtherErrors;
			if(!empty($validate)) { echo "error:".TDCommon::getArrayValuesToString($validate); exit; }
			if(!empty($validateOtherErrors)) { echo "error:".TDCommon::getArrayValuesToString($validateOtherErrors); exit; }
		}
	}

	public function actionPopupSearch() {
		$this->layout = "//layouts/single_page";	
		$tableColumnId = TDRequestData::getGetData(TDStaticDefined::$popupSearchColumnIdStr);
		if(!empty($tableColumnId)) {
			$module_id = FieldRule::getModuleId($tableColumnId);
			if(!empty($module_id)) {
				TDRequestData::setGetModuleId($module_id);
				$this->actionAdmin();
			}
		}
	}
	//控件popup
	public function actionControlPopup() {
		if(TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_KEY) == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE) {
			$this->layout = TDLayout::getSinglePage();
			$module_id = TDRequestData::getGetData(TDStaticDefined::$popupControlModuleId);
			$module_id = intval($module_id);	
			if(!empty($module_id)) {
				TDRequestData::setGetModuleId($module_id);
				$this->actionAdmin();
			}
		}
	}

	public function actionCustome() {
		$menuId = TDRequestData::getGetData("mitemId");
		$this->layout = TDLayout::getLayout();
		if(empty($menuId)) { $menuId = TDRequestData::getGetData("topmnInd"); }
		$page_top_file = TDModelDAO::queryScalarByPk(TDTable::$too_menu_items,$menuId,"page_top_file"); 
		if(!empty($page_top_file)) { 
			$value = Fie_formula::getValue(null, $page_top_file);
			if(!empty($value)) { Too::daoFile($value); }
		}

		$page_view_file = TDModelDAO::queryScalarByPk(TDTable::$too_menu_items,$menuId,"page_view_file"); 
		if(!empty($page_view_file)) { 
			$value = Fie_formula::getValue(null,$page_view_file);
			if(!empty($value)) { $this->render("custome_transfer",array("view_file"=>$value));  } 
		}
	}

	public function actionFormModuleCustome() {
		$moduleFormModuleRowId = TDRequestData::getGetData(TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID);
		$this->layout = TDLayout::getLayout();
		$page_top_file = TDModelDAO::queryScalarByPk(TDTable::$too_module_formmodule,$moduleFormModuleRowId,"page_code_dao"); 
		if(!empty($page_top_file)) { 
			$value = Fie_formula::getValue(null, $page_top_file);
			if(!empty($value)) { Too::daoFile($value); }
		}
		$page_view_file = TDModelDAO::queryScalarByPk(TDTable::$too_module_formmodule,$moduleFormModuleRowId,"page_code_view"); 
		if(!empty($page_view_file)) { 
			$value = Fie_formula::getValue(null,$page_view_file);
			if(!empty($value)) { $this->render("custome_transfer",array("view_file"=>$value));  } 
		}
	}

	public function actionQuery() {
		$mitemId = TDRequestData::getGetData("mitemId");
		$array = TDAnalyzeQuery::getData($mitemId);	
		$this->layout = TDLayout::getSinglePage();
		$this->render('min_items/analyze_query',$array);
	}

	public function actionExpandeFunction() {
		$class = TDRequestData::getGetData(TDStaticDefined::$PARAM_EXPFUN_CLASS);
		$funName = TDRequestData::getGetData(TDStaticDefined::$PARAM_EXPFUN_NAME);
		$pr1 = isset(TDStaticDefined::$PARAM_EXPFUN_PR1) ? TDRequestData::getGetData(TDStaticDefined::$PARAM_EXPFUN_PR1) : null;
		$pr2 = isset(TDStaticDefined::$PARAM_EXPFUN_PR2) ? TDRequestData::getGetData(TDStaticDefined::$PARAM_EXPFUN_PR2) : null;
		$pr3 = isset(TDStaticDefined::$PARAM_EXPFUN_PR3) ? TDRequestData::getGetData(TDStaticDefined::$PARAM_EXPFUN_PR3) : null;
		if(!empty($class) && !empty($funName) && method_exists($class,$funName)) {
			if(!is_null($pr1) && !is_null($pr2) && !is_null($pr3)) {
				$class::$funName($pr1,$pr2,$pr3);
			} else if(!is_null($pr1) && !is_null($pr2) && is_null($pr3)) {
				$class::$funName($pr1,$pr2);
			} else if(!is_null($pr1) && is_null($pr2) && is_null($pr3)) {
				$class::$funName($pr1);
			} else if(is_null($pr1) && is_null($pr2) && is_null($pr3)) {
				$class::$funName();
			} 	
		}
		exit;
	}
	
	public function actionRender() { 
		if(isset($_GET["render_page"])) { 
			$render_folder = isset($_GET["render_folder"]) ? $_GET["render_folder"]."/" : ""; 
			$this->render($render_folder.$_GET["render_page"]);
		}
		//例如
		//TDPathUrl::createUrl("tDCommon/render",
		//array("render_folder" => "cust", "render_page" => "cloud_data")) 
	}
}
