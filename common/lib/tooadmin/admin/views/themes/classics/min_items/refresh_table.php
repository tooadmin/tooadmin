<?php
if(isset($_POST['opType']) && $_POST['opType'] == "reNameTable") {
	$delTableStr = $_POST["delTableStr"];
	$reTableStr = $_POST["reTableStr"];
	$reToNewTableStr = $_POST["reToNewTableStr"];
	$deleTableAr = !empty($delTableStr) ? explode(",",$delTableStr) : [];
	$reTableAr = !empty($reTableStr) ? explode(",", $reTableStr) : [];
	$reToNewTableAr = !empty($reToNewTableStr) ? explode(",", $reToNewTableStr) : [];
	foreach($deleTableAr as $tbName) {
		TDEvent_Table::beforeDelete($tbName);
		TDModelDAO::deleteByCondition(TDTable::$too_table_collection,"`table`='".$tbName."'");
	}
	foreach($reTableAr as $index => $tbName) {
		$tbId = TDTableColumn::getTableCollectionID($tbName);
		TDModelDAO::updateRowByPk(TDTable::$too_table_collection, $tbId, array('table'=>$reToNewTableAr[$index],'name'=>$reToNewTableAr[$index],'lastupdate_set'=>date("Y-m-d H:i:s")));
		TDTable::synchronizeDBWithSys($tbId);
	}
	echo "success";exit;
}
?>
<script>
	function rftb_toChangeTableName() {
		if(window.confirm("是否确认提交？")) {
			var reNameArray = new Array();
			var delTableStr = "";
			var reTableStr = "";
			var reToNewTableStr = "";
			var tbcount = $("#tbrowIndexCount").val();
			for(var i=1; i<=tbcount; i++) {
				if($("input[name='tbrowIndex"+i+"']:checked").val() == 'reName') {
					var newTbName = $("#newTbName"+i).val();
					if(newTbName == "") {
						alert("请先选择修改的表名称");
						return;
					} else {
						for(var j=0; j<reNameArray.length; j++) {
							if(reNameArray[j] == newTbName) {
								alert("修改的表名称不能重复");
								return;
							} 	
						}
						reNameArray[reNameArray.length] = newTbName; 
						if(reTableStr != "") { reTableStr += ","; reToNewTableStr += ","; }		
						reTableStr += $("#tableName"+i).val();
						reToNewTableStr += newTbName;
					}	
				} else {
					if(delTableStr != "") { delTableStr += ","; }		
					delTableStr += $("#tableName"+i).val();	
				}
			}
        		$.ajax({ type: 'POST', url: "" , data: 'opType=reNameTable&delTableStr=' + delTableStr + '&reTableStr=' 
			+ reTableStr + '&reToNewTableStr=' + reToNewTableStr , dataType: 'html'
            		, success: function (data) { if(data=="success") { alert("操作成功"); location.reload(); } }
        		});
		}	
	}
</script>
<div class="row-fluid sortable ui-sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i>检测数据表结构</h2>
        </div>
	    <?php
	    	//获取最新被更新的表
	    	$lastUpdTIme = TDModelDAO::queryScalar(TDTable::$too_table_collection,"is_systable=0","max(lastupdate_set)");
		$lastUpdTIme = empty($lastUpdTIme) ? "2018-05-15 16:00:00" : $lastUpdTIme;
		$updTables = TDModelDAO::getCommDB()->createCommand("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='".
		TDModelDAO::getCommonDBName()."' AND (CREATE_TIME>='".$lastUpdTIme."' OR UPDATE_TIME>='".$lastUpdTIme."')")->queryAll();
		$resultUpd  = [];
		foreach($updTables as $updTable) {
			if(TDModelDAO::queryScalar(TDTable::$too_table_collection,"`table`='".$updTable['TABLE_NAME']."'", "count(*)") > 0) {
				$resultUpd[] = $updTable['TABLE_NAME'];
			}
		}
		//确认为更新过的表则直接去刷新
		foreach($resultUpd as $tbName) {
			TDTable::synchronizeDBWithSys(TDTableColumn::getTableCollectionID($tbName));
			TDModelDAO::updateRowByCondition(TDTable::$too_table_collection,"`table`='".$tbName."'",array('lastupdate_set'=>date("Y-m-d H:i:s")));
		}
		
		$resultNew  = [];
		$resultDel  = [];
	    	$dbTables = TDTable::getDataBaseAllTables();
		$sysTables = TDTable::getCommTableConllectionAllTables();
		foreach ($sysTables as $table) { if (!in_array($table, $dbTables)) { $resultDel[] = $table; } }	
		foreach ($dbTables as $table) { if (!in_array($table, $sysTables)) { $resultNew[] = $table; } }	
		//如果检测到只有删除的表没有创建的新表,则可确认为只做了删除表
		if(count($resultDel) > 0 && count($resultNew) == 0) {
			foreach($resultDel as $tbName) {
				TDEvent_Table::beforeDelete($tbName);
				TDModelDAO::deleteByCondition(TDTable::$too_table_collection,"`table`='".$tbName."'");
			}
		} else if(count($resultDel) == 0 && count($resultNew) > 0) {
			//如果检测到只做了创建新表,则添加入记录
			foreach($resultNew as $tbName) {
				$tbId = TDModelDAO::addRow(TDTable::$too_table_collection,array('table'=>$tbName,'name'=>$tbName,'type'=>3)); 
				TDTable::synchronizeDBWithSys($tbId);
				TDModelDAO::updateRowByCondition(TDTable::$too_table_collection,"`table`='".$tbName."'",array('lastupdate_set'=>date("Y-m-d H:i:s")));
			}
		}
	//如果有新的表也有删除的表，则可能存在改表明的情况
	if(count($resultNew) > 0 && count($resultDel) > 0) { ?>
        <div class="box-content">
           <div class="sortable row-fluid ui-sortable">
		   <form method="post"> 
			<table class="table">
				<?php $rowIndex=1; foreach($resultDel as $item) { ?>
				<tr>
					<td>
						未找到数据表  <span style="font-weight: bold;"><?php echo $item; ?></span>
						<input type="hidden" value="<?php echo $item ?>" id="tableName<?php echo $rowIndex ?>">
					</td>
					<td>
						<label>
						<div class="radio">
						<span class="checked"><input checked="checked" value="del" name="tbrowIndex<?php echo $rowIndex ?>" style="opacity: 0;" type="radio">
						</span></div>
						<span style="color:red;">删除该表记录</span>
						</label>
					</td>
					<td>
						<label>
						<div class="radio">
						<span><input value="reName" name="tbrowIndex<?php echo $rowIndex ?>" style="opacity: 0;" type="radio">
						</span></div>
						<span style="color:orangered;">修改表名为</span>
						<select id="newTbName<?php echo $rowIndex; ?>"><option value="">---请选择---</option><?php 
						foreach($resultNew as $newTb){ echo '<option value="'.$newTb.'">'.$newTb.'</option>'; } ?></select>
						</label>
					</td>
				</tr>
				<?php $rowIndex++; } ?>
				<tr>
					<td></td>
					<td><input type="hidden" id="tbrowIndexCount" value="<?php echo $rowIndex; ?>"></td>
					<td><button type="button" class="btn btn-primary" onclick="rftb_toChangeTableName()" >提交</button></td>
				</tr>
			</table>
		   </form>
            </div>
        </div>
	<?php } else {
		echo '<script> alert("刷新成功"); parent.parent.closeWindow(); </script>';
        
	} ?>
    </div>
</div>
