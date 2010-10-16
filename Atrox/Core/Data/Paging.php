<?php
/**
 *
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1202 $ - $Date: 2010-01-13 18:59:57 +0000 (Wed, 13 Jan 2010) $
 */

/**
 * Functions for paging a data source
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1202 $ - $Date: 2010-01-13 18:59:57 +0000 (Wed, 13 Jan 2010) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Paging implements Iterator {
	/**
	 * A pageable data source.
	 * @var Atrox_Core_Data_IPageable
	 */
	protected $pageableInterface;

	/**
	 * The page to get.
	 * @var integer
	 */
	protected $page;

	/**
	 * The length of the page.
	 * @var integer
	 */
	protected $pageLength;

	/**
	 * The current item from the data source.
	 * @var mixed
	 */
	protected $currentItem;

	/**
	 * Internal pointer to establish the current location within the page.
	 * @var unknown_type
	 */
	protected $internalPointer = 0;

	public function __construct(Atrox_Core_Data_IPageable $pageableInterface, $page, $pageLength) {
		$this->pageableInterface = $pageableInterface;
		$this->setPageLength($pageLength);
		$this->setPage($page);
	}

	protected function setPageLength($pageLength) {
		if ($pageLength == 0) {
			$this->pageLength = $this->pageableInterface->getCount();
		} else if ($pageLength > 0) {
			$this->pageLength = $pageLength;
		} else {
			throw new Atrox_Core_Exception_InvalidOffsetException("Cannot offset pages by negative length specified (" . $pageLength . ")");
		}
	}

	protected function setPage($page) {
		if ($page < 0) {
			$this->page = $this->getTotalPages() + 1 + $page;
		} else if ($page == 0) {
			$this->page = 1;
		} else {
			$this->page = $page;
		}
	}

	public function getTotalRecords() {
		return $this->pageableInterface->getCount();
	}

	public function getPage() {
		return $this->page;
	}

	public function getPageLength() {
		return $this->pageLength;
	}

	public function getTotalPages() {
		if ($this->pageLength <= 0) {
			return 1;
		}
		return max(ceil($this->pageableInterface->getCount() / $this->pageLength), 1);
	}

	public function current() {
		return $this->currentItem;
	}

	public function next() {
		$this->currentOffset = ($this->page - 1) * $this->pageLength + $this->internalPointer;
		if (($this->currentOffset < $this->pageableInterface->getCount()) &&
				($this->currentOffset >= 0)) {
			if ($this->currentItem = $this->pageableInterface->getNext($this->currentOffset)) {
				$this->internalPointer++;
			}
		}
		return $this->currentItem;
	}

	public function key() {
		return $this->internalPointer;
	}

	public function valid() {
		if (
			($this->internalPointer <= $this->pageLength) &&
			$this->currentItem &&
			($this->currentOffset < $this->pageableInterface->getCount())
			) {
			return true;
		}
		return false;
	}

	public function rewind() {
		$internalPointer = 0;
		$this->next();
	}
}