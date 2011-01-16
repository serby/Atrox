<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 * @package Core
 * @subpackage Data/PostgreSql
 */
class Atrox_Core_Data_PostgreSql_LoggingConnection extends Atrox_Core_Data_PostgreSql_Connection {

	public function query($query) {
		Atrox_Core_ServiceLocator::getInstance()->getLogger()->log($query, "Connection", Atrox_Core_Logging_Logger::DEBUG);
		$result = parent::query($query);

		return $result;
	}
}