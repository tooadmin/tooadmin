<?php 
$timerRunJs = "";
$groupColumns = $view->getViewGroupColumns();
$isgridview = false;
foreach($groupColumns as $classId => $columns) { if($columns instanceof TDGridView) { $isgridview = true; } }

if(count($groupColumns) > 0 || $isgridview) {
	echo '
	<div class="tabbable">
		<ul class="nav nav-tabs">'; 
		$rowIndex = 0;
		foreach($groupColumns as $classId => $columns) { 
			if(is_numeric($classId) && $classId >= TDStaticDefined::$formInnerGridviewIndexId) {
				continue;	
			}
			//过滤从属子分类
			if(is_numeric($classId) && $classId > 0 && TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class,$classId,"min(pid)") > 0) {
				continue;
			}
			$onclick = "";
			if(is_numeric($classId)) {
				$tmp = TDModelDAO::getModel(TDTable::$too_table_column_class)->find(array('select'=>'`group_name`','condition'=>'`id`='.$classId));
				$tabTitle = !empty($tmp) ? $tmp->group_name : TDLanguage::$table_column_class_other; 	
			} else {
				$tabTitle = $classId;
				$classId = $rowIndex;
				if($columns["pageType"] == 1) {
					$fun = 'formLoadModuleFormCustomPage(\'fieldtab_'.$classId.'_'.$columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].'\','
					.$columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].','.$columns[TDStaticDefined::$PARAM_MODULE_ROW_PKID].',\''.$columns["appendUrl"].'\')';
					$onclick = ' onclick="'.$fun.'" '; 
					$timerRunJs = $rowIndex == 0  ? 'setTimeout("'.$fun.'",500);' : '';
					//$timerRunJs = $rowIndex == 0  ? 'setTimeout("alert(123);'.$fun.'",1000);' : '';
				} else {
					$fun = 'formLoadModuleFormModule(\'fieldtab_'.$classId.'_'.$columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].'\','
					.$columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID].','.$columns[TDStaticDefined::$PARAM_MODULE_ROW_PKID].','.$columns['ntableModuleId'].',\'1'.$columns["appendUrl"].'\')';
					$onclick = ' onclick="'.$fun.'" ';
					$timerRunJs = $rowIndex == 0  ? 'setTimeout("'.$fun.'",500);' : '';
					//$timerRunJs = $rowIndex == 0  ? 'setTimeout("alert(456);'.$fun.'",1000);t>' : '';
				}
			}
			echo '<li '.($rowIndex == 0 ? 'class="active"' : '').'><a href="#fieldtab_'.$classId.'_'.
				(is_array($columns) && isset($columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '').
				'" '.$onclick.' data-toggle="tab">'.$tabTitle.'</a></li>'; 
				$rowIndex++;
		}
		echo '
		</ul>
		<div class="tab-content">'; 
		$rowIndex = 0;
		$isEchoFormInnerGridvew = false;
		foreach($groupColumns as $classId => $columns) { 
			if(is_numeric($classId) && $classId >= TDStaticDefined::$formInnerGridviewIndexId) {
				continue;	
			}
			//过滤从属子分类
			if(is_numeric($classId) && $classId > 0 && TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class,$classId,"min(pid)") > 0) {
				continue;
			}
			if(is_numeric($classId)) {
				echo '<div class="tab-pane '.($rowIndex == 0 ? "active" : '').'" id="fieldtab_'.$classId.'_'.
				(is_array($columns) && isset($columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '').
				'" style="min-height:400px;">';

				echo '<div class="box"><div class="box-header well"><h2>信息</h2><div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div></div><div class="box-content row-fluid sortable">';

				$classRow = is_numeric($classId) && $classId > 0 ? TDModelDAO::queryRowByPk(TDTable::$too_table_column_class,$classId) : array();
				echo '<div class="box-content span'.(!empty($classRow) ? $classRow["span_num"] : 12).'">';	
				$this->widget('zii.widgets.CDetailView', array('cssFile' => null, 'data'=>$view->model, 'attributes'=>$columns));
				echo '</div>';

				foreach($groupColumns as $childClassId => $childColumns) { 
					if($classId > 0 && is_numeric($childClassId) && $childClassId > 0 && TDModelDAO::queryScalarByPk(TDTable::$too_table_column_class,$childClassId,"min(pid)") == $classId) {
						$classChildRow = TDModelDAO::queryRowByPk(TDTable::$too_table_column_class,$childClassId);
						echo '<div class="box-content span'.(!empty($classChildRow) ? $classChildRow["span_num"] : 12).'">';	
						$this->widget('zii.widgets.CDetailView', array('cssFile' => null, 'data'=>$view->model, 'attributes'=>$childColumns));
						echo '</div>';	
					}
				}

				echo '</div></div>';

				if(!$isEchoFormInnerGridvew) {	
					$isEchoFormInnerGridvew = true;
					TDField::getFieldHtmls($groupColumns,TDField::$fieldHtmlTypes_FormInnerGridvew,array("classId"=>$classId,"readonly"=>1));
				}
				echo '</div>'; 
			} else {
				$classId = $rowIndex;
				echo '<div class="tab-pane '.($rowIndex == 0 ? "active" : '').'" id="fieldtab_'.$classId.'_'.
				(is_array($columns) && isset($columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID]) ? $columns[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] : '').
				'" style="min-height:400px;">';
				//echo $columns->createGridView(true,$classId);
				echo '</div>'; 
			}
			$rowIndex++;
		}
		echo '
		</div>
	</div>';
	echo '<script> 
	var timerObj = setTimeout("reSetTableWH()",1000);
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
	$isEchoFormInnerGridvew = false;
	foreach($groupColumns as $classId => $columns) { 	
		if(is_numeric($classId) && $classId >= TDStaticDefined::$formInnerGridviewIndexId) {
			continue;
		}
		echo '<div class="box"><div class="box-header well"><h2>信息</h2><div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div></div><div class="box-content">';
		$this->widget('zii.widgets.CDetailView', array('cssFile' => null, 'data'=>$view->model, 'attributes'=>$columns));
		echo '</div></div>';
		if(!$isEchoFormInnerGridvew) {	
			$isEchoFormInnerGridvew = true;
			foreach($groupColumns as $classId2 => $columns2) { 
				if(is_numeric($classId2) && $classId2 >= TDStaticDefined::$formInnerGridviewIndexId) {
					unset($_REQUEST);
					// 搜索 js 函数 formLoadModuleFormModule 参考
					$_GET[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID] = $columns2[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID];
					$_GET[TDStaticDefined::$PARAM_MODULE_ROW_PKID] = $columns2[TDStaticDefined::$PARAM_MODULE_ROW_PKID];
					$_GET['moduleId'] = $columns2['ntableModuleId']; 
					$_GET['mnInd'] = 0;	
					$_GET['topmnInd'] = 0;
					$_GET[TDStaticDefined::$pageLayoutType] = TDStaticDefined::$pageLayoutType_inner; 
					$_GET[TDStaticDefined::$PARAM_MODULE_READONLY] = 1;
					$tmpgr = new TDGridView(new CController($classId2."_"),$columns2["ntableModuleId"]); 
					echo '<div class="box"><div class="box-header well"><h2>'.TDModelDAO::queryScalarByPk(TDTable::$too_module_formmodule,$columns2[TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID],"formtab_title").
					'</h2><div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div></div><div class="box-content">';
					echo $tmpgr->createGridView();
					echo '</div></div>';
				}
			}
		}
	}
}
?>
<script>
	function to_view_refresh() {$("form").attr("target",""); $("form").append('<input type="hidden" value="yes" name="postreload" >'); $("form").submit();}
	<?php echo $timerRunJs; ?>
</script>
<?php if(TDSessionData::currentUserIsAdmin()) { ?>
<div id="operateTool" style="display: none;">
	<div class="btn-group" style="float:left;margin-left: -5px;padding-right: 5px;padding-top:3px;">
	<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-blue icon-gear"></i></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:document.getElementById('fram').contentWindow.postReloadCurrentForm();void(0);"><i class="icon icon-blue icon-refresh" title="<?php 
		echo TDLanguage::$to_refresh; ?>"></i><?php echo TDLanguage::$to_refresh; ?></a></li>
		<?php if($view->viewModuleId != TDStaticDefined::$mysqlCommonModuleId) { ?>
		<li><a href="javascript:document.getElementById('fram').contentWindow.to_form_admin(<?php echo $view->viewModuleId; ?>);void(0);"><i class="icon icon-blue icon-clipboard" title="<?php 
		echo TDLanguage::$to_columns_admin; ?>"></i><?php echo TDLanguage::$to_columns_admin; ?></a></li>
		<?php } ?>
	</ul>
	</div>
</div>
<form></form>
<script> parent.$("#modal_operate").html($("#operateTool").html()); </script>
<?php } ?>