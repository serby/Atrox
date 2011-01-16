<?php
/**
 * A very simple address book contact.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Base
 * @subpackage AddressBook
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Base_AddressBook_Contact extends Atrox_Core_Data_Entity {

	/**
	 * Setups up the properties for this entity.
	 *
	 * @return null
	 */
	public static function getSchema() {
		static $schema;
		if (!$schema) {
			$schema = new Atrox_Core_Data_EntitySchema();
			$schema->addProperty(new Atrox_Core_Data_Property_Integer("id"));
			$schema->addProperty(new Atrox_Core_Data_Property_String("firstName"));
			$schema->addProperty(new Atrox_Core_Data_Property_String("lastName"));
			$schema->addProperty(new Atrox_Core_Data_Property_String("mobile"));
			$schema->addProperty(new Atrox_Core_Data_Property_String("description"));
			$schema->addProperty(new Atrox_Core_Data_Property_Email("email"));
			$schema->addProperty(new Atrox_Core_Data_Property_Date("dateCreated"));
		}
		return $schema;
	}
}
