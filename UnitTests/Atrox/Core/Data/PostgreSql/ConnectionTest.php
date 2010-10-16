<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_PostgreSql_ConnectionTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->getConnection()->connect();
		$this->application->createBlogTable();

		$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('First Blog Entry', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;
		$this->application->getConnection()->query($sql);

		$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('I am a bog', 'A test blog for the featured member section.', '2008-05-28 17:51:24.943435');
SQL;
		$this->application->getConnection()->query($sql);

		$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('A new blog entry', 'This is a blog description', '2008-06-20 12:44:56.752169');
SQL;
		$this->application->getConnection()->query($sql);
	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE \"TempBlog\";");
		$this->application->getConnection()->close();
	}

	 public function testGetRowCount() {
		$result = $this->application->getConnection()->query("SELECT * FROM \"TempBlog\"");
		$this->assertEquals(3, $this->application->getConnection()->getRowCount($result));
	}

	 public function testFetchRow() {
		$result = $this->application->getConnection()->query("SELECT * FROM \"TempBlog\"");
		$row = $this->application->getConnection()->fetchRow($result);
		$this->assertEquals(8, count($row));
		$this->assertTrue(isset($row["Id"]));
		$this->assertTrue(isset($row["Title"]));
		$this->assertTrue(isset($row["Description"]));
		$this->assertTrue(isset($row["DateCreated"]));
	}

	 public function testGetAffectRowFail() {
		//$this->setExpectedException("ErrorException");
		//$this->application->getConnection()->query("SELECT * INTO TEMP \"TempBlog\" FROM \"Blog\"");
		//$result = $this->application->getConnection()->query("UPDATE \"TempBlog\" SET \"Title\" = 'New Title'");
		$this->assertEquals(false, $this->application->getConnection()->getAffectedRowCount(null));
	}

	 public function testGetAffectRowSuccess() {
		$result = $this->application->getConnection()->query("UPDATE \"TempBlog\" SET \"Title\" = 'New Title'");
		$this->assertEquals(3, $this->application->getConnection()->getAffectedRowCount($result));
	}

	 public function testNoSuchRelationExceptionIsThrow() {
		$this->setExpectedException("Atrox_Core_Exception_NoSuchRelationException");
		$this->application->getConnection()->query("SELECT * FROM \"NotARealTable\"");
	}

	 public function testNullValueExceptionIsThrow() {
		$this->setExpectedException("Atrox_Core_Exception_NullValueException");
$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES (null, 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}

	 public function testDuplicateKeyExceptionIsThrow() {
		$this->setExpectedException("Atrox_Core_Data_Exception_DuplicateKeyException");
$sql = <<<SQL
			INSERT INTO "TempBlog" ("Id", "Title", "Description", "DateCreated") VALUES (1, 'Test', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}

	 public function testOutOfRangeExceptionIsThrow() {
		//TODO: Can't remember how to get this error to throw - Paul Serby
		$this->markTestSkipped();

		$this->setExpectedException("Atrox_Core_Exception_OutOfRangeException");
$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('01234567890123456789', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}

		function testTooLongExceptionIsThrow() {
		$this->setExpectedException("Atrox_Core_Exception_TooLongException");
$sql = <<<SQL
			INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('012345678901234567890', 'I love blogging', '2008-05-22 17:15:33.460379');
SQL;

		$this->application->getConnection()->query($sql);
	}



	 public function testGetLastRowId() {
		$sql = <<<SQL
INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('Aenean elit,', 'Small Blog Post', '2008-10-28 15:50:24.459776');

SQL;
		$this->application->getConnection()->query($sql);
		$this->assertEquals("4", $this->application->getConnection()->getLastRowId("\"TempBlog_Id_seq\""));

		$sql = <<<SQL
INSERT INTO "TempBlog" ("Title", "Description", "DateCreated") VALUES ('Aenean elit,', 'Small Blog Post', '2008-10-28 15:50:24.459776');

SQL;
		$this->application->getConnection()->query($sql);
		$this->assertEquals("5", $this->application->getConnection()->getLastRowId("\"TempBlog_Id_seq\""));
	}
}