<?php

class TDEvent_Website {

	public static function afterSave() { 
		TDDataFiles_Website::createWebSiteParams(); 
	} 

}
