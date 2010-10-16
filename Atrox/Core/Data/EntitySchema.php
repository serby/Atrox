<?php
/**
 * Defines the structure of an entity.
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1500 $ - $Date: 2010-05-27 23:11:53 +0100 (Thu, 27 May 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_EntitySchema {

	/**
	 * The name of the property that uniquely defines this entity.
	 * @var string
	 */
	protected $idPropertyName = "id";

	/**
	 * The Properties that make up this Entity
	 *
	 * @var array of Atrox_Core_Data_Property
	 */
	protected $properties = array();

	/**
	 *
	 * @param string $propertyName
	 */
	public function setIdPropertyName($propertyName) {
		$this->idPropertyName = $propertyName;
	}

	/**
	 * Returns the name of the id property.
	 *
	 * @return string
	 */
	public function getIdPropertyName() {
		return $this->idPropertyName;
	}

	/**
	 * Called in setup to define the shape of this entity.
	 *
	 * @see setup
	 *
	 * @throws Atrox_Core_Data_Exception_DuplicatePropertyException If this entity already has the property
	 *
	 * @param Atrox_Core_Data_Property $property
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function addProperty(Atrox_Core_Data_Property $property) {
		$propertyName = $property->getName();
		if (isset($this->properties[$propertyName])) {
			throw new Atrox_Core_Data_Exception_DuplicatePropertyException(
				"'$propertyName' is already defined for this Entity");
		}
		$this->properties[$propertyName] = $property;
		return $property;
	}

	/**
	 * Returns the Atrox_Core_Data_Property if this entity has it defined.
	 *
	 * @throws Atrox_Core_Data_Exception_InvalidPropertyException If this entity doesn't have the given entity
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * Returns the Atrox_Core_Data_Property if this entity has it defined.
	 *
	 * @throws Atrox_Core_Data_Exception_InvalidPropertyException If this entity doesn't have the given entity
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function getProperty($propertyName) {
		if (isset($this->properties[$propertyName])) {
			return $this->properties[$propertyName];
		}
		throw new Atrox_Core_Data_Exception_InvalidPropertyException("'$propertyName' is not a Property of this Entity");
	}
}