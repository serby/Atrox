<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 945 $ - $Date: 2009-04-30 20:08:18 +0100 (Thu, 30 Apr 2009) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 945 $ - $Date: 2009-04-30 20:08:18 +0100 (Thu, 30 Apr 2009) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_ResizeAndCropImage implements Atrox_Core_Data_IFormatter {

	protected $width;
	protected $height;

	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}

	public function format($value) {
		if ($value["File"]["TempName"]) {
			Atrox_Core_RichMedia_Image::autoCrop($value["File"]["TempName"], 
				$value["File"]["TempName"], $this->width, $this->height);
		}
		return $value;
	}
}