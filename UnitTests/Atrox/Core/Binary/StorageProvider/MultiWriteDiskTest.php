<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");
//require_once("DiskTest.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Binary_StorageAdaptor_MultiWriteDiskTest extends PHPUnit_Framework_TestCase {

	 public function setup() {
		$this->temporaryFolder = "/tmp/UnitTest/MultiWriteDiskTest/";
		$this->invalidPath = $this->temporaryFolder . md5(uniqid(rand(), true));
		$this->writePaths = array(
			$this->temporaryFolder . md5(uniqid(rand(), true)),
			$this->temporaryFolder . md5(uniqid(rand(), true))
		);
		$this->readPath = $this->writePaths[rand(0, count($this->writePaths) - 1)];

		foreach($this->writePaths as $writePath) {
			mkdir($writePath, 0777, true);
		}

		$this->outputPath = $this->temporaryFolder . md5(uniqid(rand(), true));
		mkdir($this->outputPath, 0777, true);

		$this->filePath = dirname(realpath(__FILE__)) . "/../../../../../Support/TestResource/logo.png";
		$this->readPaths = array($this->temporaryFolder . md5(uniqid(rand(), true)));
	}

	 public function tearDown () {
		Atrox_Core_System_FileSystem::delete($this->temporaryFolder);
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testInvalidPath() {
		try {
			$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->invalidPath,
				$this->writePaths, "/resource/binary/");
		} catch (Atrox_Core_Exception_InvalidPathException $e) {}
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testListBucketReturnsNull() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertType("array", $diskStorageAdaptor->listBuckets());
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testNullBucket() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->setExpectedException("Atrox_Core_Exception_InvalidPathException", "No path given");
		$this->assertNull($diskStorageAdaptor->getBucket(null));
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testInvalidBucket() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->setExpectedException("Atrox_Core_Exception_InvalidPathException", "Invalid path: {$this->readPath}");
		$this->assertNull($diskStorageAdaptor->getBucket("DoesNotExist"));
	}

	/**
	 * @author Elliot Coad(Clock Limited) {@link mailto:elliot.coad@clock.co.uk elliot.coad@clock.co.uk }
	 */
	 public function testMakePublicBucket() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));

		foreach($this->writePaths as $path) {
			$this->assertTrue(is_dir($path . "/" . "MyFirstBucket"));
		}

		$this->assertEquals(array(), $diskStorageAdaptor->getBucket("MyFirstBucket"));
		$this->assertEquals(array("MyFirstBucket"), $diskStorageAdaptor->listBuckets());

		// Try to make the same bucket
		$this->assertFalse($diskStorageAdaptor->makeBucket("MyFirstBucket"));
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testGetBinaryInformation() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));
		$diskStorageAdaptor->putBinaryFile($this->filePath, "MyFirstBucket", "logo.png", "image/png");
		$this->assertEquals(array("logo.png"), $diskStorageAdaptor->getBucket("MyFirstBucket"));

		$fileBinary = new stdClass();
		$fileBinary->filename = "logo.png";
		$fileBinary->size = 5434;
		$fileBinary->mimeType = "image/png";

		$binaryInformation = $diskStorageAdaptor->getBinaryInformation("MyFirstBucket", "logo.png");
		$this->assertEquals($fileBinary, $binaryInformation);
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testGetBinary() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));
		$diskStorageAdaptor->putBinaryFile($this->filePath, "MyFirstBucket", "logo.png", "image/png");
		$diskStorageAdaptor->getBinary("MyFirstBucket", "logo.png", $this->outputPath . "/logo.png");
		$this->assertTrue(file_exists($this->outputPath . "/logo.png"));
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testDeleteBinary() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));
		$diskStorageAdaptor->putBinaryFile($this->filePath, "MyFirstBucket", "logo.png", "image/png");
		$diskStorageAdaptor->getBinary("MyFirstBucket", "logo.png", $this->outputPath . "/logo.png");
		$this->assertTrue(file_exists($this->outputPath . "/logo.png"));
		$diskStorageAdaptor->deleteBinary("MyFirstBucket", "logo.png");
		$this->setExpectedException("Atrox_Core_Binary_NoSuchBinaryException");
		$binaryInformation = $diskStorageAdaptor->getBinaryInformation("MyFirstBucket", "logo.png");
		$this->assertEquals(null, $binaryInformation);
		$this->assertEquals(array(), $diskStorageAdaptor->getBucket("MyFirstBucket"));
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testInvalidDeleteBinary() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");

		$this->setExpectedException("Atrox_Core_Binary_NoSuchBucketException", "Bucket 'MyFirstBucket' does not exist");

		$diskStorageAdaptor->deleteBinary("MyFirstBucket", "logo.png");

		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));

		$this->setExpectedException("Atrox_Core_Binary_NoSuchBinaryException");
		$diskStorageAdaptor->deleteBinary("MyFirstBucket", "logo.png");
	}

	/**
	 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
	 */
	 public function testGetBinaryUrl() {
		$diskStorageAdaptor = new Atrox_Core_Binary_StorageAdaptor_MultiWriteDisk($this->readPath,
			$this->writePaths, "/resource/binary/");
		$this->assertTrue($diskStorageAdaptor->makeBucket("MyFirstBucket"));
		$diskStorageAdaptor->putBinaryFile($this->filePath, "MyFirstBucket", "logo.png", "image/png");
		$this->assertEquals("/resource/binary/MyFirstBucket/logo.png", $diskStorageAdaptor->getBinaryUrl("MyFirstBucket", "logo.png"));
	}
}