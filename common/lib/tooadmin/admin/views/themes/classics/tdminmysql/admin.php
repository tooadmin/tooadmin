<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){ $('.search-form').toggle(); return false; });
$('.search-form form').submit(function(){ $.fn.yiiGridView.update('common-grid-minmysql', { data: $(this).serialize() });
return false; }); ");
?>
<script>
function onchangeMinMysqlTb(tb) {
	window.location.href="<?php echo TDPathUrl::createUrl("tDMinMysql/admin") ?>?mintb="+tb;	
}
</script>
<style> .dropdown-menu a i { padding-right: 2px; } </style>
<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div data-original-title="" class="box-header well">
			<h2>
				<i class="icon-list-alt"></i>&nbsp;&nbsp;<?php echo CHtml::link(TDLanguage::$advanced_search,'#',array('class'=>'search-button'));  ?>
			</h2>
		</div>
		<div  class="box-content">
			<div class="search-form" style="display:none">
				<?php $this->renderPartial(TDCommon::getRender('min_items/condition'),TDSearch::getConditionRenderParams(false,
				true,TDTableColumn::getTableCollectionID($mintb))); ?>
			</div>
			<select onchange="onchangeMinMysqlTb(this.value)">
				<option value="">---请选择---</option>
				<?php $array = TDDataFiles::getDBTables(); 
				foreach($array as $table) { echo '<option value="'.$table.'" '.($mintb == $table ? ' selected="selected" ' : '').' >'.$table.'</option>'; } ?>
			</select>
		</div>
		<div class="box-content" style="display:block;width:100%;overflow:scroll;">
			<?php
			$model = TDModelDAO::getModel($mintb);
			$columns = array(); //$this->getColumns();
			$conditionSql = TDSearch::getSearchConditionSql(TDTableColumn::getTableCollectionID($model->tableName));
			if(!empty($conditionSql)) {
				$model->getDbCriteria()->addCondition($conditionSql);
			}
			$dataProvider = $model->search();
			$dataProvider->pagination->pageSize = 20;
			$this->widget('zii.widgets.grid.CGridView', array(
				'id' => "common-grid-minmysql",
				'dataProvider' => $dataProvider,
		    		//'summaryText'=>'',
				//'filter'=>$model,
				'cssFile' => null,
				'itemsCssClass' => TDCommonCss::$CGridView_ItemsCssClass,
				'columns' => $columns, 
			));
			?>
		</div>
	</div>
</div>




