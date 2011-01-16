<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1487 $ - $Date: 2010-05-20 17:42:53 +0100 (Thu, 20 May 2010) $
 * @package Application
 * @subpackage Security
 */

/**
 * Authorization Provider - What you are allowed to do
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1487 $ - $Date: 2010-05-20 17:42:53 +0100 (Thu, 20 May 2010) $
 * @package Application
 * @subpackage Security
 */
interface Atrox_Application_Security_IAuthorizationProvider {

	/**
	 * Is the currently authenticated identity allowed to access the given resource
	 *
	 * @param string $uniqueIdentifier
	 * @param int $uniqueIdentifierType
	 * @param string $resource
	 * @param string $action
	 * @return bool
	 */
	public function isAllowed($uniqueIdentifier, $type, $resource, $action);

	/**
	 * Get the list of accessable resources
	 *
	 * @return array
	 */
	public function getResources();

	//TODO: This function is not implemented in AuthorizationProvider
	//static function getRolesForIdentity($uniqueIdentifier, $type);
}