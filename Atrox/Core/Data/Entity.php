<?php
/**
 * Representation of a Entity of data (Like a record or a row)
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data
 */
abstract class Atrox_Core_Data_Entity {

	/**
	 * Where the data is actually stored
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Has there been changes since creation?
	 *
	 * @var bool
	 */
	protected $dirty = false;

	/**
	 * The Properties that make up this Entity
	 *
	 * @var Atrox_Core_Data_EntitySchema
	 */
	protected $schema;

	/**
	 * When the entity is constructed
	 */
	public function __construct() {
		$this->schema = $this->getSchema();
		$this->build();
	}

	/**
	 * You must override add all the properties you want this entity to have.
	 *
	 * A contact entity may look like this:
	 *
	 * <code>
	 *
	 * 	public static function setup() {
	 *		self::addProperty(new Atrox_Core_Data_Property_Integer("id"));
	 * 		self::addProperty(new Atrox_Core_Data_Property_String("name"));
	 * 		self::addProperty(new Atrox_Core_Data_Property_String("emailAddress"));
	 *		self::addProperty(new Atrox_Core_Data_Property_Date("dateCreated"));
	 * }
	 *
	 *</code>
	 *
	 * @throws Atrox_Core_Exception_MethodMustBeOverriddenException Only thrown if you don't override this placeholder.
	 */
	public static function getSchema() {
		throw new Atrox_Core_Exception_MethodMustBeOverriddenException("You must override this method");
	}

	/**
	 * Builds the entity with the properties defined in setup
	 *
	 * @return null
	 */
	protected function build() {
		foreach ($this->schema->getProperties() as $property) {
			$name = $property->getName();
			$value = $property->getDefault();
			$this->addValue($name, $value);
		}
	}

	/**
	 * Used to set the internal data without any checking.
	 *
	 * @param string $propertyName
	 * @param string $value
	 *
	 * @return Atrox_Core_Data_Entity
	 */
	protected function addValue($propertyName, $value) {
		$this->data[$propertyName] = $value;
		return $this;
	}

	/**
	 * Set the entity as dirty
	 *
	 * @see isDirty
	 *
	 * @param bool $dirty
	 *
	 * @return Atrox_Core_Data_Entity
	 */
	protected function setDirty($dirty) {
		$this->dirty = $dirty;
		return $this;
	}

	/**
	 * Is this entity dirty? i.e. one or more of the properties has changed value since it the creation.
	 *
	 * @return bool
	 */
	public function isDirty() {
		return $this->dirty;
	}

	/**
	 * Returns a property
	 *
	 * <code>
	 *
	 * 	self::addProperty(new Atrox_Core_Data_Property_String("name"));
	 *
	 * 	echo $this->name;
	 *
	 * </code>
	 *
	 * @param string $propertyName
	 *
	 * @return mixed
	 */
	public function __get($propertyName) {
		if (array_key_exists($propertyName, $this->data)) {
			return $this->data[$propertyName];
		} else {
			throw new Atrox_Core_Data_Exception_InvalidPropertyException("Unknown property '{$propertyName}'");
		}
	}

	/**
	 * Sets an property
	 *
	 * @param string $propertyName
	 * @param string $value
	 *
	 * @return Atrox_Core_Data_Entity
	 */
	public function __set($propertyName, $value) {
		if (array_key_exists($propertyName, $this->data)) {
			$property = $this->schema->getProperty($propertyName);
			$this->data[$propertyName] = $property->formatForInput($value);
			$response = $property->validate($value);
			if (count($response) > 0) {
				throw new Atrox_Core_Data_Exception_InvalidValueException(implode(",", $response));
			}
			$this->dirty = true;
			return $this;
		} else {
			throw new Atrox_Core_Data_Exception_InvalidPropertyException("Unknown property '{$propertyName}'");
		}
	}

	/**
	 * Sets the internal data of the record. Warning no checks are made to ensure it is of the correct structure
	 *
	 * @param array $data
	 * @param bool $dirty Should this record be considered dirty after setting the data
	 *
	 * @return Atrox_Core_Data_Entity
	 */
	protected function setData($data, $dirty = true) {
		$this->data = $data;
		$this->dirty = $dirty;
		return $this;
	}

	/**
	 * Is the property === null.
	 *
	 * @param string $propertyName
	 *
	 * @return mixed
	 */
	public function isNull($propertyName) {
		return $this->data[$propertyName] === null;
	}

	/**
	 * Formats this record to a new line separated format of <fieldName>: <fieldValue>\n
	 *
	 * @return string
	 */
	public function __toString() {
		$output = "";
		foreach ($this->data as $propertyName => $value) {
			$output .= $this->getDataSource()->getProperty($propertyName)->getDescription() . ": {$value}\n";
		}
		return $output;
	}

	/**
	 * Returns a JSON string representing this entity.
	 *
	 * @return string
	 */
	public function toJson() {
		return json_encode($this->toObject());
	}

	/**
	 * Creates a stdClass representing this entity.
	 *
	 * @return stdClass
	 */
	public function toObject() {
		return (object)$this->data;
	}

	/**
	 * Validates this entity and returns an array of errors.
	 *
	 * @return array A list of errors or an empty array if there have been no errors.
	 */
	function validate() {
		$errors = array();

		$properties = $this->schema->getProperties();
		foreach ($properties as $name => $property) {
			$response = $property->validate($this->{$name});
			if (count($response) > 0) {
				if (!isset($errors[$name])) {
					$errors[$name] = array();
				}
				$errors[$name][] = $response;
			}
		}
		return $errors;
	}
}