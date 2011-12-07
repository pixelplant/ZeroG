<?php

namespace Sys\Database
{
	class Model extends \Sys\Model
	{
		/**
		 * Resource object
		 *
		 * @var <\Sys\Model\Resource>
		 */
		protected $_resource = NULL;

		/**
		 * Name of resource used by this model
		 * 
		 * @var <string>
		 */
		protected $_resourceName;

		/**
		 * Name of the collection resource
		 *
		 * @var <string>
		 */
		protected $_resourceCollectionName;

		/**
		 * Event prefix for dispatching events
		 * 
		 * @var <string>
		 */
		protected $_eventPrefix = 'sys_database_model';

		/**
		 * Name of the field used as identifier
		 * 
		 * @var <string>
		 */
		protected $_idField;

		protected function _construct()
		{
			parent::_construct();
			//$this->_pdo->cacheFields($this->_table);
		}

		protected function _init($resource, $idField = null)
		{
			if ($idField === null)
				$idField = 'id';
			$this->_idField = $idField;
			$this->_setResourceModel($resource);
			$this->_resource = \Z::getResource($this->_resourceName);
		}

		public function getCollection()
		{
			if (empty($this->_resourceCollectionName))
				throw new \Sys\Exception('The collection resource name was not set for this class => %s',
					$this->getClassName());
			return \Z::getResource($this->_resourceCollectionName);
		}

		/**
		 * Loads a record of this model by id
		 *
		 * @param <int> $id
		 * @return Model
		 */
		public function load($id = 0)
		{
			$this->_beforeLoad();
			$this->_getResource()->load($this, $id);
			$this->_afterLoad();
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
			$criteria['from'] = $this->_table;
			$collection = $this->_pdo->find($this->_className, $criteria);
			return $collection;
		}

		/**
		 * Save our model data
		 * 
		 * @return Model
		 */
		public function save()
		{
			$this->_beforeSave();
			$id = $this->_getResource()->save($this);
			$this->setId($id);
			$this->_afterSave();
			return $this;
		}

		/**
		 * Base event processing array for all models
		 * @return <array>
		 */
		protected function _getEventData()
		{
			return array('object' => $this);
		}

		protected function _beforeLoad()
		{
			\Z::dispatchEvent('model_load_before', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_load_before', $this->_getEventData());
			return $this;
		}

		protected function _afterLoad()
		{
			\Z::dispatchEvent('model_load_after', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_load_after', $this->_getEventData());
			return $this;
		}

		/**
		 * Called before a model is saved
		 */
		protected function _beforeSave()
		{
			\Z::dispatchEvent('model_save_before', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_save_before', $this->_getEventData());
			return $this;
		}

		/**
		 * Called after a model is saved
		 */
		protected function _afterSave()
		{
			\Z::dispatchEvent('model_save_before', $this->_getEventData());
			\Z::dispatchEvent($this->_eventPrefix.'_save_before', $this->_getEventData());
			return $this;
		}

		/**
		 * Called after data is commited to database
		 */
		protected function _afterSaveCommit()
		{
			return $this;
		}

		protected function _setResourceModel($resourceName, $resourceCollectionName = null)
		{
			$this->_resourceName = $resourceName;
			if ($resourceCollectionName === null)
			{
				$resourceCollectionName = $resourceName.'/collection';
			}
			$this->_resourceCollectionName = $resourceCollectionName;
		}

		protected function _getResource()
		{
			if (empty($this->_resourceName))
				throw new \Sys\Exception('The resource name => %s is not set', $this->_resourceName);
			return \Z::getResource($this->_resourceName);
		}

		public function getResource()
		{
			return $this->_getResource();
		}

		public function getIdField()
		{
			if ($this->_idField == '')
			{
				$this->_idField = $this->_getResource()->getIdField();
			}
			return $this->_idField;
		}

		public function getId()
		{
			if ($this->_idField != '')
			{
				return $this->getData($this->_idField);
			}
			else
				return $this->getData('id');
		}

		public function setId($id)
		{
			if ($this->_idField != '')
			{
				$this->setData($this->_idField, $id);
			}
			else
				$this->setData('id', $id);
			return $this;
		}

	}
}
