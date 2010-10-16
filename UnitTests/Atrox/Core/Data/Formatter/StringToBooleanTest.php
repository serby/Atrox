<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_StringToBooleanTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_StringToBoolean();
		$this->assertFalse($formatter->format(""));
		$this->assertFalse($formatter->format(0));
		$this->assertFalse($formatter->format(null));
	}

	 public function testTrue() {
		$formatter = new Atrox_Core_Data_Formatter_StringToBoolean();
		$this->assertTrue($formatter->format(true));
		$this->assertTrue($formatter->format("1"));
		$this->assertTrue($formatter->format("true"));
		$this->assertTrue($formatter->format("True"));
		$this->assertTrue($formatter->format("t"));
		$this->assertTrue($formatter->format("T"));
		$this->assertTrue($formatter->format("TRUE"));
	}

	 public function testFalse() {
		$formatter = new Atrox_Core_Data_Formatter_StringToBoolean();
		$this->assertFalse($formatter->format(false));
		$this->assertFalse($formatter->format("0"));
		$this->assertFalse($formatter->format("false"));
		$this->assertFalse($formatter->format("False"));
		$this->assertFalse($formatter->format("FALSE"));
		$this->assertFalse($formatter->format("f"));
		$this->assertFalse($formatter->format("F"));
	}
}