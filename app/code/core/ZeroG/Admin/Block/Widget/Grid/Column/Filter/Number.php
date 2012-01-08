<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Number filter
	 *
	 * @author radu.mogos
	 */
	class Number extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Base
	{

		public function getContent()
		{
			$html = '<div class="grid-input-small"><div class="range-line"><span class="label">'.$this->__('From').'</span> <input type="text" name=" class="input-text"/></div>';
			$html .= '<div class="grid-input-small"><span class="label">'.$this->__('To').'</span><input type="text" class="input-text"/></div></div>';
			return $html;
		}
	}
}
