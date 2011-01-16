<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Validator_IntegerTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Atrox_Core_Data_Property
	 */
	private $property;

	 public function setup() {
		$this->Property = new Atrox_Core_Data_Property("Test", Atrox_Core_Data_Property::TYPE_INTEGER, "Test Property");
	}

	 public function testSuccess() {
		$validator = new Atrox_Core_Data_Validator_Integer();
		$this->assertFalse($validator->isInvalid(null, $this->Property));
		$this->assertFalse($validator->isInvalid("", $this->Property));
		$this->assertFalse($validator->isInvalid(false, $this->Property));
		$this->assertFalse($validator->isInvalid(1, $this->Property));
		$this->assertFalse($validator->isInvalid("1", $this->Property));
		$this->assertFalse($validator->isInvalid("1200", $this->Property));
	}

	 public function testFailure() {
		$validator = new Atrox_Core_Data_Validator_Integer();
		$this->assertEquals("'Test Property' must be a whole number [1]", $validator->isInvalid(true, $this->Property));
		$this->assertEquals("'Test Property' must be a whole number [Hello]", $validator->isInvalid("Hello", $this->Property));
		$this->assertEquals("'Test Property' must be a whole number [1.2]", $validator->isInvalid(1.2, $this->Property));
		$this->assertEquals("'Test Property' must be a whole number [1.2]", $validator->isInvalid("1.2", $this->Property));
		$this->assertEquals("'Test Property' must be a whole number [1,200]", $validator->isInvalid("1,200", $this->Property));
	}
}