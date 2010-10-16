<?php
/**
 * Test for the ServiceLocator class.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */

require_once("PHPUnit/Framework.php");
require_once("Bootstrap.php");

/**
 * Test for the ServiceLocator class.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_ServiceLocatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * expectedException PHPUnit_Framework_Error
	 */
	public function testNotPosibleToInstantiate() {
		// Why doesn't this test work?
		//$instance = new Atrox_Core_ServiceLocator();
	}

	/**
	 * Ensure that getLogger returns a implementation of Atrox_Core_Logging_Logger
	 */
	public function testGetClass() {
		$this->assertEquals("Atrox_Core_ServiceLocator", Atrox_Core_ServiceLocator::getClass(),
			"This should only be run with the default ServiceLocator. " .
			"Ensure you haven't overloaded it with a customer implementation.");
	}

	/**
	 * Ensure that getRegistry returns a implementation of Atrox_Core_Registry
	 */
	public function testGetRegistry() {
		$this->assertType("Atrox_Core_Registry", Atrox_Core_ServiceLocator::getRegistry());
	}

	/**
	 * Ensure that getLogger returns a implementation of Atrox_Core_Logging_Logger
	 */
	public function testGetLogger() {
		$this->assertType("Atrox_Core_Logging_Logger", Atrox_Core_ServiceLocator::getLogger());
	}

	/**
	 * Ensure that getSecurity isn't implemented in the default Service Locator
	 *
	 * @expectedException Exception
	 */
	public function testGetSecurity() {
		Atrox_Core_ServiceLocator::getSecurity();
	}
}