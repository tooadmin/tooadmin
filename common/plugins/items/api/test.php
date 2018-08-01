<?php
header("Content-Type: text/html;charset=UTF-8");

include 'TDApiModel.php';

$md = new TDApiModel();
//$md->getPermissionDetail();

echo "<pre>";
//$row = $md->findByPk("test_table1","4");
///$row = $md->find("test_table1","*");
//$row = $md->findAll("test_table1","name,address","`t`.`name` like '%刘%'");
//$row = $md->addRow("test_table1",array("name"=>"刘四","address"=>"深圳宝安2222","enter_date"=>"2014-07-08"));
//$row = $md->updateRow("test_table1",8,array("name"=>"刘四222","address"=>"深圳宝安56688","enter_date"=>"2014-07-06"));
//$row = $md->deleteByPk("test_table1",7);
//print_r($row);

if(isset($_POST) && !empty($_POST)) {
	$row = $md->updateRow("test_table1",7,array("name"=>$_POST['name'],"address"=>"深圳宝安区9999"));
	print_r($row);
}

?>

<form enctype="multipart/form-data" method="post" >
	姓名<input type="text" name="name" value=""/>
	<br/>
文件上传<input type="file" name="file"  />	
<br/>
<input type="submit" value="提交数据"/>
</form>
