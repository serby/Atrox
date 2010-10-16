<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage System
 */

/**
 * Global Error Handler
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage System
 */
class Atrox_Core_System_FileSystem {

	public static function delete($path) {
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (($file = readdir($handle)) !== false) {
					if ($file != "." && $file != "..") {
						self::delete($path . "/" . $file);
						if (is_dir($path . "/" . $file)) {
							rmdir($path . "/" . $file);
						}
					}
				}
				closedir($handle);
				rmdir($path);
			}
		} else if (file_exists($path)) {
			unlink($path);
		} else {
			throw new Atrox_Core_Exception_InvalidPathException("'" . $path . "' is not a valid path");
		}
	}

	/**
	 * Create a new directory path, creating sub directories if they don't exist
	 *
	 * @param string Directory path to create
	 * @param int Mode to apply on the directory
	 * @return booln Return true on success, else false
	 */
	public function mkdir($path, $mode = null, $recursive = false) {

		$return = true;

		if ($recursive) {
			$directories = explode("/", $path);
			$currentPath = "";
			foreach ($directories as $part) {
				$currentPath .= $part . "/";
				if ((!file_exists($currentPath)) && (!is_dir($currentPath)) && (mb_strlen($currentPath) > 0)) {
					echo $currentPath;
					$return = $return && mkdir($currentPath, $mode);
				}
			}
		} else {
			$return =  mkdir($path, $mode);
		}
		return $return;
	}
}