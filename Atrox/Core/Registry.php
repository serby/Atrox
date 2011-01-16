<?php

/**
 * Registry for storing global settings
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1649 $ - $Date: 2010-07-21 14:02:50 +0100 (Wed, 21 Jul 2010) $
 * @package Core
 */
class Atrox_Core_Registry {

	/**
	 * Store for all the registry settings.
	 *
	 * @var array
	 */
	protected $registry;

	/**
	 * Gets a value from the Registry.
	 *
	 * @author Paul Serby <paul.serby@clock.co.uk>
	 * @param string $location Where to get the value from
	 * @return string The value stored in the give registry location
	 */
	public function get($location, $defaultValue = null) {
		if (isset($this->registry[$location])) {
			return $this->registry[$location];
		}
		return $defaultValue;
	}

	/**
	 * Sets a value in the Registry
	 * @author Paul Serby <paul.serby@clock.co.uk>
	 * @param string $location Where to store the value from
	 * @param string $value What to store
	 * @return string The value stored in the give registry location
	 */
	public function set($location, $value) {
		return $this->registry[$location] = $value;
	}
}