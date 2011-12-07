<?php

namespace Sys
{
/**
 * The basic model class
 */
	class Model
	{
		/**
		 * @var <array> Data contained by the models. If a database is used, a direct mapping of the table fields is made
		 */
		protected $_data = array();

		/**
		 * @var <string> An internal cache of the class name, so we won't have to recall get_class everytime we need this info
		 */
		protected $_className;

		public function __construct()
		{
			$this->_construct();
		}

		protected function _construct()
		{
			$this->_className = get_class($this);
		}

		/**
		 * Gets or sets the data of this model (dynamic properties)
		 * 
		 * @param  <string> $name name of the array item to get or set
		 * @param  <array> $arguments in case of set, the value of the item
		 * @return <void>
		 */
		public function __call($name, $arguments)
		{
			$type = strtolower(substr($name, 0, 3));
			$field = substr($name, 3);
			// camelcase to underscore
			$field = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $field));
			switch ($type)
			{
				//case 'get': return $this->_data[$field]->getValue(); break;
				//case 'set': $this->_data[$field]->setValue($arguments[0]); return; break;
				case 'get': 
					if (!isset($this->_data[$field]))
						return FALSE;
						//throw new \Sys\Exception("Property: $field not found in class: $this->_className");
					return $this->_data[$field];
					break;
				case 'set':
					$this->_data[$field] = $arguments[0];
					return $this;
					break;
			}
		}

		/**
		 * Change all model data using an array
		 * @param <array> $newData
		 */
		public function setData($field, $value = null)
		{
			if (!is_string($field))
				$this->_data = $field;
			else
				$this->_data[$field] = $value;
			/*if (!is_array($newData))
				throw new Sys\Exception('The data you want to assign to the model using ->setData must be an array');
			$this->_data = $newData;
			return $this;*/
		}

		/**
		 * Add new data to the model or overwrite existing data using an array
		 * @param <array> $mergeData
		 */
		public function mergeData($mergeData)
		{
			if (is_array($mergeData))
				$this->_data = array_merge($this->_data, $mergeData);
			return $this;
		}

		/**
		 * Return the data a model holds
		 * @return <array>
		 */
		public function getData($field = '')
		{
			if ($field == '')
				return $this->_data;
			else
				if (isset($this->_data[$field]))
					return $this->_data[$field];
				else
					return FALSE;
		}

		/**
		 * @return <string> Returns this object's classname
		 */
		public function getClassName()
		{
			return $this->_className;
		}

	}
}
