<?php
/**
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1193 $ - $Date: 2009-12-23 12:50:18 +0000 (Wed, 23 Dec 2009) $
 * @package Core
 * @subpackage Html/Theming
 */

/**
 * Globalably manages themes for the application
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1193 $ - $Date: 2009-12-23 12:50:18 +0000 (Wed, 23 Dec 2009) $
 * @package Core
 * @subpackage Html/Theming
 */
class Atrox_Core_Html_Theming_Manager {
	
	protected $themes = array();
	
	protected function __construct() {
		
	}
	
	/**
	 * Returns the one instance of the Theme Manager
	 * @return Atrox_Core_Html_Theming_Manager
	 */
	static function getInstance() {
		static $application;
		if (!isset($application)) {
			$application = new Atrox_Core_Html_Theming_Manager();
		}
		return $application;
	}
	
	/**
	 * 
	 * @param string $themeName
	 * @return Atrox_Core_Html_Theming_Manager
	 */
	public function addTheme($themeName, Atrox_Core_Html_Theming_Theme $theme) {
		$this->themes[$themeName] = $theme;
		return $this;
	}
	
	/**
	 * 
	 * @param string $themeName
	 * @return Atrox_Core_Html_Theming_Theme
	 */
	public function __invoke($themeName = 0) {
		return $this->themes[$themeName];
	}
	
	/**
	 * 
	 * @param string $themeName
	 * @return Atrox_Core_Html_Theming_Theme
	 */
	public function getTheme($themeName = 0) {
		if ($themeName == null) {
			return reset($this->themes);
		} else {
			return $this->themes[$themeName];
		}
	}
}