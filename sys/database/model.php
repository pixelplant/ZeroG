<?php

namespace Sys\Database
{
	class Model extends \Sys\Model
	{
		/**
		 * @var string The table name this models links to (if it uses DB data)
		 */
		protected $table = NULL;

		public function __construct($table = NULL)
		{
			parent::__construct();
			if ($table === NULL)
			{
				$this->table = get_class($this);
			}
			else
				$this->table = $table;
			$this->getFieldNames();
		}

		/**
		 * @return void Fetches all the fieldnames of the table and sets them as this model's properties
		 */
		public function getFieldNames()
		{
			$stmt = Pdo::query('SHOW FIELDS FROM '.$this->table);
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($result as $row)
			{
				$this->data[$row['Field']] = '';
			}
		}

		public function load($id)
		{
			$stmt = Pdo::query('SELECT * FROM '.$this->table.' WHERE id='.$id);
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			foreach ($result as $key => $value)
			{
				$this->data[$key] = $value;
			}
			return $this;
		}

		/**
		 * @return string The name of the table used by this model
		 */
		public function getTable()
		{
			return $this->table;
		}

		public function getCollection()
		{
			$collection = new Collection($this);
			return $collection->getAll();
		}

	}
}
