<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Yii Framework Learner',
    'language' => 'zh_cn',
    'timeZone' => 'Asia/Chongqing',
    'import' => array(
	'application.models.*',
	'application.components.*',
	'webroot.common.modules.*',
	'webroot.common.plugins.class.*',
	'webroot.common.lib.tooadmin.models.*',
	'webroot.common.lib.tooadmin.widget.*',
	'webroot.common.lib.tooadmin.field.*',
	'webroot.common.lib.tooadmin.field.item.*',
	'webroot.common.lib.tooadmin.field.sys_special.*',
	'webroot.common.lib.tooadmin.payment.*',
	'webroot.common.lib.tooadmin.upgrade.*',
	'webroot.common.lib.tooadmin.util.*',
	'webroot.common.lib.tooadmin.event.*',
	'webroot.common.lib.tooadmin.datafiles.*',
	'webroot.assets.program.modclass.*',
    ),
    'components' => array(
	'db' => array( //应用程序的数据库
	    'class' => 'CDbConnection',
	    'connectionString' => 'mysql:host=127.0.0.1:3306;dbname=sports',
	    'emulatePrepare' => true,
	    'username' => 'root',
	    'password' => '',
	    'charset' => 'utf8',
	),
	'too' => array( //tooadmin 系统数据库
	    'class' => 'CDbConnection',
	    'connectionString' => 'mysql:host=127.0.0.1:3306;dbname=tooadmin',
	    'emulatePrepare' => true,
	    'username' => 'root',
	    'password' => '',
	    'charset' => 'utf8',
	),
	'session' => array(
	    'class' => 'system.web.CDbHttpSession',
	    'connectionID' => 'too',
	    'sessionTableName' => 'too_session'
	),
	'cache' => array(
		//'class' => 'system.caching.CFileCache',
		//'directoryLevel' => 2,
	    	'class' => 'webroot.common.lib.tooadmin.admin.extensions.redis.CRedisCache',
	    	'servers' => array(
			array(
		    		'host' => '127.0.0.1',
		    		'port' => 6379,
		    		'database' => 10,
			),
	    	),
	),
	'user' => array('allowAutoLogin' => true,'autoRenewCookie'=>true),
	'bootstrap' => array('class' => 'bootstrap.components.Bootstrap'),
	'errorHandler' => array('errorAction' => 'tDSite/error',),
	'urlManager' => array(
	    'urlFormat' => 'path',
	    'showScriptName' => true,
	    'rules' => array(
		'' => 'tDSite/index', //首页
		'index.html' => 'tDSite/index', //首页
		'cmad_<moduleId:\d+>_<mnInd:\d+>' => 'tDCommon/admin/moduleId/<moduleId>/mnInd/<mnInd>',
	    ),
	),
    ),
    'params' => array(
	'sys_version' => '2.0', //系统当前版本编号
	'date_language' => 'zh_cn', //使用语音
	'is_use_key' => false, //是否使用加密锁
	'is_auto_upgrade' => false, //是否启动自动更新升级
	'reset_layout_millisecond' => 2000, //弹出框重画页面等待毫秒数
	'admin_menu_name' => 'tooadmin', //菜单栏名称
	'admin_head_title' => 'tooadmin数据管理', //后台title
	'admin_login_title' => 'tooadmin数据管理', //后台 登录标题
	'admin_meta_keywords' => '', //后台 meta_keywords
	'admin_meta_description' => '', //后台 meta_description
	'cus_table_prefix' => 'sp_',//db应用数据表前缀，注意不能使用too_作为表前缀
	'cus_file_path' => './../wx/dm/modules/pcadmin/', //自定义扩展引用文件
    ),
);
