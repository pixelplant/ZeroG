<?php

namespace Sys\Database
{
	interface CrudInterface
	{
		// Load a record by ID based on specified idField
		public function load($table, $idField, $id = 0);
		// Load a collection of records by criteria and instantiate them in an
		// an array of type $class
		public function find($class, $criteria = array());

		// Update an existing record in the db
		public function save($table, $idField, $id, $data = array());
		// Add a new record to the db and return the lastInsertId
		public function add($table, $data = array());
	}
}
