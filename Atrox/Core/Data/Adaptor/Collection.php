<?php
/**
 * Connection to a Data Source
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Atrox_Core_Data_Adaptor_Collection {

	/**
	 * Friendly name of this collection. i.e. Contact, Basket
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @var Atrox_Core_Data_EntitySchema
	 */
	protected $scheme;

	/**
	 * @var Atrox_Core_Data_Adaptor_IConnection
	 */
	protected $connection;

	/**
	 *
	 * @param Atrox_Core_Data_IConnection $connection
	 */
	public function __construct($name, Atrox_Core_Data_EntitySchema $scheme,
		Atrox_Core_Data_Adaptor_IConnection $connection) {

		$this->setName($name);
		$this->connection = $connection;

		$this->scheme = $scheme;
	}

	/**
	 *
	 * @param string $name
	 * @return void
	 */
	protected function setName($name) {
		$this->name = $name;
		$this->parsedName = $this->connection->parseName($name);
	}

	/**
	 * @return Atrox_Core_Data_IConnection
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 *
	 * @param int $name
	 * @return void
	 */
	protected function setId($id) {
		$this->id = $id;
		$this->parsedIdName = $this->connection->parsePropertyName($id);
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @param array $data
	 * @return Atrox_Core_Data_Entity
	 */
	public function outputMap($data) {
		$entity = $this->createEntity();
		foreach ($this->properties as $property) {
			$name = $property->getName();

			if (array_key_exists($name, $data)) {
				$value = $data[$name];
			} else {
				$value = null;
			}
			$value = $property->formatForSourceOutput($data[$name]);
			$entity->add($name, $value);
		}

		return $entity;
	}

	public abstract function add(Atrox_Core_Data_Entity $entity);
	public abstract function read($id, $cached = true);
	public abstract function readByProperty($value, $propertyName, $cached = true);
	public abstract function update(Atrox_Core_Data_Entity $entity);
	public abstract function updateProperty(Atrox_Core_Data_Entity $entity, $propertyName, $newValue);
	public abstract function remove(Atrox_Core_Data_Entity $entity);
	public abstract function retrieve(Atrox_Core_Data_IFilter $filter = null);
	public abstract function count(Atrox_Core_Data_IFilter $filter = null);
}