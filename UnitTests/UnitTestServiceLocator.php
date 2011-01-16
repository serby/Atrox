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
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * A central locator for all core application services.
 *
 * This is to be used to inject dependancies. This class will be staticaly references throughout
 * the framework. If you need to overide the core services then sub class and pass in via 'load' in
 * your bootstrap.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class UnitTestServiceLocator extends Atrox_Core_ServiceLocator {

	/**
	 * Returns the one instance of this Service Locator
	 *
	 * @return Atrox_Core_ServiceLocator
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new UnitTestServiceLocator();
		}
		return self::$instance;
	}

	/**
	 * @return Atrox_Core_Caching_ICacheManager
	 */
	public static function getCacheManager() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Caching_Memcached();
	}

	/**
	 * @return Atrox_Core_Security_Security
	 */
	public static function getSecurity() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Security_Security();
	}

	/**
	 *
	 * @return Atrox_Core_Binary_IStorageAdaptor
	 */
	public static function getStorageAdaptor() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Binary_StorageAdaptor_Disk();
	}

	public static function getConnection() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Data_PostgreSql_LoggingConnection(
			"host=furnace port=5432 dbname=Atrox user=WebUser password=test");
	}

}