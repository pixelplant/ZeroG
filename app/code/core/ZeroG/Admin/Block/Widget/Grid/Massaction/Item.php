<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Massaction
{

	/**
	 * Description of Massaction Item block
	 *
	 * @author radu.mogos
	 */
	class Item extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		protected $_massaction;

		protected function _construct()
		{
		}

		public function setMassaction($massaction)
		{
			$this->_massaction = $massaction;
			return $this;
		}

		public function getMassaction()
		{
			return $this->_massaction;
		}
	}
}
