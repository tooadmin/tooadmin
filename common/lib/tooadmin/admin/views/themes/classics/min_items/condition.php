<?php
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id' => 'form'.$markMuduleIdStr,
	'method'=> 'get',
    	'htmlOptions' => array('class'=>TDCommonCss::$CActiveForm_htmlOptions_class),
)); ?>
<fieldset id="fieldset<?php echo $markMuduleIdStr; ?>">
<?php
if(isset($analyzeHtml) && !empty($analyzeHtml)) {
	echo $analyzeHtml;	
} else { ?>
<script>
	loadSearchColumns('<?php echo TDTableColumn::getTableCollectionID($model->tableName); ?>',false,null,"tc_<?php 
	echo TDTableColumn::getTableCollectionID($model->tableName); ?>","<?php echo $markMuduleIdStr;  ?>");
</script>
<?php } ?>		
</fieldset>
<div id="div_combination<?php echo $markMuduleIdStr; ?>" <?php echo $combinationStyle; ?> >
<input type="hidden" id="combinationMaxNum<?php echo $markMuduleIdStr; ?>" name="combinationMaxNum" value="<?php echo $combinationMaxNum; ?>" />
<input type="hidden" id="advSearch_useCombinationFormula<?php echo $markMuduleIdStr; ?>" name="advSearch_useCombinationFormula" value="<?php echo $useCombinationFormula; ?>" />
<br/>
<?php echo TDLanguage::$advanced_search_combination_formula ?>&nbsp;&nbsp;
<input type="text" id="advSearch_combinationFormula<?php echo $markMuduleIdStr; ?>" name="advSearch_combinationFormula" value="<?php echo $combinationFormula; ?>" style="width:300px;" />
<button type='button' onclick="combinationFormulaSearch('<?php echo $markMuduleIdStr  ?>')" class='btn btn-primary'>
<i class='icon icon-white icon-search'></i><?php echo $conbinationButton; ?></button>
</div>
<input type="hidden" name="condition_table_id" value="<?php echo TDTableColumn::getTableCollectionID($model->tableName); ?>" />
<input type="hidden" name="condition_pk_id" value="<?php echo $condition_pk_id; ?>" />
<input type="hidden" id="condition_expert_excel<?php echo $markMuduleIdStr; ?>" name="condition_expert_excel" value="0" />
<input type="hidden" name="condition_splite_page" value="1" id="condition_splite_page_<?php echo $markMuduleIdStr; ?>" />
<br/>
<div class="controls" style="float:left;margin-left:50px;">
	<div class="input-append">
		<label class="radio">
			是否分页：
		</label>
		<label class="radio input_readio">
			<div class="radio">
				<span class=""><div class="radio"><span>
				<input onclick="tchangeRadioEvent('<?php echo $markMuduleIdStr; ?>')" value="0" id="condition_splite_page_0_<?php echo $markMuduleIdStr; ?>" name="condition_splite_page<?php 
				echo $markMuduleIdStr; ?>" style="opacity: 0;" type="radio"></span></div></span>
			</div> 否
		</label>
		<label class="radio input_readio">
			<div class="radio">
				<span class=""><div class="radio"><span class="checked">
					<input onclick="tchangeRadioEvent('<?php echo $markMuduleIdStr; ?>')" checked="checked" value="1" id="condition_splite_page_1_<?php 
					echo $markMuduleIdStr; ?>" name="condition_splite_page<?php 
					echo $markMuduleIdStr; ?>" style="opacity: 0;" type="radio"></span></div></span>
			</div> 是
		</label>
	</div>
	<?php if(TDModelDAO::queryScalarByPk(TDTable::$too_module,TDRequestData::getGetData('moduleId'),"allow_export") == 1) { ?>	
	<div class="input-append" style="margin-left:15px;">
		<a class="btn btn-primary" href="javascript:exportTbToExcelByCondition('<?php echo $markMuduleIdStr; ?>');void(0);">搜索并导出全部</a>
	</div>
	<?php } ?>
</div>
<?php $this->endWidget(); ?>