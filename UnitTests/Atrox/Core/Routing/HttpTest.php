<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Iain Grant (Clock Limited) {@link mailto:iain.grant@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Routing_HttpTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Atrox_Core_Routing_Http
	 */
	protected $router;

	/**
	 * @var array
	 */
	protected $acceptHeaders;

	 public function setUp() {
		$this->acceptHeaders = array(
			"All" => "*/*",
			"Windows Vista/Safari 3.2.1" =>
				"text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
			"Windows Vista/Opera 2.0.172.33" =>
				"application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
			"Windows Vista/IE 8.0.6001.18783" =>
				"image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/x-shockwave-flash, */*",
			"Windows Vista/IE Tester - IE 6" =>
				"image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/x-shockwave-flash, */*",
			"Windows Vista/IE Tester - IE 7" =>
				"image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/x-shockwave-flash, */*",
			"Windows Vista/Firefox 3.5" =>
				"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Pure XML" =>
				"text/xml2"
		);

		$application = Atrox_Core_Application::getInstance();
		$this->router = new Atrox_Core_Routing_Http($application);

	}

	 public function testFindAcceptableContentType() {
		$acceptType = array(
			"text/html" => "Html",
			"application/json" => "Json",
			"text/xml" => "Xml"
		);

		$_SERVER["HTTP_ACCEPT"] = "";
		$this->assertEquals("Html", $this->router->findAcceptableContentType($acceptType));

		$_SERVER["HTTP_ACCEPT"] = "text/html,*/*";
		$this->assertEquals("Html", $this->router->findAcceptableContentType($acceptType));

		$_SERVER["HTTP_ACCEPT"] = "text/xml";
		$this->assertEquals("Xml", $this->router->findAcceptableContentType($acceptType));

		$_SERVER["HTTP_ACCEPT"] = "text/xml;*/*";
		$this->assertEquals("Xml", $this->router->findAcceptableContentType($acceptType));

		$_SERVER["HTTP_ACCEPT"] = "application/json";
		$this->assertEquals("Json", $this->router->findAcceptableContentType($acceptType));

		$_SERVER["HTTP_ACCEPT"] = "application/json;*/*";
		$this->assertEquals("Json", $this->router->findAcceptableContentType($acceptType));


		foreach ($this->acceptHeaders as $acceptHeader) {
			$_SERVER["HTTP_ACCEPT"] = $acceptHeader;
			$this->assertEquals("Html", $this->router->findAcceptableContentType($acceptType), "Testng " . $acceptHeader);
		}
	}
}