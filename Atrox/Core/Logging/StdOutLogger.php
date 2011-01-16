<?php
/**
 * A central locator for all core application services.
 *
 * This is to be used to inject dependancies. This class will be staticaly references throughout
 * the framework. If you need to overide the core services then sub class and pass in via 'load' in
 * your bootstrap.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Logging
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_Logging_StdOutLogger extends Atrox_Core_Logging_Logger {

	/**
	 * (non-PHPdoc)
	 * @see Application/Atrox/Core/Logging/Atrox_Core_Logging_Logger#log($message, $level)
	 */
	public function log($message, $type = "Default", $level = self::ERROR) {
		if ($level <= $this->level) {
			echo date("Y-m-d H:i:s") . " [{$type}] {$message}\n";
		}
	}
}