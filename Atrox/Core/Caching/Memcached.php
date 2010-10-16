<?php

/**
 * @package Core
 * @subpackage Cache
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Cache
 */
class Atrox_Core_Caching_Memcached implements Atrox_Core_Caching_ICacheManager {

	/**
	 *
	 * @var memcache
	 */
	protected $memcache;

	/**
	 *
	 * @var array
	 */
	protected $startStack;

	/**
	 * Prefix all the memcache keys with the following
	 * @var string
	 */
	protected $keyPrefix;

	/**
	 *
	 * @param $keyPrefix
	 * @return unknown_type
	 */
	public function __construct($keyPrefix = "") {
		$this->memcache = new Memcache();
		$this->keyPrefix = $keyPrefix . ":";
	}

	/**
	 *
	 * @param string $server
	 * @param int $port
	 * @return Atrox_Core_Caching_memcache
	 */
	public function addServer($server = "127.0.0.1", $port = 11211) {
		$this->memcache->addServer($server, $port);
		return $this;
	}

	/**
	 *
	 * @see Core/Cache/Atrox_Core_Caching_ICacheManager#set($key, $data, $tag, $expire)
	 */
	public function set($key, $data, $tag = false, $expire = false) {
		$this->memcache->set($this->keyPrefix . $key, $data, false, $expire);
		if ($tag) {
			if (!$tagIndex = $this->memcache->get($this->keyPrefix . "__AtroxTagIndex")) {
				$tagIndex = array();
			}
			$tagIndex[$tag][] = $key;
			$this->memcache->set($this->keyPrefix . "__AtroxTagIndex", $tagIndex);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#get($key)
	 */
	public function get($key) {
		return $this->memcache->get($this->keyPrefix . $key);
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#get($key)
	 */
	public function getWithoutPrefix($key) {
		return $this->memcache->get($key);
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#start($key, $tag)
	 */
	public function start($key, $tag = false, $expire = false) {
		if ($content = $this->memcache->get($this->keyPrefix . $key)) {
			echo $content;
			return false;
		} else {
			$this->startStack[] = array($key, $tag, $expire);
			ob_start(array($this, "writeOutputBufferToCache"));
		}
		return true;
	}

	/**
	 *
	 * @param $buffer
	 * @return unknown_type
	 */
	public function writeOutputBufferToCache($buffer) {
		$details = array_pop($this->startStack);
		$this->set($details[0], $buffer, $details[1], $details[2]);
		return $buffer;
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#end()
	 */
	public function end() {
		ob_flush();
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#clearAll()
	 */
	public function clearAll() {
		$this->memcache->flush();
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#clear($key)
	 */
	public function clear($key) {
		$this->memcache->delete($this->keyPrefix . $key);
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#get($key)
	 */
	public function clearWithoutPrefix($key) {
		$this->memcache->delete($key);
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#clearTag($tag)
	 */
	public function clearTag($tag) {
		if ($tagIndex = $this->memcache->get($this->keyPrefix . "__AtroxTagIndex")) {
			if (isset($tagIndex[$tag])) {
				foreach ($tagIndex[$tag] as $key) {
					$this->clear($key);
					unset($tagIndex[$tag]);
				}
			}
			$this->memcache->set($this->keyPrefix . "__AtroxTagIndex", $tagIndex);

		} else {
			$this->clearAll();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#getFileContents($filename, $expire, $context)
	 */
	public function getFileContents($filename, $expire = false, $context = null) {
		$key = $this->keyPrefix . "__File:" . md5($filename);
		if ($data = $this->memcache->get($key)) {
 			return $data;
		} else {
			try {
 				$data = @file_get_contents($filename, 0, $context);
 				$this->memcache->set($key, $data, false, $expire);
			} catch(Exception $e) {
				echo $e->getMessage();
				throw new Atrox_Core_Exception_NoSuchFileException("'{$filename}' does not exist");
			}
		}
		return $data;
	}

	/**
	 * (non-PHPdoc)
	 * @see Atrox/Core/Cache/Atrox_Core_Caching_ICacheManager#clearFileContents($filename)
	 */
	public function clearFileContents($filename) {
		$key = "__File:" . md5($filename);
		$this->clear($key);
	}

	public function listContents($filter = null) {
    $list = array();
    $allSlabs = $this->memcache->getExtendedStats("slabs");
    $items = $this->memcache->getExtendedStats("items");
    foreach ($allSlabs as $server => $slabs) {
    	foreach ($slabs as $slabId => $slabMeta) {
    		$cdump = $this->memcache->getExtendedStats("cachedump", (int)$slabId);
    		foreach ($cdump as $server => $entries) {
    			if($entries) {
    				foreach($entries as $eName => $eData) {
    					$list[] = $eName;
    				}
    			}
    		}
    	}
    }
    sort($list);
    return $list;
	}
}