<?php
/**
 * @package Core
 * @subpackage Data/Validator
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 */

/**
 * Validates that the value is an Integer
 *
 * @author Tom Smith <thomas.smith@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data/Validator
 */
class Atrox_Core_Data_Validator_Integer implements Atrox_Core_Data_IValidator {
	/**
	 *
	 * @see Core/Data/Atrox_Core_Data_IValidator#validate($value)
	 */
	public function validate($value, Atrox_Core_Data_Property $property) {
		return (empty($value) || (is_numeric($value) && ($value == (int)$value))) ? true : "'" . $property->getDescription() . "' must be a whole number [{$value}]";
	}
}