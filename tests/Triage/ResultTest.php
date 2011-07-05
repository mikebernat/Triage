<?php

require_once 'PHPUnit/Framework/TestCase.php';

class Triage_ResultTest extends PHPUnit_Framework_TestCase
{
	protected $_case;
	
	public function setUp() {
		require_once 'Triage/Result.php';
		
		parent::setUp();
	}
	
	public function tearDown() {
		$this->_case = null;
		
		parent::tearDown();
	}
	
	public function testConstructor() {
		$result = new Triage_Result('p');
		
		$this->assertType('Triage_Result', $result);	
	}
	
	public function testConstructorResultArgument() {
		
		$tests = array(
			'TRUE' => array(
				Triage_Result::RESULT_PASS,
				'PASS',
				TRUE,
				1,
				'p',
			),
			'FALSE' => array (
				Triage_Result::RESULT_ERROR,
				Triage_Result::RESULT_FAIL,
				Triage_Result::RESULT_WARNING,
				'FAIL',
				'f',
				FALSE,
				0,
			),
		);
		
		foreach($tests as $expected_result => $arguments) {
			foreach($arguments as $argument) {
				$result = new Triage_Result($argument);
				
				$this->assertType('Triage_Result', $result);
				
				if ($expected_result === 'TRUE') {
					$this->assertTrue($result->hasPassed(), 'Triage_Result::hasPassed() should return true when argument ' . var_export($argument, true) . ' passed to constructor. Return value: ' . var_export($result->hasPassed(), true));
				} else {
					$this->assertTrue($result->hasFailed(), 'Triage_Result::hasFailed() should return true when argument ' . var_export($argument, true) . ' passed to constructor. Return value: ' . var_export($result->hasPassed(), true));
				}
			}
		}
	}
	
	public function testConstructorMessageArgument() {
		$message = uniqid();
		$result = new Triage_Result(true, $message);
		
		$this->assertEquals($message, $result->getMessage());
	}
}