<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_Boolean implements Atrox_Core_Data_IFormatter {

	/**
	 *
	 * @var string
	 */
	protected $trueValue;

	/**
	 *
	 * @var string
	 */
	protected $falseValue;

	public function __construct($trueValue = "Yes", $falseValue = "No") {
		$this->trueValue = $trueValue;
		$this->falseValue = $falseValue;
	}

	public function format($value) {
		return (($value === true) || ($value == "t")) ? $this->trueValue : $this->falseValue;
	}
}