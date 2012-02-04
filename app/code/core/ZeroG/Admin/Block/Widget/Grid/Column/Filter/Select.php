<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Select Filter
	 *
	 * @author radu.mogos
	 */
	class Select extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Base
	{

		protected function _getOptions()
		{
			$noOptions = array('value' => null, 'label' => '');

			$definedOptions = $this->getColumn()->getOptions();

			if (!empty($definedOptions) && is_array($definedOptions))
			{
				$options = array($noOptions);
				foreach ($definedOptions as $value => $label)
				{
					$options[] = array('value' => $value, 'label' => $label);
				}
				return $options;
			}
			return array();
		}

		protected function _buildOption($option, $value)
		{
			$optionValue = (string)$option['value'];
			$selected = ($optionValue === $value) ? ' selected="selected"' : '';
			$html = '<option value="'.$option['value'].'" '.$selected.'>'.$option['label'].'</option>';
			return $html;
		}

		public function getContent()
		{
			$options = $this->_getOptions();
			$selectedValue = $this->getValue();

			$html = '<select class="select" name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'">';
			foreach ($options as $option)
			{
				$html .= $this->_buildOption($option, $selectedValue);
			}
			$html .= '</select>';
			return $html;
		}
	}
}
