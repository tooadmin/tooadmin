<?php //主要用于嵌入gridview 模块用 ?>
<?php $baseUrl = Yii::app()->baseUrl.'/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/'; ?>
<script src="<?php echo $baseUrl ?>js/bootstrap-modal.js"></script>
<?php echo $content; ?>