<?php

require_once 'PHPUnit/Framework/TestCase.php';

class Triage_CaseTest extends PHPUnit_Framework_TestCase
{
	protected $_case;
	
	public function setUp() {
		require_once 'Triage/Case.php';
		
		parent::setUp();
	}
	
	public function tearDown() {
		$this->_case = null;
		
		parent::tearDown();
	}
	
	public function testConstructor() {
		$case = $this->_getCase();
		
		$this->assertType('Triage_Case', $case);	
	}
	
	public function testGetName() {
		$name = uniqid();
		$case = $this->_getCase($name);
		
		$this->assertEquals($name, $case->getName());
	}
	
	public function testGetCallback() {
		$callback = create_function('', 'return new Triage_Result(Triage_Result::RESULT_PASS);');
		$case = $this->_getCase(false, $callback);
		
		$this->assertEquals($callback, $case->getCallback());
	}
	
	/**
	 * @expectedException Triage_Exception
	 */
	public function testInvalidCallback() {
		$callback = 'not_valid_callback';
		$case = $this->_getCase(false, $callback);
	}
	
	public function testRunReturnsResult() {
		$case = $this->_getCase();
		
		$result = $case->run();
		
		$this->assertType('Triage_Result', $result);
	}
	
	public function testValidResult() {
		$callback = create_function('', 'require_once "Triage/Result.php"; return new Triage_Result("p");');
		
		$case = $this->_getCase(null, $callback);
		
		$result = $case->run();
		
		$this->assertType('Triage_Result', $result);
		$this->assertEquals(Triage_Result::RESULT_PASS, $result->getResult());
	}
	
	protected function _getCase($name = null, $callback = null) {
		if (!$name) {
			$name = uniqid();
		}
		
		if (!$callback) {
			$callback = create_function('', 'require_once "Triage/Result.php"; return new Triage_Result("p");');
		}
		
		return new Triage_Case($name, $callback);
	}
}