<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid
{

	/**
	 * Description of Grid Column Widget
	 *
	 * @author radu.mogos
	 */
	class Column extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		/**
		 * The parent grid
		 * @var <type>
		 */
		protected $_grid;

		/**
		 * The block renderer instance
		 * @var <type>
		 */
		protected $_renderer;

		/**
		 * The column filter used in the grid header
		 * @var <\Sys\Layout\Block>
		 */
		protected $_filter;

		/**
		 * Set the column's parent grid
		 *
		 * @param <type> $grid
		 * @return Column
		 */
		public function setGrid($grid)
		{
			$this->_grid = $grid;
			return $this;
		}

		public function getGrid()
		{
			return $this->_grid;
		}

		public function getHeader()
		{
			return $this->getRenderer()->getHeader();
		}

		/**
		 * Retrieve the renderer's content
		 *
		 * @param <\Sys\Model> $item Object parsed to the renderer
		 * @return <string> Returns the processed html code based on the input object
		 */
		public function getContent($item)
		{
			if ($this->getIndex())
			{
				return $item->getData($this->getIndex());
			}
			else
			{
				return $this->getRenderer()->getContent($item);
			}
		}

		public function getHeaderProperty()
		{
			return $this->getRenderer()->getHeaderProperty();
		}

		/**
		 * Return the filter block class
		 *
		 * @return <\Sys\Layout\Block>
		 */
		public function getFilter()
		{
			if (!$this->_filter)
			{
				if (!$this->getData('filter'))
				{
					$filterClass = $this->_getFilterByType();
				}
				else
				{
					$filterClass = $this->getData('filter');
				}
				$this->_filter = $this->getLayout()->createBlock($filterClass)
						->setColumn($this);
			}
			return $this->_filter;
		}

		/**
		 * Return the filter html code for the grid
		 *
		 * @return <string>
		 */
		public function getFilterHtml()
		{
			if ($this->getFilter())
			{
				return $this->getFilter()->getContent();
			}
			else
			{
				return '&nbsp;';
			}
		}

		protected function _getFilterByType()
		{
			$type = strtolower($this->getType());

			switch ($type)
			{
				case 'text' :
					$filterClass = 'admin/widget/grid/column/filter/base';
					break;
				case 'checkbox' :
					$filterClass = 'admin/widget/grid/column/filter/checkbox';
					break;
				case 'date' :
					$filterClass = 'admin/widget/grid/column/filter/date';
					break;
				case 'select' :
					$filterClass = 'admin/widget/grid/column/filter/select';
					break;
				case 'number' :
					$filterClass = 'admin/widget/grid/column/filter/number';
					break;
				case 'site' :
					$filterClass = 'admin/widget/grid/column/filter/site';
					break;
				default:
					$filterClass = 'admin/widget/grid/column/filter/text';
					break;
			}

			return $filterClass;
		}

		public function getRenderer()
		{
			if (!$this->_renderer)
			{
				$renderer = $this->getData('renderer');
				if (!$renderer)
				{
					$renderer = $this->_getDefaultRenderer();
				}
				$this->_renderer = $this->getLayout()->createBlock($renderer)
						->setColumn($this);
			}
			return $this->_renderer;
		}

		protected function _getDefaultRenderer()
		{
			return 'admin/widget/grid/column/renderer/base';
		}
	}
}
