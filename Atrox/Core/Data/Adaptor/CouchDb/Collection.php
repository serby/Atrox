<?php
/**
 * MongoDB collection
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_Data_Adaptor_MongoDb_Collection extends Atrox_Core_Data_Adaptor_Collection {

	public abstract function add(Atrox_Core_Data_Entity $entity) {

	}

	public abstract function read($id, $cached = true) {

	}

	public abstract function readByProperty($value, $propertyName, $cached = true) {

	}

	public abstract function update(Atrox_Core_Data_Entity $entity) {

	}

	public abstract function updateProperty(Atrox_Core_Data_Entity $entity, $propertyName, $newValue) {

	}

	public abstract function remove(Atrox_Core_Data_Entity $entity) {

	}

	public abstract function retrieve(Atrox_Core_Data_IFilter $filter = null) {

	}

	public abstract function count(Atrox_Core_Data_IFilter $filter = null) {

	}
}