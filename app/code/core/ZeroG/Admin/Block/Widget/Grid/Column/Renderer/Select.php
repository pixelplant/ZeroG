<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer
{

	/**
	 * Description of Select Column Renderer
	 *
	 * @author radu.mogos
	 */
	class Select extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer\Base
	{

		/*protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/action.phtml');
		}*/

		public function getContent($item)
		{
			$values = $this->getColumn()->getOptions();
			return $this->__($values[$item->getData($this->getColumn()->getIndex())]);
		}
	}
}
