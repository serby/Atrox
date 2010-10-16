<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_GeneratorTest extends PHPUnit_Framework_TestCase {


	 public function testGenerate() {
		$generatedData = Atrox_Core_Data_Generator::generate(array_keys(Atrox_Core_Data_Generator::$types), 100);
		$this->assertEquals(100, count($generatedData[Atrox_Core_Data_Generator::TYPE_FIRSTNAME]));
	}

	 public function testPostcode() {
		for ($i = 0; $i < 100; $i++) {
			$this->assertRegExp("/[A-Z]{1,2}[0-9]{1,2} [0-9]{1,2}[A-Z]{1,2}/",
				Atrox_Core_Data_Generator::getTestData(Atrox_Core_Data_Generator::TYPE_POSTCODE));
		}
	}
}