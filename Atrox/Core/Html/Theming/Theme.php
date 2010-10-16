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
 * Instance of a Theme which is a bit like a template
 *
 * @copyright Clock Limited 2010
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @version 5.1 - $Revision: 1193 $ - $Date: 2009-12-23 12:50:18 +0000 (Wed, 23 Dec 2009) $
 * @package Core
 * @subpackage Html/Theming
 */
class Atrox_Core_Html_Theming_Theme {
	
	protected $path;
	
	protected $tokens = array();
	
	public function __construct($path) {
		$this->path = $path;
	}
	
	public function load($token, $path, Atrox_Application_Component_View $view) {
		ob_start();
		
		include($path);
		$this->tokens[$token] = ob_get_contents();
		ob_end_clean();
	}
	
	public function add($token, $content) {
		if (!isset($this->tokens[$token])) {
			$this->tokens[$token] = $content;
		} else {
			$this->tokens[$token] .= $content;
		}
	}
	
	public function render() {
		$source = file_get_contents($this->path . "/main.html", FILE_USE_INCLUDE_PATH);
		
		foreach ($this->tokens as $k => $v) {
			$source = preg_replace("/\\$\{$k\}/", $v, $source);
		}
		
		// Remove any remaining tags
		$source = preg_replace("/\\$\{.*?\}/", "", $source);
		echo $source;
	}
}