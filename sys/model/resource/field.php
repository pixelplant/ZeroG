<?php

namespace Sys\Model\Resource
{
	class Field
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

		public function __construct($name, $type)
		{
			$this->name = $name;
			$this->type = $type;
		}
	}
}
