<?php
include_once("../../includes/init.php");
$htmlData = '';
if (!empty($_POST['content1'])) {
	if (get_magic_quotes_gpc()) {
		$htmlData = stripslashes($_POST['content1']);
	} else {
		$htmlData = $_POST['content1'];
	}
}
//创建编辑器实例
$KindEditor_obj = new KindEditor();
$editor = $KindEditor_obj->create_editor('content1',700,300);//content1参数对应 <textarea name="content1" ...>

/*
编辑器使用方法
$KindEditor_obj = new KindEditor();
//创建单个编辑器
$editor_code = $KindEditor_obj->create_editor('name_1',600,400,'simple');//编辑器宽600，高400
//创建多个编辑器
$batch_editor_code = $KindEditor_obj->batch_create_editor(array(
array('name_1',600,400),
array('name_2',null,null,'simple'),
));
*/

?>

<!doctype html>
<html>
<head>
</head>
<body>
	<?php echo $htmlData; ?>
	<form name="example" method="post" action="demo.php">
		<textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea>
		<input type="submit" name="button" value="提交内容" />
	</form>
<!--加载编辑器-->
<?php echo $editor; ?>
</body>
</html>

