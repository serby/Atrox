<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */

/**
 * Authentication Exception
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Application
 * @subpackage Security
 */
class Atrox_Application_Security_PermissionDeniedException extends Atrox_Core_Exception {

	// Redefine the exception so message isn't optional
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
}