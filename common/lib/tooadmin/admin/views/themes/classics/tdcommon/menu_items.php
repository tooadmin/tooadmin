<?php 
if(isset($_GET['mysqlQueryTableStr'])) {
	$queryStr = trim($_GET['mysqlQueryTableStr']);	
	$queryStr = !empty($queryStr) ? "(`table` like '%".$queryStr."%' or `name` like '%".$queryStr."%')" : "1";	
	$tbRows = TDModelDAO::getModel(TDTable::$too_table_collection)->findAll($queryStr." and `type`=3");
	$html = '<li class="nav-header">数据表</li>';
	foreach ($tbRows as $row) {
		$html .= '<li id="tbli_'.$row["id"].'"><a href="javascript:setManageTableName(\''.$row["table"].'\');void(0);">' .$row['table'].'</a>'.
		($row['table'] != $row["name"] ? '<span class="label">'.$row["name"].'</span>' : "").'</li>';
	}
	echo $html;exit;
}
if(isset($_POST['tabTableName']) && isset($_POST['menuItemName'])) {
	$blgMnInd = intval($_POST['blgMnInd']);
	$tabTableName = trim($_POST['tabTableName']);
	$menuItemName = trim($_POST['menuItemName']);
	$tableId = TDTableColumn::getTableCollectionID($tabTableName);
	$errorMsg = "";
	if(!empty($tableId)) {
		$tran = TDModelDAO::getCommDB()->beginTransaction();
		$moduleModel = TDModelDAO::getModel(TDTable::$too_module);
		$moduleModel->name = $tabTableName;
		$moduleModel->table_collection_id = $tableId;
		if($moduleModel->save()){
			$menuItemModel = TDModelDAO::getModel(TDTable::$too_menu_items);
			$menuItemModel->menu_id = $blgMnInd;
			$menuItemModel->name = $menuItemName;
			$menuItemModel->module_id = $moduleModel->id;
			$menuItemModel->order = TDModelDAO::queryScalar(TDTable::$too_menu_items,"menu_id=".$blgMnInd, "max(`order`)") + 10;
			if($menuItemModel->save()) {
				$tran->commit();	
				echo 'success';exit;
			} else {
				$tran->rollback();
				$errorMsg .= TDCommon::getArrayValuesToString($menuItemModel->errors);
			}
		} else {
			$errorMsg .= TDCommon::getArrayValuesToString($moduleModel->errors);
		}
	} else {
		$errorMsg = "数据表不存在";
	}
	echo $errorMsg; exit;
}
?>
<div class="tabbable">
	<ul class="nav nav-tabs"> 
	<?php 
	$jsAutoRunFun ='';
	$rowIndex = 1;
	$time = time();
	$hasThreeMenus = isset($_GET['topmnInd']) && TDModelDAO::queryScalar(TDTable::$too_menu,"`pid`=(select id from ".TDTable::$too_menu." where `pid`=".intval($_GET["topmnInd"])
	." order by `order` limit 1)", "count(1)") > 0 ? true : false; 
	$curMnInd = isset($_GET['mnInd']) ? intval($_GET['mnInd']) : 0;
	foreach($items as $itemIndex => $item) { 
		$tabTitle = $item['name'];
		$curClickFun = "loadMenuItemUrl('menuItem".$item['id']."','".TDPathUrl::getMenuItemLink(TDRequestData::getGetData('mnInd'),
		TDRequestData::getGetData('topmnInd'),$item["id"],TDPathUrl::$menuForType_menulink)."')"; 
		$onclick = ' onclick="'.$curClickFun.'" ';
		$isactive = isset($_GET["vlasttb"]) ? $_GET["vlasttb"] == count($items) : $rowIndex == 1;
		if($isactive) { $jsAutoRunFun = $curClickFun.';'; }	
		echo '<li '.($isactive ? 'class="active"' : '').'><a href="#menuItem'.$item['id'].'" '.$onclick.' data-toggle="tab">'.$tabTitle.'</a></li>'; 
		$rowIndex++;
	} 
	if($hasThreeMenus && !empty($curMnInd) && TDSessionData::getCurIsDevModel()) {
		echo '<li '.(count($items) == 0 ? 'class="active"' : '').'><a href="#newItem'.$time.
		'" data-toggle="tab"><span class="icon icon-green icon-plus"></span>'.TDLanguage::$quikEditMenu_createTtem.'</a></li>'; 
	}
	?>
	</ul>
	<div class="tab-content"> 
	<?php $rowIndex = 1;
	foreach($items as $itemIndex => $item) { 
		$isactive = isset($_GET["vlasttb"]) ? $rowIndex == count($items) : $rowIndex == 1;
		echo '<div class="tab-pane '.($isactive ? "active" : '').'" id="menuItem'.$item['id'].'">';
		echo '</div>'; 
	}
	if($hasThreeMenus && !empty($curMnInd) && TDSessionData::getCurIsDevModel()) {
		echo '<div class="tab-pane '.(count($items) == 0 ? "active" : '').'" id="newItem'.$time.'">';
	?>
	<script>
	function mysqlRequeryTable() {
		var queryStr = $("#queryTableStr").val();
		$.ajax({type:'get',url:'?mysqlQueryTableStr='+queryStr,data:'' ,dataType:'html' ,success:function(data){ $("#mysqlChooseTableUl").html(data);}});
	}	
	function setManageTableName(tabName) {
		$("#queryTableStr").val(tabName);
	}
	function saveToNewTabModule() {
		var tabTableName = $("#queryTableStr").val();
		var menuItemName = $("#menuItemName").val();
		if(menuItemName == ''){
			alert("tab菜单项名称不能为空");	return;
		}
		if(tabTableName == ''){
			alert("tab项管理的数据库表能为空"); return;
		}
		$.ajax({ type:'post',
			url:'',
			data:'blgMnInd=<?php echo $curMnInd; ?>&tabTableName='+tabTableName+'&menuItemName='+menuItemName,
			dataType:'html' ,success:function(data){ if(data == "success") { alert("<?php echo TDLanguage::$tip_msg_operate_ok; ?>"); 
		window.location.reload(); } else { alert(data); } }});
	}
	</script>
	
	<div style="margin-top: 28px;">
		<span>tab菜单项名称 </span> <input type="text" id="menuItemName" style="width:150px;">
	</div>

	<div style="margin-top:5px;">
		<span>tab管理数据表 </span> <input type="text" id="queryTableStr" style="width:150px;" onkeypress="if(event.keyCode==13){ mysqlRequeryTable();return false;}" placeholder="输入关键字回车搜索">
	</div>

	<div style="height: 305px;width:200px;overflow-x: -moz-hidden-unscrollable;margin-left: 70px;">
		<ul class="nav nav-list" id="mysqlChooseTableUl"></ul>
	</div>

	<div style="margin-left: 90px;margin-top: 10px;">
		<button type="submit" class="btn btn-success" onclick="saveToNewTabModule()">保存</button>
	</div>

	<?php 
	echo '</div>';
	}
	?>		
	</div>
</div>
<script>setTimeout("<?php echo $jsAutoRunFun; ?>",1000);</script>