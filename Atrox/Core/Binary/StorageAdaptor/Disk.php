<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 953 $ - $Date: 2009-05-06 15:55:50 +0100 (Wed, 06 May 2009) $
 * @package Core
 */



/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 953 $ - $Date: 2009-05-06 15:55:50 +0100 (Wed, 06 May 2009) $
 * @package Core
 */
class Atrox_Core_Binary_StorageAdaptor_Disk implements Atrox_Core_Binary_IStorageAdaptor {

	protected $path;

	protected $siteUrl;

	/**
	 *
	 * @param string $path Local path where binarys are stored
	 * @param string $sitePath Url to access the disk path
	 */
	public function __construct($path, $siteUrl) {
		$this->path = rtrim($path, "/");
		$this->siteUrl = rtrim($siteUrl, "/");

		$this->checkPath($this->path);
	}

	protected function checkPath($path) {
		clearstatcache();
		if (!is_dir($path . "/")) {
			throw new Atrox_Core_Exception_InvalidPathException("Invalid path: " . $path);
			return false;
		}
		return true;
	}

	/**
	 *
	 * @return
	 */
	public function listBuckets() {
		$buckets = array();

		foreach (new DirectoryIterator($this->path) as $fileInformation) {
	    if ($fileInformation->isDot()) {
	    	continue;
	    }
	    if ($fileInformation->isDir()) {
	    	$buckets[] = $fileInformation->getFileName();
	    }
		}
		return $buckets;
	}

	public function makeBucket($name, $accessControl = self::ACCESS_PUBLIC) {
		try {
			$path = $this->path . "/" . $name;
			if (!is_dir($path)) {
				return mkdir($this->path . "/" . $name, 0777, true);
			} else {
				return false;
			}
		} catch(Exception $e) {}
		return false;
	}

	public function getBucket($name) {
		if (empty($name)) {
			throw new Atrox_Core_Exception_InvalidPathException("No path given");
		}
		$path = $this->path . "/" . $name;
		if (!$this->checkPath($path)) {
			return false;
		}
		$binarys = array();
		foreach (new DirectoryIterator($path) as $fileInformation) {
			$filename = $fileInformation->getFileName();
	    if ($fileInformation->isDot() || substr($filename, 0, 5) == ".info") {
	    	continue;
	    }
	    if ($fileInformation->isFile()) {
	    	$binarys[] = $filename;
	    }
		}
		return $binarys;
	}

	public function putBinaryFile($file, $bucketName, $uri, $mimeType = null, $accessControl = self::ACCESS_PUBLIC) {

		if (!file_exists($file)) {
			throw new Atrox_Core_Exception_NoSuchFileException();
			return false;
		}
		$bucketParts = explode("/", $bucketName);
		$path = $this->path;
		foreach ($bucketParts as $part) {
			$path .= "/" . $part;
			if (!file_exists($path) && !is_dir($path)) {
				mkdir($path, 0777);
			}
		}
		$binaryPath = $this->path . "/" . $bucketName . "/" . $uri;
		$informationPath = $this->path . "/" . $bucketName . "/.info." . $uri;
		copy($file, $this->path . "/" . $bucketName . "/" . $uri);
		$binaryInformation = new stdClass();
		$binaryInformation->filename = basename($file);
		$binaryInformation->size = filesize($file);
		if ($mimeType) {
			$binaryInformation->mimeType = $mimeType;
		} else {
			$binaryInformation->mimeType = mime_content_type($file);
		}

		file_put_contents($informationPath, json_encode($binaryInformation));

		return true;
	}

	public function getBinaryInformation($bucketName, $uri) {
		clearstatcache();
		$binaryInformationPath = $this->path . "/" . $bucketName . "/.info." . $uri;
		try {

			$content = file_get_contents($binaryInformationPath);
			if ($content == null) {
				throw new Exception();
			}

			$binaryInformation = json_decode($content);
		}	catch(Exception $e) {
			throw new Atrox_Core_Binary_NoSuchBinaryException();
			return false;
		}

		return $binaryInformation;
	}

	public function getBinary($bucketName, $uri, $saveTo = null) {
		$binaryPath = $this->path . "/" . $bucketName . "/" . $uri;

		if (!file_exists($binaryPath)) {
			throw new Atrox_Core_Exception_NoSuchFileException();
			return false;
		}

		return copy($binaryPath, $saveTo);
	}

	public function getBinaryUrl($bucketName, $uri) {
		return $this->siteUrl . "/{$bucketName}/{$uri}";
	}

	public function deleteBinary($bucketName, $uri) {
		$binaryPath = $this->path . "/" . $bucketName . "/" . $uri;
		$informationPath = $this->path . "/" . $bucketName . "/.info." . $uri;

		if ((file_exists($binaryPath)) && (unlink($binaryPath))) {
			return unlink($informationPath = $this->path . "/" . $bucketName . "/.info." . $uri);
		} else {
			if (!is_dir($this->path . "/" . $bucketName)) {
				throw new Atrox_Core_Binary_NoSuchBucketException("Bucket '$bucketName' does not exist");
			} else {
				throw new Atrox_Core_Binary_NoSuchBinaryException();
			}
		}

		return false;
	}

	public function deleteBucket($bucketName) {
		try {
			Atrox_Core_System_FileSystem::delete($this->path . "/" . $bucketName);
		} catch(Exception $e) {}
	}
}