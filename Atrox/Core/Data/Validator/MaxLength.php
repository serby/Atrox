<?php
/**
 * @package Core
 * @subpackage Data/Validator
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 */

/**
 * Validates that strings are beyond a maximum length
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data/Validator
 */
class Atrox_Core_Data_Validator_MaxLength implements Atrox_Core_Data_IValidator {

	/**
	 *
	 * @var int
	 */
	protected $maximumLength;

	/**
	 * @param int $maxLength
	 */
	public function __construct($maximumLength) {
		$this->maximumLength = $maximumLength;
	}

	/**
	 *
	 * @see Core/Data/Atrox_Core_Data_IValidator#validate($value)
	 */
	public function validate($value, Atrox_Core_Data_Property $property) {
		return (strlen($value) > $this->maximumLength) ? "'" . $property->getDescription() .
			"' must not exceed $this->maximumLength charaters" : true;
	}

	/**
	 *
	 * @return int
	 */
	public function getMaximumLength() {
		return $this->maximumLength;
	}
}