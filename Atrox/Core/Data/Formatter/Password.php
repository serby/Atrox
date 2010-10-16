<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_Password implements Atrox_Core_Data_IFormatter {

	//TODO: It's not possible to change salt.
	const salt = "Atrox";

		public function format($value) {
		if (empty($value)) {
			return false;
		} else {
			return sha1($value . self::salt);
		}
	}
}