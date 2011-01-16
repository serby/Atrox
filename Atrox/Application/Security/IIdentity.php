<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */

/**
 * Dataset returned by the Authentication Provider Interface
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
interface Atrox_Application_Security_IIdentity {

	/**
	 * Get the Unique Identifier used in the authentication
	 *
	 * @return string
	 */
	public function getUniqueIdentifier();

	/**
	 * Get the token used in the authentication
	 *
	 * @return string
	 */
	public function getToken();

		/**
	 * Get the password used in the authentication
	 *
	 * @return string
	 */
	public function getPassword();
}