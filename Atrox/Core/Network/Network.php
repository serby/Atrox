<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1085 $ - $Date: 2009-07-28 21:53:20 +0100 (Tue, 28 Jul 2009) $
 * @package Core
 * @subpackage Internet
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1085 $ - $Date: 2009-07-28 21:53:20 +0100 (Tue, 28 Jul 2009) $
 * @package Core
 * @subpackage Internet
 */
class Atrox_Core_Network_Network {

	/**
	 *
	 * @param $ipAddress
	 * @param $cidr
	 * @return unknown_type
	 */
	public static function isAddressInNetwork($ipAddress, $cidr) {
		if (strpos($cidr, "/")) {
			list ($net, $mask) = explode("/", $cidr);
		} else {
			$mask = 32;
			$net = $cidr;
		}
    return (ip2long ($ipAddress) & ~((1 << (32 - $mask)) - 1) ) == ip2long ($net);
	}

	/**
	 *
	 * @param $ipAddress
	 * @param $cidr
	 * @return unknown_type
	 */
	public static function isInList($ipAddress, $list) {
		foreach ($list as $item) {
			if (self::isAddressInNetwork($ipAddress, $item)) {
				return true;
			}
		}
		return false;
	}
}