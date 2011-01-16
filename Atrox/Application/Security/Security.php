<?php
/**
 * So secure we named it twice
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Application
 * @subpackage Security
 */
class Atrox_Application_Security_Security  {

	/**
	 * After authentication this will store the identity dataset
	 * @var Atrox_Application_Security_IIdentity
	 */
	protected $currentIdentity;

	/**
	 *
	 * @var Atrox_Application_Security_IAuthenticationProvider
	 */
	protected $authenticationProvider;

	/**
	 *
	 * @var Atrox_Application_Security_IAuthorizationProvider
	 */
	protected $authorizationProvider;

	public function __construct(Atrox_Application_Security_IAuthenticationProvider $authenticationProvider,
		Atrox_Application_Security_IAuthorizationProvider $authorizationProvider) {
		$this->authenticationProvider = $authenticationProvider;
		$this->authorizationProvider = $authorizationProvider;
	}

	/**
	 *
	 * @param Atrox_Application_Security_IAuthenticationProvider$authenticationProvder
	 * @return Atrox_Application_Application
	 */
	public function setAuthenticationProvider(Atrox_Application_Security_IAuthenticationProvider $authenticationProvder) {
		$this->authenticationProvider = $authenticationProvder;
		return $this;
	}

	/**
	 *
	 * @return Atrox_Application_Security_IAuthenticationProvider
	 */
	public function getAuthenticationProvider() {
		return $this->authenticationProvider;
	}


	/**
	 *
	 * @param Atrox_Application_Security_IAuthorizationProvider $authorizationProvider
	 * @return Atrox_Application_Security_Security
	 */
	public function setAuthorizationProvider(Atrox_Application_Security_IAuthorizationProvider $authorizationProvider) {
		$this->authorizationProvider = $authorizationProvider;
	}

	/**
	 *
	 * @return Atrox_Application_Security_IAuthorizationProvider
	 */
	public function getAuthorizationProvider() {
		return $this->authorizationProvider;
	}

	public function authenticate($uniqueIdentifier, $password) {
		if ($this->currentIdentity = $this->authenticationProvider->authenticate($uniqueIdentifier, $password)) {
			if (!isset($_SESSION)) {
				session_start();
			}
			//TODO: fixme back to getting identity via provider
			$_SESSION["CurrentUniqueIdentifier"] = $uniqueIdentifier;
			$_SESSION["CurrentType"] = $this->authenticationProvider->getType();
			$_SESSION["Acl"] = array();
			return $this->currentIdentity;
		}
		return false;
	}

	public function trustedAuthenticate($uniqueIdentifier) {
		if ($this->currentIdentity = $this->authenticationProvider->trustedAuthenticate($uniqueIdentifier)) {

			if (!isset($_SESSION)) {
				session_start();
			}

			//TODO: fixme back to getting identity via provider
			$_SESSION["CurrentUniqueIdentifier"] = $uniqueIdentifier;
			$_SESSION["CurrentType"] = $this->authenticationProvider->getType();
			$_SESSION["Acl"] = array();
			return $this->currentIdentity;
		}
		return false;
	}

	/**
	 *
	 * @return bool
	 */
	public function deauthenticate() {
		if (!isset($_SESSION)) {
			session_start();
		}
		$this->authenticationProvider->deauthenticate($_SESSION["CurrentUniqueIdentifier"]);
		$_SESSION["Acl"] = array();
		unset($_SESSION["CurrentUniqueIdentifier"]);
		unset($_SESSION["CurrentType"]);
		return true;
	}

	public function isAuthenticated() {
		if (!isset($_SESSION)) {
			session_start();
		}
		return isset($_SESSION["CurrentUniqueIdentifier"]) && ($_SESSION["CurrentType"] == $this->authenticationProvider->getType());
	}

	public function authenticateWithToken($token) {
		if (!isset($_SESSION)) {
			session_start();
		}
		if ($this->currentIdentity = $this->authenticationProvider->authenticateWithToken($token)) {
			$_SESSION["CurrentUniqueIdentifier"] = $this->currentIdentity->getUniqueIdentifier();
			$_SESSION["CurrentType"] = $this->authenticationProvider->getType();
			$_SESSION["Acl"] = array();
			return $this->currentIdentity;
		}
		return false;
	}

	public function isAllowed($resource, $action) {
		$name = $resource . "::" .  $action;
		if (!$this->isAuthenticated()) {
			return false;
		}
		if (isset($_SESSION["Acl"][$name]) && $_SESSION["Acl"][$name]) {
			return true;
		}
		return $_SESSION["Acl"][$name] = $this->authorizationProvider->isAllowed(
			$_SESSION["CurrentUniqueIdentifier"], $_SESSION["CurrentType"], $resource, $action);
	}

	/**
	 *
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function getCurrentIdentity() {
		if ($this->isAuthenticated()) {
			if (!isset($this->currentIdentity)) {
				$this->currentIdentity = $this->authenticationProvider->loadIdentity(
					$_SESSION["CurrentUniqueIdentifier"]);
			}
			return $this->currentIdentity;
		}
	}
}