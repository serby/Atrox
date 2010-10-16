<?php
/**
 * Error Collector
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1491 $ - $Date: 2010-05-20 19:05:47 +0100 (Thu, 20 May 2010) $
 * @package Core
 */
class Atrox_Core_ErrorCollector {

	/**
	 * @see setCause
	 * @var string
	 */
	protected $cause = null;

	/**
	 * List of error details
	 * @var array
	 */
	protected $errorList = array();

	/**
	 * Creates a clear ErrorControl object.
	 * @return void
	 */
	public function __construct() {
		$this->clear();
	}

	/**
	 * Add error details to the internal list of errors rasied since 'clear' was called.
	 * @param string $value Details of the error
	 * @param string $key What element the error was on
	 * @return void
	 */
	public function add($value, $key = null) {
		$this->hasErrors = true;
		if ($key) {
			$this->errorList[$key] = $value;
		} else {
			$this->errorList[] = $value;
		}
	}

	/**
	 * Clears any errors that may have been set
	 * @return void
	 */
	public function clear() {
		$this->cause = null;
		$this->errorList = array();
		$this->hasErrors = false;
	}

	/**
	 * Reports if there have been any errors since the object was cleared
	 *
	 * @return Boolean True if there have been errors
	 */
	public function count() {
		return count($this->hasErrors);
	}

	/**
	 * Returns an array filled with errors that have been set
	 * and clears the list of errors.
	 *
	 * @return array Error Descriptions
	 */
	public function getErrors($reset = true) {
		$errors = $this->errorList;
		if ($reset) {
			$this->clear();
		}
		return $errors;
	}

	/**
	 * Returns true if there is an error with the given name.
	 * @return Boolean
	 */
	public function isErrorOn($errorName) {
		return isset($this->errorList[$errorName]);
	}


	/**
	 * Returns the last error added to the array
	 *
	 * @return string error
	 */
	public function getLastError() {
		return array_pop($this->errorList);
	}

	/**
	 * Returns the cause of this set of errors
	 *
	 * @return string The cause of the errors
	 */
	public function getCause() {
		return $this->cause;
	}

	/**
	 * Sets the cause of the set of errors
	 * @param string $value The cause of the error
	 * @return void
	 */
	public function setCause($value) {
		$this->cause = $value;
	}
}