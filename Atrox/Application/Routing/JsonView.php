<?php
/**
 * @package Application
 * @subpackage Routing
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1389 $ - $Date: 2010-03-15 23:53:11 +0000 (Mon, 15 Mar 2010) $
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1389 $ - $Date: 2010-03-15 23:53:11 +0000 (Mon, 15 Mar 2010) $
 * @package Application
 * @subpackage Routing
 */
class Atrox_Core_Routing_JsonView extends Atrox_Core_Routing_View {

	public function __construct() {
		$this->setContentType("application/json");
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

		$errors = new stdClass();
		$errors->code = $code;

		$errors->message = $error;
		if ($error instanceof Exception) {
			if (ini_get("display_errors")) {
				$errors->message = $error->getMessage();
				$errors->code = $error->getCode();
				$errors->file = $error->getFile();
				$errors->getLine = $error->getLine();
			} else {
				$errors->message =
				"An unexpected internal error has occurred. Please reference this code when reporting this error: " .
				Atrox_Core_Exception::makeHash($error);
				Atrox_Base_Application_Exception::log($error);
			}
		}

		$response = new stdClass();
		$response->success = false;
		$response->error = array($errors);

		$this->output(json_encode($response));
	}
}