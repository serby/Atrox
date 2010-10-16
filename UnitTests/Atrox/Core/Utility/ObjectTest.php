<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Utility_ObjectTest extends PHPUnit_Framework_TestCase {

	 public function testSuccessfulObjectToDomDocumentConversion() {

		$member = new stdClass();
		$member->name = "Paul";
		$member->age = 31;
		$member->dob = "2009-05-01";
		$member->text = "<strong>Hello</strong>";
		$member->pets = array("Cat","Dog","b" => "Mouse");
		$member->head = new stdClass();
		$member->head->eyeColor = array("Brown", "Blue");
		$member->head->hairColor = "Blonde";

		$domDocument = Atrox_Core_Utility_Object::toDomDocument($member);

		$this->assertType("DOMDocument", $domDocument);
		$this->assertTrue($domDocument->hasChildNodes());
		$this->assertTrue($domDocument->getElementsByTagName("pets") instanceof DOMNodeList);
		$this->assertEquals(1, $domDocument->getElementsByTagName("name")->length);
		$this->assertEquals($member->name, $domDocument->getElementsByTagName("name")->item(0)->nodeValue);
		$this->assertEquals($member->age, $domDocument->getElementsByTagName("age")->item(0)->nodeValue);
		$this->assertEquals(4, $domDocument->getElementsByTagName("value")->length);

		$this->assertEquals($member->text, $domDocument->getElementsByTagName("text")->item(0)->nodeValue);
		$this->assertEquals(0, $domDocument->getElementsByTagName("strong")->length);
	}
}