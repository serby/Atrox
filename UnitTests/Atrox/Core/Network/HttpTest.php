<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Network_HttpTest extends PHPUnit_Framework_TestCase {

	 public function setUp() {

	}

	 public function tearDown() {
	}

	 public function testSend() {
		$httpRequest = new Atrox_Core_Network_Http("http://atrox5/UnitTests/Site/http-check.php");
		$this->assertEquals(200, $httpRequest->send()->status);

		$httpRequest = new Atrox_Core_Network_Http("http://atrox5/UnitTests/Site/NOTAPAGE.html");
		$this->assertEquals(404, $httpRequest->send()->status);
	}

	 public function testMultipleSends() {
		$httpRequest = new Atrox_Core_Network_Http("http://atrox5/UnitTests/Site/http-check.php");
		$this->assertEquals(200, $httpRequest->send()->status);
		$this->assertEquals(200, $httpRequest->send()->status);
	}

	 public function testSendWithPostData() {
		$httpRequest = new Atrox_Core_Network_Http("http://atrox5/UnitTests/Site/http-check.php");
		$httpRequest->setPostData(array("q" => "hello"));
		$response = $httpRequest->send();
		$this->assertEquals(200, $response->status);
		$report = json_decode($response->body);
		$this->assertEquals("hello", $report->postData->q);
	}

	 public function testSendWithHeaders() {
		$httpRequest = new Atrox_Core_Network_Http("http://atrox5/UnitTests/Site/http-check.php");
		$httpRequest->addHeader("Accept", "application/json");
		$response = $httpRequest->send();
		$this->assertEquals(200, $response->status);
		$report = json_decode($response->body);
		$this->assertEquals("application/json", $report->headers->Accept);
	}
}