<?php
header("Content-Type: text/html;charset=UTF-8");
$yii = dirname(__FILE__) . '/common/lib/framework/yii.php';
$config = dirname(__FILE__) . '/common/lib/tooadmin/admin/config/main.php';
//$config = dirname(__FILE__) . '/xjk/config/main.php';
define('YII_DEBUG',true);
define('__MAIN_PATH__', dirname(__FILE__));
require_once($yii);
Yii::createWebApplication($config)->run();

