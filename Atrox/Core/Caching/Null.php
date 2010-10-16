<?php
/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Cache
 */
class Atrox_Core_Caching_Null implements Atrox_Core_Caching_ICacheManager {

	/**
	 *
	 * @see Core/Cache/Atrox_Core_Caching_ICacheManager#set($key, $data, $tag, $expire)
	 */
	public function set($key, $data, $tag = false, $expire = false) {
	}

	public function get($key) {
		return false;
	}

	public function getWithoutPrefix($key) {
		return false;
	}

	public function start($key, $tag = false, $expire = false) {
		return true;
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

	public function getFileContents($file, $expire = null, $context = null) {
		return file_get_contents($file, 0, $context);
	}

	public function clearFileContents($filename) {
	}

	public function listContents($filter = null) {
	}
}