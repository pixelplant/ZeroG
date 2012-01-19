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

		protected $_select;

		/**
		 * Called when collection is created
		 */
		protected function _construct()
		{
			$this->_eventPrefix = 'sys_database_collection';
			parent::_construct();
		}

		/**
		 * Initialization code
		 *
		 * @param <string> $resourceName
		 */
		protected function _init($resourceName)
		{
			$this->_pdo = \Z::getDatabaseConnection('default_setup');
			$this->_itemObjectClass = $resourceName;
			$this->_resourceName = $resourceName;
			$this->_resource = \Z::getResource($resourceName);
			$this->_select = new \Sys\Database\Select($this->_resource->getTable());
			parent::_init($resourceName);
		}

		/**
		 * Returns the current SELECT instance
		 *
		 * @return <\Sys\Database\Select>
		 */
		public function getSelect()
		{
			return $this->_select;
		}

		/**
		 * Add fields to the SELECT query
		 *
		 * @param <array> $fields
		 */
		public function addFieldsToSelect($fields)
		{
			$this->getSelect()->setFields($fields);
		}

		/**
		 * Set sorting fields
		 *
		 * @param <string> $field
		 * @param <string> $direction
		 */
		public function addFieldToSort($field, $direction = 'ASC')
		{
			if ($this->_getResource()->hasTableField($field))
			{
				$this->getSelect()->addOrderField($field, $direction);
				return $this;
			}
			else
				throw new \Sys\Exception('The field => %s is not defined in the collection resource', $field);
		}

		/**
		 * Adds a field to the WHERE query
		 *
		 * @param <string> $field
		 * @param <mixed> $condition
		 */
		public function addFieldToFilter($field, $condition = null)
		{
			if ($condition === null)
				return $this;
			if ($this->_getResource()->hasTableField($field))
			{
				$this->getSelect()->addCondition($field, $condition);
				return $this;
			}
			else
				throw new \Sys\Exception('The field => %s is not defined in the collection resource', $field);
		}

		/**
		 * Set the SQL Page and Limit
		 *
		 * @param <int> $page Starting page
		 * @param <int> $size Record limit per page
		 * @return Collection
		 */
		public function setPage($page, $size)
		{
			$this->getSelect()
					->setPageSize($page, $size);
			return $this;
		}

		/**
		 * Set the SQL page
		 *
		 * @param <int> $page
		 * @return Collection
		 */
		public function setCurPage($page)
		{
			$this->getSelect()->setPage($page);
			return $this;
		}

		/**
		 * Load the current collection data from the database
		 * 
		 * @return Collection
		 */
		public function load()
		{
			if ($this->isLoaded())
				return;
			$this->_beforeLoad();
			$this->_isCollectionLoaded = true;
			//parent::load();
			//$data = $this->_getReadAdapter()->query('SELECT * FROM '.$this->_getResource()->getTable().' WHERE 1');
			$sth = $this->_getReadAdapter()->prepare($this->getSelect());
			$sth->execute($this->getSelect()->getValues());
			while ($row = $sth->fetch(\PDO::FETCH_ASSOC))
			{
				$record = \Z::getModel($this->_itemObjectClass);
				$record->setData($row);
				$record->setIsNew(false);
				$this->_items[] = $record;
			}
			$this->_afterLoad();
			return $this;
		}

		/**
		 * Sets the data sent to the event
		 *
		 * @return <array>
		 */
		protected function _getEventData()
		{
			return array('object' => $this);
		}

		/**
		 * Executed before the collection is loaded
		 *
		 * @return Collection
		 */
		public function _beforeLoad()
		{
			\Z::dispatchEvent('collection_load_before', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_load_after', $this->_getEventData());
			return $this;
		}

		/**
		 * Executed after the collection is loaded
		 *
		 * @return Collection
		 */
		public function _afterLoad()
		{
			\Z::dispatchEvent('collection_load_after', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_load_after', $this->_getEventData());
			return $this;
		}

		/**
		 * Returns the PDO read adapter
		 *
		 * @return <\Sys\Database\PDO>
		 */
		protected function _getReadAdapter()
		{
			return $this->_pdo;
		}

		/**
		 * Returns the PDO write adapter
		 * 
		 * @return <\Sys\Database\PDO>
		 */
		protected function _getWriteAdapter()
		{
			return $this->_pdo;
		}

		/**
		 * Returns the resource used by this collection
		 * 
		 * @return <\Sys\Database\Resource>
		 */
		protected function _getResource()
		{
			return $this->_resource;
		}

		/**
		 * Name of the used resource
		 * 
		 * @return <string>
		 */
		public function getResourceName()
		{
			return $this->_resourceName;
		}
	}
}
