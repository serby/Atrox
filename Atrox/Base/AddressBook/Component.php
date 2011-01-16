<?php
/**
 * A very simple address book.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Base
 * @subpackage AddressBook
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Base_AddressBook_Component extends Atrox_Application_Component_Component {

	public function getRoutes() {
		$routes = array();

		$routes[] = $this->router->
			makeRoute($this->path . "/", "TickTock_Emporium_Recipe_Controller", "listAll");

		$routes[] = $this->router->
			makeRoute($this->path . "/form", "TickTock_Emporium_Recipe_Controller", "makeNew");

		$routes[] = $this->router->
			makeRoute($this->path . "/", "TickTock_Emporium_Recipe_Controller", "create",
			Atrox_Core_Application_Routing_Router::POST);

		$routes[] = $this->router->
			makeRoute($this->path . "/{id}", "TickTock_Emporium_Recipe_Controller", "read");

		$routes[] = $this->router->
			makeRoute($this->path . "/{id}", "TickTock_Emporium_Recipe_Controller", "delete",
			Atrox_Core_Application_Routing_Router::DELETE);

		$routes[] = $this->router->
			makeRoute($this->path . "/{id}", "TickTock_Emporium_Recipe_Controller", "update",
			Atrox_Core_Application_Routing_Router::PUT);

		$routes[] = $this->router->
			makeRoute($this->path . "/{id}/form", "TickTock_Emporium_Recipe_Controller", "edit");
		return $routes;
	}
}