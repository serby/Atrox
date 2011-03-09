<?php
/**
 * @package Core
 * @subpackage Cache
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1339 $ - $Date: 2010-02-27 20:49:35 +0000 (Sat, 27 Feb 2010) $
 */

/**
 * Standard interface to be used by all Cache methods
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1339 $ - $Date: 2010-02-27 20:49:35 +0000 (Sat, 27 Feb 2010) $
 * @package Core
 * @subpackage Cache
 */
interface Atrox_Core_Caching_ICacheManager {

	/**
	 *
	 * @param string $key
	 * @param string $data
	 * @param string $tag
	 * @param string $expire
	 * @return void
	 */
	public function set($key, $data, $tag = false, $expire = false);

	/**
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getWithoutPrefix($key);

	/**
	 *
	 * @param string $key
	 * @param string $tag
	 * @return void
	 */
	public function start($key, $tag = false);

	/**
	 *
	 * @return void
	 */
	public function end();

	/**
	 *
	 * @return void
	 */
	public function clearAll();

	/**
	 *
	 * @param string $key
	 * @return void
	 */
	public function clear($key);

	/**
	 *
	 * @param string $key
	 * @return void
	 */
	public function clearWithoutPrefix($key);

	/**
	 *
	 * @param string $tag
	 * @return unknown_type
	 */
	public function clearTag($tag);

	/**
	 *
	 * @param string $filename
	 * @param string $expire
	 * @param string $context
	 * @return mixed
	 */
	public function getFileContents($filename, $expire = false, $context = null);

	/**
	 *
	 *
	 * @param $filename
	 * @return void
	 */
	public function clearFileContents($filename);


	/**
	 *
	 * @param string $filter
	 * @return array All the contents of the cache
	 */
	public function listContents($filter = null);

	/**
	 * Calls a function and caches the results
	 *
	 * @param string $function The function to call
	 * @param array $arguments
	 * @param int $expire When the cache expires
	 *
	 * @return mixed The result
	 */
	public function call($function, array $arguments, $tags = false, $expire = false);
}