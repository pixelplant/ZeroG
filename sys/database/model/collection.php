<?php

namespace Sys\Database\Model
{
/**
 * Description of Collection
 *
 * @author radu.mogos
 */
	class Collection extends \Sys\Model\Collection
	{
		protected $_resource;

		protected $_resourceName;

		protected $_pdo;

		public function load()
		{
			if ($this->isLoaded())
				return;
			$this->_isCollectionLoaded = true;
			parent::load();
			$data = $this->_getReadAdapter()->query('SELECT * FROM '.$this->_getResource()->getTable().' WHERE 1');
			foreach ($data as $row)
			{
				$record = \Z::getModel($this->_itemObjectClass);
				$record->setData($row);
				$this->_items[] = $record;
			}
			return $this;
		}
		
		protected function _getReadAdapter()
		{
			return $this->_pdo;
		}
		protected function _getWriteAdapter()
		{
			return $this->_pdo;
		}

		protected function _getResource()
		{
			return $this->_resource;
		}

		protected function _construct()
		{
			parent::_construct();
		}

		protected function _init($resourceName)
		{
			$this->_pdo = \Z::getDatabaseConnection('default_setup');
			$this->_itemObjectClass = $resourceName;
			$this->_resourceName = $resourceName;
			$this->_resource = \Z::getResource($resourceName);
		}

		/**
		 * Adds a field to the WHERE query
		 *
		 * @param <string> $field
		 * @param <mixed> $condition
		 */
		public function addFieldToFilter($field, $condition=null)
		{
			if ($this->_getResource()->hasTableField($field))
				$field = $this->_getResource()->getTableField($field);
			else
				throw new \Sys\Exception('The field => %s is not defined in the collection resource', $field);
		}
	}
}
