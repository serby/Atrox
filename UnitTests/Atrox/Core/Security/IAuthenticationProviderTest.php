<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Security_IAuthenticationProviderTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Atrox_Core_Application
	 */
	private $application;


	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_Application::getInstance());
		$this->application->getConnection()->connect();

		$sql = <<<SQL
CREATE TEMPORARY TABLE "TestMember" (
    "Id" serial NOT NULL,
    "EmailAddress" text NOT NULL,
    "Password" text NOT NULL,
    "Token" text
);
SQL;

		$this->application->getConnection()->query($sql);
	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE \"TestMember\";");
		$this->application->getConnection()->close();
	}


	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testAuthenticate() {

		$dataSource = TestMember::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("EmailAddress", "paul.serby@clock.co.uk");
		$entity->set("Password", "clock001");
		$this->assertTrue($dataSource->create($entity));

		$this->assertTrue($entity->authenticate("paul.serby@clock.co.uk", "clock001") == true);
		$this->assertFalse($entity->authenticate("paul.serby@clock.co.uk", "wrong password") == true);

	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testTrustedAuthenticate() {

		$dataSource = TestMember::getDataSource();
		$entity = $dataSource->makeNew();
		$entity->set("EmailAddress", "paul.serby@clock.co.uk");
		$entity->set("Password", "clock001");
		$this->assertTrue($dataSource->create($entity));

		$this->assertTrue($entity->trustedAuthenticate("paul.serby@clock.co.uk") == true);
		$this->assertFalse($entity->trustedAuthenticate("wrong@clock.co.uk") == true);

	}
}

class TestMember extends Atrox_Core_Security_AuthenticationProvider_Record {

	 public static function getDataSource() {

		$dataSource = new Atrox_Core_Data_PostgreSql_Source("TestMember");

		$dataSource->setRecordClassName("TestMember");

		$dataSource->addProperty(new Atrox_Core_Data_Property("Id", Atrox_Core_Data_Property::TYPE_INTEGER, "Id", true))
			->setStorage(Atrox_Core_Data_Property::STORE_NEVER)
			->addValidator(new Atrox_Core_Data_Validator_Required());

		$dataSource->addProperty(new Atrox_Core_Data_Property("EmailAddress", Atrox_Core_Data_Property::TYPE_STRING, "Email Address"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(500));

		$dataSource->addProperty(new Atrox_Core_Data_Property("Password", Atrox_Core_Data_Property::TYPE_PASSWORD, "Password"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(500))
			->setStorage(Atrox_Core_Data_Property::STORE_CREATE);

		$dataSource->addProperty(new Atrox_Core_Data_Property("Token", Atrox_Core_Data_Property::TYPE_STRING, "Token"))
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(50));

		return $dataSource;
	}

	 public function getType() {
		return 1;
	}
}