<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 945 $ - $Date: 2009-04-30 20:08:18 +0100 (Thu, 30 Apr 2009) $
 */

/**
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 945 $ - $Date: 2009-04-30 20:08:18 +0100 (Thu, 30 Apr 2009) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_BooleanToInteger implements Atrox_Core_Data_IFormatter {

	public function format($value) {
		return $value ? 1 : 0;
	}
}