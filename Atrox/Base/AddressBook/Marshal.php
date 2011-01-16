<?php
/**
 * Controller for interacting with the address book.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Base
 * @subpackage AddressBook
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Base_AddressBook_Controller extends Atrox_Application_Component_Controller {

	public function __construct($viewType = null) {
		$this->view = new TickTock_Emporium_Recipe_View();
		$this->model = new Atrox_Base_AddressBook_Model();
		$this->request = new Atrox_Core_Network_HttpRequest();
	}

	public function listAll() {
		$this->view->displayList($this->model->retrieve());
	}

	public function read($id) {
		$this->view->display($this->model->read($id));
	}

	public function makeNew() {
		$this->view->displayForm();
	}

	public function create() {
		$this->view->displayForm();
	}

	public function update($id) {
		$record = $this->model->map($this->request->getPost());
		$record->setId($id);
		if ($this->model->update($record)) {
			$this->view->display($record);
		} else {
			$this->view->displayForm($record);
		}
	}
}