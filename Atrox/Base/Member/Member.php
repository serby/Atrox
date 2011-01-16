<?php
/**
 * @package Base
 * @subpackage Member
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 * @package Core
 * @subpackage Base/Member
 */
class Atrox_Base_Member_Member extends Atrox_Core_Data_Entity {

	public static function getDataSource() {

		$dataSource = new Atrox_Core_Data_PostgreSql_Source("TestMember");

		$dataSource->setRecordClassName("Atrox_Base_Member_Member");

		$dataSource->addProperty(new Atrox_Core_Data_Property("Id", 
			Atrox_Core_Data_Property::TYPE_INTEGER, "Id", true))
			->addValidator(new Atrox_Core_Data_Validator_Required());

		$dataSource->addProperty(new Atrox_Core_Data_Property("EmailAddress", 
			Atrox_Core_Data_Property::TYPE_STRING, "Email Address"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(500));

		$dataSource->addProperty(new Atrox_Core_Data_Property("Password", 
			Atrox_Core_Data_Property::TYPE_STRING, "Password"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(500));

		$dataSource->addProperty(new Atrox_Core_Data_Property("FirstName", 
			Atrox_Core_Data_Property::TYPE_STRING, "First Name"))
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(50));

		$dataSource->addProperty(new Atrox_Core_Data_Property("LastName", 
			Atrox_Core_Data_Property::TYPE_STRING, "Last Name"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
			->addValidator(new Atrox_Core_Data_Validator_MaxLength(50));

		return $dataSource;
	}

	public static function authenticate($uniqueIdentifier, $password) {

		$dataSource = self::getDataSource();
		$filter = $dataSource->makeFilter();
		$filter->addConditional($dataSource->getTableName(), "EmailAddress", $uniqueIdentifier);
		$filter->addConditional($dataSource->getTableName(), "Password", $password);

		$dataset = $dataSource->retrieve($filter);

		if ($entity = $dataset->getNext()) {
			return $entity;
		}

		return false;
	}

	public function getIdentity() {
		return $this->get("EmailAddress");
	}

	public function getPassword() {
		return $this->get("Password");
	}

	public function getToken() {
		return $this->get("Token");
	}
}