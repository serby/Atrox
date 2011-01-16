<?php
require_once("Atrox/Core/Application.php");
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Application
 */

/**
 * All Web application will have an instance of this class to help
 * handle elements such as Errors, Session and Form submissions
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Application
 */
class Atrox_Application_Http extends Atrox_Application_Application {

	/**
	 *
	 * @var string
	 */
	protected $contentType = "text/html";


	/**
	 * Returns the one instance of Application
	 *
	 * @return Atrox_Application_Application
	 */
	public static function getInstance($object = null) {
		static $application;
		if (!isset($application)) {
			$application = new Atrox_Application_Http();
		}
		return new $application;
	}

	public function setContentType($contentType) {
		$this->contentType = $contentType;
		header("Content-Type: $contentType");
	}

	/**
	 * Goto given page
	 * @param $url
	 */
	public function redirect($url) {
		//TODO: Is this needed??? - Tom & Rob
//		if ($url == "") {
//			if ((isset($_SESSION["GotoPage"])) && ($_SESSION["GotoPage"] != "")) {
//				$url = $_SESSION["GotoPage"];
//			} else {
//				Application::gotoLastPage();
//			}
//		}
		header("Location: $url");
//		$_SESSION["GotoPage"] = $url;
		exit;
	}

	/**
	 * Checks to see if the given path s within the current URI
	 *
	 * @param string $path
	 * @param mixed $return
	 * @return mixed
	 */
	public function inPath($path, $return) {
		$pathLen = mb_strlen($path);
		if (mb_substr($_SERVER["REQUEST_URI"], 0, $pathLen) == $path) {
			return $return;
		}
		return "";
	}

	/**
	 * Check that the given url is the same as the current script_name if not then redirect
	 * If $postData is not empty then redirect to avoid double submit via use of browser
	 * refresh button.
	 *
	 * @author Tom Smith <thomas.smith@clock.co.uk>
	 * @param string $url What url should appear in the address bar.
	 * @param array $postData Post data
	 */
	public function redirectToCorrectUrl($url) {
		if (($_SERVER["SCRIPT_NAME"] != $url) || (count($_POST) > 0)) {
			$this->redirect($url);
		}
	}
}