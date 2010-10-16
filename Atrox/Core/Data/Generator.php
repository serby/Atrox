<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

 /**
 * Create Test Data
 *
 * @author Robert Arnold <robert.arnold@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_Generator {

	const TYPE_FIRSTNAME = 1;
	const TYPE_LASTNAME = 2;
	const TYPE_ADDRESSLINE1 = 3;
	const TYPE_CITY = 4;
	const TYPE_TITLE = 5;
	const TYPE_EMAILADDRESS = 6;
	const TYPE_POSTCODE = 7;

	// Sample Data
	static $types = array (
		self::TYPE_FIRSTNAME => array (
			"Adam", "Bob", "Charles", "Dave", "Tom", "Alan", "Paul", "Sarah",
			"Sandy", "Sally", "James", "Jim", "John", "Hikaru", "Pawel", "Spock"
		),
		self::TYPE_LASTNAME => array(
			"Adams", "Baldwin", "Connor", "Smith", "Grant", "McCoy", "Kirk", "Uhura",
			"Scott", "Chekov", "Sulu", "Picard", "Troy", "ONeil", "Jackson", "Carter"
		),
		self::TYPE_ADDRESSLINE1 => array(
		  "First St", "Second St", "Third St", "Fourth St", "Fifth St",
	    "Sixth Ave", "Seventh Ave", "Eigth Ave", "Ninth Ave", "Tenth Ave",
		  "Oak Court", "Starle Close", "Regency Place", "Canterbury Road",
			"Hazelmere Park", "Random Road"
		),
		self::TYPE_CITY => array(
		  "Aylesbury", "London", "Oxford", "Stevenage", "Liverpool", "Canterbury",
			"Margate", "Ash", "Sandwich", "Deal", "Dover", "Birchington", "Tintagel",
			"Ashford", "Hearn Bay", "Whitstable", "Sittingbourne", "Faversham", "Cliftonville",
			"Wingham", "Clun"
		),
		self::TYPE_TITLE => array(
			"Mr", "Mrs", "Miss", "Ms", "Dr"
		),
		self::TYPE_EMAILADDRESS => array(
			"testa", "testb", "testc", "testd"
		)
	);

	public static function getTestData($type) {
		switch ($type) {
			case self::TYPE_EMAILADDRESS:
				$testDataCount = count(self::$types[$type]) - 1;
				$returnData = self::$types[$type][rand(0, $testDataCount)] . rand(0,10000) . "@clock.co.uk";
				break;
			case self::TYPE_TITLE:
				$testDataCount = count(self::$types[$type]) - 1;
				$returnData = self::$types[$type][rand(0, $testDataCount)];
				break;
			case self::TYPE_POSTCODE:
				$returnData = (rand(0, 5) == 0 ? "" : chr(rand(65, 90))). chr(rand(65, 90)) . rand(1, 20) . " " .
				 rand(1, 9) . chr(rand(65, 90)) . chr(rand(65,  90));
				break;
			default:
				$testDataCount = count(self::$types[$type]) - 1;
				$returnData = self::$types[$type][rand(0, $testDataCount)] . rand(0,10000);
		}
		return $returnData;
	}

	public static function generate($dataTypes, $count = 100) {
		$testData = array();
		foreach ($dataTypes as $dataType) {
			for ($i = 0; $i < $count; $i++) {
				$testData[$dataType][] = self::getTestData($dataType);
			}
		}
		return $testData;
	}
}