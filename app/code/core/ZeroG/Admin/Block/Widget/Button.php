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
		public function getContent()
		{
			$html = '<button id="'.$this->getId().'" class="'.$this->getClass().'">'.$this->getLabel().'</button>';
			return $html;
		}
	}
}
