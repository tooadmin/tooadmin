<?php

class TDPrimaryKey {
		
		public static $PRIMARY_KEY_OPERATE_CBUTTONCLUMN = 1;
		public static $PRIMARY_KEY_OPERATE_EMPTY_URLSTR = 2;
		public static $PRIMARY_KEY_OPERATE_GET_URL_VALUE = 3;
		public static $PRIMARY_KEY_OPERATE_GET_URL_ARRAY = 4;
		public static function getPrimaryKeyData($primaryKeyOperate,$params) {
			$result = null;
			switch ($primaryKeyOperate) {
				case self::$PRIMARY_KEY_OPERATE_CBUTTONCLUMN:
					$tableName = $params;
					$table = TDTable::getTableObj($tableName);
					$result = '';
					if(is_array($table->primaryKey)) {
						foreach($table->primaryKey as $key) {
							//$result .= '/id['.$key.']/$data->'.$key;
							$result .= '/'.$key.'/$data->'.$key;
						}	
					} else {
						$result .= '/'.$table->primaryKey.'/$data->'.$table->primaryKey;
					}
					break;	
				case self::$PRIMARY_KEY_OPERATE_EMPTY_URLSTR:
					$tableName = $params;
					$table = TDTable::getTableObj($tableName);
					$result = '';
					if(is_array($table->primaryKey)) {
						foreach($table->primaryKey as $key) {
							$result .= '/'.$key.'/0';
						}	
					} else {
						$result .= '/'.$table->primaryKey.'/0';
					}
					break;	
				case self::$PRIMARY_KEY_OPERATE_GET_URL_VALUE:
					$tableName = $params;
					$table = TDTable::getTableObj($tableName);
					$result = array();
					if(is_array($table->primaryKey)) {
						foreach($table->primaryKey as $key) {
							$pKeyV = TDRequestData::getGetData($key);
							$result = !empty($pKeyV) ? TDCommon::array_smerge($result,array($key=>$pKeyV)) : $result;
						}	
					} else {
						$result = TDRequestData::getGetData($table->primaryKey,0);
					}
					break;
				case self::$PRIMARY_KEY_OPERATE_GET_URL_ARRAY:
					$tableName = $params;
					$table = TDTable::getTableObj($tableName);
					$result = array();
					if(is_array($table->primaryKey)) {
						foreach($table->primaryKey as $key) {
							$result = TDCommon::array_smerge($result,array($key=>TDRequestData::getGetData($key)));
						}	
					} else {
						$result = array($table->primaryKey=> TDRequestData::getGetData($table->primaryKey,0));
					}
					break;
				default:
			}
			return $result;
		}

}