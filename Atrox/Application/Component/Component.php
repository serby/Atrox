<?php
/**
 * Global Component Configuration
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Base
 * @subpackage AddressBook
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Atrox_Application_Component_Component {

	/**
	 * @var string
	 */
	protected $path = "";

	/**
	 *
	 * @var Atrox_Application_Routing_Router
	 */
	protected $router;

	/**
	 *
	 * @param Atrox_Application_Routing_Router $router
	 */
	public function __construct(Atrox_Application_Routing_Http $router) {
		$this->router = $router;
	}

	/**
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	final public function setPath($path) {
		$this->path = $path;
	}

	/**
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 *
	 * @return array
	 */
	abstract public function getRoutes();
}