<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Text Filter
	 *
	 * @author radu.mogos
	 */
	class Text extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Base
	{

		public function getContent()
		{
			return '<div class="grid-input-medium"><input type="text" name="" class="input-text" /></div>';
			//$this->setItem($item);
			//return $this->render();
		}
	}
}
