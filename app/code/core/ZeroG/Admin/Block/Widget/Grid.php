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
		 * Paging variables
		 *
		 * @var <string>
		 */
		protected $_varNameLimit    = '_limit';
		protected $_varNamePage     = '_page';
		protected $_varNameSort     = '_sort';
		protected $_varNameDir      = '_dir';
		protected $_varNameFilter   = '_filter';

		protected $_defaultSort     = false;
		protected $_defaultDir      = 'desc';

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
		 * Container for the header menu
		 *
		 * @var <Container>
		 */
		protected $_headerContainer;

		/**
		 * Grid id, also used as the table id attribute
		 * @var <string>
		 */
		protected $_id;

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);
			$this->setTemplate('widget/grid.phtml');
			//$column->setData($columnData);
		}

		public function getHeader()
		{
			return $this->_headerContainer;
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

		protected function _setFilterValues($data)
		{
			foreach ($this->getColumns() as $columnId => $column)
			{
				if (isset($data[$columnId]) && (!empty($data[$columnId]) || strlen($data[$columnId]) > 0) && $column->getFilter())
				{
					$column->getFilter()->setValue($data[$columnId]);
					$this->_addColumnFilterToCollection($column);
				}
			}
			return $this;
		}
		
		protected function _addColumnFilterToCollection($column)
		{
			if ($this->getCollection())
			{
				$field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
				if ($column->getFilterConditionCallback())
				{
					call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
				}
				else
				{
					$cond = $column->getFilter()->getCondition();
					if ($field && isset($cond))
					{
						$this->getCollection()->addFieldToFilter($field , $cond);
					}
				}
			}
			return $this;
		}

		protected function _preparePage()
		{
			$this->getCollection()->setPage(
					$this->getParam($this->_varNamePage, $this->_defaultPage),
					$this->getParam($this->_varNameLimit, $this->_defaultLimit));
		}

		protected function _prepareCollection()
		{
			if ($this->getCollection())
			{
				$this->_preparePage();

				$sort   = $this->getParam($this->_varNameSort, $this->_defaultSort);
				$dir    = $this->getParam($this->_varNameDir, $this->_defaultDir);
				$filter = $this->getParam($this->_varNameFilter, null);

				if (is_null($filter))
				{
					$filter = array();
				}

				if (is_string($filter))
				{
					$data = $this->helper('admin')->prepareFilterString($filter);
					$this->_setFilterValues($data);
					//$this->_setFilterValues(array('post_id' => array('from' => 3, 'to' => 4)));
				}
				else if ($filter && is_array($filter))
				{
					$this->_setFilterValues($filter);
				}

				if (isset($this->_columns[$sort]) && $this->_columns[$sort]->getIndex())
				{
					$dir    = (strtolower($dir)=='desc') ? 'desc' : 'asc';
					$column = $this->_columns[$sort]->getFilterIndex() ?
							$this->_columns[$sort]->getFilterIndex() : $this->_columns[$sort]->getIndex();
					$this->_columns[$sort]->setSortDirection($dir);
					$this->getCollection()->addFieldToSort($column, $dir);
				}
			}

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
