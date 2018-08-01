<?php
class TDThemeDifPart {

	public static function getGridviewButtons() {
		
	}

	public static function removeIcon() { if(TDCommon::isClassics()) { return 'icon icon-red icon-close'; } else { return 'icon-remove'; } }
	public static function OKIcon() { if(TDCommon::isClassics()) { return 'icon icon-color icon-check'; } else { return 'icon-ok'; } }
}
