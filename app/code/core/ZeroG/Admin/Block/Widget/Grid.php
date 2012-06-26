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
		 * Url called when the grid form is submitted
		 *
		 * @var <string>
		 */
		protected $_actionUrl = null;

		/**
		 * Url called when JavaScript is enabled and you have an AJAX method listener
		 */
		protected $_ajaxActionUrl = null;

		/**
		 * Paging variables
		 *
		 * @var <string>
		 */
		protected $_varNameLimit     = '_limit';
		protected $_varNamePage      = '_page';
		protected $_varNameSort      = '_sort';
		protected $_varNameDir       = '_dir';
		protected $_varNameFilter    = '_filter';
		protected $_varNameUrlEncode = '_eurl';

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
		 * Field name used for mass exports, etc..., usually this is linked to the id
		 * @var <string>
		 */
		protected $_massactionField = null;

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);
			$this->setTemplate('widget/grid.phtml');
			$this->_decodeUrl();
			//$column->setData($columnData);
		}

		public function setSaveParametersInSession($state = false)
		{
			if ($state === true)
			{
				//save grid filters/data in the session
			}
		}

		public function setActionUrl($path = null)
		{
			$this->_actionUrl = $this->getUrl($path);
			return $this;
		}

		/**
		 * Return the grid's form action url
		 */
		public function getActionUrl()
		{
			if (is_null($this->_actionUrl))
			{
				$this->setActionUrl();
			}
			return $this->_actionUrl;
		}

		public function setAjaxActionUrl($path = null)
		{
			$this->_ajaxActionUrl = $this->getUrl($path);
			return $this;
		}

		/**
		 * Return the grid's form AJAX action url
		 */
		public function getAjaxActionUrl()
		{
			return $this->_ajaxActionUrl;
		}

		protected function _decodeUrl()
		{
			if ($this->getParam($this->_varNameUrlEncode, null) !== null)
			{
				\Z::getRequest()->setParams(unserialize(urldecode($this->getParam($this->_varNameUrlEncode))));
			}
		}

		public function getEncodedUrl($params = array())
		{
			$urlParams[$this->_varNamePage]   = $this->getParam($this->_varNamePage, $this->_defaultPage);
			$urlParams[$this->_varNameLimit]  = $this->getParam($this->_varNameLimit, $this->_defaultLimit);
			$urlParams[$this->_varNameSort]   = $this->getParam($this->_varNameSort, $this->_defaultSort);
			$urlParams[$this->_varNameDir]    = $this->getParam($this->_varNameDir, $this->_defaultDir);
			$urlParams[$this->_varNameFilter] = $this->getParam($this->_varNameFilter, null);
			$urlParams = array_merge($urlParams, $params);
			return $this->getUrl('*/*/*', array($this->_varNameUrlEncode => urlencode(serialize($urlParams))));
		}

		/**
		 * Get grid header container
		 *
		 * @return <Container>
		 */
		public function getHeader()
		{
			return $this->_headerContainer;
		}

		/**
		 * Return grid columns
		 *
		 * @return <array>
		 */
		public function getColumns()
		{
			return $this->_columns;
		}

		/**
		 * How many columns do we have?
		 * 
		 * @return <type>
		 */
		public function getColumnCount()
		{
			return count($this->_columns);
		}

		/**
		 * Get the massaction field name
		 *
		 * @return <string>
		 */
		public function getMassactionField()
		{
			return $this->_massactionField;
		}

		/**
		 * Set the massaction field name
		 * 
		 * @param <string> $field
		 * @return Grid
		 */
		public function setMassactionField($field)
		{
			$this->_massactionField = $field;
			return $this;
		}

		/**
		 * Get the massaction block name
		 *
		 * @return <string>
		 */
		public function getMassactionBlockName()
		{
			return 'admin/widget/grid/massaction';
		}

		/**
		 * Get the massaction block instance
		 *
		 * @return <Grid\Massaction>
		 */
		public function getMassactionBlock()
		{
			return $this->getChild('massaction');
		}

		/**
		 * Create the massaction block
		 *
		 * @return Grid
		 */
		public function _prepareMassactionBlock()
		{
			$this->setChild('massaction', 
					$this->getLayout()->createBlock($this->getMassactionBlockName()));
			$this->_prepareMassaction();
			if($this->getMassactionBlock()->isAvailable())
			{
				$this->_prepareMassactionColumn();
			}
			return $this;
		}

		protected function _prepareMassactionColumn()
		{
			$columnId = 'massaction';
			$massactionColumn = $this->getLayout()->createBlock('admin/widget/grid/column')
				->setData(array(
					'index'     => $this->getMassactionField(),
					'type'      => 'massaction',
					'name'      => $this->getMassactionBlock()->getFormFieldName(),
					'align'     => 'center',
					'width'     => '50px',
					'header'    => '',
					'is_system' => true
					));

			if ($this->getNoFilterMassactionColumn())
			{
				$massactionColumn->setData('filter', false);
			}

			$massactionColumn->setSelected($this->getMassactionBlock()->getSelected())
				->setGrid($this)
				->setId($columnId);

			$oldColumns = $this->_columns;
			$this->_columns = array();
			$this->_columns[$columnId] = $massactionColumn;
			$this->_columns = array_merge($this->_columns, $oldColumns);
			return $this;
		}

		/**
		 * Normally you would overwrite this method in your grid, to add
		 * additional items in the massaction dropdown list
		 * 
		 * @return Grid
		 */
		protected function _prepareMassaction()
		{
			return $this;
		}

		/**
		 * Add a new column to our grid
		 *
		 * @param <string> $columnId
		 * @param <array> $columnData
		 * @return Grid
		 */
		public function addColumn($columnId, $columnData)
		{
			if (is_array($columnData))
			{
				$this->_columns[$columnId] = $this->getLayout()->createBlock('admin/widget/grid/column')
						->setData($columnData)
						->setGrid($this)
						->setId($columnId);
			}
			else
			{
				throw new \Sys\Exception("The grid column data should be specified as an array.");
			}
			//$this->_columns[$columnId]->setId($columnId);
			return $this;
		}

		/**
		 * Set the grid collection
		 *
		 * @param <Collection> $collection
		 * @return Grid
		 */
		public function setCollection($collection)
		{
			$this->_collection = $collection;
			return $this;
		}

		/**
		 * Get the current collection used by the grid
		 *
		 * @return <Collection>
		 */
		public function getCollection()
		{
			return $this->_collection;
		}

		/**
		 * Populate the values used by the filters
		 *
		 * @param <array> $data
		 * @return Grid
		 */
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

		/**
		 * Apply the different filter conditions to our collection
		 *
		 * @param <Column> $column
		 * @return Grid
		 */
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

		/**
		 * Set grid and collection paging data
		 */
		protected function _preparePage()
		{
			$this->getCollection()->setPage(
					$this->getParam($this->_varNamePage, $this->_defaultPage),
					$this->getParam($this->_varNameLimit, $this->_defaultLimit));
		}

		/**
		 * Apply filters and sort our collection
		 * 
		 * @return Grid
		 */
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

		/**
		 * Prepare our grid
		 *
		 * @return Grid
		 */
		protected function _prepareGrid()
		{
			$this->_prepareColumns();
			$this->_prepareMassactionBlock();
			$this->_prepareCollection();
			return $this;
		}

		/**
		 * Called before the Grid is rendered
		 */
		protected function _beforeToHtml()
		{
			$this->_prepareGrid();
		}

		public function getHtmlId()
		{
			return 'grid_'.$this->_id;
		}
	}
}
