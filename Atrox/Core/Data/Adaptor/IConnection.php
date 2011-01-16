<?php
/**
 *
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
interface Atrox_Core_Data_Adaptor_IConnection {
	public function connect();
	public function close();
	public function getAffectedCount($result);
	public function getLastError();
	public function getLastId($result);
	public function parseProperty(Atrox_Core_Data_Property $property);
	public function parseName($value);
	public function parsePropertyName($name);
	public function parseValue($value);
	public function getReturnCount($result);
}