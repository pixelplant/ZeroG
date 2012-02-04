<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Checkbox Filter
	 *
	 * @author radu.mogos
	 */
	class Checkbox extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Select
	{

		protected function _getOptions()
		{
			return array(
				array(
					'value' => null,
					'label' => $this->__('Any')
				),
				array(
					'value' => '1',
					'label' => $this->__('Yes')
				),
				array(
					'value' => '0',
					'label' => $this->__('No')
				)
			);
		}

	}
}
