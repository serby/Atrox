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
class Atrox_Core_Data_Formatter_HtmlOutput implements Atrox_Core_Data_IFormatter {

	public function format($value) {
		return nl2br(htmlentities($value));
	}
}
