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
			return $noOptions;
		}

		protected function _buildOption($value, $label)
		{
			$html = '<option value="'.$value.'">'.$label.'</option>';
			return $html;
		}

		public function getContent()
		{
			$options = $this->_getOptions();

			$html = '<select name="'.$this->getFieldName().'" id="'.$this->getHtmlId().'">';
			foreach ($options as $option)
			{
				$html .= $this->_buildOption($option['value'], $option['label']);
			}
			$html .= '</select>';
			return $html;
		}
	}
}
