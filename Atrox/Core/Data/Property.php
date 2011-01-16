<?php
/**
 * Properties of a Data Entity.
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1530 $ - $Date: 2010-06-11 17:49:57 +0100 (Fri, 11 Jun 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Property {

	const STORE_CREATE = 1;
	const STORE_UPDATE = 2;
	const STORE_ALWAYS = 3;
	const STORE_NEVER = 4;

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var string
	 */
	protected $description;

	/**
	 *
	 * @var mixed
	 */
	protected $default;

	/**
	 * List of possible values for this Property.
	 *
	 * @var array
	 */
	protected $list;

	/**
	 * When this Property should be stored.
	 *
	 * @var int
	 */
	protected $storage = self::STORE_ALWAYS;

	/**
	 *
	 * @var Atrox_Core_Data_IValidator
	 */
	protected $validators = array();

	/**
	 *
	 * @var array
	 */
	protected $inputFormatter;

	/**
	 *
	 * @param string $name
	 * @param int $type
	 * @param string $description
	 */
	public function __construct($name, $description = null) {
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * Return the name of this property
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get a textual description of this property.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * When should the data in this Property be stored to the datasource
	 *
	 * STORE_ALWAYS, STORE_NEVER, STORE_CREATE, STORE_UPDATE
	 *
	 * @param $storage
	 * @return Atrox_Core_Data_Property
	 */
	public function setStoreCondition($storage) {
		$this->storage = $storage;
		return $this;
	}

	/**
	 * How should this property be stored.
	 *
	 * @return int
	 */
	public function getStorage() {
		return $this->storage;
	}

	/**
	 * Does this property have the given storage type.
	 *
	 * @param int $storage
	 *
	 * @return bool
	 */
	public function hasStorage($storage) {
		return ($this->storage & $storage) == $storage;
	}

	/**
	 * Sets the default value for this property.
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function setDefault($value) {
		$this->default = $value;
		return $this;
	}

	/**
	 * Return the default value for this property
	 *
	 * @return mixed
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * Sets the list of possible values
	 *
	 * @param array List of possible values for this Property
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function setList(array $value) {
		$this->list = $value;
		return $this;
	}

	/**
	 * Returns the list of possible values
	 *
	 * @return array
	 */
	public function getList() {
		return $this->list;
	}

	/**
	 * Add
	 *
	 * @return Atrox_Core_Data_Property
	 */
	public function setInputFormatter(Atrox_Core_Data_IFormatter $formatter) {
		$this->inputFormatter = $formatter;
		return $this;
	}

	public function formatForInput($value) {
		if ($this->inputFormatter) {
			return $this->inputFormatter->format($value);
		} else {
			return $value;
		}
	}


	/**
	 * @return Atrox_Core_Data_Property The added Property
	 */
	public function addValidator(Atrox_Core_Data_IValidator $validator) {
		$this->validators[] = $validator;
		return $this;
	}

	/**
	 * Runs through the validates and returns an error of all the errors or an empty array if all validators pass.
	 *
	 * @param mixed $value
	 *
	 * @return array
	 */
	public function validate($value) {
		$errors = array();
		foreach ($this->validators as $validator) {
			$response = $validator->validate($value, $this);
			if ($response !== true) {
				$errors[] = $response;
			}
		}

		return $errors;
	}
}