<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Dom Udall (Clock Limited) {@link mailto:dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_JsonEncodeTest extends PHPUnit_Framework_TestCase {

//	function testBasicJsonEncode() {
//		$formatter = new Atrox_Core_Data_Formatter_JsonEncode();
//		$this->assertEquals("true", $formatter->format(true));
//		$this->assertEquals(1, $formatter->format(1));
//		$this->assertEquals("\"string\"", $formatter->format("string"));
//		$this->assertEquals("{\"key\":\"value\"}", $formatter->format(array("key" => "value")));
//	}
//
//	function testComplexJsonEncode() {
//		$formatter = new Atrox_Core_Data_Formatter_JsonEncode();
//		$object = new stdClass();
//		$object->basicValue = "basicValue";
//		$object->arrayValue = array("arrayKey" => "arrayValue");
//		$complexValue = new stdClass();
//		$complexValue->key = "key";
//		$complexValue->value = array("subkey" => "subvalue");
//		$object->complexValue = $complexValue;
//
//		$expectedValue = "{\"basicValue\":\"basicValue\",\"arrayValue\":{\"arrayKey\":\"arrayValue\"},\"complexValue\":{\"key\":\"key\",\"value\":{\"subkey\":\"subvalue\"}}}";
//		$this->assertEquals($expectedValue, $formatter->format($object));
//	}

	 public function testSpecialCharacterJsonEncode() {
		$formatter = new Atrox_Core_Data_Formatter_JsonEncode();
		$this->assertEquals("\"!&pound;&quot;$%^&amp;*()+{}@~?&gt;&lt;\/.,\\\*-&eacute;~&not;`#\"", $formatter->format("!£\"$%^&*()+{}@~?></.,\*-é~¬`#"));
	}
}