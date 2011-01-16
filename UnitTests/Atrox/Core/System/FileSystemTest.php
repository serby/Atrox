<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_System_FileSystemTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {

		$this->invalidPath = array(
			"/tmp/" . md5(uniqid(rand(), true))
			//"ftp://unittest:unittest123@ftp.clock.co.uk/Atrox/5.0/Core/Data/System/FileSystem/" . md5(uniqid(rand(), true))
		);

		$this->path = array(
			"/tmp/" . md5(uniqid(rand(), true))
			//"ftp://unittest:unittest123@ftp.clock.co.uk/Atrox/5.0/Core/Data/System/FileSystem/" . md5(uniqid(rand(), true))
		);

		$this->filename = array();

		foreach ($this->path as $path) {
			mkdir($path, 0777, true);
			$this->filename[] = $filename = $path . "/file-" .  md5(uniqid(rand(), true));
			file_put_contents($filename, "Test File");
		}
	}

	 public function tearDown() {
		foreach ($this->path as $path) {

			if (is_dir($path)) {
				Atrox_Core_System_FileSystem::delete($path);
			}
		}
	}

	 public function testDeleteFile() {
		foreach ($this->filename as $filename) {
			$this->assertTrue(file_exists($filename), "File does not exist: " . $filename);
			Atrox_Core_System_FileSystem::delete($filename);
			$this->assertFalse(file_exists($filename), "File still exists: " . $filename);
		}
	}

	 public function testDeleteValidDirectory() {
		foreach ($this->path as $path) {
			$this->assertTrue(is_dir($path), "Directory does not exist: " . $path);
			Atrox_Core_System_FileSystem::delete($path);
			$this->assertFalse(is_dir($path), "Directory still exists: " . $path);
		}
	}

	 public function testDeleteInvalidDirectory() {
		foreach ($this->invalidPath as $invalidPath) {
			try {
				Atrox_Core_System_FileSystem::delete($invalidPath);
				$this->fail("Invalid Path Exception not triggered.");
			} catch (Atrox_Core_Exception_InvalidPathException $e) {}
		}
	}

	 public function testDeleteValidPathWithHiddenFiles() {
		$this->markTestSkipped();
		foreach ($this->path as $path) {
			$this->assertTrue(is_dir($path), "Directory does not exist: " . $path);
			//TOTO
			file_put_contents($path . "/.hiddenfile", "Test File");
			Atrox_Core_System_FileSystem::delete($path);
			$this->assertFalse(is_dir($path), "Directory does exists: " . $path);
		}
	}
}