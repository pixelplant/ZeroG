<?php

/**
 * Description of Resource
 *
 * @author radu.mogos
 */
namespace Sys\Database
{
	class Resource extends \Sys\Model\Resource
	{
		/**
		 * Table field representing the id
		 *
		 * @var <string>
		 */
		protected $_idField;

		/**
		 * Table name the resource links to
		 *
		 * @var <string>
		 */
		protected $_table;

		/**
		 * Cached list of table fields
		 *
		 * @var <array>
		 */
		protected $_tableFields;

		/**
		 * We do not declare it as static cause in the future the same pdo connection
		 * might link to different PDO drivers or to multiple PDO connections using
		 * the same driver (eg: connecting to 3 different mysql databases at a time)
		 * In the future I also plan to add 2 different adapters per model for writing and reading data
		 *
		 * @var <\Sys\Database\Pdo> A reference to the db connection
		 */
		protected $_pdo = NULL;
		
		protected function _init($table, $idField = null)
		{
			$this->_pdo = \Z::getDatabaseConnection('default_setup');
			if ($idField === null)
				$idField = 'id';
			$this->_idField = $idField;
			$this->_table = \Z::getConfig()->getResourceTable($table);
			$this->_tableFields = $this->_pdo->cacheFields($this->_table);
		}

		protected function _construct() {}
		protected function _getReadAdapter()
		{
			return $this->_pdo;
		}
		protected function _getWriteAdapter()
		{
			return $this->_pdo;
		}

		protected function _afterLoad(\Sys\Model $model)
		{
			return $this;
			//\Z::dispatchEvent('resource_after_load');
		}

		protected function _beforeSave(\Sys\Model $model)
		{
			return $this;
		}

		protected function _afterSave(\Sys\Model $model)
		{
			return $this;
		}

		public function getIdField()
		{
			return $this->_idField;
		}

		public function load(\Sys\Model $model, $value)
		{
			$field = $this->getIdField();
			if ($this->_getReadAdapter() && !is_null($value))
			{
				$data = $this->_getReadAdapter()->load($this->_table, $field, $value);
				if ($data && is_array($data))
					$model->setData($data);
			}
			$this->_afterLoad($model);

			return $this;
		}

		public function save(\Sys\Model $model)
		{
			$id = null;
			$this->_beforeSave($model);
			if ($model->getId())
			{
				$this->_getWriteAdapter()->save($this->_table, $this->getIdField(), $model->getId(), $model->getData());
				$id = $model->getId();
			}
			else
			{
				$this->_getWriteAdapter()->add($this->_table, $model->getData());
				$id = $this->_getWriteAdapter()->lastInsertId();
			}
			$this->_afterSave($model);
			return $id;
		}

		/**
		 * Check if a field exists in our current table
		 *
		 * @param <string> $name
		 * @return <bool>
		 */
		public function hasTableField($name)
		{
			return in_array($name, $this->_tableFields);
		}

		/**
		 * Return the resource's table
		 *
		 * @return <string>
		 */
		public function getTable()
		{
			return $this->_table;
		}
	}
}
