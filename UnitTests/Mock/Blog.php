<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */
class Mock_Blog extends Atrox_Core_Data_Entity {
	/**
	 *
	 * @return Atrox_Core_Data_Source
	 */
	 public static function getDataSource() {
		static $dataSource;
		if (!$dataSource) {
			$dataSource = new Atrox_Core_Data_PostgreSql_Source("TempBlog");

			$dataSource->setRecordClassName("Mock_Blog");

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

			$dataSource->addProperty(new Atrox_Core_Data_Property("Active", Atrox_Core_Data_Property::TYPE_BOOLEAN, "Active"))
			->addValidator(new Atrox_Core_Data_Validator_Required())
				->setDefault(true);
		}
		return $dataSource;
	}

	 public function beforeUpdate() {
		$this->beforeUpdateCalled = true;
		return true;
	}

	 public function afterUpdate() {
		$this->afterUpdateCalled = true;
		return true;
	}
}