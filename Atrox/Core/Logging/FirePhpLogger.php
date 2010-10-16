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
require_once("FirePHPCore/FirePHP.class.php");

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
class Atrox_Core_Logging_FirePhpLogger extends Atrox_Core_Logging_Logger {

	/**
	 * (non-PHPdoc)
	 * @see Application/Atrox/Core/Logging/Atrox_Core_Logging_Logger#log($message, $level)
	 */
	public function log($message, $type, $level = self::ERROR) {
		require_once("FirePHPCore/FirePHP.class.php");
		if ($level <= $this->level) {
			$firephp = FirePHP::getInstance(true);
			switch ($level) {
				case self::EMERGENCY:
				case self::ALERT:
				case self::ERROR:
					$firephp->error($message, $type);
					break;
				case self::WARNING:
					$firephp->warn($message, $type);
					break;
				case self::INFORMATION:
					$firephp->info($message);
					break;
				default:
					$firephp->log($message, $type);
			}
		}
	}
}