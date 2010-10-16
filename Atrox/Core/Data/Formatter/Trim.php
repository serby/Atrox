<?php
/**
 * Removes white space from the beginning and end of the value.
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Formatter_Trim implements Atrox_Core_Data_IFormatter {

	/**
	 * Formats the given value.
	 *
	 * @param mixed $value
	 *
	 * @return mixed The formatted value
	 */
	public function format($value) {
		return trim($value);
	}
}