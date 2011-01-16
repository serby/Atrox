<?php
/**
 * String Property
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Property_String extends Atrox_Core_Data_Property {

	public function  __construct($name, $description = null) {
		parent::__construct($name, $description);
		$this->setInputFormatter(new Atrox_Core_Data_Formatter_Trim());
	}
}