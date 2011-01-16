<?php
require_once("PHPUnit/Framework.php");
require_once("Atrox/Core/Application.php");

/**
 * @author Dom Udall (Clock Limited) {@link mailto:dom.udall@clock.co.uk }
 * @copyright Clock Limited 2010
 */
class Atrox_Core_Data_MySql_FilterTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Atrox_Core_Application
	 */
	protected $application;

	 public function setUp() {
		$this->application = Atrox_Core_Application::getInstance(Mock_MySql_Application::getInstance());
		$this->application->getConnection()->connect();
		$this->application->createBlogTable();
		$this->application->createBlogCommentTable();
	}

	 public function tearDown() {
		$this->application->dropBlogTable();
		$this->application->dropBlogCommentTable();
	}

	 public function testJoin() {
		$blogDataSource = Mock_MySql_Blog::getDataSource();
		$entity = $blogDataSource->makeNew();
		$entity->set("Title", "This is the first blog");
		$entity->set("Description", "and this is the first description");
		$blogDataSource->create($entity);


		$blogCommentDataSource = Mock_MySql_BlogComment::getDataSource();

		$entity = $blogCommentDataSource->makeNew();
		$entity->set("BlogId", 2);
		$entity->set("Comment", "WTF");
		$blogCommentDataSource->create($entity);

		$entity = $blogCommentDataSource->makeNew();
		$entity->set("BlogId", 1);
		$entity->set("Comment", "Blog Comment");
		$blogCommentDataSource->create($entity);

		$blog = $entity->getRelation("BlogId");

		$filter = $blogCommentDataSource->makeFilter();
		$filter->addJoin($blogCommentDataSource->getTableName(), "BlogId", $blogDataSource->getTableName(), "Id");
		$filter->addConditional($blogDataSource->getTableName(), "Title", "This is the first blog");
		$dataset = $blogCommentDataSource->retrieve($filter);
		$this->assertType("Atrox_Core_Data_Dataset", $dataset);
		$this->assertType("Mock_BlogComment", $blogCommentRecord = $dataset->getNext());
		$this->assertEquals("Blog Comment", $blogCommentRecord->get("Comment"));
	}
}