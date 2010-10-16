<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Iain Grant (Clock Limited) {@link mailto:iain.grant@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Caching_MemcachedTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {
		$this->memcached = new Atrox_Core_Caching_Memcached("UnitTestPrefix");
		$this->memcached->addServer("127.0.0.1");
	}

	public function tearDown() {

	}

	 public function testCanGetWhatWasSet() {
		$this->memcached->set("abc", "abc");
		$result = $this->memcached->get("abc");

		$this->assertEquals("abc", $result);
	}

	 public function testCanClear() {
		$this->memcached->set("abc", "abc");
		$result1 = $this->memcached->get("abc");
		$this->memcached->clear("abc");
		$result2 = $this->memcached->get("abc");

		$this->assertNotEquals($result1, $result2);
	}

	 public function testCanClearTag() {
		$this->memcached->set("abc", "abc", "Tag");
		$result1 = $this->memcached->get("abc");
		$this->memcached->clearTag("Tag");
		$result2 = $this->memcached->get("abc");

		$this->assertNotEquals($result1, $result2);
	}
}