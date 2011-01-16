<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 */

/**
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 * @package Core
 * @subpackage Data
 */
interface Atrox_Core_Data_IFilter {

	public function __construct(Atrox_Core_Data_Source $dataSource);

	/**
	 * @see $limit
	 * @param int $limit
	 */
	public function setLimit($limit);

	/**
	 * @see $limit
	 */
	public function getLimit();

	/**
	 * @see $order
	 * @param int $order
	 */
	public function addOrder(Atrox_Core_Data_Property $property, $descending = false, $ignoreCase = false);

	/**
	 * @see $order
	 * @param int $order
	 */
	public function clearOrder($order);

	/**
	 * @see $order
	 * @param int $order
	 */
	public function getOrder();

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getOrderSql();

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getJoinSql();

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getLimitSql();
	/**
	 *
	 * @param $table
	 * @param $field
	 * @param $value
	 * @param $operator
	 * @param $logicalOperator
	 * @return unknown_type
	 */
	public function makeConditional($table, $field, $value, $operator = " = ", $logicalOperator = "AND");

	/**
	 *
	 * @param $table
	 * @param $field
	 * @param $value
	 * @param $operator
	 * @param $logicalOperator
	 * @return unknown_type
	 */
	public function addConditional($table, $field, $value, $operator = " = ", $logicalOperator = "AND");

	/**
	 * @param $conditions
	 * @param $operator
	 * @return unknown_type
	 */
	public function addConditionalGroup($conditions, $operator = "AND");

	/**
	 *
	 * @return unknown_type
	 */
	public function getConditionSql();

	/**
	 * Sets whether or not the query should return distinct rows
	 * By default all rows are returned
	 * @param Boolean $distinct True if only distinct rows should be returned
	 * @return void
	 */
	public function setDistinct($distinct, $table = null, $field = null);

	/**
	 *
	 * @return string Sql for
	 */
	public function getDistinctSql();
}