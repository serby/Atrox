<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_PostgreSql_SourceTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Atrox_Core_Application
	 */
	protected $application;

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->createBlogTable();
		$this->application->createBlogCommentTable();
	}

	 public function tearDown() {
		$this->application->dropBlogTable();
		$this->application->dropBlogCommentTable();
	}

	 public function testGetDataSource() {
		$dataSource = Mock_Blog::getDataSource();
		$this->assertEquals("TempBlog", $dataSource->getTableName());
		$dataSource->setTableName("TempBlog");
		$this->assertEquals("TempBlog", $dataSource->getTableName());
		$this->assertEquals(5, count($dataSource->getPropertys()));
		$this->assertTrue($dataSource->getProperty("Id") instanceof Atrox_Core_Data_Property);
		$this->assertEquals("Title", $dataSource->getProperty("Title")->getName());
		$this->assertEquals("Date Created", $dataSource->getProperty("DateCreated")->getDescription());
		$this->assertEquals("", $dataSource->getProperty("Description")->getDefault());
		$this->assertEquals("This is the default title", $dataSource->getProperty("Title")->getDefault());
		$this->assertTrue($dataSource->getProperty("Id")->isHidden());
		$this->assertFalse($dataSource->getProperty("Title")->isHidden());
	}

	 public function testMapWorksWithBooleanPropertys() {
		$dataSource = Mock_Blog::getDataSource();
		$dataSource->getProperty("Active")->setDefault(true);
		$postData = array(
			"Active" => true
		);
		$entity = $dataSource->map($postData);
		$this->assertEquals(true, $entity->get("Active"));

		$dataSource->getProperty("Active")->setDefault(true);
		$postData = array(
			"Active" => false
		);
		$entity = $dataSource->map($postData);
		$this->assertEquals(false, $entity->get("Active"));


		$dataSource->getProperty("Active")->setDefault(true);
		$postData = array(
		);
		$entity = $dataSource->map($postData);
		$this->assertEquals(false, $entity->get("Active"));


		//$this->assertEquals(true, $dataSource->validate($entity));
		$this->assertEquals(array(), $this->application->getErrorHandler()->getErrors());
	}

	 public function testMakeFilter() {
		$dataSource = Mock_Blog::getDataSource();
		$this->assertTrue($dataSource->makeFilter() instanceof Atrox_Core_Data_IFilter);
	}

	 public function testCreate() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$this->assertTrue($dataSource->create($entity));
		$this->assertEquals("1", $entity->getId());
		$entity->set("Title", "This is the second blog");
		$entity->set("Description", "and this is the second description");
		$this->assertTrue($dataSource->create($entity));
		$this->assertEquals("2", $entity->getId());

		$title = utf8_encode("abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ '0123456789!£$%^&*()");
		$entity->set("Title", $title);
		$entity->set("Description", "and this is the second description");
		$this->assertTrue($dataSource->create($entity));

		$this->assertEquals($title, $entity->get("Title"));

		// Check the new records
		$entity = $dataSource->read(3);
		$this->assertEquals(3, $entity->getId());
		$this->assertEquals($title, $entity->get("Title"));
		$this->assertEquals("and this is the second description", $entity->get("Description"));
	}

	 public function testRead() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "My Title");
		$entity->set("Description", "and this is the first description");
		$dataSource->create($entity);
		$entity = $dataSource->read(1);
	}

	 public function testReadByProperty() {
		$dataSource = Mock_Blog::getDataSource();
		$firstRecord = $dataSource->makeNew();
		$firstRecord->set("Title", "My Title");
		$firstRecord->set("Description", "and this is the first description");
		$dataSource->create($firstRecord);
		$secondRecord = $dataSource->makeNew();
		$secondRecord->set("Title", "My Title");
		$secondRecord->set("Description", "and this is the second description");
		$dataSource->create($secondRecord);
		$entity = $dataSource->read(1);

		// This should fail because there are two records with the same Title value
		// $this->assertFalse($dataSource->readByProperty("My Title", "Title"));

		// No, this triggers an error, does NOT return false ()
		try {
			$dataSource->readByProperty("My Title", "Title");
			$this->fail("Title is not a unique value, but an exception has not been raised.");
		} catch (Atrox_Core_Exception_ReadByPropertyException $expected) {}

		$entity->set("Title", "This has changed");
		$dataSource->update($entity);

		$entity = $dataSource->read(1);
		$this->assertEquals("This has changed", $entity->get("Title"));

		$secondRecord = $dataSource->read(2);
		$this->assertEquals($secondRecord, $dataSource->readByProperty("My Title", "Title"));
	}

	 public function testUpdate() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$dataSource->create($entity);

		$entity = $dataSource->read(1);
		$entity->set("Title", "This is a new title");
		$entity->set("Description", "and this is a new description");
		$dataSource->update($entity);
		$this->assertTrue($entity->beforeUpdateCalled);
		$this->assertTrue($entity->afterUpdateCalled);

		$entity = $dataSource->read(1);
		$this->assertEquals("This is a new title", $entity->get("Title"));
		$this->assertEquals("and this is a new description", $entity->get("Description"));
	}

	//TODO: Add tests for updateProperty when updating a binary.
	 public function testUpdateProperty() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$dataSource->create($entity);

		$entity = $dataSource->read(1);
		$this->assertEquals("This is the first blog", $entity->get("Title"));

		$dataSource->updateProperty($entity, "Title", "This is a new title");
		$this->assertTrue($entity->beforeUpdateCalled);
		$this->assertTrue($entity->afterUpdateCalled);

		$entity = $dataSource->read(1);
		$this->assertEquals("This is a new title", $entity->get("Title"));

		//TODO: Change this exception if getProperty exception is changed
		try {
			$dataSource->updateProperty($entity, "Non-existant Property", "Value");
			$this->fail("Non-existant Property does not exist, but an exception has not been raised.");
		} catch (Exception $expected) {}

	}

	 public function testDelete() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$dataSource->create($entity);

		$entity = $dataSource->read(1);

		$this->assertTrue($dataSource->delete($entity));
		$this->assertFalse($dataSource->delete($entity));
		$this->assertFalse($dataSource->delete($entity));
	}

	 public function testGetRelation() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$dataSource->create($entity);

		$dataSource = Mock_BlogComment::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("BlogId", 1);
		$entity->set("Comment", "Blog Comment");
		$dataSource->create($entity);

		$blog = $entity->getRelation("BlogId");

		$this->assertEquals("This is the first blog", $blog->get("Title"));
	}

	 public function testValidate() {
		$dataSource = Mock_Blog::getDataSource();
		$entity = $dataSource->makeNew();

		$dataSource->validate($entity);
		$this->assertEquals(array("'Description' is required"), $this->application->getErrorHandler()->getErrors());

		$entity = $dataSource->makeNew();
		$entity->set("Title", "");
		$entity->set("Description", "");
		$dataSource->validate($entity);

		$this->assertEquals(array("'Title' is required", "'Description' is required"), $this->application->getErrorHandler()->getErrors());
	}

	 public function testDate() {
		$this->assertEquals("-2135548800", strtotime("1 May 1902"));
		$this->assertEquals("1 May 1902", date("j M Y", -2135548800));
	}
}