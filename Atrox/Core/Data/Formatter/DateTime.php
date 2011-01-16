<?php
/**
 *
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_DateTime implements Atrox_Core_Data_IFormatter {

	protected $dateTimeFormat;
	protected $nullValue;
	protected $application;

	public function __construct($dateTimeFormat, $nullValue = "-",
		$fromTimezone = "UTC", $toTimezone = "UTC") {

		$this->dateTimeFormat = $dateTimeFormat;
		$this->nullValue = $nullValue;

		$this->fromTimezone = $fromTimezone;
		$this->toTimezone = $toTimezone;
	}

	public function format($value) {
		if ($value === null) {
			return $this->nullValue;
		}

		$dateTime = new DateTime($value, new DateTimeZone($this->fromTimezone));
		$dateTime->setTimeZone(new DateTimeZone($this->toTimezone));

		return $dateTime->format($this->dateTimeFormat);
	}
}