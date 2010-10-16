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
class Atrox_Core_Data_Formatter_SanitiseHtml implements Atrox_Core_Data_IFormatter {

	/**
	 * Tags allowed in the string
	 * @var string
	 */
	protected $allowedTags;

	public function __construct($allowedTags = "<strong><img><a>") {
		$this->allowedTags = $allowedTags;
	}

	public function format($value) {
		//TODO: This need to be more secure
		return strip_tags($value, $this->allowedTags);
	}
}