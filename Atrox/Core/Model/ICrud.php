<?php
/**
 * Registry for storing global settings
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1339 $ - $Date: 2010-02-27 20:49:35 +0000 (Sat, 27 Feb 2010) $
 * @package Core
 */
interface Atrox_Core_Model_ICrud {

	public function create(Atrox_Core_Data_Entity $dataEntity);

	public function read($id);

	public function update(Atrox_Core_Data_Entity $dataEntity);

	public function delete(Atrox_Core_Data_Entity $dataEntity);

	public function retrieve(Atrox_Core_Data_IFilter $filter = null);

}