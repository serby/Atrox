<?php
/**
 * Abstact class interface for all loggers
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1298 $ - $Date: 2010-02-13 09:17:35 +0000 (Sat, 13 Feb 2010) $
 * @package Core
 * @subpackage Logging
 */
abstract class Atrox_Core_Logging_Logger {

	const EMERGENCY = 0;
	const ALERT = 1;
	const ERROR = 2;
	const WARNING = 3;
	const INFORMATION = 4;
	const DEBUG = 5;

	/**
	 * The current logging level. Any message with a level higher then this won't been logged
	 * @var integer
	 */
	protected $level = self::ERROR;

	/**
	 *
	 * @param int $logLevel
	 *
	 * @return null
	 */
	public function __construct($logLevel = self::ERROR) {
		$this->level = $logLevel;
	}

	/**
	 *
	 * @param mixed $message
	 * @param string $type
	 * @param int $level
	 *
	 * @return null
	 */
	abstract public function log($message, $type = "Default", $level = self::ERROR);
}