<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Dom Udall (Clock Limited) {@link mailto:dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_MySql_ConnectionTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_MySql_Application::getInstance());
		$this->application->getConnection()->connect();

		$this->application->createBlogTable();
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");

		$sql = <<<SQL
			INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('First Blog Entry', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;
		$this->application->getConnection()->query($sql);
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");

		$sql = <<<SQL
			INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('I am a bog', 'A test blog for the featured member section.', '2008-05-28 17:51:24.943435');
SQL;
		$this->application->getConnection()->query($sql);
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");

		$sql = <<<SQL
			INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('A new blog entry', 'This is a blog description', '2008-06-20 12:44:56.752169');
SQL;
		$this->application->getConnection()->query($sql);
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");

	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE TempBlog;");
		$this->application->getConnection()->close();
	}

	 public function testGetRowCount() {
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");
		$this->assertEquals(3, $this->application->getConnection()->getRowCount($result));
	}

	 public function testFetchRow() {
		$result = $this->application->getConnection()->query("SELECT * FROM TempBlog");
		$row = $this->application->getConnection()->fetchRow($result);
		$this->assertEquals(10, count($row));
		$this->assertTrue(isset($row["Id"]));
		$this->assertTrue(isset($row["Title"]));
		$this->assertTrue(isset($row["Description"]));
		$this->assertTrue(isset($row["DateCreated"]));
	}

	 public function testGetAffectRowSuccess() {
		$result = $this->application->getConnection()->query("UPDATE TempBlog SET Title='New Title'");
		$this->assertEquals(3, $this->application->getConnection()->getAffectedRowCount($result));
	}

	 public function testNoSuchRelationExceptionIsThrow() {
		try {
			$this->application->getConnection()->query("SELECT * FROM NotARealTable");
			$this->fail("Atrox_Core_Exception_NoSuchRelationException expected.");
		} catch (Atrox_Core_Exception_NoSuchRelationException $e) {}
	}

	 public function testNullValueExceptionIsThrow() {
		try {
$sql = <<<SQL
			INSERT INTO TempBlog (Title, Description, DateCreated) VALUES (null, 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

			$this->application->getConnection()->query($sql);
			$this->fail("Atrox_Core_Exception_NullValueException expected.");
		} catch (Atrox_Core_Exception_NullValueException $e) {}
	}

	 public function testDuplicateKeyExceptionIsThrow() {
		try {
$sql = <<<SQL
			INSERT INTO TempBlog (Id, Title, Description, DateCreated) VALUES (1, 'Test', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

			$this->application->getConnection()->query($sql);
			$this->fail("Atrox_Core_Data_Exception_DuplicateKeyException expected.");
		} catch (Atrox_Core_Data_Exception_DuplicateKeyException $e) {}
	}

	 public function testOutOfRangeExceptionIsThrow() {
		//TODO: Can't remember how to get this error to throw - Paul Serby
		$this->markTestSkipped();

		$this->setExpectedException("Atrox_Core_Exception_OutOfRangeException");
$sql = <<<SQL
			INSERT INTO TempBlog ("Title", "Description", "DateCreated") VALUES ('01234567890123456789', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}

		function testTooLongExceptionIsThrow() {
		//TODO: Can't remember how to get this error to throw - Dom Udall
		$this->markTestSkipped();
		$this->setExpectedException("Atrox_Core_Exception_TooLongException");
$sql = <<<SQL
			INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('012345678901234567890', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}

	 public function testGetLastRowId() {
$sql = <<<SQL
		INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('Aenean elit,', 'Small Blog Post', '2008-10-28 15:50:24.459776');
SQL;
		$this->application->getConnection()->query($sql);
		$this->assertEquals("4", $this->application->getConnection()->getLastRowId("TempBlog"));

		$sql = <<<SQL
INSERT INTO TempBlog (Title, Description, DateCreated) VALUES ('Aenean elit,', 'Small Blog Post', '2008-10-28 15:50:24.459776');
SQL;
		$this->application->getConnection()->query($sql);
		$this->assertEquals("5", $this->application->getConnection()->getLastRowId("TempBlog"));
	}
}