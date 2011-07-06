<?php
/**
 * Suite.php
 *
 * PHP version 5.2.*
 *
 * @category Testing
 * @package  Triage
 * @author   Mike Bernat <mike@mikebernat.com>
 * @name     class_name
 * @license  MIT http://www.opensource.org/licenses/MIT
 * @version  SVN: $Id$
 * @link     https://github.com/mikebernat/Triage
 * @since    .01
 *
 */

define('TRIAGE_RP', dirname(__FILE__));

require_once TRIAGE_RP . DIRECTORY_SEPARATOR . 'Case.php';
require_once TRIAGE_RP . DIRECTORY_SEPARATOR . 'Result.php';
require_once TRIAGE_RP . DIRECTORY_SEPARATOR . 'Exception.php';

/**
 * Class representing a suite of test cases
 *
 * @category  Testing
 * @package   Triage
 * @author    Mike Bernat <mike@mikebernat.com>
 * @copyright 2011 Mike Bernat <mike@mikebernat.com>
 * @license   MIT http://www.opensource.org/licenses/MIT
 * @version   Release: .01
 * @link      https://github.com/mikebernat/Triage
 * @since     .01
 */
class Triage_Suite
{
	
	protected $name;
	protected $cases = array();
	protected $hasrun = false;
	
	/**
	 * Create a new test suite object
	 */
	public function __construct()
	{
		$this->init();
	}
	
	/**
	 * Placeholder method
	 * 
	 * @return void
	 */
	public function init()
	{
		
	}
	
	/**
	 * Add a test case
	 * 
	 * @param Triage_Case $case The case
	 * 
	 * @return Triage_Suite
	 */
	public function addCase(Triage_Case $case) 
	{
		$this->cases[$case->getName()] = $case;
		
		return $this;
	}
	
	/**
	 * Set the name
	 * 
	 * @param string $name name
	 * 
	 * @return Triage_Suite
	 */
	public function setName($name) 
	{
		
		if (!is_string($name)) {
			include_once 'Triage/Exception.php';
			throw new Triage_Exception('Name must be of type string');	
		}
		
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get cases
	 * 
	 * @return array
	 */
	public function getCases() 
	{
		return $this->cases;
	}
	
	/**
	 * Get name
	 * 
	 * @return string
	 */
	public function getName() 
	{
		return $this->name;
	}
	
	/**
	 * Execute the test cases
	 * 
	 * @throws Triage_Exception
	 * 
	 * @return array of cases and their results
	 */
	public function run() 
	{
		if ($this->hasrun) {
			include_once 'Triage/Exception.php';
			throw new Triage_Exception(
				'This test suite has already been run'
			);
		}
		
		$cases = $this->getCases();
		
		if (empty($cases)) {
			include_once 'Triage/Exception.php';
			throw new Triage_Exception('No cases to run');
		}
		
		foreach ($cases as $case) {
			$result = $case->run();
			
			if (!($result instanceof Triage_Result)) {
				include_once 'Triage/Exception.php';
				throw new Triage_Exception(
					'Result of test case not instace of Triage_Result'
				);
			}
			
			if (!$result->getResult() 
				&& ($case->getFailMode() === Triage_Case::ERROR_FATAL)
			) {
				break;
			}
		}
		
		$this->hasrun = true;
		
		return $this->getCases();
	}
	
	/**
	 * Print the results in a readible manner
	 * 
	 * @return string
	 */
	public function __toString() 
	{
		return implode(PHP_EOL, $this->display(false));
	}
	
	/**
	 * Print the results in a readible manner
	 * 
	 * @param bool $print print
	 * 
	 * @throws Triage_Exception
	 * 
	 * @return string
	 */
	public function display($print = true) 
	{
		if (!$this->hasrun) {
			$this->run();
		}
		
		$cases = $this->getCases();
		
		if (empty($cases)) {
			throw new Triage_Exception('No cases to display');
		}
		
		$output = array();
		foreach ($cases as $case) {
			$result   = $case->getResult()->getResult();
			$output[] = sprintf(
				'[%s] %s - %s',
				$case->getResult()->getResult(),
				$case->getName(),
				$case->getResult()->getMessage()
			);
		}
		
		// Padding
		$output[] = '';
		
		if ($print) {
			print implode(PHP_EOL, $output);
		}
		
		return $output;
	}
}