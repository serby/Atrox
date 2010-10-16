<?php
/**
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
interface Atrox_Core_Data_IRelationalDatabaseConnection extends Atrox_Core_Data_IConnection{
	public function query($sql);
	public function prepare($sql);
	public function execute();
	public function fetch($result, $offset = -1);
}