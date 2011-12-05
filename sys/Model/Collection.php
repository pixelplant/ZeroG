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

		protected $_totalRecords;

		public function __construct()
		{
			$this->_construct();
		}

		protected function _construct()
		{
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
		 * @return ArrayIterator
		 */
		public function getIterator()
		{
			$this->load();
			return new \ArrayIterator($this->_items);
		}

		/**
		 * Countable implementation
		 * @return <int>
		 */
		public function count()
		{
			$this->load();
			return \count($this->_items);
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

		/**
		 * Convert the collection items to XML
		 * 
		 * @return string
		 */
		public function toXml()
		{
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<collection>
				<totalRecords>'.$this->_totalRecords.'</totalRecords>
				<items>';
			foreach ($this as $item)
			{
				$xml.=$item->toXml();
			}
			$xml.= '</items>
				</collection>';
			return $xml;
		}
	}
}
