<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer
{

	/**
	 * Description of Massaction Column Renderer
	 *
	 * @author radu.mogos
	 */
	class Massaction extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer\Base
	{

		/*protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/action.phtml');
		}*/

		public function getContent($item)
		{
			return '<input type="checkbox" name="'.$this->getColumn()->getIndex().'" value="'.$item->getId().'" />';
			//var_dump($this->getColumn()->getData());
			//echo $this->__($item->getData($this->getColumn()->getIndex()));
		}
	}
}
