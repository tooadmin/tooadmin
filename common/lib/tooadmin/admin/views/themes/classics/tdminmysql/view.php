<?php 
$groupColumns = $view->getViewGroupColumns();
$isgridview = false;
foreach($groupColumns as $classId => $columns) { if($columns instanceof TDGridView) { $isgridview = true; } }

if(count($groupColumns) > 1 || $isgridview) {
	echo '
	<div class="tabbable">
		<ul class="nav nav-tabs">'; 
		$rowIndex = 0;
		foreach($groupColumns as $classId => $columns) { 
			if(is_numeric($classId)) {
				$tmp = TDModelDAO::getModel(TDTable::$too_table_column_class)->find(array('select'=>'`group_name`','condition'=>'`id`='.$classId));
				$tabTitle = !empty($tmp) ? $tmp->group_name : TDLanguage::$table_column_class_other; 	
			} else {
				$tabTitle = $classId;
				$classId = $rowIndex;
			}
			echo '<li '.($rowIndex == 0 ? 'class="active"' : '').'><a href="#fieldtab'.$classId.'" data-toggle="tab">'.$tabTitle.'</a></li>'; 
			$rowIndex++;
		}
		echo '
		</ul>
		<div class="tab-content">'; 
		$rowIndex = 0;
		foreach($groupColumns as $classId => $columns) { 
			if(is_numeric($classId)) {
				echo '<div class="tab-pane '.($rowIndex == 0 ? "active" : '').'" id="fieldtab'.$classId.'" style="min-height:400px;">';
				$this->widget('zii.widgets.CDetailView', array('cssFile' => null, 'data'=>$view->model, 'attributes'=>$columns));
				echo '</div>'; 
			} else {
				$classId = $rowIndex;
				echo '<div class="tab-pane '.($rowIndex == 0 ? "active" : '').'" id="fieldtab'.$classId.'" style="min-height:400px;">';
				echo $columns->createGridView(true,$classId);
				echo '</div>'; 
			}
			$rowIndex++;
		}
		echo '
		</div>
	</div>';
	echo '<script> 
	var timerObj = setTimeout("reSetTableWH()",500);
	function reSetTableWH() {
	clearTimeout(timerObj);	
	var tabpanes = $(".tab-pane"); var lastMaxPanWidth=400; lastMaxPanHeight=300;
	for(var i=0; i<tabpanes.length; i++){ 
		var checkHeight = tabpanes.filter(":eq("+i+")").height(); 
		if(checkHeight > lastMaxPanHeight) { 
			lastMaxPanHeight = checkHeight; 
		}
		var checkWidth = tabpanes.filter(":eq("+i+")").width(); 
		if(checkWidth > lastMaxPanWidth) { 
			lastMaxPanWidth = checkWidth;
		} 
	}
	for(var i=0; i<tabpanes.length; i++){ 
		tabpanes.filter(":eq("+i+")").css("min-width",lastMaxPanWidth);
		tabpanes.filter(":eq("+i+")").css("min-height",lastMaxPanHeight); 
	} 
	}
	</script>';
} else {
	foreach($groupColumns as $classId => $columns) { 	
		$this->widget('zii.widgets.CDetailView', array('cssFile' => null, 'data'=>$view->model, 'attributes'=>$columns));
	}
}
?>
<script>
	function to_view_refresh() {$("form").attr("target",""); $("form").append('<input type="hidden" value="yes" name="postreload" >'); $("form").submit();}
</script>
<?php if(TDSessionData::currentUserIsAdmin()) { ?>
<div id="operateTool" style="display: none;">
	<div class="btn-group" style="float:left;margin-left: -5px;padding-right: 5px;padding-top:3px;">
	<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-blue icon-gear"></i></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:document.getElementById('fram').contentWindow.postReloadCurrentForm();void(0);"><i class="icon icon-blue icon-refresh" title="<?php 
		echo TDLanguage::$to_refresh; ?>"></i><?php echo TDLanguage::$to_refresh; ?></a></li>
		<li><a href="javascript:document.getElementById('fram').contentWindow.to_form_admin(<?php echo $view->viewModuleId; ?>);void(0);"><i class="icon icon-blue icon-clipboard" title="<?php 
		echo TDLanguage::$to_columns_admin; ?>"></i><?php echo TDLanguage::$to_columns_admin; ?></a></li>
	</ul>
	</div>
</div>
<form></form>
<script> parent.$("#modal_operate").html($("#operateTool").html()); </script>
<?php } ?>