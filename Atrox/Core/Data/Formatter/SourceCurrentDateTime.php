<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1193 $ - $Date: 2009-12-23 12:50:18 +0000 (Wed, 23 Dec 2009) $
 */

/**
 *
 * @author Tom Smith <thomas.smith@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1193 $ - $Date: 2009-12-23 12:50:18 +0000 (Wed, 23 Dec 2009) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_SourceCurrentDateTime implements Atrox_Core_Data_IFormatter {

	/**
	 * 
	 * @var Atrox_Core_Data_Source
	 */
	protected $source;

	public function __construct(Atrox_Core_Data_Source $source) {
		$this->source = $source;
	}

	public function format($value) {
		return $this->source->getCurrentDateTime();
	}
}
