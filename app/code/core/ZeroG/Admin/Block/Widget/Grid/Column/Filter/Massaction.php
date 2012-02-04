<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Massaction Filter
	 *
	 * @author radu.mogos
	 */
	class Massaction extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Select
	{

		protected function _getOptions()
		{
			$options = array(
				array('value' => '', 'label' => $this->__('All')),
				array('value' => 1,  'label' => $this->__('Yes')),
				array('value' => 0,  'label' => $this->__('No'))
			);
			return $options;
		}

		public function getContent()
		{
			$options = $this->_getOptions();
			$selectedValue = $this->getValue();

			$html = '<select name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'">';
			foreach ($options as $option)
			{
				$html .= $this->_buildOption($option, $selectedValue);
			}
			$html .= '</select>';
			return $html;
		}
	}
}
