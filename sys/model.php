<?php

namespace Sys
{

	abstract class Model
	{
		/**
		 * @var <array> Data contained by the models. If a database is used, a direct mapping of the table fields is made
		 */
		protected $data = array();

		/**
		 * @var <string> An internal cache of the class name, so we won't have to recall get_class everytime we need this info
		 */
		protected $className;

		/**
		 * Is this a new model, or a loaded one?
		 * @var <bool>
		 */
		protected $isNew;

		public function __construct()
		{
			//$this->resource = \Z::getResource($resourceName);
			//$this->data = $this->resource->getFields();
			$this->isNew = true;
			$this->className = get_class($this);
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
			$name = strtolower($name);
			$type = substr($name, 0, 3);
			$field = substr($name, 3);
			switch ($type)
			{
				//case 'get': return $this->data[$field]->getValue(); break;
				//case 'set': $this->data[$field]->setValue($arguments[0]); return; break;
				case 'get': 
					if (!isset($this->data[$field]))
						throw new \Sys\Exception("Property: $field not found in class: $this->className");
					return $this->data[$field];
					break;
				case 'set':
					$this->data[$field] = $arguments[0];
					return $this;
					break;
			}
		}

		/**
		 * @return <string> Returns this object's classname
		 */
		public function getClassName()
		{
			return $this->className;
		}

	}
}
