<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8">
		<title><?php echo Yii::app()->params->admin_head_title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="<?php echo Yii::app()->params->admin_meta_keywords; ?>" />
		<meta name="description" content="<?php echo Yii::app()->params->admin_meta_description; ?>" />
		<?php $this->beginContent(TDCommon::getRender('layouts/common_css')); $this->endContent(); ?>
		<script> var gridSettings = [];//yiigridview 全局用</script>
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); include 'common/lib/tooadmin/admin/www/too_admin/unit/gridview_condition.php'; ?>
	</head>
	<body>
		<?php $this->beginContent(TDCommon::getRender('layouts/common_topbar')); $this->endContent(); ?>
 		<div class="container-fluid">
				<div class="modal_sun hide fade" style="z-index: 999999;" id="loadingModal"> 
					<div class="modal-body_sun"> 
						<img src="<?php echo  Yii::app()->baseUrl; ?>/common/lib/tooadmin/admin/views/themes/<?php echo TDCommon::getThemeName(); ?>/www/too_admin/image/ajax-loader.gif">
					</div>
				</div>
        		<div class="modal_sun hide fade" id="myModal">
            			<div class="modal-header">
                			<button type="button" class="close" data-dismiss="modal">×</button>
					<h3><span id="modal_operate"></span><span id="modal_title">Settings</span></h3>
            			</div>
            			<div class="modal-body_sun" id="model_content"> </div>
        		</div>
        		<div class="row-fluid">
				<?php $mainSpanNum=10; $topMnInd = isset($_GET["topmnInd"]) ? $_GET["topmnInd"] : -1; 
				$menuChilds = TDSessionData::getCache("menuChilds_".$topMnInd."_".TDSessionData::userMarkStr());
				if($menuChilds === false) {
					$menuChilds = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=".$topMnInd." and `is_show`=1 ".Yii::app()->session['menu_permission_str']." order by `order`");
					TDSessionData::setCache("menuChilds_".$topMnInd."_".TDSessionData::userMarkStr(),$menuChilds);
				}	
				$mobileModel = false;
				if(!$mobileModel && count($menuChilds) > 0) { ?>
					<div style="width:160px;" class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
					<?php foreach($menuChilds as $second) { ?>
						<?php  
						$threeChilds = TDSessionData::getCache("threeChilds_".$topMnInd.'_'.$second['id']."_".TDSessionData::userMarkStr());
						if($threeChilds === false) {
							$threeChilds = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=".$second['id']." and `is_show`=1 ".Yii::app()->session['menu_permission_str']." order by `order`");
							TDSessionData::setCache("threeChilds_".$topMnInd.'_'.$second['id']."_".TDSessionData::userMarkStr(),$threeChilds);
						}	
						if(count($threeChilds) > 0) { ?>
						<li class="nav-header hidden-tablet" style="font-size:12px;"><?php echo $second['name']; ?></li>
						<?php 	foreach($threeChilds as $row) { echo TDPathUrl::getMenuItemLink($row['id'],$topMnInd,0,TDPathUrl::$menuForType_commonPage); } ?>
						<?php } else { echo TDPathUrl::getMenuItemLink($second['id'],$topMnInd,0,TDPathUrl::$menuForType_commonPage); } ?>
					<?php } ?>
					</ul>
					<label id="for-is-ajax" class="hidden-tablet" for="is-ajax" style="display:none;">
						<div class="checker" id="uniform-is-ajax">
							<span><input id="is-ajax" type="checkbox" style="opacity: 0;"></span>
						</div> Ajax on menu
					</label>
				</div>
				</div>
				<?php } else { $mainSpanNum=12;  } ?>

					<div style="min-width:<?php echo TDSessionData::getClientWidth() - 160 - 20; ?>px;" id="content" class="span<?php echo $mainSpanNum; ?>">
                			<?php $this->beginContent(TDCommon::getRender('layouts/common_breadcrumb')); $this->endContent(); ?>
                			<?php echo $content; ?>
            			</div>
        		</div>
    		</div>
			<div id="forGridviewTmpForm"></div>
	</body>
	<?php $this->beginContent(TDCommon::getRender('layouts/common_js')); $this->endContent(); ?>
</html>