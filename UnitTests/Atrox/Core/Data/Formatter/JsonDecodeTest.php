<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Dom Udall (Clock Limited) {@link mailto:dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_JsonDecodeTest extends PHPUnit_Framework_TestCase {

	 public function testBasicJsonDecode() {
		$formatter = new Atrox_Core_Data_Formatter_JsonDecode();
		$this->assertEquals(1, $formatter->format("true"));
		$this->assertEquals("", $formatter->format("false"));
		$this->assertEquals("true", $formatter->format("\"true\""));
		$this->assertEquals("false", $formatter->format("\"false\""));
		$this->assertEquals(1, $formatter->format(1));
		$this->assertEquals("string", $formatter->format("\"string\""));
		$object = new stdClass();
		$object->key = "value";
		$this->assertEquals($object, $formatter->format("{\"key\":\"value\"}"));
	}

	 public function testComplexJsonDecode() {
		$formatter = new Atrox_Core_Data_Formatter_JsonDecode();
		$object = new stdClass();
		$arrayValue = new stdClass();
		$complexValue = new stdClass();
		$arrayObject = new stdClass();

		$arrayValue->arrayKey = "arrayValue";
		$arrayObject->subkey = "subvalue";
		$object->basicValue = "basicValue";
		$object->arrayValue = $arrayValue;
		$complexValue->key = "key";
		$complexValue->value = $arrayObject;
		$object->complexValue = $complexValue;

		$inputValue = "{\"basicValue\":\"basicValue\",\"arrayValue\":{\"arrayKey\":\"arrayValue\"},\"complexValue\":{\"key\":\"key\",\"value\":{\"subkey\":\"subvalue\"}}}";
		$this->assertEquals($object, $formatter->format($inputValue));
	}

	 public function testSpecialCharacterJsonDecode() {
		$formatter = new Atrox_Core_Data_Formatter_JsonDecode();
		$this->assertEquals("!£\"$%^&*()+{}@~?></.,\*-é~¬`#", $formatter->format("\"!&pound;&quot;$%^&amp;*()+{}@~?&gt;&lt;\/.,\\\*-&eacute;~&not;`#\""));
	}
}