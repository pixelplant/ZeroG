<?php

namespace Sys\Database
{
	class Model extends \Sys\Model
	{
		/**
		 * @var string The table name this models links to (if it uses DB data)
		 */
		protected $table = NULL;

		public function __construct($resourceName)
		{
			parent::__construct($resourceName);
		}

		public function getCollection()
		{
			$collection = new Collection($this);
			return $collection->getAll();
		}

	}
}
