<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Network_NetworkTest extends PHPUnit_Framework_TestCase {

	 public function testAddressesAreInNetwork() {
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("10.0.0.1", "10.0.0.0/24"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("10.0.0.1", "10.0.0.0/8"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("136.2.0.1", "136.2.0.0/15"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("136.2.31.255", "136.2.0.0/15"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("136.8.0.1", "136.8.0.0/13"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("136.8.128.1", "136.8.0.0/13"));
		$this->assertTrue(Atrox_Core_Network_Network::isAddressInNetwork("136.2.0.1", "136.2.0.1"));
	}

	 public function testAddressesAreNotInNetwork() {
		$this->assertFalse(Atrox_Core_Network_Network::isAddressInNetwork("10.0.0.1", "20.0.0.0/24"));
		$this->assertFalse(Atrox_Core_Network_Network::isAddressInNetwork("10.0.1.1", "10.1.0.0/24"));
		$this->assertFalse(Atrox_Core_Network_Network::isAddressInNetwork("136.1.0.1", "136.2.0.0/15"));
		$this->assertFalse(Atrox_Core_Network_Network::isAddressInNetwork("136.5.0.255", "136.2.0.0/15"));
		$this->assertFalse(Atrox_Core_Network_Network::isAddressInNetwork("136.2.0.1", "136.2.0.2"));
	}

	 public function testIsInListIsSuccessful() {
		$list = array("10.0.0.1", "136.2.0.2", "136.2.0.0/15");
		$this->assertTrue(Atrox_Core_Network_Network::isInList("10.0.0.1", $list));
		$this->assertTrue(Atrox_Core_Network_Network::isInList("136.2.0.2", $list));
		$this->assertTrue(Atrox_Core_Network_Network::isInList("136.2.0.3", $list));
		$this->assertTrue(Atrox_Core_Network_Network::isInList("136.2.10.23", $list));
	}

	 public function testIsInListFails() {
		$list = array("10.0.0.1", "136.2.0.2", "136.2.0.0/15");
		$this->assertFalse(Atrox_Core_Network_Network::isInList("9.0.0.1", $list));
		$this->assertFalse(Atrox_Core_Network_Network::isInList("136.19.0.2", $list));
		$this->assertFalse(Atrox_Core_Network_Network::isInList("136.19.0.3", $list));
		$this->assertFalse(Atrox_Core_Network_Network::isInList("136.19.10.23", $list));
	}
}