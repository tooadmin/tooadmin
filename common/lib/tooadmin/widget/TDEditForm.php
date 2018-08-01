<?php
class TDEditForm extends TDWidget {
	
	public $validateErrors = array();
	public $is_popup_window = true;
	public $outside_validate_errors = array();
	public $isGridviewTmpForm = false;
	public $isTmpSaveStay = false;

	public $isControlEditForm = false;//插件试编辑表单
	public $controlExpandFun = "";
	public $controlParmStr = "";
	public $after_close_form_run_js = "";
	public function __construct($pageObj,$moduleId,$pkId =null,$extendParams=array()) {
		if(!empty($extendParams)) {
			if(isset($extendParams['is_popup_window'])) {
				$this->is_popup_window = $extendParams['is_popup_window'];
			}
			if(isset($extendParams['outside_validate_errors'])) {
				$this->outside_validate_errors = $extendParams['outside_validate_errors'];
			}
		}
		if(isset($_REQUEST["is_popup_window"])) {
			$this->is_popup_window = $_REQUEST["is_popup_window"];
		}
		if(isset($_POST["isGridviewTmpForm"]) && $_POST["isGridviewTmpForm"] == "1") {
			$this->isGridviewTmpForm = true;
		}
		if(isset($_POST["isTmpSaveStay"]) && $_POST["isTmpSaveStay"] == "1") {
			$this->isTmpSaveStay = true;
		}
		if(isset($_GET[TDStaticDefined::$PARAM_AFTER_CLOSE_FORM_TREE_JS])) {
			//合并然后再展开所以两次
			$this->after_close_form_run_js = 'parent.parent.'.urldecode($_GET[TDStaticDefined::$PARAM_AFTER_CLOSE_FORM_TREE_JS]).";";
			$this->after_close_form_run_js .= 'parent.parent.'.urldecode($_GET[TDStaticDefined::$PARAM_AFTER_CLOSE_FORM_TREE_JS]).";";
		}
		if(TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_KEY) == TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_EDIT) {
			$this->isControlEditForm = true;
			$this->controlExpandFun = TDRequestData::getGetData(TDStaticDefined::$popupControlExpandFun);
			$this->controlParmStr = TDRequestData::getGetData(TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_PARAM);
		}
		parent::__construct($pageObj,$moduleId,$pkId);
	}

	public function afterInit() { 
		if(isset($_POST['postreload'])) {
			$formValidate = new TDFormValidateSave($this->model);
			$result = $formValidate->setModelFormData();
			$result = $formValidate->run_setColumnData_Formula_Items($result);
			if(!empty($result)) {
				$newFileArray = isset($result['newFileArray']) ? $result['newFileArray'] : array();
				foreach($newFileArray as $key => $value) {
					if(is_file($value)) unlink($value);
				}
			}
		} else if(isset($_POST[TDStaticDefined::$formModelName])) {
			$isForTmpForm = false;
			$normal = array();
			$validate = array();
			$validateOtherErrors = array();
			$rw = TDModelDAO::queryRowByPk(TDTable::$too_module,$this->moduleId,"is_simulate_form,simulate_code");
			if(!empty($rw) && $rw["is_simulate_form"] == 1) {
				$isForTmpForm = true;
				$formValidate = new TDFormValidateSave($this->model,array(),$this->outside_validate_errors,$this->moduleId);
				$formValidate->runSaveFlow(TDCommon::$outputErrorType_alert,true,false);
				$validate =  $formValidate->validateUnPass;//error array
				$normal = $formValidate->validatePass; 
				$validateOtherErrors = $formValidate->validateOtherErrors;
				if(!empty($rw["simulate_code"])) {
					$model = $this->model; 
					eval($rw["simulate_code"]);
					if(isset($error) && !empty($error)) {
						$validateOtherErrors[] = array("msg"=>$error);
					}
				}
			}	
			if(!$isForTmpForm) {
				$firstIsNewRecord = $this->model->isNewRecord; 
				$isHasModuleFormModule = false;
				if($firstIsNewRecord) {
					$isHasModuleFormModule = TDField::checkHasModuleFormModuleForEdit($this->moduleId);
				}
				$formValidate = new TDFormValidateSave($this->model,array(),$this->outside_validate_errors,$this->moduleId);
				$formValidate->runSaveFlow(TDCommon::$outputErrorType_alert,true,false);
				$validate =  $formValidate->validateUnPass;//error array
				$normal = $formValidate->validatePass; 
				$validateOtherErrors = $formValidate->validateOtherErrors;
			}	
			$afterCloseFormJs = '';
			if($this->isControlEditForm && !empty($this->controlExpandFun)) {
				$afterCloseFormJs = 'setTimeout("parent.parent.'.$this->controlExpandFun.'('.$this->model->id.')",200);';
			}
			if(!$isForTmpForm && $firstIsNewRecord && $isHasModuleFormModule && !$this->model->isNewRecord && empty($validate) && empty($validateOtherErrors)) {
				$updateUrl = $_SERVER['REQUEST_URI'];
				unset($_POST);
				$updateUrl = str_replace("id/0","id/".$this->model->primaryKey."/vlasttb/1",$updateUrl);	
				//echo '<script>parent.location.href="'.$updateUrl.'";</script>';
				echo '<script>parent.loadingFinish();parent.reloadFormToModuleForm("'.$updateUrl.'");'.$afterCloseFormJs.'</script>';
			} else {	
				if($this->isGridviewTmpForm) {
					echo '<script>parent.loadingFinish();';
					echo "var validateData = ".json_encode(array('validateResult' => empty($validate) && empty($validateOtherErrors) ? true : false,
					'datas' => $validate,'normal'=>$normal,'otherErrors'=>FieldFactory::getErrorsHTML($validateOtherErrors),)).";"; 
					echo 'parent.validateForGridviewTmpForm(validateData,"common-grid'.$this->markMuduleIdStr.'");'.$afterCloseFormJs.'</script>';
				} else {
					echo '<script>parent.loadingFinish();';
					echo "var validateData = ".json_encode(array('validateResult' => empty($validate) && empty($validateOtherErrors) ? true : false
					,'datas' => $validate,'normal'=>$normal,'otherErrors'=>FieldFactory::getErrorsHTML($validateOtherErrors),)).";";
					if($this->is_popup_window) {
						$isRefresiGridView = "true";
						if(!empty($this->after_close_form_run_js)) {
							$isRefresiGridView = "false";
							$afterCloseFormJs .= $this->after_close_form_run_js;
						}
						if($this->isTmpSaveStay) {
							echo 'parent.validateSaveResultFromPopup(validateData,false,"common-grid'.$this->markMuduleIdStr.'",'.$isRefresiGridView.');'.$afterCloseFormJs.'</script>';
						} else {
							echo 'parent.validateSaveResultFromPopup(validateData,true,"common-grid'.$this->markMuduleIdStr.'",'.$isRefresiGridView.');'.$afterCloseFormJs.'</script>';
						}
					} else {
						echo 'parent.validateSaveResultFromCurrent(validateData);'.$afterCloseFormJs.'</script>';
					}	
				}
			}
			exit;		
		}		
	}

	
	public function createEditForm() {
		$isUseTmpSave = TDSessionData::currentUserIsAdmin() && $this->model->isNewRecord;
		TDField::setModelBeforFormLoad($this->model,$this->formModuleId);	
		$form=TDStaticDefined::getTmpController()->beginWidget('CActiveForm', array(
		'id'=>'common-form',
		'enableAjaxValidation'=>false,
		'clientOptions' => array('validateOnSubmit' => false),
    		'htmlOptions' => array('class'=>TDCommonCss::$CActiveForm_htmlOptions_class,'enctype'=>'multipart/form-data'
		,'target'=>'tmpValidateFrame',"onkeydown"=>"if(event.keyCode==13 && event.target.type != 'textarea')return false;"),));
		echo '<fieldset>';
		TDField::createFormEditField($this->formModuleId,$this->model);
		echo '<input type="hidden" value="'.TDStaticDefined::$formModelName.'" name="modelName" />';
		echo '<input type="hidden" value="0" name="isTmpSaveStay" id="isTmpSaveStay" />';
		echo '<iframe name="tmpValidateFrame" style="display:none;"></iframe>';
		/*
		if($isUseTmpSave) {
			echo '<div class="form-actions">';
				//'<button type="submit" class="btn btn-primary" onclick="loadingStart()">保存</button>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" onclick="tmpSaveStay()">暂存(开发用)</button>';
			echo 	'</div>';
		}
		echo '</fieldset>';
		if($isUseTmpSave) {	
			echo  '<script>function tmpSaveStay() { $("#isTmpSaveStay").val("1"); $("#common-form").submit(); $("#isTmpSaveStay").val("0");  }</script>';
		}
		*/
 		TDStaticDefined::getTmpController()->endWidget();
	}
}
