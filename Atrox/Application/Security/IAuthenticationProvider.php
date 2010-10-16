<?php
/**
 * Authentication Provider Interface - Who you are.
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
interface Atrox_Application_Security_IAuthenticationProvider {

	/**
	 * Each authentication method in a system must overtise this with a system wide unique ID
	 *
	 *
	 * @return int Unique authentication type ID
	 */
	public function getType();

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @param string $password
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function authenticate($uniqueIdentifier, $password);

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function trustedAuthenticate($uniqueIdentifier);

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @return bool
	 */
	public function deauthenticate($uniqueIdentifier);

	/**
	 *
	 * @param string $token
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function authenticateWithToken($token);

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function loadIdentity($uniqueIdentifier);
}