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
			$html = '<div class="grid-input-medium"><div class="range-line"><span class="label">'.$this->__('From').'</span> <input type="text" name="'.$this->getFieldName().'[from]" id="'.$this->getHtmlId().'_from" class="input-text" value="'.$this->getEscapedValue('from').'"/></div>';
			$html .= '<div class="grid-input-medium"><span class="label">'.$this->__('To').'</span><input type="text" name="'.$this->getFieldName().'[to]" id="'.$this->getHtmlId().'_to" value="'.$this->getEscapedValue('to').'" class="input-text"/></div></div>';
			return $html;
		}

		public function getValue($index = null)
		{
			if (!is_null($index))
			{
				return $this->getData('value', $index);
			}

			$value = $this->getData('value');

			if ((isset($value['from']) && strlen($value['from']) > 0) || (isset($value['to']) && strlen($value['to']) > 0))
			{
				return $value;
			}
			return null;
    }

		public function getCondition()
		{
			$value = $this->getValue();

			return $value;
		}
	}
}
