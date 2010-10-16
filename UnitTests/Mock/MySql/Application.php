<?php
/**
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */

/**
 *
 * @author Dom Udall {@link mailto:dom.udall@clock.co.uk dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 990 $ - $Date: 2009-05-27 23:34:16 +0100 (Wed, 27 May 2009) $
 * @package Core
 */
class Mock_MySql_Application extends Atrox_Core_Application {

	/**
	 * Returns the one instance of Mock_MySql_Application
	 *
	 * @return Mock_Application
	 */
	 public static function getInstance($object = null) {
		static $application;
		if (!isset($application)) {
			$application = new Mock_MySql_Application();
		}
		return new $application;
	}

	protected function __construct() {
		parent::__construct();
		// testdatabase - This should be defined in you hosts file
		$this->setLogPath(realpath("../Log"));
		$this->setConnection(new Atrox_Core_Data_MySql_Connection("localhost", "username", "password", "database"));
		$this->getConnection()->connect();
	}

	 public function createBlogTable() {
		$sql = <<<SQL
CREATE TABLE TempBlog (
    Id INT NOT NULL AUTO_INCREMENT,
    Title TEXT NOT NULL,
    Description TEXT NOT NULL,
    DateCreated TIMESTAMP DEFAULT NOW() NOT NULL,
    Active BOOLEAN,
    PRIMARY KEY ( Id )
);
SQL;

		try { $this->getConnection()->query($sql); } catch (Exception $e) {}
	}

	 public function dropBlogTable() {
		try { $this->getConnection()->query("DROP TABLE TempBlog;"); } catch (Exception $e) {}
	}

	 public function createBlogCommentTable() {

		$sql = <<<SQL
CREATE TABLE TempBlogComment (
    Id INT NOT NULL AUTO_INCREMENT,
    BlogId INT NOT NULL,
    Comment TEXT NOT NULL,
    DateCreated TIMESTAMP DEFAULT NOW() NOT NULL,
    PRIMARY KEY ( Id )
);
SQL;

		try { $this->getConnection()->query($sql); } catch (Exception $e) {}
	}

	 public function dropBlogCommentTable() {
		try { $this->getConnection()->query("DROP TABLE TempBlogComment;"); } catch (Exception $e) {}
	}
}