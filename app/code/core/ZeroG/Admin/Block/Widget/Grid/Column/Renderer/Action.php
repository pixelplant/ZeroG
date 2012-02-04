<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer
{

	/**
	 * Description of Grid Widget
	 *
	 * @author radu.mogos
	 */
	class Action extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Renderer\Base
	{

		protected function _construct()
		{
			$this->setTemplate('widget/grid/column/renderer/action.phtml');
		}

		public function getActions()
		{
			return $this->getColumn()->getActions();
		}

		public function getContent($item)
		{
			$this->setItem($item);
			return $this->render();
		}
	}
}
