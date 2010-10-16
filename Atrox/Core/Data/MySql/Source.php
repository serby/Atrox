<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1100 $ - $Date: 2009-08-10 16:48:23 +0100 (Mon, 10 Aug 2009) $
 */

/**
 *
* @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1100 $ - $Date: 2009-08-10 16:48:23 +0100 (Mon, 10 Aug 2009) $
 * @package Core
 * @subpackage Data/MySql
 */
class Atrox_Core_Data_MySql_Source extends Atrox_Core_Data_Source {

	protected $idName;
	protected $parsedSequenceName;

	/**
	 * @var Atrox_Application_Application
	 */
	protected $application;

	/**
	 *
	 * @param string $tableName
	 * @param string $idName
	 * @param string $sequenceName
	 * @param Atrox_Core_Data_IConnection $connection
	 * @return void
	 */
	public function __construct($tableName, $idName = "Id", $sequenceName = null, Atrox_Core_Data_IConnection $connection = null) {
		parent::__construct($connection);

		$this->setTableName($tableName);
		$this->setIdName($idName);

		if ($sequenceName) {
			$this->parsedSequenceName = $this->connection->parseField($sequenceName);
		} else {
			$this->parsedSequenceName = $this->connection->parseTable($tableName);
		}

		$this->application = Atrox_Application_Application::getInstance();
	}

	/**
	 *
	 * @param $idName
	 * @return void
	 */
	protected function setIdName($idName) {
		$this->idName = $idName;
		$this->parsedIdName = $this->connection->parseField($idName);
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Data/Atrox_Core_Data_Source#create()
	 */
	public function create(Atrox_Core_Data_Entity $entity) {

		if (!$entity->beforeCreate($entity)) {
			throw new Exception("beforeCreate failed");
			return false;
		}

		$fields = "";
		$values = "";

		$connection = $this->getConnection();
		$binaries = array();
		foreach ($this->properties as $name => $property) {
			if ($property->hasStorage(Atrox_Core_Data_Property::STORE_CREATE)) {
				$fields .= $connection->parseField("$name") . ", ";
				if ($property->getType() == Atrox_Core_Data_Property::TYPE_BINARY) {
					$binary = $entity->get($name);
					if ($this->hasFileError($binary["File"]["Error"])) {
						return false;
					}
					if ($binary["File"]["Error"] == 0) {
						$binary["Hash"] = $name . "/" . md5(uniqid(null, true));
						$value = $binary["Hash"] . "/" . $binary["File"]["Filename"];
						$binaries[] = $binary;
					} else {
						$value = "";
					}
				} else {
					$value = $property->formatForSourceInput($entity->get($name));
					if ($value == "t") {
						var_dump($property->sourceInputFormatters); exit;
					}
				}
				if (($value === "") || ($value === null)) {
					$values .= "null, ";
				} else {
					$values .= $connection->parseValue($value). ", ";
				}
			}
		}

		// Crop the last comma
		$fields = mb_substr($fields, 0, -2);
		$values = mb_substr($values, 0, -2);
		$sql = "INSERT INTO {$this->parsedTableName} ({$fields}) VALUES ({$values});";

		if ($result = $connection->query($sql)) {
			$entity->setId($connection->getLastId($this->parsedSequenceName));

			foreach ($binaries as $binary) {
				$storageProvider = $this->application->getStorageAdaptor();
				try {
					$storageProvider->makeBucket($this->getTableName() . "/" . $binary["Hash"]);
				} catch(Exception $e) {}
				$storageProvider->putBinaryFile($binary["File"]["TempName"], $this->getTableName() . "/" . $binary["Hash"], $binary["File"]["Filename"], $binary["File"]["Type"]);
			}

			if (!$entity->afterCreate($entity)) {
				throw new Exception("afterCreate failed");
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Fetches the record with the given ID from the database
	 *
	 * @param int $id The id of the row to retrieve.
	 * @return Atrox_Core_Data_Entity
	 */
	public function read($id, $cached = true) {

		if (!is_numeric($id)) {
			return false;
		}

		$key = $this->getCacheKey($this->idName . ":" . $id);
 		if (($cached) && ($this->application->getCacheManager()->get($key))) {
 			$entity = new $this->recordClassName();
 			return $entity->setData($this->application->getCacheManager()->get($key));
 		}

		$result = $this->connection->query(
			"SELECT * FROM {$this->parsedTableName} WHERE {$this->parsedIdName} = {$id} LIMIT 1"
		);

		if ($result === null) {
			return false;
		} else {
			if ($rowData = $this->connection->fetch($result)) {
				$entity = $this->outputMap($rowData);
				$this->application->getCacheManager()->set($key, $entity->getData());
				return $entity;
			} else {
				return false;
			}
		}
	}

	/**
	 * Fetches the first record with the $propertyName that equals $value
	 *
	 * @param int $id The id of the row to retrieve.
	 * @param string $propertyName
	 * @param bool $cached Whether or not to look in the cache
	 * @return Atrox_Core_Data_Entity
	 */
	public function readByProperty($value, $propertyName, $cached = true) {

		$lookupKey = $this->getCacheKey("__Key:" . $propertyName . ":" . $value);

		if (($cached) && ($key = $this->application->getCacheManager()->get($lookupKey)) && ($data = $this->application->getCacheManager()->get($key))) {
			$entity = new $this->recordClassName();
			return $entity->setData($data);
		}

		$this->getProperty($propertyName);

		$parsedProperty = $this->connection->parseField($propertyName);
		$parsedValue = $this->connection->parseValue($value);

		// LIMIT is set to 2 to make readByProperty fail when the Property value is not unique
		$result = $this->connection->query(
			"SELECT * FROM {$this->parsedTableName} WHERE {$parsedProperty} = {$parsedValue} LIMIT 2"
		);

		if ($result === null) {

			return false;
		} else {
			if ($this->connection->getResultCount($result) > 1) {
				throw new Atrox_Core_Exception_ReadByPropertyException(
					"'$propertyName' is not a unique in table '" . $this->getTableName());
			}	else if (($this->connection->getResultCount($result) == 1) && ($rowData = $this->connection->fetch($result))) {
				$entity = $this->outputMap($rowData);
				if ($cached) {
					$key = $this->getCacheKey($this->idName . ":" . $entity->getId());
					$this->application->getCacheManager()->set($lookupKey, $key);
					$this->application->getCacheManager()->set($key, $entity->getData());
				}
				return $entity;
			} else {
				return false;
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Data/Atrox_Core_Data_Source#update()
	 */
	public function update(Atrox_Core_Data_Entity $entity) {

		if (!is_numeric($id = $entity->getId())) {
			return false;
		}

		if (!$entity->beforeUpdate($entity)) {
			throw new Exception("beforeUpdate failed");
			return false;
		}

		if ($this->quickUpdate($entity)) {
			$this->application->getCacheManager()->clear($this->getCacheKey($this->idName . ":" . $id));

			if (!$entity->afterUpdate($entity)) {
				throw new Exception("afterUpdate failed");
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param $entity
	 * @return unknown_type
	 */
	public function quickUpdate(Atrox_Core_Data_Entity $entity) {

		$connection = $this->getConnection();
		$sql = "";
		$binaries = array();

		foreach ($this->properties as $name => $property) {
			if ($property->hasStorage(Atrox_Core_Data_Property::STORE_UPDATE)) {
				$sql .= $connection->parseField($name) . "=";

				if ($property->getType() == Atrox_Core_Data_Property::TYPE_BINARY) {

					$binary = $entity->get($name);

					if ($this->hasFileError($binary["File"]["Error"])) {
						return false;
					}

					if (isset($binary["Remove"])) {
						$binaries[] = $binary;
						$value = "";
					} else if ($binary["File"]["Error"] == 0) {
						$binary["Hash"] = $name . "/" . md5(uniqid(null, true));
						$value = $binary["Hash"] . "/" . $binary["File"]["Filename"];
						$binaries[] = $binary;
					} else {
						$value = $binary["Current"];
					}

				} else {
					$value = $property->formatForSourceInput($entity->get($name));
				}

				if (($value === "") || ($value === null)) {
					$sql .= "null, ";
				} else {
					$sql .= $connection->parseValue($value) . ", ";
				}
			}
		}

		// Crop the last comma
		$sql = mb_substr($sql, 0, -2);

		$sql = "UPDATE $this->parsedTableName SET $sql WHERE $this->parsedIdName = " . $entity->getId() . ";";

		if ($connection->query($sql)) {

			foreach ($binaries as $binary) {
				$storageProvider = $this->application->getStorageAdaptor();
				if (isset($binary["Remove"])) {
					if ($binary["Current"] != "") {
						$this->deleteBinary($binary["Current"]);
					}
				} else {
					try {

						if ($binary["Current"] != "") {
							$this->deleteBinary($binary["Current"]);
						}

						$storageProvider->makeBucket($this->getTableName() . "/" . $binary["Hash"]);
					} catch(Exception $e) {}
					$storageProvider->putBinaryFile($binary["File"]["TempName"], $this->getTableName() . "/" . $binary["Hash"], $binary["File"]["Filename"], $binary["File"]["Type"]);
				}
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Updates a single Property within a data record with the new value given.
	 *
	 * @param $entity Atrox_Core_Data_Entity The data record to be updated
	 * @param $propertyName String Name of the Property to be changed
	 * @param $newValue Mixed The new value for the record's Property
	 * @see Atrox/Core/Data/Atrox_Core_Data_Source#updateProperty($entity, $propertyName, $newValue)
	 */
	public function updateProperty(Atrox_Core_Data_Entity $entity, $propertyName, $newValue) {
		//TODO: this needs a unit test then implementing
		if (!is_numeric($id = $entity->getId())) {
			return false;
		}

		$property = $this->getProperty($propertyName);

		if (!$entity->beforeUpdate($entity)) {
			throw new Exception("beforeUpdate failed");
			return false;
		}

		$connection = $this->getConnection();
		$sql = $connection->parseField($propertyName). "=";

		if ($property->getType() == Atrox_Core_Data_Property::TYPE_BINARY) {
			$binary = $entity->get($name);

			if ($this->hasFileError($binary["File"]["Error"])) {
				return false;
			}

			if (isset($binary["Remove"])) {
				$value = "";
			} else if ($binary["File"]["Error"] == 0) {
				$binary["Hash"] = $propertyName . "/" . md5(uniqid(null, true));
				$value = $binary["Hash"] . "/" . $binary["File"]["Filename"];
			} else {
				$value = $binary["Current"];
			}
		} else {
			$value = $property->formatForSourceInput($newValue);
		}

		if (($value === "") || ($value === null)) {
			$sql .= "null";
		} else {
			$sql .= $connection->parseValue($value);
		}

		$sql = "UPDATE $this->parsedTableName SET $sql WHERE $this->parsedIdName = " . $entity->getId() . ";";
		if (!$connection->query($sql)) {
			return false;
		}

		$this->application->getCacheManager()->clear($this->getCacheKey($this->idName . ":" . $entity->getId()));

		if (isset($binary) && $binary) {
			$storageProvider = $this->application->getStorageAdaptor();
			if (isset($binary["Remove"])) {
				if ($binary["Current"] != "") {
					$this->deleteBinary($binary["Current"]);
				}
			} else {
				try {

					if ($binary["Current"] != "") {
						$this->deleteBinary($binary["Current"]);
					}

					$storageProvider->makeBucket($this->getTableName() . "/" . $binary["Hash"]);
				} catch(Exception $e) {}

				$storageProvider->putBinaryFile($binary["File"]["TempName"], $this->getTableName() . "/" . $binary["Hash"], $binary["File"]["Filename"], $binary["File"]["Type"]);
			}
		}

		if (!$entity->afterUpdate($entity)) {
			throw new Exception("afterUpdate failed");
			return false;
		}
	}

	protected function deleteBinary($binary) {
		$storageProvider = $this->application->getStorageAdaptor();
		$bucket = substr($binary, 0, $i = strrpos($binary, "/"));
		$uri = substr($binary, $i + 1);
		$storageProvider->deleteBucket($this->getTableName() . "/" . $bucket);
	}

	public function hasFileError($error) {
		switch ($error) {
			case 1:
			case 2:
				$this->application->getErrorHandler()->addError("File is too big");
				break;
			case 0:
			case 4:
				break;
			default:
				$this->application->getErrorHandler()->addError("There was a problem with your file");
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Data/Atrox_Core_Data_Source#delete()
	 */
	public function delete(Atrox_Core_Data_Entity $entity) {

		if (!is_numeric($id = $entity->getId())) {
			throw new Exception("Non-integer ID passed to delete: " . $entity->getId());
			return false;
		}

		if (!$entity->beforeDelete()) {
			throw new Exception("beforeDelete failed");
			return false;
		}

		$result = $this->quickDelete($id);

		if ($this->connection->getAffectedCount($result) != 1) {
			return false;
		}

		$this->application->getCacheManager()->clear($this->getCacheKey($this->idName . ":" . $id));

		foreach ($this->properties as $name => $property) {
			if ($property->getType() == Atrox_Core_Data_Property::TYPE_BINARY) {
				$this->deleteBinary($entity->get($name));
			}
		}

		if (!$entity->afterDelete()) {
			throw new Exception("afterDelete failed");
			return false;
		}

		return true;
	}

	/**
	 * Just performs a delete. No validation, escaping, or calling of callbacks. Use with care!
	 * @param int $id Id of the record to delete
	 * @return $resource Result from database
	 */
	protected function quickDelete($id) {
		return $this->connection->query(
			"DELETE FROM {$this->parsedTableName} WHERE {$this->parsedTableName}.{$this->parsedIdName} = '$id'"
		);
	}

	/**
	 * Just performs a delete. No validation or escaping, use with care!
	 * @param string $id Id of the record to delete
	 * @param string $field Field name
	 * @return $resource Result from database
	 */
	protected function quickDeleteByField($id, $field) {
		return $this->connection->query(
			"DELETE FROM {$this->parsedTableName} WHERE " . $this->getConnection()->parseField($field) . " = '$id'"
		);
	}

	/**
	 * Returns a unique name for the cache
	 * @param $id
	 * @return string
	 */
	protected function getCacheKey($id) {
		return "Record:{$this->tableName}:{$id}";
	}

	/**
	 *
	 * @see Atrox_Core_Data_Source#validate()
	 */
	public function validate(Atrox_Core_Data_Entity $entity) {
		$valid = true;
		foreach ($this->properties as $name => $property) {
			$valid = $property->validate($entity->get($name)) && $valid;
		}
		return $valid;
	}

	/**
	 *
	 * @param Atrox_Core_Data_IFilter $filter
	 * @return Atrox_Core_Data_Dataset
	 */
	public function retrieve(Atrox_Core_Data_IFilter $filter = null) {
		if ($filter != null) {
			$distinct = $filter->getDistinctSql();
			$joins = $filter->getJoinSql();
			$conditions = $filter->getConditionSql();
			$order = $filter->getOrderSql();
			$limit = $filter->getLimitSql();
			$sql = "SELECT {$distinct} {$this->parsedTableName}.* FROM {$this->parsedTableName} $joins $conditions $order $limit";
		} else {
			$sql = "SELECT {$this->parsedTableName}.* FROM {$this->parsedTableName}";
		}
		$this->rowCount = -1;

		if ($result = $this->connection->query($sql)) {
			$this->rowCount = $this->connection->getResultCount($result);
			$dataset = new Atrox_Core_Data_Dataset($result, $this, $filter);
			return $dataset;
		}
		return false;
	}

	/**
	 *
	 * @see Atrox_Core_Data_Source#count()
	 */
	public function count(Atrox_Core_Data_IFilter $filter = null) {
		if ($filter != null) {
			$distinct = $filter->getDistinctSql();
			$joins = $filter->getJoinSql();
			$conditions = $filter->getConditionSql();
			$order = $filter->getOrderSql();
			$sql = "SELECT {$distinct} COUNT(*) FROM {$this->parsedTableName} $joins $conditions $order";
		} else {
			$sql = "SELECT COUNT(*) FROM {$this->parsedTableName}";
		}
		$this->rowCount = -1;

		if ($result = $this->connection->query($sql)) {
			$row = $this->connection->fetch($result);
			return (int)$row[0];
		}
		return false;
	}

	public function makeFilter() {
		return new Atrox_Core_Data_MySql_Filter($this);
	}
}