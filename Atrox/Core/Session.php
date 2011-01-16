<?php
/**
 * Wrapper to persist Session Data.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Wrapper to persist Session Data.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_Session {

	/**
	 * Return the session data stored with the given $key.
	 *
	 * @param string $key Unique key to set the data. If the key already exists the data will be overwritten
	 *
	 * @return mixed The date for $key of false if the key does not exist
	 */
	public function get($key) {
		$this->startSesssion();
		if (isset($_SESSION["Data"][$key]) === true) {
			return $_SESSION["Data"][$key];
		} else {
			return false;
		}
	}

	/**
	 * Store $data as the give $key for the current session.
	 *
	 * @param mixed $key Unique key to set the data. If the key already exists the data will be overwritten
	 * @param mixed $data Data to store
	 *
	 * @return boolean true or false if $key or $data is not set
	 */
	public function set($key, $data) {
		if ((isset($key) === false) || (isset($data) === false)) {
			return false;
		}
		$this->startSession();
		$_SESSION["Data"][$key] = $data;
		return true;
	}

	/**
	 * Returns the current session token.
	 *
	 * {@source}
	 *
	 * @return The current session token
	 */
	public function getToken() {
		$this->startSession();
		return session_id();
	}

	/**
	 * Sets a unique token for the current session
	 *
	 * @param string $token The token that will be used to store this session with
	 *
	 * @return mixed|false The new session token or false if there was a problem
	 */
	public function setToken($token) {
		if (empty($token) === true) {
			throw new Atrox_Core_Exception_InvalidTokenException("Token Empty");
		} else {
			$sessionId = session_id($token);
			$this->startSession();
			return $sessionId;
		}
		return false;
	}

	/**
	 * Ensure the session has started.
	 *
	 * @return void
	 */
	protected function startSession() {
		try {
			session_start();
		} catch (Exception $e) {
			// Don't worry if session is already started
		}
	}
}