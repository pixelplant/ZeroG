<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer
{

	/**
	 * Description of Grid Widget
	 *
	 * @author radu.mogos
	 */
	class Base extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * The column this rendered belongs to
		 *
		 * @param <App\Code\ZeroG\Admin\Block\Widget\Grid\Column> $column
		 */
		protected $_column;

		protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/base.phtml');
		}

		/**
		 * Set the parent column this renderer belongs to
		 *
		 * @param <App\Code\ZeroG\Admin\Block\Widget\Grid\Column> $column
		 * @return Base
		 */
		public function setColumn($column)
		{
			$this->_column = $column;
			return $this;
		}

		/**
		 * Returns the parent column
		 * 
		 * @return <App\Code\ZeroG\Admin\Block\Widget\Grid\Column>
		 */
		public function getColumn()
		{
			return $this->_column;
		}

		/**
		 * Return column html header
		 *
		 * @return <string>
		 */
		public function getHeader()
		{
			if ($this->_column->getSortable())
			{
				$sortClass = 'column-no-sorting';
				$dir = strtolower($this->getColumn()->getSortDirection());
				if ($dir)
				{
					$sortClass = 'column-sort-'.$dir;
				}
				return '<a href="#" class="'.$sortClass.'" name="'.$this->_column->getId().'">'
						.$this->_column->getData('header').'</a>';
			}
			else
			{
				return '<p class="no-link">'.$this->_column->getData('header').'</p>';
			}
		}

		/**
		 * Return the styles for the header
		 * 
		 * @return string
		 */
		public function getHeaderProperty()
		{
			$html = '';
			if ($this->_column->getWidth())
			{
				$width = $this->_column->getWidth();
				$html = ' width="'.$width.'"';
			}
			return $html;
		}

		/**
		 * Returns the Rendered content
		 *
		 * @param <\Sys\Model> $item
		 * @return <string> Html code to show in the data column
		 */
		public function getContent($item)
		{
			$this->setItem($item);
			return $this->render();
		}
	}
}
