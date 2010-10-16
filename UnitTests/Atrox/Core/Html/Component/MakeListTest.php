<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Tom Smith (Clock Limited) {@link mailto:thomas.smith@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Html_Component_MakeListTest extends PHPUnit_Framework_TestCase {

	 public function testMakeList() {

		$list = array(
			"Item 1",
			"Item 2"
		);

		$this->assertEquals("",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => array()
			))
		);

		$this->assertEquals("<li>Item 1</li><li>Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list
			))
		);

		$this->assertEquals("<li class=\"active-item\">Item 1</li><li>Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 1"
			))
		);

		$this->assertEquals("<li>Item 1</li><li class=\"test-class\">Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
				"class" => "test-class"
			))
		);
	}

	 public function testMakeListWithNullValues() {
		$this->setExpectedException('InvalidArgumentException');
		$this->assertEquals("", Atrox_Core_Html_Component_MakeList::make(null));
	}

	 public function testMakeListWithMultiDimensionalList() {
		$list = array(
			array(
				0 => "Item 1",
				"1st Item"
			),
			array(
				0 => "Item 2",
				"2nd Item"
			)
		);

		$this->assertEquals("<li>Item 1</li><li class=\"active-item\">Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
			))
		);

		$this->assertEquals("<li>Item 1</li><li>Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
				"compareElement" => "2nd Item"
			))
		);

		$list = array(
			array(
				"1st",
				"Item",
				"2nd Item" => "Item 1"
			),
			array(
				"2nd",
				"Item",
				"2nd Item" => "Item 2"
			)
		);

		$this->assertEquals("<li>1st Item</li><li class=\"active-item\">2nd Item</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
				"compareElement" => "2nd Item",
				"pattern" => "%s %s"
			))
		);

		$this->assertEquals("<li>1st Item Item 1</li><li class=\"my-class-name\">2nd Item Item 2</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
				"compareElement" => "2nd Item",
				"pattern" => "%s %s %s",
				"class" => "my-class-name"
			))
		);

		$list = array(
			array(
				"2nd Item" => "Item 1",
				"1st",
				"Item"
			),
			array(
				"2nd Item" => "Item 2",
				"2nd",
				"Item"
			)
		);

		$this->assertEquals("<li>Item 1 1st</li><li class=\"active-item\">Item 2 2nd</li>",
			Atrox_Core_Html_Component_MakeList::make((object) array(
				"list" => $list,
				"selected" => "Item 2",
				"compareElement" => "2nd Item",
				"pattern" => "%s %s"
			))
		);
	}
}