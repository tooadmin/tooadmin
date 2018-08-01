<?php
if($gridView->isDisplaySearchView()) {
Yii::app()->clientScript->registerScript('search'.$gridView->markMuduleIdStr, "
$('#search_button$gridView->markMuduleIdStr').click(function(){ $('#search_form".$gridView->markMuduleIdStr."').toggle(); return false; });
$('#form".$gridView->markMuduleIdStr."').submit(function(){ $.fn.yiiGridView.update('common-grid".$gridView->markMuduleIdStr."', { data: $(this).serialize() });
return false; }); ");
}
?>

<style>
.dropdown-menu a i { padding-right: 2px; }
.clientWidth { min-width: <?php echo TDSessionData::getClientWidth() - 160 - 80; ?>px; overflow:auto;min-height:550px; }
</style>
<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div data-original-title="" class="box-header well">
			<div class="btn-group" style="float:left;margin-left: -5px;padding-right: 5px;padding-top:3px;">
				<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-blue icon-gear"></i></a>
				<ul class="dropdown-menu">
				<?php $tools = TDToolMenueButtons::getGridviewTools($gridView->moduleId,$gridView->markMuduleIdStr,$gridView->getGridviewId()); foreach($tools as $item) { ?>
					<li><a href="javascript:<?php echo $item["jsfunction"]; ?>;void(0);"><i class="<?php echo $item["iclass"]; ?>" title="<?php 
					echo $item["title"]; ?>"></i><?php echo $item["title"]; ?></a></li>
				<?php } ?>
				</ul>
			</div>
			<h2>
				<!-- <i class="icon-list-alt"></i> TDTable::getAdminModelLabelName($gridView->moduleId); -->&nbsp;&nbsp;
				<?php echo $gridView->isDisplaySearchView() ? $gridView->craeteSearchLink() : ""; ?>
				<?php $tipRemark = TDModelDAO::queryScalarByPk(TDTable::$too_module,$gridView->moduleId,"remark"); //TDRequestData::getGetData("mitemId",0)
			echo !empty($tipRemark) ? '&nbsp;&nbsp;<p style="margin-top:-25px;margin-left:90px;font-size:12px;font-weight:normal;color: #8A8A8A;">'.$tipRemark.'</p>' : ""; ?>
			</h2>
		</div>
		<div  class="box-content">
			<?php if($gridView->isDisplaySearchView()) { ?>
			<div class="search-form" id="search_form<?php echo $gridView->markMuduleIdStr ?>" style="display:none">
					<?php echo $gridView->createSearch(); ?>
				</div>
			<?php } ?>
			<?php if(isset($expandHtml)) { echo '<div>'.$expandHtml.'</div>'; } ?>
		</div>
		<div class="box-content clientWidth">
			<?php $gridView->createGridView(); ?>
		</div>
	</div>
</div>
<?php $gridview_foot_file = TDModelDAO::queryScalarByPk(TDTable::$too_module, $gridView->moduleId,"gridview_foot_file"); if(!empty($gridview_foot_file)) { eval($gridview_foot_file); } ?>
