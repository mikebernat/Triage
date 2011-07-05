<?php

class Triage_Suite
{
	
	protected $_name;
	protected $_cases = array();
	
	protected $_hasrun = false;
	
	
	public function addCase(Triage_Case $case) {
		$this->_cases[$case->getName()] = $case;
		
		return $this;
	}
	
	/**
	 * Sets name
	 * @param string $name
	 * 
	 * @return Triage_Suite
	 */
	public function setName($name) {
		
		if (!is_string($name)) {
			require_once 'Triage/Exception.php';
			throw new Triage_Exception('Name must be of type string');	
		}
		
		$this->_name = $name;
		
		return $this;
	}
	
	public function getCases() {
		return $this->_cases;
	}
	
	/**
	 * Get name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}
	
	public function run() {
		
		if ($this->_hasrun) {
			throw new Triage_Exception('This test suite has already been run', $code);
		}
		
		$cases = $this->getCases();
		
		if (empty($cases)) {
			require_once 'Triage/Exception.php';
			throw new Triage_Exception('No cases to run');
		}
		
		foreach($cases as $case) {
			$result = $case->run();
			
			if (!($result instanceof Triage_Result)) {
				require_once 'Triage/Exception.php';
				throw new Triage_Exception('Result of test case not instace of Triage_Result');
			}
			
			if (!$result->getResult() && $case->getFailMode() === Triage_Case::ERROR_FATAL) {
				break;
			}
		}
		
		$this->_hasrun = true;
		
		return $this->getCases();
	}
	
	public function __toString() {
		return $this->display();
	}
	
	public function display($print = true) {
		if (!$this->_hasrun) {
			$this->run();
		}
		
		$cases = $this->getCases();
		
		if (empty($cases)) {
			throw new Triage_Exception('No cases to display');
		}
		
		$output = array();
		foreach($cases as $case) {
			$result = $case->getResult()->getResult();
			$output [] = sprintf(
				'[%s] %s - %s',
				 $case->getResult()->getResult(),
				 $case->getName(),
				 $case->getResult()->getMessage()
			);
		}
		
		if ($print) {
			print implode(PHP_EOL, $output);
		}
		
		return $output;
	}
}