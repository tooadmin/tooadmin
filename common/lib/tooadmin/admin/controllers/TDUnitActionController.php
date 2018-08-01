<?php

class TDUnitActionController extends TDController
{
	public function actionsRemark() {
		return array(
			'ControllerRemark' => TDLanguage::$UnitActionController_Remark,
			'actionUpdatePwd' => TDLanguage::$UnitActionController_UpdateInfo,
			'actionPopupConditionEdit' => TDLanguage::$UnitActionController_PopupConditionEdit, 
		    	'actionCreateQrcode'=> TDLanguage::$UnitActionController_CreateQrcode,
		    	'actionMysql' => TDLanguage::$UnitActionController_Mysql,
		    	'actionFileContent' => TDLanguage::$UnitActionController_FileConten,
		    	'actionCheckUpgrade' => TDLanguage::$UnitActionController_CheckUpgrade,
		    	'actionExportTableHtml' => TDLanguage::$UnitActionController_ExportTableHtml,
			'actionExportSysTable' => '导出系统表',
			'actionRefreshTableStruct' => TDLanguage::$UnitActionController_RefreshTableStruct,
			'actionOpenDevModel' => TDLanguage::$UnitActionController_OpenDevModel,
			'actionCloseDevModel' => TDLanguage::$UnitActionController_CloseDevModel,
			'actionStructMenu' => TDLanguage::$UnitActionController_STRUCT_MENU,
		    	'actionUserManage' => TDLanguage::$menu_user_manage
		);	
	}

	public function actionUpdatePwd() {
		$dataArray = array(
			'nickname_er' => '',
			'org_password_er' => '',
			'new_password_er' => '',
			'check_password_er' => '',
			'nickname' => TDSessionData::getNickName(),
			'org_password' => '',
			'new_password' => '',
			'check_password' => '',
		);
		if(isset($_POST['nickname'])) {
			$dataArray['nickname'] = $_POST['nickname'];
			$dataArray['org_password'] = $_POST['org_password'];
			$dataArray['new_password'] = $_POST['new_password'];
			$dataArray['check_password'] = $_POST['check_password'];

			if(empty($_POST['nickname'])) {
			 	$dataArray['nickname_er'] = TDLanguage::$unitAction_nickname_empty;
			 } else  if(empty($_POST['org_password'])) {
			 	$dataArray['org_password_er'] = TDLanguage::$unitAction_password_empty;
			 } else {
			  	$model = TDModelDAO::getModel(TDTable::$too_user)->findByPk(TDSessionData::getUserId());
			  	if($model->password != md5($_POST['org_password'])) {
			  		$dataArray['org_password_er'] = TDLanguage::$unitAction_oldpwd_error;
			  	} else {
			  		if(empty($_POST['new_password'])) {
					 	$dataArray['new_password_er'] = TDLanguage::$unitAction_newpwd_empty;
					 } else {
					 	if(strlen($_POST['new_password']) < 6) {
					 		$dataArray['new_password_er'] = TDLanguage::$unitAction_pwdless_length;
					 	} else {
					 		if($_POST['new_password'] != $_POST['check_password']) {
					 			$dataArray['check_password_er'] = TDLanguage::$unitAction_checkpwd_error;
					 		} else {
					 			$model->nickname = $_POST['nickname'];
					 			$model->password = md5($_POST['new_password']);
					 			if($model->save()) {
					 				TDCommon::tipMessage(TDLanguage::$unitAciton_update_success_relogin
									,TDPathUrl::createUrl('tDSite/logout'));
					 			}
					 		}
					 	}
					 }
			  	}
			 } 
		}
		$this->render('min_items/update_pwd',array('dataArray'=>$dataArray));
	}
	public function actionPopupConditionEdit() {
	    $this->layout = TDLayout::getSinglePage();
	    $condition_table_id = $_REQUEST['condition_table_id'];	
	    $condition_pk_id = !empty($_REQUEST['condition_pk_id']) ? $_REQUEST['condition_pk_id'] : 0;	
	    $conditionSql = TDSearch::getSearchConditionSql($condition_table_id);
	    $jsStr = "";
	    if(!empty($conditionSql)) {
		$analyzeDataJson = TDSearch::createCondtionAnalyzeDataJsonFormatStr();
            	$jsStr = '
		<script>	
		parent.condtionSetData("'.$conditionSql.'","'.$analyzeDataJson.'");
	   	parent.closeWindow();
		</script>';	    
		echo $jsStr;
		exit;
	    }
	    $is_use_combination = true;
	    $is_create_condition = true;
	    $this->render('min_items/condition',TDSearch::getConditionRenderParams($is_use_combination
	    ,$is_create_condition,$condition_table_id,$condition_pk_id));			
	}
	public function actionCreateQrcode() {
		TDPhpqrcode::includeLib();
		$sizeArray = array();
		$matrixPointSize = 10;
		for($i=12; $i>=1; $i--) {
			$sizeArray[$i] = (29*$i)."×".(29*$i);	
		}
		$content = ''; 
		$filename = '';
		if(isset($_POST['qr_content']) && !empty($_POST['qr_content'])) {
			$content = $_POST['qr_content'];
    		if (isset($_POST['qr_size']))
        	$matrixPointSize = min(max((int)$_POST['qr_size'], 1), 10);
    		if (!file_exists(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH))) 
			mkdir(TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH));
			$filename = time("YMDHis").'.png';
			$errorCorrectionLevel = 'L';
			QRcode::png($content,TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH).$filename,
			$errorCorrectionLevel, $matrixPointSize, 2); 

			$logo = false; //TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH).'logo46.png';
  			$png = TDPathUrl::getPathUrl(TDPathUrl::$TYPE_PATH).$filename;
			///"http://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=Hello+world&chld=L|1&choe=UTF-8";
  			$QR = imagecreatefrompng($png);
  			if($logo !== FALSE){
   				$logo = imagecreatefromstring(file_get_contents($logo));
   				$QR_width = imagesx($QR);
   				$QR_height = imagesy($QR);
       
   				$logo_width = imagesx($logo);
   				$logo_height = imagesy($logo);
          
   				// Scale logo to fit in the QR Code
   				$logo_qr_width = $QR_width/5;
   				$scale = $logo_width/$logo_qr_width;
   				$logo_qr_height = $logo_height/$scale;
   				$from_width = ($QR_width-$logo_qr_width)/2;
   				//echo $from_width;exit;
   				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0,
				$logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

				header('Content-type: image/png');
  				imagepng($QR);
  				imagedestroy($QR);
  			}
			echo empty($filename) ? '' : '<img src="'.TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL).$filename.'"/>';exit;
		}
		if(isset($_GET[TDStaticDefined::$pageLayoutType])) {
	    	$this->layout = TDLayout::getLayout();
		} else {
	    	$this->layout = TDLayout::getSinglePage();
		}
		$sizeField = CHtml::dropDownList('qr_size',$matrixPointSize,$sizeArray);
		$contentField = CHtml::textArea('qr_content',$content,array('style'=>'width:300px;height:200px;'));
		$qrCodeImgField = empty($filename) ? '' : '<img src="'.TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL).$filename.'"/>';
		$this->render('min_items/create_qrcode',
		array('sizeField'=>$sizeField,'contentField'=>$contentField,'qrCodeImgField'=>$qrCodeImgField));	
	}	
	public function actionExcuteSQL() {
		$sql = isset($_POST["sqltxt"]) ? $_POST["sqltxt"] : "";
		$result = "";
		if(!empty($sql)) {
			try {
				$result = TDModelDAO::getDBBySQL($sql)->createCommand($sql)->execute();
				$result  =  date("Y-m-d H:i:s")." ".TDLanguage::$sys_excute_sql_result.$result;
			}  catch (Exception $e) {
				$result = $e->getMessage();
			}
		}		
		$this->render("min_items/excute_sql",array("result"=> $result));
	}
	public function actionMysql() {
		$this->render("min_items/mysql",array());
	}
	public function actionCheckUpgrade() {
		TDUpgrade1_2_8::upgrade();
		TDUpgrade1_2_9::upgrade();
		TDUpgrade1_3_0::upgrade();
	}
	public function actionExportSysTable() {
		$sys1 = array( 'module_bank', 'module_province', 'module_province_city');
		$sys2 = array('too_login_log', 'too_role', 'too_user',);
		$sys3 = array( 'too_menu', 'too_module', 'too_module_formedit', 'too_module_formmodule', 'too_module_gridview',
		'too_table_collection', 'too_table_column', 'too_table_column_class', 'too_table_column_input');
		$upgradeSQL = new TDUpgradeSQL(); 
		$fileTxt = ''; 
		foreach($sys3 as $table) {
			$fileTxt .= $upgradeSQL->getCreateTableSQL($table,true);	
		}
		$fileName = "sysdb".date("Ymd"); 
		$fileFold = "assets/program/sysdbs/";
		if(!is_dir($fileFold)) {
			TDCommon::mkdir($fileFold);
		}	
		$fp = fopen($fileFold.$fileName.'.sql', "w"); 
		fwrite($fp,$fileTxt); fclose($fp); 
		$filezip = new TDFileZipUnZip();
		$filezip->create_zip(array($fileFold.$fileName.'.sql'),$fileFold.$fileName.".zip",true);
		echo TDPathUrl::getPathUrl(TDPathUrl::$TYPE_URL,$fileFold).$fileName.".zip";
	}
	public function actionExportTableHtml() {
		$tableHtml = isset($_POST["table"]) ? $_POST["table"] : ""; 
		$tool = new TDToolExcel();
		$url = $tool->exportByTableHtml($tableHtml);
		echo json_encode(array("url"=>$url));	
	}
	public function actionRefreshTableStruct() {
		$this->layout = TDLayout::getSinglePage();
		$this->render("min_items/refresh_table");
	}
	public function actionOpenDevModel() { TDSessionData::setCurToDevModel(); }
	public function actionCloseDevModel() { TDSessionData::closeDevModel(); }
	public function actionQuikReorderMenu() {
		$menuId = intval($_POST["reOrderMenuId"]);
		$go0Back1 = $_POST["go0OrBack1"];
		$row = TDModelDAO::queryRowByPk(TDTable::$too_menu,$menuId);
		if($go0Back1 == 0) {
			$reRow = TDModelDAO::queryRowByCondtion(TDTable::$too_menu,"`pid`=".$row["pid"]." and `is_show`=1 and `order`<".$row["order"]." order by `order`");
			if(!empty($reRow)) {
				TDModelDAO::updateRowByPk(TDTable::$too_menu, $row["id"],array("order"=>$reRow["order"]));
				TDModelDAO::updateRowByPk(TDTable::$too_menu, $reRow["id"],array("order"=>$row["order"]));
			}
		} else {
			$reRow = TDModelDAO::queryRowByCondtion(TDTable::$too_menu,"`pid`=".$row["pid"]." and `is_show`=1 and `order`>".$row["order"]." order by `order`");
			if(!empty($reRow)) {
				TDModelDAO::updateRowByPk(TDTable::$too_menu, $row["id"],array("order"=>$reRow["order"]));
				TDModelDAO::updateRowByPk(TDTable::$too_menu, $reRow["id"],array("order"=>$row["order"]));
			}
		}
		echo "success";
	}
	public function actionStructMenu() {
		$this->layout = TDLayout::getSinglePage();
		$this->render("min_items/struct_menu");
	}
	public function actionUserManage() {
		$this->layout = TDLayout::getLayout();
		$this->render('min_items/user_manage');
	}
}
