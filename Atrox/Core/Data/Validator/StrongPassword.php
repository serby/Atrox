<?php
/**
 * @package Core
 * @subpackage Data/Validator
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 */

/**
 * Validates that passwords are strong
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data/Validator
 */
class Atrox_Core_Data_Validator_StrongPassword implements Atrox_Core_Data_IValidator {

	/**
	 *
	 * @var int
	 */
	protected $minimumLength;

	/**
	 * @param $minimumLength The length to validate password against
	 */
	public function __construct($minimumLength = 6) {
		$this->minimumLength = $minimumLength;
	}

	/**
	 * Validate $password making sure it is Strong
	 * A strong password is any password greater than $minLength which has at
	 * least 1 alphabet character and one number or symbol in it
	 * @see Core/Data/Atrox_Core_Data_IValidator#validate($value)
	 * @param string $password The value to validate
	 * @return bool True if $value is a strong password
	 */

	public function validate($value, Atrox_Core_Data_Property $property) {
		$password = trim($value);
		$length = mb_strlen($value);

		if ($length < $this->minimumLength) {
			return false;
		}

		$charFound = false;
		$digitFound = false;

		for ($i = 0; $i < $length; $i++) {
			$c = ord(mb_substr($value, $i, 1));
			if (($c >= 65) && ($c <= 90) ||
				($c >= 97) && ($c <= 122)) {
				$charFound = true;
			}

			if (($c >= 48) && ($c <= 57)) {
				$digitFound = true;
			}

			if ($digitFound && $charFound) {
				return "'" . $property->getDescription() . "' must be a mixture of numbers and letters";
			}
		}
		return true;
	}
}