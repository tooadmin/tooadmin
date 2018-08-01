<script>
function refreshSession() {
	$.ajax({  type:'get', url:'<?php echo TDPathUrl::createUrl('tDAjax/refreshSession'); ?>', 
        success:function(html){  alert('<?php echo TDLanguage::$tip_msg_operate_ok; ?>'); }  });  
}
function openDevModel() {
	$.ajax({  type:'get', url:'<?php echo TDPathUrl::createUrl('tDUnitAction/openDevModel'); ?>', 
        success:function(html){  alert('<?php echo TDLanguage::$tip_msg_operate_ok; ?>'); window.location.reload(); }  });  
}
function closeDevModel() {
	$.ajax({  type:'get', url:'<?php echo TDPathUrl::createUrl('tDUnitAction/closeDevModel'); ?>', 
        success:function(html){  alert('<?php echo TDLanguage::$tip_msg_operate_ok; ?>'); window.location.reload(); }  });  
}
function refreshTableStruct() {
        popupWindow("<?php echo TDLanguage::$UnitActionController_RefreshTableStruct ?>", "<?php echo TDPathUrl::createUrl('tDUnitAction/refreshTableStruct'); ?>");
}
function quickEditMenu(id) {
        popupWindow("<?php echo TDLanguage::$CommonController_Edit ?>", "<?php echo TDPathUrl::createUrl('tDCommon/edit',array('moduleId'=>  TDStaticDefined::$devMenuModelId)); ?>/id/"+id,0,0,true,'window.location.reload()');
}
function quickAddMenu(pid) {
        popupWindow("<?php echo TDLanguage::$CommonController_Edit ?>", "<?php echo TDPathUrl::createUrl('tDCommon/edit',array('moduleId'=>  TDStaticDefined::$devMenuModelId)); ?>/id/0/qkm_pid/"+pid,0,0,true,'window.location.reload()');
}
function quickDeleteMenu(id) {
	if(window.confirm("<?php echo TDLanguage::$tip_msg_check_delete_current_menu ?>")) {
		$.ajax({  type:'post', url:'<?php echo TDPathUrl::createUrl('tDCommon/delete',array('moduleId'=> TDStaticDefined::$devMenuModelId)); ?>/id/'+id, 
        	success:function(html){  alert('<?php echo TDLanguage::$tip_msg_operate_ok; ?>'); window.location.reload(); }  });
	}	
}
function quickReorderMenu(id,go_0_back_1) {
	$.ajax({  type:'post', url:'<?php echo TDPathUrl::createUrl('tDUnitAction/quikReorderMenu'); ?>',data:'reOrderMenuId='+id+'&go0OrBack1='+go_0_back_1, 
        success:function(html){  window.location.reload(); }  });
}
</script>
<style>
	.navbar .nav > li > a {
		float: none;
		padding: 2px 9px 6px;
		line-height: 19px;
		color: #f5f5f5;
		text-decoration: none;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		border-right: 0px solid #3887B3;
		border-left: 0px solid #3887B3;
	}
	.dropdown-menu li {
		cursor:pointer;
	}
	.dropdown-menu li span {
		margin-left: 5px;
		padding-right: 5px;
	}
	.mainMenuSet { width:20px; }
	.mainMenuSet div ul li {
		color: #555555;
	}
	.dropdown-toggle {box-shadow:none !important;}
	.qkmenu { min-width: 85px !important; }
</style>
<div class="navbar">
	<div class="navbar-inner">
		<!-- <a class="brand"></a> -->
		<a href="<?php echo TDPathUrl::createUrl("/tDSite/index"); ?>">
		<span style="float: left; height: 30px; padding-right: 20px; 
		color: rgb(255, 255, 255); font-size: 16px; margin-top:12px;font-weight: bold;">
			<?php echo Yii::app()->params->admin_menu_name; ?>
		</span>
		</a>
		<ul class="nav">
			<?php
			$result = TDSessionData::getCache("userMenuBar_".TDSessionData::userMarkStr());
			if ($result === false) {
				$result = array();
				$rows = TDModelDAO::getDB(TDTable::$too_menu)->createCommand("select `id`,`name`,`is_home`,`pid` from ".TDTable::$too_menu
				.' where `is_show`=1 and `pid`=0 '.Yii::app()->session['menu_permission_str'].' order by `order`')->queryAll(); 
				foreach($rows as $index => $row) {
					$minInd = 0;
					$minIndRows = TDModelDAO::queryAll(TDTable::$too_menu,"pid=".$row["id"]." and `is_show`=1 ".
					Yii::app()->session['menu_permission_str']." order by `order`,`id`","id");
					foreach($minIndRows as $minIndRow) {
						$minInd = TDModelDAO::queryScalar(TDTable::$too_menu,"pid=".$minIndRow["id"]." and `is_show`=1 ".
						Yii::app()->session['menu_permission_str']." order by `order`,`id` limit 1","id");
						if(!empty($minInd)) { break; }
					}
					if(empty($minInd)) { 
						$url = TDPathUrl::getMenuItemLink($minInd, $row["id"],0, TDPathUrl::$menuForType_topMenulink);
						//$url = "javascript:alert('没有子菜单');void(0);";
					} else {
						$url = TDPathUrl::getMenuItemLink($minInd, $row["id"],0, TDPathUrl::$menuForType_topMenulink);
					}
					$mnInd = $row["id"]; //$index;	
					$menu = array(
						'url'=> $url, //TDPathUrl::createUrl($url),
						'name'=>$row["name"],
						'topmnInd' => $mnInd,
					);
					$result[] = $menu;
				}
				TDSessionData::setCache("userMenuBar_".TDSessionData::userMarkStr(),$result);
			}
			foreach($result as $resIndex => $menu) {
				echo '<li '.((isset($_GET["topmnInd"]) && TDPathUrl::getGETParam("topmnInd") == $menu["topmnInd"]) ? " class='active' " : "")
				.'><a style="padding: 10px 9px 10px;margin-left:10px;" href="'.$menu['url'].'">'.$menu['name'].'</a></li>';
				if(TDSessionData::getCurIsDevModel()){
					$devMD = '<div class="btn-group pull-right" style="margin-top:8px;">
					<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-orange icon-wrench"></i></a>
					<ul class="dropdown-menu qkmenu">
					<li onclick="quickEditMenu('.$menu['topmnInd'].')"><span class="icon icon-color icon-edit"></span>'.TDLanguage::$quikEditMenu_edit.'</li>
					<li onclick="quickAddMenu('.$menu['topmnInd'].')"><span class="icon icon-color icon-plus"></span>'.TDLanguage::$quikEditMenu_addChild.'</li>
					'.($resIndex != 0 ? '<li onclick="quickReorderMenu('.$menu['topmnInd'].',0)"><span class="icon icon-orange icon-arrowthick-w"></span>'.TDLanguage::$quikEditMenu_toLeft.'</li>' : '').'
					'.($resIndex < count($result)-1 ? '<li onclick="quickReorderMenu('.$menu['topmnInd'].',1)"><span class="icon icon-orange icon-arrowthick-e"></span>'.TDLanguage::$quikEditMenu_toRight.'</li>' : '').'
					<li onclick="quickDeleteMenu('.$menu['topmnInd'].')"><span class="icon icon-color icon-close" ></span>'.TDLanguage::$quikEditMenu_delete.'</li>
					</ul>
					</div>';
					echo '<li class="mainMenuSet">'.$devMD.'</li>';
				}	
				/*
				if(TDSessionData::getCurIsDevModel()){
					$devMD = '<span  class="icon icon-color icon-close" onclick="quickDeleteMenu('.
					$menu['topmnInd'].')"></span>
  					<span style="margin-top: 5px;margin-right: -20px;" class="icon icon-color icon-edit" onclick="quickEditMenu('.
					$menu['topmnInd'].')"></span>';
					echo '<li>'.$devMD.'</li>';
				}
				*/
			}
			if(TDSessionData::getCurIsDevModel()){
				echo '<li><a style="padding: 10px 9px 10px;margin-left:10px;" onclick="quickAddMenu(0)"><span title="'.TDLanguage::$quikEditMenu_addMain.
				'" style="margin-top:-5px;" class="icon32 icon-white icon-plus"></span></a></li>';
			}
		?>
		</ul>
        	<!-- <input class="search-query span2" placeholder="Search" style="margin-top:5px;" type="text"> -->
		<?php 
		if(TDSessionData::currentUserIsAdmin()) { 
			$fileStr = TDSessionData::getCache("developerToolMenu");
			if ($fileStr === false) {
				$fileStr = '
				<div class="btn-group pull-right" style="margin-left: 10px;padding-top:9px;">
					<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-white icon-gear"></i></a>
					<ul class="dropdown-menu">
						<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_menu).'_2000').'">'.TDLanguage::$menu_menu_manage.'</a></li>
						<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_table_collection).'_0').'">'.TDLanguage::$menu_table_and_module.'</a></li>
						<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_table_column).'_0').'">'.TDLanguage::$mysql_table_columns.'</a></li>
						<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_module).'_0').'">'.TDLanguage::$menu_module_manage.'</a></li>
						<li><a href="'.TDPathUrl::createUrl('tDUnitAction/mysql').'">MySQL</a></li>
					</ul>
				</div>';
				TDSessionData::setCache("developerToolMenu",$fileStr);
			}
			echo $fileStr;
			/*
			<li><a style="cursor:pointer;" onclick="too_exportSysSQL()">'.TDLanguage::$menu_export_syssql.'</a></li>
			<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_table_column).'_0').'">'.TDLanguage::$mysql_table_columns.'</a></li>
			<li><a href="'.TDPathUrl::createUrl('tDUnitAction/checkUpgrade').'" target="_blank">'.TDLanguage::$menu_system_upgrade.'</a></li>
			<li><a href="'.TDPathUrl::createUrl('tDUnitAction/excuteSQL/mnInd/0').'">SQL执行</a></li>
			<li><a href="'.TDPathUrl::createUrl('tDMinMysql/admin').'">MinMysql</a></li>
		 	*/
			//<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_table_column).'_0').'">'.TDLanguage::$mysql_table_columns.'</a></li>
			//<li><a href="'.TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_module).'_0').'">'.TDLanguage::$menu_module_manage.'</a></li>
		}
		?>
		<div class="btn-group pull-right">
			<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
				<i class="icon-user"></i><span class="hidden-phone"> <?php echo TDSessionData::getNickName(); ?></span>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo TDPathUrl::createUrl('tDSite/logout'); ?>"><span class="icon-chevron-left"></span><?php echo TDLanguage::$main_topbar_logout; ?></a></li>
				<li><a href="<?php echo TDPathUrl::createUrl('tDUnitAction/updatePwd'); ?>"><span class="icon-lock"></span><?php echo TDLanguage::$main_topbar_update_info; ?></a></li>
				<?php /*
				<li><a href="javascript:refreshSession();void(0);"> echo TDLanguage::$main_topbar_refreshSession; </a></li>
				*/ ?>
				<?php if(TDSessionData::currentUserIsAdmin()) { ?>
				<li><a href="javascript:refreshCash();void(0);"><span class="icon-repeat"></span><?php echo TDLanguage::$UnitActionController_RefreshCash ?></a></li>
				<li><a href="javascript:refreshTableStruct();void(0);"><span class=" icon-refresh"></span><?php echo TDLanguage::$UnitActionController_RefreshTableStruct ?></a></li>
				<li><a target="_blank" href="<?php echo TDPathUrl::createUrl("tDUnitAction/structMenu") ?>"><span class="icon-list"></span><?php echo TDLanguage::$UnitActionController_STRUCT_MENU; ?></a></li>
				<?php if(TDSessionData::getCurIsDevModel()){ ?>
				<li><a href="javascript:closeDevModel();void(0);"><span class="icon-stop"></span><?php echo TDLanguage::$UnitActionController_CloseDevModel ?></a></li>
				<?php } else { ?>
				<li><a href="javascript:openDevModel();void(0);"><span class="icon-play"></span><?php echo TDLanguage::$UnitActionController_OpenDevModel ?></a></li>
				<?php } ?>
				<li><a href="<?php echo TDPathUrl::createUrl('tDUnitAction/userManage'); ?>"><span class="icon-user"></span><?php echo TDLanguage::$menu_user_manage; ?></a></li>
				<li><a href="<?php echo TDPathUrl::createUrl('tDUnitAction/mysql'); ?>"><span class="icon-th-large"></span>minMySQL</a></li>
				<?php } ?>
			</ul>
		</div>
        </div>
</div>
</div>
