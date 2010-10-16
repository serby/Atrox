<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Paul Serby (Clock Limited) {@link mailto:paul.serby@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_MySql_SourceUsingMemcachedTest extends Atrox_Core_Data_MySql_SourceTest {

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_MySql_Application::getInstance());
		$this->application->getConnection()->connect();
		$this->application->setCache($cache = new Atrox_Core_Caching_Memcached());
		$cache->addServer("localhost");

		$this->application->createBlogTable();
		$this->application->createBlogCommentTable();

	}

	 public function tearDown() {
		$this->application->getConnection()->query("DROP TABLE TempBlog;");
		$this->application->getConnection()->query("DROP TABLE TempBlogComment;");
		$this->application->getConnection()->close();
		$this->application->getCacheManager()->clearAll();
	}
}