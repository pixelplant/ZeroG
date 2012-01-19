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
			$html = '<div class="grid-input-medium"><div class="range-line"><span class="label">'.$this->__('From').'</span> <input type="text" name="'.$this->getFieldName().'[from]" id="'.$this->getHtmlId().'_from" class="input-text"/></div>';
			$html .= '<div class="grid-input-medium"><span class="label">'.$this->__('To').'</span><input type="text" name="'.$this->getFieldName().'[to]" id="'.$this->getHtmlId().'_to" class="input-text"/></div></div>';
			return $html;
		}

		public function getCondition()
		{
			$value = $this->getValue();

			return $value;
		}
	}
}
