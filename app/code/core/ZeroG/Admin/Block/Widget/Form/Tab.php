<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Form
{

	/**
	 * Description of Form Widget
	 *
	 * @author radu.mogos
	 */
	class Tab extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * The parent form this tab belongs to
		 * 
		 * @var <Form>
		 */
		protected $_form;

		protected $_items = array();

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			/*$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);*/
			//$this->setTemplate('widget/form.phtml');
		}

		public function setForm($form)
		{
			$this->_form = $form;
			return $this;
		}

		public function getHeader()
		{
			return $this->getLabel();
		}

		public function getContent()
		{
			//return $this->getData('content');
			//return $this->render();
			//return 'empty';
			$html = '';
			if ($this->_items)
			{
				foreach ($this->_items as $_item)
				{
					$html .= $_item->getContent();
				}
			}
			return $html;
		}

		protected function _addFieldset($name, $label)
		{
			$this->_items[$name] = $this->getLayout()->createBlock('admin/widget/form/fieldset')
					->setLabel($label)
					->setTab($this);
			return $this->_items[$name];
		}

		public function getHtmlId()
		{
			return 'tab_'.$this->_id;
		}

		public function getItems()
		{
			return $this->_items;
		}
	}
}
