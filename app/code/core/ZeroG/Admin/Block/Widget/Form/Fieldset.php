<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Form
{

	/**
	 * Description of Form Fieldset Widget
	 *
	 * @author radu.mogos
	 */
	class Fieldset extends \App\Code\Core\ZeroG\Admin\Block\Template
	{
		/**
		 * The parent tab this fieldset belongs to
		 *
		 * @var <Tab>
		 */
		protected $_tab;

		//protected $_elements;

		protected function _construct()
		{
			parent::_construct();
			$this->_id = 'default';
			/*$this->_headerContainer = $this->getLayout()
					->createBlock('admin/widget/grid/container')
					->setGrid($this);*/
			$this->setTemplate('widget/form/fieldset.phtml');
		}

		public function setTab($tab)
		{
			$this->_tab = $tab;
			return $this;
		}

		public function getTab()
		{
			return $this->_tab;
		}

		public function getHeader()
		{
			return $this->getLabel();
		}

		public function getContent()
		{
			//return $this->getData('content');
			return $this->render();
			//return 'empty';
		}

		public function addElement($name, $data)
		{
			$this->addChild($this->getLayout()->createBlock('admin/widget/form/fieldset/element')
					->setId($name)
					->setData($data));
			/*$this->_elements[$name] = $this->getLayout()->createBlock('admin/widget/form/fieldset/element')
					->setData($data);*/
			return $this;
		}

		/*protected function _getRendererByType()
		{
			$type = $this->getType();

			switch ($type)
			{
				case 'text':
				default:
					$class = 'admin/widget/form/fieldset/element/base';
					break;
			}

			return $class;
		}*/

		public function getElements()
		{
			return $this->_elements;
		}

		public function getHtmlId()
		{
			return 'fieldset_'.$this->_id;
		}
	}
}
