<?php
/**
 * Makes a string lower case.
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1500 $ - $Date: 2010-05-27 23:11:53 +0100 (Thu, 27 May 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_Lower implements Atrox_Core_Data_IFormatter {

	/**
	 * Formats the given value.
	 *
	 * @param mixed $value
	 *
	 * @return mixed The formatted value
	 */
	public function format($value) {
		return mb_strtolower($value);
	}
}