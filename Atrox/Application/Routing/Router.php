<?php
/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1490 $ - $Date: 2010-05-20 18:59:44 +0100 (Thu, 20 May 2010) $
 * @package Application
 * @subpackage Routing
 */
class Atrox_Application_Routing_Router {

	const GET = "GET";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";

	/**
	 *
	 * @var Atrox_Application_Application
	 */
	protected $application;

	/**
	 *
	 * @var array
	 */
	protected $routes = array();

	/**
	 *
	 * @param Atrox_Application_Application $application
	 */
	public function __construct(Atrox_Application_Application $application) {
		$this->application = $application;
	}

	/**
	 * Is the given content type acceptable for the browser to receive
	 *
	 * @param string $accepts
	 */
	public function accepts($accept) {
		foreach ($this->getAcceptables() as $acceptable) {
			$acceptable = trim($acceptable);
			// I'm taking this out. I might have broken Chrome - Serby
			//if (($accept == $acceptable) || ($acceptable == "*/*")) {
			if ($accept == $acceptable) {
				return true;
			}
		}
		return false;
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
	 * @param array $routes
	 * @return Atrox_Application_Routing_Router
	 */
	public function addRoutes($routes) {
		$this->routes += $routes;
		return $this;
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
	 * @param $route
	 * @param $className
	 * @param $function
	 * @param $method
	 * @return unknown_type
	 */
	public function addRoute($routePath, $className, $function, $method = self::GET)	{
		$this->routes[] = $this->makeRoute($routePath, $className, $function, $method);
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
			$parameters = array();

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
			$object = new $route[0]->className;
			call_user_func_array(array($object, $route[0]->function), $route[1]);
		} else {
			//TODO: This needs sorting
			header("HTTP/1.1 404 Page Not Found");
			header("Status: 404 Page Not Found");
			echo "404 - " . $path;
			exit;
		}
	}

	/**
	 *
	 * @return array|false Accepted responses or false if the Accept header is missing
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