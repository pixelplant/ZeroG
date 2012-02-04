<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer
{

	/**
	 * Description of Checkbox Column Renderer
	 *
	 * @author radu.mogos
	 */
	class Checkbox extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer\Base
	{

		/*protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/action.phtml');
		}*/

		public function getContent($item)
		{
			var_dump($this->getColumn()->getData());
			echo $this->__($item->getData($this->getColumn()->getIndex()));
		}
	}
}
