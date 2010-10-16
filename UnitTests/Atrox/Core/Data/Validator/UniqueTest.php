<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_Validator_UniqueTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Atrox_Core_Data_Property
	 */
	private $property;

	 public function setUp() {
		// testdatabase - This should be defined in you hosts file
		$this->validConnectionString = "host=testdatabase port=5432 dbname=AtroxTest user=WebUser password=test";
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->setConnection(new Atrox_Core_Data_PostgreSql_Connection($this->validConnectionString));
		$this->application->getConnection()->connect();
		$this->application->setLogPath(realpath("../../../../../Log"));

		$this->application->createBlogTable();
	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE \"TempBlog\";");
		$this->application->getConnection()->close();
	}

	 public function testSuccess() {
		$validator = new Atrox_Core_Data_Validator_Unique();
		$property = UniqueTestBlog::getDataSource()->getProperty("Title");
		$this->assertFalse($validator->isInvalid("Hello", $property));
	}

	 public function testFailure() {
	}
}

class UniqueTestBlog extends Atrox_Core_Data_Entity {
	/**
	 *
	 * @return Atrox_Core_Data_Source
	 */
	 public static function getDataSource() {

			$dataSource = new Atrox_Core_Data_PostgreSql_Source("TempBlog");

			$dataSource->setRecordClassName("UniqueTest");

			$dataSource->addProperty(new Atrox_Core_Data_Property("Id", Atrox_Core_Data_Property::TYPE_INTEGER, "Id", true))
				->setStorage(Atrox_Core_Data_Property::STORE_NEVER);

			$dataSource->addProperty(new Atrox_Core_Data_Property("Title", Atrox_Core_Data_Property::TYPE_STRING, "Title"))
				->addValidator(new Atrox_Core_Data_Validator_Required())
				->addValidator(new Atrox_Core_Data_Validator_MaxLength(50))
				->setDefault("This is the default title");

			$dataSource->addProperty(new Atrox_Core_Data_Property("Description", Atrox_Core_Data_Property::TYPE_STRING, "Description", true))
				->addValidator(new Atrox_Core_Data_Validator_Required())
				->addValidator(new Atrox_Core_Data_Validator_MaxLength(1000));

			$dataSource->addProperty(new Atrox_Core_Data_Property("DateCreated", Atrox_Core_Data_Property::TYPE_DATE, "Date Created"))
				->setStorage(Atrox_Core_Data_Property::STORE_NEVER);

		return $dataSource;
	}
}