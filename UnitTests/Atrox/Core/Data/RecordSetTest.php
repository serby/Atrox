<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 *
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_DatasetTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->getConnection()->connect();

		$this->application->createBlogTable();
	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE \"TempBlog\";");
		$this->application->getConnection()->close();
	}

	 public function testGetPage() {
		$filter = Mock_Blog::getDataSource()->makeFilter();
		$dataSource = Mock_Blog::getDataSource();
		$dataset = $dataSource->retrieve($filter);

		//Getting Page 1 with no records in DB
		$i = 0;
		while ($entity = $dataset->getPage(1, 5)) {
			$i++;
		}
		$this->assertEquals(0, $i);

		//Getting Page 1 with 0 length with no records in DB
		$i = 0;
		while ($entity = $dataset->getPage(1, 0)) {
			$i++;
		}
		$this->assertEquals(0, $i);

		//Add 9 Records.
		for ($i = 1; $i <= 9; $i++) {
			$newBlog = $dataSource->makeNew();
			$newBlog->set("Title", "{$i}");
			$newBlog->set("Description", "Some Description {$i}");
			$dataSource->create($newBlog);
		}
		$dataset = $dataSource->retrieve($filter);

		//Getting Page 1 with 0 length with records in DB
		$i = 0;
		while ($entity = $dataset->getPage(1, 0)) {
			$i++;
		}
		$this->assertEquals(0, $i);


		//Getting Page 1 with records in DB
		$i = 0;
		while ($entity = $dataset->getPage(1, 5)) {
			$i++;
			$this->assertType("Mock_Blog", $entity);
			$this->assertEquals($i, $entity->getId());
		}
		$this->assertEquals(5, $i);

		//Getting Page 2 with records in DB
		$i = 0;
		while ($entity = $dataset->getPage(2, 5)) {
			$i++;
			$this->assertType("Mock_Blog", $entity);
			$this->assertEquals($i + 5, $entity->getId());
		}
		$this->assertEquals(4, $i);

		//Getting Page 3 with records in DB should return false immediately
		$i = 0;
		while ($entity = $dataset->getPage(3, 5)) {
			$i++;
		}
		$this->assertEquals(0, $i);

		//Requesting page 1 with a length longer than rows in DB
		$i = 0;
		while ($entity = $dataset->getPage(1, 11)) {
			$i++;
			$this->assertType("Mock_Blog", $entity);
			$this->assertEquals($i, $entity->getId());
		}
		$this->assertEquals(9, $i);
	}
}