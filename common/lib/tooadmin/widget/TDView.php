<?php
class TDView extends TDWidget{

	public function getViewGroupColumns() {
		return TDField::getViewGroupColumns($this->model,$this->viewModuleId);
	}
}
