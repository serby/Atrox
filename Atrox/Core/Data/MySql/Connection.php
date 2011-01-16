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
class Atrox_Core_Data_MySql_Connection implements Atrox_Core_Data_IConnection {

	/**
	 *
	 * @var string
	 */
	protected $server;

	/**
	 *
	 * @var string
	 */
	protected $username;

	/**
	 *
	 * @var string
	 */
	protected $password;


	/**
	 *
	 * @var string
	 */
	protected $database;

	/**
	 *
	 * @var $resource
	 */
	protected $connection;

	public function __construct($server, $username, $password, $database) {
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	public function connect() {
		$this->connection = mysql_connect($this->server, $this->username, $this->password);
		mysql_select_db($this->database, $this->connection);
	}

	public function close() {
		mysql_close($this->connection);
	}

	public function query($query) {
		$result = mysql_query($sql, $this->connection);

		if (!$result) {
			if (preg_match("/^Table '(\S+)' doesn't exist/", $this->getLastError(), $matches)) {
				throw new Atrox_Core_Exception_NoSuchRelationException($this->getLastError());
			} elseif (preg_match("/^Duplicate entry '(\S+)' for key (.+)/", $this->getLastError(), $matches)) {
				throw new Atrox_Core_Data_Exception_DuplicateKeyException($this->getLastError());
			} elseif (preg_match("/^Column '(\S+)' cannot be null/", $this->getLastError(), $matches)) {
				throw new Atrox_Core_Exception_NullValueException($matches[1] . " can not be null");
			} else {
				throw new Exception($this->getLastError());
			}
		}
		return $result;
	}

	public function fetch($result, $offset=-1) {
		if ($offset == -1) {
			return mysql_fetch_array($result);
		} else {
			return mysql_fetch_array($result, $offset);
		}
	}

	public function getResultCount($result) {
		return mysql_num_rows($result);
	}

	#TODO: Consider removing this from proxy pattern due to unused $result - Dom Udall 09-08-18
	public function getAffectedCount($result) {
		return mysql_affected_rows($this->connection);
	}

	#TODO: Consider removing this from proxy pattern due to different variable needed ($sequence) - Dom Udall 09-08-18
	public function getLastId($sequence) {
		$sql = "SHOW TABLE STATUS LIKE '" . $sequence . "';";
		$data = mysql_fetch_row($this->query($sql));
		return $data[4];
	}

	public function getLastError() {
		return mysql_error($this->connection);
	}

	public function parseTable($value) {
		return "{$value}";
	}

	public function parseField($value) {
		return "{$value}";
	}

	public function parseValue($value) {
		return "'" . mysql_escape_string($value) . "'";
	}

	/**
	 *
	 * @param Atrox_Core_Data_Property$property
	 * @return string
	 */
	public function parseProperty(Atrox_Core_Data_Property $property) {
		return $this->parseTable($property->getDataSource()->getTableName()) . "." . $this->parseField($property->getName());
	}
}