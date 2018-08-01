<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TDTest_Mmodel
 *
 * @author Administrator
 */
class TDTest_Mmodel extends TDCommonModel {
	//put your code here
	public function __construct($scenario="insert") {
		$this->tableName = "too_user";
		parent::__construct($scenario);
	}
	public static function getClassName() { return __CLASS__; }
}
