<?php

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1199 $ - $Date: 2010-01-13 12:25:00 +0000 (Wed, 13 Jan 2010) $
 * @package Core
 * @subpackage Data
 */
interface Atrox_Core_Data_IPersistable {

	/**
	 *Returns the ID of the object
	 * @return mixed
	 */
	public function getId();

	/**
	 * Sets the ID of the object
	 * @param $id
	 * @return mixed
	 */
	public function setId($id);

}