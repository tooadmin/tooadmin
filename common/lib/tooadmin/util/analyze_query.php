<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title="">
			<h2><i class="icon-edit"></i>查询统计</h2>
		</div>
		<div class="box-content">
			<?php echo $queryFormCode; ?>
			<table class="<?php echo TDCommonCss::$CGridView_ItemsCssClass; ?>">
				<thead>
					<tr>
						<?php foreach ($columns as $item) {
							echo '<th>'.(isset($item["label"]) ? $item["label"] : $item["name"]).'</th>';
						} ?> 
					</tr>
				</thead>   
				<tbody>
					<?php foreach ($rows as $row) { ?>
					<tr><?php foreach ($columns as $item) {
						$viewHtml = null;
						if(isset($item["columnId"]) && !empty($item["columnId"])) {
							$params = array( 'tableColumnId'=>$item["columnId"], 'value' => $row[$item['name']], 'model' =>null,);
							$inputType = TDTableColumn::getInputTypeByColumnId($params['tableColumnId']);		
							if(method_exists($inputType,'viewHtml')) {
								$fie = new $inputType();
								$viewHtml = $fie->viewHtml($params);	
								if(!empty($viewColumn)) {
									$tmpModel = TDModelDAO::queryRowByPk(TDTable::$too_table_column,$params["tableColumnId"],'`group_id`,`column_type`,`formula`,`name`');
									if($tmpModel["column_type"] == TDTableColumn::$COLUMN_TYPE_CUSTOM_COLUMN || !empty($tmpModel["formula"])) { 
										$viewColumn["value"] = Fie_formula::computeFormula($model,TDTableColumn::getColumnAppendStr($params["tableColumnId"],""));
									}
								}
							}
						}
						echo '<td>'.(!empty($viewHtml) ? $viewHtml : $row[$item['name']]).'</td>';
					} ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>	  
			<div class="pagination pagination-centered">
				<ul>
					<?php 
					$pageSize = 10;
					$pageCount = intval(($totalCount/$pageSize)+0.499);
					$currentPage = isset($_GET["currentPage"]) ? $_GET["currentPage"] : 1;
					if($pageCount > 1) {
						$start = intval($currentPage/10) * 10;
						$start = empty($start) ? 1 : $start;
						$iindex = 0;
						$pageChart = "?";
						$baseUrl =  Yii::app()->request->url;
						if(strpos($baseUrl,"?") !== false) {
							$pageChart = "&"; 
						}
						if(strpos($baseUrl,"currentPage=") !== false) {
							$baseUrl = str_replace("currentPage=","",$baseUrl);
						}
						for($i=$start; $i<=$pageCount; $i++) {
							echo '<li '.($i == $currentPage ? 'class="active"' : '').'><a href="'.$baseUrl.$pageChart.'currentPage='.$i.'">'.$i.'</a> </li>';	
							$iindex++;
							if($iindex == 10) {
								if($pageCount > $i) {
									echo '<li>...</li>';	
								}	
							}
						}
					}	
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
