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

		public function getValue($index = null)
		{
			if ($index)
			{
				if ($data = $this->getData('value', $index))
				{
					return $data;//date('Y-m-d', strtotime($data));
				}
				return null;
			}

			return $this->getData('value');
		}

		public function getCondition()
		{
			$value = $this->getValue();

			return $value;
		}
	}
}
