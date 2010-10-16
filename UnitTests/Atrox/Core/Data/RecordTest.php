<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_EntityTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->getConnection()->connect();

		$this->application->createBlogTable();
	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE \"TempBlog\";");
		//$this->application->getConnection()->close();
	}

	 public function testMakeNew() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$this->assertEquals("This is the default title", $entity->get("Title"));
		$this->assertEquals("Id: \nTitle: This is the default title\nDescription: \nDate Created: \n", $entity->toString());
	}

	 public function testGetFormatted() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("DateCreated", "2009-05-01 12:34");
		$this->assertEquals("This is the default title", $entity->getFormatted("Title"));
		$this->assertEquals("", $entity->getFormatted("Description"));
		$this->assertEquals("1 May 2009", $entity->getFormatted("DateCreated"));
	}

	 public function testToObject() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$this->assertEquals("This is the default title", $entity->get("Title"));

		$response = new stdClass();
		$response->id = new stdClass();
		$response->id->data = "";
		$response->title = new stdClass();
		$response->title->data = "This is the default title";
		$response->description = new stdClass();
		$response->description->data = "";
		$response->dateCreated = new stdClass();
		$response->dateCreated->data = "";

		$this->assertEquals($response, $entity->toObject(false));

		$entity->set("Title", " Big Dog ");
		$response = new stdClass();
		$response->id = new stdClass();
		$response->id->data = "";
		$response->title = new stdClass();
		$response->title->data = "Big Dog";
		$response->description = new stdClass();
		$response->description->data = "";
		$response->dateCreated = new stdClass();
		$response->dateCreated->data = "";
		$this->assertEquals($response, $entity->toObject(false));

		$response = new stdClass();
		$response->id = new stdClass();
		$response->id->data = "";
		$response->id->formatted = "";
		$response->title = new stdClass();
		$response->title->data = "Big Dog";
		$response->title->formatted = "Big Dog";
		$response->description = new stdClass();
		$response->description->data = "";
		$response->description->formatted = "";
		$response->dateCreated = new stdClass();
		$response->dateCreated->data = "";
		$response->dateCreated->formatted = "-";


		$this->assertEquals($response, $entity->toObject());

		$entity->set("DateCreated", "2009-05-01 11:34:00");

		$response->dateCreated->data = "2009-05-01 10:34:00";
		$response->dateCreated->formatted = "1 May 2009";

		$this->assertEquals($response, $entity->toObject());
	}
}