<?php
/**
 * @package Core
 * @subpackage Html/Component
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1339 $ - $Date: 2010-02-27 20:49:35 +0000 (Sat, 27 Feb 2010) $
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1339 $ - $Date: 2010-02-27 20:49:35 +0000 (Sat, 27 Feb 2010) $
 * @package Core
 * @subpackage Html/Component
 */
class Atrox_Core_Html_Widget_OptionList implements Atrox_Core_Html_IWidget {
 	/**
 	 * (non-PHPdoc)
 	 * @see Atrox/Core/Html/Atrox_Core_Html_IWidget#make()
 	 */
	static function make($default, $options = null) {
		$defaultOptions = new stdClass();
		$defaultOptions->data = array();
		$defaultOptions->default = "";
		$defaultOptions->useValues = true;
		$options = Atrox_Core_Utility_Object::override($defaultOptions, $options);
		$returnValue = "";
		if ($options->useValues) {
			foreach ($options->data as $value => $text) {
				$returnValue .= "<option" . (($options->default == $value) ? " selected=\"selected\"" : "")
					. " value=\"$value\">$text</option>\n";
			}
		} else {
			foreach ($options->data as $text) {
				$returnValue .= "<option" . (($options->default == $text) ? " selected=\"selected\"" : "")
					. ">$text</option>\n";
			}
		}
		return $returnValue;
	}
}