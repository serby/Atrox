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
class Atrox_Base_AddressBook_Model {

	/**
	 * @var Atrox_Core_Data_Adaptor_Collection
	 */
	protected $contacts;

	public function __construct() {
		$serviceLocator = Atrox_Core_ServiceLocator::getInstance();
		$dataAdaptorfactory = $serviceLocator->getDataAdaptorFactory();

		$this->contacts = new $dataAdaptorfactory->getCollection(
			"Contact", Atrox_Base_AddressBook_Contact::getSchema(),
			$serviceLocator->getConnection());
	}

	public function add(Atrox_Base_AddressBook_Contact $contact) {
		if ($contact->validate()) {
			$this->contacts->add($contact);
		}
	}

	public function update(Atrox_Base_AddressBook_Contact $contact) {

	}

	public function remove(Atrox_Base_AddressBook_Contact $contact) {

	}

	public function search($searchPhrase) {

	}
}
