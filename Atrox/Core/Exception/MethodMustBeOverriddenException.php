<?php
/**
 * Thrown when a method must be overridden in a child class.
 *
 * This is to get round PHP 5.2 not allowing absreact static functions.
 *
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package TickTock
 * @subpackage Store
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */
class Atrox_Core_Exception_MethodMustBeOverriddenException extends Atrox_Core_Exception {

}