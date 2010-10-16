<?php
/**
 * @package Application
 * @subpackage Routing
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1453 $ - $Date: 2010-04-29 19:10:26 +0100 (Thu, 29 Apr 2010) $
 */

/**
 *
 * @author Paul Serby {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2009
 * @version 5.1 - $Revision: 1453 $ - $Date: 2010-04-29 19:10:26 +0100 (Thu, 29 Apr 2010) $
 * @package Application
 * @subpackage Routing
 */
class Atrox_Application_Routing_Http {

	const GET = "GET";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";

	/**
	 *
	 * @var Atrox_Core_Network_HttpRequest
	 */
	protected $request;

	/**
	 *
	 * @var Atrox_Core_Routing_View
	 */
	protected $view;

	/**
	 *
	 * @var array
	 */
	protected $routes = array();

	/**
	 *
	 * @param Atrox_Core_Application $application
	 */
	public function __construct(Atrox_Core_Network_HttpRequest $request, Atrox_Application_Routing_View $view = null) {

		$this->request = $request;

		if ($view === null) {
			$view = new Atrox_Application_Routing_View();
		}
		$this->view = $view;
	}

	/**
	 *
	 *
	 * @param array $acceptTypes
	 */
	public function findAcceptableContentType($acceptTypes) {
		$acceptables = $this->getAcceptables();
		foreach ($acceptTypes as $acceptType => $type) {
			foreach ($acceptables as $acceptable) {
				$acceptable = $acceptable;
				if ($acceptType == $acceptable) {
					return $type;
				}
			}
		}

		if (is_array($acceptTypes) && (count($acceptTypes) > 0)) {
			return reset($acceptTypes);
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param $route
	 * @param $function
	 * @param $controller
	 * @param $method
	 * @return unknown_type
	 */
	public function addRoute($route, $function, $controller, $method = self::GET) {
		$newRoute = new stdClass();
		$newRoute->route = $route;
		$newRoute->function = $function;
		$newRoute->controller = $controller;
		$newRoute->method = $method;
		$this->routes[] = $newRoute;
	}

	/**
	 *
	 * @param $routePath
	 * @param $className
	 * @param $function
	 * @param $method
	 * @return unknown_type
	 */
	public function makeRoute($routePath, $className, $function, $method = self::GET)	{
		$route = new stdClass();
		$route->route = $routePath;
		$route->function = $function;
		$route->className = $className;
		$route->method = $method;
		return $route;
	}


	/**
	 *
	 * @param string $componentPath
	 * @param Atrox_Application_Component_Component $component
	 * @return Atrox_Application_Routing_Router
	 */
	public function addComponent($componentPath, Atrox_Application_Component_Component $component) {
		$component->setPath($componentPath);
		$this->addRoutes($component->getRoutes());
		return $this;
	}

	/**
	 *
	 * @param array $routes
	 * @return Atrox_Application_Routing_Router
	 */
	public function addRoutes($routes) {
		$this->routes = array_merge($this->routes, $routes);
		return $this;
	}

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
			$data = $_REQUEST;

			foreach ($_FILES as $attribute => $fileInfo) {
				$extendedData = new Atrox_Core_Data_ExtendedData(
					isset($data[$attribute]["Current"]) ? $data[$attribute]["Current"] : null
				);
				$extendedData->metadata["Filename"] = $fileInfo["name"]["File"];
				$extendedData->metadata["Type"] = $fileInfo["type"]["File"];
				$extendedData->metadata["TempName"] = $fileInfo["tmp_name"]["File"];
				$extendedData->metadata["Error"] = $fileInfo["error"]["File"];
				$extendedData->metadata["Size"] = $fileInfo["size"]["File"];
				$data[$attribute] = $extendedData;
			}

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

	/**
	 *
	 * @param $path
	 * @param $method
	 * @return unknown_type
	 */
	public function findRoute($path, $method) {
		if ($route = $this->hasRoute($path, $method)) {
			$this->callFunction(
				$route[0]->className,
				$route[0]->function,
				$route[1]);
		} else {
			$this->view->displayError(404, 404 . " - {$path}");
		}
	}

	protected function callFunction($className, $functionName, $parameters) {
		try {
			call_user_func_array(
				array(
					new $className($this),
					$functionName),
					$parameters);
		} catch (Atrox_Core_Exception_BadRequestException $e) {
			// I've changed this at Fuses request. It is bad, this should be a 400
			$this->view->displayError(400, $e->getMessage());
		} catch (Atrox_Core_Exception_InvalidTokenException $e) {
			// I've changed this at Fuses request. It is bad, this should be a 400
			$this->view->displayError(400, $e->getMessage());
		} catch (Atrox_Core_Exception_ResourceNotFoundException $e) {
				// I've changed this at Fuses request. It is bad, this should be a 400
			$this->view->displayError(400, $e->getMessage());
		} catch (Atrox_Core_Exception_UnauthorizedException $e) {
				// I've changed this at Fuses request. It is bad, this should be a 400
			$this->view->displayError(400, $e->getMessage());
		}
	}

	/**
	 *
	 * @return array Accepted responses if the Accept header is missing
	 */
	public function getAcceptables() {
		if (!isset($_SERVER["HTTP_ACCEPT"])) {
			return array();
		}
		$tempAcceptables = explode(",", $_SERVER["HTTP_ACCEPT"]);
		$acceptables = array();

		foreach ($tempAcceptables as $acceptable) {
			$acceptable = explode(";", $acceptable);
			$acceptables[] = trim($acceptable[0]);
		}
		return $acceptables;
	}
}