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

		public function getHeaderText()
		{
			return 'Undefined';
		}

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
	}
}
