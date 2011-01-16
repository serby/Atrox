<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Dom Udall (Clock Limited) {@link mailto:dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_BooleanToIntegerTest extends PHPUnit_Framework_TestCase {

	 public function testReturnOne() {
		$formatter = new Atrox_Core_Data_Formatter_BooleanToInteger();
		$this->assertEquals(1, $formatter->format(true));
		$this->assertEquals(1, $formatter->format("1"));
		$this->assertEquals(1, $formatter->format("true"));
		$this->assertEquals(1, $formatter->format("True"));
		$this->assertEquals(1, $formatter->format("t"));
		$this->assertEquals(1, $formatter->format("T"));
		$this->assertEquals(1, $formatter->format("TRUE"));
		$this->assertEquals(1, $formatter->format("false"));
		$this->assertEquals(1, $formatter->format("False"));
		$this->assertEquals(1, $formatter->format("FALSE"));
		$this->assertEquals(1, $formatter->format("f"));
		$this->assertEquals(1, $formatter->format("F"));
	}

	 public function testReturnZero() {
		$formatter = new Atrox_Core_Data_Formatter_BooleanToInteger();
		$this->assertEquals(0, $formatter->format(""));
		$this->assertEquals(0, $formatter->format(0));
		$this->assertEquals(0, $formatter->format(null));
		$this->assertEquals(0, $formatter->format(false));
		$this->assertEquals(0, $formatter->format("0"));
	}
}