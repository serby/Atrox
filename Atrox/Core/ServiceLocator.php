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
class Atrox_Core_ServiceLocator {

	/**
	 * The one instance the ServiceLocator. This could be a sub class.
	 *
	 * @var Atrox_Core_ServiceLocator
	 */
	protected static $instance;

	/**
	 * Don't allow this class to be instantiated.
	 */
	protected function __construct() {
	}

	/**
	 * Disallow cloning
	 */
	final protected function __clone() {
	}

	/**
	 * Returns the one instance of Application
	 *
	 * @return Atrox_Core_ServiceLocator
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new Atrox_Core_ServiceLocator();
		}
		return self::$instance;
	}

	/**
	 * Injects a particualr instance of Atrox_Core_ServiceLocator sub class.
	 *
	 * @param Atrox_Core_ServiceLocator $instance
	 *
	 * @return null
	 */
	public static function load(Atrox_Core_ServiceLocator $instance) {
		self::$instance = $instance;
	}

	/**
	 *
	 */
	public static function getClass() {
		return get_class(self::$instance);
	}

	/**
	 * @return Atrox_Application_Application
	 */
	public static function getApplication() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			Atrox_Application_Application::getInstance();
	}

	/**
	 * @return Atrox_Core_Registry
	 */
	public static function getRegistry() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Registry();
	}

	/**
	 * Return the application wide logger.
	 *
	 * @return Atrox_Core_Logging_Logger
	 */
	public static function getLogger() {
		static $serviceInstance;
		return isset($serviceInstance) ? $serviceInstance :
			new Atrox_Core_Logging_StdOutLogger();
	}

	/**
	 * Return the applications connection.
	 *
	 * @return Atrox_Core_Data_Adaptor_IConnection
	 */
	public static function getConnection() {
		throw new Exception("Connection has not been implemented");
	}

	/**
	 * Returns the defined Data Adaptor
	 *
	 * @return Atrox_Core_Data_Adaptor_IFactory
	 */
	public static function getDataAdaptorFactory($name = 0) {
		throw new Exception("Data Adaptor Factory has not been implemented");
	}

	/**
	 * @return Atrox_Core_Security_Security
	 */
	public static function getSecurity() {
		throw new Exception("Security has not been implemented");
	}

	/**
	 *
	 * @return Atrox_Core_Binary_IStorageAdaptor
	 */
	public static function getStorageAdaptor() {
		throw new Exception("StorageAdaptor has not been implemented");
	}

	/**
	 * @return Atrox_Core_Caching_ICacheManager
	 */
	public static function getCacheManager() {
		throw new Exception("Cache Manager has not been implemented");
	}
}