<?php

/**
 * Object used to perform all SELECT queries in the database
 */

namespace Sys\Database
{
	class Select
	{
		/**
		 * SQL where conditions
		 *
		 * @var <array>
		 */
		protected $_where = array();

		/**
		 * SQL Where values
		 *
		 * @var <array>
		 */
		protected $_values = array();

		/**
		 * SQL Page Limit
		 *
		 * @var <int>
		 */
		protected $_page = null;

		/**
		 * SQL Size Limit
		 *
		 * @var <int>
		 */
		protected $_size = null;

		/**
		 * List of fields to order by
		 *
		 * @var <array>
		 */
		protected $_order = array();

		/**
		 * Comma separated list of fields to use in the SQL Query
		 *
		 * @var <string>
		 */
		protected $_selectFields = '*';

		/**
		 * The main table of the select query
		 * 
		 * @var <string>
		 */
		protected $_table;

		public function __construct($table)
		{
			$this->_table = $table;
		}

		/**
		 * Add a WHERE condition to the query
		 *
		 * @param <string> $field
		 * @param <array> $condition
		 * @return Select
		 */
		public function addCondition($field, $condition)
		{
			$this->_where[] = new \Sys\Database\Select\Condition($field, $condition);
			return $this;
		}

		/**
		 * Add a field to the ORDER clause
		 *
		 * @param <string> $field
		 * @param <string> $direction
		 * @return <Select>
		 */
		public function addOrderField($field, $direction)
		{
			$dir = strtolower($direction);
			if ($dir != 'asc' && $dir != 'desc')
				return;
			$this->_order[$field] = $direction;
			return $this;
		}

		/**
		 * Set the SELECT fields for the query
		 *
		 * @param <array> $fields
		 * @return Select
		 */
		public function setFields($fields)
		{
			if (!is_array($fields))
				$this->_selectFields = $fields;
			else
				$this->_selectFields = implode(",", $fields);
			return $this;
		}

		/**
		 * Set the SQL LIMIT page value
		 *
		 * @param <int> $page
		 * @return Select
		 */
		public function setPage($page)
		{
			$this->_page = $page;
			return $this;
		}

		/**
		 * Return the SQL LIMIT page value
		 * 
		 * @return <int>
		 */
		public function getPage()
		{
			return $this->_page;
		}

		public function setSize($size)
		{
			$this->_size = $size;
			return $this;
		}

		public function getSize()
		{
			return $this->_size;
		}

		public function setPageSize($page, $size)
		{
			$this->_page = $page;
			$this->_size = $size;
			return $this;
		}

		public function getValues()
		{
			return $this->_values;
		}
		
		public function getCount()
		{
			return $this->_getSelectString('COUNT(*) as total', null);
			//return $this;
		}

		public function getWhere()
		{
			$this->_values = array();
			$where = '';
			if (sizeof($this->_where) > 0)
			{
				$iterations = 1;
				foreach ($this->_where as $column)
				{
					if ($iterations > 1)
					{
						$where .= ' AND ';
					}
					//$where .= $this->_getWhere($column);
					$where .= $this->_getWhere($column->getField(), $column->getConditions());
					$iterations++;
				}
			}
			return $where;
		}

		protected function _getWhere($field, $conditions)
		{
			$query = '';
			if (!(is_array($conditions)))
			{
				$query = $this->_sqlWhere($field, 'eq', $conditions);
			}
			else
			{
				$iterations = 1;
				foreach ($conditions as $compare => $value)
				{
					if (is_array($value))
						$query .= $this->_getWhere($field, $value);
					if (is_string($compare))
					{
						if ($iterations > 1)
						{
							$query .= ' AND ';
						}
						$query .= $this->_sqlWhere($field, $compare, $value);
					}
					else if ($iterations < sizeof($conditions))
						$query .= ' OR ';
					$iterations++;
				}
				if ($iterations > 2)
				{
					$query = '('.$query.')';
				}
			}
			return $query;
		}

		protected function _sqlWhere($field, $compare, $value)
		{
			if ($compare == 'in' || $compare == 'nin')
			{
				foreach ($value as $index)
					$this->_values[] = $index;
			}
			else
			{
				$this->_values[] = $value;
			}
			switch ($compare)
			{
				case 'eq' :
					return $field.'= ? ';
					break;
				case 'neq' :
					return $field.'<> ? ';
					break;
				case 'nul' :
					return $field.' IS NULL';
					break;
				case 'notnull' :
					return $field.' IS NOT NULL';
					break;
				case 'in' :
					return $field.' IN ('.implode(',', array_fill(0, count($value), '?')).') ';
					break;
				case 'nin' :
					return $field.' NOT IN ('.implode(',', array_fill(0, count($value), '?')).') ';
					break;
				case 'like' :
					return $field.' LIKE ? ';
					break;
				case 'nlike' :
					return $field.' NOT LIKE ? ';
					break;
				case 'gt' :
					return $field.' > ?';
					break;
				case 'lt' :
					return $field.' < ?';
					break;
				case 'moreq' :
				case 'from' :
				case 'gteq' :
					return $field.' >= ? ';
					break;
				case 'to' :
				case 'lteq' :
					return $field.' <= ? ';
					break;
			}
		}

		public function __toString()
		{
			return $this->_getSelectString($this->_selectFields, $this->getSize());
		}
		
		protected function _getSelectString($selectWhat, $pageSize)
		{
			$select = 'SELECT '.$selectWhat.' FROM '.$this->_table;
			// Set the WHERE conditions
			if ($this->getWhere() != '')
				$select .= ' WHERE '.$this->getWhere();
			// ORDER BY the results
			if (sizeof($this->_order) > 0)
			{
				$select .= ' ORDER BY '.\Z::getHelper('core')->array_implode(' ', ',', $this->_order);
			}
			// Then set the LIMIT
			if ($pageSize !== null)
			{
				//($this->getPage() === null) ? $this->setPage(0) : $this->setPage((int)$this->_page) ;
				$select .= ' LIMIT '.$this->_getSqlPage().', '.$this->getSize();
			}
			return $select;
		}

		protected function _getSqlPage()
		{
			if ($this->getPage() === null)
				return 0;
			$page = (int)$this->getPage();
			if ($page > 0)
				return ($page * $this->getSize() - $this->getSize());
		}
	}
}