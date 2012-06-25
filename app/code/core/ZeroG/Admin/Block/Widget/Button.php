<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget
{

	/**
	 * Description of Container Widget
	 *
	 * @author radu.mogos
	 */
	class Button extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		protected function _construct()
		{
			//$this->setType("button");
		}

		public function getContent()
		{
			$html = '<button type="'.$this->getType().'" id="'.$this->getId().'" onclick="'.$this->getOnclick().'" class="'.$this->getClass().'">'.$this->getLabel().'</button>';
			return $html;
		}
	}
}
