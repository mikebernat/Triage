<?php

class Triage_Result
{
	protected $_result;
	protected $_message;
	
	const RESULT_PASS = 'PASS';
	const RESULT_FAIL = 'FAIL';
	const RESULT_ERROR = 'ERROR';
	const RESULT_WARNING = 'WARNING';
	
	public function __construct($result, $message = null) {
		$this->setResult($result);
		$this->setMessage($message);
	}
	
	public function setMessage($message) {
		$this->_message = $message;
		
		return $this;
	}
	
	public function getMessage() {
		return $this->_message;
	}
	
	public function setResult($result) {		
		$this->_result = $result;
		
		return $this;
	}
	
	public function getResult() {
		$result =  $this->_result;
		
		if ($result === self::RESULT_PASS) {
			return self::RESULT_PASS;
		}
		
		if ($result === TRUE) {
			return self::RESULT_PASS;
		}
		
		if (strtoupper($result) === 'PASS') {
			return self::RESULT_PASS;
		}
		
		if (strtoupper($result) === 'P') {
			return self::RESULT_PASS;
		}
		
		if ($result === 1) {
			return self::RESULT_PASS;
		}
		
		if ($result === '1') {
			return self::RESULT_PASS;
		}
		
		if ($result === self::RESULT_FAIL) {
			return self::RESULT_FAIL;
		}
		
		if ($result === self::RESULT_ERROR) {
			return self::RESULT_ERROR;
		}
		
		if ($result === self::RESULT_WARNING) {
			return self::RESULT_WARNING;
		}
		
		if ($result === 0) {
			return self::RESULT_FAIL;
		}
		
		if ($result === '0') {
			return self::RESULT_FAIL;
		}
		
		if ($result === FALSE) {
			return self::RESULT_FAIL;
		}
		
		if ($result === 'FAIL') {
			return self::RESULT_FAIL;
		}
		
		if (strtoupper($result) === 'F') {
			return self::RESULT_FAIL;
		}
		
		return $result;
	}
	
	protected function __toBool() { 
		$result = $this->getResult();
		
		if ($result === self::RESULT_PASS) {
			return true;
		}
		
		if ($result === self::RESULT_FAIL) {
			return false;
		}
		
		if ($result === self::RESULT_ERROR) {
			return false;
		}
		
		if ($result === self::RESULT_WARNING) {
			return false;
		}
		
		require_once 'Triage/Exception.php';
		throw new Triage_Exception('Result value not recognized: ' . var_export($result, true));
	}
	
	public function hasPassed() {
		return $this->__toBool();
	}
	
	public function hasFailed() {
		return !$this->__toBool();
	}
}