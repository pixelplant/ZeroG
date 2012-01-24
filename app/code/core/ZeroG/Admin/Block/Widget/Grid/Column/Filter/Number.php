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
			$startId =  $this->getHtmlId().'_from';
			$endId   =  $this->getHtmlId().'_to';

			$html  = '<div class="grid-input-medium"><div class="range-line"><label for="'.$startId.'">'.$this->__('From').'</label><input type="text" name="'.$this->getFieldName().'[from]" id="'.$startId.'" class="input-text" value="'.$this->getEscapedValue('from').'"/></div></div>';
			$html .= '<div class="grid-input-medium"><div class="range-line"><label for="'.$endId.'">'.$this->__('To').'</label><input type="text" name="'.$this->getFieldName().'[to]" id="'.$endId.'" value="'.$this->getEscapedValue('to').'" class="input-text"/></div></div>';
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
