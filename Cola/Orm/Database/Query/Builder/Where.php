<?php 
/**
 * Database query builder for WHERE statements. See [Query Builder](/database/query/builder) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
abstract class Orm_Database_Query_Builder_Where extends Orm_Database_Query_Builder {

	// WHERE ...
	protected $_where = array();

	// ORDER BY ...
	protected $_order_by = array();

	// LIMIT ...
	protected $_limit = NULL;

	/**
	 * Alias of and_where()
	 *
	 * @param   mixed   $column  column name or array($column, $alias) or object
	 * @param   string  $op      logic operator
	 * @param   mixed   $value   column value
	 * @return  self
	 */
	public function where($column, $op, $value)
	{
		return $this->and_where($column, $op, $value);
	}

	/**
	 * Creates a new "AND WHERE" condition for the query.
	 *
	 * @param   mixed   $column  column name or array($column, $alias) or object
	 * @param   string  $op      logic operator
	 * @param   mixed   $value   column value
	 * @return  self
	 */
	public function and_where($column, $op, $value)
	{
		$this->_where[] = array('AND' => array($column, $op, $value));

		return $this;
	}

	/**
	 * Creates a new "OR WHERE" condition for the query.
	 *
	 * @param   mixed   $column  column name or array($column, $alias) or object
	 * @param   string  $op      logic operator
	 * @param   mixed   $value   column value
	 * @return  self
	 */
	public function or_where($column, $op, $value)
	{
		$this->_where[] = array('OR' => array($column, $op, $value));

		return $this;
	}

	/**
	 * Alias of and_where_open()
	 *
	 * @return  self
	 */
	public function where_open()
	{
		return $this->and_where_open();
	}

	/**
	 * Opens a new "AND WHERE (...)" grouping.
	 *
	 * @return  self
	 */
	public function and_where_open()
	{
		$this->_where[] = array('AND' => '(');

		return $this;
	}

	/**
	 * Opens a new "OR WHERE (...)" grouping.
	 *
	 * @return  self
	 */
	public function or_where_open()
	{
		$this->_where[] = array('OR' => '(');

		return $this;
	}

	/**
	 * Closes an open "WHERE (...)" grouping.
	 *
	 * @return  self
	 */
	public function where_close()
	{
		return $this->and_where_close();
	}

	/**
	 * Closes an open "WHERE (...)" grouping or removes the grouping when it is
	 * empty.
	 *
	 * @return  self
	 */
	public function where_close_empty()
	{
		$group = end($this->_where);

		if ($group AND reset($group) === '(')
		{
			array_pop($this->_where);

			return $this;
		}

		return $this->where_close();
	}

	/**
	 * Closes an open "WHERE (...)" grouping.
	 *
	 * @return  self
	 */
	public function and_where_close()
	{
		$this->_where[] = array('AND' => ')');

		return $this;
	}

	/**
	 * Closes an open "WHERE (...)" grouping.
	 *
	 * @return  self
	 */
	public function or_where_close()
	{
		$this->_where[] = array('OR' => ')');

		return $this;
	}

	/**
	 * Applies sorting with "ORDER BY ..."
	 *
	 * @param   mixed   $column     column name or array($column, $alias) or object
	 * @param   string  $direction  direction of sorting
	 * @return  self
	 */
	public function order_by($column, $direction = NULL)
	{
		$this->_order_by[] = array($column, $direction);

		return $this;
	}

	/**
	 * Return up to "LIMIT ..." results
	 *
	 * @param   integer  $number  maximum results to return or NULL to reset
	 * @return  self
	 */
	public function limit($number)
	{
		$this->_limit = $number;

		return $this;
	}

} // End Database_Query_Builder_Where
