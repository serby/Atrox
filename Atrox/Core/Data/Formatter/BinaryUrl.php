<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision$ - $Date$
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision$ - $Date$
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_BinaryUrl implements Atrox_Core_Data_IFormatter {

	/**
	 *
	 * @param Atrox_Core_Binary_IStorageAdaptor
	 */
	protected $storageProvider;

	/**
	 * @var string
	 */
	protected $bucket;

	/**
	 *
	 * @param Atrox_Core_Binary_IStorageAdaptor $storageProvider
	 * @return unknown_type
	 */
	public function __construct($bucket, Atrox_Core_Binary_IStorageAdaptor $storageProvider) {
		$this->bucket = $bucket;
		$this->storageProvider = $storageProvider;
	}

	public function format($value) {
		if ($value == "") {
			return false;
		} else {
			return $this->storageProvider->getBinaryUrl($this->bucket, $value);
		}
	}
}
