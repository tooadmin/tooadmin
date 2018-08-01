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
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); include 'common/lib/tooadmin/admin/views/comm/unit/gridview_condition.php'; ?>
	</head>
	<body>
 		<div class="container-fluid">
			<div class="modal_sun hide fade" style="z-index: 999999;" id="loadingModal"> <div class="modal-body_sun"> 
					<img src="<?php echo  Yii::app()->baseUrl; ?>/common/lib/tooadmin/admin/views/themes/<?php echo TDCommon::getThemeName(); ?>/www/too_admin/image/ajax-loader.gif"> </div> </div>
        		<div class="modal_sun hide fade" id="myModal">
            			<div class="modal-header">
                			<button type="button" class="close" data-dismiss="modal">×</button>
					<h3><span id="modal_operate"></span><span id="modal_title">Settings</span></h3>
            			</div>
            			<div class="modal-body_sun" id="model_content"> </div>
        		</div>
        		<div class="row-fluid">
            			<div id="content" class="span12">
                			<?php echo $content; ?>
            			</div>
        		</div>
    	</div>
		<div id="forGridviewTmpForm"></div>
	</body>
	<?php $this->beginContent(TDCommon::getRender('layouts/common_js')); $this->endContent(); ?>
</html>
