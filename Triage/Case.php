<?php
/**
 * Case.php
 *
 * PHP version 5.2.*
 *
 * @category Testing
 * @package  Triage
 * @author   Mike Bernat <mike@mikebernat.com>
 * @name     Acuity_Request
 * @license  MIT http://www.opensource.org/licenses/MIT
 * @version  SVN: $Id$
 * @link     https://github.com/mikebernat/Triage
 * @since    .01
 *
 */

/**
 * Case object representing one test.
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
class Triage_Case
{
	protected $name;
	protected $callback;
	protected $result;
	protected $failmode;
	
	const ERROR_FATAL   = 'FATAL';
	const ERROR_ERROR   = 'ERROR';
	const ERROR_WARNING = 'WARNING';
	
	/**
	 * Creates a new test case
	 * 
	 * @param string $name     The name or label of the test case
	 * @param lambda $callback The test itself, in lamba/closure form
	 * @param string $failmode Failure mode
	 */
	public function __construct($name, $callback, $failmode = null) 
	{
		$this->setName($name);
		$this->setCallback($callback);
		$this->setFailMode($failmode);
	}
	
	/**
	 * Sets the fail mode
	 * 
	 * @param string $failmode failure mode
	 * 
	 * @return Triage_Case
	 */
	public function setFailMode($failmode) 
	{
		switch ($failmode) {
		case self::ERROR_FATAL:
			$this->failmode = self::ERROR_FATAL;
			break;
		case self::ERROR_WARNING:
			$this->failmode = self::ERROR_WARNING;
			break;
		case self::ERROR_ERROR:
		default:
			$this->failmode = self::ERROR_ERROR;
			break;
		}
		
		return $this;
	}
	
	/**
	 * Get the failure mode
	 * 
	 * @return string
	 */
	public function getFailMode() 
	{
		return $this->failmode;
	}
	
	/**
	 * Set the case name
	 * 
	 * @param string $name name
	 * 
	 * @return Triage_Case
	 */
	public function setName($name) 
	{
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get the case name
	 * 
	 * @return string
	 */
	public function getName() 
	{
		return $this->name;
	}
	
	/**
	 * Set the callback (test)
	 * 
	 * @param lambda $callback callback
	 * 
	 * @throws Triage_Exception
	 * 
	 * @return Triage_Case
	 */
	public function setCallback($callback) 
	{
		
		if (!is_callable($callback)) {
			include_once 'Triage/Exception.php';
			throw new Triage_Exception('Argument must be a valid callback function');
		}
		
		$this->callback = $callback;
		
		return $this;
	}
	
	/**
	 * Get the callback function
	 * 
	 * @return lamda
	 */
	public function getCallback() 
	{
		return $this->callback;
	}
	
	/**
	 * Get the result
	 * 
	 * @return Triage_Result
	 */
	public function getResult() 
	{
		return $this->result;
	}
	
	/**
	 * Execute the callback function
	 *  
	 * @throws Triage_Exception
	 * 
	 * @return Triage_Result
	 */
	public function run() 
	{
		$callback = $this->getCallback();
		
		$callback_result = $callback();
		
		if (!($callback_result instanceof Triage_Result)) {
			include_once 'Triage/Exception.php';
			throw new Triage_Exception(
				'Callbacks must result a Triage_Result object'
			);
		}
		
		return $this->result = $callback_result;
	}
}