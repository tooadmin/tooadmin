<?php
//common sys js fun
include 'common/lib/tooadmin/admin/views/comm/unit/sysjsfun.php';
//just for reorder payge
include 'common/lib/tooadmin/admin/views/comm/unit/Fie_order_reorder.php';
//just for laddercolumn
include 'common/lib/tooadmin/admin/views/comm/unit/Fie_laddercolumn_set.php';
?>
<?php $baseUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/'; ?>
<script src="<?php echo $baseUrl ?>js/jquery.uniform.min.js"></script>
