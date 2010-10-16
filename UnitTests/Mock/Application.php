<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */

/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */
class Mock_Application extends Atrox_Core_Application {

	/**
	 * Returns the one instance of Mock_Application
	 *
	 * @return Mock_Application
	 */
	 public static function getInstance($object = null) {
		static $application;
		if (!isset($application)) {
			$application = new Mock_Application();
		}
		return new $application;
	}

	protected function __construct() {
		parent::__construct();
		// testdatabase - This should be defined in you hosts file
		$validConnectionString = "host=testdatabase port=5432 dbname=AtroxTest user=WebUser password=test";
		$this->setLogPath(realpath("../Log"));
		$this->setConnection(new Atrox_Core_Data_PostgreSql_Connection($validConnectionString));
		$this->getConnection()->connect();
	}

	 public function createBlogTable() {

		$sql = <<<SQL
CREATE TEMPORARY TABLE "TempBlog" (
    "Id" serial NOT NULL,
    "Title" text NOT NULL,
    "Description" text NOT NULL,
    "DateCreated" timestamp without time zone DEFAULT now() NOT NULL,
    "Active" boolean
);
SQL;

		$this->getConnection()->query($sql);
	}

	 public function dropBlogTable() {
		$this->getConnection()->query("DROP TABLE \"TempBlog\";");
	}

	 public function createBlogCommentTable() {

		$sql = <<<SQL
CREATE TEMPORARY TABLE "TempBlogComment" (
    "Id" serial NOT NULL,
    "BlogId" int NOT NULL,
    "Comment" text NOT NULL,
    "DateCreated" timestamp without time zone DEFAULT now() NOT NULL
);
SQL;

		$this->getConnection()->query($sql);
	}

	 public function dropBlogCommentTable() {
		$this->getConnection()->query("DROP TABLE \"TempBlogComment\";");
	}
}