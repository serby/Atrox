<?php
/**
 * Test for the Registry class.
 *
 * @version 5.1 - $Revision: 1338 $ - $Date: 2010-02-27 20:43:51 +0000 (Sat, 27 Feb 2010) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */

require_once("PHPUnit/Framework.php");
require_once("Bootstrap.php");

/**
 * Test for the Registry class.
 *
 * @version 5.1 - $Revision: 1338 $ - $Date: 2010-02-27 20:43:51 +0000 (Sat, 27 Feb 2010) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_RegistryTest extends PHPUnit_Framework_TestCase {

	/**
	 * Check the get method.
	 *
	 * @return null
	 */
	public function testGet() {
		$registry = new Atrox_Core_Registry();
		$this->assertEquals("", $registry->get(""));
		$this->assertEquals("", $registry->get("Test"));
		$this->assertEquals("Hello", $registry->get("Test", "Hello"));
		$this->assertEquals("Something New", $registry->get("Test", "Something New"));

		$this->assertEquals("Hello", $registry->set("Test", "Hello"));
		$this->assertEquals("Hello", $registry->get("Test", "Something New"));

		$registry->set("Test Location", "Test Value");
		$this->assertEquals("Test Value", $registry->get("Test Location"));
	}
}