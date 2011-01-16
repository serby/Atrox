<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1547 $ - $Date: 2010-06-20 18:24:48 +0100 (Sun, 20 Jun 2010) $
 */

/**
 * Left crops to the nearest last word with ellipsis
 * @author Elliot Coad (Clock Limited) {@link mailto:elliot.coad@clock.co.uk }
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1547 $ - $Date: 2010-06-20 18:24:48 +0100 (Sun, 20 Jun 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_LeftCropWordEllipsis implements Atrox_Core_Data_IFormatter {
	var $length = 0;
	var $html = false;

	public function __construct($length, $html = false) {
		$this->length = $length;
		$this->html = $html;
	}

	public function format($value) {
		$value = trim(strip_tags($value));

		$string = mb_substr($value, 0, $this->length);
		$key1 = strripos($string, " ");
		$key2 = strripos($string, "&nbsp;");

		if ($key1 >= $key2) {
			$position = $key1;
		} else {
			$position = $key2;
		}

		if (mb_strlen($value) > $this->length) {
			if ($this->html) {
				$string = mb_substr($value, 0, $this->length);
				return mb_substr($string, 0, $position) . "&hellip;";
			} else {
				$string = mb_substr($value, 0, $this->length);
				return mb_substr($string, 0, $position) . "...";
			}
		} else {
			return mb_substr($value, 0, $this->length);
		}
	}
}