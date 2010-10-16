<?php
/**
 *
 * @author Paul Serby {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1389 $ - $Date: 2010-03-15 23:53:11 +0000 (Mon, 15 Mar 2010) $
 * @package Application
 * @subpackage Routing
 */
class Atrox_Application_Routing_View {

	/**
	 *
	 * @param string $message
	 *
	 * @return void
	 *
	 */
	public function output($message) {
		echo $message;
	}

	/**
	 *
	 * @param int $code
	 * @param array $errors
	 *
	 * @return void
	 */
	public function displayError($code, $error) {
		$this->setStatus($code);
		$this->output($error);
		Atrox_Core_ServiceLocator::getLogger()->log($error, "Request", Atrox_Core_Logging_Logger::DEBUG);
	}

	/**
	 * Sets the content type for this response.
	 *
	 * @param unknown_type $contentType
	 *
	 * @return void
	 */
	public function setContentType($contentType) {
		header("Content-type: {$contentType}");
	}

	/**
	 *
	 * @param int $code
	 *
	 * @return void
	 */
	public function setStatus($code) {
		switch ($code) {
			case 201:
				$status = "201 Created";
				break;
			case 202:
				$status = "202 Accepted";
				break;
			case 400:
				$status = "400 Bad Request";
				break;
			case 401:
				$status = "401 Unauthorized";
				break;
			case 404:
				$status = "404 Page Not Found";
				break;
			case 405:
				$status = "405 Method Not Allowed";
				break;
			case 406:
				$status = "406 Not Acceptable";
				break;
			case 500:
				$status = "500 Internal Server Error";
				break;
			default:
				$status = "$code Unknown";
		}
		header("HTTP/1.1 {$status}");
		header("Status: {$status}");
	}
}