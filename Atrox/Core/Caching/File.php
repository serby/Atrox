<?php
/**
 * Standard interface to be used by all Cache methods
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Cache
 */
class Atrox_Core_Caching_File implements Atrox_Core_Caching_ICacheManager {


	/**
	 * Where to read write cache
	 * @var string
	 */
	protected $writePath;

	public function __construct($writePath, $readPath) {

		if (!is_array($writePath)) {
			$writePath = array($writePath);
		}
		$this->writePath = $writePath;
	}

	public function set($key, $data, $tag = false, $expire = false) {
		foreach ($this->path as $path) {
			//write $path.$key = $data;
		}
	}

	public function get($key) {
		// read $this->$readPath . "/" . [$key];
	}

	public function getWithoutPrefix($key) {
		return false;
	}

	public function start($key, $tag = false) {

	}

	public function end() {

	}

	public function clearAll() {

	}

	public function clear($key) {

	}

	public function clearWithoutPrefix($key) {
	}


	public function clearTag($tag) {

	}

	public function getFileContents($filename, $expire = false, $context = null) {
	}

	public function clearFileContents($filename) {
	}

	public function listContents($filter = null) {
	}
}