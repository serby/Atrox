<?php
/**
 * @package Core
 * @subpackage Html/Component
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

/**
 * Create a textbox
 *
 * @author Tom Smith <thomas.smith@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Html/Component
 */
class Atrox_Core_Html_Widget_TextBox implements Atrox_Core_Html_IWidget {
	
 	/**
 	 * (non-PHPdoc)
 	 * @see Atrox/Core/Html/Atrox_Core_Html_IWidget#make()
 	 */
	static function make($default, $options = null) {

		$defaultOptions = new stdClass();
		$defaultOptions->class = "";
		$defaultOptions->disable = false;
		$defaultOptions->tabIndex = null;
		
		if ($options != null) {
			if (!is_object($options)) {
				throw new InvalidArgumentException("Options must be an object");
			}
			$options = Atrox_Core_Utility_Object::override($defaultOptions,	$options);
		}

		return "<input type=\"text\" class=\"{$defaultOptions->class}\" value=\"{$default}\" tabindex=\"{$defaultOptions->tabIndex}\" />";		
	}
}