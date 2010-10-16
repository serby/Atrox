<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */

/**
 * Basic Authorization Provider - Will always return true
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
class Atrox_Application_Security_AuthorizationProvider_Basic implements Atrox_Application_Security_IAuthorizationProvider {

	/**
	 * Will always return true
	 *
	 * @see Atrox_Application_Security_IAuthorizationProvider#isAllowed($resource, $action)
	 */
	public function isAllowed($uniqueIdentifier, $type, $resource, $action) {

		return true;
	}

	public function getResources() {
		return array();
	}

	public static function getRolesForIdentity($uniqueIdentifier, $type) {

	}
}