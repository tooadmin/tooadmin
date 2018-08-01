<?php $baseUrl = Yii::app()->baseUrl.'/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/';
////*
$cssBasePath = './common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/';
$cssArray = array(
$cssBasePath."css/bootstrap-cerulean.css", 
$cssBasePath."css/bootstrap-responsive.css",
$cssBasePath."css/charisma-app.css",
$cssBasePath."css/jquery-ui-1.8.21.custom.css",
$cssBasePath."css/fullcalendar.css",
$cssBasePath."css/fullcalendar.print.css",
$cssBasePath."css/chosen.css",
$cssBasePath."css/uniform.default.css",
$cssBasePath."css/colorbox.css",
$cssBasePath."css/jquery.cleditor.css",
$cssBasePath."css/jquery.noty.css",
$cssBasePath."css/noty_theme_default.css",
$cssBasePath."css/elfinder.min.css",
$cssBasePath."css/elfinder.theme.css",
$cssBasePath."css/jquery.iphone.toggle.css",
$cssBasePath."css/opa-icons.css",
$cssBasePath."css/uploadify.css",
'./common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/too_admin/css/rewrite.css',
"./common/plugins/items/codemirror/lib/codemirror.css",
);
?>

<link href="<?php echo TDDataFiles::getCompressFile($cssArray,"common_css.css") ?>" rel="stylesheet">
<?php
?>
<link rel="Shortcut Icon" href="<?php echo Yii::app()->baseUrl.'/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/too_admin'; ?>/image/shortcut.ico<?php echo "?".time(); ?>" />
<!-- The fav icon -->
<link rel="shortcut icon" href="/favicon.ico">
<style type="text/css">
	.error { color:#BD4247; }
	.grid-view table td {vertical-align:middle;}
	.grid-view{margin-top: -15px;}
	.radio, .checkbox {padding-left: 0px !important;}
	.tablebtdrop {min-width: 80px;}
	/*table tr 鼠标移过的背景颜色*/
	.table tbody tr:hover td, .table tbody tr:hover th { background-color: #E8EBEE;	}
	/* .button-column a img {display: none;} 替换删除按钮图标*/
	/*放大*/
	/*
	.grid-view .button-column { width: 100px;} 
	body {font-size: 18px;}
	label, input, button, select, textarea,.chzn-container { font-size: 18px; }
	.box-header h2, .label, .badge, .btn { font-size: 18px; }
	ul.yiiPager { font-size: 22px; }
	.table th, .table td {padding: 6px !important; margin-bottom: 0px;}
	.box-content { padding: 5px; }
	.dropdown-menu a { font-size: 18px; padding-bottom: 10px;}
	select, input[type="file"] {height: 30px;}
	.chzn-container-single .chzn-single {height: 26px;}
	*/
	.cboxPhoto { max-width:1000px !important;max-height: 900px !important; }

.tableOperBtnfixed { position: fixed; width: 50px; height: 25px; line-height: 30px;left:<?php echo TDSessionData::spec_layout_common_page_right_gridview_opbtn_left(); ?>px; }
.tableOperBtnAdd { position: fixed; width: 25px; height: 25px; line-height: 30px; }
.tableOperBtnEdit { position: fixed;width: 25px; height: 25px; line-height: 30px; }
.tableOperBtnView { position: fixed;width: 25px; height: 25px; line-height: 30px; }
.tableOperBtnDel { position: fixed;width: 25px; height: 25px; line-height: 30px; }

.CodeMirror { border: 1px solid #eee; height: auto; } .CodeMirror-scroll { overflow-y: hidden; overflow-x: auto; }
</style>
<?php ///TDCodemirror::jscssCode(); ?>

