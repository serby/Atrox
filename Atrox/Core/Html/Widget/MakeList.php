<?php
/**
 * @package Core
 * @subpackage Html/Component
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

/**
 * Construct a string containing list items from the passed in array. For a list
 * with different values i.e. value is different from output uses a multidimesional array.
 *
 * @author Tom Smith <thomas.smith@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Html/Component
 */
class Atrox_Core_Html_Widget_MakeList implements Atrox_Core_Html_IWidget {
 	/**
 	 * (non-PHPdoc)
 	 * @see Atrox/Core/Html/Atrox_Core_Html_IWidget#make()
 	 */
	static function make($default, $options = null) {

		$defaultOptions = new stdClass();
		$defaultOptions->list = array();
		$defaultOptions->compareElement = 0;
		$defaultOptions->pattern = "%s";
		$defaultOptions->class = "active-item";

		if ($options != null) {
			if (!is_object($options)) {
				throw new InvalidArgumentException("Options must be an object");
			}
			$options = Atrox_Core_Utility_Object::override($defaultOptions,	$options);
		}
		
		$newList = "";
		if ((isset($options->list[0])) && (is_array($options->list[0]))) {
			foreach ($options->list as $value) {
				if ((array_key_exists($options->compareElement, $value)) && $value[$options->compareElement] == $options->selected) {
					$newList .= "<li class=\"{$options->class}\">" . vsprintf($options->pattern, $value) . "</li>";
				} else {
					$newList .= "<li>" . vsprintf($options->pattern, $value) . "</li>";
				}
			}
		} else {
			foreach ($options->list as $value) {
				if ($value == $default) {
					$newList .= "<li class=\"{$options->class}\">" . $value . "</li>";
				} else {
					$newList .= "<li>" . $value . "</li>";
				}
			}
		}
		return "$newList";
	}
}