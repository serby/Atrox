<?php
//
//require_once("FirePHPCore/FirePHP.class.php");
//
//$firephp = FirePHP::getInstance(true);
//
//$var = array('i'=>10, 'j'=>20);
//
//$firephp->log($var, 'Iterators');

class DataEntity {

	private $data;

	/**
	 *
	 * @var DataMapper
	 */
	private $dataMapper;

	public function __construct(DataMapper $dataMapper) {
		$this->dataMapper = $dataMapper;
	}

	public function setData($data) {
		$this->data = $data;
	}

	/**
	 *
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function __get($name) {
		$name = ucfirst($name);
		if ($this->dataMapper->hasProperty($name)) {
			return $this->data[$name];
		} else {
			throw new Exception("DataEntity does not have a property '{$name}'");
		}
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}
}


class DataMapper {
	private $properties = array();
	protected function addProperty(Property $property) {
		if ($this->hasProperty($property->getName())) {
			throw new Exception("Property already defined");
		}
		$this->properties[$property->getName()] = $property;
		return $this;
	}

	public function hasProperty($name) {
		return array_key_exists($name, $this->properties);
	}

	public function getProperty($name) {
		return $this->properties[$name];
	}

	/**
	 *
	 * @return DataEntity
	 */
	public function makeNew() {
		$data = array();
		foreach ($this->properties as $key => $value) {
			$data[$key] = new MapValue($value);
		}
		$dataEntity = $this->createDataEntity();
		$dataEntity->setData($data);
		return $dataEntity;
	}

	public function createDataEntity() {
		return new DataEntity($this);
	}
}

class Property {
	const TYPE_INTEGER = 1;
	const TYPE_REAL = 2;
	const TYPE_BOOLEAN = 3;
	const TYPE_STRING = 4;

	private $name;
	private $type;

	public function __construct($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}

	public function getName() {
		return $this->name;
	}

	public function getType() {
		return $this->type;
	}
}


class TicketMapper extends DataMapper {

	/**
	 * @var Atrox_Core_Data_Source
	 */
	private $dataSource;

	public function setup() {
		$this
		->addProperty(new Property("Start", Property::TYPE_STRING))
		->addProperty(new Property("Destination", Property::TYPE_STRING))
		->addProperty(new Property("Price", Property::TYPE_REAL));
	}

	public function listAll() {
		$this->dataSource->retrieve();
	}

	public function buy() {

	}
}

class Ticket extends DataEntity {

	public function __construct() {
	}
}

class MapValue {

	protected $property;

	/**
	 *
	 * @param Property $property
	 */
	public function __construct(Property $property) {
		$this->property = $property;
	}

	public function set($value) {
		$this->value = value;
	}

	public function format() {

	}

}

$ticketManager = new TicketMapper();
$ticketManager->setup();

$ticket = $ticketManager->makeNew();


var_dump($ticket);
//var_dump($ticket->start->value);
var_dump($ticket->start->format());
