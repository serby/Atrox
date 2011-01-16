<?php
/**
 * Wraps the default $_GET $_POST $_COOKIE
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_Network_HttpRequest {

	/**
	 *
	 * @var array
	 */
	protected $getData;

	/**
	 *
	 * @var array
	 */
	protected $postData;

	/**
	 * @string
	 */
	protected $rawPostData;

	/**
	 *
	 * @var array
	 */
	protected $cookieData;


	/**
	 * Map the default request data and then clears $_GET, $_POST, and $_COOKIE.
	 */
	public function __construct() {
		$this->getData = $_GET;
		$this->postData = $_POST;
		$this->cookieData = $_COOKIE;
		unset($_GET);
		unset($_POST);
		unset($_COOKIE);
	}

	/**
	 * Return the GET data with the given $key.
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed Either the post data or default if it isn't present.
	 */
	public function getGetValue($key, $default = null) {
		return isset($this->getData[$key]) ? $this->getData[$key] : $default;
	}

	/**
	 * Return the POST data with the given $key.
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed Either the post data or default if it isn't present.
	 */
	public function getPostValue($key, $default = null) {
		return isset($this->postData[$key]) ? $this->postData[$key] : $default;
	}

	/**
	 * Return the POST data with the given $key.
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed Either the post data or default if it isn't present.
	 */
	public function getCookieValue($key, $default = null) {
		return isset($this->cookieData[$key]) ? $this->cookieData[$key] : $default;
	}

	/**
	 * Return the raw POST data.
	 *
	 * Does not work with enctype=�multipart/form-data�.
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed Either the post data or default if it isn't present.
	 */
	public function getRawPostData() {
		return file_get_contents("php://input");
	}
}