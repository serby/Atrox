<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1090 $ - $Date: 2009-07-29 23:38:09 +0100 (Wed, 29 Jul 2009) $
 */

/**
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1090 $ - $Date: 2009-07-29 23:38:09 +0100 (Wed, 29 Jul 2009) $
 * @package Core
 * @subpackage Data/MySql
 */
class Atrox_Core_Data_MySql_LoggingConnection extends Atrox_Core_Data_MySql_Connection {

	public function query($query) {
		$application = Atrox_Application_Application::getInstance();
		$result = parent::query($sql);
		$application->log($sql, "sql");
		$application->log("Error: " . mysql_error($this->connection), "sql");
		return $result;
	}
}