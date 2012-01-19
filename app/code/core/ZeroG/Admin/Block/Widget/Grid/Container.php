<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid
{

	/**
	 * Description of Container Widget
	 *
	 * @author radu.mogos
	 */
	class Container extends \App\Code\Core\ZeroG\Admin\Block\Widget\Container
	{
		protected $_grid;

		public function setGrid($grid)
		{
			$this->_grid = $grid;
			return $this;
		}

		public function getGrid()
		{
			return $this->_grid;
		}
	}
}
