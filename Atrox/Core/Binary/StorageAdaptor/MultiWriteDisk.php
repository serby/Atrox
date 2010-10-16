<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 953 $ - $Date: 2009-05-06 15:55:50 +0100 (Wed, 06 May 2009) $
 * @package Core
 */



/**
 * Allows multiple write locations for multi server implementations
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 953 $ - $Date: 2009-05-06 15:55:50 +0100 (Wed, 06 May 2009) $
 * @package Core
 */
class Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk extends Atrox_Core_Binary_StorageAdaptor_Disk {

	/**
	 *
	 * @var array
	 */
	protected $writePaths;

	/**
	 *
	 * @param string $path Local path where binarys are stored
	 * @param array $writePaths Array where to write to these should be remote paths
	 * @param string $sitePath Url to access the disk path
	 */
	public function __construct($path, $writePaths, $siteUrl) {
		$this->path = rtrim($path, "/");

		$this->checkPath($path);

		foreach ($writePaths as $writePath) {
			$this->writePaths[] = $writePath = rtrim($writePath, "/");
			$this->checkPath($writePath);
		}
		$this->siteUrl = rtrim($siteUrl, "/");
	}

	public function makeBucket($name, $accessControl = self::ACCESS_PUBLIC) {
		foreach ($this->writePaths as $writePath) {
			try {
				mkdir($writePath . "/" . $name, 0777, true);
			} catch(Exception $e) {
				return false;
			}
		}
		return true;
	}

	public function putBinaryFile($file, $bucketName, $uri, $mimeType = null, $accessControl = self::ACCESS_PUBLIC) {

		if (!file_exists($file)) {
			throw new Atrox_Core_Exception_NoSuchFileException();
			return false;
		}

		foreach($this->writePaths as $writePath) {
			copy($file, $writePath . "/" . $bucketName . "/" . $uri);
			$binaryPath = $writePath . "/" . $bucketName . "/" . $uri;
			$informationPath = $writePath . "/" . $bucketName . "/.info." . $uri;

			$binaryInformation = new stdClass();
			$binaryInformation->filename = basename($file);
			$binaryInformation->size = filesize($file);
			if ($mimeType) {
				$binaryInformation->mimeType = $mimeType;
			} else {
				$binaryInformation->mimeType = mime_content_type($file);
			}
			file_put_contents($informationPath, json_encode($binaryInformation));
		}

		return true;
	}

	public function deleteBinary($bucketName, $uri) {
		foreach ($this->writePaths as $writePath) {
			$binaryPath = $writePath . "/" . $bucketName . "/" . $uri;
			$informationPath = $writePath . "/" . $bucketName . "/.info." . $uri;
			try {
				if (unlink($binaryPath)) {
					unlink($informationPath = $writePath . "/" . $bucketName . "/.info." . $uri);
				}
			}	catch(Exception $e) {
				if (!is_dir($writePath . "/" . $bucketName)) {
					throw new Atrox_Core_Binary_NoSuchBucketException("Bucket '$bucketName' does not exist");
				} else {
					throw new Atrox_Core_Binary_NoSuchBinaryException();
				}
				return false;
			}
		}
		return true;
	}

	public function deleteBucket($bucketName) {
		try {
			foreach ($this->writePaths as $writePath) {
				Atrox_Core_System_FileSystem::delete($writePath . "/" . $bucketName);
			}
		} catch(Exception $e) {}
	}
}