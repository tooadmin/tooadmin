<?php

class TDLanguage {

	public static $install_title = "tooadmin 安装";
	public static $install_remark = "系统参数配置信息";
	public static $install_db_name = "mysql数据库名";
	public static $install_db_user = "mysql登录用户";
	public static $install_db_pwd = "mysql登录密码";
	public static $install_install = "安装";

	
	public static $view = '查看';
	public static $nopid =  '--无--';
	public static $child = '子级数量';
	public static $please_choose = '---请选择---'; 
	public static $please_choose_edit_form = '---------请选择---------'; 

	public static $common_button_operate = '操作';
	public static $common_button_view = '查看';
	public static $common_button_update = '修改';
	public static $common_button_add = '添加';
	public static $common_button_delete = '删除';
	public static $common_button_cancel = '取消';
	public static $common_button_save = '保存';
	public static $true = '是';
	public static $false = '否';
	public static $title_add = '添加';
	public static $title_edit = '编辑';
	public static $advanced_search = '高级搜索';
	public static $advanced_search_btn = '搜索';
	public static $advanced_search_combination_btn = '组合公式搜索';
	public static $advanced_search_select_null = 'null(未设置)';
	public static $advanced_search_combination_formula = "条件组合公式";
	public static $advanced_search_create_condition = '搜索';
	public static $advanced_search_combination_create_condition = '确定';
	
	public static $choose_condition_column = '选择条件';
	public static $choose_condition_combination = '条件';
			
	public static $condion_type_eq = '等于'; 
	public static $condion_type_gre = '大于'; 
	public static $condion_type_les = '小于'; 
	public static $condion_type_greeq = '大于等于'; 
	public static $condion_type_leseq = '小于等于'; 
	public static $condion_type_noteq = '不等于'; 

	public static $condion_type_LeftRightLike = '%关键字%'; 
	public static $condion_type_LeftLike = '%关键字'; 
	public static $condion_type_RightLike = '关键字%'; 

	public static $condion_type_include = '包含'; 
	public static $condion_type_NotInclude = '不包含'; 

	public static $form_foreign_search_byReadCardID = '读卡';
	public static $form_foreign_search = '搜索';
	public static $form_foreign_search_cancel = '取消';
	public static $form_popup_saerch_title = '搜索数据';
	public static $form_popup_saerch_choose = '选择';

	public static $main_topbar_backupDataBase= '备份数据库';
	public static $main_topbar_refreshTableOk = '刷新成功!';
	public static $main_topbar_update_info = '修改密码';
	public static $main_topbar_logout = '退出登录';
	public static $main_topbar_refreshSession = '更新缓存';
	public static $tip_msg_operate_ok = '操作成功!';
	public static $tip_msg_operate_fail = '操作失败!';
	public static $tip_msg_operate_cash_refresh_ok = '刷新缓存成功!';
	
	public static $tip_msg_not_have_choooseed_column = '请先选择要显示的列!';
	public static $tip_msg_check_delete_new_column = '是否确认删除自定义列?';
	public static $tip_msg_check_drop_column = '是否确认删除字段?';
	public static $tip_msg_check_delete_current = '是否确认删除?';
	public static $tip_msg_check_delete_current_menu = '删除菜单,将删除该菜单所有子菜单及模块,是否确认删除?';
	public static $button_delete = '删除';
	public static $tip_msg_save_success = '保存成功!';


	public static $validate_msg_date_error = "日期格式不正确";
	public static $validate_msg_datetime_error = "日期时间格式不正确";

	public static $database_tables = '数据表';

	public static $choose_column_button_tip = '确认选中';
	public static $choose_column = '选择字段';
	
	public static $common_sum = '统计:';
	public static $common_avg = '平均:';

	public static $common_msg_param_error = '参数传递有误!';
	public static $common_msg_unneed_upgrade = '当前版本已经是最新的版本';
	public static $common_msg_please_upgrade = '有新的版本,请下载升级!';
	public static $common_not_allow_empty = '不允许为空!';
	
	public static $unitAction_person_info = '个人信息';
	public static $unitAction_nickname_empty = '呢称不可以为空';
	public static $unitAction_password_empty = '原密码不可以为空';
	public static $unitAction_oldpwd_error = '原密码不正确';	
	public static $unitAction_newpwd_empty = "新密码不可以为空";
	public static $unitAction_pwdless_length = '密码长度不可少于6个字符';
	public static $unitAction_checkpwd_error = '确认密码不正确';
	public static $unitAciton_update_success_relogin = '修改成功,请重新登录!';

	public static $AjaxController_Remark = 'ajax请求';
	public static $AjaxController_ExpandTableTreeData = '展开表数据树形结构';
	public static $AjaxController_ColumnsByTable = '加载表的所有字段';
	public static $AjaxController_ConditionLoadInputType = '加载搜索条件';
	public static $AjaxController_BackupDataBase = '备份数据库';
	public static $AjaxController_ConditionLoadTableColumns = '加载搜索的字段';
	public static $AjaxController_GetPopupData = '获取弹出框的数据';
	public static $AjaxController_GetPopupLadderColumn = '获取弹出框递归的列';
	public static $AjaxController_UpdateARow = '更新一行数据';
	public static $AjaxController_RefreshSession = '更新缓存';
	public static $AjaxController_CommonOperate= '通用操作';
	public static $AjaxController_ReorderRows = '重新排序';
	public static $AjaxController_NtValidate = 'NT加密锁验证';
	
	public static $CommonController_Remark = '通用操作';
	public static $CommonController_View = '查看详细';
	public static $CommonController_Delete = '删除数据';
	public static $CommonController_Admin = '数据管理';
	public static $CommonController_Edit = '编辑数据';
	public static $CommonController_MenuItems = '菜单模块';
	public static $CommonController_LayoutCompos = '组合布局';
	public static $CommonController_EditBool = 'bool操作';
	public static $CommonController_PopupSearch = '弹出框搜索数据';
	public static $CommonController_Custome= '自定义扩展';
	public static $CommonController_Render = '直接渲染页面';
	public static $CommonController_ExpandeFunction = '执行扩展类函数';
	public static $CommonController_Query = 'SQL查询统计';

	public static $DataBaseController_Remark = '数据表结构管理';
	public static $DataBaseController_View = '查看详细';
	public static $DataBaseController_Admin = '表结构管理';
	public static $DataBaseController_Edit = '编辑字段';
	public static $DataBaseController_Delete = '删除字段';

	public static $ModuleController_Remark = '模块管理';
	public static $ModuleController_ChooseColumns = '选择字段列';
	public static $ModuleController_CommonChooseColumns = '通用选择字段列';
	public static $ModuleController_ModuleColumns = '模块已选中的字段';
	public static $ModuleController_ColumnsForModule = '选中字段设置到模块';

	public static $UnitActionController_Remark = '单元动作';
	public static $UnitActionController_UpdateInfo = '修改个人信息';
	public static $UnitActionController_PopupConditionEdit = '编辑条件语句';
	public static $UnitActionController_CreateQrcode = '生成二维码';
	public static $UnitActionController_Mysql= 'MySql';
	public static $UnitActionController_FileConten= '生成临时文件内容';
	public static $UnitActionController_CheckUpgrade = '检测更新升级';
	public static $UnitActionController_ExportTableHtml = "导出EXCEL";	
	public static $UnitActionController_RefreshCash= "刷新缓存";	
	public static $UnitActionController_RefreshTableStruct = "刷新表结构";	
	public static $UnitActionController_OpenDevModel = "启动开发模式";	
	public static $UnitActionController_CloseDevModel = "关闭开发模式";	
	public static $UnitActionController_STRUCT_MENU = "菜单结构";	

	public static $TDMinMysqlController_Remark = "Min MySQL";
	public static $TDMinMysqlController_View = '查看详细';
	public static $TDMinMysqlController_Delete = '删除数据';
	public static $TDMinMysqlController_Admin = '数据管理';
	public static $TDMinMysqlController_Edit = '编辑数据';

	
	
	public static $Operate_gridview = '列表';
	public static $Operate_add = '添加';
	public static $Operate_update = '修改';
	public static $Operate_view= '查看';
	public static $Operate_delete = '删除';
	public static $Operate_delete_file_confirm = "是否确认删除，删除后将不可恢复？";
	public static $Operate_delete_chooseed = "删除选中";
	public static $Operate_tip_chooseed_empty = "请先选中要删除的数据!";
	public static $Operate_tip_delete_chooseed = "是否确认删除选中？";

	public static $file_validate_type_error = "文件类型不正确,允许上传的文件类型为";
	public static $file_validate_size_error = "文件过大,最大不超过";

	public static $custom_validate_error = '验证不通过';
	public static $custom_code_empty = '代码为空'; 
	public static $custom_missing_end_code = '缺少结束符号";"或"}"'; 
	public static $custom_syntax_error = '语法错误'; 
	public static $custom_missing_result_val = '缺少结果变量$VAL'; 
	public static $validate_msg_not_unique = '该数据已存在';
	public static $validate_unpass = '输入不正确';
	public static $validate_not_less_than = '不能小于';
	public static $validate_not_more_than = '不能大于';
	public static $validate_not_equal = '不能等于';
	public static $validate_not_in_array = '不在约定的数组内';

	public static $condition_button_edit = '编辑';
	public static $checkBox_ChooseAll = "全选";

	//login start
	public static $login_enter_pwd_name = '请输入您的用户名和密码';
	public static $login_username = '用户名';
	public static $login_password = '密码';
	public static $login_verify_code = '验证码';
	public static $login_refresh_verifycode = '看不清？';
	public static $login_enter_button = '登陆';
	public static $login_msg_username_empty = '用户名不能为空!';
	public static $login_msg_pwd_empty = '密码不能为空!';
	public static $logon_msg_verify_code_empty = '验证码不能为空!';
	public static $logon_msg_verify_code_error = '验证码不正确!';
	public static $login_msg_name_error = '用户名错误!';
	public static $login_msg_pwd_error = '密码错误!';
	public static $login_msg_usbkey_error = '加密锁与登陆用户不匹配!';
	//login end

	//系统操作
	public static $sys_operate_chooseAllTable = '-----所有数据表------';
	public static $sys_operate_button_create_static_file = '生成静态文件';
	public static $sys_operate_msg_create_success = '文件生成成功！';
	public static $sys_operate_msg_create_fail = '文件生成失败,请设置当前为开发模式,并重新登陆！';
	public static $sys_operate_excute = "执行SQL";
	public static $sys_excute_sql_result = "执行完毕,受影响的行:";
	public static $sys_website_publish_error = "执行发布站点出错";
	
	//权限
	public static $sys_permission_delete_data = "删除数据";	
	public static $operate_no_permission = "操作权限不够";

	public static $common_tip_msg_1 = "table id is empty";	
	public static $common_tip_msg_2 = "table name is empty";	

	public static $to_refresh = "刷新";
	public static $to_columns_admin= "字段使用";
	public static $to_gridview_set = "模块设置";
	public static $to_export_excel = "导出Excel";
	public static $to_add_panel_row = "添加一行";
	public static $to_split_panel_row_left = "左拆分行";
	public static $to_split_panel_row_right = "右拆分行";
	public static $to_merger_panel_items = "合并单元格";
	public static $to_delete_panel_items = "删除选中行";
	public static $to_tip_confirm_delete_panel_row = "是否确认删除选中行?";
	public static $to_tip_confirm_splite_panel_row = "是否确认拆分选中行?";
	public static $to_tip_confirm_merger_panel_items = "是否确认合并选中项?";
	public static $to_tip_illegal_merger = "不符合合并规则";
	public static $refreshAllTablesStructure = "同步所有表结构";
	public static $to_tip_refreshAllTablesStructure = "是否确认刷新同步所有数据表结构?";
	
	public static $menu_menu_manage = "菜单栏管理";
	public static $menu_module_manage = "功能模块";
	public static $menu_system_config = "系统配置";
	public static $menu_system_upgrade= "更新升级";
	public static $menu_role_permission = "角色权限";
	public static $menu_user_manage = "用户管理";
	public static $menu_table_and_module = "数据表和模块";
	public static $menu_api_permission = "API权限";
	public static $menu_watermark_module = "水印模板";
	public static $menu_check_data = "数据核算";
	public static $menu_website_mg = "站点管理";
	public static $menu_website_themes = "站点主题";
	public static $menu_website_page = "站点页面";
	public static $menu_login_log = "登录日志";
	public static $menu_export_syssql = "导出系统表";

	public static $mysql_table_columns = "数据表字段";
	public static $mysql_table_create_SQL = "数据表SQL";
	public static $mysql_table_data = "数据表数据";
	public static $mysql_choose_columns = "选用字段";
	public static $mysql_popup_viewdata = "查看数据";
	public static $mysql_tip_chooseradioid = "请先选中一个ID值";
	public static $mysql_con_relation_tables = "条件关系表";

	public static $table_column_class_other = "相关信息";
	public static $form_button_text_admin = "设置";

	public static $operate_default_confirm_msg = "是否确认操作?";
	public static $operate_publish_confirm_msg = "是否确认更新发布?";
	public static $operate_publishAll_confirm_msg = "是否确认重新发布?";

	public static $quikEditMenu_addMain = "添加主菜单";	
	public static $quikEditMenu_edit = "编辑";	
	public static $quikEditMenu_addChild = "加子菜单";	
	public static $quikEditMenu_toLeft = "左移";	
	public static $quikEditMenu_toRight = "右移";	
	public static $quikEditMenu_delete = "删除";	
	public static $quikEditMenu_toUp = "上移";	
	public static $quikEditMenu_toDown = "下移";	
	public static $quikEditMenu_createTtem = "创建tab项";	
}












