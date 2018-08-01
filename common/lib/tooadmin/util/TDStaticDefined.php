<?php
class TDStaticDefined {
	
	public static $formFieldColumnBelongToOrder = '__';
	public static $formFieldID = 'cid';
	public static $formFieldName = 'cname';
	public static $formModelName = 'Common';
	public static $foreignKey_tableName = '___';
	public static $childModels = 'childmds__';// 

	public static $pageLayoutType = "pageLayoutType";
	public static $pageLayoutType_single = "sigle";
	public static $pageLayoutType_alone = "alone";
	public static $pageLayoutType_inner = "inner";
	public static $pageLayoutType_common = "common";
	
	public static $mysqlCommonMudelTabId = "mysqlCommonMuduleTabId";
	public static $mysqlTableColumnsStr= "mysqlColumnsStr";
	public static $mysqlDataDispalyType = "mysqlDataDisplayType";
	public static $mysqlDataDispalyType_org = "org";
	public static $mysqlDataDispalyType_format = "format";

	public static $viewChildTableDatasFromTbId = 'viewChildTableDatasFromTbId';
	public static $viewChildTableDatasFromPkId = 'viewChildTableDatasFromPkId';
	
	public static $popupSearchColumnIdStr = 'popupSearch_ColumnId';
	public static $popupSearchForeignFieldId = 'popupSearch_ForeignFiledId';
	public static $popupSearchFormPrimaryKey = "popupSearch_FormPrimaryKey";
	public static $popupSearchUniqueColumnIdsStr = "popupSearch_UniqueColumnIdsStr";
	public static $popupSearchUniqueColumnIdsValue = "popupSearch_UniqueColumnIdsValue";
	public static $popupControlModuleId= "popupControl_ModuleId";
	public static $popupControlExpandFun = "popupControl_ExpandFun";
	
	public static $OPERATE_TYPE_KEY = 'operateType';
	public static $OPERATE_TYPE_POPUP_SEARCH = 'popup_saerch';
	public static $OPERATE_TYPE_POPUP_LADDER_COLUMN = 'popupLadderColumn';
	public static $OPERATE_TYPE_POPUP_CHOOSE_BUTTON_ONCLICK = 'chooseButtonOnClick';
	public static $OPERATE_TYPE_POPUP_CONTROL_CHOOSE = 'pupup_control_choose';
	public static $OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE = 'pupup_control_choose_type';
	public static $OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE_ONE = 'one';
	public static $OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE_MORE = 'more';
	public static $OPERATE_TYPE_POPUP_CONTROL_CHOOSE_PARAM = 'pupup_control_choose_param';

	public static $OPERATE_TYPE_POPUP_CONTROL_EDIT = 'pupup_control_edit';

	public static $PARAM_AFTER_CLOSE_FORM_TREE_JS ="AFTER_CLOSE_FORM_TREE_JS"; 

	public static $PARAM_MODULE_FORM_MODULE_ID ="MODULE_FORM_MODULE_ID"; 
	public static $PARAM_MODULE_ROW_PKID ="MODULE_ROW_PKID"; 
	public static $PARAM_MODULE_READONLY ="MODULE_READONLY"; 

	public static $PARAM_EXPFUN_CLASS = "PARAM_EXPFUN_CLASS";
	public static $PARAM_EXPFUN_NAME = "PARAM_FUN_NAME";
	public static $PARAM_EXPFUN_PR1 = "PARAM_EXPFUN_PR1";
	public static $PARAM_EXPFUN_PR2 = "PARAM_EXPFUN_PR2";
	public static $PARAM_EXPFUN_PR3 = "PARAM_EXPFUN_PR3";

	
	public static $ladderColumn_FieldColumnId = 'ladderFieldColumnId';
	public static $ladderColumn_FieldTextId = 'ladderFieldTextId';
	public static $url_condition_str = "url_con_str";
	
	public static $moduleManageId =  11;//模块管理的模块ID
	public static $devMenuModelId =  10;//编辑菜单模块ID
	public static $gridviewColumnsModuleId = 38;
	public static $editColumnsModuleId = 40;
	public static $moduleCopyModuleId = 68;
	public static $mysqlCommonModuleId = 480;//在myql中管理数据用的通用模块ID
	public static $column_input_id_file = "11";
	public static $choose_columns_moduleId = 36;//用于选择字段的模块ID
	public static $tableManageModuleId = 26;//数据表结构管理的模块ID
	public static $formInnerGridviewIndexId = 9999999880;
	
	private static $tmpController = null;
	public static function getTmpController() {
		if(is_null(self::$tmpController)) {
			self::$tmpController = new CController('');
		}
		return self::$tmpController;
	}
}
