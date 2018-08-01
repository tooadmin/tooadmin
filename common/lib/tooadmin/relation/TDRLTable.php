<?php

class TDRLTable {

	public static $TYPE_ONE_TO_ONE = 1;
	public static $TYPE_ONE_TO_MORE = 2;
	public static $TYPE_CUSTOME = 3;
	public 	$cur_type = "";

	public $tableA = "";
	public $tableB = "";
	public $tableA_PK = "";
	public $tableB_PK = "";
	
	public function __construct($tbA,$tbB,$type) {
		$this->tableA = $tbA;
		$this->tableB = $tbB;
		$this->cur_type = $type;	
	}

	public function setPrimaryKeyColumn($tbA_PK,$tbB_PK) {
		$this->tableA_PK = $tbA_PK;
		$this->tableB_PK = $tbB_PK;
	} 
}
