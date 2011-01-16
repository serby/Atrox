<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */

/**
 * Security Role
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
interface Atrox_Application_Security_IRole {

	public function isAllowed($resourceId, $action);

	public function allow($resource, $action);
	public function disallow($resource, $action);
	public function getResources();

	public function addIdentity($uniqueIdentifier, $type);
	public function removeIdentity($uniqueIdentifier, $type);

	public static function getRolesForIdentity($uniqueIdentifier, $type);
}