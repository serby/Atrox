<?php
/**
 * Validator Interface
 *
 * @version 5.1
 * @package Atrox
 * @subpackage Core
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
interface Atrox_Core_Data_IValidator {

	/**
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value, Atrox_Core_Data_Property $property);
}