<?php

namespace Sys\Model
{
/**
 * Description of Abstract
 *
 * @author radu.mogos
 */
	class Collection implements \Countable, \IteratorAggregate
	{
		protected $_items = array();

		protected $_itemObjectClass = '\Sys\Model';

		protected $_isCollectionLoaded = false;

		protected $_eventPrefix = 'sys_collection';

		protected $_className;

		protected $_totalRecords;

		public function __construct()
		{
			$this->_construct();
		}

		protected function _construct()
		{
		}

		protected function _init($resourceName)
		{
			$this->_className = get_class($this);
		}

		/**
		 * Lazy loading function
		 */
		public function load()
		{
			//$this->_isCollectionLoaded = true;
		}

		/**
		 * Is the collection loaded?
		 * 
		 * @return <bool>
		 */
		public function isLoaded()
		{
			return $this->_isCollectionLoaded;
		}

		/**
		 * IteratorAggregate implementation
		 * 
		 * @return ArrayIterator
		 */
		public function getIterator()
		{
			$this->load();
			return new \ArrayIterator($this->_items);
		}

		/**
		 * Countable implementation
		 * 
		 * @return <int>
		 */
		public function count()
		{
			$this->load();
			return \count($this->_items);
		}

		/**
		 * Returns the number of items the collection has
		 *
		 * @return <int>
		 */
		public function getSize()
		{
			$this->load();
			if (is_null($this->_totalRecords))
			{
				$this->_totalRecords = count($this->getItems());
			}
			return $this->_totalRecords;
		}

		/**
		 * Return the items stored in the collection
		 *
		 * @return <array>
		 */
		public function getItems()
		{
			$this->load();
			return $this->_items;
		}

		/**
		 * Get the first item in the collection
		 *
		 * @return <\Sys\Model>
		 */
		public function getFirstItem()
		{
			$this->load();

			if (count($this->_items))
			{
				reset($this->_items);
				return current($this->_items);
			}

			return new $this->_itemObjectClass();
		}

		/**
		 * Get the last item in the collection
		 * 
		 * @return <\Sys\Model>
		 */
		public function getLastItem()
		{
			$this->load();

			if (count($this->_items))
			{
				return end($this->_items);
			}

			return new $this->_itemObjectClass();
		}

		public function getClassName()
		{
			return $this->_className;
		}

		/**
		 * Convert the collection items to XML
		 * 
		 * @return string
		 */
		public function toXml()
		{
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<collection>
				<totalRecords>'.$this->getSize().'</totalRecords>
				<items>';
			foreach ($this->getItems() as $_item)
			{
				$xml .= $_item->toXml();
			}
			$xml .= '</items>
				</collection>';
			return $xml;
		}
	}
}
