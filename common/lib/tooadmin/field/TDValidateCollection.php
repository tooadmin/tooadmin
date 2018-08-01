<?php
class TDValidateCollection {
	
	public static function isDatetime($data) {
		$time = strtotime($data);
		return !empty($time);
	}
}
