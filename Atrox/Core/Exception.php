<?php

/**
 * Exception to throw when an offset is invalid
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 */
class Atrox_Core_Exception extends Exception {


	/**
	 * This uniquly identifies this type of exception. Users can report this error to help diagnostics.
	 *
	 * @param Exception $exception
	 */
	public static function makeHash(Exception $exception) {
		$hashtrace = $exception->getTrace();
		$hashtrace["File"] = $exception->getFile();
		$hashtrace["Line"] = $exception->getLine();
		$hashtrace["Code"] = $exception->getCode();
		$stopat = array("require", "include", "require_once", "include_once");
		for ($i = 0; $i < count($hashtrace); $i++) {
			if (isset($hashtrace[$i]["function"]) && in_array($hashtrace[$i]["function"], $stopat)) {
				$hashtrace = array_slice($hashtrace, 0, $i);
				break;
			}
			unset($hashtrace[$i]["args"], $hashtrace[$i]["object"]);
		}
 		return sprintf("%08X", crc32(print_r($hashtrace, true)));
	}
}