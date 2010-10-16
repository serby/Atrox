<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Dataset implements Atrox_Core_Data_IPageable {

	/**
	 *
	 * @var array
	 */
	protected $entitys = array();
	protected $order = null;
	protected $pageSize = null;
	protected $pageCount = null;

	/**
	 *
	 * @var resource
	 */
	protected $result;

	/**
	 *
	 * @var Atrox_Core_Data_Source
	 */
	protected $dataSource;

	/**
	 *
	 * @var Atrox_Core_Data_IFilter
	 */
	protected $filter;


	protected $pageLength;
	protected $currentPage;
	protected $entityPointer;

	/**
	 *
	 * @param $result
	 * @param Atrox_Core_Data_Source $dataSource
	 * @param Atrox_Core_Data_IFilter $filter
	 * @return void
	 */
	public function __construct($result, Atrox_Core_Data_Source $dataSource, Atrox_Core_Data_IFilter $filter = null) {
		$this->result = $result;
		$this->dataSource = $dataSource;
		$this->filter = $filter;
	}

	/**
	 *
	 * @return Atrox_Core_Data_Entity
	 */
	public function getNext($offset = -1) {
		if ($data = $this->dataSource->getConnection()->fetch($this->result, $offset)) {
			return $this->dataSource->outputMap($data);
		}
		return false;
	}

	/**
	 *
	 * @return Atrox_Core_Data_Source
	 */
	public function getDataSource() {
		return $this->dataSource;
	}

	/**
	 *
	 * @return int
	 */
	public function getCount() {
		return $this->dataSource->getConnection()->getRowCount($this->result);
	}

	/**
	 *
	 * @return int
	 */
	public function getPageCount() {
		return $this->pageCount;
	}

	/**
	 * Converts a recordSet into an array of its records
	 *
	 * @author Robert Arnold <robert.arnold@clock.co.uk>
	 * @return array
	 */
	public function toArray() {
		$entitys = array();
		while ($entity = $this->getNext()) {
			$entitys[$entity->getId()] = $entity;
		}
		return $entitys;
	}

	/**
	 * Creates an indexed array with the values of a given PropertyName from a recordSets records
	 *
	 * @author Robert Arnold <robert.arnold@clock.co.uk>
	 * @param $propertyName
	 * @return array
	 */
	public function getValueArray($propertyName) {
		$entitys = array();
		while ($entity = $this->getNext()) {
			$entitys[$entity->getId()] = $entity->get($propertyName);
		}
		return $entitys;
	}
}