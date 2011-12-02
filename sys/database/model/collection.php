<?php

namespace Sys\Database\Model
{
	class Collection
	{
		/**
		 * @var DatabaseModel The model it is linked to
		 */
		protected $model;

		/**
		 * @var array The list of items this collection has (an array of $model objects)
		 */
		protected $items = array();

		public function __construct(\Sys\Database\Model $model)
		{
			$this->model = $model;
		}

		/**
		 * @return Returns a list of DatabaseModel objects, based on the supplied criteria
		 */
		public function getAll()
		{
			$stmt = Pdo::query('SELECT * FROM '.$this->model->getTable());
			//$objects = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->model->getClassName(), array($this->model->getTable()));
			$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$objects = array();
			foreach ($results as $result)
			{
				$class = $this->model->getClassName();
				$temp = new $class($this->model->getTable());
				foreach ($result as $key => $value)
				{
					$property = 'set'.ucfirst($key);
					$temp->$property($value);
				}
				$objects[] = $temp;
			}
			return $objects;
		}
	}
}