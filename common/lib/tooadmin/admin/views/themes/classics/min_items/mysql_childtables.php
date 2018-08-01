<?php
	$currentViewTableId = isset($_GET[TDStaticDefined::$mysqlCommonMudelTabId]) ? intval($_GET[TDStaticDefined::$mysqlCommonMudelTabId]) : -1;
	$rows = TDModelDAO::getDB(TDTable::$too_table_column)->createCommand("select table_collection_id from ".TDTable::$too_table_column
	." where map_table_collection_id=".$currentViewTableId." group by table_collection_id order by table_collection_id")->queryAll();
	if(count($rows) > 0) {
?>
<style>
	.mysqltbsul {margin: 0 0 2px 5px;float: left;list-style-type: none;}
	.mysqltbsul li {line-height:20px;}
	.mysqltbsul a:link{ text-decoration:none; }
	.radio input[type="radio"] {margin-left: 0px;}
</style>
<script>
	function filterMysqlForTables() {
		var str = $("#filtertbstr").val();
		var litbs = $("li[name=lifortabs]");
		for(var i=0; i<litbs.size(); i++) {
			if(str == '' || litbs.filter(":eq("+i+")").attr("table").indexOf(str) !== -1) {
				litbs.filter(":eq("+i+")").attr("style","display:block;");
			} else {
				litbs.filter(":eq("+i+")").attr("style","display:none;");
			}
		}
	}
	function openToViewForTableDatas(tbid) {
		if($("input[name=idradio]:checked").size() == 0) {
			alert("<?php echo TDLanguage::$mysql_tip_chooseradioid; ?>");
			return;
		}
		var tbkpparam = '<?php echo '&'.TDStaticDefined::$viewChildTableDatasFromTbId.'='.$currentViewTableId.'&'
		.TDStaticDefined::$viewChildTableDatasFromPkId.'='; ?>'+$("input[name=idradio]:checked").val();
		popupWindow('<?php echo TDLanguage::$mysql_popup_viewdata; ?>',homeUrl+"/cmad_<?php echo TDStaticDefined::$mysqlCommonModuleId; ?>_0?<?php 
		echo TDStaticDefined::$pageLayoutType."=".TDStaticDefined::$pageLayoutType_single.'&'.TDStaticDefined::$mysqlCommonMudelTabId.'=';?>"+tbid+tbkpparam);
	}
</script>
<div class="row-fluid sortable ui-sortable">
	<div class="box span12" style="margin-top:-2px;margin-bottom:2px;">
		<div class="box-content">
			<ul class="mysqltbsul">
				<li><span class="label label-info"><?php echo TDLanguage::$mysql_con_relation_tables; ?></span></li>
			<li>
	<input id="filtertbstr" onkeypress="if(event.keyCode==13){ filterMysqlForTables();return false;}" type="text" style="width:80px;font-size:12px;padding:1px;height:13px;margin-bottom: 0px;"/>
			</li>
			</ul>
			<ul class="mysqltbsul">
			<?php $index=0; foreach($rows as $row) {
				$tableRow = TDModelDAO::getModel(TDTable::$too_table_collection,$row['table_collection_id']);
				$table = $tableRow->table.($tableRow->table != $tableRow->name ? $tableRow->name : ""); 
				echo '<li name="lifortabs" table="'.$table.'"><a href="javascript:openToViewForTableDatas('.$row['table_collection_id']
				.');void(0);"><span class="label label-success">'.$table.'</span></a></li>';
				$index++; if($index % 2 == 0 || $index == count($rows)) { echo '</ul>'; if($index < count($rows)) {echo '<ul class="mysqltbsul">';} }
			}
			?>
			</ul>
		</div>
	</div>
</div>
<?php } ?>