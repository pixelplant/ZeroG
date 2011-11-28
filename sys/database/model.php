<?php

namespace Sys\Database
{
	class Model extends \Sys\Model
	{
		/**
		 * @var string The table name this models links to (since it's a Database Model
		 */
		protected $table = NULL;

		/**
		 *
		 * @var <\Sys\Model\Resource>
		 */
		protected $resource = NULL;

		/**
		 * We do not declare it as static cause in the future the same pdo connection
		 * might link to different PDO drivers or to multiple PDO connections using
		 * the same driver (eg: connecting to 3 different mysql databases at a time)
		 * In the future I also plan to add 2 different adapters per model for writing and reading data
		 * 
		 * @var <\Sys\Database\Pdo> A reference to the db connection
		 */
		protected $pdo = NULL;

		public function __construct($resourceName)
		{
			parent::__construct();
			$this->pdo = \Z::getDatabaseConnection('default_setup');
			$this->table = $resourceName;
			$this->pdo->cacheFields($this->table);
			//$this->resource = $this->pdo->cacheFields($resourceName);
			//parent::__construct($resourceName);
		}

		/*public function getCollection()
		{
			$collection = new Collection($this);
			return $collection->getAll();
		}*/

		/**
		 * Loads a record of this model by id
		 *
		 * @param <int> $id
		 * @return Model
		 */
		public function load($id = 0)
		{
			// load model data
			$this->data = $this->pdo->load($this->table, $id);
			// if we load the model data then this is not a new model
			$this->isNew = false;
			return $this;
		}

		/**
		 * The search criteria to use for the query
		 * 
		 * @param <array> $criteria
		 * @return <array> Returns an array of Model items
		 */
		public function find($criteria = array())
		{
			$criteria['from'] = $this->table;
			$collection = $this->pdo->find($this->className, $criteria);
			return $collection;
		}

		/**
		 * Save our model data
		 * 
		 * @return Model
		 */
		public function save()
		{
			if ($this->isNew)
			{
				// if it's a new record, insert it
				$this->pdo->add($this->table, $this->data);
				// store the last inserted id
				$this->setId($this->pdo->lastInsertId());
				// and make sure next time we save this object, we only update it's data
				$this->isNew = false;
			}
			else
				// if it's not new, just update it
				$this->pdo->save($this->table, $this->getId(), $this->data);
			return $this;
		}

	}
}
