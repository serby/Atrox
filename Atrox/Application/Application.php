<?php
/**
 * All applications will have an instance of this class
 *
 * @version 1.0
 * @package Core
 * @subpackage Application
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Application_Application {

	/**
	 * Returns the one instance of Application
	 *
	 * @return Atrox_Application_Application
	 */
	public static function getInstance($object = null) {
		static $application;
		if (!isset($application)) {
			if ($object) {
				$application = $object;
			} else {
				$application = new Atrox_Application_Application();
			}
		}
		return $application;
	}

	/**
	 *
	 */
	protected function __construct() {
		// Make the server work in UTC
		putenv("TZ=UTC");
	}

	/**
	 * Registers the autoload so classes can be instantated without including them.
	 *
	 * @return null
	 */
	public static function registerAutoload() {
		spl_autoload_register("Atrox_Application_Application::autoload");
	}

	/**
	 * Turns on the custom exception handler
	 *
	 * @return null
	 */
	protected function registerExceptionHandler() {
		set_exception_handler(array($this, "customExceptionHandler"));
		set_error_handler(array($this, "customErrorHandler"));
	}

	/**
	 * Turns on the custom exception handler
	 *
	 * @return null
	 */
	public function unregisterExceptionHandler() {
		restore_exception_handler();
		restore_error_handler();
	}

	/**
	 *
	 * @param $className
	 */
	protected static function autoload($className) {
		require str_replace("_", "/", $className) . ".php";
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @param string $name
	 * @return Atrox_Application_Application
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Error handling function.
	 *
	 * @param int $errorNumber
	 * @param string $errorMessage
	 * @param string $file
	 * @param int $lineNumber
	 * @param array $variables
	 */
	protected function errorHandler($errorNumber, $errorMessage, $file, $lineNumber, $context) {
    throw new Atrox_Core_Exception_ErrorException($errorMessage, 0, $errorNumber, $file, $lineNumber, $context);
	}

	/**
	 *
	 * @param Exception $exception
	 */
	protected function exceptionHandler(Exception $exception) {

		$logger = Atrox_Core_ServiceLocator::getInstance()->getLogger();
		$logger->log($exception, "Exception", Atrox_Core_Logging_Logger::ERROR);
		if (method_exists($exception, "getContext")) {
			$logger->log($exception->getContext(), "Exception", Atrox_Core_Logging_Logger::ERROR);
		}
		if (ini_get("display_errors")) {
			@header("Content-type: text/plain");
			echo $exception;
		} else {
			@header("HTTP/1.1 500 Internal Server Error");
			@header("Status: 500 Internal Server Error");
			exit;
		}
		return false;
	}
}