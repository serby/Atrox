<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_HtmlOutputTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_HtmlOutput();
		$this->assertEquals("", $formatter->format(null));
		$this->assertEquals("", $formatter->format(""));
		$this->assertEquals("0", $formatter->format(0));
	}

	 public function testFormat() {
		$formatter = new Atrox_Core_Data_Formatter_HtmlOutput();
		$this->assertEquals("Hello<br />\nBye", $formatter->format("Hello\nBye"));
		$this->assertEquals("&pound;100", $formatter->format("£100"));
		$this->assertEquals("&lt;100&gt;", $formatter->format("<100>"));
		$this->assertEquals("100 Hello", $formatter->format("100 Hello"));
	}
}