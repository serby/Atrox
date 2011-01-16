<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Formatter_ArrayListTest extends PHPUnit_Framework_TestCase {

	 public function testEmpty() {
		$formatter = new Atrox_Core_Data_Formatter_ArrayList(array());
		$this->assertEquals("", $formatter->format(null));
		$this->assertEquals("", $formatter->format(1));
	}

	 public function testOutOfBounds() {
		$formatter = new Atrox_Core_Data_Formatter_ArrayList(array("Paul", "Serby", "Tom", "Smith"));
		$this->assertEquals("Paul", $formatter->format(0));
		$this->assertEquals("Serby", $formatter->format(1));
		$this->assertEquals("Tom", $formatter->format(2));
		$this->assertEquals("Smith", $formatter->format(3));
		$this->assertEquals("Paul", $formatter->format(4));
	}

	 public function testAssociative() {
		$formatter = new Atrox_Core_Data_Formatter_ArrayList(array("CTO" => "Paul Serby", "CEO" => "Syd Nadim", "FO" => "Paul Shattock"));
		$this->assertEquals("Paul Serby", $formatter->format(-1));
		$this->assertEquals("Paul Serby", $formatter->format(0));
		$this->assertEquals("Paul Serby", $formatter->format(1));
		$this->assertEquals("Paul Serby", $formatter->format(2));
		$this->assertEquals("Paul Serby", $formatter->format(3));
		$this->assertEquals("Paul Serby", $formatter->format(4));
		$this->assertEquals("Paul Serby", $formatter->format("CTO"));
		$this->assertEquals("Syd Nadim", $formatter->format("CEO"));
		$this->assertEquals("Paul Shattock", $formatter->format("FO"));
		$this->assertEquals("Paul Serby", $formatter->format("BAD"));
	}
}