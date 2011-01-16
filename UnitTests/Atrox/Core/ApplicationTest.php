<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_ApplicationTest extends PHPUnit_Framework_TestCase {

	 public function testCheckSingleton() {
		$application1 = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$application2 = Atrox_Core_Application::getInstance();

		$this->assertEquals($application1, $application2);
	}

	 public function testDebug() {
		$application = Atrox_Core_Application::getInstance();
		$this->assertFalse($application->isDebug());
		$application->setDebug(false);
		$this->assertFalse($application->isDebug());

		$application->setDebug(true);
		$this->assertTrue($application->isDebug());

		$application->setDebug(false);
		$this->assertFalse($application->isDebug());
	}

	 public function testDataConnection() {
		$application = Atrox_Core_Application::getInstance();
	}
}