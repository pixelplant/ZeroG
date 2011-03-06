<?php

namespace Sys\Model\Resource
{
	abstract class Field
	{
		/**
		 * The name of this field
		 * @var <string>
		 */
		protected $name;

		/**
		 * Field type, eg: varchar, float, text, blob
		 * @var <string>
		 */
		protected $type;

		/**
		 * Custom or predefined filter to be applied to this field
		 * @var <string>
		 */
		protected $filter = '';

		/**
		 * The field's value
		 * @var <mixed>
		 */
		protected $value = '';

		public function __construct($name, $type)
		{
			$this->name = $name;
			$this->type = $type;
		}

		public function __toString()
		{
			return $this->value;
		}

		public function getValue()
		{
			return $this->value;
		}

		public function setValue($newValue)
		{
			$this->value = $newValue;
		}
	}
}
