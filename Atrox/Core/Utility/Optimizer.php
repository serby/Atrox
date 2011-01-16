<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 * @subpackage Utility
 */

/**
 * Extra array functions
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 * @subpackage Utility
 */
class Atrox_Core_Utility_Optimizer {

	public static function minifyJavascript($source) {

	}

	/**
	 * Removes
	 *
	 * Based on cssmin by Joe Scylla
	 *
	 * @param string $source
	 * @return string
	 */
	public static function minifyCss($source) {
		$v = trim($source);
		$v = str_replace("\r\n", "\n", $v);
	  $search = array(
	  	"/\/\*[\d\D]*?\*\/|\t+/",
	  	"/\s+/",
	  	"/\}\s+/"
	 	);
	  $replace = array(null, " ", "}\n");
		$v = preg_replace($search, $replace, $v);
		$search = array(
			"/\\;\s/", 
			"/\s+\{\\s+/", 
			"/\\:\s+\\#/", 
			"/,\s+/i", 
			"/\\:\s+\\\'/i", 
			"/\\:\s+([0-9]+|[A-F]+)/i");
	  $replace = array(";", "{", ":#", ",", ":\'", ":$1");
	  $v = preg_replace($search, $replace, $v);
	  $v = str_replace("\n", null, $v);
	  return $v;
	}

	/**
	 *
	 * @param string $filePath
	 * @return string
	 */
	public static function minifyCssFile($filePath) {
		$contents = file_get_contents($filePath);
		return self::minifyCss($contents);
	}

	/**
	 * Parses a css file and replaces tokens with given values then returns
	 * minized css
	 *
	 * @param string $filePath File to process
	 * @param array $tokenValue Associative array where key is the token name and value 
	 * is the value to replace
	 * @return string
	 */
	public static function minifyCssAndReplaceTokens($filePath, $tokenValues) {
		$contents = file_get_contents($filePath, FILE_USE_INCLUDE_PATH);
		foreach ($tokenValues as $token => $value) {
			$contents = str_replace("{" . $token . "}", $value, $contents);
		}
		return self::minifyCss($contents);
	}
}