<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_SanitiseHtmlTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_SanitiseHtml();
		$this->assertEquals("", $formatter->format(null));
		$this->assertEquals("", $formatter->format(""));
		$this->assertEquals("0", $formatter->format(0));
	}

	 public function testFormat() {
		$formatter = new Atrox_Core_Data_Formatter_SanitiseHtml();
		$this->assertEquals("This is a test paragraph alert('Test');",
			$formatter->format("This is a test paragraph <script>alert('Test');</script>"));
		$this->assertEquals("This <strong>is</strong> a test paragraph alert('Test');",
			$formatter->format("This <strong>is</strong> a test paragraph <script>alert('Test');</script>"));
		$this->assertEquals("This <strong>is</strong> a test paragraph alert('Test');",
			$formatter->format("This <strong>is</strong> a test paragraph <script type='application/javascript'>alert('Test');</script>"));
	}

	 public function testStrict() {
		$formatter = new Atrox_Core_Data_Formatter_SanitiseHtml("");
		$this->assertEquals("This is a test paragraph alert('Test');",
			$formatter->format("This <strong>is</strong> a test paragraph <script>alert('Test');</script>"));
	}
}