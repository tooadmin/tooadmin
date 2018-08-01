<?php

class TDMinMysqlController extends TDController
{
	public function actionsRemark() {
		return array(
			'ControllerRemark' => TDLanguage::$TDMinMysqlController_Remark, 
			'actionView' => TDLanguage::$TDMinMysqlController_View,	
			'actionDelete' => TDLanguage::$TDMinMysqlController_Delete,	
			'actionAdmin' => TDLanguage::$TDMinMysqlController_Admin,	
			'actionEdit' => TDLanguage::$TDMinMysqlController_Edit,	
		);	
	}

	public function actionView($moduleId) {
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,TDTableColumn::getTableDBNameByModuleId($moduleId));
		$view = new TDView($this,$moduleId,$pkId);
		$this->render(TDPathUrl::getViewRender($view->model->tableName()),array('view'=>$view,));
	}

	public function actionDelete($moduleId) {
		$tableName = TDTableColumn::getTableDBNameByModuleId($moduleId); 
		if(!TDPermission::checkDeletePermission(TDTableColumn::getTableCollectionID($tableName))) {
			throw new CHttpException(400,TDLanguage::$operate_no_permission);
		}
		$pkId = TDPrimaryKey::getPrimaryKeyData(TDPrimaryKey::$PRIMARY_KEY_OPERATE_GET_URL_VALUE,$tableName);
		//if(in_array($tableName,  TDXml::xmlTables())) { $result = TDXml::delete($tableName,$pkId); } 
		//else { $result = TDEvents::deleteEven($tableName, $pkId); }
		$result = TDEvents::deleteEven($tableName, $pkId,true,$moduleId);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionAdmin() {
		$this->render('admin',array("mintb"=>  isset($_GET["mintb"]) ? $_GET["mintb"] : "gl_global" ));//array('gridView'=> new TDGridView($this,TDRequestData::getGetData('moduleId'))));
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
			$model->save();
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
