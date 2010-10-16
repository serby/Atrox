<?php
/**
 * @package Core
 * @subpackage Data
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 930 $ - $Date: 2009-04-23 17:53:26 +0100 (Thu, 23 Apr 2009) $
 */

/**
 *
 * @author Dom Udall <dom.udall@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 930 $ - $Date: 2009-04-23 17:53:26 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data
 */
class Atrox_Core_Data_MySql_Filter implements Atrox_Core_Data_IFilter {

	/**
	 * The Sql for the filters join
	 * @var array
	 */
	protected $joins = null;

	/**
	 * Used to parse Propertys correctly for the current datatsource
	 * @var Atrox_Core_Data_Source
	 */
	protected $dataSource;

	/**
	 * How many records to return
	 * @var int
	 */
	protected $limit = 0;

	/**
	 * How many records to offset
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * How many records to return
	 * @var int
	 */
	protected $distinctSql;

	/**
	 * The order
	 * @var array
	 */
	protected $order = array();

	/**
	 * All the conditions by which to filter
	 * @var array
	 */
	protected $conditions = array();

	/**
	 *
	 */
	public function __construct(Atrox_Core_Data_Source $dataSource) {
		$this->dataSource = $dataSource;
	}

	/**
	 * @see $limit
	 * @param int $limit
	 */
	public function setLimit($limit) {
		$this->limit = (int)$limit;
		return $this;
	}

	/**
	 * @see $limit
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @see $offset
	 * @param int $offset
	 */
	public function setOffset($offset) {
		$this->offset = (int)$offset;
		return $this;
	}

	/**
	 * @see $offset
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * @see $order
	 * @param int $order
	 */
	public function addOrder(Atrox_Core_Data_Property $property, $descending = false, $ignoreCase = false) {
		$order = new stdClass();
		$order->Property = $property;
		$order->fullOrder = $property->getDataSource()->getConnection()->parseProperty($property);
		$order->descending = $descending;
		$order->ignoreCase = $ignoreCase;
		$this->order[] = $order;
		return $this;
	}

	/**
	 * @see $order
	 * @param int $order
	 */
	public function clearOrder($order) {
		$this->order = array();
		return $this;
	}

	/**
	 * @see $order
	 * @param int $order
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getOrderSql() {
		if (!is_array($this->order)) {
			return null;
		}
		$orderSql = null;

		foreach ($this->order as $order) {
			if ($order->ignoreCase) {
				$orderSql  .= "lower(" . $order->fullOrder . ") " . ($order->descending ? "DESC" : "") . ", ";
			} else {
				$orderSql  .= $order->fullOrder . " " . ($order->descending ? "DESC" : "") . ", ";
			}
		}

		// Crop the last comma
		$orderSql = mb_substr($orderSql, 0, -2);

		if ($orderSql == null) {
			return null;
		} else {
			return "ORDER BY " . $orderSql;
		}
	}

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getJoinSql() {
		if (!is_array($this->joins)) {
			return null;
		}
		$joinSql = "";
		foreach ($this->joins as $v) {
			if ($v["As"]) {
				$joinSql .= "LEFT JOIN {$v["FullTableTo"]} AS {$v["As"]} ON {$v["FullTableFrom"]}.{$v["FullPropertyFrom"]} = {$v["As"]}.{$v["FullPropertyTo"]} ";
			} else {
				$joinSql .= "LEFT JOIN {$v["FullTableTo"]} ON {$v["FullTableFrom"]}.{$v["FullPropertyFrom"]} = {$v["FullTableTo"]}.{$v["FullPropertyTo"]} ";
			}
		}
		return $joinSql;
	}

	/**
	 *
	 * @return string Sql to generate ordered results
	 */
	public function getLimitSql() {
		if ($this->limit > 0) {
			return "LIMIT " . $this->limit;
		}
		return "";
	}

	/**
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $value
	 * @param string $operator
	 * @param string $logicalOperator
	 * @return mixed
	 */
	public function makeConditional($table, $field, $value, $operator = " = ", $logicalOperator = "AND") {
		$newCondition["FullTable"] = $this->dataSource->getConnection()->parseTable($table);
		$newCondition["FullProperty"] = $this->dataSource->getConnection()->parseField($field);
		if ($value === null) {
  	  $newCondition["Value"] = "null";
		} else if (is_array($value)) {
			$newCondition["Value"] = "('" . implode("','", $this->dataSource->getConnection()->parseValue($value)) . "')";
		} else {
  	  $newCondition["Value"] = $this->dataSource->getConnection()->parseValue($value);
		}
		$newCondition["Operator"] = $operator;
		$newCondition["LogicalOperator"] = $logicalOperator;
		$newCondition["Type"] = 0;
		return $newCondition;
	}

	/**
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $value
	 * @param string $operator
	 * @param string $logicalOperator
	 * @return Atrox_Core_Data_MySql_Filter
	 */
	public function addConditional($table, $field, $value, $operator = " = ", $logicalOperator = "AND") {
		$this->conditions[] = $this->makeConditional($table, $field, $value, $operator, $logicalOperator);
		return $this;
	}

	/**
	 * @param $conditions
	 * @param $operator
	 * @return Atrox_Core_Data_MySql_Filter
	 */
	public function addConditionalGroup($conditions, $operator = "AND") {
		if ((!is_array($conditions)) || (count($conditions) <= 0)) {
			return false;
		}
		$this->conditions[] = array("LogicalOperator" => $operator, "Type" => 1);
		$this->conditions = array_merge($this->conditions, $conditions);
		$this->conditions[] = array("LogicalOperator" => "", "Type" => 2);
		return $this;
	}

	/**
	 *
	 * @return unknown_type
	 */
	public function getConditionSql() {
		if (!is_array($this->conditions)) {
			return null;
		}
		$whereSql = "";
		$logicalOperatorLength = 0;
		$firstItem = true;
		foreach ($this->conditions as $v) {
			if ($v["Type"] == 0) {
				$whereSql .= (!$firstItem ? $v["LogicalOperator"] : "") .
					" $v[FullTable].$v[FullProperty] $v[Operator] $v[Value] ";
				$firstItem = false;
			} else if ($v["Type"] == 1) {
				$whereSql .= (!$firstItem ? $v["LogicalOperator"] : "") . " (";
				$firstItem = true;
			} else if ($v["Type"] == 2) {
				$whereSql .= ")";
			}
		}
		if (!$firstItem) {
			$whereSql = $whereSql;
		}
		if ($whereSql != null) {
			return "WHERE $whereSql";
		}
	}

	/**
	 * Sets whether or not the query should return distinct rows
	 * By default all rows are returned
	 * @param Boolean $distinct True if only distinct rows should be returned
	 * @return void
	 */
	public function setDistinct($distinct, $table = null, $field = null) {
		if ($distinct) {
			if ($table == null || $field == null) {
				$this->distinctSql = "DISTINCT";
			} else {
				$this->distinctSql = "DISTINCT ON (\"{$table}\".\"{$field}\")";
			}
		} else {
			$this->distinctSql = "";
		}
	}

	/**
	 *
	 * @return string Sql for
	 */
	public function getDistinctSql() {
		return $this->distinctSql;
	}

	public function addJoin($tableFrom, $propertyFrom, $tableTo, $propertyTo, $as = null) {
		$connection = $this->dataSource->getConnection();

		$newJoin["FullTableFrom"] = $connection->parseTable($tableFrom);
		$newJoin["FullPropertyFrom"] = $connection->parseField($propertyFrom);
		$newJoin["FullTableTo"] = $connection->parseTable($tableTo);
		$newJoin["FullPropertyTo"] = $connection->parseField($propertyTo);
		$newJoin["As"] = null;
		if ($as) {
			$newJoin["As"] =  $connection->parseTable($as);
		}
		$this->joins[] = $newJoin;
	}
}