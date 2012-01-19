<?php

namespace Sys\Database\Select
{
	class Condition
	{
		protected $_field;

		protected $_conditions;

		public function __construct($field, $conditions)
		{
			$this->_field = $field;
			$this->_conditions = $conditions;
		}

		public function getField()
		{
			return $this->_field;
		}

		public function getConditions()
		{
			return $this->_conditions;
		}
	}
}