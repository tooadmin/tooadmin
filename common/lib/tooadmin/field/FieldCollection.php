<?php
class FieldCollection {

	public static $hiddenFieldClass = "Fie_hidden";

	public static $SEARCH_NOT_ALLOWED = array(
		'file',	'pid','order'
	);

	public static $SEARCH_EQ_ONLY = array(
		'select','selectdb','radio','bool',	'createuser','updateuser','dbtype',		
	);

	public static $SEARCH_AROUND = array(
		'date', 'datetime', 'createtime', 'updatetime',	
	);

	public static $SEARCH_INCLUDE_ONLY = array(
		'checkbox','checkboxdb','foreignkey'	
	);

}
