<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Utility
 */

/**
 * Extra Object functions
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Core
 * @subpackage Utility
 */
class Atrox_Core_Utility_Object {

	/**
	 * Override a set of defaults
	 * Format of $source must be along the lines of...
	 * (object)array(
	 * 	 "a" => 1,
	 * 	 "b" => (object)array(
	 *     "c" => 2
	 *   )
	 * );
	 *
	 * @param object $source
	 * @param object $overrides
	 * @return array
	 */
	public static function override($source, $overrides) {
		foreach ($overrides as $key => $value) {
			if (array_key_exists($key, $source)) {
				$source->$key = $value;
			}
		}
		return $source;
	}

	public static function overrideRecursively($source, $overrides) {
		foreach ($overrides as $key => $value) {
			if (array_key_exists($key, $source)) {
				if (is_array($value)) {
					$source->$key = self::overrideRecursively($source->$key, $value);
				} else {
					$source->$key = $value;
				}
			}
		}
		return $source;
	}

	/**
	 *
	 * @param $object
	 * @return DOMDocument
	 */
	public static function toDomDocument(stdClass $object) {
		$domDocument = new DOMDocument();
		self::toDomNode($object, $domDocument, $domDocument);
		return $domDocument;
	}

	/**
	 *
	 * @param $object
	 * @param $parentNode
	 * @param $document
	 * @return DomNode
	 */
	protected static function toDomNode($object, DOMNode $parentNode, DOMDocument $document) {

		$newNode = false;

		foreach ($object as $name => $value) {

			if (is_numeric($name)) {
				$name = "value";
			}

			$childNode = $document->createElement($name);

			$newNode = $parentNode->appendChild($childNode);

			if (is_array($value) || $value instanceof stdClass) {
				$childNode = self::toDomNode($value, $childNode, $document);
			} else if (is_bool($value)) {
				$childNode->nodeValue = $value ? "true" : "false";
			} else {
				//TODO: Work out how to CDATA this value. Could be a problem with &
				$childNode->nodeValue = $value;
			}
		}
		return $newNode;
	}
}