<?php

class TDRLSigleTable {

	public $table = "";
	public $relations = array();
	
	public function __construct($table) {
		$this->table = $table;	
	}

	public function addRelation($relationObj) {
		$this->relations[] = $relationObj;	
	}
	
	
	
}
