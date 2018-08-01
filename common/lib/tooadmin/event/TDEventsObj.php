<?php
class TDEventsObj {
	
	private $excuteSQLArray = array();
	private $eventsObjArray = array();	
	private $relationModel = array();
	
	public function addExcuteSQL($sql) { if(!empty($sql)) $this->excuteSQLArray[] = $sql;	}
	public function getExcuteSQLArray() { return $this->excuteSQLArray; }

	public function addEventsObj($eventsObj) { if($eventsObj instanceof TDEventsObj) $this->eventsObjArray[] = $eventsObj;	}
	public function getEventsObjArray() { return $this->eventsObjArray; }

	public function addRalationModel($model) { if($model instanceof CActiveRecord) { $this->relationModel[] = $model; } }
	public function getRalationModelArray() { return $this->relationModel; }

}
