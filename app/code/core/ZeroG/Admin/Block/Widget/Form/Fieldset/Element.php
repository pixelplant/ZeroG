<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Form\Fieldset
{

	/**
	 * Description of Form Fieldset Element Widget
	 *
	 * @author radu.mogos
	 */
	class Element extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		protected $_fieldset;

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'input';
			/*$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);*/
			$this->setTemplate('widget/form/fieldset/element.phtml');
		}

		public function setFieldset($fieldset)
		{
			$this->_fieldset = $fieldset;
			return $this;
		}

		public function getFieldset()
		{
			return $this->_fieldset;
		}

		public function getHeader()
		{
			return $this->getLabel();
		}

		public function getContent()
		{
			//return $this->getData('content');
			return $this->render();
			//return '<input type="text" name="'.$this->getId().'" value="" />';
		}

		public function getHtmlId()
		{
			return 'form_element_'.$this->_id;
		}

		public function getElementHtml()
		{
			$this->addClass('select');
			$html = '<select id="'.$this->getHtmlId().'" name="'.$this->getIndex().'" class="select">'."\n";

			$value = $this->getValue();
			if (!is_array($value))
			{
				$value = array($value);
			}

			if ($values = $this->getValues())
			{
				foreach ($values as $key => $option)
				{
					if (!is_array($option))
					{
						$html.= $this->_optionToHtml(array(
							'value' => $key,
							'label' => $option),
								$value);
					}
					else if (is_array($option['value']))
					{
						$html.='<optgroup label="'.$option['label'].'">'."\n";
						foreach ($option['value'] as $groupItem)
						{
							$html.= $this->_optionToHtml($groupItem, $value);
						}
						$html.='</optgroup>'."\n";
					}
					else
					{
						$html.= $this->_optionToHtml($option, $value);
					}
				}
			}

			$html.= '</select>'."\n";
			return $html;
		}

		protected function _optionToHtml($option, $selected)
		{
			if (is_array($option['value']))
			{
				$html ='<optgroup label="'.$option['label'].'">'."\n";
				foreach ($option['value'] as $groupItem)
				{
					$html .= $this->_optionToHtml($groupItem, $selected);
				}
				$html .='</optgroup>'."\n";
			}
			else
			{
				$html = '<option value="'.$this->_escape($option['value']).'"';
				$html.= isset($option['title']) ? 'title="'.$this->_escape($option['title']).'"' : '';
				$html.= isset($option['style']) ? 'style="'.$option['style'].'"' : '';
				if (in_array($option['value'], $selected))
				{
					$html.= ' selected="selected"';
				}
				$html.= '>'.$this->_escape($option['label']). '</option>'."\n";
			}
			return $html;
		}
	}
}
