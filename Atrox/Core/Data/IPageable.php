<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1199 $ - $Date: 2010-01-13 12:25:00 +0000 (Wed, 13 Jan 2010) $
 */

/**
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1199 $ - $Date: 2010-01-13 12:25:00 +0000 (Wed, 13 Jan 2010) $
 * @package Core
 * @subpackage Data
 */
interface Atrox_Core_Data_IPageable {
	/**
	 * Returns the next item of the page
	 * @return mixed
	 */
	public function getNext();

	/**
	 * Returns the count of the page
	 * @return integer
	 */
	public function getCount();
}