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
class Atrox_Core_Data_Adaptor_Collection {

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
	 * @var Atrox_Core_Data_Adaptor_ICollectionDelegate
	 */
	protected $collectionDelegate;

	/**
	 *
	 * @param Atrox_Core_Data_IConnection $connection
	 */
	public function __construct($name, Atrox_Core_Data_EntitySchema $scheme,
		Atrox_Core_Data_Adaptor_ICollectionDelegate $collectionDelegate) {

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
}