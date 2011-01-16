<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */

/**
 * For using a Record as the Authentication Provider
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
abstract class Atrox_Application_Security_AuthenticationProvider_Record
	extends Atrox_Core_Data_Entity
	implements Atrox_Application_Security_IAuthenticationProvider, Atrox_Application_Security_IIdentity {

	/**
	 *
	 * @var string
	 */
	protected $uniqueIdentifierPropertyName = "EmailAddress";

	/**
	 *
	 * @var string
	 */
	protected $passwordPropertyName = "Password";


	/**
	 *
	 * @var string
	 */
	protected $tokendPropertyName = "Token";

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @param string $password
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function authenticate($uniqueIdentifier, $password) {

		//TODO: This won't work until PHP 5.3
		//$dataSource = static::getDataSource();
		$dataSource = $this->getDataSource();
		$filter = $dataSource->makeFilter();
		$passwordFormatter = new Atrox_Core_Data_Formatter_Password();
		$filter->addConditional($dataSource->getTableName(), $this->uniqueIdentifierPropertyName, $uniqueIdentifier);
		$filter->addConditional($dataSource->getTableName(), $this->passwordPropertyName, $passwordFormatter->format($password));

		$dataset = $dataSource->retrieve($filter);

		if ($entity = $dataset->getNext()) {
			return $entity;
		}

		return false;
	}

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function trustedAuthenticate($uniqueIdentifier) {
		//TODO: This won't work until PHP 5.3
		//$dataSource = static::getDataSource();
		$dataSource = $this->getDataSource();
		$filter = $dataSource->makeFilter();
		$filter->addConditional($dataSource->getTableName(), $this->uniqueIdentifierPropertyName, $uniqueIdentifier);

		$dataset = $dataSource->retrieve($filter);

		if ($entity = $dataset->getNext()) {
			return $entity;
		}

		return false;
	}

		/**
	 *
	 * @param string $uniqueIdentifier
	 * @return bool
	 */
	public function deauthenticate($uniqueIdentifier) {
		return true;
	}

	/**
	 *
	 * @param string $token
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function authenticateWithToken($token) {

		$dataSource = $this->getDataSource();
		$filter = $dataSource->makeFilter();
		$filter->addConditional($dataSource->getTableName(), $this->tokendPropertyName, $token);

		$dataset = $dataSource->retrieve($filter);

		if ($entity = $dataset->getNext()) {
			return $entity;
		}

		return false;
	}

	/**
	 *
	 * @param string $uniqueIdentifier
	 * @return Atrox_Application_Security_IIdentity
	 */
	public function loadIdentity($uniqueIdentifier) {

		$dataSource = $this->getDataSource();
		$filter = $dataSource->makeFilter();
		$filter->addConditional($dataSource->getTableName(), $this->uniqueIdentifierPropertyName, $uniqueIdentifier);

		$dataset = $dataSource->retrieve($filter);

		if ($entity = $dataset->getNext()) {
			return $entity;
		} else {
			throw new Atrox_Application_Security_UnableToLoadIdentityException("Unable to load identity for '$uniqueIdentifier'");
		}
		return false;
	}

	public function getUniqueIdentifier() {
		return $this->get($this->uniqueIdentifierPropertyName);
	}

	public function getPassword() {
		return $this->get($this->passwordPropertyName);
	}

	public function getToken() {
		return $this->get($this->tokendPropertyName);
	}
}