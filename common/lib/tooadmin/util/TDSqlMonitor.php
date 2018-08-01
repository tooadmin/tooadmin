<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TDSqlMonitor
 *
 * @author ThinkPad User
 */
class TDSqlMonitor {

	public static function commonSQL($sql,$tableName='') {
		return;
		$file = "common/sqllog.txt";
		$fp = fopen($file,"a");
		fwrite($fp,$sql."\n");
		fclose($fp);	
	}
}