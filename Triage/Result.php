<?php
/**
 * Result.php
 *
 * PHP version 5.2.*
 *
 * @category Testing
 * @package  Triage
 * @author   Mike Bernat <mike@mikebernat.com>
 * @name     Triage_Result
 * @license  MIT http://www.opensource.org/licenses/MIT
 * @version  SVN: $Id$
 * @link     https://github.com/mikebernat/Triage
 * @since    .01
 *
 */

/**
 * Result of a Case
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
class Triage_Result
{
	protected $result;
	protected $message;
	
	const RESULT_PASS = 'PASS';
	const RESULT_FAIL = 'FAIL';
	const RESULT_ERROR = 'ERROR';
	const RESULT_WARNING = 'WARNING';
	
	/**
	 * Creates a case result object
	 * 
	 * @param string $result  Result of the test. See self::RESULT* constants
	 * @param string $message Message to pass along with the result
	 */
	public function __construct($result, $message = null) 
	{
		$this->setResult($result);
		$this->setMessage($message);
	}
	
	/**
	 * Set the message
	 * 
	 * @param string $message message
	 * 
	 * @return Triage_Result
	 */
	public function setMessage($message) 
	{
		$this->message = $message;
		
		return $this;
	}
	
	/**
	 * Get the message
	 * 
	 * @return string
	 */
	public function getMessage() 
	{
		return $this->message;
	}
	
	/**
	 * Set the result
	 * 
	 * @param string $result result
	 * 
	 * @return Triage_Result
	 */
	public function setResult($result) 
	{		
		$this->result = $result;
		
		return $this;
	}
	
	/**
	 * Get the result
	 * 
	 * @return string
	 */
	public function getResult() 
	{
		$result =  $this->result;
		
		if ($result === self::RESULT_PASS) {
			return self::RESULT_PASS;
		}
		
		if ($result === true) {
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
		
		if ($result === false) {
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
	
	/**
	 * Returns a boolean result
	 * 
	 * @throws Triage_Exception
	 * 
	 * @return bool
	 */
	protected function toBool() 
	{ 
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
		
		include_once 'Triage/Exception.php';
		throw new Triage_Exception(
			'Result value not recognized: ' . var_export($result, true)
		);
	}
	
	/**
	 * Determine if the result was success
	 * 
	 * @return bool
	 */
	public function hasPassed() 
	{
		return $this->toBool();
	}
	
	/**
	 * Determine is the result was a failure
	 * 
	 * @return bool
	 */
	public function hasFailed() 
	{
		return !$this->toBool();
	}
}