<?php

require_once 'PHPUnit/Framework/TestCase.php';

class Triage_SuiteTest extends PHPUnit_Framework_TestCase
{
	protected $_suite;
	
	public function setUp() {
		require_once 'Triage/Suite.php';
		$this->_suite = new Triage_Suite();
		
		require_once 'Triage/Case.php';
		
		parent::setUp();
	}
	
	public function tearDown() {
		$this->_suite = null;
		parent::tearDown();
	}
	
	public function testConstructor() {
		$this->assertType('Triage_Suite', $this->_suite);
	}
	
	public function testSetName() {
		$name = uniqid();
		$this->assertType('Triage_Suite', $this->_suite->setName($name), 'Triage_Suite::setName() should return instance of Triage_Suite');
		$this->assertEquals($name, $this->_suite->getName(), 'Triage_Suite::getName() should return value previously set in Triage_Suite::setName()');
	}
	
	public function testAddCase() {
		$case = new Triage_Case(uniqid(), create_function('', 'return true;'));
		
		$result = $this->_suite->addCase($case);
		
		$this->assertType('Triage_Suite', $result);
		$this->assertEquals(1, count($this->_suite->getCases()));
	}
	
	public function testRunSuite() {
		$message = uniqid();
		$case = new Triage_Case(uniqid(), create_function('', 'require_once "Triage/Result.php"; return new Triage_Result("p", "' . $message . '");'));
		
		$this->_suite->addCase($case);
		
		$result = $this->_suite->run();
		
		$this->assertTrue(is_array($result), 'The result of Triage_Suite::run() should be an array');
		$this->assertEquals(1, count($result), 'Only one test case was added, only one result should be returned');
		
		$first_result = reset($result);
		
		$this->assertType('Triage_Case', $first_result);
		$this->assertTrue($first_result->getResult()->hasPassed());
		$this->assertEquals($message, $first_result->getResult()->getMessage(), 'Triage_Result message is not what it was set to');
	}
	
	public function testRunSuiteExample() {
		$test_name = 'PHP Version';
		$callback = create_function('', 
			'
				$required_version = "3.0.0";
				if (version_compare(PHP_VERSION, $required_version) >= 0) {
					return new Triage_Result("Pass", "PHP Version meets minimum requirements, current version: " . PHP_VERSION);
				} else {
					return new Triage_Result("Fail", "PHP Version does not meets minimum requirements ($required_version), current version: " . PHP_VERSION);
				}
			'
		);
		
		$case = new Triage_Case($test_name, $callback);
		$this->_suite->addCase($case);
		
		$result = $this->_suite->run();
		
		$first_result = reset($result);
		
		$this->assertTrue($first_result->getResult()->hasPassed());
		$this->assertEquals(Triage_Result::RESULT_PASS, $first_result->getResult()->getResult());
	}
	
	public function testRunSuiteDisplay() {
		$test_name = 'PHP Version';
		$callback = create_function('', 
			'
				$required_version = "3.0.0";
				if (version_compare(PHP_VERSION, $required_version) >= 0) {
					return new Triage_Result("Pass", "PHP Version meets minimum requirements, current version: " . PHP_VERSION);
				} else {
					return new Triage_Result("Fail", "PHP Version does not meets minimum requirements ($required_version), current version: " . PHP_VERSION);
				}
			'
		);
		
		$case = new Triage_Case($test_name, $callback);
		$this->_suite->addCase($case);
		
		$this->_suite->run();
		
		$expected = "[PASS] $test_name - PHP Version meets minimum requirements, current version: " . PHP_VERSION;
		
		$result = $this->_suite->display(false);
		
		$this->assertEquals($expected, $result[0]);
	}
	
	public function testRunSuiteToString() {
		$test_name = 'PHP Version';
		$callback = create_function('', 
			'
				$required_version = "3.0.0";
				if (version_compare(PHP_VERSION, $required_version) >= 0) {
					return new Triage_Result("Pass", "PHP Version meets minimum requirements, current version: " . PHP_VERSION);
				} else {
					return new Triage_Result("Fail", "PHP Version does not meets minimum requirements ($required_version), current version: " . PHP_VERSION);
				}
			'
		);
		
		$case = new Triage_Case($test_name, $callback);
		$this->_suite->addCase($case);
		
		$this->_suite->run();
		
		$expected = "[PASS] $test_name - PHP Version meets minimum requirements, current version: " . PHP_VERSION . PHP_EOL;

		$result = $this->_suite->__toString();
		
		$this->assertEquals($expected, $result);
	}
	
	/**
	 * 
	 * @expectedException Triage_Exception
	 */
	public function testRunWithInvalidResult() {
		$test_name = 'PHP Version';
		$callback = create_function('', 'return true;');
		
		$case = new Triage_Case($test_name, $callback);
		$this->_suite->addCase($case);
		
		$this->_suite->run();
	}
	
	/**
	 * 
	 * @expectedException Triage_Exception
	 */
	public function testRunWithNoCases() {
		$this->_suite->run();
	}
	
	/**
	 * 
	 * @expectedException Triage_Exception
	 */
	public function testSetNameWithBadArg() {
		$this->_suite->setName(array('fail'), 'Triage_Suite::setName() should only allow args of type string');
	}
}