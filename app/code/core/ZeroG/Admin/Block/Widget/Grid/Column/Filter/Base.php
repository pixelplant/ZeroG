<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Base Filter
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
			//$this->setTemplate('widget/grid/column/renderer/base.phtml');
		}

		public function setColumn($column)
		{
			$this->_column = $column;
			return $this;
		}

		public function getColumn()
		{
			return $this->_column;
		}

		public function getContent()
		{
			return '';
			//$this->setItem($item);
			//return $this->render();
		}
	}
}
