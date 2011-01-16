<?php
/**
 *
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
interface Atrox_Core_Data_Adaptor_IFactory {

	/**
	 * Returns the connection for this adaptor.
	 *
	 * @return Atrox_Core_Data_Adaptor_Connection
	 */
	public function getConnection();

	/**
	 * Returns the collection for this adaptor.
	 *
	 * @return Atrox_Core_Data_Adaptor_Collection
	 */
	public function getCollection();

	/**
	 * Returns the filter for this adaptor.
	 *
	 * @return Atrox_Core_Data_Adaptor_IFilter
	 */
	public function getFilter();
}