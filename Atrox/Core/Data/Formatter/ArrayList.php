<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision$ - $Date$
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision$ - $Date$
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_ArrayList implements Atrox_Core_Data_IFormatter {

	public function __construct($array, $default = null) {
		$this->array = $array;
		$this->default = $default;
	}

	public function format($value) {
		if (isset($this->array[$value])) {
			return $this->array[$value];
		} else {
			if ($this->default) {
				return $this->array[$this->default];
			} else {
				reset($this->array);
				return current($this->array);
			}
		}
	}
}
