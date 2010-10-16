<?php
/**
 * Allows multiple Formatters to be queued together.
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1500 $ - $Date: 2010-05-27 23:11:53 +0100 (Thu, 27 May 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_Queue implements Atrox_Core_Data_IFormatter {

	/**
	 * List of formatters.
	 *
	 * @var array
	 */
	protected $formatters;

	/**
	 * Adds a formatter to the queue and returns $this;
	 *
	 * @param Atrox_Core_Data_IFormatter $formatter
	 *
	 * @return Atrox_Core_Data_Formatter_Queue
	 */
	public function add(Atrox_Core_Data_IFormatter $formatter) {
		$this->formatters[] = $formatter;
		return $this;
	}

	/**
	 * Clears the formatter queue.
	 */
	public function clear() {
		unset($this->formatters);
		$this->formatters = array();
	}

	/**
	 * Formats the given value.
	 *
	 * @param mixed $value
	 *
	 * @return mixed The formatted value
	 */
	public function format($value) {
		$returnValue = clone($value);
		foreach ($fthis->formatters as $formatter) {
			$returnValue = $formatter->format($returnValue);
		}
		return $returnValue;
	}
}