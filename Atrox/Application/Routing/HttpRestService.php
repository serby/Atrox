<?php
/**
 * @package Application
 * @subpackage Routing
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1379 $ - $Date: 2010-03-09 17:55:32 +0000 (Tue, 09 Mar 2010) $
 */

/**
 *
 * @author Paul Serby {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1379 $ - $Date: 2010-03-09 17:55:32 +0000 (Tue, 09 Mar 2010) $
 * @package Application
 * @subpackage Routing
 */
class Atrox_Core_Routing_HttpRestService extends Atrox_Core_Routing_Http {

	/**
	 * Tries to match the given paths to the current list of routes
	 *
	 * Value placeholders must be in lowercase only [-a-z] allowed
	 *
	 * /admin/member/{id}
	 *
	 * @param string $path
	 * @param string $method
	 * @return mixed false if no path is found
	 */
	public function hasRoute($path, $method) {
		$path = explode("/", trim($path, "/"));

		$method = strtoupper($method);

		foreach ($this->routes as $route) {
			if ($method != $route->method) {
				continue;
			}
			$routePaths = explode("/", trim($route->route, "/"));

			$i = 0;
			$data = $this;

			$parameters = array($data);
			while (isset($routePaths[$i]) && isset($path[$i]) && (($routePaths[$i] == $path[$i]) || ($routePaths[$i] == "*") ||
				(preg_match("/{[-a-z]+?}/", $routePaths[$i]) ? ($parameters[] = $path[$i]) || true : false))) {

				if ($routePaths[$i] == "*") {
					return array($route, $parameters);
				}

				$i++;
			}

			if ($i == count($path)) {
				return array($route, $parameters);
			}
		}
		return false;
	}

	public function getRequestObject() {
		if (isset($_SERVER["CONTENT_TYPE"])) {
			$contentType = $_SERVER["CONTENT_TYPE"];
		} else {
			$contentType = "";
		}

		if ($pos = strpos($contentType, ";")) {
			$contentType = substr($contentType, 0, $pos);
		}

		switch ($contentType) {
			case "application/json":
				$jsonRequestData = file_get_contents("php://input");
				$requestObject = json_decode($jsonRequestData);
				break;
			default:
				$requestObject = (object)$_REQUEST;
		}
		return $requestObject;
	}

	protected function callFunction($controller, $functionName, $parameters) {
		// Catch all exceptions rather than letting them hit the top level exception handler.
		try {
			parent::callFunction($controller, $functionName, $parameters);
		} catch (Exception $e) {
			$this->view->displayError(500, $e);
			exit(500);
		}
	}
}