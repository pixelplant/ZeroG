<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Date Filter
	 *
	 * @author radu.mogos
	 */
	class Date extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Base
	{

		protected function _construct()
		{
			$this->setTemplate('widget/grid/column/filter/date.phtml');
		}

		public function getContent()
		{
			return $this->render();
			//return '<div class="grid-input-medium"><input type="text" name="" class="input-text" /></div>';
		}
	}
}
