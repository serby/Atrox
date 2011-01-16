<?php
/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1085 $ - $Date: 2009-07-28 21:53:20 +0100 (Tue, 28 Jul 2009) $
 * @package Core
 * @subpackage Internet
 */
class Atrox_Core_Network_HttpConnection {

	/**
	 *
	 * @var string
	 */
	protected $url;

	/**
	 *
	 * @var array
	 */
	protected $headers = array();

	/**
	 *
	 * @var string
	 */
	protected $username;

	/**
	 *
	 * @var string
	 */
	protected $password;

	/**
	 *
	 * @var int
	 */
	protected $timeout = 30;

	/**
	 *
	 * @var string
	 */
	protected $userAgent = "Atrox";

	/**
	 *
	 *
	 * @var string
	 */
	protected $referer = "";

	/**
	 *
	 * @var string
	 */
	protected $postData;

	/**
	 *
	 * @var resource
	 */
	protected $curl;

	public function __construct($url) {
		$this->url = $url;
	}

	public function send() {

		$curl = curl_init();


		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

		if ($this->username) {
			curl_setopt($curl, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		}

		if ($this->postData) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->postData);
		}

		if ($this->userAgent) {
			curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
		}

		if ($this->referer) {
			curl_setopt($curl, CURLOPT_REFERER, $this->referer);
		}


		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);

		$response = new stdClass();
		$responseText = curl_exec($curl);

		list($response->header, $response->body) = explode("\r\n\r\n", $responseText);

		$response->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		return $response;
	}

	/**
	 *
	 * @param array $data
	 */
	public function setPostData(array $data) {
		$this->postData = http_build_query($data);
	}

	/**
	 *
	 * @param string $data
	 */
	public function setRawPostData($data) {
		$this->postData = $data;
	}

	/**
	 *
	 * @param string $header
	 * @param strign $value
	 * @return Atrox_Core_Network_HttpRequest
	 */
	public function addHeader($header, $value) {
		$this->headers[] = "$header: $value";
		return $this;
	}

	public function addCookie($name, $value, $expire = 0, $path = false, $domain = false, $secure = false) {
		throw new Exception("Not yet implemented");
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function setAuthenticationDetails($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}
}