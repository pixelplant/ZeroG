<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget
{

	/**
	 * Description of Grid Widget
	 *
	 * @author radu.mogos
	 */
	class Grid extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		/**
		 * Grid columns
		 * @var <array>
		 */
		protected $_columns = array();

		/**
		 * The grid collection
		 * @var <\Sys\Collection>
		 */
		protected $_collection;

		/**
		 * Grid collection default limit per page
		 * @var <int>
		 */
		protected $_defaultLimit = 20;

		/**
		 * Grid collection default start page
		 * @var <int>
		 */
		protected $_defaultPage = 1;

		/**
		 * Grid id, also used as the table id attribute
		 * @var <string>
		 */
		protected $_id;

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			$this->setTemplate('widget/grid.phtml');
			//$column->setData($columnData);
		}

		public function getId()
		{
			return $this->_id;
		}

		public function setId($id)
		{
			$this->_id = $id;
			return $this;
		}

		public function getColumns()
		{
			return $this->_columns;
		}

		public function getColumnCount()
		{
			return count($this->_columns);
		}

		public function addColumn($columnId, $columnData)
		{
			if (is_array($columnData))
			{
				$this->_columns[$columnId] = $this->getLayout()->createBlock('admin/widget/grid/column')
						->setData($columnData)
						->setGrid($this);
			}
			else
			{
				throw new \Sys\Exception("The grid column data should be specified as an array.");
			}
			$this->_columns[$columnId]->setId($columnId);
			return $this;
		}

		public function setCollection($collection)
		{
			$this->_collection = $collection;
			return $this;
		}

		public function getCollection()
		{
			return $this->_collection;
		}

		protected function _prepareCollection()
		{
			return $this;
		}

		protected function _prepareColumns()
		{
			return $this;
		}

		protected function _prepareGrid()
		{
			$this->_prepareColumns();
			$this->_prepareCollection();
			return $this;
		}

		protected function _beforeToHtml()
		{
			$this->_prepareGrid();
		}
	}
}
