<?php 
//table create  sql 
if(isset($_GET["mysql_table_id"]) && !empty($_GET["mysql_table_id"])) {
	$tableName = TDTableColumn::getTableDBName(intval($_GET["mysql_table_id"]));
	$createtable = TDModelDAO::getDB($tableName)->createCommand("SHOW CREATE TABLE $tableName")->query();
	$createTableSQL = ""; foreach($createtable as $row) { $createTableSQL =  $row['Create Table'].";\n"; } echo "<pre>"; echo $createTableSQL; exit;
}	
if(isset($_GET['mysqlQueryTableStr'])) {
	$queryStr = trim($_GET['mysqlQueryTableStr']);	
	$queryStr = !empty($queryStr) ? "(`table` like '%".$queryStr."%' or `name` like '%".$queryStr."%')" : "1";	
	$tbRows = TDModelDAO::getModel(TDTable::$too_table_collection)->findAll($queryStr." and `type`=3");
	$html = '<li class="nav-header">数据表</li>';
	foreach ($tbRows as $row) {
		$html .= '<li id="tbli_'.$row["id"].'"><a href="javascript:reloadTableColumns('.$row["id"].');void(0);">' .$row['table'].'</a>'.($row['table'] != $row["name"] ? '<span class="label">'.$row["name"].'</span>' : "").'</li>';
	}
	echo $html;exit;
}
?>

<script>
	function reloadTableColumns(tbid) {
		loadingStart();
		$("#lastQueryTableId").val(tbid);
		$("#lastQueryTableColumnsStr").val("");
		var lis = $("#mysqlChooseTableUl").find("li");
		for(var i=0; i<lis.size(); i++) {
			lis.filter(":eq("+i+")").attr("class","");
		}
		$("#tbli_"+tbid).attr("class","active");
		var homeUrl = '/index.php';
		$.ajax({type:'get',url:'?mysql_table_id='+tbid,data:'' ,dataType:'html' ,success:function(data){ $("#create_sql").html(data);}});
		$.ajax({type:'get',url:homeUrl+"/tDModule/mysqlChooseColumns/toolModuleId/38/mysqlTableId/"+tbid,data:'' ,dataType:'html' ,success:function(data){ $("#choose_clumns").html(data);}});
		var viewOrgData = '&<?php echo TDStaticDefined::$mysqlDataDispalyType.'='.TDStaticDefined::$mysqlDataDispalyType_org; ?>';
		if(document.getElementById("data_display_type0").checked) { viewOrgData = ''; }
		$.ajax({type:'get',url:homeUrl+"/cmad_<?php echo TDStaticDefined::$mysqlCommonModuleId."_0"; ?>?<?php 
		echo TDStaticDefined::$pageLayoutType."=".TDStaticDefined::$pageLayoutType_single.'&'
		.TDStaticDefined::$mysqlCommonMudelTabId.'=';?>"+tbid+viewOrgData,data:'' ,dataType:'html' ,success:function(data){ $("#table_data").html(data);}});
		loadingFinish();
	}
	function mysqlReloadTableData(tableId,columnsStr) {
		$("#lastQueryTableColumnsStr").val(columnsStr);
		var viewOrgData = '&<?php echo TDStaticDefined::$mysqlDataDispalyType.'='.TDStaticDefined::$mysqlDataDispalyType_org; ?>';
		if(document.getElementById("data_display_type0").checked) {
			viewOrgData = '';	
		}
		$.ajax({type:'get',url:homeUrl+"/cmad_<?php echo TDStaticDefined::$mysqlCommonModuleId."_0"; ?>?<?php 
		echo TDStaticDefined::$pageLayoutType."=".TDStaticDefined::$pageLayoutType_single.'&'.TDStaticDefined::$mysqlCommonMudelTabId.'=';?>"+tableId+'&<?php 
		echo TDStaticDefined::$mysqlTableColumnsStr ?>='+columnsStr+viewOrgData,data:'' ,dataType:'html' ,success:function(data){ $("#table_data").html(data);}});
		document.getElementById("viewTableDataLi").click();		
	}
	function mysqlRequeryTable() {
		var queryStr = $("#queryTableStr").val();
		$.ajax({type:'get',url:'?mysqlQueryTableStr='+queryStr,data:'' ,dataType:'html' ,success:function(data){ $("#mysqlChooseTableUl").html(data);}});
	}
	function checkDataDisplayType() {
		mysqlReloadTableData($("#lastQueryTableId").val(),$("#lastQueryTableColumnsStr").val());	
	}
</script>
<div class="row-fluid sortable ui-sortable" style="margin-top: -10px;">
	<div class="span2 main-menu-span">
		<div class="box-content"> 
			<div style="margin-left: -20px; margin-top: 28px; position: absolute;">
			<input type="text" id="queryTableStr" style="width:150px;" onkeypress="if(event.keyCode==13){ mysqlRequeryTable();return false;}">
			<div class="controls">
				<div class="input-append">
					<label class="radio input_readio" style="cursor: pointer;">
						<div class="radio">
							<span class=""><div id="uniform-cid11040" class="radio"><span class="checked">
							<input type="hidden" value="0" id="lastQueryTableId">
							<input type="hidden" value="" id="lastQueryTableColumnsStr">
							<input value="0" name="data_display_type" checked="checked" id="data_display_type0" onclick="checkDataDisplayType()" style="opacity: 0;" type="radio"></span></div></span>
						</div>格式处理&nbsp;
					</label>
					<label class="radio input_readio" style="cursor: pointer;">
						<div class="radio">
							<span class=""><div id="uniform-cid11041" class="radio"><span>
							<input value="1"  name="data_display_type" id="data_display_type1" onclick="checkDataDisplayType()" style="opacity: 0;" type="radio"></span></div></span>
						</div>原始数据
					</label>
				</div>
			</div>
			</div>
			<div style="height: 705px;position: absolute;width:200px;overflow-x: -moz-hidden-unscrollable;margin-left: -35px;margin-top: 100px;">
				<ul class="nav nav-list" id="mysqlChooseTableUl"></ul>
			</div>
		</div>
	</div>
	<div style="min-width:1710px;margin-left:170px;" class="span10">
		<div class="box-content">
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li class="active"><a id="viewTableDataLi" href="#table_data" data-toggle="tab"><?php echo TDLanguage::$mysql_table_data ?></a></li>
					<li><a href="#choose_clumns" data-toggle="tab"><?php echo TDLanguage::$mysql_choose_columns; ?></a></li>
					<li><a href="#create_sql" data-toggle="tab"><?php echo TDLanguage::$mysql_table_create_SQL ?></a></li>
					<!--<li><a href="#phpMyAdmin" data-toggle="tab">phpMyAdmin</a></li> -->
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="table_data"></div>
					<div class="tab-pane" id="choose_clumns"></div>
					<div class="tab-pane" id="create_sql"></div>
					<!--
					<div class="tab-pane" id="phpMyAdmin">
						<iframe name="iframe_phpmyadmin" src="?php 
						echo Yii::app()->request->hostInfo.Yii::app()->homeUrl; >common/lib/phpmyadmin/index.php" width="98%" height="650px;" border="0"></iframe>
					</div>
	 				-->
				</div>
			</div>
		</div>
	</div>
</div>
<script>mysqlRequeryTable();</script>
