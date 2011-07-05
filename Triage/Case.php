<?php

class Triage_Case
{
	protected $_name;
	protected $_callback;
	protected $_result;
	protected $_failmode;
	
	const ERROR_FATAL = 'FATAL';
	const ERROR_ERROR = 'ERROR';
	const ERROR_WARNING = 'WARNING';
	
	public function __construct($name, $callback, $failmode = null) {
		$this->setName($name);
		$this->setCallback($callback);
		$this->setFailMode($failmode);
	}
	
	public function setFailMode($failmode) {
		switch ($failmode) {
			case self::ERROR_FATAL:
				$this->_failmode = self::ERROR_FATAL;
				break;
			case self::ERROR_WARNING:
				$this->_failmode = self::ERROR_WARNING;
				break;
			case self::ERROR_ERROR:
			default:
				$this->_failmode = self::ERROR_ERROR;
				break;
		}
	}
	
	public function getFailMode() {
		return $this->_failmode;
	}
	
	public function setName($name) {
		$this->_name = $name;
		
		return $this;
	}
	
	public function getName() {
		return $this->_name;
	}
	
	public function setCallback($callback) {
		
		if (!is_callable($callback)) {
			require_once 'Triage/Exception.php';
			throw new Triage_Exception('Argument must be a valid callback function');
		}
		
		$this->_callback = $callback;
		
		return $this;
	}
	
	public function getCallback() {
		return $this->_callback;
	}
	
	public function getResult() {
		return $this->_result;
	}
	
	public function run() {
		$callback = $this->getCallback();
		
		$callback_result = $callback();
		
		if (!($callback_result instanceof Triage_Result)) {
			require_once 'Triage/Exception.php';
			throw new Triage_Exception('Callbacks must result a Triage_Result object');
		}
		
		return $this->_result = $callback_result;
	}
}