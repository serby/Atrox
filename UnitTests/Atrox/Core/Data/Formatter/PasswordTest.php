<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_PasswordTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_Password();
		$this->assertFalse($formatter->format(null));
		$this->assertFalse($formatter->format(""));
		$this->assertFalse($formatter->format(0));
		$this->assertFalse($formatter->format("0"));
		$this->assertEquals("085424ae8597faf769ff0ac95d06bb88e1575f4a", $formatter->format(1));
	}

	 public function testRegualar() {
		$formatter = new Atrox_Core_Data_Formatter_Password();
		$this->assertEquals("ac974dda4af5b3094c2d25362042c40359514e1c", $formatter->format("glitter8moon"));
		$this->assertEquals("271036c5361a00b9453af9d071e59af6bd4dc506", $formatter->format("clock001"));
		$this->assertEquals("5aeed8bfd8e211bc3c22549691aa3ad3435d12a0", $formatter->format("CLOCK001"));
		$this->assertEquals("07e4d6961babbaf8c20aa367336031546dbf1591", $formatter->format("1234567890-=qwertyuiop[]asdfghjkl;'#\zxcvbnm,./!\"£$%^&*()_+QWERTYUIOP{}ASDFGHJKL:@~|ZXCVBNM<>?"));
	}
}