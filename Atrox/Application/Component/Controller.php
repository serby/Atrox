<?php
/**
 * This is used for Page Controller Pattern where the controller passes actions to the model
 * but also handles complex view logic
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 961 $ - $Date: 2009-05-11 14:25:42 +0100 (Mon, 11 May 2009) $
 * @package Application
 * @subpackage Application/Component
 */
abstract class Atrox_Application_Component_Controller {

	/**
	 *
	 * @var Atrox_Application_Component_IView
	 */
	protected $view;

	/**
	 *
	 * @var Atrox_Application_Component_IModel
	 */
	protected $model;

	abstract public function __construct($viewType = null);
}