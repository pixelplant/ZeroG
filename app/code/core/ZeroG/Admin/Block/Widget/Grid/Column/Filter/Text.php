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
			return '<div class="grid-input-medium"><input type="text" name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'" value="'.$this->getEscapedValue().'" class="input-text" /></div>';
			//$this->setItem($item);
			//return $this->render();
		}
	}
}
