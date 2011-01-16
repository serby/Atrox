<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Data/PostgreSql
 */
class Atrox_Core_Data_PostgreSql_Connection implements Atrox_Core_Data_IConnection {

	/**
	 *
	 * @var string
	 */
	protected $connectionString;

	/**
	 *
	 * @var $resource
	 */
	protected $connection;

	public function __construct($connectionString) {
		$this->connectionString = $connectionString;
	}

	public function connect() {
		$this->connection = pg_pconnect($this->connectionString);
	}

	public function close() {
		pg_close($this->connection);
	}

	public function query($query) {

		if ($this->connection === null) {
			$this->connect();
		}
		try {
			$result = pg_query($this->connection, $query);
		} catch (Exception $e) {
			if (preg_match("/^pg_query\(\): Query failed: ERROR:  null value in column (\S+) violates not-null constraint/", $e->getMessage(), $matches)) {
				throw new Atrox_Core_Exception_NullValueException($matches[1] . " can not be null");
			} else if (preg_match("/^pg_query\(\): Query failed: ERROR:  duplicate key violates unique constraint/", $e->getMessage())) {
				throw new Atrox_Core_Data_Exception_DuplicateKeyException($e->getMessage());
			} else if (preg_match("/^pg_query\(\): Query failed: ERROR:  relation \"(\S+)\" does not exist/", $e->getMessage(), $matches)) {
				throw new Atrox_Core_Exception_NoSuchRelationException($e->getMessage());
			} else if (preg_match("/^pg_query\(\): Query failed: ERROR:  value (\S+) is out of range for type (\S+)/", $e->getMessage(), $matches)) {
				throw new Atrox_Core_Exception_OutOfRangeException($matches[1] . " is not within range of datatype '{$matches[2]}'");
			} else if (preg_match("/^pg_query\(\): Query failed: ERROR:  value too long for type (.+)/", $e->getMessage(), $matches)) {
				throw new Atrox_Core_Exception_TooLongException("Value is too long for datatype '{$matches[1]}'");
			} else {
				throw $e;
			}
		}
		return $result;
	}

	public function fetch($result, $offset=-1) {
		if ($offset == -1) {
			return pg_fetch_array($result);
		} else {
			return pg_fetch_array($result, $offset);
		}
	}

	public function getReturnCount($result) {
		return pg_num_rows($result);
	}

	public function getAffectedCount($result) {
		try {
			return pg_affected_rows($result);
		} catch (Exception $e) {
			return false;
		}
	}

	public function getLastId($sequence) {
		$sql = "select currval('" . $sequence . "'::text)";
		$data = $this->fetch($this->query($sql));
		return $data[0];
	}

	public function getLastError() {
		return pg_last_error($this->connection);
	}

	public function parseName($value) {
		return "\"{$value}\"";
	}

	public function parsePropertyName($name) {
		return "\"{$name}\"";
	}

	public function parseValue($value) {
		return "'" . pg_escape_string($this->connection, $value) . "'";
	}

	/**
	 *
	 * @param Atrox_Core_Data_Property $property
	 * @return string
	 */
	public function parseProperty(Atrox_Core_Data_Property $property) {
		return $this->parseTable($property->getDataSource()->getTableName()) . "." . $this->parseField($property->getName());
	}

	public function createSource($name, $id) {
		return new Atrox_Core_Data_PostgreSql_Source($name, $id, $this);
	}
}