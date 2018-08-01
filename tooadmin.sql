/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : tooadmin_init

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-08-01 11:12:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `too_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `too_login_log`;
CREATE TABLE `too_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `too_uid` int(11) DEFAULT NULL COMMENT '用户',
  `from_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '登录IP',
  `login_time` int(10) DEFAULT NULL COMMENT '登录时间',
  `login_area` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '登录地区',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for `too_menu`
-- ----------------------------
DROP TABLE IF EXISTS `too_menu`;
CREATE TABLE `too_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `pid` int(11) DEFAULT '0' COMMENT '所属上级',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '激活',
  `action_url` text COMMENT '附加参数URL',
  `module_id` int(11) DEFAULT NULL COMMENT '管理模块',
  `is_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为主页',
  `iclass` varchar(50) DEFAULT NULL COMMENT '图标class',
  `target_condition` text COMMENT '目标模块condition',
  `page_top_file` text COMMENT '页面顶部文件',
  `page_view_file` text COMMENT '页面视图文件',
  `link_page_type` tinyint(1) DEFAULT '0' COMMENT '页面类型;[0=普通页面],[1=自定义页面],[2=SQL查询页面]',
  `min_iclass` varchar(50) DEFAULT NULL COMMENT '小图标iclass',
  `query_sql` text COMMENT '查询SQL',
  `query_params` text COMMENT '查询表单参数',
  `is_show_code` text COMMENT '是否显示code',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='菜单栏';

-- ----------------------------
-- Records of too_menu
-- ----------------------------

-- ----------------------------
-- Table structure for `too_menu_items`
-- ----------------------------
DROP TABLE IF EXISTS `too_menu_items`;
CREATE TABLE `too_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL COMMENT '所属菜单',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '激活;[0=否],[1=是]',
  `action_url` text COMMENT '附加参数URL',
  `module_id` int(11) DEFAULT NULL COMMENT '管理模块',
  `iclass` varchar(50) DEFAULT NULL COMMENT '图标class',
  `target_condition` text COMMENT '目标模块condition',
  `target_join_sql` text COMMENT '目标模块join SQL',
  `page_top_file` text COMMENT '页面顶部文件',
  `page_view_file` text COMMENT '页面视图文件',
  `link_page_type` tinyint(1) DEFAULT '0' COMMENT '页面类型;[0=普通页面],[1=自定义页面],[2=SQL查询页面]',
  `min_iclass` varchar(50) DEFAULT NULL COMMENT '小图标iclass',
  `query_sql` text COMMENT '查询SQL',
  `query_params` text COMMENT '查询表单参数',
  `layout_compos` tinyint(1) DEFAULT '0' COMMENT '布局结构;[0=默认],[1=1行],[2=1/2行],[3=1/3行],[4=1/4行],[6=1/6行],[12=1/12行]',
  `layout_menu_items_pid` int(11) DEFAULT '0' COMMENT '从属布局结构菜单项',
  `remark` text COMMENT '备注说明',
  `is_show_code` text COMMENT '是否显示code',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `too_menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `too_menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='菜单栏';

-- ----------------------------
-- Records of too_menu_items
-- ----------------------------

-- ----------------------------
-- Table structure for `too_module`
-- ----------------------------
DROP TABLE IF EXISTS `too_module`;
CREATE TABLE `too_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '模块名称',
  `table_collection_id` int(11) NOT NULL COMMENT '数据表',
  `search_view` tinyint(1) DEFAULT '1' COMMENT '搜索模式',
  `allow_actions` varchar(255) DEFAULT NULL COMMENT '允许操作',
  `remark` text COMMENT '备注',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `is_pagination` tinyint(1) DEFAULT '1' COMMENT '是否分页',
  `page_item_count` int(11) DEFAULT '0' COMMENT '分页行数',
  `use_id_checkbox` tinyint(4) DEFAULT '0' COMMENT '选择框',
  `tree_table_column_id` int(11) DEFAULT NULL COMMENT '树形结构列',
  `gridview_width` int(11) DEFAULT '0' COMMENT 'gridview宽度',
  `default_order` text COMMENT '默认排序',
  `gridview_default_condition` text COMMENT '默认condition',
  `form_save_php_code` text COMMENT '表单保存执行PHP代码',
  `before_form_set_code` text COMMENT '表单加载前设置',
  `after_save_code` text COMMENT '保存表单后执行代码',
  `form_use_group` tinyint(1) DEFAULT '1' COMMENT '表单启用分组',
  `form_after_commit` text COMMENT '表单commit之后',
  `update_button_view` text COMMENT '修改按钮显示条件',
  `delete_button_view` text COMMENT '删除按钮显示条件',
  `view_button_view` text COMMENT '查看按钮显示条件',
  `expande_operate_button` text COMMENT '扩展操作按钮设置',
  `gridview_query_group` text COMMENT '默认group',
  `before_delete` text COMMENT '删除之前',
  `after_delete` text COMMENT '删除之后',
  `gridview_top_file` text COMMENT 'gridview顶部文件',
  `gridview_foot_file` text COMMENT 'gridview底部文件',
  `delete_after_commit` text COMMENT '删除commit之后',
  `join_sql` text COMMENT 'JOIN SQL',
  `expande_select_sql` text COMMENT '扩展select sql',
  `having_sql` text COMMENT 'having sql',
  `is_simulate_form` tinyint(1) DEFAULT '0' COMMENT '启动模拟表单;[0=否],[1=是]',
  `simulate_code` text COMMENT '模拟表单执行代码',
  `edit_from_type` tinyint(1) DEFAULT '0' COMMENT '表单编辑模式;[0=弹窗编辑],[1=列表内编辑]',
  `btn_add_alias` varchar(200) DEFAULT NULL COMMENT '添加操作别名',
  `btn_edit_alias` varchar(200) DEFAULT NULL COMMENT '修改操作别名',
  `btn_delete_alias` varchar(200) DEFAULT NULL COMMENT '删除操作别名',
  `btn_view_alias` varchar(200) DEFAULT NULL COMMENT '查看操作别名',
  `gridview_rewrite_file` text COMMENT 'gridview组件重写文件',
  `add_form_width` int(11) DEFAULT '0' COMMENT '表单添加width',
  `add_form_height` int(11) DEFAULT '0' COMMENT '表单添加height',
  `edit_form_width` int(11) DEFAULT '0' COMMENT '表单编辑width',
  `edit_form_height` int(11) DEFAULT '0' COMMENT '表单编辑height',
  `view_form_width` int(11) DEFAULT '0' COMMENT '查看表单width',
  `view_form_height` int(11) DEFAULT '0' COMMENT '查看表单height',
  `notuse_sys_form` tinyint(4) DEFAULT '0' COMMENT '不使用系统表单;[0=否],[1=是]',
  `add_button_view` text COMMENT '添加按钮显示条件',
  `expande_operate_title` varchar(255) DEFAULT NULL COMMENT '扩展操作标题',
  `allow_export` tinyint(1) DEFAULT '1' COMMENT '允许导出',
  `default_expand_all_tree` tinyint(1) DEFAULT '0' COMMENT '默认全部展开树',
  PRIMARY KEY (`id`),
  KEY `table_collection_id` (`table_collection_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1096 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_module
-- ----------------------------
INSERT INTO `too_module` VALUES ('10', '菜单栏管理', '9', '1', 'add,update,delete,view', null, '2180', '0', '0', '0', '0', '0', '', '', 'if(isset($_GET[\"qkm_pid\"])){\r\n$model->pid=intval($_GET[\"qkm_pid\"]);\r\n}', 'if(isset($_GET[\"qkm_pid\"])){\r\n$model->pid=intval($_GET[\"qkm_pid\"]);\r\n}', 'TDEvent_Menu::afterSave($model);', '1', '', '', '', '', '', '', '', 'TDEvent_Menu::afterDelete($model);', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('11', '功能模块管理', '12', '1', 'add,update,delete', null, '2200', '1', '0', '0', '0', '0', '', '$VAL = \"\";\r\nif(!TDSessionData::currentUserIsTooAdmin()) {\r\n$VAL = \"`t`.`table_collection_id` not in(\".TDTable::$sys_table_ids.\")\";\r\n}', '', '', '', '1', '', '', '', '', '', '', 'TDEvent_Module::beforeDelete($model);', '', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('17', '系统-角色', '11', '1', 'add,update,delete,view', '角色管理', '2210', '1', '0', '0', '0', '0', '', '', '', '', '', '1', '', '', '', '', '', '', null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('26', '数据表结构', '13', '1', 'add,update,delete,view', null, '2220', '1', '0', '0', '0', '0', '$VAL=\"`t`.`type` desc\";', '$VAL = \'\';\r\nif(!TDSessionData::currentUserIsTooAdmin()) {\r\n$VAL = \"`t`.`id` not in(\".TDTable::$sys_table_ids.\")\";\r\n}', '', '', '', '1', 'unset($_GET);unset($_POST);\r\nTDTable::synchronizeDBWithSys($model->id);', '', '', '', '', '', 'TDEvent_Table::beforeDelete($model->table);', '', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('31', '数据表字段', '20', '2', 'add,update,delete', null, '2230', '1', '50', '0', '0', '0', '', '', '', '', '', '1', '', '', '$VAL=$data->column_type==1;', '', '', '', 'TDEvent_Column::beforDeleveCusColumn($model->id);', '', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('36', '数据表字段选择', '20', '0', 'update', null, '2240', '1', '108', '1', '144', '0', '', '', '', '', '', '1', '', '', '', '', '', '', '', '', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('38', 'gridview字段', '26', '0', 'add,delete', null, '2250', '0', '0', '0', '0', '950', '', '', '', '', '', '1', '', '', '', '', '', '', '', '', 'if(isset($_GET[\"optype\"]) && $_GET[\"optype\"] == \"changeGridviewUseStatus\"){\r\n    $tomenuid = intval($_GET[\"tomenuid\"]);\r\n  	$csstatus = intval($_GET[\"csstatus\"]);\r\n  	if(empty($tomenuid)){\r\n  		echo \"tomenuid 为空,参数有误\";exit;\r\n    }\r\n	if(TDModelDAO::queryScalar(\"xg_wechat_pcgridview\",\"wechat_id=\".Xjkut::getCurwc().\" and gridview_id=\".$tomenuid,\"count(*)\") > 0) {\r\n		TDModelDAO::updateRowByCondition(\"xg_wechat_pcgridview\", \"wechat_id=\".Xjkut::getCurwc().\" and gridview_id=\".$tomenuid,array(\"is_use\"=>$csstatus));\r\n	} else {\r\n		$new = TDModelDAO::getModel(\"xg_wechat_pcgridview\");\r\n		$new->wechat_id =Xjkut::getCurwc();\r\n		$new->gridview_id = $tomenuid;\r\n		$new->is_use = $csstatus;\r\n      	$new->too_module_id = TDModelDAO::queryScalarByPk(\"too_module_gridview\",$tomenuid,\"module_id\");\r\n		$new->save();\r\n	}\r\n	echo \"success\";exit;\r\n}', 'echo \'\r\n<script>\r\nfunction changeGridviewUseStatus(id,csstatus){\r\n  var tip = csstatus == 1 ? \"激活该项\" : \"禁用该项\";\r\n  if(window.confirm(\"当前公众号是否确认\"+tip+\"？\")) {\r\n    	$.ajax({  \r\n			type:\"GET\",\r\n			dataType:\"text\",\r\n			url:\"\'.Yii::app()->request->url.\'?optype=changeGridviewUseStatus&tomenuid=\"+id+\"&csstatus=\"+csstatus,  \r\n			data:\"\",\r\n        	success:function(data){  \r\n              if(data==\"success\"){\r\n                  alert(\"操作成功\");\r\n                  to_gridview_refresh();\r\n              } else {\r\n                  alert(\"操作失败,\"+data);\r\n              }\r\n      		}  \r\n		});\r\n	}\r\n}\r\n</script>\r\n\';', '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('40', 'formupdate字段', '28', '0', 'add,delete', '', '2260', '0', '0', '0', '0', '850', '', '', '', '', '', '1', '', '', '', '', '', '', null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('71', '输入类型', '21', '0', 'add,update', '', '2270', '0', '0', '0', '0', '0', '', '', '', '', '', '1', '', '', '', '', '', '', null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('72', '管理员', '16', '1', 'add,update,delete,view', '', '2280', '1', '0', '0', '0', '0', '', '', '', '', '', '1', '', '', '', '', '', '', null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('73', '数据表字段分组', '68', '1', 'add,update,delete', '', '2290', '1', '0', '0', '0', '0', '', '', '', '', '', '1', '', '', '', '', '', '', null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('74', '模块表单模块', '69', '0', 'add,update,delete', null, '2300', '0', '0', '0', '0', '0', '', '', '', '', '', '1', '', '', '', '', '', '', '', '', null, null, '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('238', '系统登录日志', '186', '1', null, null, '2330', '1', '0', '0', null, '0', '$VAL=\"id desc\";', '', '', '', '', '1', '', '', '', '', '', '', '', '', null, null, '', null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('638', 'too_menu_items', '676', '1', 'update,delete', null, '5050', '0', '0', '0', null, '0', '', '', '', '', '', '1', 'TDEvent_Menu::afterSave($model);', '', '', '', '', '', '', '', 'if(isset($_GET[\"optype\"]) && $_GET[\"optype\"] == \"changeMenuItemsUseStatus\"){\r\n    $tomenuid = intval($_GET[\"tomenuid\"]);\r\n  	$csstatus = intval($_GET[\"csstatus\"]);\r\n  	if(empty($tomenuid)){\r\n  		echo \"tomenuid 为空,参数有误\";exit;\r\n    }\r\n	if(TDModelDAO::queryScalar(\"xg_wechat_pcmenu_items\",\"wechat_id=\".Xjkut::getCurwc().\" and menu_items_id=\".$tomenuid,\"count(*)\") > 0) {\r\n		TDModelDAO::updateRowByCondition(\"xg_wechat_pcmenu_items\", \"wechat_id=\".Xjkut::getCurwc().\" and menu_items_id=\".$tomenuid,array(\"is_use\"=>$csstatus));\r\n	} else {\r\n		$new = TDModelDAO::getModel(\"xg_wechat_pcmenu_items\");\r\n		$new->wechat_id =Xjkut::getCurwc();\r\n		$new->menu_items_id = $tomenuid;\r\n		$new->is_use = $csstatus;\r\n		$new->save();\r\n	}\r\n	echo \"success\";exit;\r\n}', 'echo \'\r\n<script>\r\nfunction changeMenuItemsUseStatus(id,csstatus){\r\n  var tip = csstatus == 1 ? \"激活该项\" : \"禁用该项\";\r\n  if(window.confirm(\"当前公众号是否确认\"+tip+\"？\")) {\r\n    	$.ajax({  \r\n			type:\"GET\",\r\n			dataType:\"text\",\r\n			url:\"\'.Yii::app()->request->url.\'?optype=changeMenuItemsUseStatus&tomenuid=\"+id+\"&csstatus=\"+csstatus,  \r\n			data:\"\",\r\n        	success:function(data){  \r\n              if(data==\"success\"){\r\n                  alert(\"操作成功\");\r\n                  to_gridview_refresh();\r\n              } else {\r\n                  alert(\"操作失败,\"+data);\r\n              }\r\n      		}  \r\n		});\r\n	}\r\n}\r\n</script>\r\n\';', '', '', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '', null, '1', '0');
INSERT INTO `too_module` VALUES ('868', 'too_role原职位对应', '11', '1', 'update', null, '6660', '1', '0', '0', null, '0', '', '', '', '', '', '1', '', '', '', '', '', '', '', '', null, null, '', '$rows = TDModelDAO::queryAll(\"xg_position\",\"wechat_id=\".Xjkut::getCurwc());\r\nforeach($rows as $row) {\r\n	$roleId = TDModelDAO::queryScalar(\"too_role\",\"expand_id=\".$row[\"id\"],\"min(id)\");\r\n	if(empty($roleId)) {\r\n		TDModelDAO::addRow(\"too_role\",array(\"name\"=>$row[\"name\"],\"remark\"=>$row[\"description\"],\"expand_id\"=>$row[\"id\"]));\r\n	} else {\r\n		TDModelDAO::saveRowByData(\"too_role\",$roleId,array(\"name\"=>$row[\"name\"]));\r\n	}\r\n}\r\n$VAL=\"join xg_position as xgp on xgp.id = t.expand_id and xgp.wechat_id=\".Xjkut::getCurwc();', '', '', '0', '', '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('969', 'too_module_gridview_expbtn', '833', '1', 'add,update,delete,view', null, '1030', '1', '0', '0', null, '0', null, '', '', null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');
INSERT INTO `too_module` VALUES ('1088', 'too_session', '931', '1', 'add,update,delete,view', null, '650', '1', '0', '0', null, '0', null, '', '', null, null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '0', null, null, null, null, null, '0', '0', '0', '0', '0', '0', '0', null, null, '1', '0');

-- ----------------------------
-- Table structure for `too_module_formedit`
-- ----------------------------
DROP TABLE IF EXISTS `too_module_formedit`;
CREATE TABLE `too_module_formedit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL COMMENT '所属模块',
  `table_column_id` int(11) NOT NULL COMMENT '字段',
  `belong_to_column_id` int(11) DEFAULT NULL COMMENT '所属字段',
  `belong_order_column_ids` varchar(255) DEFAULT NULL COMMENT '从属关系',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `use_add` tinyint(1) NOT NULL DEFAULT '1' COMMENT '添加显示',
  `use_update` tinyint(1) NOT NULL DEFAULT '1' COMMENT '修改显示',
  `use_view` tinyint(1) NOT NULL DEFAULT '1' COMMENT '查看显示',
  `readonly` tinyint(1) DEFAULT '0' COMMENT '只读',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `table_column_id` (`table_column_id`),
  KEY `belong_to_column_id` (`belong_to_column_id`),
  CONSTRAINT `too_module_formedit_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `too_module` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6216 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_module_formedit
-- ----------------------------
INSERT INTO `too_module_formedit` VALUES ('1', '11', '61', '0', '', '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('2', '11', '68', '0', '', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3', '11', '69', '0', '', '360', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4', '11', '70', '0', '', '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5', '11', '71', '0', '', '60', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6', '11', '72', '0', '', '80', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('7', '11', '148', '0', '', '140', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('8', '11', '63', '0', '0', '150', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('20', '26', '75', '0', '0', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('21', '26', '76', '0', '0', '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('26', '10', '50', '0', '0', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('27', '10', '52', '0', '0', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('80', '31', '80', '0', null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('82', '31', '81', '0', '', '60', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('116', '17', '57', '0', '', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('117', '17', '58', '0', '', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('122', '17', '269', '0', '', '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('123', '17', '270', '0', '', '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('124', '31', '274', '0', '', '80', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('125', '31', '275', '0', '', '140', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('126', '31', '276', '0', '', '150', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('127', '31', '125', '0', '', '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('128', '31', '126', '0', '', '120', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('129', '31', '79', '0', null, '30', '1', '0', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('130', '31', '122', '0', null, '40', '1', '0', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('131', '26', '74', '0', '', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('238', '31', '272', '0', '', '170', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('239', '31', '273', '0', '', '550', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('249', '17', '669', '0', '', '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('299', '71', '86', '0', '', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('300', '71', '695', '0', '', '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('301', '71', '85', '0', '', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('302', '31', '698', '0', '', '190', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('303', '31', '699', '0', '', '200', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('304', '31', '700', '0', '', '210', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('305', '31', '701', '0', '', '220', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('306', '31', '702', '0', '', '230', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('307', '31', '703', '0', '', '240', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('308', '31', '704', '0', '', '250', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('309', '31', '705', '0', '', '270', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('310', '31', '706', '0', '', '310', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('311', '31', '707', '0', '', '300', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('313', '31', '709', '0', '', '320', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('314', '31', '710', '0', '', '330', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('315', '31', '711', '0', '', '340', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('318', '31', '714', '0', '', '360', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('319', '31', '715', '0', '', '370', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('320', '31', '716', '0', '', '380', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('321', '31', '717', '0', '', '400', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('322', '31', '718', '0', '', '410', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('323', '31', '719', '0', '', '420', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('324', '31', '720', '0', '', '430', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('325', '31', '721', '0', '', '440', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('326', '31', '722', '0', '', '350', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('327', '11', '697', '0', '', '210', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('328', '31', '724', '0', '', '70', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('329', '11', '727', '0', '', '160', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('331', '31', '732', '0', '', '390', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('335', '73', '737', '0', '', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('336', '31', '738', '0', '', '450', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('337', '11', '739', '0', '', '130', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('338', '31', '740', '0', '', '110', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('342', '11', '745', '0', '', '260', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('345', '31', '78', '0', null, '10', '1', '1', '1', '1');
INSERT INTO `too_module_formedit` VALUES ('346', '11', '62', '0', null, '10', '0', '0', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('350', '74', '751', '0', '', '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('354', '74', '755', '0', '', '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('355', '74', '756', '0', '', '70', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('356', '74', '757', '0', '', '90', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('358', '11', '758', '0', '', '300', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('359', '31', '271', '0', null, '130', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('360', '74', '761', '0', '', '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('471', '31', '187', '0', '', '90', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('476', '31', '792', '0', '', '100', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('707', '31', '1065', '0', '', '460', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('717', '72', '118', '0', null, '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('718', '72', '119', '0', null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('719', '72', '120', '0', null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('720', '72', '121', '0', null, '70', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('721', '74', '1070', '0', '', '80', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('722', '11', '1071', '0', '', '290', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('733', '74', '1078', '0', '', '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1080', '72', '1680', '0', null, '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1092', '11', '1683', '0', '', '310', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1194', '31', '1717', '0', '', '530', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1195', '31', '1718', '0', '', '540', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1203', '11', '1722', '0', '', '70', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1208', '11', '1727', '0', '', '320', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1271', '11', '1751', '0', '', '90', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1272', '11', '1752', '0', '', '100', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1273', '11', '1753', '0', '', '110', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1274', '11', '1754', '0', '', '120', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1275', '31', '1755', '0', '', '470', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1276', '11', '1761', '0', '', '230', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1295', '31', '1790', '0', '', '260', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1305', '72', '1794', '0', null, '50', '0', '0', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1508', '11', '2006', null, null, '330', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1509', '11', '2007', null, null, '340', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1569', '11', '2075', null, null, '370', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1573', '11', '2079', null, null, '380', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1577', '11', '2090', null, null, '350', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('1822', '26', '2430', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('2545', '31', '3520', null, null, '280', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('2546', '31', '3521', null, null, '290', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('2881', '11', '3906', null, null, '250', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3054', '31', '4105', null, null, '480', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3055', '31', '4106', null, null, '490', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3056', '31', '4107', null, null, '500', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3061', '31', '4116', null, null, '510', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3062', '31', '4117', null, null, '520', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3103', '11', '4294', null, null, '220', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3104', '11', '4295', null, null, '240', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3238', '74', '4620', null, null, '60', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3239', '74', '4621', null, null, '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3254', '31', '4637', null, null, '160', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3414', '72', '1795', null, null, '60', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3430', '11', '5434', null, null, '270', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3431', '11', '5435', null, null, '280', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3437', '74', '5441', null, null, '100', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3439', '11', '5444', null, null, '390', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3440', '74', '5448', null, null, '110', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3441', '73', '5449', null, null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3442', '73', '5450', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3545', '31', '5703', null, null, '570', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3546', '31', '5704', null, null, '580', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3547', '31', '5705', null, null, '590', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3548', '31', '5708', null, null, '600', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3598', '638', '5902', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3599', '638', '5901', null, null, '60', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3600', '638', '5900', null, null, '55', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3601', '638', '5899', null, null, '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3603', '638', '5897', null, null, '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3606', '638', '5895', null, null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3607', '638', '5893', null, null, '10', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3609', '638', '5904', null, null, '80', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('3610', '638', '5905', null, null, '90', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4612', '638', '5892', null, null, '5', '0', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4897', '638', '6523', null, null, '35', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4898', '638', '6524', null, null, '37', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4981', '868', '57', null, null, '10', '1', '1', '1', '1');
INSERT INTO `too_module_formedit` VALUES ('4982', '868', '58', null, null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4983', '868', '269', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4984', '868', '270', null, null, '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4994', '11', '6609', null, null, '400', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4995', '11', '6610', null, null, '410', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4996', '11', '6611', null, null, '420', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('4997', '11', '6612', null, null, '430', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5056', '11', '6636', null, null, '375', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5078', '31', '6649', null, null, '172', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5085', '31', '6667', null, null, '610', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5086', '31', '6668', null, null, '620', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5095', '638', '6681', null, null, '100', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5111', '31', '6701', null, null, '135', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5179', '638', '6750', null, null, '52', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5194', '11', '6771', null, null, '440', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5195', '11', '6772', null, null, '450', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5196', '11', '6773', null, null, '460', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5197', '11', '6774', null, null, '470', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5198', '11', '6775', null, null, '480', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5199', '11', '6776', null, null, '490', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5386', '74', '6995', null, null, '120', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5387', '74', '6996', null, null, '130', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5388', '74', '6997', null, null, '140', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5397', '74', '7014', null, null, '150', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5398', '11', '7016', null, null, '500', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5450', '74', '7017', null, null, '34', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5451', '74', '7074', null, null, '32', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5457', '969', '7077', null, null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5458', '969', '7078', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5459', '969', '7079', null, null, '40', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5460', '969', '7080', null, null, '50', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5462', '969', '7082', null, null, '70', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5463', '969', '7084', null, null, '25', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5464', '74', '7083', null, null, '160', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5528', '11', '7165', null, null, '85', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5682', '638', '7372', null, null, '95', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5701', '11', '7391', null, null, '118', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5724', '11', '7425', null, null, '65', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5730', '31', '7435', null, null, '122', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5731', '31', '7436', null, null, '123', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('5732', '31', '7437', null, null, '124', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6094', '11', '7891', null, null, '63', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6095', '1088', '7894', null, null, '20', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6096', '1088', '7895', null, null, '30', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6134', '31', '7924', null, null, '365', '1', '1', '1', '0');
INSERT INTO `too_module_formedit` VALUES ('6136', '72', '7926', null, null, '80', '1', '1', '1', '0');

-- ----------------------------
-- Table structure for `too_module_formmodule`
-- ----------------------------
DROP TABLE IF EXISTS `too_module_formmodule`;
CREATE TABLE `too_module_formmodule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_module_id` int(11) NOT NULL COMMENT '所属模块表单',
  `ntable_module_id` int(11) DEFAULT '0' COMMENT '(一对多)多表的模块',
  `formtab_title` varchar(50) NOT NULL COMMENT '表单tab标题',
  `ntable_condition` text COMMENT '默认condition',
  `ntable_set_code` text COMMENT '多表表单固定设置',
  `default_relation_column` varchar(50) DEFAULT NULL COMMENT '自动关联字段',
  `ntable_before_form_code` text COMMENT '多表表单加载前设置',
  `tab_display_condition` text COMMENT 'tab是否显示条件',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `join_sql` text COMMENT 'JOIN SQL',
  `is_forread` tinyint(1) DEFAULT '0' COMMENT '用于查看;[0=否],[1=是]',
  `is_activate` tinyint(1) DEFAULT '1' COMMENT '启用',
  `order_type` tinyint(1) DEFAULT '0' COMMENT '排列方式;[0=tab项],[1=嵌入表单]',
  `view_to_gridview` tinyint(1) DEFAULT '0' COMMENT '表单模块嵌入gridview中;[0=否],[1=是]',
  `page_type` tinyint(1) DEFAULT '0' COMMENT '页面类型;[0=gridview模块],[1=自定义页面]',
  `page_code_dao` text COMMENT '自定义页面顶部',
  `page_code_view` text COMMENT '自定义页面内容',
  `page_url_get_array` text COMMENT '自定义页面URL GET传递的数组',
  `is_foredit` tinyint(1) DEFAULT '0' COMMENT '用于修改;[0=否],[1=是]',
  `is_foradd` tinyint(1) DEFAULT '0' COMMENT '用于添加;[0=否],[1=是]',
  `gridview_expbtn_id` int(11) DEFAULT '0' COMMENT '所属扩展操作按钮',
  PRIMARY KEY (`id`),
  KEY `form_module_id` (`form_module_id`),
  KEY `ntable_module_id` (`ntable_module_id`),
  CONSTRAINT `too_module_formmodule_ibfk_1` FOREIGN KEY (`form_module_id`) REFERENCES `too_module` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_module_formmodule
-- ----------------------------
INSERT INTO `too_module_formmodule` VALUES ('2', '11', '74', '模块表单模块', '', '', '749', '', '', '10', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('4', '26', '73', '字段分组', '', '', '736', '', '', '10', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('5', '26', '31', '数据表字段', '', '', '78', '', '', '20', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('9', '26', '11', '功能模块', '', '', '62', '', '', '30', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('158', '10', '638', 'tab内容项', null, null, '5892', null, '$VAL=false;\r\n$pid1=intval(TDModelDAO::queryScalar(TDTable::$too_menu,\"id=\".intval($model->pid),\"pid\"));\r\nif($pid1>0 && intval(TDModelDAO::queryScalar(TDTable::$too_menu,\"id=\".$pid1,\"pid\")) == 0){\r\n	$VAL=true;\r\n}', '10', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('246', '72', '0', '测试数据', null, null, null, null, null, '10', null, '0', '0', '0', '0', '1', 'echo \"<h1>测试页面顶部内容</h1>\";', 'echo \"<h1>测试页面底部内容</h1>\";', null, '0', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('284', '11', '969', '扩展操作按钮', null, null, '7081', null, null, '20', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('341', '11', '38', 'grdview显示字段', null, null, '152', null, null, '30', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');
INSERT INTO `too_module_formmodule` VALUES ('342', '11', '40', '编辑表单字段', null, null, '174', null, null, '40', null, '1', '1', '0', '0', '0', null, null, null, '1', '0', '0');

-- ----------------------------
-- Table structure for `too_module_gridview`
-- ----------------------------
DROP TABLE IF EXISTS `too_module_gridview`;
CREATE TABLE `too_module_gridview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL COMMENT '所属模块',
  `table_column_id` int(11) NOT NULL COMMENT '字段',
  `belong_to_column_id` int(11) DEFAULT NULL COMMENT '所属字段',
  `belong_order_column_ids` varchar(255) DEFAULT NULL COMMENT '从属关系',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `allow_order` tinyint(1) DEFAULT '0' COMMENT '可排序;bool',
  `allow_edit` tinyint(1) DEFAULT '0' COMMENT '可编辑;bool',
  `allow_sum` tinyint(1) DEFAULT '0' COMMENT '统计;bool',
  `allow_avg` tinyint(1) DEFAULT '0' COMMENT '平均值;bool',
  `width` int(11) DEFAULT '0' COMMENT '宽度(px)',
  `is_hidden` tinyint(1) DEFAULT '0' COMMENT '隐藏',
  `is_merge` tinyint(1) DEFAULT '0' COMMENT '合并',
  PRIMARY KEY (`id`),
  KEY `table_column_id` (`table_column_id`),
  KEY `belong_to_column_id` (`belong_to_column_id`),
  KEY `sys_module_gridview_ibfk_3` (`module_id`),
  CONSTRAINT `too_module_gridview_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `too_module` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8673 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_module_gridview
-- ----------------------------
INSERT INTO `too_module_gridview` VALUES ('13', '11', '61', '0', '', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('14', '11', '63', '0', '', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('15', '11', '70', '0', '', '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('49', '38', '61', '152', '152', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('50', '38', '80', '153', '153', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('51', '38', '154', '0', '0', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('52', '38', '80', '154', '154', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('54', '36', '79', '0', '0', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('55', '36', '80', '0', '0', '20', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('64', '10', '51', '0', '0', '120', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('65', '10', '52', '0', '0', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('66', '10', '53', '0', '0', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('69', '38', '155', '0', '0', '60', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('74', '40', '61', '174', '174', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('75', '40', '80', '175', '175', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('76', '40', '176', '0', '0', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('77', '40', '80', '176', '176', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('78', '40', '177', '0', '0', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('85', '26', '74', '0', '0', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('86', '26', '75', '0', '0', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('87', '26', '76', '0', '0', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('90', '31', '79', '0', '0', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('91', '31', '80', '0', '0', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('92', '31', '85', '81', '81', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('107', '38', '195', '0', '0', '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('108', '38', '196', '0', '0', '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('109', '38', '197', '0', '0', '90', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('110', '38', '198', '0', '0', '100', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('147', '17', '57', '0', '', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('148', '17', '58', '0', '', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('156', '17', '269', '0', '', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('157', '17', '270', '0', '', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('158', '11', '75', '62', '62', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('160', '31', '271', '0', '', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('358', '26', '673', '0', '', '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('365', '71', '84', '0', '', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('367', '71', '86', '0', '', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('368', '71', '136', '0', '', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('370', '71', '695', '0', '', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('371', '71', '696', '0', '', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('384', '38', '726', '0', null, '50', '0', '1', '1', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('390', '73', '737', '0', '', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('391', '31', '738', '0', null, '60', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('395', '40', '746', '0', '', '60', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('396', '40', '747', '0', '', '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('397', '40', '748', '0', '', '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('400', '74', '751', '0', '', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('404', '11', '759', '0', '', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('405', '11', '760', '0', '', '60', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('407', '74', '755', '0', null, '10', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('408', '74', '761', '0', '', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('431', '11', '69', '0', '', '90', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('478', '73', '826', '0', '', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('649', '17', '1037', '0', '', '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('678', '72', '118', '0', '', '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('680', '72', '120', '0', '', '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('681', '72', '121', '0', '', '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('699', '40', '1089', '0', '', '90', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1039', '31', '78', '0', '', '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1223', '72', '1794', '0', '', '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1337', '38', '1857', '0', '', '110', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1338', '38', '1858', '0', '', '120', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1551', '238', '2097', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1552', '238', '2098', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1553', '238', '2099', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1554', '238', '2100', null, null, '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1555', '238', '2101', null, null, '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('1921', '26', '2430', null, null, '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('3776', '31', '4144', null, null, '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('3777', '36', '4144', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('3835', '26', '73', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4139', '74', '4605', null, null, '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4185', '74', '4621', null, null, '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4186', '74', '4634', null, null, '50', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4448', '31', '77', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4604', '72', '1795', null, null, '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4694', '74', '5441', null, null, '60', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4697', '11', '5444', null, null, '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4698', '74', '5448', null, null, '70', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4699', '73', '5449', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4700', '73', '5450', null, null, '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('4701', '73', '735', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('5087', '638', '5894', null, null, '60', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('5088', '638', '5895', null, null, '50', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('5089', '638', '5893', null, null, '20', '0', '1', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('5091', '638', '5891', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('5094', '638', '5897', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('6743', '868', '56', null, null, '10', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('6744', '868', '57', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('6745', '868', '58', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7499', '74', '6995', null, null, '75', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7510', '74', '7017', null, null, '38', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7580', '74', '7074', null, null, '35', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7620', '969', '7076', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7621', '969', '7077', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7622', '969', '7078', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7623', '969', '7079', null, null, '40', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7624', '969', '7080', null, null, '50', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7626', '969', '7082', null, null, '70', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7627', '969', '7084', null, null, '25', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7628', '74', '7083', null, null, '55', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('7684', '969', '7131', null, null, '80', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8502', '10', '53', null, null, '130', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8542', '1088', '7893', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8543', '1088', '7894', null, null, '20', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8544', '1088', '7895', null, null, '30', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8597', '36', '81', null, null, '25', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `too_module_gridview` VALUES ('8598', '72', '7926', null, null, '60', '0', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for `too_module_gridview_expbtn`
-- ----------------------------
DROP TABLE IF EXISTS `too_module_gridview_expbtn`;
CREATE TABLE `too_module_gridview_expbtn` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) DEFAULT NULL COMMENT '按钮名称',
  `labeltext` text COMMENT '按钮文本',
  `is_active` tinyint(1) DEFAULT '1' COMMENT '启用',
  `link_type` tinyint(1) DEFAULT '0' COMMENT '链接模式;[0=弹窗],[1=打开新页面],[2=当前页面跳转]',
  `set_url` text COMMENT '自定义URL',
  `too_module_id` int(11) DEFAULT NULL COMMENT '所属模块',
  `remark` text COMMENT '备注说明',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `too_module_id` (`too_module_id`),
  CONSTRAINT `too_module_gridview_expbtn_ibfk_1` FOREIGN KEY (`too_module_id`) REFERENCES `too_module` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of too_module_gridview_expbtn
-- ----------------------------

-- ----------------------------
-- Table structure for `too_role`
-- ----------------------------
DROP TABLE IF EXISTS `too_role`;
CREATE TABLE `too_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '角色',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `action_permission` text COMMENT '动作权限',
  `menu_module_permission` text COMMENT '菜单权限',
  `dbp_query` text COMMENT '数据库操作权限',
  `dbp_add` text COMMENT '添加权限',
  `dbp_update` text COMMENT '修改权限',
  `dbp_delete` text COMMENT '删除表权限',
  `use_db_permission` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启动数据库权限',
  `expand_id` int(11) DEFAULT '0' COMMENT '扩展存储ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of too_role
-- ----------------------------

-- ----------------------------
-- Table structure for `too_session`
-- ----------------------------
DROP TABLE IF EXISTS `too_session`;
CREATE TABLE `too_session` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理后台session';

-- ----------------------------
-- Records of too_session
-- ----------------------------
INSERT INTO `too_session` VALUES ('gnvl5h4n0vlnb7akmsfepfndv8', '1533094508', 0x5969692E4343617074636861416374696F6E2E32303563393431332E7444536974652E636170746368617C733A343A22636E6A77223B5969692E4343617074636861416374696F6E2E32303563393431332E7444536974652E63617074636861636F756E747C693A313B34326434333663636636643236383436663630383662386565383032353731355F5F69647C733A313A2239223B34326434333663636636643236383436663630383662386565383032353731355F5F6E616D657C733A353A2261646D696E223B34326434333663636636643236383436663630383662386565383032353731355F5F7374617465737C613A303A7B7D7573657269647C733A313A2239223B757365726E616D657C733A353A2261646D696E223B6E69636B6E616D657C733A393A22E5BC80E58F91E88085223B726F6C65737C4E3B69736D616E616765727C733A333A22796573223B697341646D696E4D61726B7C623A313B6D656E755F7065726D697373696F6E5F7374727C733A313A2220223B636C69656E7457696474687C693A313834303B757365724D61726B5374727C733A31313A223931353333303930333232223B);

-- ----------------------------
-- Table structure for `too_table_collection`
-- ----------------------------
DROP TABLE IF EXISTS `too_table_collection`;
CREATE TABLE `too_table_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '表名称',
  `type` tinyint(4) NOT NULL COMMENT '所属分类; [0=未分类],[1=系统],[2=组件],[3=应用]',
  `engine` tinyint(1) NOT NULL DEFAULT '0' COMMENT '引擎',
  `is_systable` tinyint(1) DEFAULT '0' COMMENT '是否为系统表',
  `lastupdate_set` datetime DEFAULT NULL COMMENT '最后更改时间',
  PRIMARY KEY (`id`),
  KEY `table` (`table`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=948 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_table_collection
-- ----------------------------
INSERT INTO `too_table_collection` VALUES ('9', 'too_menu', '系统菜单', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('11', 'too_role', '系统角色', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('12', 'too_module', '系统模块', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('13', 'too_table_collection', '系统数据表', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('16', 'too_user', '系统管理员', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('20', 'too_table_column', '系统表字段', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('21', 'too_table_column_input', '系统输入类型', '1', '1', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('26', 'too_module_gridview', '系统模块Gridview配置', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('28', 'too_module_formedit', '系统模块表单配置', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('68', 'too_table_column_class', '系统表字段分类', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('69', 'too_module_formmodule', '系统模块表单模块', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('186', 'too_login_log', '系统登录日志', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('676', 'too_menu_items', 'too_menu_items', '2', '1', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('833', 'too_module_gridview_expbtn', 'too_module_gridview_expbtn', '1', '0', '1', '2018-05-15 00:00:00');
INSERT INTO `too_table_collection` VALUES ('931', 'too_session', 'too_session', '1', '0', '1', '2018-05-15 00:00:00');

-- ----------------------------
-- Table structure for `too_table_column`
-- ----------------------------
DROP TABLE IF EXISTS `too_table_column`;
CREATE TABLE `too_table_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_collection_id` int(11) NOT NULL COMMENT '数据表',
  `name` varchar(255) NOT NULL COMMENT '字段',
  `label` varchar(255) NOT NULL COMMENT '名称',
  `is_primary_key` tinyint(4) NOT NULL DEFAULT '0' COMMENT '主键',
  `db_type` varchar(255) DEFAULT NULL COMMENT '数据类型',
  `db_size` int(11) DEFAULT NULL COMMENT '长度',
  `db_precision` int(11) DEFAULT NULL COMMENT '精度',
  `default_value` varchar(255) DEFAULT NULL COMMENT '默认值',
  `allow_empty` tinyint(4) DEFAULT '1' COMMENT '允许为空',
  `foreign_table_column_id` int(11) DEFAULT NULL COMMENT '引用外键ID',
  `table_column_input_id` int(11) NOT NULL DEFAULT '1' COMMENT '输入类型',
  `column_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '字段类型',
  `formula` text COMMENT '运算公式',
  `formula_remark` text COMMENT '字段输入说明',
  `is_unique` tinyint(1) DEFAULT NULL COMMENT '是否唯一',
  `unique1_laddercolumn` int(11) DEFAULT NULL COMMENT '(同一表中的列)组合唯一约束列1',
  `unique2_laddercolumn` int(11) DEFAULT NULL COMMENT '(同一表中的列)组合唯一约束列2',
  `min_value` varchar(50) DEFAULT NULL COMMENT '最小值',
  `max_value` varchar(50) DEFAULT NULL COMMENT '最大值',
  `not_eq` varchar(255) DEFAULT NULL COMMENT '不等于',
  `in_array` text COMMENT '在某范围内,分开',
  `file_types` text COMMENT '文件类型',
  `file_max_size` double DEFAULT NULL COMMENT '文件最大限制(KB)',
  `file_too_large` text COMMENT '文件过大提示',
  `file_path` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `module_id` int(11) DEFAULT NULL COMMENT '模块ID',
  `map_table_collection_id` int(11) DEFAULT NULL COMMENT 'Map数据表',
  `value_laddercolumn` varchar(255) DEFAULT NULL COMMENT 'MapValue列ID',
  `append_laddercolumn` varchar(255) DEFAULT NULL COMMENT 'MapValue附加列ID',
  `optgroup_laddercolumn` varchar(255) DEFAULT NULL COMMENT '列表分组列ID',
  `change` varchar(255) DEFAULT NULL COMMENT 'onchange事件',
  `static_array` text COMMENT '静态数组,格式[key=value],[key=value].....',
  `grep_text` text COMMENT '正则表达式',
  `encrypt` varchar(255) DEFAULT NULL COMMENT '加密方式',
  `grep_tip_msg` text COMMENT '正则验证提示',
  `width` int(11) DEFAULT NULL COMMENT '宽度',
  `height` int(11) DEFAULT NULL COMMENT '高度',
  `order_group_laddercolumn` varchar(255) DEFAULT NULL COMMENT '排序组合ID',
  `display_validate` text COMMENT '是否显示验证',
  `map_condition` text COMMENT 'Map条件',
  `pid_view_columnid` int(11) DEFAULT NULL COMMENT 'pid显示的字段',
  `group_id` int(11) DEFAULT NULL COMMENT '所属分组',
  `auto_increment` tinyint(4) DEFAULT '0' COMMENT '自动增长',
  `in_form_notnull` tinyint(1) DEFAULT '0' COMMENT '表单必填',
  `input_expand_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'input扩展类',
  `input_front_txt` text COMMENT 'input前文本',
  `input_back_txt` text COMMENT 'input后文本',
  `edit_static_array` text COMMENT '编辑表单时所用数组',
  `save_expande_path` text COMMENT '保存扩展路径',
  `file_save_base_value` text COMMENT '文件字段值基础路径值',
  `file_read_base_value` text COMMENT '文件访问基础路径值',
  `choose_more` tinyint(1) DEFAULT '0' COMMENT 'foreign是否多选;[0=否],[1=是]',
  `auto_set_code` text COMMENT '自动赋值code',
  `use_org_filename` tinyint(1) DEFAULT '0' COMMENT '使用原文件名;[0=否],[1=是]',
  `foreign_relation` tinyint(1) DEFAULT '0' COMMENT 'foreign关系;[0=无],[1=N-1],[2=1-1]',
  `foreign_relation_condition` text COMMENT 'foreign关系条件',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `unique_check_condtion` text COMMENT '唯一约束检测附加condition',
  `order_group_key1` int(11) DEFAULT NULL COMMENT '组合排序字段1',
  `order_group_key2` int(11) DEFAULT NULL COMMENT '组合排序字段2',
  `order_group_key3` int(11) DEFAULT NULL COMMENT '组合排序字段3',
  `form_save_validate` text COMMENT '字段表单验证',
  `edit_form_code` text COMMENT '编辑表单自定义',
  `onchange_reload` tinyint(1) DEFAULT '0' COMMENT 'onchange后重载;[0=否],[1=是]',
  `pupup_chooseed_expfun` varchar(255) DEFAULT NULL COMMENT '弹出框选中后调用扩展js函数',
  `intercept_toolong` tinyint(1) DEFAULT '0' COMMENT '截取显示处理;[0=否],[1=是]',
  `gridview_query_show` tinyint(1) DEFAULT '1' COMMENT 'gridview搜索是否显示;[0=否],[1=是]',
  `for_query_join_sql` text COMMENT '搜索joinSQL',
  `for_query_condition_sql` text COMMENT '搜索conditionSQL',
  `tree_node_max` tinyint(1) DEFAULT '0' COMMENT '树节点数限制(0不限)',
  PRIMARY KEY (`id`),
  KEY `table_collection_id` (`table_collection_id`),
  KEY `table_column_input_id` (`table_column_input_id`),
  KEY `foreign_table_column_id` (`foreign_table_column_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8106 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_table_column
-- ----------------------------
INSERT INTO `too_table_column` VALUES ('49', '9', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('50', '9', 'name', '名称', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', null, '建议不超过6个汉字或12个数字字母', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('51', '9', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', null, null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', '52', null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '30', null, '52', null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('52', '9', 'pid', '所属上级', '0', 'int(11)', '11', '11', '0', '1', null, '15', '0', '', null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '$VAL=isset($_GET[\"qkm_pid\"]) || (!empty($model->id) && $model->pid == 0) ? false : true;\r\n$tip=\"有设置qkm_pid表示从快捷编辑中添加菜单\";', '', '50', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '40', '', null, null, null, '', '', '0', null, '0', '1', '', '', '3');
INSERT INTO `too_table_column` VALUES ('53', '9', 'is_show', '激活', '0', 'tinyint(4)', '4', '4', '1', '1', null, '2', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('54', '9', 'action_url', '附加参数URL', '0', 'text', null, null, null, '1', null, '1', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('55', '9', 'module_id', '管理模块', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', null, null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, '11', '12', '61', null, '728', null, null, null, null, null, '0', '0', null, null, '$VAL = \"\";\r\nif(!TDSessionData::currentUserIsTooAdmin()) {\r\n$VAL = \"`t`.`table_collection_id` not in(\".TDTable::$sys_table_ids.\")\";\r\n}', '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('56', '11', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('57', '11', 'name', '角色', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('58', '11', 'remark', '备注', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('60', '12', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('61', '12', 'name', '模块名称', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('62', '12', 'table_collection_id', '数据表', '0', 'int(11)', '11', '11', null, '0', null, '18', '0', '', null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, null, '13', '75', null, '76', 'postReloadCurrentForm()', null, null, null, null, '0', '0', null, '', '$VAL = \"\";\r\nif(!TDSessionData::currentUserIsTooAdmin()) {\r\n$VAL = \"`t`.`id` not in(\".TDTable::$sys_table_ids.\")\";\r\n}', '0', '5', '0', '0', '0', '', '', null, null, null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('63', '12', 'search_view', '搜索模式', '0', 'tinyint(1)', '1', '1', '1', '1', null, '17', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=不显示],[1=高级搜索],[2=高级组合搜索]\";', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('68', '12', 'allow_actions', '允许操作', '0', 'varchar(255)', '255', '255', null, '1', null, '3', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[add=添加],[update=修改],[delete=删除],[deletemore=批量删除],[view=查看]\";', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('69', '12', 'remark', '备注', '0', 'text', null, null, null, '1', null, '19', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, null, null, null, '', null, '0', '5', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '60', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('70', '12', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('71', '12', 'is_pagination', '是否分页', '0', 'tinyint(1)', '1', '1', '1', '1', null, '16', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=否],[1=是]\";', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('72', '12', 'page_item_count', '分页行数', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('73', '13', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('74', '13', 'table', '数据表', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '1', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('75', '13', 'name', '表名称', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('76', '13', 'type', '所属分类', '0', 'tinyint(4)', '4', '4', null, '0', null, '17', '0', '', null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, '0', '0', null, null, null, null, '$VAL = \"[0=未分类],[1=系统],[2=组件],[3=应用]\";', null, null, null, '0', '0', null, '', null, '0', null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('77', '20', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('78', '20', 'table_collection_id', '数据表', '0', 'int(11)', '11', '11', null, '0', null, '18', '0', '', null, '0', '79', '0', '0', '0', null, null, null, '0', null, null, null, '13', '74', null, '76', 'postReloadCurrentForm()', null, null, null, null, '0', '0', null, '', '', '0', '1', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '20', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('79', '20', 'name', '字段', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '78', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('80', '20', 'label', '名称', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('81', '20', 'table_column_input_id', '输入类型', '0', 'int(11)', '11', '11', '1', '0', null, '18', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '21', '85', '', '695', 'postReloadCurrentForm()', '', '', '', '', '0', '0', '', '', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '120', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('84', '21', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('85', '21', 'name', '输入类型', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('86', '21', 'remark', '备注', '0', 'text', null, null, null, '1', null, '19', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('117', '16', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('118', '16', 'username', '用户名', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', '', '', '1', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('119', '16', 'password', '密码', '0', 'varchar(255)', '255', '255', null, '1', null, '14', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', 'md5', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('120', '16', 'nickname', '昵称', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '1', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('121', '16', 'roles', '角色', '0', 'text', null, null, null, '1', null, '4', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '11', '57', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('122', '20', 'db_type', '数据类型', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('125', '20', 'default_value', '默认值', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->column_type == 0;', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('126', '20', 'allow_empty', '允许为空', '0', 'tinyint(4)', '4', '4', '1', '1', null, '16', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=否],[1=是]\";', '', '', '', '0', '0', '', '$VAL = $data->column_type == 0;', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('136', '21', 'db_types', '数据类型', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('144', '20', 'foreign_table_column_id', '引用外键ID', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '31', '20', '78,75', '', '78,76', '', '', '', '', '', '0', '0', '', '', '$VAL = \"`t`.`is_primary_key`=1\";', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '110', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('148', '12', 'use_id_checkbox', '选择框', '0', 'tinyint(4)', '4', '4', '0', '1', null, '16', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, '0', '0', null, '', null, '0', '5', '0', '0', '0', '', '', '', null, null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('151', '26', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('152', '26', 'module_id', '所属模块', '0', 'int(11)', '11', '11', null, '0', null, '12', '0', '', null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, null, '12', '60', '61', null, null, null, null, null, null, '0', '0', null, '', '', '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '20', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('153', '26', 'table_column_id', '字段', '0', 'int(11)', '11', '11', null, '0', null, '12', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '31', '20', '80', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('154', '26', 'belong_to_column_id', '所属字段', '0', 'int(11)', '11', '11', null, '1', null, '12', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '31', '20', '80', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('155', '26', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '152', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('173', '28', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('174', '28', 'module_id', '所属模块', '0', 'int(11)', '11', '11', null, '0', null, '12', '0', '', null, '0', '0', '0', '0', '0', null, null, null, '0', null, null, null, '12', '60', '61', null, null, null, null, null, null, '0', '0', null, '', '', '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '20', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('175', '28', 'table_column_id', '字段', '0', 'int(11)', '11', '11', null, '0', null, '12', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '31', '20', '80', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('176', '28', 'belong_to_column_id', '所属字段', '0', 'int(11)', '11', '11', null, '1', null, '12', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '31', '20', '80', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('177', '28', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '174', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('184', '28', 'belong_order_column_ids', '从属关系', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('186', '26', 'belong_order_column_ids', '从属关系', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('187', '20', 'is_primary_key', '主键', '0', 'tinyint(4)', '4', '4', '0', '0', null, '16', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=否],[1=是]\";', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('193', '20', 'db_size', '长度', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('194', '20', 'db_precision', '精度', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('195', '26', 'allow_order', '可排序', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('196', '26', 'allow_edit', '可编辑', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('197', '26', 'allow_sum', '统计', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('198', '26', 'allow_avg', '平均值', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('207', '21', 'is_auto_set_value', '自动设置值', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('269', '11', 'action_permission', '动作权限', '0', 'text', null, null, null, '1', null, '23', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('270', '11', 'menu_module_permission', '菜单权限', '0', 'text', null, null, null, '1', null, '24', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('271', '20', 'column_type', '字段类型', '0', 'tinyint(1)', '1', '1', '0', '0', null, '16', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=数据表字段],[1=自定义字段]\";', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('272', '20', 'formula', '运算公式', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '140', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('273', '20', 'formula_remark', '字段输入说明', '0', 'text', null, null, null, '1', null, '19', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '150', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('274', '20', 'is_unique', '是否唯一', '0', 'tinyint(1)', '1', '1', null, '1', null, '16', '0', '', '', '0', '0', '0', '0', '0', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=否],[1=是]\";', '', '', '', '0', '0', '', '$VAL = $data->column_type == 0;', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '160', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('275', '20', 'unique1_laddercolumn', '(同一表中的列)组合唯一约束列1', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '170', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('276', '20', 'unique2_laddercolumn', '(同一表中的列)组合唯一约束列2', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '180', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('669', '11', 'dbp_query', '数据库操作权限', '0', 'text', null, null, null, '1', null, '28', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('670', '11', 'dbp_add', '添加权限', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('671', '11', 'dbp_update', '修改权限', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('672', '11', 'dbp_delete', '删除表权限', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('673', '13', 'refresh_structure', '刷新', '0', 'varchar(255)', '255', '255', 'Empty String', '1', null, '25', '1', '$VAL = \'<a href=\"javascript:refreshTableStructure(\'.$data->id.\');void(0);\"><i class=\"icon icon-color icon-refresh\"></i></a>\';', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('695', '21', 'pid', 'pid', '0', 'int(11)', '11', '11', '0', '0', null, '15', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '85', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('696', '21', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('697', '12', 'tree_table_column_id', '树形结构列', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '110', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('698', '20', 'min_value', '最小值', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '190', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('699', '20', 'max_value', '最大值', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '200', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('700', '20', 'not_eq', '不等于', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '210', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('701', '20', 'in_array', '在某范围内,分开', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '220', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('702', '20', 'file_types', '文件类型', '0', 'text', null, null, null, '1', null, '1', '0', '', '多个类型使用逗号隔开,如: jpg,png,gif,jpeg', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '$VAL = $data->table_column_input_id == 11;', null, '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '230', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('703', '20', 'file_max_size', '文件最大限制(KB)', '0', 'double', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->table_column_input_id == 11;', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '240', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('704', '20', 'file_too_large', '文件过大提示', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->table_column_input_id == 11;', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '250', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('705', '20', 'file_path', '文件路径', '0', 'varchar(255)', '255', '255', null, '1', null, '25', '0', '', '上传文件时保存的基本路径,紧紧只作为上传文件的时候使用,它会结合扩展路径一起使用,\r\n如:  $VAL=\"file/img/\";  或  $VAL=\"/../wx/xjk/web/upload/productImg/\";', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '$VAL = $data->table_column_input_id == 11;', null, '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '260', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('706', '20', 'module_id', '模块ID', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '12', '61', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(4,12,18));', '$tbid = !empty($data->map_table_collection_id) ? $data->map_table_collection_id : 0;\r\n$VAL =\"`t`.`table_collection_id`=\".$tbid;', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '270', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('707', '20', 'map_table_collection_id', 'Map数据表', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, null, '13', '74', null, '76', 'postReloadCurrentForm()', null, null, null, null, '0', '0', null, '$VAL = in_array($data->table_column_input_id,array(4,12,18));', '', '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '280', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('709', '20', 'value_laddercolumn', 'MapValue列ID', '0', 'varchar(255)', '255', '255', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(4,12,18));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '290', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('710', '20', 'append_laddercolumn', 'MapValue附加列ID', '0', 'varchar(255)', '255', '255', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(4,12,18));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '300', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('711', '20', 'optgroup_laddercolumn', '列表分组列ID', '0', 'varchar(255)', '255', '255', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(4,12,18));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '310', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('714', '20', 'change', 'onchange事件', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(17,18));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '320', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('715', '20', 'static_array', '静态数组,格式[key=value],[key=value].....', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '300', '150', null, '$VAL = in_array($data->table_column_input_id,array(3,16,17,36));', null, '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '330', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('716', '20', 'grep_text', '正则表达式', '0', 'text', null, null, null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '340', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('717', '20', 'encrypt', '加密方式', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->table_column_input_id == 14;', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '350', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('718', '20', 'width', '宽度', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(10,19,25));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '370', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('719', '20', 'height', '高度', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(10,19,25));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '380', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('720', '20', 'order_group_laddercolumn', '排序组合ID', '0', 'varchar(255)', '255', '255', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->table_column_input_id == 13;', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '390', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('721', '20', 'display_validate', '是否显示验证', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '400', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('722', '20', 'map_condition', 'Map条件', '0', 'text', null, null, null, '1', null, '25', '0', '', '可用通过$model获取表单的数据对象,如 $VAL=empty($model->type)?false:\'class_type=1\';', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '$VAL = in_array($data->table_column_input_id,array(4,12,15,18));', null, '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '410', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('724', '20', 'pid_view_columnid', 'pid显示的字段', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = $data->table_column_input_id == 15;', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '420', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('726', '26', 'width', '宽度(%)', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '', null, '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '110', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('727', '12', 'gridview_width', 'gridview宽度', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '120', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('732', '20', 'grep_tip_msg', '正则验证提示', '0', 'text', null, null, null, '1', null, '19', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '3', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '360', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('735', '68', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('736', '68', 'table_id', '数据表', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '13', '75', '', '76', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('737', '68', 'group_name', '分组名称', '0', 'varchar(50)', '50', '50', null, '0', null, '1', '0', '', '', '0', '736', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('738', '20', 'group_id', '所属分组', '0', 'int(11)', '11', '11', null, '1', null, '18', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '68', '737', '', '', '', '', '', '', '', '0', '0', '', '', '$tbid = !empty($data->table_collection_id) ? $data->table_collection_id : 0;\r\n$VAL = \"`t`.`table_id` =\".$tbid;', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '430', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('739', '12', 'default_order', '默认排序', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '30', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('740', '20', 'auto_increment', '自动增长', '0', 'tinyint(4)', '4', '4', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '440', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('745', '12', 'gridview_default_condition', '默认condition', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '50', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '140', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('746', '28', 'use_add', '添加显示', '0', 'tinyint(1)', '1', '1', '1', '0', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('747', '28', 'use_update', '修改显示', '0', 'tinyint(1)', '1', '1', '1', '0', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('748', '28', 'use_view', '查看显示', '0', 'tinyint(1)', '1', '1', '1', '0', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('749', '69', 'form_module_id', '所属模块表单', '0', 'int(11)', '11', '11', null, '0', null, '12', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '11', '12', '61', null, '728', null, null, null, null, null, '0', '0', null, '', '', '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '20', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('751', '69', 'ntable_module_id', '(一对多)多表的模块', '0', 'int(11)', '11', '11', '0', '1', null, '12', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '11', '12', '61', null, null, 'postReloadCurrentForm()', null, null, null, null, '0', '0', null, '', '', '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '30', '', null, null, null, '', '', '1', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('755', '69', 'formtab_title', '表单tab标题', '0', 'varchar(50)', '50', '50', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('756', '69', 'ntable_condition', '默认condition', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '50', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('757', '69', 'ntable_set_code', '多表表单固定设置', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '200', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('758', '12', 'form_save_php_code', '表单保存执行PHP代码', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '200', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '150', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('759', '12', 'gridview_columns_admin', 'gridview字段', '0', 'varchar(255)', '255', '255', 'Empty String', '1', null, '25', '1', '$VAL = \"<button type=\'button\' class=\'btn\' onclick=\'to_gridview_admin(\".$data->id.\")\'>gridview</button>\";', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '300', '150', null, '', null, '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '520', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('760', '12', 'form_columns_admin', '表单字段', '0', 'varchar(255)', '255', '255', 'Empty String', '1', null, '25', '1', '$VAL = \"<button type=\'button\' class=\'btn\' onclick=\'to_form_admin(\".$data->id.\")\'>form</button>\";', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '150', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '530', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('761', '69', 'default_relation_column', '自动关联字段', '0', 'varchar(50)', '50', '50', null, '1', null, '22', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('792', '20', 'in_form_notnull', '表单必填', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL = \"[0=否],[1=是]\";', '', '', '', '0', '0', '', '', '', '0', '1', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '450', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('826', '68', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1037', '11', 'use_db_permission', '启动数据库权限', '0', 'tinyint(1)', '1', '1', '0', '0', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1065', '20', 'input_expand_type', 'input扩展类', '0', 'tinyint(1)', '1', '1', '0', '0', null, '17', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '$VAL=\"[0=input],[1=color]\";', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,\r\narray(1));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '460', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1067', '9', 'is_home', '是否为主页', '0', 'tinyint(1)', '1', '1', '0', '0', null, '16', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1069', '69', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1070', '69', 'ntable_before_form_code', '多表表单加载前设置', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '50', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1071', '12', 'before_form_set_code', '表单加载前设置', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '300', '50', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '160', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1078', '69', 'tab_display_condition', 'tab是否显示条件', '0', 'text', null, null, null, '1', null, '25', '0', '', '可以使用$model 读取当前表单的数据，通过设置$VAL变量的bool值控制是否显示tab，例如：$VAL=$model->pid > 0;', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '', null, '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '90', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('1089', '28', 'readonly', '只读', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1680', '16', 'phone', '联系电话', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1682', '9', 'iclass', '图标class', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1683', '12', 'after_save_code', '保存表单后执行代码', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '170', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1717', '20', 'input_front_txt', 'input前文本', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '470', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1718', '20', 'input_back_txt', 'input后文本', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '480', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1722', '12', 'form_use_group', '表单启用分组', '0', 'tinyint(1)', '1', '1', '1', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '5', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '180', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1724', '9', 'target_condition', '目标模块condition', '0', 'text', null, null, null, '1', null, '25', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '100', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1727', '12', 'form_after_commit', '表单commit之后', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '190', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1751', '12', 'update_button_view', '修改按钮显示条件', '0', 'text', null, null, null, '1', null, '25', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '200', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1752', '12', 'delete_button_view', '删除按钮显示条件', '0', 'text', null, null, null, '1', null, '25', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '210', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1753', '12', 'view_button_view', '查看按钮显示条件', '0', 'text', null, null, null, '1', null, '25', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '220', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1754', '12', 'expande_operate_button', '扩展操作按钮设置', '0', 'text', null, null, null, '1', null, '25', '0', null, null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, null, null, '0', '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '230', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1755', '20', 'edit_static_array', '编辑表单时所用数组', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '$VAL = in_array($data->table_column_input_id,array(3,16,17));', '', '0', '2', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '490', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1761', '12', 'gridview_query_group', '默认group', '0', 'text', null, null, null, '1', null, '25', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '7', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '240', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1790', '20', 'save_expande_path', '保存扩展路径', '0', 'text', null, null, null, '1', null, '25', '0', '', '上传文件的时候,文件夹路径变量,作为整体的路径存储在字段中,如: $VAL=date(\'Ymd\');', '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '$VAL = $data->table_column_input_id == 11;', null, '0', '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '500', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1794', '16', 'nt_number', 'NT编号', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '', null, '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '70', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('1795', '16', 'nt_code', 'NT代码', '0', 'varchar(500)', '500', '500', null, '1', null, '1', '0', '', null, '0', '0', '0', null, null, null, null, null, '0', null, null, '0', '0', null, null, null, null, null, null, null, null, '0', '0', null, '', null, '0', null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '80', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('1796', '16', 'nt_pwd', 'NT密码', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1857', '26', 'is_hidden', '隐藏', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '120', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1858', '26', 'is_merge', '合并', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1929', '168', 'id', 'id', '1', 'int(11)', '11', '11', '', '0', '0', '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '1', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1930', '168', 'uid', '购买者', '0', 'int(11)', '11', '11', '', '1', '0', '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1931', '168', 'cloudbuy_id', '云购项目', '0', 'int(11)', '11', '11', '', '1', '0', '12', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '167', '1917', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1932', '168', 'buy_amount', '购买金额', '0', 'decimal(5,2)', '5', '5', '0.00', '1', '0', '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1933', '168', 'buy_time', '购买时间', '0', 'int(10)', '10', '10', '', '1', '0', '9', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1934', '168', 'buyer_ip', '购买者IP', '0', 'varchar(255)', '255', '255', '', '1', '0', '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('1935', '168', 'buy_code', '云购编号', '0', 'varchar(255)', '255', '255', '', '1', '0', '1', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '0', '0', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '0', '0', '0', '0', '0', '', '', '', '', null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2006', '12', 'before_delete', '删除之前', '0', 'text', null, null, null, '1', null, '25', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '7', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '250', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2007', '12', 'after_delete', '删除之后', '0', 'text', null, null, null, '1', null, '25', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '7', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '260', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2075', '12', 'gridview_top_file', 'gridview顶部code', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '47', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '270', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2076', '9', 'page_top_file', '页面顶部文件', '0', 'text', null, null, null, '1', null, '34', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '110', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2077', '9', 'page_view_file', '页面视图文件', '0', 'text', null, null, null, '1', null, '34', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '120', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2078', '9', 'link_page_type', '页面类型', '0', 'tinyint(1)', '1', '1', '0', '1', null, '17', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=普通页面],[1=自定义页面],[2=SQL查询页面]\";', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2079', '12', 'gridview_foot_file', 'gridview底部code', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '47', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '280', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2090', '12', 'delete_after_commit', '删除commit之后', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', null, null, null, null, '0', null, '0', '0', null, '290', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2097', '186', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2098', '186', 'too_uid', '用户', '0', 'int(11)', '11', '11', null, '1', null, '12', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, '16', '118', null, null, null, null, null, null, null, null, null, null, '', '', null, null, '0', '0', '0', '', '', null, null, null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2099', '186', 'from_ip', '登录IP', '0', 'varchar(20)', '20', '20', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2100', '186', 'login_time', '登录时间', '0', 'int(10)', '10', '10', null, '1', null, '9', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', null, '0', '0', null, '40', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2101', '186', 'login_area', '登录地区', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2103', '188', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('2430', '13', 'engine', '引擎', '0', 'tinyint(1)', '1', '1', '0', '0', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=MyISAM],[1=InnoDB]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('3520', '20', 'file_save_base_value', '文件字段值基础路径值', '0', 'text', null, null, null, '1', null, '25', '0', '', '上传文件时, 文件路径 + 扩展路径 + 当前值 + 文件名称\r\n当前值会作为整体的路径值存在字段中 如：$VAL=\'/upload/productImg/\';', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = $data->table_column_input_id == 11;', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '510', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('3521', '20', 'file_read_base_value', '文件访问基础路径值', '0', 'text', null, null, null, '1', null, '25', '0', '', '访问图片路径的域名,默认为当前域名,可以设置为使用其它域名访问文件路径,如: $VAL=\'http://www.domainname.com/\';', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = $data->table_column_input_id == 11;', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '520', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('3906', '12', 'join_sql', 'JOIN SQL', '0', 'text', null, null, null, '1', null, '25', '0', '', '例如：join table_name as tbn on t.table_id=tbn.id and tbn.type=1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', null, null, null, null, '0', null, '0', '0', null, '300', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('3993', '9', 'min_iclass', '小图标iclass', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '140', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4105', '20', 'choose_more', 'foreign是否多选', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '$VAL = $data->table_column_input_id == 12;', null, null, '2', '0', '0', '0', '', '', '', null, null, null, '0', null, '0', '0', null, '530', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4106', '20', 'auto_set_code', '自动赋值code', '0', 'text', null, null, null, '1', null, '25', '0', '', '在保存当前数据表时,自动复制当前字段的值,可通过 $data 获取当前行的数据做逻辑运算,如: $VAL= $data->type==1 ? \'valu1\' : \'value2\';', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '540', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4107', '20', 'use_org_filename', '使用原文件名', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '$VAL = $data->table_column_input_id == 11;', null, null, '2', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '550', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4116', '20', 'foreign_relation', 'foreign关系', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=无],[1=N-1],[2=1-1]\";', null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(4,12,18));', null, null, '2', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '560', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4117', '20', 'foreign_relation_condition', 'foreign关系条件', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(4,12,18));', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '570', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4144', '20', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '580', '', '78', null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4294', '12', 'expande_select_sql', '扩展select sql', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '310', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4295', '12', 'having_sql', 'having sql', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '320', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4605', '69', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '100', '', '749', null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4620', '69', 'join_sql', 'JOIN SQL', '0', 'text', null, null, null, '1', null, '25', '0', '', '例如：join table_name as tbn on t.table_id=tbn.id and tbn.type=1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '110', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4621', '69', 'is_forread', '用于查看', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '120', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4634', '69', 'is_activate', '启用', '0', 'tinyint(1)', '1', '1', '1', '1', null, '2', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('4637', '20', 'unique_check_condtion', '唯一约束检测附加condition', '0', 'text', null, null, null, '1', null, '25', '0', '', '用于唯一约束检测时,附加的where条件,无 $data、$model 的运算取值,如：$VAL=\"is_delete=0\";', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '1', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '590', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5434', '12', 'is_simulate_form', '启动模拟表单', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', '提交表单时，不执行保存save', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '330', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5435', '12', 'simulate_code', '模拟表单执行代码', '0', 'text', null, null, null, '1', null, '25', '0', '', '只有在启动模拟表单后，当POST表单数据后，执行此处的代码,如果需要提示报错信息，则设置$error值', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '7', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '340', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5437', '9', 'query_sql', '查询SQL', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '300', '100', null, '', null, null, '50', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '150', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5439', '9', 'query_params', '查询表单参数', '0', 'text', null, null, null, '1', null, '25', '0', '', '$VAL二维数组 变量, 如: array(array(\'name\'=>\'create_time\',\'title\'=>\'标题\',\'type\'=>\'datetime\')); type 按输入类型设置', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '50', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '160', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5441', '69', 'order_type', '排列方式', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=tab项],[1=嵌入表单]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '140', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5444', '12', 'edit_from_type', '表单编辑模式', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', '当选择为列表内编辑时,允许编辑的字段只能列表中存在于配置的表单中的字段', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=弹窗编辑],[1=列表内编辑]\";', null, null, null, null, null, null, '', null, null, '5', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '350', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5448', '69', 'view_to_gridview', '表单模块嵌入gridview中', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '150', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5449', '68', 'pid', '从属分组', '0', 'int(11)', '11', '11', '0', '1', null, '18', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, '68', '737', null, null, null, '$VAL=\"[1=1TAB项],[1=1/2TAB项],[1=1/3TAB项],[1=1/4TAB项]\";', null, null, null, null, null, null, '', '$VAL=\"table_id=\".$model->table_id.\" and `pid`=0 and `id`<>\".intval($model->id);', null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '40', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5450', '68', 'span_num', '占用span数量', '0', 'tinyint(1)', '1', '1', '12', '1', null, '1', '0', '', '1至12', '0', null, null, '1', '12', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '50', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5703', '20', 'order_group_key1', '组合排序字段1', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(13));', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '600', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5704', '20', 'order_group_key2', '组合排序字段2', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(13));', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '610', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5705', '20', 'order_group_key3', '组合排序字段3', '0', 'int(11)', '11', '11', null, '1', null, '22', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(13));', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '620', '', null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5708', '20', 'form_save_validate', '字段表单验证', '0', 'text', null, null, null, '1', null, '25', '0', '', '$VAL变量为验证返回的异常信息,如果为空则表示验证通过,$model 为当前表单数据对象', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '3', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '630', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5891', '676', 'id', 'id', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '1', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5892', '676', 'menu_id', '所属菜单', '0', 'int(11)', '11', '11', null, '1', null, '12', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, '10', '9', '49', '50', null, null, null, null, null, null, null, null, null, '', '', null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '20', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5893', '676', 'name', '名称', '0', 'varchar(255)', '255', '255', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5894', '676', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '40', '', '5892', null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5895', '676', 'is_show', '激活', '0', 'tinyint(4)', '4', '4', '1', '1', null, '17', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5896', '676', 'action_url', '附加参数URL', '0', 'text', null, null, null, '1', null, '19', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '60', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5897', '676', 'module_id', '管理模块', '0', 'int(11)', '11', '11', null, '1', null, '12', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, '11', '12', '61', null, null, null, null, null, null, null, null, null, null, '', '', null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '70', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5898', '676', 'iclass', '图标class', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5899', '676', 'target_condition', '目标模块condition', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '90', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5900', '676', 'page_top_file', '页面顶部文件', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '110', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5901', '676', 'page_view_file', '页面视图文件', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '120', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5902', '676', 'link_page_type', '页面类型', '0', 'tinyint(1)', '1', '1', '0', '1', null, '17', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=普通页面],[1=自定义页面],[2=SQL查询页面]\";', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '130', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5903', '676', 'min_iclass', '小图标iclass', '0', 'varchar(50)', '50', '50', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '140', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5904', '676', 'query_sql', '查询SQL', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '150', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('5905', '676', 'query_params', '查询表单参数', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '160', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6523', '676', 'layout_compos', '布局结构', '0', 'tinyint(1)', '1', '1', '0', '1', null, '17', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=默认],[1=1列],[2=1/2列],[3=1/3列],[4=1/4列],[6=1/6列],[12=1/12列]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '170', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6524', '676', 'layout_menu_items_pid', '所属布局结构菜单', '0', 'int(11)', '11', '11', '0', '1', null, '18', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, '676', '5893', null, null, null, null, null, null, null, null, null, null, '', '$VAL=\"menu_id=\".intval($model->menu_id).\" and layout_menu_items_pid=0 and id<>\".intval($model->id);', null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '180', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6607', '11', 'expand_id', '扩展存储ID', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '110', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6609', '12', 'btn_add_alias', '添加操作别名', '0', 'varchar(200)', '200', '200', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '360', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6610', '12', 'btn_edit_alias', '修改操作别名', '0', 'varchar(200)', '200', '200', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '370', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6611', '12', 'btn_delete_alias', '删除操作别名', '0', 'varchar(200)', '200', '200', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '380', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6612', '12', 'btn_view_alias', '查看操作别名', '0', 'varchar(200)', '200', '200', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '64', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '390', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6636', '12', 'gridview_rewrite_file', 'gridview组件重写code', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '47', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '400', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6649', '20', 'edit_form_code', '编辑表单自定义', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '1', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '640', '', null, null, null, '', null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6667', '20', 'onchange_reload', 'onchange后重载', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, null, null, null, '2', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '650', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6668', '20', 'pupup_chooseed_expfun', '弹出框选中后调用扩展js函数', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = in_array($data->table_column_input_id,array(4,12,18));', null, null, '2', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '660', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6681', '676', 'remark', '备注说明', '0', 'text', null, null, null, '1', null, '19', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '190', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6701', '20', 'intercept_toolong', '截取显示处理', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, '1', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '670', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6750', '676', 'target_join_sql', '目标模块join SQL', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '100', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6771', '12', 'add_form_width', '表单添加width', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '410', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6772', '12', 'add_form_height', '表单添加height', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '420', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6773', '12', 'edit_form_width', '表单编辑width', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '430', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6774', '12', 'edit_form_height', '表单编辑height', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '440', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6775', '12', 'view_form_width', '查看表单width', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '450', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6776', '12', 'view_form_height', '查看表单height', '0', 'int(11)', '11', '11', '0', '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '70', '0', '0', '0', '', '$VAL=\"px\";', null, null, null, null, '0', '', '0', '0', null, '460', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6995', '69', 'page_type', '页面类型', '0', 'tinyint(1)', '1', '1', '0', '1', null, '17', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=gridview模块],[1=自定义页面]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '160', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6996', '69', 'page_code_dao', '自定义页面顶部', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '170', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('6997', '69', 'page_code_view', '自定义页面内容', '0', 'text', null, null, null, '1', null, '34', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '180', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7014', '69', 'page_url_get_array', 'URL传递GET数组', '0', 'text', null, null, null, '1', null, '34', '0', null, '可以通过 $model 来获取当前 form表单的数据对象', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '190', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7016', '12', 'notuse_sys_form', '不使用系统表单', '0', 'tinyint(4)', '4', '4', '0', '1', null, '16', '0', '', '如果设置是,则在编辑查看时,不加载配置的表单字段,只加载自定义页面', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, '5', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '470', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7017', '69', 'is_foredit', '用于修改', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '200', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7074', '69', 'is_foradd', '用于添加', '0', 'tinyint(1)', '1', '1', '0', '1', null, '2', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '210', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7076', '833', 'id', 'ID', '1', 'int(11)', '11', '11', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '1', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7077', '833', 'name', '按钮标题', '0', 'varchar(200)', '200', '200', null, '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '20', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7078', '833', 'is_active', '启用', '0', 'tinyint(1)', '1', '1', '1', '1', null, '2', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '40', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7079', '833', 'link_type', '链接模式', '0', 'tinyint(1)', '1', '1', '0', '1', null, '17', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=弹窗],[1=打开新页面],[2=当前页面跳转]\";', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '50', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7080', '833', 'set_url', '自定义URL', '0', 'text', null, null, null, '1', null, '25', '0', '', '可以使用 $data 引用gridview当前行的数据', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '60', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7081', '833', 'too_module_id', '所属模块', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7082', '833', 'remark', '备注说明', '0', 'text', null, null, null, '1', null, '19', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '80', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7083', '69', 'gridview_expbtn_id', '所属扩展操作按钮', '0', 'int(11)', '11', '11', '0', '1', null, '18', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, '833', '7077', null, null, null, null, null, null, null, null, null, null, '', '$VAL=\"too_module_id=\".intval($model->form_module_id);', null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', '', '220', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7084', '833', 'labeltext', '按钮文本', '0', 'text', null, null, null, '1', null, '25', '0', '', '可以使用 $data 引用gridview当前行的数据', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '30', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7131', '833', 'order', '排序', '0', 'int(11)', '11', '11', '0', '1', null, '13', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '90', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7165', '12', 'add_button_view', '添加按钮显示条件', '0', 'text', null, null, null, '1', null, '25', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '64', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '480', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7371', '9', 'is_show_code', '是否显示code', '0', 'text', null, null, null, '1', null, '25', '0', '', '菜单项是否显示的php执行验证 $VAL= bool', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '170', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7372', '676', 'is_show_code', '是否显示code', '0', 'text', null, null, null, '1', null, '25', '0', '', '菜单项是否显示的php执行验证 $VAL= bool', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '200', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7391', '12', 'expande_operate_title', '扩展操作标题', '0', 'varchar(255)', '255', '255', null, '1', null, '1', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '64', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '490', '', null, null, null, '', '', '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7425', '12', 'allow_export', '允许导出', '0', 'tinyint(1)', '1', '1', '1', '1', null, '16', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, null, null, null, '5', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '500', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7435', '20', 'gridview_query_show', 'gridview搜索是否显示', '0', 'tinyint(1)', '1', '1', '1', '1', null, '16', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, null, null, null, '1', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '680', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7436', '20', 'for_query_join_sql', '搜索joinSQL', '0', 'text', null, null, null, '1', null, '25', '0', '', '返回$VAL变量值,代码中可以$data读取传递过来的搜索词,用于组装join的SQL', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '1', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '690', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('7437', '20', 'for_query_condition_sql', '搜索conditionSQL', '0', 'text', null, null, null, '1', null, '25', '0', '', '返回$VAL变量值,代码中可以$data读取传递过来的搜索词,用于组装condition的SQL', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, '1', '0', '0', '0', '', '', null, null, null, null, '0', '', '0', '0', null, '700', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('7891', '12', 'default_expand_all_tree', '默认全部展开树', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', '只有当列表存在树形层级时才需要设置', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, '5', '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '510', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('7892', '13', 'is_systable', '是否为系统表', '0', 'tinyint(1)', '1', '1', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '60', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');
INSERT INTO `too_table_column` VALUES ('7893', '931', 'id', 'id', '1', 'char(32)', '32', '32', null, '0', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '10', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7894', '931', 'expire', 'expire', '0', 'int(11)', '11', '11', null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '20', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7895', '931', 'data', 'data', '0', 'blob', null, null, null, '1', null, '1', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '30', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7924', '20', 'tree_node_max', '树节点数限制(0不限)', '0', 'tinyint(1)', '1', '1', '0', '1', null, '1', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL = $data->table_column_input_id == 15;', null, null, '2', '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '710', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7925', '13', 'lastupdate_set', '最后更改时间', '0', 'datetime', null, null, null, '1', null, '9', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', null, null, null, null, null, null, '0', null, '0', '0', null, '70', null, null, null, null, null, null, '0', null, '0', '1', null, null, '0');
INSERT INTO `too_table_column` VALUES ('7926', '16', 'is_manager', '是否为管理员', '0', 'tinyint(11)', '11', '11', '0', '1', null, '16', '0', '', null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '$VAL=\"[0=否],[1=是]\";', null, null, null, null, null, null, '', null, null, null, '0', '0', '0', '', '', '', null, null, null, '0', '', '0', '0', null, '100', '', null, null, null, '', '', '0', null, '0', '1', '', '', '0');

-- ----------------------------
-- Table structure for `too_table_column_class`
-- ----------------------------
DROP TABLE IF EXISTS `too_table_column_class`;
CREATE TABLE `too_table_column_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL COMMENT '数据表',
  `group_name` varchar(50) NOT NULL COMMENT '分组名称',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  `pid` int(11) DEFAULT '0' COMMENT '从属分组',
  `span_num` tinyint(1) DEFAULT '12' COMMENT '占用span数量',
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`),
  CONSTRAINT `too_table_column_class_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `too_table_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of too_table_column_class
-- ----------------------------
INSERT INTO `too_table_column_class` VALUES ('1', '20', '基本信息', '10', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('2', '20', '表单显示', '130', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('3', '20', '验证规则', '120', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('5', '12', '基本信息', '90', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('7', '12', 'php代码', '110', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('47', '12', 'Gridview 扩展文件', '400', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('48', '9', '基本信息', '410', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('64', '12', '编辑操作配置', '105', '0', '12');
INSERT INTO `too_table_column_class` VALUES ('70', '12', '表单宽高', '610', '0', '12');

-- ----------------------------
-- Table structure for `too_table_column_input`
-- ----------------------------
DROP TABLE IF EXISTS `too_table_column_input`;
CREATE TABLE `too_table_column_input` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '输入类型',
  `db_types` text,
  `is_auto_set_value` tinyint(1) DEFAULT '0' COMMENT '自动设置值;bool',
  `remark` text COMMENT '备注',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'pid',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='输入类型定义';

-- ----------------------------
-- Records of too_table_column_input
-- ----------------------------
INSERT INTO `too_table_column_input` VALUES ('1', 'input', '', '0', '普通输入框', '33', '220');
INSERT INTO `too_table_column_input` VALUES ('2', 'bool', 'tinyint,int,bigint', '0', '是否', '33', '230');
INSERT INTO `too_table_column_input` VALUES ('3', 'checkbox', 'varchar,text', '0', '复选框', '31', '110');
INSERT INTO `too_table_column_input` VALUES ('4', 'checkboxdb', 'varchar,text', '0', '数据复选框', '30', '80');
INSERT INTO `too_table_column_input` VALUES ('5', 'hidden', '', '0', '隐藏域', '33', '240');
INSERT INTO `too_table_column_input` VALUES ('6', 'createtime', 'int,datetime', '1', '创建时间，自动设置', '29', '20');
INSERT INTO `too_table_column_input` VALUES ('7', 'createuser', 'int,bigint', '1', '创建者,自动设置', '29', '30');
INSERT INTO `too_table_column_input` VALUES ('8', 'date', 'int,date', '0', '日期', '33', '250');
INSERT INTO `too_table_column_input` VALUES ('9', 'datetime', 'int,datetime', '0', '日期时间', '33', '260');
INSERT INTO `too_table_column_input` VALUES ('10', 'editor', 'text', '0', '编辑器', '33', '270');
INSERT INTO `too_table_column_input` VALUES ('11', 'file', 'varchar,text', '0', '文件', '33', '280');
INSERT INTO `too_table_column_input` VALUES ('12', 'foreignkey', 'int,bigint', '0', '外键', '32', '140');
INSERT INTO `too_table_column_input` VALUES ('13', 'order', 'int,bigint,decimal', '1', '排序', '29', '40');
INSERT INTO `too_table_column_input` VALUES ('14', 'password', 'varchar,text', '0', '密码', '33', '290');
INSERT INTO `too_table_column_input` VALUES ('15', 'pid', 'int,bigint', '0', '所属上级', '32', '300');
INSERT INTO `too_table_column_input` VALUES ('16', 'radio', '', '0', '单选框', '31', '200');
INSERT INTO `too_table_column_input` VALUES ('17', 'select', '', '0', '下拉框', '31', '120');
INSERT INTO `too_table_column_input` VALUES ('18', 'selectdb', '', '0', '数据下拉框', '30', '90');
INSERT INTO `too_table_column_input` VALUES ('19', 'text', 'text', '0', '文本编辑器', '33', '310');
INSERT INTO `too_table_column_input` VALUES ('20', 'updatetime', 'int,datetime', '1', '更新时间，自动设置', '29', '50');
INSERT INTO `too_table_column_input` VALUES ('21', 'updateuser', 'int,bigint', '1', '更新者，自动设置', '29', '60');
INSERT INTO `too_table_column_input` VALUES ('22', 'laddercolumn', 'varchar,text', '0', '设置从属阶梯列ID多个用逗号,分开', '32', '150');
INSERT INTO `too_table_column_input` VALUES ('23', 'actionpermission', 'text', '0', '设置action权限', '32', '160');
INSERT INTO `too_table_column_input` VALUES ('24', 'menumodulepermission', 'text', '0', '设置菜单以及模块操作权限', '32', '170');
INSERT INTO `too_table_column_input` VALUES ('25', 'formula', 'text', '0', '运算公式,所选择的字段必须是从基表中的字段或由基表扩展表的字段', '32', '320');
INSERT INTO `too_table_column_input` VALUES ('26', 'condition', 'text', '0', 'SQL条件定义,定义查询或修改数据时的条件SQL语句', '32', '190');
INSERT INTO `too_table_column_input` VALUES ('28', 'dbtablepermission', 'text', '0', '数据表操作权限', '32', '180');
INSERT INTO `too_table_column_input` VALUES ('29', 'autoset', '', '0', null, '0', '210');
INSERT INTO `too_table_column_input` VALUES ('30', 'arraydb', '', '0', '', '0', '10');
INSERT INTO `too_table_column_input` VALUES ('31', 'arraystatic', '', '0', '', '0', '70');
INSERT INTO `too_table_column_input` VALUES ('32', 'tool', '', '0', '', '0', '100');
INSERT INTO `too_table_column_input` VALUES ('33', 'common', '', '0', '', '0', '130');
INSERT INTO `too_table_column_input` VALUES ('34', 'editfile', 'text', '0', '文件编辑', '32', '330');
INSERT INTO `too_table_column_input` VALUES ('35', 'time', 'varchar,text,time', '0', '时间', '33', '340');
INSERT INTO `too_table_column_input` VALUES ('36', 'is_del', 'tinyint,int,bigint', '0', '删除状态', '32', '350');

-- ----------------------------
-- Table structure for `too_user`
-- ----------------------------
DROP TABLE IF EXISTS `too_user`;
CREATE TABLE `too_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `roles` text COMMENT '角色;checkboxdb=[table=sys_role],[key=id],[value=name]',
  `phone` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `nt_number` varchar(50) DEFAULT NULL COMMENT 'NT编号',
  `nt_code` varchar(500) DEFAULT NULL COMMENT 'NT代码',
  `nt_pwd` varchar(50) DEFAULT NULL COMMENT 'NT密码',
  `is_manager` tinyint(11) DEFAULT '0' COMMENT '是否为管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of too_user
-- ----------------------------
INSERT INTO `too_user` VALUES ('124', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '开发者', null, null, null, null, null, '1');
