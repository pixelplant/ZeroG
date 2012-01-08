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
		protected $_column;

		/**
		 * The column this rendered belongs to
		 *
		 * @param <App\Code\ZeroG\Admin\Block\Widget\Grid\Column> $column
		 */

		protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/base.phtml');
		}

		public function setColumn($column)
		{
			$this->_column = $column;
			return $this;
		}

		public function getHeader()
		{
			return $this->_column->getData('header');
		}

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

		public function getContent($item)
		{
			$this->setItem($item);
			return $this->render();
		}
	}
}
