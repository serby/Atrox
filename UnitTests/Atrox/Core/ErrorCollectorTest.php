<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_ErrorCollectorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testConstructor() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertType("Atrox_Core_ErrorCollector", $errorHandler);
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testAddAndGetErrors() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals(array(), $errorHandler->getErrors());

		$errorHandler->addError("Test Error");
		$errorHandler->addError("Value Test", "Key Test");
		$this->assertEquals(array("Test Error", "Key Test" => "Value Test"), $errorHandler->getErrors());
		$this->assertEquals(array(), $errorHandler->getErrors());

		$errorHandler->addError("Test Error");

		$this->assertEquals(array("Test Error"), $errorHandler->getErrors(false));
		$this->assertEquals(array("Test Error"), $errorHandler->getErrors(true));
		$this->assertEquals(array(), $errorHandler->getErrors());

		$errorHandler->addError(null);
		$this->assertEquals(array(null), $errorHandler->getErrors());
	}

	/**
 	 * @author Kapil Gohil (Clock Limited) {@link mailto:kapil.gohil@clock.co.uk kapil.gohil@clock.co.uk }
 	 */
	 public function testHasErrors() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals(null, $errorHandler->hasErrors());

		$errorHandler->addError("Test");
		$errorHandler->addError("Value Test", "Key Test");
		$errorHandler->addError("Value", 9);
		$this->assertTrue($errorHandler->hasErrors());
		$errorHandler->clear();

		$errorHandler->addError(null);
		$errorHandler->addError("Value Test", "Key Test");
		$this->assertTrue($errorHandler->hasErrors());
		$errorHandler->clear();

		$errorHandler->addError(null);
		$this->assertTrue($errorHandler->hasErrors());
		$errorHandler->clear();

		$this->assertFalse($errorHandler->hasErrors());
	}

	/**
 	 * @author Kapil Gohil (Clock Limited) {@link mailto:kapil.gohil@clock.co.uk kapil.gohil@clock.co.uk }
 	 */
	 public function testIsErrorOn() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals("", $errorHandler->isErrorOn(""));

		$errorHandler->addError("Test is empty", "Test");
		$errorHandler->addError("Hello  is empty", "Hello");
		$errorHandler->addError("World is empty", "World");

		$this->assertTrue($errorHandler->isErrorOn("World"));
		$this->assertFalse($errorHandler->isErrorOn("Failed"));
		$errorHandler->clear();

		$errorHandler->addError("Test Error1");
		$errorHandler->addError(1);
		$errorHandler->addError(null);

		$this->assertTrue($errorHandler->isErrorOn(0));
		$this->assertFalse($errorHandler->isErrorOn("Test"));
		$this->assertFalse($errorHandler->isErrorOn(5));
	}

	/**
 	 * @author Kapil Gohil (Clock Limited) {@link mailto:kapil.gohil@clock.co.uk kapil.gohil@clock.co.uk }
 	 */
	 public function testGetLastError() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals(null, $errorHandler->getLastError());

		$errorHandler->addError("Test", "Test is empty");
		$this->assertEquals("Test", $errorHandler->getLastError());

		$errorHandler->addError("Jim");
		$errorHandler->addError("Bob");
		$errorHandler->addError("Builder");

		$this->assertEquals("Builder", $errorHandler->getLastError());

		$errorHandler->addError("I like cats", "Dog");
		$errorHandler->addError("I like mice", "Cat");
		$errorHandler->addError("I like cheese", "Mouse");

		$this->assertEquals("I like cheese", $errorHandler->getLastError());
		$this->assertTrue(true, $errorHandler->getLastError());

		$errorHandler->addError(null);

		$this->assertEquals(null, $errorHandler->getLastError());
	}

	/**
 	 * @author Kapil Gohil (Clock Limited) {@link mailto:kapil.gohil@clock.co.uk kapil.gohil@clock.co.uk }
 	 */
	 public function testGetCause() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals(null, $errorHandler->getCause());

		$errorHandler->setCause("Test Cause");
		$this->assertEquals("Test Cause", $errorHandler->getCause());

		$errorHandler->setCause("Hello World");
		$this->assertTrue(true, $errorHandler->getCause());

		$errorHandler->setCause(null);
		$this->assertTrue(true, $errorHandler->getCause());

		$errorHandler->setCause(1);
		$this->assertEquals(1, $errorHandler->getCause());
	}

	/**
 	 * @author Kapil Gohil (Clock Limited) {@link mailto:kapil.gohil@clock.co.uk kapil.gohil@clock.co.uk }
 	 */
	 public function testSetCause() {
		$errorHandler = new Atrox_Core_ErrorCollector();
		$this->assertEquals(null, $errorHandler->setCause(null));

		$errorHandler->setCause("Test Cause");
		$this->assertTrue(true, $errorHandler->getCause());
		$this->assertEquals("Test Cause", $errorHandler->getCause());

		$errorHandler->setCause(null);
		$this->assertEquals(null, $errorHandler->getCause());
	}
}