<?php
/**
 * The does the work of adding, updating, deleting and fetching data from a adaptors datasource.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
interface Atrox_Core_Data_Adaptor_ICollectionDelegate {
	public function setConnection(Atrox_Core_Data_Adaptor_IConnection $connection);
	public function add(Atrox_Core_Data_Entity $entity);
	public function read($id, $cached = true);
	public function readByProperty($value, $propertyName, $cached = true);
	public function update(Atrox_Core_Data_Entity $entity);
	public function remove(Atrox_Core_Data_Entity $entity);
	public function retrieve(Atrox_Core_Data_IFilter $filter = null);
	public function count(Atrox_Core_Data_IFilter $filter = null);
}