<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_UpperTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_Upper();
		$this->assertEquals("", $formatter->format(""));
		$this->assertEquals("1", $formatter->format(1));
		$this->assertEquals("1", $formatter->format("1"));
		$this->assertEquals("", $formatter->format(null));
		$this->assertEquals("HELLO", $formatter->format("hello"));
		$this->assertEquals("HELLO WORLD", $formatter->format("Hello World"));
	}
}