<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget
{

	/**
	 * Description of Form Widget
	 *
	 * @author radu.mogos
	 */
	class Form extends \App\Code\Core\ZeroG\Admin\Block\Widget
	{
		/**
		 * Form tabs
		 *
		 * @var <array>
		 */
		protected $_tabs = null;

		/**
		 * Buttons used on the form
		 * @var <type>
		 */
		protected $_buttons = array();

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			/*$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);*/
			$this->setTemplate('widget/form.phtml');
			$this->_prepareForm();
		}

		protected function _prepareForm()
		{
			$this->addButton('save', array('type' => 'button', 'class' => 'ui-icon-disk', 'label' => $this->helper('admin')->__('Save')));
		}

		/**
		 * Add a new tab to the form
		 *
		 * @param <string> $name Tab name
		 * @param <string> $type Tab block type
		 * @param <array> $data  Tab data
		 */
		public function addTab($name, $type, $data)
		{
			$this->_tabs[$name] = $this->getLayout()->createBlock($type)
					->setId($name)
					->setData($data)
					->setForm($this);
			return $this;
		}

		/**
		 * Return current tabs
		 * @return <array>
		 */
		public function getTabs()
		{
			return $this->_tabs;
		}

		/**
		 * Get the header text used by the form
		 * @return <type>
		 */
		public function getHeaderText()
		{
			return 'Undefined';
		}

		/**
		 * Get this forms id
		 * @return <type>
		 */
		public function getHtmlId()
		{
			return 'form_'.$this->_id;
		}

		public function setValues($data)
		{
			foreach ($this->_tabs as $_tab)
			{
				foreach ($_tab->getItems() as $_item)
				{
					foreach ($_item->getChildren() as $_child)
					{
						if (isset($data[$_child->getIndex()]))
							$_child->setValue($data[$_child->getIndex()]);
					}
				}
			}
		}

		/**
		 * Get all buttons defined for this form
		 * @return <type>
		 */
		public function getButtons()
		{
			return $this->_buttons;
		}

		/**
		 * Remove the button from the form
		 * @param <string> $name
		 * @return Form
		 */
		public function removeButton($name)
		{
			unset($this->_buttons[$name]);
			return $this;
		}

		/**
		 * Add a new button to the form
		 * @param <string> $name Button name
		 * @param array $data Button data to be passed to the block
		 * @return Form
		 */
		public function addButton($name, $data)
		{
			$data['id'] = $name;
			$this->_buttons[$name] = $this->getLayout()->createBlock('admin/widget/button')->setData($data);
			return $this;
		}

		/**
		 * Render all the container buttons
		 *
		 * @return <string>
		 */
		public function getButtonsHtml()
		{
			$html = '';
			foreach ($this->_buttons as $button)
			{
				$html .= $button->getContent();
			}
			return $html;
		}
	}
}
