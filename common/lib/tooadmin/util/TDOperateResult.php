<?php

class TDOperateResult {

	private $isSuccess = false;

	private $msg = "";

	public  function setResult($pIsSuccess) {
		$this->isSuccess = $pIsSuccess;
	}
	
	public function getIsSuccess() {
		return $this->isSuccess;	
	}

	public function setMsg($pMsg) {
		$this->msg = $pMsg;	
	}

	public function getMsg() {
		return $this->msg;
	}

	public function getResultForAjax() {
		return array("result"=>  $this->isSuccess ? "success" : "fail","msg"=> $this->msg);
	}
}
